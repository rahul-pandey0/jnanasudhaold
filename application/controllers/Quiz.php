<?php
/**
 * Quiz Controller
 * Shows MCQ test for students by category
 */
class Quiz extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Quiz_model', 'quiz_model');
    }

    /**
     * Show quiz for a given category
     * URL: /quiz/index/<category_id>
     */
    public function index($category_id = null) {
        if (!$category_id) {
            show_error('Category is required', 400);
        }
        // Fetch all questions for this category
        $questions = $this->quiz_model->get_questions_by_category($category_id);
        // For each question, fetch answers
        foreach ($questions as &$q) {
            $q['answers'] = $this->quiz_model->get_answers_by_question($q['id']);
        }
        $data = array(
            'title' => 'Quiz',
            'questions' => $questions,
            'category_id' => $category_id
        );
        $this->load->view('quiz/quiz_view', $data);
    }
}
