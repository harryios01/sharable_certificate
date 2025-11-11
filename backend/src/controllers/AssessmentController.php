<?php

namespace App\Controllers;

use App\Models\Assessment;
use App\Models\Question;
use App\Utils\Response;
use App\Utils\UUIDGenerator;

class AssessmentController
{
    private $assessmentModel;
    private $questionModel;

    public function __construct()
    {
        $this->assessmentModel = new Assessment();
        $this->questionModel = new Question();
    }

    public function start()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $technology = $data['technology'] ?? null;

        if (!in_array($technology, ['Ruby', 'PHP'])) {
            Response::error('Invalid technology. Must be Ruby or PHP', 400);
        }

        $userId = $GLOBALS['currentUser']['userId'] ?? null;
        $guestSession = null;

        if (!$userId) {
            $guestSession = UUIDGenerator::generate();
        }

        $assessment = $this->assessmentModel->create($userId, $technology, $guestSession);

        if (!$assessment) {
            Response::serverError('Failed to create assessment');
        }

        Response::success([
            'assessmentId' => $assessment['id'],
            'technology' => $assessment['technology'],
            'sessionToken' => $guestSession
        ], 'Assessment started successfully', 201);
    }

    public function getQuestions()
    {
        $technology = $_GET['technology'] ?? null;

        if (!in_array($technology, ['Ruby', 'PHP'])) {
            Response::error('Invalid technology', 400);
        }

        $questions = $this->questionModel->getByTechnology($technology);

        Response::success(['questions' => $questions]);
    }

    public function submitAnswer()
    {
        $data = json_decode(file_get_contents('php://input'), true);

        $assessmentId = $data['assessmentId'] ?? null;
        $questionNumber = $data['questionNumber'] ?? null;
        $answer = $data['answer'] ?? null;

        if (!$assessmentId || !$questionNumber || !$answer) {
            Response::error('Missing required fields', 400);
        }

        $assessment = $this->assessmentModel->findById($assessmentId);

        if (!$assessment) {
            Response::notFound('Assessment not found');
        }

        // Verify ownership (user or guest session)
        $userId = $GLOBALS['currentUser']['userId'] ?? null;
        if ($assessment['user_id'] && (!$userId || $userId != $assessment['user_id'])) {
            Response::forbidden('Not authorized to modify this assessment');
        }

        $success = $this->assessmentModel->saveAnswer($assessmentId, $questionNumber, $answer);

        if (!$success) {
            Response::serverError('Failed to save answer');
        }

        // Check if all 10 questions are answered
        $answers = $this->assessmentModel->getAnswers($assessmentId);
        $isComplete = count($answers) >= 10;

        if ($isComplete && $questionNumber == 10) {
            $this->assessmentModel->markCompleted($assessmentId);
        }

        Response::success([
            'saved' => true,
            'completed' => $isComplete,
            'nextQuestion' => $questionNumber < 10 ? $questionNumber + 1 : null
        ]);
    }

    public function getAssessment()
    {
        $assessmentId = $_GET['id'] ?? null;

        if (!$assessmentId) {
            Response::error('Assessment ID required', 400);
        }

        $assessment = $this->assessmentModel->findById($assessmentId);

        if (!$assessment) {
            Response::notFound('Assessment not found');
        }

        $answers = $this->assessmentModel->getAnswers($assessmentId);

        Response::success([
            'assessment' => $assessment,
            'answers' => $answers
        ]);
    }

    public function assignToUser()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $assessmentId = $data['assessmentId'] ?? null;

        if (!$assessmentId) {
            Response::error('Assessment ID required', 400);
        }

        $userId = $GLOBALS['currentUser']['userId'];
        $assessment = $this->assessmentModel->findById($assessmentId);

        if (!$assessment) {
            Response::notFound('Assessment not found');
        }

        if ($assessment['user_id']) {
            Response::error('Assessment already assigned to a user', 400);
        }

        $this->assessmentModel->assignToUser($assessmentId, $userId);

        Response::success(['assigned' => true]);
    }
}
