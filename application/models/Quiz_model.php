<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Quiz_model extends CI_Model {
    // Get all questions for a given category
    public function get_questions_by_category($category)
    {
        $db = get_db();
        $cat = str_replace("'", "''", trim($category));
        $sql = "SELECT * FROM quiz_question WHERE category = '" . $cat . "' ORDER BY id ASC";
        return $this->get_data($sql);
    }

    // Get all answers for a given question (for MCQ)
    public function get_answers_by_question($question_id)
    {
        $db = get_db();
        $escaped_qid = (int)$question_id;
        $sql = "SELECT * FROM quiz_question_answers WHERE Qustion_no = $escaped_qid ORDER BY id ASC";
        return $this->get_data($sql);
    }

    public function __construct()
    {
        parent::__construct();
        require_once APPPATH . 'helpers/database_helper.php';
    }

    // Generic helper to run SQL and return array
    public function get_data($sql)
    {
        $result = db_query($sql); // executes the query
        $data = array();
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }
    
    // Get distinct quiz types
    public function get_quiz_types()
    {
        $sql = "SELECT DISTINCT quiztype FROM quiz_info";
        return $this->get_data($sql);
    }

    // Get quizzes by type (returning id and quiz name)
    public function get_quizzes_by_type($quiztype)
    {
        $db = get_db();
        $escaped_type = "'" . $db->escape($quiztype) . "'"; 

        $sql = "SELECT id, Quiz_name FROM quiz_info WHERE quiztype = $escaped_type";
        return $this->get_data($sql);
    }
}
?>