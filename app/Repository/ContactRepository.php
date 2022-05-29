<?php

/**
 * @author FernandoDefez <fernandodefez@outlook.com>
 */

namespace FernandoDefez\Agenda\App\Repository;

use FernandoDefez\Agenda\App\Database;
use FernandoDefez\Agenda\App\Model\Contact;

class ContactRepository {

    private Database $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    /**
     * This method performs the contact creation
     *
     * @param Contact $contact
     * @return bool
     */
    public function create(Contact $contact) : bool
    {
        $sql = "INSERT INTO public.contacts (name, lastname, email, phone, thumbnail)  
                VALUES (?,?,?,?,?)";

        $stmt = $this->db->connect()->prepare($sql);
        $stmt->execute(
            [
                $contact->getName(),
                $contact->getLastname(),
                $contact->getEmail(),
                $contact->getPhone(),
                $contact->getThumbnail()
            ]
        );
        return $stmt->rowCount() == 1;
    }

    /**
     * This method retrieves a contact information based on it's id
     *
     * @param int $id
     * @return array
     */
    public function get(int $id) : array
    {
        $sql = "SELECT * FROM contacts WHERE id = :id";
        $stmt = $this->db->connect()->prepare($sql);
        $stmt->execute(['id' => $id]);

        $contact = [];

        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $contact = [
                'id' => $row['id'],
                'name' => $row['name'],
                'lastname' => $row['lastname'],
                'email' => $row['email'],
                'phone' => $row['phone'],
                'thumbnail' => $row['thumbnail'],
            ];
        }
        return $contact;
    }

    /**
     * Find all contacts
     *
     * @return array
     */
    public function findAll() : array
    {
        $sql = "SELECT * FROM contacts";
        $stmt = $this->db->connect()->query($sql);
        $contacts = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $contacts[] = [
                'id' => $row['id'],
                'name' => $row['name'],
                'lastname' => $row['lastname'],
                'email' => $row['email'],
                'phone' => $row['phone'],
                'thumbnail' => $row['thumbnail'],
            ];
        }
        return $contacts;
    }

    /**
     * Updates a specified contact
     * and return true or false whether it was updated
     *
     * @param Contact $contact
     * @return bool
     */
    #public function update(Contact $contact) : bool;

    /**
     * Deletes a specified contact
     * and return true or false whether it was deleted
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id) : bool
    {
        $sql = "DELETE FROM contacts WHERE id = :id";
        $stmt = $this->db->connect()->prepare($sql);
        $stmt->execute(['id' => $id]);
        $row = $stmt->rowCount();
        return $row == 1;
    }
}