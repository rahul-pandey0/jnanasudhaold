<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends CI_protectedController
{
    public $db; // property for database instance

    public function __construct()
    {
        parent::__construct();

        // CORS headers
       /* header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers: *');
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE');*/

        // Start PHP session if not started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Load your database helper
        $this->load->helper('database'); //
        $this->load->helper(array('url'));
		
        // Initialize database using helper
        $this->db = get_db(); // now get_db() exists

        // Load required models
        $this->load->model('Admin_Model');
	
		$this->load->model('Menu_model', 'menu_model');
		$this->load->model('Quiz_model','quiz_model');
		//$this->load->library('form_validation');
        
    }

  /*  public function main_menu()
    {
        $userrole = isset($_SESSION['userrole']) ? $_SESSION['userrole'] : null;

        if (in_array($userrole, ["1","3","4","5","6"])) {
            $arrtopmenudetails = $this->Admin_model->gettopmenu();
            $arrmenudetails = [];

            foreach ($arrtopmenudetails as $menu) {
                $arrmenudetails[$menu['MAIN_MENU']] = $this->Admin_model->getmenu($menu['MAIN_MENU']);
            }

            $this->data['arrtopmenudetails'] = $arrtopmenudetails;
            $this->data['arrmenudetails'] = $arrmenudetails;

        } else if ($userrole == "2") {
            redirect(base_url("quiz/quiz_userscreen"));
        } else {
            redirect(base_url("index"));
        }*/
    
public function quiz_details()
	{
		//$this->main_menu();
		$this->data['quiz_details']= $this->Admin_Model->findAllForquizdetails();
		$this->load->view('Quiz_details',$this->data);
	}
	
	public function save_quizDetails()
	{	
		//$this->main_menu();
		$this->load->model('Quiz_model','quiz_model');
		$s_type=$this->input->post('s_type');
		//echo "type   ".$s_type;
		if($s_type=="1")
		{
			$result=$this->Admin_Model->get_static_quiz();
			$url=base_url()."Admin/quiz_static_view";
		}
		else
		{
			$result=$this->Admin_Model->get_dynamic_quiz();
			$url=base_url()."Admin/quiz_dynamic_view";
		}
		$this->Admin_Model->save_quizDetails($result,$s_type);
			// $this->load->view('Quiz_reportview',$this->data);
		echo "<script>alert('Quiz details saved');
		window.location.href='$url';
		</script>";
	}

	public function get_questions21()
	{
		//$this->nativesession->delete('');
		//$rs=$this->uri->segment_array();
		$this->load->model('Admin_Model');
		$id=$this->input->get('id');
		$parameter=$this->input->get('parameter');
		$this->data['quizid']= '18 JUT-'.$parameter;
		$result = $this->Admin_Model->get_questionby_categoryandsubject1($id,$parameter);
		//print_r($result);
		/* $result = $this->quiz_model->get_questionby_categoryandsubject($rs[4],$rs[6]);*/
		/*
		$data1="";
		for($i=0;$i<count($result);$i++)
		{
		if($i>0)
		$data1=$data1.",,,";
		$data1=$data1."".$result[$i]['id'];
		$data1=$data1."~,~,~".$result[$i]['question_name'];
		$data1=$data1."~,~,~".$result[$i++]['question_answer'];
		$data1=$data1."~,~,~".$result[$i++]['question_answer'];
		$data1=$data1."~,~,~".$result[$i++]['question_answer'];
		$data1=$data1."~,~,~".$result[$i]['question_answer'];
		$data1=$data1."~,~,~".$result[$i]['discription'];
		}
		for($i=0;$i<count($result);$i++)
		{
		$result[$i]['id'];
		$result[$i]['question_name']=str_replace('"','\'',$result[$i]['question_name']);
		$result[$i]['question_name']= str_replace("\n", " ", $result[$i]['question_name']);
		$result[$i]['question_name']= str_replace("\r", " ",$result[$i]['question_name']);
		$result[$i]['question_answer']=str_replace('"','\'',$result[$i]['question_answer']);
		$result[$i]['question_answer']= str_replace("\n", " ", $result[$i]['question_answer']);
		$result[$i]['question_answer']= str_replace("\r", " ",$result[$i]['question_answer']);
		$i++;
		$result[$i]['question_answer']=str_replace('"','\'',$result[$i]['question_answer']);
		$result[$i]['question_answer']= str_replace("\n", " ", $result[$i]['question_answer']);
		$result[$i]['question_answer']= str_replace("\r", " ",$result[$i]['question_answer']);
		//$data1=$data1."~,~,~".$result[$i++]['question_answer'];
		$i++;
		//$data1=$data1."~,~,~".$result[$i++]['question_answer'];
		$result[$i]['question_answer']=str_replace('"','\'',$result[$i]['question_answer']);
		$result[$i]['question_answer']= str_replace("\n", " ", $result[$i]['question_answer']);
		$result[$i]['question_answer']= str_replace("\r", " ",$result[$i]['question_answer']);
		$i++;
		$result[$i]['question_answer']=str_replace('"','\'',$result[$i]['question_answer']);
		$result[$i]['question_answer']= str_replace("\n", " ", $result[$i]['question_answer']);
		$result[$i]['question_answer']= str_replace("\r", " ",$result[$i]['question_answer']);

		$result[$i]['discription']=str_replace('"','\'',$result[$i]['discription']);;
		}
		*/
		//$this->main_menu();
		$this->load->model('Admin_Model');
		$this->data['result1']=$this->Admin_Model->get_allquiz_subject();
		$redirt=0;
		if(is_array($this->data['result1'])==true)
		{
			foreach($this->data['result1'] as $row)
			{
				$redirt=1;
			}
		}
		$this->data['result']=$result;
		//print_R($result);
		//$this->nativesession->set('t_rs',$result);
		//echo $data1;
		$this->load->view('Quiz_shows', $this->data);
	}

	public function save_quizSubject()
	{
		//$this->main_menu();
		$this->load->model('Admin_Model');
		$result=$this->Admin_Model->save_allSubject();
		$url=base_url()."Admin/quiz_subject";
		echo "<script>alert('sucessfully Saved');
		window.location.href='$url';
		</script>";
	}

	public function delete_question()
	{
		//$this->main_menu();
		$rs=$this->uri->segment_array();
		$this->load->model('Admin_Model');
		$result = $this->Admin_Model->delete_question($rs[4]);
		echo $result;
	}

	public function quiz_individualquiz()
	{
		//$this->main_menu();
		//$rs=$this->uri->segment_array();
		$rs=$this->input->get('res');
		$this->load->model('Admin_Model');
		$sql="SELECT a.id, a.question_name,a.mark,a.penalty,a.level,a.discription,a.correctoption,b.question_answer,a.qtype,a.correct_answer
		FROM quiz_question a,quiz_question_answers b
		WHERE a.id = b.Qustion_no and a.id='$rs'";
		$this->data['result']=$this->Admin_Model->get_data($sql);
		//$this->load->view('Quiz_IndividualQuestion',$this->data);

		$this->load->view('Quiz_IndividualQuestion1',$this->data);
	}

	public function quiz_individualquizs()
	{
		//$this->main_menu();
		//$rs=$this->uri->segment_array();
		$rs=$this->input->get('res');
		$this->load->model('Admin_Model');
		$sql="SELECT a.id, a.question_name,a.mark,a.penalty,a.level,a.discription,a.correctoption,b.question_answer,a.qtype,a.correct_answer,a.grace
		FROM quiz_question a,quiz_question_answers b
		WHERE a.id = b.Qustion_no and a.id='$rs'";
		$this->data['result']=$this->Admin_Model->get_data($sql);
		//$this->load->view('Quiz_IndividualQuestion',$this->data);

		$this->load->view('Quiz_IndividualQuestions',$this->data);
	}
	public function editquiz_individual()
	{
		//$this->main_menu();
		//$rs=$this->uri->segment_array();
		$rs=$this->input->get('res');
		$this->load->model('Admin_Model');
		$sql="SELECT a.id, a.question_name,a.mark,a.penalty,a.level,a.discription,a.correctoption,b.question_answer
		FROM quiz_question a,quiz_question_answers b
		WHERE a.id = b.Qustion_no and a.id='$rs'";
		$this->data['result']=$this->Admin_Model->get_data($sql);
		//$this->load->view('Quiz_IndividualQuestion',$this->data);

		$this->load->view('Quiz_EditIndividualQuestion',$this->data);
	}

	public function get_quizCategory()
	{
		//$this->main_menu();
		$rs=$this->uri->segment_array();
		$this->load->model('Admin_Model');
		$result=$this->Admin_Model->get_subjectnamecategory_id($rs[4]);
		$i=0;
		$data1="o";
		if(is_array($result)==true)
		{
			$data1="";
			foreach ($result as $resultnew)
			{
				if($i>0)
					$data1=$data1.",,,";
				$data1=$data1."".$result[$i]['id'];
				$data1=$data1."~,~,~".$result[$i]['subject'];
				$data1=$data1."~,~,~".$result[$i]['category'];
				$data1=$data1."~,~,~".$result[$i]['discription'];
				$i++;
			}
		}
		echo $data1;
	}

	public function quiz_question()
	{
		//$this->main_menu();
		$this->load->model('Admin_Model');
		$this->data['result']=$this->Admin_Model->get_allquiz_subject();
		//$this->data['teachers']=$this->quiz_model->get_teacherinfo();
		$redirt=0;
		if(is_array($this->data['result'])==true)
		{
			foreach($this->data['result'] as $row)
			{
				$redirt=1;
			}
		}
		if($redirt==0)
		{
			$url=base_url()."Admin/quiz_subject";
			echo "<script>alert('Please Fill the Subject name and category');
			window.location.href='$url';
			</script>";
		}
		$this->data['title']='Quiz';
		//print_R($this->data['teachers']);
		$this->load->view('Quiz_question',$this->data);
	}

	public function save_quizquestion()
	{
		//$this->main_menu();
		$que='';$op1='';$op2='';$op3='';$op4='';$dis='';
		$this->load->model('Admin_Model');
		$subject=$this->input->post('subject_name');

		//$file = $_FILES['DATAFILE1']['tmp_name'];

		if($_FILES['DATAFILE1']['name'])
		{
			$upOne = realpath(__DIR__ .'/../..');
			$target_Path =$upOne."/assets/uploaded_xl/".basename($_FILES['DATAFILE1']['name']);
			if (move_uploaded_file($_FILES['DATAFILE1']['tmp_name'],$target_Path)) {
				$que=base_url()."assets/uploaded_xl/".basename($_FILES['DATAFILE1']['name']);
			}
		}

		if($_FILES['DATAFILE2']['name'])
		{
			$upOne = realpath(__DIR__ .'/../..');
			$target_Path =$upOne."/assets/uploaded_xl/".basename($_FILES['DATAFILE2']['name']);
			if (move_uploaded_file($_FILES['DATAFILE2']['tmp_name'],$target_Path)) {
				$op1=base_url()."assets/uploaded_xl/".basename($_FILES['DATAFILE2']['name']);
			}
		}

		if($_FILES['DATAFILE3']['name'])
		{
			$upOne = realpath(__DIR__ .'/../..');
			$target_Path =$upOne."/assets/uploaded_xl/".basename($_FILES['DATAFILE3']['name']);
			if (move_uploaded_file($_FILES['DATAFILE3']['tmp_name'],$target_Path)) {
				$op2=base_url()."assets/uploaded_xl/".basename($_FILES['DATAFILE3']['name']);
			}
		}

		if($_FILES['DATAFILE4']['name'])
		{
			$upOne = realpath(__DIR__ .'/../..');
			$target_Path =$upOne."/assets/uploaded_xl/".basename($_FILES['DATAFILE4']['name']);
			if (move_uploaded_file($_FILES['DATAFILE4']['tmp_name'],$target_Path)) {
				$op3=base_url()."assets/uploaded_xl/".basename($_FILES['DATAFILE4']['name']);
			}
		}
		if($_FILES['DATAFILE5']['name'])
		{
			$upOne = realpath(__DIR__ .'/../..');
			$target_Path =$upOne."/assets/uploaded_xl/".basename($_FILES['DATAFILE5']['name']);
			if (move_uploaded_file($_FILES['DATAFILE5']['tmp_name'],$target_Path)) {
				$op4=base_url()."assets/uploaded_xl/".basename($_FILES['DATAFILE5']['name']);
			}
		}
		//echo "subject_id".$subject_id=$this->quiz_model->get_subjectid($subject);
		//echo "unit_id".$unit_id=$this->quiz_model->get_unitid($subject_id,$category);
		//echo $question_id=$this->quiz_model->get_subid($subject_id,$unit_id);
		//$question_id=$this->quiz_model->get_questionid($subject_id,$unit_id);
		/*if($question_id=="o")
		{
		$question_id=1;
		}
		else
		{
		$question_id=$question_id+1;
		}*/
		$this->Admin_Model->save_question($que,$op1,$op2,$op3,$op4,$dis);

			//$category=$this->input->post('category');

		redirect(base_url().'Admin/Quiz_question_before_preview');
			// $questions=$this->quiz_model->get_quizquestion();
			// print_r($questions);
	}
	
	public function Quiz_question_before_preview()
	{
		//$this->main_menu();
		$this->load->model('Quiz_model', 'quiz_model');
		$sql="SELECT id FROM quiz_question ORDER BY id DESC LIMIT 1";
		$id=$this->quiz_model->get_data($sql);

			//	 print_R($id);
		$sql="SELECT a.id, a.question_name,a.mark,a.penalty,a.level,a.discription,a.correctoption,b.question_answer
		FROM quiz_question a,quiz_question_answers b
		WHERE a.id = b.Qustion_no and
		a.id=(select max(id) from quiz_question)";
		$this->data['result']=$questions=$this->quiz_model->get_data($sql);
		//print_r($questions);

		// $url=base_url()."quiz/Quiz_question_before_preview";
		/*	echo "<script>alert('Successfully saved.');
		window.location.href='$url';
		</script>";
		*/
		$this->load->view('Quiz_question_before_preview', $this->data);
	}

	public function quiz_report()
	{
		//$this->main_menu();
		$this->load->model('Admin_Model');
		$result = $this->Admin_Model->get_report();
		$this->data['result']=$result;
		$this->load->view('Quiz_report',$this->data);
	}

	public function uploadxlfile()
	{
		//$this->main_menu();
		$this->data['var']='';

		$this->load->model('Admin_Model');
		$this->data['result']=$this->Admin_Model->get_allquiz_subject();

		$this->load->view('uploadxl',$this->data);
	}

	public function saveuploadxlfile()
	{
		//$this->main_menu();
		$str='';
		if($_FILES['uploads']['name'])
		{
			for($i=0; $i<count($_FILES['uploads']['name']); $i++)
			{
		//$arrName=explode('.',$_FILES['uploads']['name'][$i]);
				$file = explode(".",$_FILES['uploads']['name'][$i]);
				$upOne = realpath(__DIR__ .'/../..');
				$target_Path =$upOne."/assets/uploaded_xl/".basename($_FILES['uploads']['name'][$i]);

				if (move_uploaded_file($_FILES['uploads']['tmp_name'][$i],$target_Path)) {
				}
				$this->load->library('uploadexcel');
				$str=$this->uploadexcel->getxl_info($target_Path);
			}
		}
		$temp=explode("~~~",$str);

		for($i=1;$i<count($temp);$i++)
		{
			$arr=explode(",",$temp[$i]);
			if(is_array($arr))
			{
				$question1=$arr[0];
				$op1=$arr[1];
				$op2=$arr[2];
				$op3=$arr[3];
				$op4=$arr[4];
				$dis=$arr[5];
				$correct=$arr[6];
				$dif=$arr[7];
				$mar=$arr[8];
				$pen=$arr[9];
				$this->load->model('Admin_Model');
				$this->Admin_Model->save_excel_question($question1,$op1,$op2,$op3,$op4,$dis,$correct,$dif,$mar,$pen);
			}
		}
		$url=base_url()."Admin/uploadxlfile";
		echo "<script>alert('Successfully uploaded');
		window.location.href='$url';
		</script>";
	}

	public function add_student_info()
	{
		//$this->main_menu();
		$this->load->view('Quiz_add_studnt',$this->data);
	}

	public function save_studentinfo()
	{
		//$this->main_menu();
		$this->load->model('Admin_Model');
		$this->Admin_Model->save_studentinfo();

		$url=base_url()."Admin/add_student_info";
		echo "<script>alert('student details successfully saved');
		window.location.href='$url';
		</script>";

	}

	public function add_teacher_info()
	{
		//$this->main_menu();
		$this->data['college_details']= $this->Admin_Model->getallcolleges();
		$this->data['subject_details']= $this->Admin_Model->getallsubject();
		
		$this->data['teachers']=$this->Admin_Model->get_allteacherinfo();
		$this->load->view('Quiz_add_teacher',$this->data);
	}

	public function save_teacherinfo()
	{
		//$this->main_menu();
		$this->load->model('Admin_Model');
		$this->Admin_Model->save_teacherinfo();
		
		$this->session->set_flashdata('message_name', 'Teacher Details Successfully Saved'); 
		redirect(base_url()."Admin/add_teacher_info");
	}

	public function add_subject()
	{
		//$this->main_menu();
		$this->load->model('Admin_Model');
		$this->data['sub']=$this->Admin_Model->get_allquiz_subject();
		$this->data['cls']=$this->Admin_Model->get_classcode_quiz();
		$this->load->view('Quiz_search',$this->data);
	}
	
	public function get_searchinfo()
	{
		//$this->main_menu();
		$data1="";$i=0;
		$result="";
		$name=$this->input->get('id');
		$info=$this->input->get('cls');
		$cls=$this->input->get('info');
		$roll=$this->input->get('roll');
		$this->load->model('Admin_Model');
		if($name=="teacher")
		{
			if($cls)
			{
				$result=$this->Admin_Model->get_individualteacherinfo($cls);
			//echo "teacher 1";
			}
			else
			{

				$result=$this->Admin_Model->get_teacherinfo();
			//echo "teacher 2";
			}
			if(is_array($result)==true)
			{

				foreach ($result as $resultnew)
				{
					if($i>0)
						$data1=$data1.",,,";
					$data1=$data1."".$result[$i]['id'];
					$data1=$data1."~,~,~".$result[$i]['name'];
					$data1=$data1."~,~,~".$result[$i]['user_name'];
					$data1=$data1."~,~,~".$result[$i]['email'];
					$data1=$data1."~,~,~".$result[$i]['phone_no'];
					$data1=$data1."~,~,~".$result[$i]['actual_password'];
					$i++;
				}
			}
		}
		else
		{
			if($info)
			{
				if($roll)
					$result=$this->Admin_Model->get_individualstudentinfo($info,$roll);
				else
					$result=$this->Admin_Model->get_individualstudentinfo($info,'');
				//echo "student 1";
			}
			else
			{
				if($roll)
					$result=$this->Admin_Model->get_individualstudentinfo('',$roll);
				else
					$result=$this->Admin_Model->get_studentinfo();
				//echo "student 2";
			}
			if(is_array($result)==true)
			{

				foreach ($result as $resultnew)
				{
					if($i>0)
						$data1=$data1.",,,";
					$data1=$data1."".$result[$i]['id'];
					$data1=$data1."~,~,~".$result[$i]['name'];
					$data1=$data1."~,~,~".$result[$i]['Father_name'];
					$data1=$data1."~,~,~".$result[$i]['Email'];
					$data1=$data1."~,~,~".$result[$i]['phone_no'];
					$data1=$data1."~,~,~".$result[$i]['user_name'];
					$data1=$data1."~,~,~".$result[$i]['role_no'];
					$data1=$data1."~,~,~".$result[$i]['actual_password'];
					$data1=$data1."~,~,~".$result[$i]['status'];
					$i++;
				}
			}
		}
		if($data1=="")
			echo "o";
		else
			echo $data1;
	}

	public function savesearch_info()
	{
		//$this->main_menu();
		$this->load->model('Admin_Model');
		$this->Admin_Model->save_searchinfo();
		redirect(base_url()."Admin/add_subject");
	}

	public function add_student_xl()
	{
		//$this->main_menu();
		$this->data['var']='';

		$this->load->model('Admin_Model');
		$this->data['result']=$this->Admin_Model->get_allquiz_subject();

		$this->load->view('studentxl',$this->data);
	}

	public function saveuploadxlfilestudent()
	{
		//$this->main_menu();
		$str='';
		if($_FILES['uploads']['name'])
		{
			for($i=0; $i<count($_FILES['uploads']['name']); $i++)
			{
			//$arrName=explode('.',$_FILES['uploads']['name'][$i]);
				$file = explode(".",$_FILES['uploads']['name'][$i]);
				$upOne = realpath(__DIR__ .'/../..');
				$target_Path =$upOne."/assets/uploaded_xl/".basename($_FILES['uploads']['name'][$i]);

				if (move_uploaded_file($_FILES['uploads']['tmp_name'][$i],$target_Path)) {
				}
				$this->load->library('uploadexcel');
				$str=$this->uploadexcel->getxl_info($target_Path);
			}
		}
		$temp=explode("~~~",$str);

		for($i=1;$i<count($temp);$i++)
		{
			$arr=explode(",",$temp[$i]);
			// print_r($arr);
			if(is_array($arr))
			{
				//echo count($arr);
				if(count($arr)=="4")
				{
					//echo " v";
					$first_name=$arr[0];
					$roll_no=$arr[1];
					$class_code=$arr[2];
					$no_for_communication=$arr[3];
					$this->load->model('Admin_Model');
					$this->Admin_Model->add_student_xl($first_name,$no_for_communication,$roll_no,$class_code);
			/*
			$question1=$arr[0];
			$op1=$arr[0];
			$op2=$arr[2];
			$op3=$arr[3];
			$op4=$arr[4];
			$dis=$arr[5];
			$correct=$arr[6];
			$dif=$arr[7];
			$mar=$arr[8];
			$pen=$arr[9];
			$this->load->model('quiz_model');
			$this->quiz_model->save_excel_question($question1,$op1,$op2,$op3,$op4,$dis,$correct,$dif,$mar,$pen);*/
		}
		}
		}
			//print_r($temp);
		$url=base_url()."Admin/add_student_xl";
		echo "<script>alert('Successfully uploaded');
		window.location.href='$url';
		</script>";
	}

	public function student_subject_detail()
	{
		//$this->main_menu();
		$this->load->model('Admin_Model');
		$this->data['result']=$this->Admin_Model->get_studentinfo();
		$this->data['result1']=$this->Admin_Model->get_allquiz_subject();
		$this->load->view('show_allsub',$this->data);
	}

	public function check_quizSubject()
	{
		//$this->main_menu();
		$rs=$this->uri->segment_array();
		$this->load->model('Admin_Model');
		$num=$this->Admin_Model->chk_subjectname($rs[4]);
		if($num=="o")
		{
			echo "1";
		}
		else
		{
			echo "o";
		}
	}

	public function changeusersubject()
	{
		//$this->main_menu();
		$this->load->model('Admin_Model');
		$student_name=$this->input->get('user');
		$this->data['result']=$result=$this->Admin_Model->get_individualstudentinfos($student_name);
		$this->data['result1']=$this->Admin_Model->get_allquiz_subject();
		//print_R($this->data['result1']);
		$this->load->view('changeusersubject',$this->data);
	}

	public function update_usersubject()
	{
		//$this->main_menu();
		$this->load->model('Admin_Model');
		$student_name=$this->input->post('user1');
		$result=$this->Admin_Model->get_allquiz_subject();
		$sub="";
		$jt=0;
		for($i=0;$i<count($result);$i++)
		{
			if($this->input->post('sub_'.$result[$i]['Subject_id'])=="on")
			{
				if($jt==0)
					$sub=$result[$i]['Subject_id'];
				else
				{
					$sub=$sub.",".$result[$i]['Subject_id'];
				}
				$jt++;
			}
		}
		$this->Admin_Model->update_usersubject($sub,$student_name);
		$url=base_url()."Admin/changeusersubject?user=".$student_name;
		echo "<script>alert('Successfully saved');
		window.location.href='$url';
		</script>";
		//print_r($sub);
		//echo $this->input->post('sub_5');
	}

	public function edit_quizinfo()
	{
		$id = $_GET['id'];
		//$this->main_menu();
		$this->data['result'] = $this->Admin_Model->findById($id);
		// print_r($this->data['result']);
		$this->load->view('Quiz_detail_editform',$this->data);
	}

	public function payment_status()
	{
		//$this->main_menu();
		//$this->data['payment']= $this->Admin_Model->collectiondetail();
		//	print_r($this->data['user']);
		$this->data['package']=$this->Admin_Model->getpackagename();
		$this->data['role']=$this->Admin_Model->getpgstatus();
		$this->load->view('payment_status', $this->data);
	}
	public function user_payments()
	{
		//$this->main_menu();
		//$this->data['payment']= $this->Admin_Model->collectiondetail();
		//	print_r($this->data['user']);
		$this->data['payment'] = $this->Admin_Model->get_statusondateandstatus($fromdate,$todate,$Status,$package_id);
		$this->load->view('payment_status', $this->data);
	}

	public function get_student_result()
	{
		//$this->main_menu();
		$sub=$this->input->post('subject_name');
		$cat=$this->input->post('category');
		$this->data['var']='';

		$this->load->model('Admin_Model');
		$this->data['result']=$this->Admin_Model->get_quizresult($cat);
		$this->data['result_sub']=$this->Admin_Model->get_allquiz_subject();
		
		// print_r($this->data['result']);
		// print_r($this->data['result_sub']);
		$this->load->view('Quiz_all_reportst',$this->data);
	}

	public function quiz_report1()
	{
		//$this->main_menu();
		$this->load->model('Admin_Model');
		$result = $this->Admin_Model->get_report();
		$this->data['result']=$result;
		$this->load->view('Quiz_sd_report',$this->data);
	}

	public function quiz_static_view()
	{
		//$this->main_menu();
		$this->load->model('Admin_Model');
		$this->data['results'] = $result = $this->Admin_Model->get_static_quizdetails();
		
		if($_POST['showvalues']==1){ 
		$quiztype=$this->input->post('quiztype');
		//print_R($quiztype);
		$this->data['result'] = $result = $this->Admin_Model->quiztypeget($quiztype);
		//print_R($this->data['result']);
		}
		$this->data['rs']="1";
		$this->load->view('Quiz_reportview',$this->data);
	}

	public function quiz_dynamic_view()
	{
		//$this->main_menu();
		$this->load->model('Admin_Model');
		$this->data['result']=$result=$this->Admin_Model->get_dynamic_quiz();
		$this->data['rs']="2";
		$this->load->view('Quiz_reportview',$this->data);
	}

	public function add_quiz()
	{
		//$this->main_menu();
		$this->load->model('Admin_Model');
		$this->data['result1']=$this->Admin_Model->get_allquiz_subject();
		//print_r($this->data['result1']);
		$redirt=0;
		if(is_array($this->data['result1'])==true)
		{	//echo "hi";
			foreach($this->data['result1'] as $row)
			{
				$redirt=1;
			}
		}
		if($redirt==0)
		{
			$url=base_url()."Admin/quiz_subject";
			echo "<script>alert('Please Fill the Subject name and category');
			window.location.href='$url';
			</script>";
		}
		// $this->data['result'] = $this->quiz_model->get_allquestion();
		$this->data['title']='Quiz';
		$this->load->view('Quiz_assign',$this->data);
	}

	public function chkadd_quiz()
	{
		//$this->main_menu();
		$this->nativesession->set('subject_name',$this->input->post('subject_name'));
		$this->nativesession->set('v1',$this->input->post('v1'));
		$quiz_type=$this->input->post('quiz_type');

		if ($quiz_type == "Static") {
			redirect(base_url() . "Admin/add_newquiz");
		}else if ($quiz_type == "Static_Random") {
			redirect(base_url() . "Admin/add_newquizs");
			}
		else {
			if ($quiz_type == "Dynamic") {
				$this->nativesession->set('Static_mixed', '0');
			} else {
				$this->nativesession->set('Static_mixed', '1');
			}

			redirect(base_url() . "Admin/add_newquiz1");
		}
	}

	public function add_newquiz()
	{
		//$this->main_menu();
		$this->load->model('Admin_Model');
		$this->data['result1']=$this->Admin_Model->get_allquiz_subject();
		$redirt=0;
		if(is_array($this->data['result1'])==true)
		{
			foreach($this->data['result1'] as $row)
			{
				$redirt=1;
			}
		}
		if($redirt==0)
		{
			$url=base_url()."Admin/quiz_subject";
			echo "<script>alert('Please Fill the Subject name and category');
			window.location.href='$url';
			</script>";
		}
		// $this->data['result'] = $this->quiz_model->get_allquestion();
		$this->data['title']='Quiz';
		$this->load->view('Quiz_new', $this->data);
	}

	public function evaluation_question()
	{
		//$this->main_menu();
		$this->load->model('Admin_Model');
		$result = $this->Admin_Model->get_report();
		$this->data['result']=$result;
		$this->load->view('Quiz_sd_report_result',$this->data);
	}

	public function quiz_static_view_result()
	{
		//$this->main_menu();
		$this->load->model('Admin_Model');
		$this->data['result']=$result=$this->Admin_Model->get_static_quiz();
		$this->data['rs']="1";
		$this->load->view('Quiz_reportview_result',$this->data);
	}

	public function quiz_static_report_result()
	{
		//$this->main_menu();
		$this->load->model('Admin_Model');
		$id=$this->input->get('id');
		$rs=$this->Admin_Model->chk_report_static_quiz($id);
		if($rs=='0')
			$this->data['result']=$result=$this->Admin_Model->report_static_quiz_result($id);
		else
		{
			$this->nativesession->set('result_m','1');
			redirect(base_url()."Admin/get_static_mixed_quiz_result?id=".$id);
		}
		$this->load->view('Quiz_reportstaic_result',$this->data);
	}

	public function quiz_dynamic_view_result()
	{
		//$this->main_menu();
		$this->load->model('Admin_Model');
		$this->data['result']=$result=$this->Admin_Model->get_dynamic_quiz();
		$this->data['rs']="2";
		$this->load->view('Quiz_reportview_result',$this->data);
	}

	public function quiz_dynamic_report_result()
	{
		//$this->main_menu();
		$this->load->model('Admin_Model');
		$result=$this->Admin_Model->report_dynamic_quiz();
		$category=explode(",",$result[0]['category_name']);
		$level=explode(",",$result[0]['levels']);
		$levels=explode(",",$result[0]['difficulty_level']);
		$k=0;
		$data="";
		$x=0;
		for($i=0;$i<count($category);$i++)
		{

			for($j=0;$j<$level[$i];$j++)
			{
				if($data=="")
				{
					if($levels[$x]>0)
						$data=$this->Admin_Model->get_allquedynamic_quiz_result($category[$i],$j+1,$levels[$x]);
				}
				else if($j==($level[$i]-1) && $i==(count($category)-1))
				{
					if($levels[$x]>0)
					{
						$data=$data.",,,";
						$data=$data.$this->Admin_Model->get_allquedynamic_quiz_result($category[$i],$j+1,$levels[$x]);
					}
				}
				else
				{
					if($levels[$x]>0)
					{
						$data=$data.",,,";
						$data=$data.$this->Admin_Model->get_allquedynamic_quiz_result($category[$i],$j+1,$levels[$x]);

					}
				}
				$x++;
			}
		}
		$this->data['result']=$data;
		$this->load->view('Quiz_reportstaic_result',$this->data);
	}

	public function quiz_chart_view()
	{
		//$this->main_menu();
		$this->load->view('quiz_chart_view',$this->data);
	}

	public function quiz_subject()
	{
		//$this->main_menu();
		$this->load->model('Admin_Model');
		$this->data['result']=$this->Admin_Model->get_allquiz_subject();
		$this->load->view('Quiz_subject',$this->data);
	}

	public function quiz_category()
	{
		//$this->main_menu();
		$this->load->model('Admin_Model');
		$this->data['result']=$this->Admin_Model->get_allquiz_subject();
		$redirt=0;
		if(is_array($this->data['result'])==true)
		{
			foreach($this->data['result'] as $row)
			{
				$redirt=1;
			}
		}
		if($redirt==0)
		{
			$url=base_url()."quiz/quiz_subject";
			echo "<script>alert('Please Fill the Subject name');
			window.location.href='$url';
			</script>";
		}
		$this->data['result1']=$this->Admin_Model->get_allquiz_category();
		$this->load->view('Quiz_category',$this->data);
	}

	public function save_quizCategory()
	{
		//$this->main_menu();
		$this->load->model('Admin_Model');
		$subject=$this->input->post('subject_name');
		$subject_id=$this->Admin_Model->get_subjectid($subject);
		$unit_id="";
		$unit_id=$this->Admin_Model->get_subjectidandunitid($subject);
		if($unit_id=="o")
		{
			$unit_id=1;
		}
		else
		{
			$unit_id=$unit_id+1;
		}
		$result=$this->Admin_Model->save_allCategory($subject_id,$unit_id);
		$url=base_url()."Admin/quiz_category";
		echo "<script>alert('sucessfully Saved');
		window.location.href='$url';
		</script>";
	}

	public function quiz_package()
	{
		//$this->main_menu();
		$this->load->model('Admin_Model');
		$this->data['result']=$this->Admin_Model->get_allquiz_subject();
		//print_R($this->data['result1']);
		$this->load->view('Quiz_package',$this->data);

		//$this->load->view('sendmail');
	}

	public function save_quizpackage()
	{
		//$this->main_menu();
		$this->load->model('Admin_Model');
		$package_name = $this->input->post('package_name');
		$package_amt = $this->input->post('package_amt');
		$package_srtdate = $this->input->post('package_srtdate');
		$package_enddate = $this->input->post('package_enddate');
		$gst_amt = $this->input->post('gst_amt');
		//echo $no_of_que;
		//echo $subject_name;
			$sql = "INSERT INTO `package_info`(org_id,`id`, `package_name`,`price`,`start_date`,`end_date`,`cgst_amt`,`package_info`, `subject_name`) VALUES (1,'','$package_name','$package_amt','$package_srtdate','$package_enddate','$gst_amt','','')";
		//print_r($sql);
		$result=$this->Admin_Model->save_data($sql);
		$this->load->view('Quiz_package',$this->data);
	}

	public function quiz_show()
	{
		//$this->main_menu();
		$this->load->model('Admin_Model');
		$this->data['result1']=$this->Admin_Model->get_allquiz_subject();
		$redirt=0;
		if(is_array($this->data['result1'])==true)
		{
			foreach($this->data['result1'] as $row)
			{
				$redirt=1;
			}
		}
		if($redirt==0)
		{
			$url=base_url()."Admin/quiz_subject";
			echo "<script>alert('Please Fill the Subject name and category');
			window.location.href='$url';
			</script>";
		}
		$this->data['result'] ='';// $this->quiz_model->get_allquestion();
		$this->data['title']='Quiz';
		$this->load->view('Quiz_shows',$this->data);
	}

	public function edit_student() {
		$user_id = $_GET['user_id'];
		//$this->main_menu();
		$this->data['result'] = $this->Admin_Model->findByuserId($user_id);
		 //print_r($this->data['result']);
		$this->load->view('update_student',  $this->data);
	}

	public function savestudentinfo()
		{	
		print_r($_POST);
		$this->load->model('Quiz_model', 'quiz_model');
			$user_id = $_POST['user_id'];
			$username = $_POST['user_name'];
		   // print_r($user_id);
			//$this->main_menu();
			$this->Admin_Model->savestud_information($user_id);
			$this->data['result'] = $this->Admin_Model->findByuserId($user_id);
			//$this->data['result1'] = $this->quiz_model->studinfo($username);
			//$this->load->view('list_user', $this->data);
	redirect(base_url()."Admin/list_user");
		}
		
	public function delete_student() {
		//$user_name = $_GET['user_name'];
		//$this->main_menu();
$user_name = trim($this->input->post('user_name'));
		$this->data['result'] = $this->Admin_Model->deleteuser($user_name);
		//print_r($this->data['result']);
		//$this->data = $this->Admin_Model->getstud_information($user_id);
		// $this->data['user']= $this->Admin_Model->findAllForSummaryPage();
		// $this->data['role']=$this->Admin_Model->getrollid();
		// $this->load->view('list_user',  $this->data);
	}

	public function save_quizeditinfo() {
		$id = $_POST['id'];
		//$this->main_menu();
		$this->data['quiz_editdetails']= $this->Admin_Model->findForquizediteddetails($id);
		redirect(base_url()."Admin/quiz_details");
	}


	public function quiz_list_package() {
		//$this->main_menu();
		$this->data['quiz_list_package']= $this->Admin_Model->findsummaryForquizlist();
		//print_r($this->data['quiz_list_package']);
		if($_POST['showvalues']==1){ 
		$package_id = $this->input->post("package_id");
		//print_r($package_id);
		$this->data['quiz_package_link']= $this->Admin_Model->findAllForquizpacklink($package_id);
		$this->data['quiz_details']= $this->Admin_Model->findAllForquizdetails($package_id);
		}
		//print_r($this->data['quiz_package_link']);
		$this->load->view('Quiz_list_package',$this->data);

	}

	public function getallrank()
	{
		//$this->main_menu();
		$this->load->model('Admin_Model');
		$sub = $this->input->post('subject_name');
		$cat = $this->input->post('category');
		$batch = $this->input->post('batch');
		$this->data['var'] = '';
			if ($batch == ''){
				$batch='all';
			}
			$this->data['result'] = $this->Admin_Model->getallrank($cat,$batch);
			$this->data['result_sub'] = $this->Admin_Model->get_allquiz_subject();

			// print_r($this->data['result']);
			// print_r($this->data['result_sub']);
			$this->load->view('getallrank', $this->data);
	}
	
	public function getallrankcaf1()
	{
		//$this->main_menu();
		$this->load->model('Admin_Model');
		$sub = $this->input->post('subject_name');
		$cat = $this->input->post('category');
		$batch = $this->input->post('batch');
		$this->data['var'] = '';
			if ($batch == ''){
				$batch='all';
			}
			$this->data['result'] = $this->Admin_Model->getallrankcaf1($cat,$batch);
			$this->data['result_sub'] = $this->Admin_Model->get_allquiz_subject();

			// print_r($this->data['result']);
			// print_r($this->data['result_sub']);
			$this->load->view('getallrankcaf1', $this->data);
	}
		public function getallrankcaf2()
	{
		//$this->main_menu();
		$this->load->model('Admin_Model');
		$sub = $this->input->post('subject_name');
		$cat = $this->input->post('category');
		$batch = $this->input->post('batch');
		$this->data['var'] = '';
			if ($batch == ''){
				$batch='all';
			}
			$this->data['result'] = $this->Admin_Model->getallrankcaf2($cat,$batch);
			$this->data['result_sub'] = $this->Admin_Model->get_allquiz_subject();

			// print_r($this->data['result']);
			// print_r($this->data['result_sub']);
			$this->load->view('getallrankcaf2', $this->data);
	}
	
/*	public function getallrankcafshort1()
	{
		$this->main_menu();
		$this->load->model('Admin_Model');
		$sub = $this->input->post('subject_name');
		$cat = $this->input->post('category');
		$batch = $this->input->post('batch');
		$this->data['var'] = '';
			if ($batch == ''){
				$batch='all';
			}
			$this->data['result'] = $this->Admin_Model->getallrankcafshort1($cat,$batch);
			$this->data['result_sub'] = $this->Admin_Model->get_allquiz_subject();

			// print_r($this->data['result']);
			// print_r($this->data['result_sub']);
			$this->load->view('getallrankcafshort1', $this->data);
	}*/
// 	public function getallrankcafshort1()
// {
//     //$this->main_menu();
    
//     $sub = $this->input->post('subject_name');
//     $cat = $this->input->post('category');
//     $batch = $this->input->post('batch');
    
//     $this->data['var'] = '';

//     if ($batch == '') {
//         $batch = 'all';
//     }

//     // Fetch result using db helper
//     if ($batch == 'all') {
//         $this->data['result'] = db_query("SELECT * FROM ranks_table WHERE category = ?", array($cat))->result_array();
//     } else {
//         $this->data['result'] = db_query("SELECT * FROM ranks_table WHERE category = ? AND batch = ?", array($cat, $batch))->result_array();
//     }

//     // Fetch all subjects using db helper
//     $this->data['result_sub'] = db_query("SELECT * FROM quiz_subjects")->result_array();

//     // Load view
//     $this->load->view('getallrankcafshort1', $this->data);
// }
 public function getallrankcafshort1()
{
    // Get POST values safely
    $sub = isset($_POST['subject_name']) ? trim($_POST['subject_name']) : '';
    $cat = isset($_POST['category']) ? trim($_POST['category']) : '';
    $batch = isset($_POST['batch']) ? trim($_POST['batch']) : '';

    // Validate category
    if ($cat === '') {
        show_error("Category is required.", 400); 
    }

    if ($batch === '') $batch = 'all';

    $data = array();
    $data['var'] = '';

    // Use the CI DB instance (properly configured)
    $db = get_db(); 

    if ($batch === 'all') {
        // Safe query using CI query bindings
        $query = db_query("SELECT * FROM ranks_table WHERE category = ?", array($cat));
    } else {
        $query = db_query(
            "SELECT * FROM ranks_table WHERE category = ? AND batch = ?", 
            array($cat, $batch)
        );
    }

    $data['result'] = $query->result_array();

    // Fetch all subjects
    $subjects_query = db_query("SELECT * FROM quiz_subjects");
    $data['result_sub'] = $subjects_query->result_array();

    // Load view
    $this->load->view('getallrankcafshort1', $data);
}
     public function getallrankcafshort2()
	{
		//$this->main_menu();
		$this->load->model('Admin_Model');
		$sub = $this->input->post('subject_name');
		$cat = $this->input->post('category');
		$batch = $this->input->post('batch');
		$this->data['var'] = '';
			if ($batch == ''){
				$batch='all';
			}
			$this->data['result'] = $this->Admin_Model->getallrankcafshort2($cat,$batch);
			$this->data['result_sub'] = $this->Admin_Model->get_allquiz_subject();

			// print_r($this->data['result']);
			// print_r($this->data['result_sub']);
			$this->load->view('getallrankcafshort2', $this->data);
	}
	/*public function getallrankneet2021()
	{
		$this->main_menu();
		$this->load->model('Admin_Model');
		$sub = $this->input->post('subject_name');
		$cat = $this->input->post('category');
		$batch = $this->input->post('batch');
		$this->data['var'] = '';
			if ($batch == ''){
				$batch='all';
			}
			$this->data['result'] = $this->Admin_Model->getallrankneet2021($cat,$batch);
			$this->data['result_sub'] = $this->Admin_Model->get_allquiz_subject();

			// print_r($this->data['result']);
			// print_r($this->data['result_sub']);
			$this->load->view('getallrankneet2021', $this->data);
	}*/
	public function getallrankneet2021()
{
	$cat   = isset($_POST['category']) ? $_POST['category'] : '';
    $batch = isset($_POST['batch']) ? $_POST['batch'] : 'all';

    if ($batch == '') {
        $batch = 'all';
    }

    $data['result'] = [];
    if ($cat != '') {
        $data['result'] = $this->Admin_Model->getallrankneet2021($cat, $batch);
    }

    $data['result_sub'] = $this->Admin_Model->get_allquiz_subject();

    $this->load->view('quizdownload/getallrankneet2021', $data);
}

public function getallranksingle()
{
    // Load menu + access check
   // $this->main_menu();

    // Get POST values
    //$cat   =$_POST['category'];
    //$batch = $_POST['batch'];
    $cat = isset($_POST['category']) ? $_POST['category'] : '';
    $batch = isset($_POST['batch']) ? $_POST['batch'] : 'all';

    if ($batch == '') {
        $batch = 'all';
    }

    // Call model
    $this->data['result']     = $this->Admin_Model->getallranksingle($cat, $batch);
    $this->data['result_sub'] = $this->Admin_Model->get_allquiz_subject();

    // Load view
    $this->load->view('getallranksingle', $this->data);
}

	/*public function getallranksingle()
	{
		$this->main_menu();
		$this->load->model('Admin_Model');
		$sub = $this->input->post('subject_name');
		$cat = $this->input->post('category');
		$batch = $this->input->post('batch');
		$this->data['var'] = '';
			if ($batch == ''){
				$batch='all';
			}
			$this->data['result'] = $this->Admin_Model->getallranksingle($cat,$batch);
			$this->data['result_sub'] = $this->Admin_Model->get_allquiz_subject();

			// print_r($this->data['result']);
			// print_r($this->data['result_sub']);
			$this->load->view('getallranksingle', $this->data);
	}
	*/
	
/*	public function getallrankonesub()
	{
		$this->main_menu();
		$this->load->model('Admin_Model');
		$sub = $this->input->post('subject_name');
		$cat = $this->input->post('category');
		$batch = $this->input->post('batch');
		$this->data['var'] = '';
			if ($batch == ''){
				$batch='all';
			}
			$this->data['result'] = $this->Admin_Model->getallrankonesub($cat,$batch);
			$this->data['result_sub'] = $this->Admin_Model->get_allquiz_subject();

			// print_r($this->data['result']);
			// print_r($this->data['result_sub']);
			$this->load->view('getallrankonesub', $this->data);
	}*/
// 	public function getallrankonesub()
// {
//    // $this->main_menu();

//     $cat   = $this->input->post('category');
//     $batch = $this->input->post('batch');

//     if ($batch == '') {
//         $batch = 'all';
//     }

//     $this->load->model('Admin_Model');

//     $this->data['result']     = $this->Admin_Model->getallrankonesub($cat, $batch);
//     $this->data['result_sub'] = $this->Admin_Model->get_allquiz_subject();

//     $this->load->view('getallrankonesub', $this->data);
// }
	public function getallrankonesub()
	{
	    // Get POST values safely
		$cat = isset($_POST['category']) ? trim($_POST['category']) : '';
		$batch = isset($_POST['batch']) ? trim($_POST['batch']) : '';

		if ($batch == '') {
			$batch = 'all';
		}
		// Load model 
		$this->load->model('Admin_Model');

		// Prepare data array
		$data = array();
		$data['result'] = $this->Admin_Model->getallrankonesub($cat, $batch);
		$data['result_sub'] = $this->Admin_Model->get_allquiz_subject();

		// Load view
		$this->load->view('getallrankonesub', $data);
   }

    public function getallconsolrank()
	{
		//$this->main_menu();
		$this->load->model('Admin_Model');
		$sub=$this->input->post('sub');
		$batch=$this->input->post('batch');
		$this->data['var'] = '';
			if ($batch == '' && $sub == ''){
				$batch='all';
				$sub='all';
			}
			$this->data['result'] = $this->Admin_Model->get_consol_rank($batch,$sub);
		   //print_r($this->data['result']);
			// print_r($this->data['result_sub']);
			$this->load->view('getconsolrank', $this->data);
		}
	
	public function getconsolrankjee()
	{
		//$this->main_menu();
		$this->load->model('Admin_Model');
		$sub=$this->input->post('sub');
		$batch=$this->input->post('batch');
		$this->data['var'] = '';
			if ($sub == ''){
				//$batch='all';
				$sub='all';
			}
			$this->data['result'] = $this->Admin_Model->get_consol_rank_jee($batch,$sub);
		  // print_r($this->data['result']);
			// print_r($this->data['result_sub']);
			$this->load->view('getconsolrankjee', $this->data);
		}
		
	function quizquestionupload(){
		//$this->main_menu();
		$this->load->model('Admin_Model');
		$data = json_decode(file_get_contents("php://input"),true);
		//print_r($data);
		$arrInsertData=array();

		$arrInsertData = array(
			'quiz_id'=> $data['quiz_id'],
			'question_no'=> $data['question_no'],
			'question_name'=> $data['question'],
			'answer_a' => $data['op_a'],
			'answer_b' =>$data['op_b'],
			'answer_c'=> $data['op_c'],
			'answer_d'=> $data['op_d'],
			'processed'=> $data['req']

		);
				//echo json_encode($arrInsertData);
		$insertId = $this->Admin_Model->insertquizquestionupload($arrInsertData);
		if ($insertId == true) {
			$data['result_msg'] = 'Json data successfully inserted into database !';
		} else {
			$data['result_msg'] = 'Please configure your database correctly';
		}
	}

	function quizanswerupload(){
		//$this->main_menu();
		$this->load->model('Admin_Model');
		$data = json_decode(file_get_contents("php://input"),true);
		//print_r($data);
		$arrInsertData=array();		
		$arrInsertData = array(
			'quiz_id' => $data['quiz_id'],
			'question_no'=> $data['question_no'],
			'correctanswer'=> $data['correctanswer'],
			'explanation'=> $data['explanation'],
			'required'=> $data['req']
			
		);

		$insertId = $this->Admin_Model->insertquizanswerupload($arrInsertData);
		if ($insertId == true) {
			$data['result_msg'] ='inserted into database !';
		} else {
			$data['result_msg'] = 'Please configure your database correctly';
		}
	}
	public function getallquestionupload()
	{
		//$this->main_menu();
		$quiz_id=$this->input->post('quiz_id');
		$this->data['var']='';
		$this->load->model('Admin_Model');
		$this->data['result_quizid']=$this->Admin_Model->get_allquiz_upload_id();
		$this->data['result']=$this->Admin_Model->get_allquiz_upload();
			 //print_r($this->data['result_quizid']);
		$this->load->view('getallquestionupload',$this->data);
	}

	public function quiz_difficultylevelshow()
	{
		//$this->main_menu();
		$this->load->model('Admin_Model');
		$this->data['result1']=$this->Admin_Model->get_allquiz_subject();
		$redirt=0;
		if(is_array($this->data['result1'])==true)
		{
			foreach($this->data['result1'] as $row)
			{
				$redirt=1;
			}
		}
		if($redirt==0)
		{
			$url=base_url()."Admin/quiz_subject";
			echo "<script>alert('Please Fill the Subject name and category');
			window.location.href='$url';
			</script>";
		}
		$this->data['result'] ='';// $this->quiz_model->get_allquestion();
		$this->data['title']='Quiz';
		$this->load->view('Quiz_difficultylevel',$this->data);
	}

	public function get_questions()
	{
		//$this->main_menu();
		//$this->nativesession->delete('');
		//$rs=$this->uri->segment_array();
		$this->load->model('Admin_Model');
		$id=$this->input->get('id');
		//print_r($id);
		$parameter=$this->input->get('parameter');
		//print_r($parameter);
		$this->data['quizid']= '18 JUT-'.$parameter;
		$result = $this->Admin_Model->get_questionby_categoryandsubject($id,$parameter);
		//print_r($result);
		$this->data['result1']=$this->Admin_Model->get_allquiz_subject();
		$redirt=0;
		if(is_array($this->data['result1'])==true)
		{
			foreach($this->data['result1'] as $row)
			{
				$redirt=1;
			}
		}
		$this->data['result']=$result;

		//print_R($result);
		//$this->nativesession->set('t_rs',$result);
		//echo $data1;
		$this->load->view('Quiz_difficultylevel', $this->data);
	}
	public function quiz_savediff_info() {
		//$this->main_menu();
			   // $data['attachment'] = $this->Admin_Model->attachment_type($attachment);

		$this->Admin_Model->savediffrecords();	 
		$this->load->view('Quiz_difficultylevel',$this->data);


	}
	public function quiz_freesubscription()
	{
		//$this->main_menu();
		$this->data['quiz_sub']= $this->Admin_Model->findAllForquizfreesub();
		$this->load->view('Quiz_freesubscription',$this->data);

	}

	public function coupon_generate() {
		$id = $_GET['id'];
					//print_r($id);

					//$this->load->library('get_coupon');
		//$this->main_menu();
		$message = $this->Admin_Model->checkcoupon($id);

		$this->data['result'] = $this->Admin_Model->findAllForquizfreesubgencoupon($id);
					//print_r($this->data['result']);
		$this->load->view('coupon_generate',  $this->data);
	}

	public function save_freesubscription_coupon(){
		$id = $_POST['id'];
		//print_r($id);
		//$this->main_menu();
		$this->data['res'] = $this->Admin_Model->savefreesubgencoupon($id);
		//print_r($this->data['result1']);
		$this->data['quiz_sub']= $this->Admin_Model->findAllForquizfreesub();
		$this->load->view('Quiz_freesubscription',$this->data);
	}	
	public function docupload()
	{
		//$this->main_menu();
		$this->load->view('Quiz_docupload',$this->data);
	}
	/*public function quizdocupload()
    {
        $this->load->model('login_model');
        $this->load->model('Quiz_model', 'quiz_model');
        $arrtopmenudetails = $this->login_model->get_top_menus();

        foreach ($arrtopmenudetails as $arrtopmenudetailss) {
            $arrmenudetails[$arrtopmenudetailss['MAIN_MENU']] = $this->login_model->get_menu($arrtopmenudetailss['MAIN_MENU']);
        }
        $this->data['arrtopmenudetails'] = $arrtopmenudetails;
        $this->data['arrmenudetails'] = $arrmenudetails;
        $this->load->library('form_validation');

        $this->form_validation->set_rules('type', 'Type', 'max_length[25]');
        $this->form_validation->set_rules('service', 'Service', 'max_length[25]');
        $this->form_validation->set_rules('operation', 'Operation', 'max_length[25]');
        $this->form_validation->set_rules('file', 'File', 'max_length[100]');
        $this->form_validation->set_rules('no_of_que', 'No Of Que', 'integer|greater_than[0]');
        $this->form_validation->set_rules('quizname', 'Quizname', 'max_length[25]');
        $this->form_validation->set_rules('start_qno', 'Start Qno', 'integer');
        $this->form_validation->set_rules('end_qno', 'End Qno', 'integer|greater_than[0]');
        $this->form_validation->set_rules('subject', 'Subject', 'max_length[25]');

        if ($this->form_validation->run()) {
            $params = array(
                'type' => $this->input->post('type'),
                'service' => $this->input->post('service'),
                'operation' => $this->input->post('operation'),
                'file' => 'file',
                'no_of_que' => $this->input->post('no_of_que'),
                'quizname' => $this->input->post('quizname'),
                'start_qno' => $this->input->post('start_qno'),
                'end_qno' => $this->input->post('end_qno'),
                'subject' => $this->input->post('subject'),
            );

      //      $upload_question_id = $this->Admin_Model->add_upload_question($params);
           $uploadRslt = $this->Admin_Model->truncate_question_upload();
			$file_name = $_FILES['docfile']['name'];
			print_r($file_name);
			//die;
			$no_of_ques = trim($this->input->post('no_of_que'));
			$startno = trim($this->input->post('start_qno'));
			$subject = trim($this->input->post('subject'));
			
            $config['upload_path'] = './uploads/';
            $config['allowed_types'] = '*';
            //$config['file_name'] = $ph_no.'.jpg';
            $this->load->library('upload', $config);

            $this->upload->do_upload('docfile');
			print_r($this->upload->file_name);
            //$xml = $this->print_r_xml($params);

          	$upld_path = realpath(APPPATH . '../uploads');
			$upld_file = $upld_path.'\\'.$this->upload->file_name;
/*
             $xml='<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
            <command>
            <quiztype>NEET</quiztype>
            <username>Shashank</username>
            <request>Question</request>
            <service>upload</service>
            <operation>uploadquestion</operation>
            <documentname>C:\installations\questionBank\NEET\PART TEST - 1 QP.docx</documentname>
            <quizid>103</quizid>
            <questioncount>180</questioncount>
            <startnumber>1</startnumber>
            <subject>Mathematics</subject>
            </command>';
             

            $URL = "http://localhost:8080/docRest/command/uploadquestion";
           $xml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<command>
<username>Shashank</username>
<request>Answer</request>
<service>upload</service>
<operation>uploadanswer</operation>
<documentname>C:\installations\questionBank\NEET\PART TEST - 8 AK.docx</documentname>
<quizid>110</quizid>
<quiztype>NEET</quiztype>
<questioncount>180</questioncount>
<startnumber>1</startnumber>
<subject>Mathematics</subject>
</command>';
print_r($upld_file);
//die;
               $URL = "http://localhost:8080/docRest/command/uploadquestion/2.0";

            $xml='<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
            <command>
            <username>Shashank</username>
            <request>Question</request>
            <service>upload</service>
            <operation>uploadquestion</operation>
            <documentname>'.$upld_file.'</documentname>
            <quizid>523</quizid>
            <quiztype>Questions</quiztype>
            <questioncount>'.$no_of_ques.'</questioncount>
            <startnumber>1</startnumber>
             <subject>Maths</subject>
            </command>';    

            $ch = curl_init($URL);
            //curl_setopt($ch, CURLOPT_MUTE, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,500);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/xml', 'Accept: application/xml'));
            curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $output = curl_exec($ch);
            curl_close($ch);
          //  print_r($output);
				$result = $this->Admin_Model->getQuestionUpload();
			if(is_array($result) && sizeof($result) > 0){
				redirect(base_url()."Question_upload/disp_question_data");
			}else{
				$this->session->set_flashdata('message_name', 'Data could nut be saved . Try again later.'); 
				redirect(base_url() . "Admin/docupload");
				//$this->load->view('Quiz_docupload', $this->data);
			}

           // $this->load->view('Quiz_docupload', $this->data);
        } else {
            //   $data['_view'] = 'views/add';
            $this->load->view('Quiz_docupload', $this->data);
        }
    }

	public function delete_freesub_row() { 
		$id = $_GET['id'];
		$this->main_menu();
		$this->data['result'] = $this->Admin_Model->deleterow($id);
	//print_r($this->data['result']);
	//$this->data = $this->Admin_Model->getstud_information($user_id);
		$this->data['quiz_sub']= $this->Admin_Model->findAllForquizfreesub();
		$this->load->view('Quiz_freesubscription',  $this->data);
	}

	public function lettertoprincipal() { 
		$id = $_GET['id'];
	//print_r($id);

	//$this->load->library('get_coupon');
		$this->main_menu();
		$this->data['result'] = $this->Admin_Model->findAllForquizfreesubgencoupon($id);
	//print_r($this->data['result']);
		$this->load->view('lettertoprincipal',  $this->data);
	}
*/
     public function quizdocupload() {
    // Get top menu details
    $role_id = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : null;
    $username = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Guest';

    if ($role_id) {
        $top_menus = $this->menu_model->get_top_menus($role_id);
    } else {
        $top_menus = [];
    }

    $data['top_menus'] = $top_menus;
    $data['user_role'] = $role_id;
    $data['user_name'] = $username;

   
    // Admin menu
	$arrtopmenudetails = $this->menu_model->get_top_menus($role_id);
	$arrmenudetails = [];

	foreach ($arrtopmenudetails as $menu) {
		$arrmenudetails[$menu['MAIN_MENU']] = 
		$this->menu_model->get_top_menus($menu['MAIN_MENU']);
        //$this->Admin_model->get_menu($menu['MAIN_MENU']);
	}

	$data['arrtopmenudetails'] = $arrtopmenudetails;
	$data['arrmenudetails'] = $arrmenudetails;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        
		$no_of_que = trim(isset($_POST['no_of_que']) ? $_POST['no_of_que'] : '');
		$start_qno = trim(isset($_POST['start_qno']) ? $_POST['start_qno'] : '');
		$subject   = trim(isset($_POST['subject']) ? $_POST['subject'] : '');

        if ($no_of_que === '' || !is_numeric($no_of_que) || $no_of_que <= 0) {
            $_SESSION['message_name'] = 'Invalid number of questions';
            redirect(base_url() . "Admin/docupload");
            return;
        }

        if ($start_qno !== '' && !is_numeric($start_qno)) {
            $_SESSION['message_name'] = 'Invalid start question number';
            redirect(base_url() . "Admin/docupload");
            return;
        }

        if (strlen($subject) > 25) {
            $_SESSION['message_name'] = 'Subject name must be less than 25 characters';
            redirect(base_url() . "Admin/docupload");
            return;
        }

      
        $this->Admin_Model->truncate_question_upload();

       
        if (isset($_FILES['docfile']) && $_FILES['docfile']['name'] != '') {

           
            $config['upload_path']   = './uploads/';
            $config['allowed_types'] = '*';
            $this->upload->initialize($config);

            if (!$this->upload->do_upload('docfile')) {
                $this->session->set_flashdata('message_name', $this->upload->display_errors());
                redirect(base_url() . "Admin/docupload");
                return;
            }

            $uploadData = $this->upload->data();
            $upld_file = realpath(APPPATH . '../uploads') . '/' . $uploadData['file_name'];

            
            $xml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
            <command>
                <username>Shashank</username>
                <request>Question</request>
                <service>upload</service>
                <operation>uploadquestion</operation>
                <documentname>' . $upld_file . '</documentname>
                <quizid>523</quizid>
                <quiztype>Questions</quiztype>
                <questioncount>' . $no_of_que . '</questioncount>
                <startnumber>' . $start_qno . '</startnumber>
                <subject>' . $subject . '</subject>
            </command>';

            
            $URL = "http://localhost:8080/docRest/command/uploadquestion/2.0";
            $ch = curl_init($URL);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/xml', 'Accept: application/xml']);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $output = curl_exec($ch);
            curl_close($ch);

            
            $result = $this->Admin_Model->getQuestionUpload();
            if (is_array($result) && count($result) > 0) {
                redirect(base_url() . "Question_upload/disp_question_data");
                return;
            } else {
                $this->session->set_flashdata('message_name', 'Data could not be saved. Try again later.');
                redirect(base_url() . "Admin/docupload");
                return;
            }

        } else {
            $_SESSION['message_name'] = 'Please select a file to upload.';
            redirect(base_url() . "Admin/docupload");
            return;
        }

    } else {
       
        $this->load->view('Quiz_docupload', $data);
    }
}


    public function delete_freesub_row() { 
        $id = $this->input->get('id');
        //$this->main_menu();
        $this->data['result'] = $this->Admin_Model->deleterow($id);
        $this->data['quiz_sub'] = $this->Admin_Model->findAllForquizfreesub();
        $this->load->view('Quiz_freesubscription', $this->data);
    }

    public function lettertoprincipal() { 
        $id = $this->input->get('id');
        //$this->main_menu();
        $this->data['result'] = $this->Admin_Model->findAllForquizfreesubgencoupon($id);
        $this->load->view('lettertoprincipal', $this->data);
    }

	public function get_statusdetails()
	{
		//$this->main_menu();
		$this->load->model('Admin_Model');
		//print_r($_POST);
		$fromdate=$this->input->post('fromdate');
		$todate=$this->input->post('todate');
	//print_r($date);
		$Status=$this->input->post('Status');
		$package_id=$this->input->post('package_id');
	//print_r($Status);
	    $this->data['package']=$this->Admin_Model->getpackagename();
		$this->data['role']=$this->Admin_Model->getpgstatus();
		$this->data['payment'] = $this->Admin_Model->get_statusondateandstatus($fromdate,$todate,$Status,$package_id);
	//print_r($payment);
		$this->load->view('payment_status', $this->data);
	}
	public function list_user() 
	{
		//$this->main_menu();
		$this->load->model('Admin_Model');
        $this->data['user']= $this->Admin_Model->findActiveForSummaryPage();
		$this->data['role']= $this->Admin_Model->getrollid();
		$this->load->view('list_user', $this->data);
	}
	
	public function listent_user() 
	{
		//$this->main_menu();
		$this->load->model('Admin_Model');
        $this->data['user']= $this->Admin_Model->findentranceuser();
		$this->data['role']= $this->Admin_Model->getrollid();
	//	print_r($this->data['user']);
		$this->load->view('listent_user', $this->data);
	}
	public function get_role_id()
	{
		//$this->main_menu();
		$this->load->model('Admin_Model');
		$role_id=$this->input->get('role');
	//print_r($role_id);
		$this->data['role']= $this->Admin_Model->getrollid();
		$this->data['user'] = $this->Admin_Model->get_user_details($role_id);
	//print_r($this->data['user']);
		$this->load->view('list_user', $this->data);

	}
	public function subscription_details() 
	{
		//$this->main_menu();
		$this->load->model('Admin_Model');
		$this->data['sub_det']= $this->Admin_Model->findallsubscriptiondetails();
		//print_r($this->data['sub_det']);
		$this->load->view('subscription_report', $this->data);
	}
	public function get_subdetails()
	{
		//$this->main_menu();
		$this->load->model('Admin_Model');
		$role_id=$this->input->get('role');
	//print_r($role_id);
		$this->data['sub_det'] = $this->Admin_Model->get_sub_details($role_id);

		$this->load->view('subscription_report', $this->data);

	}
	public function edit_studentdetails()
	{
		$user_id = $_GET['user_id'];
		$package_id = $_GET['package_id'];
		//$this->main_menu();
		$this->data['result'] = $this->Admin_Model->get_student_details($user_id,$package_id);
		$this->load->view('student_profileanddetails', $this->data);

	}
	public function print_studentrankdetails()
	{
		$user_id = $_GET['user_id'];
		//Print_r($user_id);
		//$this->main_menu();
		$this->data['result'] = $this->Admin_Model->get_studentrankdetails($user_id);
		//print_r($this->data['result']);
		$this->load->view('student_allrankdetails', $this->data);
	}
	public function assign_package()
	{
		$user_id = $_GET['user_id'];
		$user_name = $_GET['user_name'];
		//Print_r($user_id);
		//$this->main_menu();
		$sql = "select count(*) count from subscription_details where username = '$user_name'";
			$result = $this->Admin_Model->get_data($sql);
			if ($result[0]['count'] > 10) {
				 $url = base_url() . "admin/list_user";
                     echo "<script>alert('User is already Subscribed to package');
					window.location.href='$url';
					</script>";
                }
				
		$this->data['result'] = $this->Admin_Model->get_userpackagedetails($user_id);
		$this->data['result1'] = $this->Admin_Model->packageinfo();
		//print_r($this->data['result1']);
		$this->load->view('assign_package', $this->data);
	}
	public function save_userdetails() {
		//print_r($_post);
	//$this->main_menu();
	$this->data['result']= $this->Admin_Model->insertuserdetails();
	//$this->data['result1']= $this->Admin_Model->updateuserdetails();
	redirect(base_url()."Admin/list_user");
	}

	public function jnanasudhastudornot(){
	//$this->main_menu();
	$this->load->model('Admin_Model');
	$this->data['user']= $this->Admin_Model->findAllForSummaryPage();
	$this->load->view('js_studentornot', $this->data);
	}
	public function savestud_info(){
		//$this->main_menu();
		$this->Admin_Model->saveinforecords();
		$this->data['user']= $this->Admin_Model->findAllForSummaryPage();
		$this->load->view('js_studentornot',$this->data);
	}
	
	public function clearquizresponse(){
		//$this->main_menu();
		$this->data['result']= $this->Admin_Model->getquizinfo();
		//print_r($this->data['result']);
		$this->load->view('clearquizresponse',$this->data);
	}
	
	public function saveclearresponse(){
		$quiz_id = $_POST['quiz_id'];
		//print_r($quiz_id);
		$user_name = $_POST['user_name'];
		//print_r($user_name);
		//$this->main_menu();
		$this->Admin_Model->saveclearres($quiz_id,$user_name);
		$this->session->set_flashdata('message_name', 'Clear Reponse Record Saved Successfully'); 
		redirect(base_url() . "Admin/clearquizresponse");
		
	}
	public function resumequiz(){
		//$this->main_menu();
		$this->data['result']= $this->Admin_Model->getquizinfo();
		//print_r($this->data['result']);
		$this->load->view('resumequiz',$this->data);
	}
	public function saveresume(){
		$quiz_id = $_POST['quiz_id'];
		//print_r($quiz_id);
		$user_name = $_POST['user_name'];
		//print_r($user_name);
		//$this->main_menu();
		$this->Admin_Model->saveresume($quiz_id,$user_name);
		$this->session->set_flashdata('message_name', 'Resume Record Saved Successfully'); 
		redirect(base_url() . "Admin/resumequiz");
	}
	public function updatequizresult(){
		//$this->main_menu();
		$this->data['result']= $this->Admin_Model->getquizinfo();
		$this->load->view('updatequizresult',$this->data);
		
	}
	public function calculateresultagain(){
		
	$this->data['result']= $this->Admin_Model->calculateresultagain();
		
	}
	public function updatequizresults(){
		$this->load->model('Quiz_model', 'quiz_model');
		$quiz_id = $_POST['quiz_id'];
		//print_r($quiz_id);
		$user_name = $_POST['user_name'];
		$grace =  $_POST['grace'];
		
		
		//$this->main_menu();
		$this->data['result']= $this->Admin_Model->getquizinfo();
		if($_POST['showvalues']==1){ 
		
		if ($user_name=='8888888888' || $user_name=='' )
		{
			print_r($user_name);
			
			//die;
			if($grace == 1)
			{
				$this->data['res']=$this->Admin_Model->insertresultForcompletequizgrace($quiz_id);
			}
			else{
				print_r($grace);
			
		$this->data['res']=$this->Admin_Model->insertresultForcompletequiz($quiz_id);
			}
		
		}
		ELSE
		{
		
		$sql = "select count(*)count from student_quiz_result where user_id = '$user_name' and quiz_id = '$quiz_id'";
			//print_r($sql);
			$result = $this->quiz_model->get_data($sql);
			//print_r($result);
			if ($result[0]['count'] > 0) {
				//echo('here');
				if($stid == '' || $stid == null)
				{
					$st_id= '';
				} else {
					$st_id= $stid;
				}
			//print_r($st_id);
			$this->data['res']=$this->Admin_Model->insertresultForquiz($quiz_id,$user_name,$st_id);
                  $this->session->set_flashdata('message_name', 'Quiz Result is Already Updated'); 
				redirect(base_url() . "Admin/updatequizresults");
                }
			else {	
				//$this->data['quiz_details']= $this->Admin_Model->showresultForquiz($quiz_id,$user_name);
				//print_r($this->data['quiz_details']);
				$stid = $this->Admin_Model->getstid($quiz_id,$user_name);
				//$st_id= $stid[0]['st_id'];
				if($stid == '' || $stid == null)
				{
					$st_id= '';
				} else {
					$st_id= $stid;
				}
			//print_r($st_id);
			$this->data['res']=$this->Admin_Model->insertresultForquiz($quiz_id,$user_name,$st_id);
			$this->session->set_flashdata('message_name', 'Quiz Result is Inserted Successfully'); 
			redirect(base_url() . "Admin/updatequizresults");	
			}
		}
		}
		//$this->data['res']=$this->Admin_Model->insertresultForquiz($quiz_id,$user_name,$st_id);
		$this->load->view('updatequizresult',$this->data);
	}
	
		public function updatethequizdetails(){
		$quiz_id = $_POST['quiz_id'];
		//print_r($quiz_id);
		$user_name = $_POST['user_name'];
		//print_r($user_name);
		//$this->main_menu();
		$this->data['result']= $this->Admin_Model->getquizinfo();
		$stid = $this->Admin_Model->getstid($quiz_id,$user_name);
		$st_id= $stid[0]['st_id'];
			//print_r($st_id);
		$this->data['res']=$this->Admin_Model->insertresultForquiz($quiz_id,$user_name,$st_id);
		$this->session->set_flashdata('message_name', 'Quiz Result is Inserted Successfully'); 
				redirect(base_url() . "Admin/updatequizresults");	
		
	}
	
	
	/*public function updatethequizdetails(){
	$quiz_id = $_POST['quiz_id'];
	//print_r($quiz_id);
	$user_name = $_POST['user_name'];
	//print_r($user_name);
	$this->main_menu();
	$this->data['result']= $this->Admin_Model->getquizinfo();
	$this->session->set_flashdata('message_name', 'Quiz Result is Inserted Successfully'); 
			redirect(base_url() . "Admin/updatequizresults");	
	
	}*/
	public function resetpsd(){
	//$this->main_menu();
	$user_name = $_GET['user_name'];
	$this->data['result'] = $this->Admin_Model->get_userdetails($user_name);
	$this->load->view('resetpassword',$this->data);		
	}
	public function save_userresetpsd(){
		//$this->main_menu();
		$this->Admin_Model->updatenewpass();
		$this->session->set_flashdata('message_name', 'Your password is successfully updated'); 
	redirect(base_url() . "Admin/resetpsd");
		
	}
	public function quiznewpack()
	{
		//$this->main_menu();	
		//print_r($_POST);
		$package_id = $_POST['package_id'][0];
		$i=1;
		foreach ($_POST['quiz_id'] as $key => $value) {
		//print_r($key);

		$arrInsertData[$i] =
		[
		'quiz_id' =>  $_POST['quiz_id'][$key],
		'quiz_name' =>  $_POST['quiz_name'][$key],
		'package_id' =>  $_POST['package_id'][$key],
		//'package_name' =>  $_POST['quiz_id'][$key],
		'start_date' => $_POST['strdate'][$key],
		'end_date' =>  $_POST['enddate'][$key],
		];
		$i=$i+1;

		}
		//print_r($arrInsertData);
		$sql = "delete from quiz_package_link where package_id ='$package_id'";
		$query = $this->db->query($sql);

		$this->db->insert_batch('quiz_package_link', $arrInsertData);
		
		$this->session->set_flashdata('message_name', 'Records are successfully Inserted'); 
		redirect(base_url() . "Admin/Quiz_list_package");
	}
	
	public function addquiztype()
	{
		//$this->main_menu();	
		//$this->data['result'] = $this->Admin_Model->savenewpack();
		$this->load->view('addquiztype',$this->data);		
	}
	public function savequiztype(){
		//$this->main_menu();	
		//print_r($_POST);
		
		$type=[];
		$type['quiztype']=$_POST['quiztype'];
		$type['crtmark']=$_POST['crtmark'];
		$type['wrmark']=$_POST['wrmark'];
		
		$quiztypeid= $this->Admin_Model->savequiztypedetails($type);

		//print_r($data);
		$this->Admin_Model->savearraydetails($quiztypeid);
		$this->session->set_flashdata('message_name', 'Data saved successfully'); 
		redirect(base_url() . "Admin/addquiztype");
	}
	public function quiz_static_report()
	{
		//$this->main_menu();
		$this->load->model('Quiz_model', 'quiz_model');
		$id = $this->input->get('id');
		$rs = $this->Admin_Model->chk_report_static_quiz($id);
		if ($rs == '0') {
			$this->data['result'] = $result = $this->quiz_model->report_static_quiz($id);
		} else {
			redirect(base_url() . "quiz/get_static_mixed_quiz?id=" . $id);
		}
		$this->load->view('Quiz_reportstaic', $this->data);
	}
	public function save_static_quiz()
	{
		//$this->main_menu();
		$this->load->model('Admin_Model');
		$this->Admin_Model->save_static_quiz();
		$this->session->set_flashdata('message_name', 'Quiz saved successfully.'); 
		redirect(base_url() . "Admin/add_quiz");
		
	}
	public function assign_category()
	{
		//$this->main_menu();
		$this->load->model('Admin_Model');
		$this->data['result1']=$this->Admin_Model->get_allquiz_subject();
		$redirt=0;
		if(is_array($this->data['result1'])==true)
		{
			foreach($this->data['result1'] as $row)
			{
				$redirt=1;
			}
		}
		if($redirt==0)
		{
			$url=base_url()."Admin/quiz_subject";
			echo "<script>alert('Please Fill the Subject name and category');
			window.location.href='$url';
			</script>";
		}
		$this->data['result'] ='';// $this->quiz_model->get_allquestion();
		$this->data['title']='Quiz';
			$this->load->view('assign_category', $this->data);
	}
	public function save_category()
	{
		//$this->main_menu();
		$this->load->model('Admin_Model');
		$this->Admin_Model->save_category();

		$this->session->set_flashdata('message_name', 'Questions Updated for New Category'); 
		redirect(base_url() . "Admin/assign_category");
		
	}
	
    public function get_question()
    {
		//$this->main_menu();
        $this->load->model('Admin_Model');
		$id=$this->input->get('id');
		$parameter=$this->input->get('parameter');
		$result = $this->Admin_Model->get_questionby_categoryandsubject1($id,$parameter);
		$this->data['result1']=$this->Admin_Model->get_allquiz_subject();
		//print_r($result);
		$redirt=0;
		if(is_array($this->data['result1'])==true)
		{
			foreach($this->data['result1'] as $row)
			{
				$redirt=1;
			}
		}
		$this->data['result']=$result;
		//print_r($result);
       $this->load->view('assign_category', $this->data);

    }
	public function check_quizCategory()
	{
		//$this->main_menu();
		$rs = $this->uri->segment_array();
		$this->load->model('Admin_Model');
		$num = $this->Admin_Model->chk_subjectnamecategory($rs[4], $rs[6]);
		if ($num == "o") {
			echo "1";
		} else {
			echo "o";
		}
	}
	public function authoriserecord()
    {
        //$this->main_menu();
        $name = $this->input->get('id');
        $cls = $this->input->get('info');
        $this->Admin_Model->authoriserecord($name, $cls);
    }
	public function changeuserpass()
    {
		//$this->main_menu();
        $this->load->view('changeuserpass');
    }
	public function changeuserpass1()
    {
       //$this->main_menu();
        //$this->login_model->updatepass1();
        $url = base_url() . "Admin/changeuserpass";
        echo "<script>alert('password is successfully updated');
        window.close();
        </script>";
    }
