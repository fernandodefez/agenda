<?php

/**
 * @author Fernando Defez <fernandodefez@outlook.com>
 */

namespace FernandoDefez\Agenda\App;

use PDO;
use PDOException;

class Database {

    private string $host;
    private string $port;
    private string $database;
    private string $username;
    private string $password;

    public function __construct()
    {
        //$this->database = localhost;
        $this->host     =   "";
        $this->port     =   "";
        $this->database =   "";
        $this->username =   "";
        $this->password =   "";
    }

    public function connect() : PDO
    {
        try{
            $conn = new PDO("pgsql:host=$this->host;port=$this->port;dbname=$this->database;user=$this->username;password=$this->password");
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $conn->prepare(
                "
                CREATE TABLE IF NOT EXISTS public.contacts (
                    id INTEGER PRIMARY KEY,
                    name VARCHAR(50) NOT NULL,
                    lastname VARCHAR(60) NOT NULL,
                    email VARCHAR(150) NOT NULL,
                    phone VARCHAR(10) NOT NULL,
                    thumbnail VARCHAR(250) NOT NULL
                )")->execute();
            return $conn;
        } catch(PDOException $exception) {
            throw new PDOException("Something went wrong when trying to connect to the database");
        }
    }
}