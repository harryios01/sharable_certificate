<?php

namespace App\Models;

use App\Config\Database;
use PDO;

class User
{
    private $db;
    private $table = 'users';

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function findById($id)
    {
        $query = "SELECT * FROM {$this->table} WHERE id = :id LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function findByEmail($email)
    {
        $query = "SELECT * FROM {$this->table} WHERE email = :email LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function findByGoogleId($googleId)
    {
        $query = "SELECT * FROM {$this->table} WHERE google_id = :google_id LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':google_id', $googleId);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function findByInstagramId($instagramId)
    {
        $query = "SELECT * FROM {$this->table} WHERE instagram_id = :instagram_id LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':instagram_id', $instagramId);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function create($data)
    {
        $query = "INSERT INTO {$this->table}
                  (google_id, instagram_id, name, email, profile_picture, provider)
                  VALUES (:google_id, :instagram_id, :name, :email, :profile_picture, :provider)";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':google_id', $data['google_id']);
        $stmt->bindParam(':instagram_id', $data['instagram_id']);
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':profile_picture', $data['profile_picture']);
        $stmt->bindParam(':provider', $data['provider']);

        if ($stmt->execute()) {
            return $this->findById($this->db->lastInsertId());
        }

        return false;
    }

    public function update($id, $data)
    {
        $fields = [];
        $params = [':id' => $id];

        foreach ($data as $key => $value) {
            if (in_array($key, ['google_id', 'instagram_id', 'name', 'email', 'profile_picture', 'provider', 'total_assessments'])) {
                $fields[] = "{$key} = :{$key}";
                $params[":{$key}"] = $value;
            }
        }

        if (empty($fields)) {
            return false;
        }

        $query = "UPDATE {$this->table} SET " . implode(', ', $fields) . " WHERE id = :id";
        $stmt = $this->db->prepare($query);

        return $stmt->execute($params);
    }

    public function updateLastLogin($id)
    {
        $query = "UPDATE {$this->table} SET last_login_at = NOW() WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function getUserCertificates($userId)
    {
        $query = "SELECT * FROM certificates WHERE user_id = :user_id ORDER BY issued_date DESC";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getUserStats($userId)
    {
        $user = $this->findById($userId);
        if (!$user) {
            return null;
        }

        $certQuery = "SELECT COUNT(*) as total, technology
                      FROM certificates
                      WHERE user_id = :user_id
                      GROUP BY technology";
        $stmt = $this->db->prepare($certQuery);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        $certStats = $stmt->fetchAll();

        return [
            'user' => $user,
            'total_certificates' => array_sum(array_column($certStats, 'total')),
            'certificates_by_technology' => $certStats
        ];
    }
}
