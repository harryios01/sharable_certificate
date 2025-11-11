<?php

namespace App\Models;

use App\Config\Database;
use PDO;

class Question
{
    private $db;
    private $table = 'questions';

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getByTechnology($technology)
    {
        $query = "SELECT * FROM {$this->table}
                  WHERE technology = :technology
                  ORDER BY question_number ASC";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':technology', $technology);
        $stmt->execute();

        $questions = $stmt->fetchAll();

        // Parse JSON options
        foreach ($questions as &$question) {
            if ($question['options']) {
                $question['options'] = json_decode($question['options'], true);
            }
        }

        return $questions;
    }

    public function getByTechnologyAndNumber($technology, $questionNumber)
    {
        $query = "SELECT * FROM {$this->table}
                  WHERE technology = :technology AND question_number = :question_number
                  LIMIT 1";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':technology', $technology);
        $stmt->bindParam(':question_number', $questionNumber, PDO::PARAM_INT);
        $stmt->execute();

        $question = $stmt->fetch();

        if ($question && $question['options']) {
            $question['options'] = json_decode($question['options'], true);
        }

        return $question;
    }
}
