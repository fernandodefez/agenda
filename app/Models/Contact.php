<?php

/**
 * @author Fernando Defez <fernandodefez@outlook.com>
*/

namespace FernandoDefez\Agenda\App\Models;

class Contact {

    private int $id;
    private string $name;
    private string $lastname;
    private string $thumbnail;
    private string $phone;
    private string $email;

    /**
     * @param int $id
     * @param string $name
     * @param string $lastname
     * @param string $thumbnail
     * @param string $phone
     * @param string $email
     */
    public function __construct(int $id = -1, string $name, string $lastname, string $thumbnail, string $phone, string $email)
    {
        $this->id = $id;
        $this->name = $name;
        $this->lastname = $lastname;
        $this->thumbnail = $thumbnail;
        $this->phone = $phone;
        $this->email = $email;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * @param string $lastname
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
    }

    /**
     * @return string
     */
    public function getThumbnail()
    {
        return $this->thumbnail;
    }

    /**
     * @param string $thumbnail
     */
    public function setThumbnail($thumbnail)
    {
        $this->thumbnail = $thumbnail;
    }

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }
}