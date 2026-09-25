<?php
/**
 * Quiz Editor Controller - Inline editing for quiz questions with MathML support
 */
class Quiz_editor extends CI_ProtectedController {
    public function __construct()
    {
        parent::__construct();
        require_once APPPATH . 'helpers/common_helper.php';
        $this->load->model('Question_model', 'question_model');
        $this->load->model('Quiz_model', 'quiz_model');
    }

    /**
     * List all questions by category for inline editing
     * URL: /quiz_editor/index/{category}
     */
    public function index($category = null)
    {
        if (!$category) {
            $categories = $this->question_model->get_distinct_categories();
            $data = array(
                'title' => 'Quiz Editor - Select Category',
                'categories' => $categories
            );
            $this->load->view('quiz_editor/select_category', $data);
            return;
        }

        // Get all questions for this category
        $questions = $this->quiz_model->get_questions_by_category($category);
        
        // Get answers for each question
        foreach ($questions as &$q) {
            $q['answers'] = $this->quiz_model->get_answers_by_question($q['id']);
        }

        $data = array(
            'title' => 'Edit Quiz Questions - ' . htmlspecialchars($category),
            'category' => $category,
            'questions' => $questions
        );
        $this->load->view('quiz_editor/edit_questions', $data);
    }

    /**
     * Save question edit via AJAX
     * URL: POST /quiz_editor/save_question
     */
    public function save_question()
    {
        header('Content-Type: application/json');
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'error' => 'Invalid method']);
            return;
        }

        $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
        $html = isset($_POST['html']) ? $_POST['html'] : '';

        if ($id <= 0) {
            echo json_encode(['success' => false, 'error' => 'Invalid question ID']);
            return;
        }

        // Basic sanitation: remove null bytes
        $html = str_replace("\0", '', $html);

        $ok = $this->question_model->update_quiz_question_html($id, $html);
        
        if ($ok) {
            echo json_encode(['success' => true, 'message' => 'Question saved successfully']);
        } else {
            echo json_encode(['success' => false, 'error' => 'Failed to save question']);
        }
    }

    /**
     * Save answer edit via AJAX
     * URL: POST /quiz_editor/save_answer
     */
    public function save_answer()
    {
        header('Content-Type: application/json');
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'error' => 'Invalid method']);
            return;
        }

        $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
        $html = isset($_POST['html']) ? $_POST['html'] : '';

        if ($id <= 0) {
            echo json_encode(['success' => false, 'error' => 'Invalid answer ID']);
            return;
        }

        // Basic sanitation
        $html = str_replace("\0", '', $html);

        // Update answer in quiz_question_answers table
        require_once APPPATH . 'helpers/database_helper.php';
        $db = get_db();
        $data = array('question_answer' => $html);
        $where = array('id' => $id);
        $ok = $db->update('quiz_question_answers', $data, $where);
        
        if ($ok) {
            echo json_encode(['success' => true, 'message' => 'Answer saved successfully']);
        } else {
            echo json_encode(['success' => false, 'error' => 'Failed to save answer']);
        }
    }
}