public function getjeerank()
    {
       // $this->main_menu();
        $sub = $this->input->post('subject_name');
        $cat = $this->input->post('category');
		$batch = $this->input->post('batch');
		$this->data['var'] = '';
			if ($batch == ''){
				$batch='all';
			}
		
		
		$this->load->model('Admin_Model');
        $this->data['result'] = $this->Admin_Model->getjeerank($cat,$batch);
        $this->data['result_sub'] = $this->Admin_Model->get_allquiz_subject();

        // print_r($this->data['result']);
        // print_r($this->data['result_sub']);
        $this->load->view('getjeerank', $this->data);
    }
	
	// public function getsubjectmathrank()
    // {
    //     //$this->main_menu();
    //     $sub = $this->input->post('subject_name');
    //     $cat = $this->input->post('quiz_name');
	// 	$batch = $this->input->post('batch');
	// 	$this->data['var'] = '';
	// 		if ($batch == ''){
	// 			$batch='all';
	// 		}
		
		
	// 	$this->load->model('Admin_Model');
    //     $this->data['result'] = $this->Admin_Model->getsubmathrank($cat);
       

    //     // print_r($this->data['result']);
    //     // print_r($this->data['result_sub']);
	// 	 $this->data['quiz_details'] = $this->Admin_Model->getsubjectmathquiz();
    //     $this->load->view('getsubjectmathrank', $this->data);
    // }
	public function getsubjectmathrank()
{
    $sub = isset($_POST['subject_name']) ? trim($_POST['subject_name']) : '';
    $cat = isset($_POST['quiz_name']) ? trim($_POST['quiz_name']) : '';
    $batch = isset($_POST['batch']) ? trim($_POST['batch']) : '';

    if ($batch == '') {
        $batch = 'all';
    }

    $data = array();
    $data['var'] = '';

    $db = get_db();

    // If $cat is empty, do not run the query
    if ($cat != '') {
        $cat_escaped = $db->escape($cat);  
        $sql = "SELECT * FROM student_quiz_result WHERE quiz_id = $cat_escaped";
        $data['result'] = db_query($sql)->result_array();
    } else {
        $data['result'] = array(); 
    }

    // Quiz details
    if ($sub != '') {
        $sub_escaped = $db->escape($sub);
        $sql2 = "SELECT * FROM quiz_subject WHERE subject_name = $sub_escaped";
        $data['quiz_details'] = db_query($sql2)->result_array();
    } else {
        $data['quiz_details'] = array();
    }

    $this->load->view('getsubjectmathrank', $data);
}

	public function getstrank()
    {
       // $this->main_menu();
		$this->load->model('Admin_Model');
        $sub = $this->input->post('subject_name');
        $cat = $this->input->post('category');
        $this->data['var'] = '';

		if($cat){
			$this->load->model('Admin_Model');
        $this->data['result'] = $this->Admin_Model->getjeerank($cat);
		}

        $this->data['result_sub'] = $this->Admin_Model->get_allquiz_subject();

        // print_r($this->data['result']);
        // print_r($this->data['result_sub']);
        $this->load->view('getstrank', $this->data);
    }

	 public function quiz_view()
    {
        //$this->main_menu();
		$this->load->model('Quiz_model', 'quiz_model');
        $this->data['result1'] = $this->quiz_model->get_allquiz_subject();
        $redirt = 0;
        if (is_array($this->data['result1']) == true) {
            foreach ($this->data['result1'] as $row) {
                $redirt = 1;
            }
        }
        if ($redirt == 0) {
            $url = base_url() . "Admin/quiz_subject";
            echo "<script>alert('Please Fill the Subject name and category');
            window.location.href='$url';
            </script>";
        }
        $this->data['title'] = 'Quiz';
        $this->load->view('Quiz_view', $this->data);
    }
	 public function quiz_questiondetail()
    {
        //$this->main_menu();
        $subject = $this->input->post('subject_name');
        $category = $this->input->post('category1');
        $this->load->model('Quiz_model', 'quiz_model');
        $result = $this->quiz_model->get_questionby_categoryandsubject1($subject, $category);
        $this->data['result'] = $result;
        $this->load->view('Quiz_detail', $this->data);
    }
	public function get_question1()
    {
       // $this->main_menu();
        $rs = $this->uri->segment_array();
		//print_r($rs);
        if ($rs[8]) {
            $this->load->model('Admin_Model');
            $result = $this->Admin_Model->get_questionby_categoryandsubject12($rs[4], $rs[6], $rs[8]);
         //  print_r(count($result));
           
            $data1 = "";
            for ($i = 0; $i < count($result); $i++) {
                if ($i > 0) {
                    $data1 = $data1 . ",,,";
                }

                $data1 = $data1 . "" . $result[$i]['id'];
                $data1 = $data1 . "~,~,~" . $result[$i]['question_name'];
                $data1 = $data1 . "~,~,~" . $result[$i]['op_a'];
                $data1 = $data1 . "~,~,~" . $result[$i]['op_b'];
                $data1 = $data1 . "~,~,~" . $result[$i]['op_c'];
                $data1 = $data1 . "~,~,~" . $result[$i]['op_d'];
            }
            echo $data1;
        } else {
            //  $subject=$this->input->post('subject_name');
            // $category=$this->input->post('category1');
            $this->load->model('Admin_Model');
            $result = $this->Admin_Model->get_questionby_categoryandsubject1($rs[4], $rs[6]);
            //print_r($result);
            /* $result = $this->quiz_model->get_questionby_categoryandsubject($rs[4],$rs[6]);*/

            $data1 = "";
            for ($i = 0; $i < count($result); $i++) {
                if ($i > 0) {
                    $data1 = $data1 . ",,,";
                }

                $data1 = $data1 . "" . $result[$i]['id'];
                $data1 = $data1 . "~,~,~" . $result[$i]['question_name'];
                $data1 = $data1 . "~,~,~" . $result[$i]['op_a'];
                $data1 = $data1 . "~,~,~" . $result[$i]['op_b'];
                $data1 = $data1 . "~,~,~" . $result[$i]['op_c'];
                $data1 = $data1 . "~,~,~" . $result[$i]['op_d'];
            }
            echo $data1;

        }

    }
	public function getlibrank()
    {
        //$this->main_menu();
		$this->load->model('Admin_Model');
        $sub = $this->input->post('subject_name');
        $cat = $this->input->post('category');
        $this->data['var'] = '';

		if($cat){
        $this->data['result'] = $this->Admin_Model->getjeerank($cat);
		}

        $this->data['result_sub'] = $this->Admin_Model->get_allquiz_subject();

        // print_r($this->data['result']);
        // print_r($this->data['result_sub']);
        $this->load->view('getstrank', $this->data);
    }
	
      public function getallconsolrankjee()
    {
        //$this->main_menu();
		$this->load->model('Admin_Model');
        $sub = $this->input->post('subject_name');
        $cat = $this->input->post('category'); 
        $this->data['var'] = '';
       // $this->data['result'] = $this->Admin_Model->get_consol_rankjee($cat);

        //     $this->data['result_sub']=$this->quiz_model->get_allquiz_subject();

        // print_r($this->data['result']);
        // print_r($this->data['result_sub']);
        $this->load->view('getconsolrankjee', $this->data);
    }
	public function add_newquiz1()
	{
		//$this->main_menu();
		$this->load->model('Admin_Model');
		$this->data['result1'] = $this->Admin_Model->get_allquiz_subject();
		$redirt = 0;
		if (is_array($this->data['result1']) == true) {
			foreach ($this->data['result1'] as $row) {
				$redirt = 1;
			}
		}
		if ($redirt == 0) {
			$url = base_url() . "Admin/quiz_subject";
			echo "<script>alert('Please Fill the Subject name and category');
			window.location.href='$url';
			</script>";
		}
	//    $this->data['result'] = $this->quiz_model->get_allquestion();
		$this->data['title'] = 'Quiz';
		$this->load->view('Quiz_dynamicSubject', $this->data);
	}
    public function show_dynamicquiz()
	{
		//$this->main_menu();
		$this->load->model('Admin_Model');
		$this->data['result1'] = $this->Admin_Model->get_allquiz_subject();
		$redirt = 0;
		if (is_array($this->data['result1']) == true) {
			foreach ($this->data['result1'] as $row) {
				$redirt = 1;
			}
		}
		if ($redirt == 0) {
			$url = base_url() . "quiz/quiz_subject";
			echo "<script>alert('Please Fill the Subject name and category');
			window.location.href='$url';
			</script>";
		}
		$this->Admin_Model->get_maxlevelquiz();
			//$this->nativesession->set('r1',$this->data['r1']);
		$this->load->view('dynamicquiz', $this->data);
	}
	public function save_dynamic_quiz()
	{
		//$this->main_menu();
		$this->load->model('Admin_Model');
		$this->Admin_Model->save_dynamic_quiz();
		$static = $this->nativesession->get('Static_mixed');
		if ($static == '1') {
			$this->nativesession->set('Static_mixed', '0');
			$max = $this->Admin_Model->get_max_dynamicquiz();

			$category = explode(",", $max[0]['category_name']);
			$order = implode(',',$_POST['ordr']);
			print_r($category);
			print_r($order);
			die;
			$level = explode(",", $max[0]['levels']);
			$levels = explode(",", $max[0]['difficulty_level']);
			$k = 0;
			$data = "";

			$x = 0;
			for ($i = 0; $i < count($category); $i++) {

				for ($j = 0; $j < $level[$i]; $j++) {
					if ($data == "") {
						if ($levels[$x] > 0) {
							$data = $this->Admin_Model->get_allquedynamic_quiz_result($category[$i], $j + 1, $levels[$x]);
						}

					} else if ($j == ($level[$i] - 1) && $i == (count($category) - 1)) {

						if ($levels[$x] > 0) {
							$data = $data . ",,,";
							$data = $data . $this->Admin_Model->get_allquedynamic_quiz_result($category[$i], $j + 1, $levels[$x]);
						}
					} else {
						if ($levels[$x] > 0) {
							$data = $data . ",,,";
							$data = $data . $this->Admin_Model->get_allquedynamic_quiz($category[$i], $j + 1, $levels[$x]);

						}
					}
					$x++;
				}
			}
				//$this->data['result']=$data;
				//print_r($data);
			$final = "";
			$t_rs1 = explode(',,,', $data);
			for ($i = 0; $i < count($t_rs1); $i++) {
				$t_rs2 = explode('~,~,~', $t_rs1[$i]);
				if ($i == 0) {
					$final = $t_rs2[0];
				} else {
					$final = $final . "," . $t_rs2[0];
				}

			}

				//print_r($max);
				//    print_R($final);
			$final1 = explode(',', $final);
	//$nque=
	$quiz_type=$_POST['quiz_type'];
			$this->Admin_Model->save_static_quiz_mixed($max[0]['Quiz_name'], $max[0]['time_limit'], $max[0]['subject_name'], count($final1), $final,$quiz_type);
			$this->Admin_Model->delete_static_quiz_mixed($max[0]['id']);
		}

		$url = base_url() . "Admin/add_quiz";
		echo "<script>alert('Quiz saved successfully.');
		window.location.href='$url';
		</script>";
	}
	 public function update_quizquestion1()
    {  
	// echo '<pre>';
	// print_r($_POST);
	// echo '</pre>';
		//$this->main_menu();
        $que = '';
        $op1 = '';
        $op2 = '';
        $op3 = '';
        $op4 = '';
        $dis = '';
        $this->load->model('Admin_Model');
        //$file = $_FILES['DATAFILE1']['tmp_name'];
        if ($_FILES['DATAFILE1']['name']) {
            $upOne = realpath(__DIR__ . '/../..');
            $target_Path = $upOne . "/assets/uploaded_xl/" . basename($_FILES['DATAFILE1']['name']);
            if (move_uploaded_file($_FILES['DATAFILE1']['tmp_name'], $target_Path)) {
                $que = base_url() . "assets/uploaded_xl/" . basename($_FILES['DATAFILE1']['name']);
            }
        }

        if ($_FILES['DATAFILE2']['name']) {
            $upOne = realpath(__DIR__ . '/../..');
            $target_Path = $upOne . "/assets/uploaded_xl/" . basename($_FILES['DATAFILE2']['name']);
            if (move_uploaded_file($_FILES['DATAFILE2']['tmp_name'], $target_Path)) {
                $op1 = base_url() . "assets/uploaded_xl/" . basename($_FILES['DATAFILE2']['name']);
            }
        }

        if ($_FILES['DATAFILE3']['name']) {
            $upOne = realpath(__DIR__ . '/../..');
            $target_Path = $upOne . "/assets/uploaded_xl/" . basename($_FILES['DATAFILE3']['name']);
            if (move_uploaded_file($_FILES['DATAFILE3']['tmp_name'], $target_Path)) {
                $op2 = base_url() . "assets/uploaded_xl/" . basename($_FILES['DATAFILE3']['name']);
            }
        }

        if ($_FILES['DATAFILE4']['name']) {
            $upOne = realpath(__DIR__ . '/../..');
            $target_Path = $upOne . "/assets/uploaded_xl/" . basename($_FILES['DATAFILE4']['name']);
            if (move_uploaded_file($_FILES['DATAFILE4']['tmp_name'], $target_Path)) {
                $op3 = base_url() . "assets/uploaded_xl/" . basename($_FILES['DATAFILE4']['name']);
            }
        }

        if ($_FILES['DATAFILE5']['name']) {
            $upOne = realpath(__DIR__ . '/../..');
            $target_Path = $upOne . "/assets/uploaded_xl/" . basename($_FILES['DATAFILE5']['name']);
            if (move_uploaded_file($_FILES['DATAFILE5']['tmp_name'], $target_Path)) {
                $op4 = base_url() . "assets/uploaded_xl/" . basename($_FILES['DATAFILE5']['name']);
            }
        }

        if ($_FILES['DATAFILE6']['name']) {
            $upOne = realpath(__DIR__ . '/../..');
            $target_Path = $upOne . "/assets/uploaded_xl/" . basename($_FILES['DATAFILE6']['name']);
            if (move_uploaded_file($_FILES['DATAFILE6']['tmp_name'], $target_Path)) {
                $dis = base_url() . "assets/uploaded_xl/" . basename($_FILES['DATAFILE6']['name']);
            }
        }
        //echo $dis;
        //echo "subject_id".$subject_id=$this->quiz_model->get_subjectid($subject);
        //echo "unit_id".$unit_id=$this->quiz_model->get_unitid($subject_id,$category);
        //echo $question_id=$this->quiz_model->get_subid($subject_id,$unit_id);
        //$question_id=$this->quiz_model->get_questionid($subject_id,$unit_id);
        /*if($question_id=="o")
        {
        $question_id=1;
        }
        else
        {
        $question_id=$question_id+1;
    }*/
	// echo "came".$que;
		$this->Admin_Model->update_quizquestion1($que, $op1, $op2, $op3, $op4, $dis);
		$id1 = $this->input->post('id1');
		redirect(base_url() . "Admin/quiz_individualquiz1?res=" . $id1);
	}
	
	public function quiz_individualquiz1()
    {
        //$this->main_menu();
        //$rs=$this->uri->segment_array();
        $rs = $this->input->get('res');
        $this->load->model('Admin_Model');
        $sql = "SELECT a.id, a.question_name,a.mark,a.penalty,a.level,a.discription,a.correctoption,b.question_answer,a.qtype,a.correct_answer
        FROM quiz_question a,quiz_question_answers b
        WHERE a.id = b.Qustion_no and a.id='$rs'";
        $this->data['result'] = $this->Admin_Model->get_data($sql);
        //$this->load->view('Quiz_IndividualQuestion',$this->data);
        $this->load->view('Quiz_IndividualQuestion1', $this->data);
    }
