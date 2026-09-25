<?php
/**
 * Question Model for MathML questions
 */
class Question_model extends CI_Model {
    protected $table = 'questions';
    protected $quizTable = 'quiz_question';
    protected $answersTable = 'quiz_question_answers';

    public function __construct()
    {
        parent::__construct();
        require_once APPPATH . 'helpers/database_helper.php';
    }

    public function get_question($id)
    {
        $db = get_db();
        $id = (int)$id;
        $sql = "SELECT * FROM {$this->table} WHERE id = {$id} LIMIT 1";
        $res = $db->query($sql);
        return $db->get_row($res);
    }

    public function update_question_mathml($id, $mathml)
    {
        $db = get_db();
        $id = (int)$id;
        $data = array(
            'question_mathml' => $mathml,
            'modified_at' => date('Y-m-d H:i:s')
        );
        $where = array('id' => $id);
        return $db->update($this->table, $data, $where);
    }

    /**
     * Quiz module: distinct categories
     */
    public function get_distinct_categories()
    {
        $db = get_db();
        $sql = "SELECT DISTINCT category FROM {$this->quizTable} WHERE category IS NOT NULL AND category <> '' ORDER BY category";
        $res = $db->query($sql);
        $rows = $db->get_result($res);
        $out = array();
        if (!empty($rows)) {
            foreach ($rows as $r) {
                $out[] = $r['category'];
            }
        }
        return $out;
    }

    /**
     * Quiz module: list questions with optional category filter
     */
    public function get_quiz_questions($category = null, $limit = 50, $offset = 0)
    {
        $db = get_db();
        $limit = max(1, (int)$limit);
        $offset = max(0, (int)$offset);
        $where = '';
        if ($category !== null && $category !== '') {
            // Simple escape by doubling single quotes
            $cat = str_replace("'", "''", trim($category));
            $where = "WHERE category = '" . $cat . "'";
        }
        $sql = "SELECT id, question_name, category, mark, penalty, level, qtype FROM {$this->quizTable} {$where} ORDER BY id DESC LIMIT {$limit} OFFSET {$offset}";
        $res = $db->query($sql);
        return $db->get_result($res);
    }

    /**
     * Quiz module: count questions for pagination
     */
    public function count_quiz_questions($category = null)
    {
        $db = get_db();
        $where = '';
        if ($category !== null && $category !== '') {
            $cat = str_replace("'", "''", trim($category));
            $where = "WHERE category = '" . $cat . "'";
        }
        $sql = "SELECT COUNT(*) AS cnt FROM {$this->quizTable} {$where}";
        $res = $db->query($sql);
        $row = $db->get_row($res);
        return $row ? (int)$row['cnt'] : 0;
    }

    /**
     * Quiz module: get one question by id
     */
    public function get_quiz_question($id)
    {
        $db = get_db();
        $id = (int)$id;
        $sql = "SELECT * FROM {$this->quizTable} WHERE id = {$id} LIMIT 1";
        $res = $db->query($sql);
        return $db->get_row($res);
    }

    /**
     * Quiz module: get answers for a question
     */
    public function get_quiz_answers($questionId)
    {
        $db = get_db();
        $questionId = (int)$questionId;
        $sql = "SELECT id, Qustion_no, question_answer, fraction, rownumbers FROM {$this->answersTable} WHERE Qustion_no = {$questionId} ORDER BY id ASC";
        $res = $db->query($sql);
        return $db->get_result($res);
    }

    /**
     * Quiz module: update question_name HTML/content
     */
    public function update_quiz_question_html($id, $html)
    {
        $db = get_db();
        $id = (int)$id;
        $data = array(
            'question_name' => $html
        );
        $where = array('id' => $id);
        return $db->update($this->quizTable, $data, $where);
    }

    /**
     * Create a new quiz question (minimal fields, other fields optional)
     */
    public function create_quiz_question($data)
    {
        $db = get_db();
        $defaults = array(
            'discription' => '',
            'correctoption' => '',
            'category' => '',
            'question_name' => '',
            'mark' => '1',
            'penalty' => '0',
            'level' => '1',
            'rownumbers' => null,
            'qtype' => 'MCQ',
            'correct_answer' => null,
            'grace' => 0,
        );
        $row = array_merge($defaults, $data);
        return $db->insert($this->quizTable, $row);
    }

    /**
     * Update quiz question metadata (non-HTML fields)
     */
    public function update_quiz_question_meta($id, $data)
    {
        $db = get_db();
        $id = (int)$id;
        $allowed = array('category','mark','penalty','level','qtype','discription','correctoption','correct_answer','grace');
        $filtered = array();
        foreach ($allowed as $k) {
            if (array_key_exists($k, $data)) { $filtered[$k] = $data[$k]; }
        }
        if (empty($filtered)) return false;
        return $db->update($this->quizTable, $filtered, array('id' => $id));
    }

    /**
     * Delete quiz question and related data
     */
    public function delete_question($id)
    {
        $db = get_db();
        $id = (int)$id;
        
        // Delete answers first
        $db->delete($this->answersTable, array('qid' => $id));
        
        // Delete question
        return $db->delete($this->quizTable, array('id' => $id));
    }}