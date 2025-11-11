<?php

namespace App\Models;

use App\Config\Database;
use PDO;

class Assessment
{
    private $db;
    private $table = 'assessments';
    private $answersTable = 'assessment_answers';

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function create($userId, $technology, $guestSession = null)
    {
        $query = "INSERT INTO {$this->table} (user_id, technology, guest_session)
                  VALUES (:user_id, :technology, :guest_session)";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':technology', $technology);
        $stmt->bindParam(':guest_session', $guestSession);

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

    public function saveAnswer($assessmentId, $questionNumber, $answer)
    {
        $query = "INSERT INTO {$this->answersTable} (assessment_id, question_number, answer)
                  VALUES (:assessment_id, :question_number, :answer)
                  ON DUPLICATE KEY UPDATE answer = :answer, answered_at = CURRENT_TIMESTAMP";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':assessment_id', $assessmentId, PDO::PARAM_INT);
        $stmt->bindParam(':question_number', $questionNumber, PDO::PARAM_INT);
        $stmt->bindParam(':answer', $answer);

        return $stmt->execute();
    }

    public function getAnswers($assessmentId)
    {
        $query = "SELECT * FROM {$this->answersTable}
                  WHERE assessment_id = :assessment_id
                  ORDER BY question_number ASC";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':assessment_id', $assessmentId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function markCompleted($assessmentId)
    {
        $query = "UPDATE {$this->table}
                  SET status = 'completed', completed_at = CURRENT_TIMESTAMP
                  WHERE id = :id";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $assessmentId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function assignToUser($assessmentId, $userId)
    {
        $query = "UPDATE {$this->table} SET user_id = :user_id WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':id', $assessmentId, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