public function add_newquizs()
	{
		//$this->main_menu();
		$this->load->model('Admin_Model');
		$this->data['result1']=$this->Admin_Model->get_allquiz_subject();
		$this->data['quiznametype']=$this->Admin_Model->getquiznametype();
		//print_r($this->data['quiznametype']);
		$redirt=0;
		if(is_array($this->data['result1'])==true)
		{
			foreach($this->data['result1'] as $row)
			{
				$redirt=1;
			}
		}
		if($redirt==0)
		{
			$url=base_url()."Admin/quiz_subject";
			echo "<script>alert('Please Fill the Subject name and category');
			window.location.href='$url';
			</script>";
		}
		// $this->data['result'] = $this->quiz_model->get_allquestion();
		$this->data['title']='Quiz';
		$this->load->view('Add_quiz_new', $this->data);
	}
	public function save_staticrandom_quiz()
	{
		//$this->main_menu();
		$this->load->model('Admin_Model');
		$this->Admin_Model->save_staticrandom_quiz();
		$this->session->set_flashdata('message_name', 'Quiz saved successfully.'); 
		redirect(base_url() . "Admin/add_quiz");
		
	}
	public function getneetcrashrank()
    {
        //$this->main_menu();
		$this->load->model('Admin_Model');
        $sub = $this->input->post('subject_name');
        $cat = $this->input->post('category');
        $this->data['var'] = '';

		if($cat){
			$this->load->model('Admin_Model');
        $this->data['result'] = $this->Admin_Model->getcrashrank($cat);
		}

        $this->data['result_sub'] = $this->Admin_Model->get_allquiz_subject();

        // print_r($this->data['result']);
        // print_r($this->data['result_sub']);
        $this->load->view('getcrashrank', $this->data);
    }
	public function getkcetcrashrank()
    {
       // $this->main_menu();
		$this->load->model('Admin_Model');
        $sub = $this->input->post('subject_name');
        $cat = $this->input->post('category');
        $this->data['var'] = '';

		if($cat){
			$this->load->model('Admin_Model');
        $this->data['result'] = $this->Admin_Model->getkcetrank($cat);
		}

        $this->data['result_sub'] = $this->Admin_Model->get_allquiz_subject();

        // print_r($this->data['result']);
        // print_r($this->data['result_sub']);
        $this->load->view('getkcetrank', $this->data);
    }
