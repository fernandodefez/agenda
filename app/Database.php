<?php

/**
 * @author Fernando Defez <fernandodefez@outlook.com>
 */

namespace FernandoDefez\Agenda\App;


use PDO;
use PDOException;

class Database {

    private string $database;

    public function __construct()
    {
        $this->database = "postgres://vscxmzqqbltbch:b35477ad5c313605c67dd82fae0a5ddd3ba6a09472228900724b4704560003c3@ec2-34-227-120-79.compute-1.amazonaws.com:5432/ddt7640qr5skkm";
    }

    public function connect(): PDO
    {
        try{
            $conn = new PDO($this->database);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $conn->prepare(
                "
                CREATE TABLE IF NOT EXISTS contacts (
                    id INTEGER PRIMARY KEY,
                    name VARCHAR(50) NOT NULL,
                    lastname VARCHAR(60) NOT NULL,
                    email VARCHAR(150) NOT NULL,
                    phone VARCHAR(10) NOT NULL,
                    thumbnail VARCHAR(250) NOT NULL
                )
                ")->execute();
            return $conn;
        } catch(PDOException $exception) {
            throw new PDOException("Something went wrong when trying to connect to the database");
        }
    }
}