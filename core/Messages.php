<?php

namespace Core;


final class Messages
{

    private $db;

    public function __construct(ConnectDB $db)
    {
        $this->db = $db;
    }

    public function addMessage($message, $name, $email, $avatar_name)
    {
        try {
            $stmt = $this->db->prepare('INSERT INTO `messages` SET `message` = :message, `name` = :name, `email` = :email, `add_date` = :add_date, `avatar` = :avatar');
            $stmt->bindValue(':message', $message, \PDO::PARAM_STR);
            $stmt->bindValue(':name', $name, \PDO::PARAM_STR);
            $stmt->bindValue(':email', $email, \PDO::PARAM_STR);
            $stmt->bindValue(':add_date', date('Y-m-d h:m:s', time()), \PDO::PARAM_STR);
            $stmt->bindValue(':avatar', $avatar_name, \PDO::PARAM_STR);
            $stmt->execute();
        } catch (\PDOException $exc) {
            return $exc->getMessage();
        }
    }

    public function getMessage()
    {
        try {
            $stmt = $this->db->prepare('SELECT * FROM `messages`');
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (\PDOException $exc) {
            return $exc->getMessage();
        }
    }
    
    public function notActiveMessages() {
        try {
            $stmt = $this->db->prepare('SELECT * FROM `messages` WHERE `active` = 0');
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (\PDOException $exc) {
            return $exc->getMessage();
        }
    }

    public function activateMessage($id) {
        try {
            $stmt = $this->db->prepare('UPDATE `messages` SET `active` = 1 WHERE `id` = :id');
            $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
            $stmt->execute();
        } catch(\PDOException $exc) {
            return $exc->getMessage();
        }
    }
    
    public function deleteMessage($id) {
        try {
            $stmt = $this->db->prepare('DELETE FROM `messages` WHERE `id` = :id');
            $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->rowCount();
        } catch(\PDOException $exc) {
            return $exc->getMessage();
        }
    }
    
    public function activateAllMessages() {
        try {
            $stmt = $this->db->prepare('UPDATE `messages` SET `active` = 1');
            $stmt->execute();
        } catch(\PDOException $exc) {
            return $exc->getMessage();
        }
    }

    public function deleteNotActiveAllMessages() {
        try {
            $stmt = $this->db->prepare('DELETE FROM `messages` WHERE `active` = 0');
            $stmt->execute();
        } catch (\PDOException $exc) {
            return $exc->getMessage();
        }
    }
}