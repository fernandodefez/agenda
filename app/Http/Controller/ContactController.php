<?php

/**
 * @author Fernando Defez <fernandodefez@outlook.com>
 */

namespace FernandoDefez\Agenda\App\Http\Controller;

use FernandoDefez\Agenda\App\Image;
use FernandoDefez\Agenda\App\Model\Contact;
use FernandoDefez\Agenda\App\Repository\ContactRepository;

class ContactController {

    const NAME_MAX_LENGTH = 50;
    const LASTNAME_MAX_LENGTH = 60;
    const EMAIL_MAX_LENGTH = 150;
    const PHONE_MAX_LENGTH = 10;
    const THUMBNAIL_MAX_LENGTH = 500000;
    const MAX_CONTACTS = 15;

    public function index()
    {
        include('resources/views/index.php');
    }

    public function find($id)
    {
        $contactRepository = new ContactRepository();
        $contact = $contactRepository->get($id);
        echo json_encode($contact);
    }

    public function show()
    {
        header('Content-Type: application/json');
        $contacts = (new ContactRepository())->findAll();
        echo json_encode($contacts);
    }

    public function store()
    {
        header('Content-Type: application/json');

        $errors = [];
        $data = [];

        $name = (isset($_POST['name'])) ? $this->sanitize($_POST['name']) : '';
        $lastname = (isset($_POST['lastname'])) ? $this->sanitize($_POST['lastname']) : '';
        $phone = (isset($_POST['phone'])) ? $this->sanitize($_POST['phone']) : '';
        $email = (isset($_POST['email'])) ? $this->sanitize($_POST['email']) : '';

        if (empty($name)) {
            $errors['name'] = "This field is required";
        } else if(!preg_match("/^[\p{L}-]*$/u", $name)) {
            $errors['name'] = "This field only allows letters";
        } else if (strlen($name) > self::NAME_MAX_LENGTH){
            $errors['name'] = "The field has to be " . self::MAX_CONTACTS . " chars long";
        }

        if (empty($lastname)) {
            $errors['lastname'] = "This field is required";
        } else if(!preg_match("/^[\p{L}-]*$/u", $lastname)) {
            $errors['lastname'] = "This field only allows letters";
        } else if (strlen($lastname) > self::LASTNAME_MAX_LENGTH){
            $errors['lastname'] = "The field has to be " . self::LASTNAME_MAX_LENGTH . " chars long";
        }

        if(!empty($_FILES['thumbnail']['name'])) {
            if (!empty($_FILES['thumbnail']['tmp_name'])) {
                $fileName = basename($_FILES["thumbnail"]["name"]);
                $imageFileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

                $check = getimagesize($_FILES["thumbnail"]["tmp_name"]);
                if (!($check !== false)) {
                    $errors['thumbnail'] = "This file must be an image";
                }
                if ($_FILES["thumbnail"]["size"] > self::THUMBNAIL_MAX_LENGTH) {
                    $errors['thumbnail'] = "This image is too large.";
                }
                if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
                    $errors['thumbnail'] = "This field only allows these extensions: JPG, JEPG y PNG";
                }
            } else {
                $errors['thumbnail'] = "This image is too large.";
            }
        } else {
            $errors['thumbnail'] = "This field is required";
        }

        if (empty($phone)) {
            $errors['phone'] = "This field is required";
        } else if (!preg_match("/^[0-9]{3}[0-9]{4}[0-9]{3}$/", $phone)) {
            $errors['phone'] = "This phone is invalid";
        } else if (strlen($phone) != self::PHONE_MAX_LENGTH){
            $errors['phone'] = "This field has to be " . self::PHONE_MAX_LENGTH . " chars long";
        }

        if (empty($email)) {
            $errors['email'] = "This field is required";
        } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "This email is invalid";
        } else if (strlen($email) > self::EMAIL_MAX_LENGTH){
            $errors['email'] = "The field has to be " . self::EMAIL_MAX_LENGTH . " chars long";
        }

        $contactRepository = new ContactRepository();
        $contacts = $contactRepository->findAll();
        if ((count($contacts) >= self::MAX_CONTACTS)) {
            $errors['out_of_bounds'] = "You are only allowed to create up to " . self::MAX_CONTACTS . " contacts";
        }

        if (!empty($errors)) {
            $data['success'] = false;
            $data['errors'] = $errors;
        } else {
            $filename = basename($_FILES["thumbnail"]["name"]);
            $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            $time = str_replace('-', '', date("d-m-Y").time());
            $thubmnail = $time . "." . $extension;

            $contact = new Contact(-1, $name, $lastname, $thubmnail, $phone, $email);

            $data['message'] = ($contactRepository->create($contact) && Image::make($_FILES['thumbnail'], $thubmnail))
                ? "Contact was successfully created" : "Contact was not created";

            $data['success'] = true;
        }

        echo json_encode($data);
    }

    public function destroy()
    {
        $errors = [];
        $data = [];

        parse_str(file_get_contents('php://input'), $content);
        $data['content'] = $content;
        if (!isset($content['id'])) {
            $errors['id'] = "The id is required";
        } else {
            if (!(is_numeric($content['id']))) {
                $errors['id'] = "The id must be a number";
            }

            if (!($content['id'] > 0)) {
                $errors['id'] = "The id value must be greater than 0";
            }
        }

        if (!empty($errors)) {
            $data['success'] = false;
            $data['errors'] = $errors;
        } else {
            $contactRepository = new ContactRepository();
            $contact = $contactRepository->get($content['id']);

            Image::destroy($contact['thumbnail']);

            $contactRepository->delete($content['id']);

            $data['success'] = true;
            $data['message'] = "Success!";
            $data['contact'] = $contact;
        }
        echo json_encode($data);
    }

    public function sanitize($var) { return htmlspecialchars(trim($var)); }
}
