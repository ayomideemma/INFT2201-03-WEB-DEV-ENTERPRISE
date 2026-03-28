<?php
namespace Application;

use PDO;

class Mail
{
    protected $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // 1. Save mail with a specific userId
    public function createMail($name, $message, $userId)
    {
        // 👇 Changed "userId" column to "userid"
        $stmt = $this->db->prepare("INSERT INTO mail (name, message, userid) VALUES (:name, :message, :userId)");
        $stmt->execute(['name' => $name, 'message' => $message, 'userId' => (int)$userId]);

        return $this->db->lastInsertId();
    }

    // 2. Fetch mail optionally filtered by a userId
    public function listMail($userId = null) 
    {
        if ($userId !== null) {
            // 👇 Changed "userId" column to "userid"
            $stmt = $this->db->prepare("SELECT id, name, message, userid FROM mail WHERE userid = :userId ORDER BY id");
            $stmt->execute(['userId' => (int)$userId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            // 👇 Changed "userId" column to "userid"
            $result = $this->db->query("SELECT id, name, message, userid FROM mail ORDER BY id");
            return $result->fetchAll(PDO::FETCH_ASSOC);
        }
    }
}