public function addppturl(){
		//$this->main_menu();
		$this->load->model('Admin_Model');
		$this->data['quiz_list_package']=$this->Admin_Model->getpackagename();
	//	print_r($this->data['quiz_list_package']);
		$this->data['quiz_id_list']=$this->Admin_Model->getquizid();
		$this->load->view('addppturl', $this->data);
	}
	public function savequizpptlink(){
		//$this->main_menu();
		$this->load->model('Admin_Model');
		$this->data['savequizppt']=$this->Admin_Model->savequizpptlink();
		//print_r($this->data['savequizppt']);
		$this->session->set_flashdata('message_name', 'Record Saved Successfully'); 
		redirect(base_url() . "Admin/addppturl");
	}
	public function pptlinkreport()
	{
		//$this->main_menu();
		$this->load->model('Admin_Model');
		$this->data['linkdetails']= $this->Admin_Model->getpptlinkdetails();
		//print_r($this->data['linkdetails']);
		$this->load->view('pptlinkreport', $this->data);
	}
	public function getneetconsolrank()
	{
		//$this->main_menu();
		$this->load->model('Admin_Model');
		$sub=$this->input->post('sub');
		$batch=$this->input->post('batch');
		$this->data['var'] = '';
			if ($batch == '' && $sub == ''){
				$batch='all';
				$sub='all';
			}
			$this->data['result'] = $this->Admin_Model->get_neet_consol_rank($batch,$sub);
		   //print_r($this->data['result']);
			// print_r($this->data['result_sub']);
			$this->load->view('getneetconsolrank', $this->data);
		}
			public function getneetconsolrankjs()
	{
		//$this->main_menu();
		$this->load->model('Admin_Model');
		$sub=$this->input->post('sub');
		$batch=$this->input->post('batch');
		$this->data['var'] = '';
			if ($batch == '' && $sub == ''){
				$batch='all';
				$sub='all';
			}
			$this->data['result'] = $this->Admin_Model->get_neet_consol_rankjs($batch,$sub);
		   //print_r($this->data['result']);
			// print_r($this->data['result_sub']);
			$this->load->view('getneetconsolrankjs', $this->data);
		}
		
	public function getkcetpcmconsolrank()
	{
		//$this->main_menu();
		$this->load->model('Admin_Model');
		$sub=$this->input->post('sub');
		$batch=$this->input->post('batch');
		$this->data['var'] = '';
			if ($batch == '' && $sub == ''){
				$batch='all';
				$sub='all';
			}
			$this->data['result'] = $this->Admin_Model->get_kcet_consol_rank($batch,$sub);
		   //print_r($this->data['result']);
			// print_r($this->data['result_sub']);
			$this->load->view('getkcetpcmconsolrank', $this->data);
	}
	public function change_rollno_standard()
	{
		//$this->main_menu();
		$this->load->model('Admin_Model');
		///print_r($_POST);
			$user_id = $_POST['user_id'];

			$this->data['result'] = $this->Admin_Model->update_rollno_standard($user_id);
	}
public function update_batch_acc_names()
	{
		//$this->main_menu();
		$this->load->model('Admin_Model');
		//print_r($_POST);
			$user_id = $_POST['user_id'];

			$this->data['result'] = $this->Admin_Model->update_batch_acc_names($user_id);
	}
	public function update_batch_accom()
	{
		//$this->main_menu();
		$this->load->model('Admin_Model');
		//print_r($_POST);
			$user_id = $_POST['user_id'];

			$this->data['result'] = $this->Admin_Model->update_batch_accom($user_id);
	}
public function getntsesatrank()
	{
		//$this->main_menu();
		$this->load->model('Admin_Model');
		$sub = $this->input->post('subject_name');
		$cat = $this->input->post('category');
		$batch = $this->input->post('batch');
		$this->data['var'] = '';
			if ($batch == ''){
				$batch='all';
			}
			$this->data['result'] = $this->Admin_Model->getntsesatrank($cat,$batch);
			$this->data['result_sub'] = $this->Admin_Model->get_allquiz_subject();

			// print_r($this->data['result']);
			// print_r($this->data['result_sub']);
			$this->load->view('getntsesatrank', $this->data);
	}
	public function getntsematrank()
	{
		//$this->main_menu();
		$this->load->model('Admin_Model');
		$sub = $this->input->post('subject_name');
		$cat = $this->input->post('category');
		$batch = $this->input->post('batch');
		$this->data['var'] = '';
			if ($batch == ''){
				$batch='all';
			}
			$this->data['result'] = $this->Admin_Model->getntsematrank($cat,$batch);
			$this->data['result_sub'] = $this->Admin_Model->get_allquiz_subject();

			// print_r($this->data['result']);
			// print_r($this->data['result_sub']);
			$this->load->view('getntsematrank', $this->data);
	}
	
	public function uploadcsv(){
//$this->main_menu();
$this->load->view('uploadCsvView',$this->data);
}
public function upload_data(){
//$this->main_menu();
$this->load->model('Admin_Model');
        $this->Admin_Model->upload_data();
        $this->Admin_Model->insert_to_details();
$this->session->set_flashdata('message_name', 'CSV Saved Successfully');
redirect(base_url() . "Admin/uploadcsv");
}
public function uploadcsvforsubscribe(){
		//$this->main_menu();
		$this->load->view('uploadcsvforsubscribe',$this->data);
	}
	public function upload_dataforsubscribe(){
		//$this->main_menu();
		$this->load->model('Admin_Model');
        $this->Admin_Model->upload_dataforsubscribe();
        $this->Admin_Model->insert_to_subscription_detls();
		//die;
		$this->session->set_flashdata('message_name', 'CSV Saved Successfully'); 
		redirect(base_url() . "Admin/uploadcsvforsubscribe");
	}
	public function pptorvideoupload()
	{
		//$this->main_menu();
		$this->load->view('uploadpptorvideo',$this->data);
	}
	public function save_uploaded_video()
	{
	$this->load->model('Admin_Model');
	$config['upload_path'] = './video_ppt/videos/'; # check path is correct
	$config['max_size'] = '102400';
	$config['allowed_types'] = 'mp4|3gp|mpeg|avi|mov'; # add video extenstion on here
	$config['overwrite'] = FALSE;
	$config['remove_spaces'] = TRUE;
	$video_name =  $_FILES['file-upload']['name'];
	$config['file_name'] = $video_name;

	$this->load->library('upload', $config);
	$this->upload->initialize($config);
	
		if (!$this->upload->do_upload('file-upload')) # form input field attribute
		{
			# Upload Failed
			$this->session->set_flashdata('message_name', $this->upload->display_errors());
			redirect(base_url() . "Admin/pptorvideoupload");
		}
		else
		{
			# Upload Successfull
			$url = './video_ppt/videos/'.$video_name;
			$this->load->model('Admin_Model');
			$set1 =  $this->Admin_Model->uploadVideoData($url,$video_name);
			$this->session->set_flashdata('message_name', 'Video Has been Uploaded');
			redirect(base_url() . "Admin/pptorvideoupload");
		} 
	   
	}

public function save_video_url()
	{
		//$this->main_menu();
	    $this->load->model('Admin_Model');
        $this->Admin_Model->upload_video_url();
		$this->session->set_flashdata('message_name', 'Video Link is Saved Successfully'); 
		redirect(base_url() . "Admin/pptorvideoupload");
	}
	public function save_uploaded_ppt()
	{
	$this->load->model('Admin_Model');
	$config['upload_path'] = './video_ppt/ppts/'; # check path is correct
	$config['max_size']    = '0';
	$config['allowed_types'] = 'ppt|pptx'; # add ppt extenstion on here
	$config['overwrite'] = FALSE;
	$config['remove_spaces'] = TRUE;
	$ppt_name =  $_FILES['file_upload_ppt']['name'];
	$config['file_name'] = $ppt_name;

	$this->load->library('upload', $config);
	$this->upload->initialize($config);
	
		if (!$this->upload->do_upload('file_upload_ppt')) # form input field attribute
		{
			# Upload Failed
			$this->session->set_flashdata('message_name', $this->upload->display_errors());
			redirect(base_url() . "Admin/pptorvideoupload");
		}
		else
		{
			# Upload Successfull
			$url = './video_ppt/ppts/'.$ppt_name;
			$this->load->model('Admin_Model');
			$set1 =  $this->Admin_Model->uploadPptData($url,$ppt_name);
			$this->session->set_flashdata('message_name', 'PPT Has been Uploaded');
			redirect(base_url() . "Admin/pptorvideoupload");
		} 
	   
	}
	
	public function save_ppt_url()
	{
		//$this->main_menu();
	    $this->load->model('Admin_Model');
        $this->Admin_Model->upload_ppt_url();
		$this->session->set_flashdata('message_name', 'PPT Link is Saved Successfully'); 
		redirect(base_url() . "Admin/pptorvideoupload");
	}

	public function quiz_package_status()
	{
		//$this->main_menu();
		$this->load->model('Admin_Model');
		$this->data['quiz_list_package']= $this->Admin_Model->findsummaryForquizlist();
		//print_R($this->data['result1']);
		$this->load->view('package_status',$this->data);
	}
	
	public function quiz_solution_status()
	{
		//$this->main_menu();
		$this->load->model('Admin_Model');
		$this->data['quiz_list']= $this->Admin_Model->get_static_quiz();
		//print_R($this->data['result1']);
		$this->load->view('quiz_solution_status',$this->data);
	}

	public function save_quizpackagestatus()
	{
		//$this->main_menu();
		$this->load->model('Admin_Model');
		$package_id = $this->input->post('package_id');
		$status = $this->input->post('status');

			$sql = "UPDATE `package_info` SET `status`='$status' WHERE id='$package_id'";
			$query=$this->db->query($sql);
		//print_r($sql);
		$this->session->set_flashdata('message_name', 'Status is Saved Successfully'); 
		redirect(base_url() . "Admin/quiz_package_status");
	}
	
		public function save_quizsolutionstatus()
	{
		//$this->main_menu();
		$this->load->model('Admin_Model');
		$quiz_id = $this->input->post('quiz_id');
		$status = $this->input->post('status');

			$sql = "UPDATE `quiz_info` SET `solution_status`='$status' WHERE id='$quiz_id'";
			$query=$this->db->query($sql);
		//print_r($sql);
		$this->session->set_flashdata('message_name', 'Status is Saved Successfully'); 
		redirect(base_url() . "Admin/quiz_solution_status");
	}
	
	public function createpackage()
	{
		//$this->main_menu();	
		$this->load->model('Admin_Model');
		$this->data['quiz_list_package']= $this->Admin_Model->findquizpackage();
		//$this->data['result'] = $this->Admin_Model->savenewpack();
		
		$this->data['quiz_details']= $this->Admin_Model->getquizinfo();
		$this->load->view('createpackage',$this->data);		
	}
	public function quizsubjectname()
	{
		//$this->main_menu();	
		$this->load->model('Admin_Model');
		$data['quiz_subject_name']= $this->Admin_Model->quizsubjectname();
		//$this->data['result'] = $this->Admin_Model->savenewpack();
		//print_r($data['quiz_subject_name']);	
		echo json_encode($data['quiz_subject_name']);		
	}

