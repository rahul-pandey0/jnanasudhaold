<?php
/**
 * Questions Controller - MathML inline editing
 */
class Questions extends CI_ProtectedController {
    public function __construct()
    {
        parent::__construct();
        require_once APPPATH . 'helpers/common_helper.php';
        $this->load->model('Question_model', 'question_model');
    }

    /**
     * List quiz questions with category filter
     * URL: /questions?category=...
     */
    public function index()
    {
        $category = isset($_GET['category']) ? trim($_GET['category']) : '';
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 25;
        $limit = $limit > 0 ? $limit : 25;
        $page = $page > 0 ? $page : 1;
        $offset = ($page - 1) * $limit;

        $categories = $this->question_model->get_distinct_categories();
        // Only load questions after a category is selected
        $questions = array();
        $total = 0;
        if ($category !== '') {
            $questions = $this->question_model->get_quiz_questions($category, $limit, $offset);
            $total = $this->question_model->count_quiz_questions($category);
        }

        $data = array(
            'title' => 'Quiz Questions',
            'categories' => $categories,
            'selected_category' => $category,
            'questions' => $questions,
            'total' => $total,
            'limit' => $limit,
            'current_page' => $page
        );
        $this->load->view('questions/list', $data);
    }

    /**
     * Edit MathML question inline
     * URL: /questions/edit_mathml/{id}
     */
    public function edit_mathml($id = null)
    {
        if ($id === null) { show_error('Question id required', 400); }
        $question = $this->question_model->get_question($id);
        if (!$question) { show_error('Question not found', 404); }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $mathml = isset($_POST['mathml']) ? trim($_POST['mathml']) : '';
            if ($mathml === '') {
                $_SESSION['error'] = 'MathML content cannot be empty';
                redirect(base_url('questions/edit_mathml/' . (int)$id));
                return;
            }
            if ($this->question_model->update_question_mathml($id, $mathml)) {
                $_SESSION['success'] = 'Question updated successfully';
            } else {
                $_SESSION['error'] = 'Failed to update question';
            }
            redirect(base_url('questions/edit_mathml/' . (int)$id));
            return;
        }

        $data = array(
            'title' => 'Edit MathML Question #' . (int)$id,
            'question' => $question
        );
        $this->load->view('questions/edit_mathml', $data);
    }

    /**
     * Update quiz question HTML inline (question_name)
     * URL: POST /questions/update_quiz_html
     */
    public function update_quiz_html()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { show_error('Invalid method', 405); }
        $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
        $html = isset($_POST['html']) ? $_POST['html'] : '';
        if ($id <= 0) {
            $_SESSION['error'] = 'Invalid question id';
            redirect(base_url('questions'));
            return;
        }
        // Basic sanitation: trim dangerous null bytes
        $html = str_replace("\0", '', $html);
        $ok = $this->question_model->update_quiz_question_html($id, $html);
        if ($ok) {
            $_SESSION['success'] = 'Question updated';
        } else {
            $_SESSION['error'] = 'Failed to update question';
        }
        redirect(base_url('questions'));
    }

    /**
     * Create quiz question
     * GET: render form, POST: insert
     */
    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $category = isset($_POST['category']) ? trim($_POST['category']) : '';
            $qtype = isset($_POST['qtype']) ? trim($_POST['qtype']) : 'MCQ';
            $mark = isset($_POST['mark']) ? trim($_POST['mark']) : '1';
            $penalty = isset($_POST['penalty']) ? trim($_POST['penalty']) : '0';
            $level = isset($_POST['level']) ? trim($_POST['level']) : '1';
            $question_name = isset($_POST['html']) ? $_POST['html'] : '';
            $question_name = str_replace("\0", '', $question_name);
            $ok = $this->question_model->create_quiz_question(array(
                'category' => $category,
                'qtype' => $qtype,
                'mark' => $mark,
                'penalty' => $penalty,
                'level' => $level,
                'question_name' => $question_name,
            ));
            if ($ok) { $_SESSION['success'] = 'Question created'; } else { $_SESSION['error'] = 'Failed to create question'; }
            redirect(base_url('questions'));
            return;
        }
        $data = array(
            'title' => 'Create Quiz Question',
            'mode' => 'create',
            'question' => null
        );
        $this->load->view('questions/create_edit_quiz', $data);
    }

    /**
     * Edit full quiz question
     * URL: /questions/edit_quiz/{id}
     */
    public function edit_quiz($id = null)
    {
        if ($id === null) { show_error('Question id required', 400); }
        $q = $this->question_model->get_quiz_question($id);
        if (!$q) { show_error('Question not found', 404); }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $category = isset($_POST['category']) ? trim($_POST['category']) : '';
            $qtype = isset($_POST['qtype']) ? trim($_POST['qtype']) : 'MCQ';
            $mark = isset($_POST['mark']) ? trim($_POST['mark']) : '1';
            $penalty = isset($_POST['penalty']) ? trim($_POST['penalty']) : '0';
            $level = isset($_POST['level']) ? trim($_POST['level']) : '1';
            $question_name = isset($_POST['html']) ? $_POST['html'] : '';
            $question_name = str_replace("\0", '', $question_name);
            $ok1 = $this->question_model->update_quiz_question_meta($id, array(
                'category' => $category,
                'qtype' => $qtype,
                'mark' => $mark,
                'penalty' => $penalty,
                'level' => $level,
            ));
            $ok2 = $this->question_model->update_quiz_question_html($id, $question_name);
            if ($ok1 || $ok2) { $_SESSION['success'] = 'Question updated'; } else { $_SESSION['error'] = 'No changes saved'; }
            redirect(base_url('questions'));
            return;
        }
        $data = array(
            'title' => 'Edit Quiz Question #' . (int)$id,
            'mode' => 'edit',
            'question' => $q
        );
        $this->load->view('questions/create_edit_quiz', $data);
    }

    /**
     * Delete quiz question
     * URL: POST /questions/delete
     */
    public function delete()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { show_error('Invalid method', 405); }
        $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
        if ($id <= 0) {
            $_SESSION['error'] = 'Invalid question id';
            redirect(base_url('questions'));
            return;
        }
        $ok = $this->question_model->delete_question($id);
        if ($ok) {
            $_SESSION['success'] = 'Question deleted successfully';
        } else {
            $_SESSION['error'] = 'Failed to delete question';
        }
        redirect(base_url('questions'));
    }}