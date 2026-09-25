<?php
/*
 * Student & Teacher REST API
 * Base URL: /api/student/...  /api/teacher/...
 * Auth: Authorization: Bearer <jwt>  (all endpoints)
 *
 * student_quiz_result actual columns:
 *   user_id, quiz_id, st_id, rank,
 *   physicsattempted, chemistryattempted, biologyattempted,
 *   physicscorrect,  chemistrycorrect,  biologycorrect,
 *   physicswrong,    chemistrywrong,    biologywrong,
 *   physicsmark,     chemistrymark,     biologymark
 *
 * quiz_info actual columns: id, Quiz_name, quiztype, No_of_question, Time_limit
 * (no subject_name, no time_taken, no end_time, no session_status)
 */
class Student_api extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        require_once APPPATH . 'helpers/common_helper.php';
        require_once APPPATH . 'helpers/database_helper.php';
        require_once APPPATH . 'helpers/jwt_helper.php';

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    private function _input()
    {
        $raw  = file_get_contents('php://input');
        $body = $raw ? json_decode($raw, true) : null;
        return is_array($body) ? $body : $_POST;
    }

    private function _require_jwt()
    {
        $payload = jwt_from_request();
        if (!$payload) {
            header('Content-Type: application/json');
            http_response_code(401);
            echo json_encode(array('success' => false, 'message' => 'Unauthorized: valid JWT required'));
            exit;
        }
        return $payload;
    }

    private function _json($data, $code = 200)
    {
        header('Content-Type: application/json');
        http_response_code($code);
        echo json_encode($data);
        exit;
    }

    /* Resolve student's user_name (= phone, used as FK in student_quiz_result) */
    private function _username($db, $uid)
    {
        $res = $db->query("SELECT user_name FROM user_details WHERE user_id = " . (int)$uid . " LIMIT 1");
        $row = $res ? $db->get_row($res) : null;
        return $row ? $row['user_name'] : '';
    }

    /* Collation-safe equality fragment for user_id join (utf8mb4 server) */
    private function _uid_eq($db, $uname)
    {
        return "sqr.user_id COLLATE utf8_general_ci = '" . $db->escape($uname) . "' COLLATE utf8_general_ci";
    }

    // ---------------------------------------------------------------
    // GET /api/student/home
    // ---------------------------------------------------------------
    public function home()
    {
        $jwt = $this->_require_jwt();
        $uid = (int)$jwt['user_id'];
        $db  = get_db();

        $uname = $this->_username($db, $uid);

        $pkg_res = $db->query(
            "SELECT COUNT(DISTINCT package_id) AS total
             FROM subscription_details
             WHERE username = '" . $db->escape($uname) . "'"
        );
        $pkg_row   = $pkg_res ? $db->get_row($pkg_res) : null;
        $pkg_total = $pkg_row ? (int)$pkg_row['total'] : 0;

        $stats_res = $db->query(
            "SELECT COUNT(*) AS total_attempts,
                    ROUND(AVG(physicsmark + chemistrymark + biologymark), 0) AS avg_score
             FROM student_quiz_result
             WHERE user_id  = '" . $db->escape($uname) . "'"
        );
        $stats = $stats_res ? $db->get_row($stats_res) : null;

        $this->_json(array(
            'success'    => true,
            'packages'   => array('total' => $pkg_total),
            'quiz_stats' => array(
                'total_attempts' => $stats ? (int)$stats['total_attempts'] : 0,
                'avg_score'      => $stats ? (int)$stats['avg_score']      : 0,
            ),
        ));
    }

    // ---------------------------------------------------------------
    // GET /api/student/packages
    // ---------------------------------------------------------------
    public function packages()
    {
        $jwt = $this->_require_jwt();
        $uid = (int)$jwt['user_id'];
        $db  = get_db();

        $uname = $this->_username($db, $uid);

        $res = $db->query(
            "SELECT DISTINCT p.id AS package_id, p.package_name,
                    p.start_date, p.end_date,
                    CASE WHEN p.status = 1 THEN 'active' ELSE 'inactive' END AS pkg_status
             FROM package_info p
             INNER JOIN subscription_details s ON s.package_id = p.id
             WHERE s.username = '" . $db->escape($uname) . "'
             ORDER BY p.id DESC"
        );

        $rows = $res ? $db->get_result($res) : array();
        $this->_json(array('success' => true, 'packages' => $rows));
    }

    // ---------------------------------------------------------------
    // GET /api/student/package/:id
    // ---------------------------------------------------------------
    public function package($package_id = 0)
    {
        $jwt = $this->_require_jwt();
        $uid = (int)$jwt['user_id'];
        $pid = (int)$package_id;
        $db  = get_db();

        $pkg_res = $db->query("SELECT * FROM package_info WHERE id = $pid LIMIT 1");
        $pkg     = $pkg_res ? $db->get_row($pkg_res) : null;
        if (!$pkg) {
            $this->_json(array('success' => false, 'message' => 'Package not found'), 404);
        }

        $uname = $this->_username($db, $uid);

        // Quizzes linked to this package, with student's score if attempted
        $quiz_res = $db->query(
            "SELECT qi.id AS quiz_id, qi.Quiz_name, qi.quiztype,
                    qi.No_of_question, qi.Time_limit,
                    CASE WHEN sqr.quiz_id IS NOT NULL THEN 'submitted' ELSE NULL END AS session_status,
                    COALESCE(sqr.physicsmark + sqr.chemistrymark + sqr.biologymark, 0) AS score
             FROM quiz_package_link qpl
             INNER JOIN quiz_info qi ON qi.id = qpl.quiz_id
             LEFT JOIN student_quiz_result sqr
                    ON sqr.quiz_id = qi.id
                   AND sqr.user_id  = '" . $db->escape($uname) . "' 
             WHERE qpl.package_id = $pid
             ORDER BY qpl.id ASC"
        );
        $quizzes = $quiz_res ? $db->get_result($quiz_res) : array();

        $this->_json(array(
            'success' => true,
            'package' => array(
                'package_id'   => (int)$pkg['id'],
                'package_name' => $pkg['package_name'],
                'start_date'   => isset($pkg['start_date']) ? $pkg['start_date'] : null,
                'end_date'     => isset($pkg['end_date'])   ? $pkg['end_date']   : null,
            ),
            'quizzes' => $quizzes,
        ));
    }

    // ---------------------------------------------------------------
    // GET /api/student/quizzes/:package_id
    // ---------------------------------------------------------------
    public function quizzes($package_id = 0)
    {
        $jwt = $this->_require_jwt();
        $uid = (int)$jwt['user_id'];
        $pid = (int)$package_id;
        $db  = get_db();

        $uname = $this->_username($db, $uid);

        $res = $db->query(
            "SELECT qi.id AS quiz_id, qi.Quiz_name, qi.quiztype,
                    qi.No_of_question, qi.Time_limit,
                    CASE WHEN sqr.quiz_id IS NOT NULL THEN 'submitted' ELSE NULL END AS session_status,
                    COALESCE(sqr.physicsmark + sqr.chemistrymark + sqr.biologymark, 0) AS score
             FROM quiz_package_link qpl
             INNER JOIN quiz_info qi ON qi.id = qpl.quiz_id
             LEFT JOIN student_quiz_result sqr
                    ON sqr.quiz_id = qi.id
                   AND sqr.user_id  = '" . $db->escape($uname) . "' 
             WHERE qpl.package_id = $pid
             ORDER BY qpl.id ASC"
        );

        $rows = $res ? $db->get_result($res) : array();
        $this->_json(array('success' => true, 'quizzes' => $rows));
    }

    // ---------------------------------------------------------------
    // GET /api/student/results
    // ---------------------------------------------------------------
    public function results()
    {
        $jwt = $this->_require_jwt();
        $uid = (int)$jwt['user_id'];
        $db  = get_db();

        $uname = $this->_username($db, $uid);

        $res = $db->query(
            "SELECT sqr.quiz_id,
                    qi.Quiz_name,
                    qi.quiztype,
                    qi.No_of_question,
                    (sqr.physicsmark + sqr.chemistrymark + sqr.biologymark) AS score,
                    sqr.rank,
                    (SELECT COUNT(*) FROM student_quiz_result WHERE quiz_id = sqr.quiz_id) AS total_students
             FROM student_quiz_result sqr
             INNER JOIN quiz_info qi ON qi.id = sqr.quiz_id
             WHERE sqr.user_id  = '" . $db->escape($uname) . "' 
             ORDER BY sqr.quiz_id DESC"
        );

        $rows = $res ? $db->get_result($res) : array();
        $this->_json(array('success' => true, 'results' => $rows));
    }

    // ---------------------------------------------------------------
    // GET /api/student/result/:quiz_id
    // ---------------------------------------------------------------
    public function result($quiz_id = 0)
    {
        $jwt = $this->_require_jwt();
        $uid = (int)$jwt['user_id'];
        $qid = (int)$quiz_id;
        $db  = get_db();

        $uname = $this->_username($db, $uid);

        $res = $db->query(
            "SELECT sqr.*,
                    qi.Quiz_name, qi.quiztype, qi.No_of_question,
                    (sqr.physicsmark + sqr.chemistrymark + sqr.biologymark) AS score,
                    (SELECT COUNT(*) FROM student_quiz_result WHERE quiz_id = $qid) AS total_students
             FROM student_quiz_result sqr
             INNER JOIN quiz_info qi ON qi.id = sqr.quiz_id
             WHERE sqr.quiz_id = $qid
               AND sqr.user_id  = '" . $db->escape($uname) . "' 
             LIMIT 1"
        );
        $row = $res ? $db->get_row($res) : null;

        if (!$row) {
            $this->_json(array('success' => false, 'message' => 'Result not found'), 404);
        }

        $total_q  = (int)$row['No_of_question'];
        $correct  = (int)(isset($row['physicscorrect'])   ? $row['physicscorrect']   : 0)
                  + (int)(isset($row['chemistrycorrect'])  ? $row['chemistrycorrect']  : 0)
                  + (int)(isset($row['biologycorrect'])    ? $row['biologycorrect']    : 0);
        $wrong    = (int)(isset($row['physicswrong'])     ? $row['physicswrong']     : 0)
                  + (int)(isset($row['chemistrywrong'])   ? $row['chemistrywrong']   : 0)
                  + (int)(isset($row['biologywrong'])     ? $row['biologywrong']     : 0);
        $skipped  = max(0, $total_q - $correct - $wrong);
        $score    = (float)$row['score'];
        $max_score = $total_q * 4;
        $pct      = $max_score > 0 ? round(($score / $max_score) * 100, 1) : 0;

        $sections = array();
        foreach (array('physics', 'chemistry', 'biology') as $subj) {
            $s_att     = (int)(isset($row[$subj . 'attempted']) ? $row[$subj . 'attempted'] : 0);
            $s_correct = (int)(isset($row[$subj . 'correct'])   ? $row[$subj . 'correct']   : 0);
            $s_wrong   = (int)(isset($row[$subj . 'wrong'])     ? $row[$subj . 'wrong']     : 0);
            $s_mark    = isset($row[$subj . 'mark'])             ? (float)$row[$subj . 'mark'] : 0;
            if ($s_att > 0 || $s_mark != 0) {
                $sections[] = array(
                    'section_name' => ucfirst($subj),
                    'score'        => $s_mark,
                    'correct'      => $s_correct,
                    'wrong'        => $s_wrong,
                    'unanswered'   => max(0, $s_att - $s_correct - $s_wrong),
                );
            }
        }

        $this->_json(array(
            'success' => true,
            'session' => array(
                'quiz_name' => $row['Quiz_name'],
                'quiztype'  => $row['quiztype'],
                'score'     => $score,
                'max_score' => $max_score,
            ),
            'summary' => array(
                'correct'    => $correct,
                'incorrect'  => $wrong,
                'skipped'    => $skipped,
                'percentage' => $pct,
            ),
            'rank' => array(
                'rank'           => isset($row['rank'])           ? (int)$row['rank']           : null,
                'total_students' => isset($row['total_students'])  ? (int)$row['total_students']  : 0,
            ),
            'sections' => $sections,
        ));
    }

    // ---------------------------------------------------------------
    // GET /api/student/profile
    // ---------------------------------------------------------------
    public function profile()
    {
        $jwt = $this->_require_jwt();
        $uid = (int)$jwt['user_id'];
        $db  = get_db();

        $res = $db->query(
            "SELECT first_name, last_name, phone, email,
                    college_name, rollno, address, state, city
             FROM user_details
             WHERE user_id = $uid AND user_status = 1
             LIMIT 1"
        );
        $row = $res ? $db->get_row($res) : null;

        if (!$row) {
            $this->_json(array('success' => false, 'message' => 'User not found'), 404);
        }

        $this->_json(array(
            'success'  => true,
            'name'     => trim($row['first_name'] . ' ' . $row['last_name']),
            'phone'    => $row['phone'],
            'email'    => isset($row['email'])        ? $row['email']        : '',
            'college'  => isset($row['college_name']) ? $row['college_name'] : '',
            'roll_no'  => isset($row['rollno'])        ? $row['rollno']       : '',
            'address'  => isset($row['address'])       ? $row['address']      : '',
        ));
    }

    // ---------------------------------------------------------------
    // GET /api/teacher/packages
    // ---------------------------------------------------------------
    public function teacher_packages()
    {
        $jwt = $this->_require_jwt();
        if ((int)$jwt['role'] !== 3) {
            $this->_json(array('success' => false, 'message' => 'Forbidden: teacher role required'), 403);
        }
        $db = get_db();

        $res = $db->query(
            "SELECT p.id, p.package_name,
                    COUNT(DISTINCT s.username) AS student_count
             FROM package_info p
             LEFT JOIN subscription_details s ON s.package_id = p.id
             WHERE p.status = 1
             GROUP BY p.id, p.package_name
             ORDER BY p.package_name"
        );

        $rows = $res ? $db->get_result($res) : array();
        $this->_json(array('success' => true, 'packages' => $rows));
    }

    // ---------------------------------------------------------------
    // GET /api/teacher/students/:package_id
    // ---------------------------------------------------------------
    public function teacher_students($package_id = 0)
    {
        $jwt = $this->_require_jwt();
        if ((int)$jwt['role'] !== 3) {
            $this->_json(array('success' => false, 'message' => 'Forbidden: teacher role required'), 403);
        }
        $pid = (int)$package_id;
        $db  = get_db();

        $pkg_res  = $db->query("SELECT package_name FROM package_info WHERE id = $pid LIMIT 1");
        $pkg_row  = $pkg_res ? $db->get_row($pkg_res) : null;
        $pkg_name = $pkg_row ? $pkg_row['package_name'] : '';

        $res = $db->query(
            "SELECT ud.first_name, ud.last_name, ud.phone, ud.email,
                    ROUND(AVG(sqr.physicsmark + sqr.chemistrymark + sqr.biologymark), 0) AS avg_score,
                    COUNT(sqr.quiz_id) AS quiz_attempts
             FROM subscription_details sd
             INNER JOIN user_details ud
                    ON ud.user_name COLLATE utf8_general_ci = sd.username COLLATE utf8_general_ci
             LEFT JOIN student_quiz_result sqr
                    ON sqr.user_id COLLATE utf8_general_ci = ud.user_name COLLATE utf8_general_ci
             WHERE sd.package_id = $pid AND ud.user_status = 1
             GROUP BY ud.user_id, ud.first_name, ud.last_name, ud.phone, ud.email
             ORDER BY ud.first_name, ud.last_name"
        );

        $students = $res ? $db->get_result($res) : array();
        $this->_json(array(
            'success'      => true,
            'package_name' => $pkg_name,
            'students'     => $students,
        ));
    }
}