public function findquizpackagenames()
{
	Header('Access-Control-Allow-Origin: *'); //for allow any domain, insecure
Header('Access-Control-Allow-Headers: *'); //for allow any headers, insecure
Header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE'); //method allowed

$this->load->model('Admin_Model');
$data['quiz_package_name']= $this->Admin_Model->findquizpackagename();
//$this->data['result'] = $this->Admin_Model->savenewpack();
//print_r($data['quiz_package_name']);
echo json_encode($data['quiz_package_name']);
}
public function findquizsubjectname()
{
	Header('Access-Control-Allow-Origin: *'); //for allow any domain, insecure
Header('Access-Control-Allow-Headers: *'); //for allow any headers, insecure
Header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE'); //method allowed

$this->load->model('Admin_Model');
 $data = (file_get_contents("php://input"));
        $datajson = json_decode($data);
$package_id = $datajson->id;
$data= $this->Admin_Model->findquizsubjectname($package_id);
//$this->data['result'] = $this->Admin_Model->savenewpack();
//print_r($data);
echo json_encode($data);
}

public function save_video_upload()
	{
	//print_r(file_upload);
	$this->load->model('Admin_Model');
	$config['upload_path'] = './video_ppt/videos/'; # check path is correct
	$config['max_size'] = '102400';
	$config['allowed_types'] = 'mp4|3gp|mpeg|avi|mov'; # add video extenstion on here
	$config['overwrite'] = FALSE;
	$config['remove_spaces'] = TRUE;
	$video_name =  $_FILES['file']['name'];
	$config['file_name'] = $video_name;

	$this->load->library('upload', $config);
	$this->upload->initialize($config);
	
		if (!$this->upload->do_upload('file')) # form input field attribute
		{	
			# Upload Failed
			$responce->data['error']  =$this->upload->display_errors('', '');
			$resp = array("msg" => $responce->data['error']);
			echo json_encode($resp);
		}
		else
		{
			# Upload Successfull
			$url = './video_ppt/videos/'.$video_name;
			$this->load->model('Admin_Model');
			$set1 =  $this->Admin_Model->uploadVideoData($url,$video_name);
			$resp = array( 
				"video_name" => $video_name, 
				"msg" => 'Video Has been Uploaded'
			); 
			
			echo json_encode($resp);			
		} 
	   
	}
	public function save_ppt_upload()
	{
	$this->load->model('Admin_Model');
	$config['upload_path'] = './video_ppt/ppts/'; # check path is correct
	$config['max_size']    = '0';
	$config['allowed_types'] = 'ppt|pptx'; # add ppt extenstion on here
	$config['overwrite'] = FALSE;
	$config['remove_spaces'] = TRUE;
	$ppt_name =  $_FILES['file']['name'];
	$config['file_name'] = $ppt_name;

	$this->load->library('upload', $config);
	$this->upload->initialize($config);
	
		if (!$this->upload->do_upload('file')) # form input field attribute
		{
			# Upload Failed
			$responce->data['error']  =$this->upload->display_errors('', '');
			$resp = array("msg" => $responce->data['error']);
			echo json_encode($resp);
		}
		else
		{
			# Upload Successfull
			$url = './video_ppt/ppts/'.$ppt_name;
			$this->load->model('Admin_Model');
			$set1 =  $this->Admin_Model->uploadPptData($url,$ppt_name);
			$resp = array( 
				"ppt_name" => $ppt_name, 
				"msg" => 'PPT Has been Uploaded'
			); 
			echo json_encode($resp);	
		} 
	   
	}
	public function savepackage(){
		//$this->main_menu();	
		//print_r($_POST);
		
		$this->load->model('Admin_Model');
		$result = $this->Admin_Model->savepackagesyllabus();
		if(intval($result)&& $result > 0){
			$message = 'Data saved successfully';
		}else {
			$message = 'Data could not be saved/ Data already exists.';
		}
		
		$this->session->set_flashdata('message_name', $message); 
		redirect(base_url() . "Admin/createpackage");
	}

	public function getalljjutconsolrank()
{
//$this->main_menu();
$this->load->model('Admin_Model');
$sub=$this->input->post('sub');
$batch=$this->input->post('batch');
$this->data['var'] = '';
if ($batch == '' && $sub == ''){
$batch='all';
$sub='all';
}
$this->data['result'] = $this->Admin_Model->get_jjut_consol_rank($batch,$sub);
// echo '<pre>';
    // print_r($this->data['result']);
// echo '</pre>';
// print_r($this->data['result_sub']);
$this->load->view('getalljjutconsolrank', $this->data);
}
public function add_status()
	{
		$user_id = $_GET['user_id'];
		//Print_r($user_id);
		//$this->main_menu();		
		$this->data['result'] = $this->Admin_Model->get_userpackagedetails($user_id);
		$this->load->view('add_status', $this->data);
		
	}
public function save_userstatus() 
	{
		//print_r($_post);
		//$this->main_menu();
		$this->data['result']= $this->Admin_Model->updateuserstatus();
		// $this->session->set_flashdata('message_name', 'Updated Status Successfully'); 
		// redirect(base_url()."Admin/list_user");
	}
public function quiz_assign_package() {
		//$this->main_menu();
		$this->data['quiz_assign_package']= $this->Admin_Model->findsummaryForquizlist();
		//print_r($this->data['quiz_assign_package']);
		
		if($_POST['showvalues']==1){ 
			$package_id = $this->input->post("package_id");
			//print_r($package_id);
			$this->data['quiz_package_link']= $this->Admin_Model->findAllForquizpacklink($package_id);
			$this->data['quiz_details']= $this->Admin_Model->findAllForquizdetails($package_id);
		}
		//print_r($this->data['quiz_package_link']);
		$this->load->view('Quiz_assign_package',$this->data);

	}
	public function quiz_assign_new_pack()
	{
		//$this->main_menu();
		$this->Admin_Model->insertnewquizpackage();
		$this->session->set_flashdata('message_name', 'Record successfully Inserted'); 
		redirect(base_url() . "Admin/Quiz_list_package");
	}
public function deleterecord()
	{
		//$this->main_menu();
		$this->load->model('Admin_Model');
		$quiz_id=$this->input->get('quiz_id');
		//print_r($quiz_id);
		$this->data['del_details'] = $this->Admin_Model->delete_details($quiz_id);
		$this->session->set_flashdata('message_name', 'Record Deleted'); 
		redirect(base_url() . "Admin/Quiz_list_package");
	}

public function getallkvpyrank()
{
//$this->main_menu();
$this->load->model('Admin_Model');
$sub = $this->input->post('subject_name');
$cat = $this->input->post('category');
$batch = $this->input->post('batch');
$this->data['var'] = '';
if ($batch == ''){
$batch='all';
}
$this->data['result'] = $this->Admin_Model->getallkvpyrank($cat,$batch);
$this->data['result_sub'] = $this->Admin_Model->get_allquiz_subject();

// print_r($this->data['result']);
// print_r($this->data['result_sub']);
$this->load->view('getallkvpyrank', $this->data);
}

public function quiz_new_package()
	{
		//$this->main_menu();
		$this->load->model('Admin_Model');
		$this->data['result']=$this->Admin_Model->get_allquiz_subject();
		//print_R($this->data['result1']);
		$this->load->view('Quiz_new_package',$this->data);
	}
	public function save_new_quizpackage()
	{
		//$this->main_menu();
		$this->load->model('Admin_Model');
		$type=[];
		$type['package_name']=$_POST['package_name'];
		$type['package_amt']=$_POST['package_amt'];
		$type['package_srtdate']=$_POST['package_srtdate'];
		$type['package_enddate']=$_POST['package_enddate'];
		$type['gst_amt']=$_POST['gst_amt'];
		
		$packagetypeid= $this->Admin_Model->savequizpackagedetails($type);
		//print_r($packagetypeid);
		
		$this->Admin_Model->savepackagearraydetails($packagetypeid);
		$this->session->set_flashdata('message_name', 'New Package Saved Successfully'); 
		redirect(base_url() . "Admin/quiz_new_package");
	}
	
	/*public function offline_package()
	{
		$this->main_menu();
		$this->load->model('Admin_Model');
		$this->data['result']=$this->Admin_Model->get_allquiz_subject();
		//print_R($this->data['result1']);
		$this->load->view('offline_package',$this->data);
	}*/
// 	public function offline_package()
// {
//     //$this->main_menu();
// 	//$this->load->model('Admin_Model');

//     // Use custom DB helper to get all quiz subjects
//     $this->data['result'] = db_get('quiz_subject')->result();

//     // Load the view with the data
//     $this->load->view('offline_package', $this->data);
// }
public function offline_package()
{
    $db = get_db();  // your helper DB instance

    // Fetch all quiz subjects
    $result = db_get('quiz_subject'); // mysqli_result

    // Convert mysqli_result to array
    $data = array();
    if ($result) {
        $data['result'] = $result->fetch_all(MYSQLI_ASSOC);
    } else {
        $data['result'] = array();
    }

    // Load the view
    $this->load->view('offline_package', $data);
}


	
	public function list_offline_package()
	{
		//$this->main_menu();
		$this->load->model('Admin_Model');
		$this->data['package']=$this->Admin_Model->getofflinepackage();
		//print_R($this->data['package']);
		$this->load->view('list_offline_package',$this->data);
	}
	
	public function save_offline_package()
	{
		//$this->main_menu();
		$this->load->model('Admin_Model');
		$type=[];
		$type['package_code']=$_POST['package_code'];
		$type['package_name']=$_POST['package_name'];
		$type['package_amt']=$_POST['package_amt'];
		$type['package_srtdate']=$_POST['package_srtdate'];
		$type['package_enddate']=$_POST['package_enddate'];
		$type['cgst_amt']=$_POST['cgst_amt'];
		$type['sgst_amt']=$_POST['sgst_amt'];
		
		$packagetypeid= $this->Admin_Model->saveofflinepackage($type);
		//print_r($packagetypeid);
		
		//$this->Admin_Model->savepackagearraydetails($packagetypeid);
		$this->session->set_flashdata('message_name', 'Offline Package Saved Successfully'); 
		redirect(base_url() . "Admin/offline_package");
	}
	
	// public function assign_offline_package(){
	// 	//$this->main_menu();
	// 	$this->data['packages']= $this->Admin_Model->getofflinepackage();
	// 	$this->data['user']= $this->Admin_Model->offline_package();
	// 	$this->load->view('assign_offline_package',$this->data);
		
	// }
	public function assign_offline_package()
{
    $data = array(); // use local array instead of $this->data

    // Fetch offline packages
    $data['packages'] = $this->Admin_Model->getofflinepackage();

    // Fetch offline users (make sure this method exists in your model)
    if (method_exists($this->Admin_Model, 'offline_package')) {
        $data['user'] = $this->Admin_Model->offline_package();
    } else {
        $data['user'] = array(); // fallback empty array
    }

    // Load the view
    $this->load->view('assign_offline_package', $data);
}

	

	public function save_assigned_offline_package(){
	//	print_r($_POST);
	//$this->main_menu();
		$this->data['packages']= $this->Admin_Model->getofflinepackage();
		$this->data['user']= $this->Admin_Model->findActiveForSummaryPage();
		
		foreach($_POST['userid'] as $key=>$val)
		{
			
			$userid = $val;
			$packageid = $_POST['packageid'][$key];
			
		 $this->data['count']= $this->Admin_Model->saveofflinepackageassignment($userid,$packageid);
			
			
		}
		$this->load->view('assign_offline_package',$this->data);
		
	}
	
	
	
	public function update_duplicate_quizresult(){
		//$this->main_menu();
		$this->data['result']= $this->Admin_Model->getquizinfo();
		$this->load->view('update_duplicate_quizresult',$this->data);
		
	}
	
	public function update_duplicate_quizresults(){
		$this->load->model('Quiz_model', 'quiz_model');
		$quiz_id = $_POST['quiz_id'];
		//print_r($quiz_id);
		$user_name = $_POST['user_name'];
		//print_r($user_name);
		//$this->main_menu();
		$this->data['result']= $this->Admin_Model->getquizinfo();
		if($_POST['showvalues']==1){ 
		$sql = "select count(*)count from student_quiz_result where user_id = '$user_name' and quiz_id = '$quiz_id'";
			//print_r($sql);
			$result = $this->quiz_model->get_data($sql);
			//print_r($result);
			if ($result[0]['count'] > 0) {
				//echo('here');
                  $this->session->set_flashdata('message_name', 'Quiz Result is Already Updated'); 
				redirect(base_url() . "Admin/update_duplicate_quizresult");
                }
			else {	
				//$this->data['quiz_details']= $this->Admin_Model->showresultForquiz($quiz_id,$user_name);
				//print_r($this->data['quiz_details']);
				$stid = $this->Admin_Model->getstid($quiz_id,$user_name);
				//$st_id= $stid[0]['st_id'];
				if($stid == '' || $stid == null)
				{
					$st_id= '';
				} else {
					$st_id= $stid;
				}
			//print_r($st_id);
			$this->data['res']=$this->Admin_Model->insertorupdateresultForquiz($quiz_id,$user_name,$st_id);
			$this->session->set_flashdata('message_name', 'Quiz Result is Inserted Successfully'); 
			redirect(base_url() . "Admin/update_duplicate_quizresult");	
			}
		}
		$this->load->view('update_duplicate_quizresult',$this->data);
	}
	public function view_resultadmin()
{$this->load->library('nativesession');
$id = $_GET['id'];
$userid = $_GET['userid'];
$this->load->model('Quiz_model', 'quiz_model');
$data['result'] = $this->quiz_model->get_inforesultofstudentsadmin($id, $userid);
$data['questioninfo'] = $this->quiz_model->getallquestionsinfosadmin($id, $userid);
$sql = "select quiztype from quiz_info where id ='$id' ";
$quiztype = $this->quiz_model->get_data($sql);
if ($quiztype[0]['quiztype'] == "JUT") {
    $this->load->view('studentresult1', $data);
}
if ($quiztype[0]['quiztype'] == "NEETFDTNTEST") {
    $this->load->view('studentresult1fdtn', $data);
}
if ($quiztype[0]['quiztype'] == "FREE") {
    $this->load->view('studentresult', $data);
}
if ($quiztype[0]['quiztype'] == "JEE") {
    $this->load->view('studentresult1jee', $data);
}
if ($quiztype[0]['quiztype'] == "NEETSHORT") {
    $this->load->view('studentresult1neetst', $data);
}
if ($quiztype[0]['quiztype'] == "NEWJEE") {
    $this->load->view('studentresult1newjee', $data);
}
if ($quiztype[0]['quiztype'] == "KCETPCB") {
    $this->load->view('studentresult1kcetpcb', $data);
}
if ($quiztype[0]['quiztype'] == "KCETPCM") {
    $this->load->view('studentresult1kcetpcm', $data);
}
if ($quiztype[0]['quiztype'] == "NEETCRASHCOURSE") {
    $this->load->view('studentresult1neetcrash', $data);
}
if ($quiztype[0]['quiztype'] == "NTSESAT") {
    $this->load->view('studentresult1sat', $data);
}
if ($quiztype[0]['quiztype'] == "NTSEMAT") {
    $this->load->view('studentresult1mat', $data);
}
if ($quiztype[0]['quiztype'] == "KVPY") {
    $this->load->view('studentresult1kvpy', $data);
}

}
	public function quiz_edit_subpackage() {
		//$this->main_menu();
		if($_POST['showvalues']==1){ 
		$user_name = $this->input->post("user_name");
		//print_r($user_name);
		$this->data['quiz_edit_package']= $this->Admin_Model->findsummaryForquizlist();
		$this->data['quiz_edit_subpackage']=$this->Admin_Model->get_stud_package($user_name);
		$this->data['user_details']=$this->Admin_Model->get_userdetails($user_name);
		//print_r($this->data['user_details']);
		}
		$this->load->view('quiz_edit_subpackage',$this->data);

	}
	public function delete_edit_subpackage()
	{
		//$this->main_menu();
		$this->load->model('Admin_Model');
		$user_name=$this->input->get('user_name');
		$package_id=$this->input->get('package_id');
		// print_r($user_name);
		// print_r($package_id);
		$this->data['del_details'] = $this->Admin_Model->delete_subpackagedet($user_name,$package_id);
		$this->session->set_flashdata('message_name', 'Record Deleted'); 
		redirect(base_url() . "Admin/quiz_edit_subpackage");
	}
	public function quiz_new_packagedet()
	{
		//$this->main_menu();
		$this->Admin_Model->insertorupdatenewpackage();
		$this->session->set_flashdata('message_name', 'Record successfully Inserted / Updated'); 
		redirect(base_url() . "Admin/quiz_edit_subpackage");
	}
	public function findquizpackagename()
	{
		//$this->main_menu();	
		$this->load->model('Admin_Model');
		$data['pg_name']= $this->Admin_Model->findquizpgname();
		// print_r($data['pg_name']);
		echo json_encode($data['pg_name']);		
	}
