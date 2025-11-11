<?php

namespace App\Models;

use App\Config\Database;
use PDO;

class Certificate
{
    private $db;
    private $table = 'certificates';

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function create($data)
    {
        $query = "INSERT INTO {$this->table}
                  (certificate_id, user_id, user_name, user_email, profile_picture,
                   technology, assessment_id, pdf_url, image_url, qr_code_url, verification_url)
                  VALUES (:certificate_id, :user_id, :user_name, :user_email, :profile_picture,
                          :technology, :assessment_id, :pdf_url, :image_url, :qr_code_url, :verification_url)";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':certificate_id', $data['certificate_id']);
        $stmt->bindParam(':user_id', $data['user_id'], PDO::PARAM_INT);
        $stmt->bindParam(':user_name', $data['user_name']);
        $stmt->bindParam(':user_email', $data['user_email']);
        $stmt->bindParam(':profile_picture', $data['profile_picture']);
        $stmt->bindParam(':technology', $data['technology']);
        $stmt->bindParam(':assessment_id', $data['assessment_id'], PDO::PARAM_INT);
        $stmt->bindParam(':pdf_url', $data['pdf_url']);
        $stmt->bindParam(':image_url', $data['image_url']);
        $stmt->bindParam(':qr_code_url', $data['qr_code_url']);
        $stmt->bindParam(':verification_url', $data['verification_url']);

        if ($stmt->execute()) {
            return $this->findById($this->db->lastInsertId());
        }

        return false;
    }

    public function findById($id)
    {
        $query = "SELECT * FROM {$this->table} WHERE id = :id LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function findByCertificateId($certificateId)
    {
        $query = "SELECT * FROM {$this->table} WHERE certificate_id = :certificate_id LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':certificate_id', $certificateId);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function findByUserId($userId, $filters = [])
    {
        $query = "SELECT * FROM {$this->table} WHERE user_id = :user_id";

        if (!empty($filters['technology'])) {
            $query .= " AND technology = :technology";
        }

        $query .= " ORDER BY issued_date DESC";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);

        if (!empty($filters['technology'])) {
            $stmt->bindParam(':technology', $filters['technology']);
        }

        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function incrementShareCount($certificateId)
    {
        $query = "UPDATE {$this->table} SET share_count = share_count + 1 WHERE certificate_id = :certificate_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':certificate_id', $certificateId);
        return $stmt->execute();
    }

    public function incrementVerificationCount($certificateId)
    {
        $query = "UPDATE {$this->table} SET verification_count = verification_count + 1 WHERE certificate_id = :certificate_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':certificate_id', $certificateId);
        return $stmt->execute();
    }

    public function delete($id, $userId)
    {
        $query = "DELETE FROM {$this->table} WHERE id = :id AND user_id = :user_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
