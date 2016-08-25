<?php

namespace Core;


class ConnectDB extends \PDO
{


    public function __construct()
    {
        try {
            parent::__construct("mysql:host=localhost;dbname=guestbook", 'root', '', [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC
            ]);

        } catch (PDOException $exc) {
            echo $exc->getMessage();
        }
    }
}