public function quizLinkId()
	{
		//$this->main_menu();	
		$this->load->model('Admin_Model');
		$package_id = $_POST['package_id'];
		$packageid =explode('_',$package_id);
		//print_r($abc);
		$data['quiz_package_link']= $this->Admin_Model->findAllForquizpacklink($packageid[0]);
		//$data['quiz_subject_name']= $this->Admin_Model->quizsubjectname();
		//$this->data['result'] = $this->Admin_Model->savenewpack();
		//print_r($data['quiz_package_link']);	
		echo json_encode($data['quiz_package_link']);		
	}
	
	public function send_marks_sms(){
		log_message('info', 'in Admin.send_marks_sms()');
		
		$sms_list = $this->input->post('chk_send_sms');
		// echo '<pre>';
			// print_r($sms_list);
		// echo '</pre>';
		$tot_sltd = sizeof($sms_list);
			
			include 'sendsms.php';
			
			  $url = "https://sms.shreetripada.com/api/sendapi.php";
    $auth_key = "3600DyzX4xCVK92lwSOXFw"; // Replace with your actual auth key
    $sender = "JSUDHA"; // Replace with your actual sender ID
    $route = "4";
    
    // URL encode the message to handle special characters
	/*
	
	{
    "message": {
        "channel": "WABA",
        "content": {
            "preview_url": false,
            "type": "MEDIA_TEMPLATE",
            "mediaTemplate": {
                "templateId": "marks_jut",
                "bodyParameterValues": {
                    "0": "12",
                    "1": "2222",
                    "2": "22",
                    "3": "222",
                    "4": "222",
                    "5": "222"
                }
            }
        },
        "recipient": {
            "to": "+918758092562",
            "recipient_type": "individual"
        },
        "sender": {
            "from": "917349094912"
        },
        "preferences": {
            "webHookDNId": "1001"
        }
    },
    "metaData": {
        "version": "v1.0.9"
    }
}
	*/
   
			
			if ( $tot_sltd > 0 ) {
				log_message('info', 'Retrieved selected records, Preparing to send SMS Messages');
				for ( $idx = 0; $idx < $tot_sltd; $idx++ ) {
					
					$rowVal = explode(' # ', $sms_list[$idx]);
					$mob_no = $rowVal[0];
					$sms_text = $rowVal[1];
					$whatsapppayload =  $rowVal[2];
					
					echo '<pre>';
						print_r($rowVal);
					echo '</pre>';
					echo '<pre>';
						print_r("mob_no = " . $mob_no . ", msg = " . $sms_text . ", whatsapppayload = " . $whatsapppayload );
					echo '</pre>'; 
					
				/*	$sendsms = new sendsms("http://alerts.sinfini.com/api"  1207164551591406054
						, "A97d6c2cf473d98e2f300f96584d6874e", "JSUDHA");
					$response = $sendsms->unicode_sms("" . $mob_no . "", "" . $sms_text . "", "http://www.yourdomainname.domain/yourdlrpage&msgid=XX", "xml", "1");
					$msg = "Marks SMS sent successfully to ".$tot_sltd." mobile nos ";
					
					https://sms.shreetripada.com/api/sendapi.php?auth_key=YourAuthKey&mobiles=919999999990&templateid=yourtemplateid&message=test message&sender=senderid&route=4*/
					
					 $encoded_message = urlencode($sms_text);
    
    // Construct the full API URL
    $full_url = "https://whatsapp.shreetripada.com/api/sendMessage";
    
$curl2 = curl_init();

if ($curl2 !== false) {
    // Set each option individually for maximum compatibility
    curl_setopt($curl2, CURLOPT_URL, 'https://whatsapp.shreetripada.com/api/sendMessage');
    curl_setopt($curl2, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl2, CURLOPT_POST, true);
    curl_setopt($curl2, CURLOPT_POSTFIELDS, $whatsapppayload);
    curl_setopt($curl2, CURLOPT_TIMEOUT, 30);
    curl_setopt($curl2, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($curl2, CURLOPT_MAXREDIRS, 10);
    curl_setopt($curl2, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl2, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl2, CURLOPT_USERAGENT, 'PHP cURL');
    
    // Set headers
    curl_setopt($curl2, CURLOPT_HTTPHEADER, array(
        'Authorization: Bearer l2Zk8Un6wSqJo0FHFeqcDg==',
        'Content-Type: application/json',
        'Accept: application/json'
    ));
    
    // Execute
    $response2 = curl_exec($curl2);
    $err2 = curl_error($curl2);
    $http_code2 = curl_getinfo($curl2, CURLINFO_HTTP_CODE);
    
    curl_close($curl2);
    
    if ($err2) {
        echo "cURL Error: " . $err2 . "\n";
    } else {
        echo "HTTP Status Code: " . $http_code2 . "\n";
        echo "Response: " . $response2 . "\n";
    }
}
				}
			//	die;
			} else {
				 $msg = 'No mobile numbers available to publish marks!';
				 log_message('info', $msg);
				
			}
			
		$this->session->set_flashdata('message_name', $msg); 
		redirect($_SERVER['HTTP_REFERER']);
		//redirect(base_url()."Admin/getallrank");	
		
	}
public function enquiry_details()
{
//$this->main_menu();
$this->load->model('Admin_Model');
$this->data['enq_det']= $this->Admin_Model->findallenquirydetails();
//print_r($this->data['enq_det']);
$this->load->view('enquiry_report', $this->data);
}

public function foundation_details()
{
//$this->main_menu();
$this->load->model('Admin_Model');
$this->data['subscription']= $this->Admin_Model->findallfoundationdetails();
//print_r($this->data['enq_det']);
$this->load->view('foundation_report', $this->data);
}

public function commerce_details()
{
//$this->main_menu();
$this->load->model('Admin_Model');
$this->data['subscription']= $this->Admin_Model->findallcommercedetails($_POST['frmdate'],$_POST['todate']);
//print_r($this->data['subscription']);
$this->load->view('commerce_report', $this->data);
}
function getIndianCurrency(float $number)
{
    $decimal = round($number - ($no = floor($number)), 2) * 100;
    $hundred = null;
    $digits_length = strlen($no);
    $i = 0;
    $str = array();
    $words = array(0 => '', 1 => 'one', 2 => 'two',
        3 => 'three', 4 => 'four', 5 => 'five', 6 => 'six',
        7 => 'seven', 8 => 'eight', 9 => 'nine',
        10 => 'ten', 11 => 'eleven', 12 => 'twelve',
        13 => 'thirteen', 14 => 'fourteen', 15 => 'fifteen',
        16 => 'sixteen', 17 => 'seventeen', 18 => 'eighteen',
        19 => 'nineteen', 20 => 'twenty', 30 => 'thirty',
        40 => 'forty', 50 => 'fifty', 60 => 'sixty',
        70 => 'seventy', 80 => 'eighty', 90 => 'ninety');
    $digits = array('', 'hundred','thousand','lakh', 'crore');
    while( $i < $digits_length ) {
        $divider = ($i == 2) ? 10 : 100;
        $number = floor($no % $divider);
        $no = floor($no / $divider);
        $i += $divider == 10 ? 1 : 2;
        if ($number) {
            $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
            $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
            $str [] = ($number < 21) ? $words[$number].' '. $digits[$counter]. $plural.' '.$hundred:$words[floor($number / 10) * 10].' '.$words[$number % 10]. ' '.$digits[$counter].$plural.' '.$hundred;
        } else $str[] = null;
    }
    $Rupees = implode('', array_reverse($str));
    $paise = ($decimal > 0) ? "." . ($words[$decimal / 10] . " " . $words[$decimal % 10]) . ' Paise' : '';
    return ($Rupees ? 'Rupees '.$Rupees : '') . $paise;
}
public function create_pdf($printdata){
	
	$amtinwords = $this->getIndianCurrency($printdata[0]['price']);
    require_once('\TCPDF\tcpdf.php');
//print_r($printdata);
// create new PDF document
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('JnanaSudha');


// set default header data
//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 006', PDF_HEADER_STRING);

// set header and footer fonts
//	$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
//$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
    $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
    if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
        require_once(dirname(__FILE__).'/lang/eng.php');
        $pdf->setLanguageArray($l);
    }

// ---------------------------------------------------------

// set font
    $pdf->SetFont('dejavusans', '', 10);

// add a page
    $pdf->AddPage();

// writeHTML($html, $ln=true, $fill=false, $reseth=false, $cell=false, $align='')
// writeHTMLCell($w, $h, $x, $y, $html='', $border=0, $ln=0, $fill=0, $reseth=true, $align='', $autopadding=true)

// create some HTML content
    $html = '<html>
    <head>
    <meta charset="utf-8">
    <title>Invoice</title>
    <style>
    
    /* content editable */
    *[contenteditable] { border-radius: 0.25em; min-width: 1em; outline: 0; }
    *[contenteditable] { cursor: pointer; }
    *[contenteditable]:hover, *[contenteditable]:focus, td:hover *[contenteditable], td:focus *[contenteditable], img.hover { background: #DEF; box-shadow: 0 0 1em 0.5em #DEF; }
    span[contenteditable] { display: inline-block; }
    /* heading */
    h1,h5 { font: bold 100% Trebuchet MS; letter-spacing: 0.5em; text-align: center; text-transform: uppercase;}
    img{text-align: right;}
    /* table */
    table { font-size: 75%; table-layout: fixed; width: 100%; }
    table { border-collapse: separate; border-spacing: 2px; }
    /*th, td { border-width: 1px; padding: 0.5em; position: relative; text-align: left; }
    th, td { border-radius: 0.25em; border-style: solid; }
    th { background: #EEE; border-color: #BBB; }
    td { border-color: #DDD; }*/
    /* page */
    html { font: 16px/1 "Open Sans", sans-serif; overflow: auto;  }
    html { background: #999; cursor: default; }
    body { box-sizing: border-box; height: 11in; overflow: hidden; }
    body { background: #FFF; border-radius: 1px; box-shadow: 0 0 1in -0.25in rgba(0, 0, 0, 0.5); }
    /* header */
    header{line-height:-10px;}
    header:after { clear: both; content: ""; display: table; }	
    header address { float: left; font-size: 75%; font-style: normal;  margin: 0 1em 1em 0; }
    header address p { margin: 0 0 0.25em; }
    header span, header img { display: block; float: right; }
    header span { margin: 0 0 1em 1em; max-height: 25%; max-width: 60%; position: relative; }
    header img { max-height: 100px; max-width: 400px; }
    header input { cursor: pointer; -ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=0)"; height: 100%; left: 0; opacity: 0; position: absolute; top: 0; width: 100%; }
    /* article */
    article, article address, table.meta, table.inventory { margin: 0 0 3em; }
    article:after { clear: both; content: ""; display: table; }
    article h1 { clip: rect(0 0 0 0); position: absolute; }
    
    article address { float: right; font-size: 125%; font-weight: bold; }
    /* table meta & balance */
    table.meta, table.balance { float: right; width: 36%; }
    table.meta:after, table.balance:after { clear: both; content: ""; display: table; }
    /* table meta */
    table.meta th { width: 40%; }
    table.meta td { width: 60%; }
    /* table items */
    table.inventory { clear: both; width: 100%; }
    table.inventory th { font-weight: bold; text-align: center; }
    table.inventory td:nth-child(1) { width: 26%; }
    table.inventory td:nth-child(2) { width: 38%; }
    table.inventory td:nth-child(3) { text-align: right; width: 12%; }
    table.inventory td:nth-child(4) { text-align: right; width: 12%; }
    table.inventory td:nth-child(5) { text-align: right; width: 12%; }
    /* table balance */
    table.balance th, table.balance td { width: 50%; }
    table.balance td { text-align: right; }
    /* aside */
    aside h1 { border: none; border-width: 0 0 1px; }
    aside h1 { border-color: #999; border-bottom-style: solid; }
    /* javascript */
    .add, .cut
    {
       border-width: 1px;
       display: block;
       font-size: .8rem;
       padding: 0.25em 0.5em;
       float: left;
       text-align: center;
       width: 0.6em;
   }
   .add, .cut
   {
       background: #9AF;
       box-shadow: 0 1px 2px rgba(0,0,0,0.2);
       background-image: -moz-linear-gradient(#00ADEE 5%, #0078A5 100%);
       background-image: -webkit-linear-gradient(#00ADEE 5%, #0078A5 100%);
       border-radius: 0.5em;
       border-color: #0076A3;
       color: #FFF;
       cursor: pointer;
       font-weight: bold;
       text-shadow: 0 -1px 2px rgba(0,0,0,0.333);
   }
   .add { margin: -2.5em 0 0; }
   .add:hover { background: #00ADEE; }
   .cut { opacity: 0; position: absolute; top: 0; left: -1.5em; }
   .cut { -webkit-transition: opacity 100ms ease-in; }
   tr:hover .cut { opacity: 1; }
   @media print {
       * { -webkit-print-color-adjust: exact; }
       html { background: none; padding: 0; }
       body { box-shadow: none; margin: 0; }
       span:empty { display: none; }
       .add, .cut { display: none; }
   }
   
   </style>
   
   </head>
   <body>


  
   
   <br/>	
   <table>
   <tr>
   <td style="text-align:left;"><img src="assets/studentdashboard/img/logo/logo.gif" style="width:200px;height:80;float:left;"></td>
   <td colspan="4"><p style="color: blue;font-size:20px;text-align:center">JNANSUDHA ENTRANCE ACADEMY LLP</p>
   <p style="font-size:12px;height: 200px;text-align:center">D.NO 4-408/1,PADMAGOPAL,JODURASTHE <br> KUKKUNDOOR VILLAGE AND POST, KARKALA,Udupi,<br>Karnataka, 576117.</p>
   </td>
   </tr>
  
   

   </table>               				
   
   
   
   <p style="font-size:15px;height: 200px;text-align:center">GSTIN:       29AARFJ8177G1ZK</p>
   <h2></h2>
   <h1>RECEIPT</h1>

   <address contenteditable>
   <p>Name:       '.$printdata[0]['cust_name'].' </p>
   <p>Email ID:   '.$printdata[0]['email_id'].'</p>
   <p>Mobile No:  '.$printdata[0]['mobile_no'].'</p>
   </address>
    <article>
	
   <table>
   <tr>
   
   <th class="inventory" border="2"><span>Order No #: </span></th>
   <td class="inventory" border="2"><span> '.$printdata[0]['order_no'].'</span></td>
   <th></th>
   <td></td>
  
   </tr>
   <tr>
  
   <th class="inventory" border="2"><span>Receipt No #: </span></th>
   <td class="inventory" border="2"><span> '.$printdata[0]['receipt_no'].'</span></td>
    <th></th>
   <td></td>
  
   </tr>
   <tr>
 
   <th class="inventory" border="2"><span contenteditable>Date:-</span></th>
   <td class="inventory" border="2"><span contenteditable> '.$printdata[0]['datetime'].'</span></td>
    <th></th>
   <td></td>
  
   </tr>   
   </table>
   
   
   
   <br/>
   <table class="inventory" border="1">
   <thead>
   <tr>
   <th><span contenteditable>Sr No</span></th>
   <th colspan="2"><span contenteditable>Particulars</span></th>
   <th><span contenteditable>SAC</span></th>
   <th><span contenteditable>Payment Gateway Ref No</span></th>
   <th><span contenteditable>Amount(Rs.)</span></th>
   </tr>
   </thead>
   <tbody>
   <tr>
   <td style="text-align:right"><span contenteditable>1</span></td>
   <td colspan="2"><a class="cut">-</a><span contenteditable> '.$printdata[0]['packagename'].'</span></td>
   <td style="text-align:right"><span contenteditable>999299</span></td>
   <td><span contenteditable></span>'.$printdata[0]['pg_refno'].'</td>
   <td style="text-align:right"><span data-prefix>&#8377;</span><span contenteditable> '.number_format($printdata[0]['packageamount'],2).'</span></td>
   
   </tr>
   
    <tr>
   
   <td colspan ="5" style="text-align:lwft"><span contenteditable>Total(Rs.)</span></td>
   <td style="text-align:right"><span data-prefix>&#8377;</span><span contenteditable>  '.number_format($printdata[0]['packageamount'],2).'</span></td>
   </tr>
   
   
     <tr>
   
   <td colspan ="5" style="text-align:lwft"><span contenteditable>SGST(9%):-</span></td>
   <td style="text-align:right"><span data-prefix>&#8377;</span><span contenteditable>  '.number_format($printdata[0]['sgst_amt'],2).'</span></td>
   </tr>
   
   <tr>
   
   <td colspan ="5" style="text-align:lwft"><span contenteditable>CGST(9%):-</span></td>
   <td style="text-align:right"><span data-prefix>&#8377;</span><span contenteditable>  '.number_format($printdata[0]['cgst_amt'],2).'</span></td>
   </tr>
   
  
   
   <tr>
   
   <td colspan ="5" style="text-align:lwft"><span contenteditable>Grand Total</span></td>
   <td style="text-align:right"><span data-prefix>&#8377;</span><span contenteditable>  '.number_format($printdata[0]['price'],2).'</span></td>
   </tr>
   
   </tbody>
   </table>
   <h3>'.$amtinwords.' only</h3>
   <a class="add">+</a>
  
   </article>
   <aside>
   <h1><span contenteditable>Additional Notes</span></h1>
   <div contenteditable>
   <p>This is an electronic copy, signature is not required.</p>
   <p>Fee once paid will not be refunded</p>
   </div>
   </aside>
   </body>
   </html>';
        // output the HTML content
   $pdf->writeHTML($html, true, false, true, false, '');

        // - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

        // reset pointer to the last page
   $pdf->lastPage();

        // ---------------------------------------------------------

        //Close and output PDF document
   $pdf->Output(FCPATH . 'pdf/invoices/' . $printdata[0]['order_no'] . '.pdf', 'F');

 /*  require '/phpmailer/PHPMailerAutoload.php';
   $mail = new PHPMailer;
   $mail->ClearAllRecipients();
   $mail->IsSMTP();
   $mail->SMTPAuth = true;
   $mail->Host = "smtp.pepipost.com";
   $mail->Port = 587;
   $mail->Username = "rahulpandey";
   $mail->Password = "Mgicgkp1";

   $mail->SMTPSecure = 'tls';

   $mail->setFrom('info@jnanasudha.com', 'Jnanasudha admin');
   $mail->addReplyTo('info@jnanasudha.com', 'Jnanasudha admin');
   $mailmsg = 'Dear ' . $printdata[0]['cust_name'] . ',</br> You are successfully subscribed to package ' . $printdata[0]['packagename'] . '. </br> Your payment Receipt is attached';
   $mail->addAddress($printdata[0]['email_id']);
        //$mail->AddAttachment(ABS_PATH.'pdf/'.$files);
   $mail->Subject = 'Payment Receipt from JnanaSudha';
   $mail->AddAttachment(FCPATH . 'pdf/' . $printdata[0]['order_no'] . '.pdf');

   $mail->msgHTML($mailmsg);
   $mail->IsHTML(true);
   if (!$mail->send()) {
    $error = "Mailer Error: " . $mail->ErrorInfo;
    ?><script>alert('<?php echo $error ?>');</script><?php
} else {
    echo "Message sent!";
}*/

}

public function create_pdfolds($printdata){
    require_once('\TCPDF\tcpdf.php');
//print_r($printdata);
// create new PDF document
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('JnanaSudha');


// set default header data
//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 006', PDF_HEADER_STRING);

// set header and footer fonts
//	$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
//$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
    $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
    if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
        require_once(dirname(__FILE__).'/lang/eng.php');
        $pdf->setLanguageArray($l);
    }

// ---------------------------------------------------------

// set font
    $pdf->SetFont('dejavusans', '', 10);

// add a page
    $pdf->AddPage();

// writeHTML($html, $ln=true, $fill=false, $reseth=false, $cell=false, $align='')
// writeHTMLCell($w, $h, $x, $y, $html='', $border=0, $ln=0, $fill=0, $reseth=true, $align='', $autopadding=true)

// create some HTML content
    $html = '<html>
    <head>
    <meta charset="utf-8">
    <title>Invoice</title>
    <style>
    
    /* content editable */
    *[contenteditable] { border-radius: 0.25em; min-width: 1em; outline: 0; }
    *[contenteditable] { cursor: pointer; }
    *[contenteditable]:hover, *[contenteditable]:focus, td:hover *[contenteditable], td:focus *[contenteditable], img.hover { background: #DEF; box-shadow: 0 0 1em 0.5em #DEF; }
    span[contenteditable] { display: inline-block; }
    /* heading */
    h1,h5 { font: bold 100% Trebuchet MS; letter-spacing: 0.5em; text-align: center; text-transform: uppercase;}
    img{text-align: right;}
    /* table */
    table { font-size: 75%; table-layout: fixed; width: 100%; }
    table { border-collapse: separate; border-spacing: 2px; }
    /*th, td { border-width: 1px; padding: 0.5em; position: relative; text-align: left; }
    th, td { border-radius: 0.25em; border-style: solid; }
    th { background: #EEE; border-color: #BBB; }
    td { border-color: #DDD; }*/
    /* page */
    html { font: 16px/1 "Open Sans", sans-serif; overflow: auto;  }
    html { background: #999; cursor: default; }
    body { box-sizing: border-box; height: 11in; overflow: hidden; }
    body { background: #FFF; border-radius: 1px; box-shadow: 0 0 1in -0.25in rgba(0, 0, 0, 0.5); }
    /* header */
    header{line-height:-10px;}
    header:after { clear: both; content: ""; display: table; }	
    header address { float: left; font-size: 75%; font-style: normal;  margin: 0 1em 1em 0; }
    header address p { margin: 0 0 0.25em; }
    header span, header img { display: block; float: right; }
    header span { margin: 0 0 1em 1em; max-height: 25%; max-width: 60%; position: relative; }
    header img { max-height: 100px; max-width: 400px; }
    header input { cursor: pointer; -ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=0)"; height: 100%; left: 0; opacity: 0; position: absolute; top: 0; width: 100%; }
    /* article */
    article, article address, table.meta, table.inventory { margin: 0 0 3em; }
    article:after { clear: both; content: ""; display: table; }
    article h1 { clip: rect(0 0 0 0); position: absolute; }
    
    article address { float: left; font-size: 125%; font-weight: bold; }
    /* table meta & balance */
    table.meta, table.balance { float: right; width: 36%; }
    table.meta:after, table.balance:after { clear: both; content: ""; display: table; }
    /* table meta */
    table.meta th { width: 40%; }
    table.meta td { width: 60%; }
    /* table items */
    table.inventory { clear: both; width: 100%; }
    table.inventory th { font-weight: bold; text-align: center; }
    table.inventory td:nth-child(1) { width: 26%; }
    table.inventory td:nth-child(2) { width: 38%; }
    table.inventory td:nth-child(3) { text-align: right; width: 12%; }
    table.inventory td:nth-child(4) { text-align: right; width: 12%; }
    table.inventory td:nth-child(5) { text-align: right; width: 12%; }
    /* table balance */
    table.balance th, table.balance td { width: 50%; }
    table.balance td { text-align: right; }
    /* aside */
    aside h1 { border: none; border-width: 0 0 1px; }
    aside h1 { border-color: #999; border-bottom-style: solid; }
    /* javascript */
    .add, .cut
    {
       border-width: 1px;
       display: block;
       font-size: .8rem;
       padding: 0.25em 0.5em;
       float: left;
       text-align: center;
       width: 0.6em;
   }
   .add, .cut
   {
       background: #9AF;
       box-shadow: 0 1px 2px rgba(0,0,0,0.2);
       background-image: -moz-linear-gradient(#00ADEE 5%, #0078A5 100%);
       background-image: -webkit-linear-gradient(#00ADEE 5%, #0078A5 100%);
       border-radius: 0.5em;
       border-color: #0076A3;
       color: #FFF;
       cursor: pointer;
       font-weight: bold;
       text-shadow: 0 -1px 2px rgba(0,0,0,0.333);
   }
   .add { margin: -2.5em 0 0; }
   .add:hover { background: #00ADEE; }
   .cut { opacity: 0; position: absolute; top: 0; left: -1.5em; }
   .cut { -webkit-transition: opacity 100ms ease-in; }
   tr:hover .cut { opacity: 1; }
   @media print {
       * { -webkit-print-color-adjust: exact; }
       html { background: none; padding: 0; }
       body { box-shadow: none; margin: 0; }
       span:empty { display: none; }
       .add, .cut { display: none; }
   }
   
   </style>
   
   </head>
   <body>


   <header>
   <textarea style="color: #FFFFFF;border:2px solid #ddd;background-color:#848386;height: 20px;padding-top: 10px;text-align:center;font-size:18px"><b>Invoice</b></textarea>
   </header>	
   
   <br/>	
   <table>
   <tr>
   <td><p style="color: blue;">JNANASUDHA ONLINE TEST SERIES <br> JNANSUDHA ENTRANCE ACADEMY LLP</p><br>
   <p style="font-size:12px;height: 200px;">
   D.NO 4-408/1,PADMAGOPAL,<br>JODURASTHE KUKKUNDOOR VILLAGE <br>AND POST, KARKALA,Udupi,<br>Karnataka, 576117.
   </p>
   <p>GSTIN:       29AARFJ8177G1ZK</p></td>
   <td style="text-align:right;"><img src="assets/studentdashboard/img/logo/logo.gif" style="width:200px;height:80px;float:right;"></td>
   
   </tr>
   </table>               				
   
   
   
   
   <article>
   <h1>Recipient</h1>

   <address contenteditable>
   <p>Name:       '.$printdata[0]['cust_name'].' </p>
   <p>Email ID:   '.$printdata[0]['email_id'].'</p>
   <p>Mobile No:  '.$printdata[0]['mobile_no'].'</p>
   </address>
   
   <table>
   <tr>
   <th></th>
   <td></td>
   <th></th>
   <td></td>
   <th class="inventory" border="2"><span>Order No #: </span></th>
   <td class="inventory" border="2"><span> '.$printdata[0]['order_no'].'</span></td>
   </tr>
     <tr>
   <th></th>
   <td></td>
   <th></th>
   <td></td>
   <th class="inventory" border="2"><span>Receipt No #: </span></th>
   <td class="inventory" border="2"><span> '.$printdata[0]['receipt_no'].'</span></td>
   </tr>
   
   <tr>
   <th></th>
   <td></td>
   <th></th>
   <td></td>
   <th class="inventory" border="2"><span contenteditable>Date:-</span></th>
   <td class="inventory" border="2"><span contenteditable> '.$printdata[0]['datetime'].'</span></td>
   </tr>
   
   </table>
   <br/>
   <table class="inventory" border="1">
   <thead>
   <tr>
   <th><span contenteditable>Item</span></th>
   <th><span contenteditable>Payment Gateway Ref</span></th>
   <th><span contenteditable>Rate</span></th>
   <th><span contenteditable>Quantity</span></th>
   <th><span contenteditable>Price</span></th>
   </tr>
   </thead>
   <tbody>
   <tr>
   <td><a class="cut">-</a><span contenteditable> '.$printdata[0]['packagename'].'</span></td>
   <td><span contenteditable></span>'.$printdata[0]['pg_refno'].'</td>
   <td style="text-align:right"><span data-prefix>&#8377;</span><span contenteditable> '.$printdata[0]['packageamount'].'</span></td>
   <td style="text-align:right"><span contenteditable>1</span></td>
   <td style="text-align:right"><span data-prefix>&#8377;</span><span contenteditable> '.$printdata[0]['packageamount'].'</span></td>
   </tr>
   </tbody>
   </table>
   <a class="add">+</a>
   <table class="">
   <tr>
   <th></th>
   <td></td>
   <th></th>
   <td></td>
   <th><span contenteditable>CGST(9%):-</span></th>
   <td style="text-align:right"><span data-prefix>&#8377;</span><span> '.$printdata[0]['cgst_amt'].'</span></td>
   </tr>
   <tr>
   <th></th>
   <td></td>
   <th></th>
   <td></td>
   <th><span contenteditable>SGST(9%):-</span></th>
   <td style="text-align:right"><span data-prefix>&#8377;</span><span> '.$printdata[0]['sgst_amt'].'</span></td>
   </tr>
   <tr>
   <th></th>
   <td></td>
   <th></th>
   <td></td>
   <th><span contenteditable>Total:-</span></th>
   <td style="text-align:right"><span data-prefix>&#8377;</span><span> '.$printdata[0]['price'].'</span></td>
   </tr>
   <tr>
   <th></th>
   <td></td>
   <th></th>
   <td></td>
   <th><span contenteditable>Amount Paid:-</span></th>
   <td style="text-align:right"><span data-prefix>&#8377;</span><span contenteditable>'.$printdata[0]['price'].'</span></td>
   </tr>
   <tr>
   <th></th>
   <td></td>
   <th></th>
   <td></td>
   <th><span contenteditable>Balance Due:-</span></th>
   <td style="text-align:right"><span data-prefix>&#8377;</span><span>0</span></td>
   </tr>
   </table>
   </article>
   <aside>
   <h1><span contenteditable>Additional Notes</span></h1>
   <div contenteditable>
   <p>This is an electronic copy, signature is not required.</p>
   <p>Fee once paid will not be refunded</p>
   </div>
   </aside>
   </body>
   </html>';
        // output the HTML content
   $pdf->writeHTML($html, true, false, true, false, '');

        // - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

        // reset pointer to the last page
   $pdf->lastPage();

        // ---------------------------------------------------------

        //Close and output PDF document
   $pdf->Output(FCPATH . 'pdf/invoices/' . $printdata[0]['order_no'] . '.pdf', 'F');

 /*  require '/phpmailer/PHPMailerAutoload.php';
   $mail = new PHPMailer;
   $mail->ClearAllRecipients();
   $mail->IsSMTP();
   $mail->SMTPAuth = true;
   $mail->Host = "smtp.pepipost.com";
   $mail->Port = 587;
   $mail->Username = "rahulpandey";
   $mail->Password = "Mgicgkp1";

   $mail->SMTPSecure = 'tls';

   $mail->setFrom('info@jnanasudha.com', 'Jnanasudha admin');
   $mail->addReplyTo('info@jnanasudha.com', 'Jnanasudha admin');
   $mailmsg = 'Dear ' . $printdata[0]['cust_name'] . ',</br> You are successfully subscribed to package ' . $printdata[0]['packagename'] . '. </br> Your payment Receipt is attached';
   $mail->addAddress($printdata[0]['email_id']);
        //$mail->AddAttachment(ABS_PATH.'pdf/'.$files);
   $mail->Subject = 'Payment Receipt from JnanaSudha';
   $mail->AddAttachment(FCPATH . 'pdf/' . $printdata[0]['order_no'] . '.pdf');

   $mail->msgHTML($mailmsg);
   $mail->IsHTML(true);
   if (!$mail->send()) {
    $error = "Mailer Error: " . $mail->ErrorInfo;
    ?><script>alert('<?php echo $error ?>');</script><?php
} else {
    echo "Message sent!";
}*/

}


public function response()
    {

        $this->load->library('nativesession');
       	log_message('info', "in pg_response()");
		//	print_r($_POST);
			
			include('Crypto.php');
			
			log_message('info', 'Displaying payment gateway response page123');
			
			//die;
	
	$workingKey='5F878407C35097C9981962B2B9157E99';		//Working Key should be provided here.
	$encResponse=$_POST["encResp"];			//This is the response sent by the CCAvenue Server
	$rcvdString=decrypt($encResponse,$workingKey);		//Crypto Decryption used as per the specified working key.
	$order_status="";
	$decryptValues=explode('&', $rcvdString);
	$dataSize=sizeof($decryptValues);
//	echo "<center>";
	//print_r($decryptValues);  $encResponse,$rcvdString

	for($i = 0; $i < $dataSize; $i++) 
	{
		$information=explode('=',$decryptValues[$i]);
		if($i==3)	$order_status=$information[1];
	}
//print_r($order_status);
	/*if($order_status==="Success")
	{
		echo "<br>Thank you for shopping with us. Your credit card has been charged and your transaction is successful. We will be shipping your order to you soon.";
		
	}
	else if($order_status==="Aborted")
	{
		echo "<br>Thank you for shopping with us.We will keep you posted regarding the status of your order through e-mail";
		
           
        
	
	}
	else if($order_status==="Failure")
	{
		echo "<br>Thank you for shopping with us.However,the transaction has been declined.";
	}
	else
	{
		echo "<br>Security Error. Illegal access detected";
	
	}

	echo "<br><br>";

	echo "<table cellspacing=4 cellpadding=4>";*/
	for($i = 0; $i < $dataSize; $i++) 
	{
		$information[$i]=explode('=',$decryptValues[$i]);
	    	//echo '<tr><td>'.$information[$i][0].'</td><td>'.urldecode($information[$i][1]).'</td></tr>';
	}

/*	echo "</table><br>";
	echo "</center>";
	echo "<pre>";
	//print_r($information);
	echo "</pre>";*/
	//die;
	
		$txn_resp['ag_id'] = 'HDFC';
		$txn_resp['me_id'] = '827354';
		$txn_resp['order_no'] = isset($information[0][1]) ? $information[0][1] : '';
		$txn_resp['amount'] = isset($information[10][1]) ? $information[10][1] : '';

		$txn_resp['country'] = isset($information[24][1]) ? $information[24][1] : '';
		$txn_resp['currency'] = isset($information[9][1]) ? $information[9][1] : '';
		$txn_resp['txn_date'] = isset($information[40][1]) ? $information[40][1] : '';
		$txn_resp['txn_time'] = '';
		$txn_resp['ag_ref'] = isset($information[1][1]) ? $information[1][1] : '';
		$txn_resp['pg_ref'] = isset($information[2][1]) ? $information[2][1] : '';
		$txn_resp['status'] = isset($information[3][1]) ? $information[3][1] : '';
		$txn_resp['res_code'] = isset($information[38][1]) ? $information[38][1] : '';
		$txn_resp['res_message'] = isset($information[8][1]) ? $information[8][1] : '';
		

		$txn_resp['udf_1'] = isset($information[26][1]) ? $information[26][1] : '';
		$txn_resp['udf_2'] = isset($information[27][1]) ? $information[27][1] : '';
		$txn_resp['udf_3'] = isset($information[28][1]) ? $information[28][1] : '';
		$txn_resp['udf_4'] = isset($information[29][1]) ? $information[29][1] : '';
		$txn_resp['udf_5'] = isset($information[30][1]) ? $information[30][1] : '';
		
		$mobile_nologin = isset($information[17][1]) ? $information[17][1] : '';
		$order_no = $txn_resp['order_no'];
				log_message('debug', substr($order_no, 1, 1));

/* 04Apr2019 - Per Rahul, different A/c Nos. to capture transaction amount under different A/c heads are to be used. Such A/c no. should be passed to PG in udf_1. So, existing use is being changed
				$item_type = $txn_resp['udf_1'];
				$fee_head = $txn_resp['udf_2'];
				$fee_type = $txn_resp['udf_3'];*/

				$acc_no = $txn_resp['udf_1'];
				$item_type = $txn_resp['udf_2'];
				$merchant_param3 = $txn_resp['udf_3'];
				$fee_type = $txn_resp['udf_4'];
				
            //echo "</pre>";
            //print_r($return_elements);die;
        

        //print_r($other_details     );
        $status = $data['response'] = $txn_resp['res_code'];
        $payment_refno = $data['pg_ref'] = $txn_resp['pg_ref'];
        $pg_refno = $data['ag_ref'] = $txn_resp['ag_ref'];
        $order_no = $data['order_no'] = $txn_resp['order_no'];
        $res_code = $data['res_code'] = $txn_resp['res_code'];
        $res_message = $data['res_message'] =$order_status;
        $this->load->model('Quiz_model', 'quiz_model');
		$noupdate =0;
		  $sql = "select count(*) count from  payment_gateway_status where pg_refno='$pg_refno' and order_no ='$order_no' ";
		//print_r($sql);
        $badreq=$this->quiz_model->get_data($sql);
		//print_r($badreq);
		
		if(  $badreq[0]['count'] > 0)
		{
			
			$res_message = 'failure';
			$res_message = 'record already Exist';
			$noupdate =1;
			
		}
		
		
		 $sql = "select count(*) count from  payment_gateway_status where  udf_1 ='$merchant_param3' and order_no ='$order_no' ";
		//print_r($sql);
        $recordexist= $this->quiz_model->get_data($sql);
		//print_r($recordexist);
		
		if(  $recordexist[0]['count'] < 1)
		{
			
			$res_message = 'failure';
			$res_message = 'improper response';
			$noupdate =1;
			
			
		}

if ($noupdate ==0){
	
	
        $sql = "update payment_gateway_status set pg_refno='$pg_refno', payment_refno='$payment_refno',status='$res_message',status_reason='$res_message' where order_no ='$order_no' 
		and udf_1 ='$merchant_param3'";
		
        $this->quiz_model->save_data($sql);
};
		$data['res_message'] =$res_message;

        if ($res_message == 'Success') {
			
			$data = array(
               'order_no' => $order_no 
            );

$this->db->insert('receipt_no', $data); 

$last_id = $this->db->insert_id();

 $sql = "update payment_gateway_status set receipt_no ='$last_id' where order_no ='$order_no' 
		and udf_1 ='$merchant_param3'";
		
        $this->quiz_model->save_data($sql);
		
		
		$sqlccavenue = "INSERT INTO `newtechv_quizmaster`.`ccavenue_response`(order_no,`encr`,`decr`) VALUES('$order_no', '$encResponse', '$rcvdString')";
		 $this->quiz_model->save_data($sqlccavenue);
          
//print_r($sql);
if ($fee_type == 'offline'){
	  $sqlgetdata = "select * from payment_gateway_status a, package_info_offline b where a.packageid =b.id and a.order_no= '$order_no'";
	  $sql = "select * from payment_gateway_status a, package_info_offline b where a.packageid =b.id and a.order_no= '$order_no'";
	  
	   $sqlupdstatus ="update offline_assigned_package a inner join payment_gateway_status b on a.user_name = b.mobile_no 
	   set a.status = 'Paid'
where a.package_id  = b.packageid
and b.order_no = '$order_no'";

 $this->quiz_model->save_data($sqlupdstatus);
	
}else{
	  $sqlgetdata = "select * from payment_gateway_status a, package_info b where a.packageid =b.id and a.order_no= '$order_no'";
	  $sql = "select * from payment_gateway_status a, package_info b where a.packageid =b.id and a.order_no= '$order_no'";
	  
	    $sqlsubs = "INSERT INTO `subscription_details`
            (`org_id`,`id`,`name`,`phone_no`,`email`,`username`,`password`,`package_id`,`package_name`,`package_amount`,
            `subscribed_on`,`end_date`)
            select 1,'',`cust_name`,`mobile_no`,`email_id`,`mobile_no`,'',packageid,packagename,amount,now(),now()
            from payment_gateway_status where order_no = '$order_no'";
			 $this->quiz_model->save_data($sqlsubs);
	
}
          
            $PRINTDATA = $this->quiz_model->get_data($sqlgetdata);

          //  $this->quiz_model->save_data($sql);
            
//    print_r($sql);
            $data['packagedetails'] = $this->quiz_model->get_data($sql);

            $this->create_pdf($PRINTDATA);

        }
		//print_r($data);
		$data['ord_stat'] = $order_status;
        $this->load->view('responsecc', $data);
    }
	public function download($file){
		$this->load->helper('download'); 
			$filename=$this->input->get('file');
			print_r($filename);
        $data = file_get_contents(FCPATH.'pdf/invoices/' . $filename); // Read the file's contents
        $name = $filename;
        force_download($name, $data);
    }
	
	public function downloadreportcsat() {
	   try { 
	$quiz_id = isset($_GET['quiz_id']) ? $_GET['quiz_id'] : null;
	     $this->load->model('Quiz_model', 'quiz_model');
		 $sqlgetdata='select concat(b.first_name," ",b.last_name) name,a.user_id,quiz_id,physicsattempted attempted ,
physicscorrect correct , physicswrong wrong, physicsmark mark from student_quiz_result a,user_details b 
where a.user_id collate utf8_general_ci = b.user_name collate utf8_general_ci and   a.quiz_id ="'.$quiz_id.'"';

//select concat(b.first_name," ",b.last_name) name,a.* from student_quiz_result_foundation a,user_details b 
//where a.user_id collate utf8_general_ci = b.user_name collate utf8_general_ci and   a.quiz_id ="2822";
	    
	   	$downloaddata=$this->quiz_model->get_data($sqlgetdata);
		
		$file_name =  $quiz_id.date('Ymd').'.csv'; 
     header("Content-Description: File Transfer"); 
     header("Content-Disposition: attachment; filename=$file_name"); 
     header("Content-Type: application/csv;");
   
  
     $file = fopen('php://output', 'w');
 
     $header = array("name","user_id","quiz_id","attempted","correct","wrong","mark"); 
     fputcsv($file, $header);
     foreach ($downloaddata as $key => $value)
     { 
       fputcsv($file, $value); 
     }
     fclose($file); 
     exit; 
    }catch (Exception $e) {
      echo $e->getMessage();
    }
}
/*public function downloadquizreport() {
  try { 
	$quiz_id = isset($_GET['quiz_id']) ? $_GET['quiz_id'] : null;
	     $this->load->model('Quiz_model', 'quiz_model');
		 $sqlgetdata='select concat(b.first_name," ",b.last_name) name,a.* from student_quiz_result a,user_details b 
where a.user_id collate utf8_general_ci = b.user_name collate utf8_general_ci and   a.quiz_id ="'.$quiz_id.'"';


	  //  print_r($sqlgetdata);
		//die;
	   	$downloaddata=$this->quiz_model->get_data($sqlgetdata);
		
		$file_name =  $quiz_id.date('Ymd').'.csv'; 
     header("Content-Description: File Transfer"); 
     header("Content-Disposition: attachment; filename=$file_name"); 
     header("Content-Type: application/csv;");
   
  
     $file = fopen('php://output', 'w');
 
     $header = array("name","id","user_id","quiz_id","st_id","physicsattempted","chemistryattempted","mathsattempted","physicscorrect","chemistrycorrect","mathscorrect","physicswrong","chemistrywrong","mathswrong","physicsmark","chemistrymark","mathsmark","rank"); 
     fputcsv($file, $header);
     foreach ($downloaddata as $key => $value)
     { 
       fputcsv($file, $value); 
     }
     fclose($file); 
     exit; 
    }catch (Exception $e) {
      echo $e->getMessage();
    }
}*/
//  public function quiz_report7() {
// 	    $role_id = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : null;
//         $username = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Guest';

//         // $data['quiz_types'] = $this->quiz_model->get_quiz_types();
//         // $data['quiz_name'] = $this->quiz_model->get_quizzes_by_type($quiztype);


// 	$quiztype = $_POST['quiztype'] ?? '';
//  // selected quiz type

// $data['quiz_types'] = $this->quiz_model->get_quiz_types();

// $data['quiz_names'] = [];
// if (!empty($quiztype)) {
//     $data['quiz_names'] = $this->quiz_model->get_quizzes_by_type($quiztype);
// }
//         $data['quizzes'] = array(); // empty initially
// 		$data['user_role'] = $role_id;
//         $data['user_name'] = $username;
//         if ($role_id) {
//             $data['top_menus'] = $this->menu_model->get_top_menus($role_id);
//         } else {
//             $data['top_menus'] = array();
//         }

//         $this->load->view('quizdownload/quiz_report_view', $data);
//   

/*public function downloadquizreport() {
  try { 
	   $quiz_type = isset($_POST['quiztype']) ? strtolower(trim($_POST['quiztype'])) : null;
       $quiz_id=isset($_POST['id'])?trim($_POST['id']):null;
	   //$quiz_id=$_POST['id']?? null;
	  // $quiz_type=$_POST['quiztype']?? null;
	   if(!$quiz_id||!$quiz_type){
		 echo'invalid request';
		 exit;
	   }
	     $report = $this->getQuizReportData($quiz_type, $quiz_id);
	     $this->load->model('Quiz_model', 'quiz_model');

		 if($quiz_type=="CAF1"){
			$sql = 'select concat(b.first_name," ",b.last_name) name,a.* from student_quiz_result a,user_details b 
			where a.user_id collate utf8_general_ci = b.user_name collate utf8_general_ci and   a.quiz_id ="'.$quiz_id.'"';

            $header = array("name", "user_id", "quiz_id", "st_id", "physicsattempted", "chemistryattempted", "biologyattempted", "physicscorrect", "chemistrycorrect", "biologycorrect", "physicswrong", "chemistrywrong", "biologywrong", "physicsmark", "chemistrymark", "biologymark", "rank"); 

		//$header = array("name","id","user_id","id","st_id","physicsattempted","chemistryattempted","mathsattempted","physicscorrect","chemistrycorrect","mathscorrect","physicswrong","chemistrywrong","mathswrong","physicsmark","chemistrymark","mathsmark","rank"); 
		 //$header = array("name", "id", "user_id", "quiz_id", "st_id", "physicsattempted", "chemistryattempted", "biologyattempted", "physicscorrect", "chemistrycorrect", "biologycorrect", "physicswrong", "chemistrywrong", "biologywrong", "physicsmark", "chemistrymark", "biologymark", "rank"); 
         }elseif
		 ($quiz_type=="cs2024"){

    $sql='select concat(b.first_name," ",b.last_name) name,a.user_id,
		a.quiz_id,a.st_id,a.bcattempted,a.lawattempted,a.lrattempted,a.qaattempted,
	    a.ecoattempted,a.benvattempted,a.caattempted,a.bccorrect,a.lawcorrect,a.lrcorrect,
		a.qacorrect,a.ecocorrect,a.benvcorrect,a.cacorrect,a.bcwrong,a.lawwrong,a.lrwrong,
		a.qawrong,a.ecowrong,a.benvwrong,a.cawrong,a.bcmark,a.lawmark,a.lrmark,a.qamark,a.ecomark,
		a.benvmark,a.camark from student_quiz_result_cs2024 a,user_details b 
		where a.user_id collate utf8_general_ci = b.user_name collate utf8_general_ci and   a.quiz_id ="'.$quiz_id.'"';

	$header = array("name","user_id","quiz_id","st_id","bcattempted","lawattempted",
		"lrattempted","qaattempted","ecoattempted","benvattempted","caattempted",
		"bccorrect","lawcorrect","lrcorrect","qacorrect","ecocorrect","benvcorrect",
		"cacorrect","bcwrong","lawwrong","lrwrong","qawrong","ecowrong","benvwrong",
		"cawrong","bcmark","lawmark","lrmark","qamark","ecomark","benvmark","camark"); 


} elseif($quiz_type=="foundation"){
	    $sql = 'select concat(b.first_name," ",b.last_name) name,b.rollno,b.accommodation,b.batch,a.* from student_quiz_result_foundation a,user_details b 
               where a.user_id collate utf8_general_ci = b.user_name collate utf8_general_ci and   a.quiz_id ="'.$quiz_id.'"';

        $header = array("name","rollno","accommodation","batch","id","user_id","quiz_id","st_id","physicsattempted","chemistryattempted","mathsattempted","bioattempted","physicscorrect","chemistrycorrect","mathscorrect","biocorrect","physicswrong","chemistrywrong","mathswrong","biowrong","physicsmark","chemistrymark","mathsmark","biomark","rank"); 

		 }elseif($quiz_type == 'csat'){
			 $sql='select concat(b.first_name," ",b.last_name) name,a.user_id,quiz_id,physicsattempted attempted ,
				physicscorrect correct , physicswrong wrong, physicsmark mark from student_quiz_result a,user_details b 
				where a.user_id collate utf8_general_ci = b.user_name collate utf8_general_ci and   a.quiz_id ="'.$quiz_id.'"';
				$header = array("name","user_id","quiz_id","attempted","correct","wrong","mark");
				}
		 elseif($quiz_type == 'subjectmath'){
			 $sql='select concat(b.first_name," ",b.last_name) name,a.user_id,quiz_id,physicsattempted attempted ,
				physicscorrect correct , physicswrong wrong, physicsmark mark from student_quiz_result a,user_details b 
				where a.user_id collate utf8_general_ci = b.user_name collate utf8_general_ci and   a.quiz_id ="'.$quiz_id.'"';

		      $header = array("name","user_id","quiz_id","attempted","correct","wrong","mark"); 


		 }elseif($quiz_type=='jee'){
			$sql = "
		    SELECT 
			CONCAT(b.first_name,' ',b.last_name) AS name,
			b.rollno,
			b.batch,
			b.accommodation,
			a.user_id,
			a.quiz_id,
			a.st_id,
			a.physicsattempted,
			a.chemistryattempted,
			a.biologyattempted,
			a.physicscorrect,
			a.chemistrycorrect,
			a.biologycorrect,
			a.physicswrong,
			a.chemistrywrong,
			a.biologywrong,
			a.physicsmark,
			a.chemistrymark,
			a.biologymark,
			(a.physicsmark + a.chemistrymark + a.biologymark) AS total,
			a.rank
		FROM student_quiz_result a
		JOIN user_details b 
			ON a.user_id COLLATE utf8_general_ci = b.user_name COLLATE utf8_general_ci
		WHERE a.quiz_id = '".$quiz_id."'
		ORDER BY CONVERT(a.rank, SIGNED INTEGER)
		";
		$header = array(
			"name",
			"rollno",
			"batch",
			"accommodation",
			"user_id",
			"quiz_id",
			"st_id",
			"physicsattempted",
			"chemistryattempted",
			"biologyattempted",
			"physicscorrect",
			"chemistrycorrect",
			"biologycorrect",
			"physicswrong",
			"chemistrywrong",
			"biologywrong",
			"physicsmark",
			"chemistrymark",
			"biologymark",
			"total",
			"rank"
		);
}
  elseif($quiz_type=='neetshort'){
   $sql="SELECT 
    r.rank,
    r.name,
    r.rollno,
    r.batch,
    r.accommodation,
    r.user_id,
    r.quiz_id,
    r.st_id,
    r.physicsattempted,
    r.chemistryattempted,
    r.biologyattempted,
    r.physicscorrect,
    r.chemistrycorrect,
    r.biologycorrect,
    r.physicswrong,
    r.chemistrywrong,
    r.biologywrong,
    r.physicsmark,
    r.chemistrymark,
    r.biologymark,
    r.total
FROM (
    SELECT 
        @rank := @rank + 1 AS rank,
        CONCAT(b.first_name,' ',b.last_name) AS name,
        b.rollno,
        b.batch,
        b.accommodation,
        a.user_id,
        a.quiz_id,
        a.st_id,
        a.physicsattempted,
        a.chemistryattempted,
        a.biologyattempted,
        a.physicscorrect,
        a.chemistrycorrect,
        a.biologycorrect,
        a.physicswrong,
        a.chemistrywrong,
        a.biologywrong,
        a.physicsmark,
        a.chemistrymark,
        a.biologymark,
        (a.physicsmark + a.chemistrymark + a.biologymark) AS total
    FROM student_quiz_result a
    JOIN user_details b 
        ON a.user_id COLLATE utf8_general_ci = b.user_name COLLATE utf8_general_ci
    CROSS JOIN (SELECT @rank := 0) r
    WHERE a.quiz_id = '".$quiz_id."'
    ORDER BY 
        total DESC,
        a.biologymark DESC,
        name ASC
) r
ORDER BY r.rank";
$header = array(
    "rank",
    "name",
    "rollno",
    "batch",
    "accommodation",
    "user_id",
    "quiz_id",
    "st_id",
    "physicsattempted",
    "chemistryattempted",
    "biologyattempted",
    "physicscorrect",
    "chemistrycorrect",
    "biologycorrect",
    "physicswrong",
    "chemistrywrong",
    "biologywrong",
    "physicsmark",
    "chemistrymark",
    "biologymark",
    "total"
);

}

 else{
	echo "<script>
	alert('INVALID QUIZ TYPE');
    window.location.href = '" . base_url('Admin/quiz_report7') . "';
	</script>";
	exit;

    


		 }
       //file download 
	  //  print_r($sqlgetdata);
		//die;
	   	$downloaddata=$this->quiz_model->get_data($sql);
		
	 $file_name =  $quiz_id.date('Ymd').'.csv'; 
     header("Content-Description: File Transfer"); 
     header("Content-Disposition: attachment; filename=$file_name"); 
     header("Content-Type: application/csv;");
   
  
     $file = fopen('php://output', 'w');
 
    // $header = array("name","id","user_id","quiz_id","st_id","physicsattempted","chemistryattempted","mathsattempted","physicscorrect","chemistrycorrect","mathscorrect","physicswrong","chemistrywrong","mathswrong","physicsmark","chemistrymark","mathsmark","rank"); 
     fputcsv($file, $header);
     foreach ($downloaddata as $key => $value)
     { 
       fputcsv($file, $value); 
     }
     fclose($file); 
     exit; 
    }catch (Exception $e) {
      echo $e->getMessage();
    }
}*/
public function quiz_report7()
{
	$quiztype = isset($_POST['quiztype']) ? $_POST['quiztype'] : '';
	$quiz_id  = isset($_POST['id']) ? $_POST['id'] : '';
	$role_id = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : null;
    $username = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Guest';


    $data['quiz_types'] = $this->quiz_model->get_quiz_types();
    $data['quiz_names'] = [];
	$data['report']     = null; 
    
	if (!empty($quiztype)) {
    $data['quiz_names'] = $this->quiz_model->get_quizzes_by_type($quiztype);

    $valid_ids = array_column($data['quiz_names'], 'id');
    if (!in_array($quiz_id, $valid_ids)) {
        $quiz_id = '';
    }
	}
	if (!empty($quiztype) && !empty($quiz_id)) {        
        $data['report'] = $this->getQuizReportData(
            $quiztype,
            $quiz_id
        ); 
	 } 
	$data['user_role'] = $role_id;
       $data['user_name'] = $username;
        if ($role_id) {
            $data['top_menus'] = $this->menu_model->get_top_menus($role_id);
        } else {
            $data['top_menus'] = array();
        }

    $this->load->view('quizdownload/quiz_report_view', $data);
}
public function downloadquizreport()
{
    try {

		$quiz_type = strtolower(trim(isset($_POST['quiztype']) ? $_POST['quiztype'] : ''));
		$quiz_id   = trim(isset($_POST['id']) ? $_POST['id'] : '');

        if (!$quiz_type || !$quiz_id) {
            echo 'Invalid request';
            exit;
        }

        
        $report = $this->getQuizReportData($quiz_type, $quiz_id);

        if (!$report || empty($report['data'])) {
            echo 'No data found';
            exit;
        }

        $file_name = $quiz_id . date('Ymd') . '.csv';

        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=$file_name");
        header("Content-Type: application/csv;");

        $file = fopen('php://output', 'w');

        // header row
        fputcsv($file, $report['header']);

        // data rows
        foreach ($report['data'] as $row) {
            fputcsv($file, $row);
        }

        fclose($file);
        exit;

    } catch (Exception $e) {
        echo $e->getMessage();
    }
}

public function downloadquizexcel()
{
    try {

		$quiz_type = strtolower(trim(isset($_POST['quiztype']) ? $_POST['quiztype'] : ''));
		$quiz_id   = trim(isset($_POST['id']) ? $_POST['id'] : '');

        if (!$quiz_type || !$quiz_id) {
            echo 'Invalid request';
            exit;
        }

        
        $report = $this->getQuizReportData($quiz_type, $quiz_id);

        if (!$report || empty($report['data'])) {
            echo 'No data found';
            exit;
        }

        $file_name = $quiz_id . date('Ymd') . '.xls';

        // Use CSV format but with Excel headers for proper column separation
        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=$file_name");
        header("Content-Type: application/vnd.ms-excel; charset=UTF-8");
        header("Pragma: no-cache");
        header("Expires: 0");

        $file = fopen('php://output', 'w');

        // Set BOM for UTF-8
        fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

        // Add header row
        fputcsv($file, $report['header']);

        // Add data rows
        foreach ($report['data'] as $row) {
            $row_data = array();
            foreach ($report['header'] as $col_name) {
				$row_data[] = isset($row[$col_name]) ? $row[$col_name] : '';
            }
            fputcsv($file, $row_data);
        }

        fclose($file);
        exit;

    } catch (Exception $e) {
        echo $e->getMessage();
    }
}

public function getQuizReportData($quiz_type, $quiz_id)
{
    $quiz_type = strtolower(trim($quiz_type));

    if($quiz_type=="jut"){
			$sql = 'select concat(b.first_name," ",b.last_name) name,a.* from student_quiz_result a,user_details b 
			where a.user_id collate utf8_general_ci = b.user_name collate utf8_general_ci and   a.quiz_id ="'.$quiz_id.'"';

            $header = array("name", "user_id", "quiz_id", "st_id", "physicsattempted", "chemistryattempted", "biologyattempted", "physicscorrect", "chemistrycorrect", "biologycorrect", "physicswrong", "chemistrywrong", "biologywrong", "physicsmark", "chemistrymark", "biologymark", "rank"); 
      } elseif ($quiz_type == "cs2024") {

         $sql='select concat(b.first_name," ",b.last_name) name,a.user_id,
				a.quiz_id,a.st_id,a.bcattempted,a.lawattempted,a.lrattempted,a.qaattempted,
				a.ecoattempted,a.benvattempted,a.caattempted,a.bccorrect,a.lawcorrect,a.lrcorrect,
				a.qacorrect,a.ecocorrect,a.benvcorrect,a.cacorrect,a.bcwrong,a.lawwrong,a.lrwrong,
				a.qawrong,a.ecowrong,a.benvwrong,a.cawrong,a.bcmark,a.lawmark,a.lrmark,a.qamark,a.ecomark,
				a.benvmark,a.camark from student_quiz_result_cs2024 a,user_details b 
				where a.user_id collate utf8_general_ci = b.user_name collate utf8_general_ci and   a.quiz_id ="'.$quiz_id.'"';

	$header = array("name","user_id","quiz_id","st_id","bcattempted","lawattempted",
				"lrattempted","qaattempted","ecoattempted","benvattempted","caattempted",
				"bccorrect","lawcorrect","lrcorrect","qacorrect","ecocorrect","benvcorrect",
				"cacorrect","bcwrong","lawwrong","lrwrong","qawrong","ecowrong","benvwrong",
				"cawrong","bcmark","lawmark","lrmark","qamark","ecomark","benvmark","camark"); 


    } elseif ($quiz_type == "foundation") {

        $sql = 'select concat(b.first_name," ",b.last_name) name,b.rollno,b.accommodation,b.batch,a.* from student_quiz_result_foundation a,user_details b 
               where a.user_id collate utf8_general_ci = b.user_name collate utf8_general_ci and   a.quiz_id ="'.$quiz_id.'"';

        $header = array("name","rollno","accommodation","batch","id","user_id","quiz_id","st_id","physicsattempted","chemistryattempted","mathsattempted","bioattempted","physicscorrect","chemistrycorrect","mathscorrect","biocorrect","physicswrong","chemistrywrong","mathswrong","biowrong","physicsmark","chemistrymark","mathsmark","biomark","rank"); 


}elseif($quiz_type == 'csat'){
			 $sql='select concat(b.first_name," ",b.last_name) name,a.user_id,quiz_id,physicsattempted attempted ,
				physicscorrect correct , physicswrong wrong, physicsmark mark from student_quiz_result a,user_details b 
				where a.user_id collate utf8_general_ci = b.user_name collate utf8_general_ci and   a.quiz_id ="'.$quiz_id.'"';
				$header = array("name","user_id","quiz_id","attempted","correct","wrong","mark");
				}
		 elseif($quiz_type == 'subjectmath'){
			 $sql='select concat(b.first_name," ",b.last_name) name,a.user_id,quiz_id,physicsattempted attempted ,
				physicscorrect correct , physicswrong wrong, physicsmark mark from student_quiz_result a,user_details b 
				where a.user_id collate utf8_general_ci = b.user_name collate utf8_general_ci and   a.quiz_id ="'.$quiz_id.'"';

		      $header = array("name","user_id","quiz_id","attempted","correct","wrong","mark"); 

			  } elseif($quiz_type=='jee2021'){
			$sql = "
					SELECT CONCAT(b.first_name,' ',b.last_name) AS name,b.rollno,b.batch,b.accommodation,a.user_id,a.quiz_id,
					a.st_id,a.physicsattempted,a.chemistryattempted,a.biologyattempted,a.physicscorrect,a.chemistrycorrect,a.biologycorrect,
					a.physicswrong,a.chemistrywrong,a.biologywrong,a.physicsmark,a.chemistrymark,a.biologymark,(a.physicsmark + a.chemistrymark + a.biologymark) AS total,
					a.rank
				FROM student_quiz_result a
				JOIN user_details b 
					ON a.user_id COLLATE utf8_general_ci = b.user_name COLLATE utf8_general_ci
				WHERE a.quiz_id = '".$quiz_id."'
				ORDER BY CONVERT(a.rank, SIGNED INTEGER)
				";
		$header = array("name","rollno","batch","accommodation","user_id","quiz_id","st_id","physicsattempted","chemistryattempted",
				"biologyattempted","physicscorrect","chemistrycorrect","biologycorrect","physicswrong","chemistrywrong","biologywrong",
				"physicsmark","chemistrymark","biologymark","total","rank"
		);
}
elseif($quiz_type=='neetfdtntest'){
   $sql="SELECT 
				r.rank, r.name, r.rollno,r.batch, r.accommodation,r.user_id,r.quiz_id,r.st_id,r.physicsattempted,
				r.chemistryattempted,r.biologyattempted,r.physicscorrect,r.chemistrycorrect,r.biologycorrect,r.physicswrong,
				r.chemistrywrong,r.biologywrong,r.physicsmark,r.chemistrymark,r.biologymark,r.total
FROM (
    SELECT 
				@rank := @rank + 1 AS rank,
				CONCAT(b.first_name,' ',b.last_name) AS name,
				b.rollno,b.batch,b.accommodation,a.user_id,a.quiz_id,a.st_id,a.physicsattempted,a.chemistryattempted,
				a.biologyattempted, a.physicscorrect, a.chemistrycorrect, a.biologycorrect,a.physicswrong,a.chemistrywrong,a.biologywrong,
				a.physicsmark,a.chemistrymark, a.biologymark,(a.physicsmark + a.chemistrymark + a.biologymark) AS total
			FROM student_quiz_result a
			JOIN user_details b 
				ON a.user_id COLLATE utf8_general_ci = b.user_name COLLATE utf8_general_ci
			CROSS JOIN (SELECT @rank := 0) r
			WHERE a.quiz_id = '".$quiz_id."'
			ORDER BY 
				total DESC,
				a.biologymark DESC,
				name ASC
		) r
		ORDER BY r.rank";
$header = array( "rank","name","rollno","batch","accommodation","user_id","quiz_id","st_id","physicsattempted","chemistryattempted",
		"biologyattempted","physicscorrect","chemistrycorrect","biologycorrect","physicswrong","chemistrywrong",
		"biologywrong","physicsmark","chemistrymark","biologymark","total"
);
}else {
        return false;
    }

    $data = $this->quiz_model->get_data($sql);

    return [
        'header' => $header,
        'data'   => $data
    ];
}


public function downloadreportfoundation() {
	   try { 
	$quiz_id = isset($_GET['quiz_id']) ? $_GET['quiz_id'] : null;
	     $this->load->model('Quiz_model', 'quiz_model');
		 $sqlgetdata='select concat(b.first_name," ",b.last_name) name,b.rollno,b.accommodation,b.batch,a.* from student_quiz_result_foundation a,user_details b 
where a.user_id collate utf8_general_ci = b.user_name collate utf8_general_ci and   a.quiz_id ="'.$quiz_id.'"';


	  //  print_r($sqlgetdata);
		//die;
	   	$downloaddata=$this->quiz_model->get_data($sqlgetdata);
		
		$file_name =  $quiz_id.date('Ymd').'.csv'; 
     header("Content-Description: File Transfer"); 
     header("Content-Disposition: attachment; filename=$file_name"); 
     header("Content-Type: application/csv;");
   
  
     $file = fopen('php://output', 'w');
 
     $header = array("name","rollno","accommodation","batch","id","user_id","quiz_id","st_id","physicsattempted","chemistryattempted","mathsattempted","bioattempted","physicscorrect","chemistrycorrect","mathscorrect","biocorrect","physicswrong","chemistrywrong","mathswrong","biowrong","physicsmark","chemistrymark","mathsmark","biomark","rank"); 
     fputcsv($file, $header);
     foreach ($downloaddata as $key => $value)
     { 
       fputcsv($file, $value); 
     }
     fclose($file); 
     exit; 
    }catch (Exception $e) {
      echo $e->getMessage();
    }
}


public function downloadreportentrance() {
	   try { 
	$quiz_id = isset($_GET['quiz_id']) ? $_GET['quiz_id'] : null;
	     $this->load->model('Quiz_model');
		 $sqlgetdata='select concat("KJS25H",lpad(a.id,4,"0")),a.student_name,a.father_name,a.mother_name,a.gender,a.city,a.nationality,a.ten_std_board,a.sslc_skl_name,a.address,a.ninth_std_math,a.ninth_std_science,a.dist_of_skl,a.state_of_skl,a.accomodation,a.mobile_no,a.whatsapp_no,b.user_id,b.quiz_id,b.physicsattempted,b.chemistryattempted,b.mathsattempted,b.bioattempted,b.physicscorrect,b.chemistrycorrect,b.mathscorrect,b.biocorrect,b.physicswrong,b.chemistrywrong,b.mathswrong,b.biowrong,b.physicsmark,b.chemistrymark,b.mathsmark,b.biomark from entranceexam a, student_quiz_result_foundation  b where a.mobile_no  = b.user_id and   b.quiz_id ="'.$quiz_id.'"';


	  //  print_r($sqlgetdata);
		//die;
	   	$downloaddata=$this->quiz_model->get_data($sqlgetdata);
		
		$file_name =  $quiz_id.date('Ymd').'.csv'; 
     header("Content-Description: File Transfer"); 
     header("Content-Disposition: attachment; filename=$file_name"); 
     header("Content-Type: application/csv;");
   
  
     $file = fopen('php://output', 'w');
 
     $header = array("ref_no","student_name","father_name","mother_name","gender","city","nationality","ten_std_board","sslc_skl_name","address","ninth_std_math","ninth_std_science","dist_of_skl","state_of_skl","accomodation","mobile_no","whatsapp_no","user_id","quiz_id","physicsattempted","chemistryattempted","mathsattempted","bioattempted","physicscorrect","chemistrycorrect","mathscorrect","biocorrect","physicswrong","chemistrywrong","mathswrong","biowrong","physicsmark","chemistrymark","mathsmark","biomark"); 
     fputcsv($file, $header);
     foreach ($downloaddata as $key => $value)
     { 
       fputcsv($file, $value); 
     }
     fclose($file); 
     exit; 
    }catch (Exception $e) {
      echo $e->getMessage();
    }
}



public function downloadreportsubjectmath() {
	   try { 
	$quiz_id = isset($_GET['quiz_id']) ? $_GET['quiz_id'] : null;
	     $this->load->model('Quiz_model', 'quiz_model');
		 $sqlgetdata='select concat(b.first_name," ",b.last_name) name,a.user_id,quiz_id,physicsattempted attempted ,
physicscorrect correct , physicswrong wrong, physicsmark mark from student_quiz_result a,user_details b 
where a.user_id collate utf8_general_ci = b.user_name collate utf8_general_ci and   a.quiz_id ="'.$quiz_id.'"';

//select concat(b.first_name," ",b.last_name) name,a.* from student_quiz_result_foundation a,user_details b 
//where a.user_id collate utf8_general_ci = b.user_name collate utf8_general_ci and   a.quiz_id ="2822";
	    
	   	$downloaddata=$this->quiz_model->get_data($sqlgetdata);
		
		$file_name =  $quiz_id.date('Ymd').'.csv'; 
     header("Content-Description: File Transfer"); 
     header("Content-Disposition: attachment; filename=$file_name"); 
     header("Content-Type: application/csv;");
   
  
     $file = fopen('php://output', 'w');
 
     $header = array("name","user_id","quiz_id","attempted","correct","wrong","mark"); 
     fputcsv($file, $header);
     foreach ($downloaddata as $key => $value)
     { 
       fputcsv($file, $value); 
     }
     fclose($file); 
     exit; 
    }catch (Exception $e) {
      echo $e->getMessage();
    }
}

public function downloadreportcs2024() {
	   try { 
	$quiz_id = isset($_GET['quiz_id']) ? $_GET['quiz_id'] : null;
	     $this->load->model('Quiz_model');
		 $sqlgetdata='select concat(b.first_name," ",b.last_name) name,a.user_id,
a.quiz_id,
a.st_id,
a.bcattempted,
a.lawattempted,
a.lrattempted,
a.qaattempted,
a.ecoattempted,
a.benvattempted,
a.caattempted,
a.bccorrect,
a.lawcorrect,
a.lrcorrect,
a.qacorrect,
a.ecocorrect,
a.benvcorrect,
a.cacorrect,
a.bcwrong,
a.lawwrong,
a.lrwrong,
a.qawrong,
a.ecowrong,
a.benvwrong,
a.cawrong,
a.bcmark,
a.lawmark,
a.lrmark,
a.qamark,
a.ecomark,
a.benvmark,
a.camark from student_quiz_result_cs2024 a,user_details b 
where a.user_id collate utf8_general_ci = b.user_name collate utf8_general_ci and   a.quiz_id ="'.$quiz_id.'"';

//select concat(b.first_name," ",b.last_name) name,a.* from student_quiz_result_foundation a,user_details b 
//where a.user_id collate utf8_general_ci = b.user_name collate utf8_general_ci and   a.quiz_id ="2822";
	    
	   	$downloaddata=$this->quiz_model->get_data($sqlgetdata);
		
		$file_name =  $quiz_id.date('Ymd').'.csv'; 
     header("Content-Description: File Transfer"); 
     header("Content-Disposition: attachment; filename=$file_name"); 
     header("Content-Type: application/csv;");
   
  
     $file = fopen('php://output', 'w');
 
     $header = array("name","user_id","quiz_id","st_id","bcattempted","lawattempted","lrattempted","qaattempted","ecoattempted","benvattempted","caattempted","bccorrect","lawcorrect","lrcorrect","qacorrect","ecocorrect","benvcorrect","cacorrect","bcwrong","lawwrong","lrwrong","qawrong","ecowrong","benvwrong","cawrong","bcmark","lawmark","lrmark","qamark","ecomark","benvmark","camark"); 
     fputcsv($file, $header);
     foreach ($downloaddata as $key => $value)
     { 
       fputcsv($file, $value); 
     }
     fclose($file); 
     exit; 
    }catch (Exception $e) {
      echo $e->getMessage();
    }
}
public function mathlive()
	{
		//$this->main_menu();
		$this->load->view('mathlive');
	}
	
public function quizmaterial()
	{
		//$this->main_menu();
		$this->load->model('Admin_Model');
		$this->load->view('quizmaterial');
	}
	
	  public function batch()
        {
			//$this->main_menu();
		    $this->load->model('Admin_Model');
			$this->data['batch_details']= $this->Admin_Model->getallbatch();
			$this->data['college_details']= $this->Admin_Model->getallcolleges();
            $this->load->view('Create_batch',$this->data);
        }
        public function save_batch()
        {
            //$this->main_menu();
            $this->load->model('Admin_Model');
            $this->Admin_Model->save_batch();
			$this->data['batch_details']= $this->Admin_Model->getallbatch();
		    $this->load->view('Create_batch',$this->data);
        }
		
		  public function college()
        {
			//$this->main_menu();
            $this->load->model('Admin_Model');
			$this->data['college_details']= $this->Admin_Model->getallcolleges();
            $this->load->view('Create_college',$this->data);
        }
        public function save_college()
        {
            //$this->main_menu();
            $this->load->model('Admin_Model');
            $this->Admin_Model->save_college();
			$this->data['college_details']= $this->Admin_Model->getallcolleges();
            $this->load->view('Create_college',$this->data);
        }
		
		  public function quiz_feedback_param()
        {
			//$this->main_menu();
			$this->load->model('Admin_Model');
			$this->data['quiz_assign_package']= $this->Admin_Model->findsummaryForquizlist();
			//print_r($this->data['quiz_assign_package']);

			if($_POST['showvalues']==1){ 
			$package_id = $this->input->post("package_id");
			//print_r($package_id);

			$this->data['quiz_details']= $this->Admin_Model->findAllquizforpackageadmin($package_id);
			$this->data['batch_details']= $this->Admin_Model->getallbatch();
			$this->data['subject_details']= $this->Admin_Model->getallsubject();
		
		$this->data['teachers']=$this->Admin_Model->get_allteacherinfo();
			}
			//$this->data['feedback_details']= $this->Admin_Model->getfeedbackdetails();
			$this->load->view('quiz_feedback_param',$this->data);
        }
		
		  public function savequiz_feedback_param()
        {
			//echo "<pre>";
			
			//print_r($_POST);
			//echo "</pre>";
			
           // $this->main_menu();
            $this->load->model('Admin_Model');
            $this->Admin_Model->savequiz_feedback_param();
			$this->data['quiz_details']= $this->Admin_Model->get_quiz_feedback_param();
			$this->load->view('Quiz_feedback_details',$this->data);
        }
		
		
		public function get_quiz_feedback_param()
	{
		//$this->main_menu();
		$this->data['quiz_details']= $this->Admin_Model->get_quiz_feedback_param();
		$this->load->view('Quiz_feedback_details',$this->data);
	}
	


		  public function quiz_feedback_report()
        {
			//$this->main_menu();
			$this->load->model('Admin_Model');
			$this->data['quiz_assign_package']= $this->Admin_Model->findsummaryForquizlist();
			//print_r($this->data['quiz_assign_package']);

			//if($_POST['showvalues']==1){ 
			$package_id = $this->input->post("package_id");
			//print_r($package_id);

			$this->data['quiz_details']= $this->Admin_Model->findAllquizforpackage($package_id);
			$this->data['batch_details']= $this->Admin_Model->getallbatch();
			$this->data['subject_details']= $this->Admin_Model->getallsubject();
		
		$this->data['teachers']=$this->Admin_Model->get_allteacherinfo();
		//	}
			//$this->data['feedback_details']= $this->Admin_Model->getfeedbackdetails();
			$this->load->view('quiz_feedback_report',$this->data);
        }
		
		
			  public function quiz_feedback_reports()
        {
			//$this->main_menu();
			$this->load->model('Admin_Model');
			$this->data['subject_details']= $this->Admin_Model->findsubjectsforquiz();
			//print_r($this->data['quiz_assign_package']);

			//if($_POST['showvalues']==1){ 
			$package_id = $this->input->post("package_id");
			//print_r($package_id);

			$this->data['quiz_details']= $this->Admin_Model->findAllquizforpackage($package_id);
			$this->data['batch_details']= $this->Admin_Model->getallbatch();
			$this->data['subject_details']= $this->Admin_Model->getallsubject();
		
		$this->data['teachers']=$this->Admin_Model->get_allteacherinfo();
		//	}
			//$this->data['feedback_details']= $this->Admin_Model->getfeedbackdetails();
			$this->load->view('quiz_feedback_reports',$this->data);
        }
		
		public function getteacher()
	{
		//$this->main_menu();	
		$this->load->model('Admin_Model');
		$subject = $_POST['subject'];
		//echo($subject);
		//die;
		
		$data['teacher']= $this->Admin_Model->getallteacher($subject);
		//$data['quiz_subject_name']= $this->Admin_Model->quizsubjectname();
		//$this->data['result'] = $this->Admin_Model->savenewpack();
		//print_r($data['quiz_package_link']);	
		echo json_encode($data['teacher']);		
	}
	
		public function getbatch()
	{
		//$this->main_menu();	
		$this->load->model('Admin_Model');
		$teacher = $_POST['teacher'];
		//echo($subject);
		//die;
		
		$data['batch']= $this->Admin_Model->getallbatches($teacher);
		//$data['quiz_subject_name']= $this->Admin_Model->quizsubjectname();
		//$this->data['result'] = $this->Admin_Model->savenewpack();
		//print_r($data['quiz_package_link']);	
		echo json_encode($data['batch']);		
	}
	//getgeneralfeedbackbatch
	
		public function getgeneralfeedbackbatch()
	{
		//$this->main_menu();	
		$this->load->model('Admin_Model');
		$teacher = $_POST['teacher'];
		//echo($subject);
		//die;
		
		$data['batch']= $this->Admin_Model->getallgenbatches($teacher);
		//$data['quiz_subject_name']= $this->Admin_Model->quizsubjectname();
		//$this->data['result'] = $this->Admin_Model->savenewpack();
		//print_r($data['quiz_package_link']);	
		echo json_encode($data['batch']);		
	}
	
		public function getquiz()
	{
		//$this->main_menu();	
		$this->load->model('Admin_Model');
		$batch = $_POST['batch'];
		//echo($subject);
		//die;
		
		$data['quiz']= $this->Admin_Model->getallquizzes($batch);
		//$data['quiz_subject_name']= $this->Admin_Model->quizsubjectname();
		//$this->data['result'] = $this->Admin_Model->savenewpack();
		//print_r($data['quiz_package_link']);	
		echo json_encode($data['quiz']);		
	}
		
		
		  public function quiz_feedback_report_staff()
        {
			//$this->main_menu();
			//print_r($this->nativesession->get('name'));
			$staff_name = $this->nativesession->get('name');
			$this->load->model('Admin_Model');
		//	$this->data['quiz_assign_package']= $this->Admin_Model->findsummaryForquizlist();
			//print_r($this->data['quiz_assign_package']);

			
			$package_id = $this->input->post("package_id");
			//print_r($package_id);

			$this->data['quiz_details']= $this->Admin_Model->findAllquizforpackagestaff($staff_name);
			$this->data['batch_details']= $this->Admin_Model->getallbatchstaff($staff_name);
			$this->data['subject_details']= $this->Admin_Model->getallsubject();		
		    $this->data['teachers']=$this->Admin_Model->get_allteacherinfo();
			
			//print_r($this->data);
			
			$this->data['quiz_detail']= $this->Admin_Model->feedback_report_staff($staff_name);
			//$this->data['feedback_details']= $this->Admin_Model->getfeedbackdetails();
			$this->load->view('quiz_feedback_report_staff',$this->data);
        }


//show_feedback_report
	public function show_feedback_report()
{
    // Set the response header to application/json
    header('Content-Type: application/json');

    // Retrieve POST data
    $this->data['batch_name'] = $_POST['batch_name'];
    $this->data['quiz_id'] = $_POST['quiz_name'];

    // Load the model
    $this->load->model('Admin_Model');

    // Fetch the quiz feedback data
    $quizFeedbackData = $this->Admin_Model->feedback_report_admin($this->data['batch_name'], $this->data['quiz_id']);

    // Check if data is not empty and return as JSON
    if (!empty($quizFeedbackData)) {
        echo json_encode([
            'success' => true,
            'data' => $quizFeedbackData
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'No feedback found.'
        ]);
    }
}
		
		
	//	show_general_feedback_report
	
		public function show_general_feedback_report()
	{
		
		// Set the response header to application/json
		header('Content-Type: application/json');

		// Retrieve POST data
		$this->data['batch_name'] = $_POST['batch_name'];

		$this->data['teacher'] = $_POST['teacher'];

		// Load the model
		$this->load->model('Admin_Model');

		// Fetch the feedback data based on batch name and teacher
		$feedbackData = $this->Admin_Model->feedback_report_general($batch_name, $teacher);

		// Check if data is not empty and return as JSON
		if (!empty($feedbackData)) {
			echo json_encode([
				'success' => true,
				'data' => $feedbackData
			]);
		} else {
			echo json_encode([
				'success' => false,
				'message' => 'No feedback found.'
			]);
		}
	}
		public function getfeedbackcomments()
		{
			$quiz_id =$_GET['quiz_id'];
			$batch_name = $_GET['batch_name'];
			$teachers = $_GET['teachers'];
			
		   $this->load->model('Admin_Model');
           $this->data['comments'] = $this->Admin_Model->getfeedbackcomments($quiz_id,$batch_name,$teachers);
		 //s  print_r($this->data['comments']);
		   
		    $this->load->view('feedback_comments',$this->data);
			
		}


public function feedback_enable_disable()
		{
		
			    // $this->main_menu();
		   $this->load->model('Admin_Model');
		   $this->data['batch_details']= $this->Admin_Model->getallbatch();
          
		   
		    $this->load->view('feedback_enable_disable',$this->data);
			
		}

public function enable_disable_feedback()
{
	
	
	
	 //$this->main_menu();
		   $this->load->model('Admin_Model');
		   $rslt=$this->Admin_Model->enable_disable_feedback();
		   $this->data['batch_details']= $this->Admin_Model->getallbatch();
          
		   
		    $this->load->view('feedback_enable_disable',$this->data);
}

  public function general_feedback_reports()
        {
			//$this->main_menu();
			$this->load->model('Admin_Model');
			$this->data['subject_details']= $this->Admin_Model->findsubjectsforquiz();
			//print_r($this->data['quiz_assign_package']);

			//if($_POST['showvalues']==1){ 
			$package_id = $this->input->post("package_id");
			//print_r($package_id);

			//$this->data['quiz_details']= $this->Admin_Model->findAllquizforpackage($package_id);
			//$this->data['batch_details']= $this->Admin_Model->getallbatch();
			//$this->data['subject_details']= $this->Admin_Model->getallsubject();
		
			$this->data['teachers']=$this->Admin_Model->get_allgeneralteacherinfo();
			//print_r($this->data['teachers']);
		//	}
			//$this->data['feedback_details']= $this->Admin_Model->getfeedbackdetails();
			$this->load->view('General_feedback_reports',$this->data);
        }
		
		 public function general_feedback_reports_staff()
        {
			//$this->main_menu();
			$this->load->model('Admin_Model');
			$this->data['subject_details']= $this->Admin_Model->findsubjectsforquiz();
			//print_r($this->data['quiz_assign_package']);

			//if($_POST['showvalues']==1){ 
			$package_id = $this->input->post("package_id");
			//print_r($package_id);

			//$this->data['quiz_details']= $this->Admin_Model->findAllquizforpackage($package_id);
			//$this->data['batch_details']= $this->Admin_Model->getallbatch();
			//$this->data['subject_details']= $this->Admin_Model->getallsubject();
		
			$this->data['teachers']=$this->Admin_Model->get_allgeneralteacherinfo();
			//print_r($this->data['teachers']);
		//	}
			//$this->data['feedback_details']= $this->Admin_Model->getfeedbackdetails();
			$this->load->view('General_feedback_reports_staff',$this->data);
        }

public function new_feedback_reports()
	{
		//$this->main_menu();
		$this->load->model('Admin_Model');

		// Only fetch quiz and batch details
		$this->data['quiz_details'] = $this->Admin_Model->get_all_quizzes();
		$this->data['batch_details'] = $this->Admin_Model->get_all_batches();

		$this->load->view('new_feedback_report', $this->data);
	}
	public function get_batches_by_quiz()
	{
		$quiz_id = $this->input->post('quiz_id');
		$this->load->model('Admin_Model');
		$batches = $this->Admin_Model->get_batches_by_quiz_model($quiz_id);
		echo json_encode($batches);
	}

	public function get_quizzes_by_batch()
	{
		$batch_name = $this->input->post('batch_name');
		$this->load->model('Admin_Model');
		$quizzes = $this->Admin_Model->get_quizzes_by_batch_model($batch_name);
		echo json_encode($quizzes);
	}
		public function show_new_feedback_report()
{
    // Set the response header to application/json
    header('Content-Type: application/json');

    // Retrieve POST data
    $this->data['batch_name'] = $_POST['batch_name'];
    $this->data['quiz_id'] = $_POST['quiz_name'];

    // Load the model
    $this->load->model('Admin_Model');

    // Fetch the quiz feedback data
    $quizFeedbackData = $this->Admin_Model->feedback_new_report_admin($this->data['batch_name'], $this->data['quiz_id']);

    // Check if data is not empty and return as JSON
    if (!empty($quizFeedbackData)) {
        echo json_encode([
            'success' => true,
            'data' => $quizFeedbackData
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'No feedback found.'
        ]);
    }
}

public function delete_offline_package()
	{
		//$this->main_menu();
		$this->data['packages'] = $this->Admin_Model->getofflinepackagedelete();
		$this->load->view('delete_offline_package', $this->data);
	}

	
	public function delete_offline_packages()
	{
		$id = $this->input->post('id');

		if ($id) {
			$this->db->where('id', $id);
			$deleted = $this->db->delete('offline_assigned_package');

			if ($deleted) {
				echo json_encode(['status' => 'success']);
			} else {
				echo json_encode(['status' => 'error', 'message' => 'Delete failed']);
			}
		} else {
			echo json_encode(['status' => 'error', 'message' => 'Missing ID']);
		}
	}

	public function get_offline_package_details()
	{
		$package_id = $this->input->post('package_id');

		if ($package_id) {
			$this->db->where('package_id', $package_id);
			// $this->db->where('status', 'Unpaid');
			$query = $this->db->get('offline_assigned_package');

			$data = $query->result_array();

			if (!empty($data)) {
				echo json_encode(['status' => 'success', 'data' => $data]);
			} else {
				echo json_encode(['status' => 'error', 'message' => 'No data found']);
			}
		} else {
			echo json_encode(['status' => 'error', 'message' => 'Invalid package ID']);
		}
	}


	
	public function upload_csv()
	{
		$this->main_menu();
		$this->load->view('upload_csv_offline_package');
	}


	public function import_csv()
	{
		$this->load->model('admin_model');

		if (!empty($_FILES['csv_file']['name'])) {
			$filename = $_FILES['csv_file']['tmp_name'];

			if (($handle = fopen($filename, "r")) !== FALSE) {
				fgetcsv($handle);

				$batchData = [];

				while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
					if (count(array_filter($data)) == 0) continue;

					$user_name  = isset($data[0]) ? trim($data[0]) : null;
					$package_id = isset($data[1]) ? trim($data[1]) : null;

					if (!$user_name || !$package_id) continue;

					$batchData[] = [
						'user_name'     => $user_name,
						'package_id'    => $package_id,
						'status'        => 'unpaid',
						'creation_time' => date("Y-m-d H:i:s")
					];
				}

				fclose($handle);

				if (!empty($batchData)) {
					$this->admin_model->insert_batch_csv_data($batchData);
					echo "<script>
                    alert('✅ CSV uploaded successfully!');
                    window.location.href = '" . base_url('admin/upload_csv') . "';
                </script>";
					exit;
				} else {
					echo "<script>alert('❌ No valid rows to insert.'); window.history.back();</script>";
				}
			} else {
				echo "<script>alert('❌ Could not open the CSV file.'); window.history.back();</script>";
			}
		} else {
			echo "<script>alert('❌ No CSV file uploaded.'); window.history.back();</script>";
		}
	}
	
	public function get_package_details_by_username()
	{
		$username = $this->input->post('username');

		if (!$username) {
			echo json_encode(['status' => 'error', 'message' => 'Username is required']);
			return;
		}

		$this->db->where('user_name', $username);
		$query = $this->db->get('offline_assigned_package');

		if ($query->num_rows() > 0) {
			echo json_encode(['status' => 'success', 'data' => $query->result_array()]);
		} else {
			echo json_encode(['status' => 'error', 'message' => 'No records found.']);
		}
	}
	
	}
?>