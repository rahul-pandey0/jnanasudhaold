<?php
class Admin_Model extends CI_Model 
{
	protected $table="quiz_info";
    public function __construct(){
	   parent::__construct();
	   require_once APPPATH . 'helpers/database_helper.php';
	   
   }
	 
  
   public function findAllForSummaryPage() 
	{   
		$userQry = "SELECT * FROM user_details";
		
		// if ( $_SESSION['usr_role'] != SA ) {
			// $userQry = $userQry . " and stud.orgn_id = $this->orgn_id and stud.inst_id = $this->inst_id ";
		// }
		// $userQry = $userQry . " order by stud.id desc";
		$userRslt = $this->db->query($userQry);
		$rslt = $userRslt->result_array();
		if ( sizeof($rslt) > 0 ) {
			log_message('info', 'Successfully retrieved user_list!');
		} else {
			log_message('info', 'No user_list found!');
		}
		return $rslt;
	}
	
	/*public function findActiveForSummaryPage() 
	{   
		$userQry = "SELECT rollno,batch,accommodation,jnanasudhastandard,user_name,actual_password,first_name,last_name,phone,email,creation_date,role_id,user_status,aadhar_no,address,state,city,pincode,college_name,standard FROM user_details where user_status = '1' and otp_confirmed='Y' limit 5000 ";
		
		$userResult = $this->db->query($userQry);
		$rslt = $userResult->result_array();
		if ( sizeof($rslt) > 0 ) {
			log_message('info', 'Successfully retrieved user_list!');
		} else {
			log_message('info', 'No user_list found!');
		}
		return $rslt;
	}*/
	public function findActiveForSummaryPage()
{
    $userQry = "SELECT rollno,batch,accommodation,jnanasudhastandard,user_name,actual_password,first_name,last_name,phone,email,creation_date,role_id,user_status,aadhar_no,address,state,city,pincode,college_name,standard 
                FROM user_details 
                WHERE user_status = '1' AND otp_confirmed = 'Y' 
                LIMIT 5000";
    $userResult = db_query($userQry);
    $rslt = db_result($userResult); // <-- use helper to get array

    if (sizeof($rslt) > 0) {
        log_message('info', 'Successfully retrieved user_list!');
    } else {
        log_message('info', 'No user_list found!');
    }
    return $rslt;
}


	
	public function findentranceuser() 
	{   
		$userQry = "select * from entranceexam a, user_details  b where a.mobile_no collate utf8_general_ci = b.user_name";
		
		$userResult = $this->db->query($userQry);
		$rslt = $userResult->result_array();
		if ( sizeof($rslt) > 0 ) {
			log_message('info', 'Successfully retrieved user_list!');
		} else {
			log_message('info', 'No user_list found!');
		}
		return $rslt;
	}
	
	public function getrollid() 
	{
		$sql="SELECT DISTINCT role_id from user_details";
		$query = $this->db->query($sql);

		if ($query->num_rows() == 1)
		{
			return $query->result_array();
		}
		elseif ($query->num_rows() > 1)
		{
			return $query->result_array(); //This returns an array of results which you can whatever you need with it
		}
		else 
		{
			return $query->result_array();
		}
	}

	public function getpgstatus()
	{
		$sql="SELECT DISTINCT status from payment_gateway_status";
		$query = $this->db->query($sql);

		if ($query->num_rows() == 1)
		{
			return $query->result_array();
		}
		elseif ($query->num_rows() > 1)
		{
			return $query->result_array(); //This returns an array of results which you can whatever you need with it
		}
		else 
		{
			return $query->result_array();
		}
	}

	public function get_quizresult($cat)
	{
		$this->load->library('nativesession');
	    $sql = "select a.* ,concat(b.first_name,' ',b.last_name) name from student_quiz_result a,user_details b  where a.user_id collate utf8_general_ci = b.user_name collate utf8_general_ci and   a.quiz_id ='$cat'";
	    //print_r($sql);
	    $query=$this->db->query($sql);
	    try{
			if ($query->num_rows() > 0)
				{
					return $query->result_array();
				}else{
					return $query->result_array();
				}

	  		}catch (exception $e)
	 	 {echo $e->getMessage();
	 	 }
	}

	/*public function get_allquiz_subject()
  	{
		$sql="SELECT * FROM quiz_subject";
		$query=$this->db->query($sql);
		return $query->result_array();
   	}*/
	public function get_allquiz_subject()
{
    $sql = "SELECT * FROM quiz_subject";
    $query = db_query($sql);   // returns mysqli_result

    if (!$query) {
        return [];
    }
    return $query->fetch_all(MYSQLI_ASSOC);
}


	
	public function save_question($que,$op1,$op2,$op3,$op4,$dis)
   {
		$subject_name=$this->input->post('subject_name');
		$category=$this->input->post('category');
		$question_name=stripslashes($this->input->post('question_name'));
		$question_name=str_replace("<p>"," ",$question_name);
		$question_name=str_replace("</p>"," ",$question_name);
		if($que==""){}else{
		$question_name=$question_name.'<img src="'.$que.'" height="100px" width="100px"></img>';}
		$ans1=stripslashes($this->input->post('option1'));
		$ans1=str_replace("<p>"," ",$ans1);
		$ans1=str_replace("</p>"," ",$ans1);
		if($op1==""){}else{
		$ans1=$ans1.'<img src="'.$op1.'" height="100px" width="100px"></img>';}
		$ans2=stripslashes($this->input->post('option2'));
		$ans2=str_replace("<p>"," ",$ans2);
		$ans2=str_replace("</p>"," ",$ans2);
			if($op2==""){}else{
		$ans2=$ans2.'<img src="'.$op2.'" height="100px" width="100px"></img>';}
		$ans3=stripslashes($this->input->post('option3'));
		$ans3=str_replace("<p>"," ",$ans3);
		$ans3=str_replace("</p>"," ",$ans3);
			if($op3==""){}else{
		$ans3=$ans3.'<img src="'.$op3.'" height="100px" width="100px"></img>';}
		$ans4=stripslashes($this->input->post('option4'));
		$ans4=str_replace("<p>"," ",$ans4);
		$ans4=str_replace("</p>"," ",$ans4);
			if($op4==""){}else{
			$ans4=$ans4.'<img src="'.$op4.'" height="100px" width="100px"></img>';}
		$correct_ans=$this->input->post('correctoption');
		$marks1=$this->input->post('marks1');	
		$penalty=$this->input->post('penalty');
		$discription=stripslashes($this->input->post('dis')); 	
			$discription=str_replace("<p>"," ",$discription);
		$discription=str_replace("</p>"," ",$discription);
		$level=$this->input->post('level');
		$sql="INSERT INTO `quiz_question`(`id`, `discription`, `correctoption`, `category`, `question_name`, `mark`, `penalty`,`level`) VALUES
		('','$discription','$correct_ans','$category','$question_name','$marks1','$penalty','$level')";
	   $this->db->query($sql);
	   $id=$this->db->insert_id();
	  $a=$b=$c=$d=$penalty;
		   switch($correct_ans)
		   {
		   case 'A':
		   $a=$marks1;
		   break;
			case 'B':
			$b=$marks1;
		   break;
		   case 'C':
		   $c=$marks1;
		   break;
		   case 'D':
		   $d=$marks1;
		   break;
		   }
		$sql="INSERT INTO `quiz_question_answers`(`id`, `Qustion_no`,`question_answer`,`fraction`)VALUES 
		 ('','$id','$ans1','$a')";
		$this->db->query($sql);
		$sql="INSERT INTO `quiz_question_answers`(`id`, `Qustion_no`,`question_answer`,`fraction`)VALUES 
		 ('','$id','$ans2','$b')";
		$this->db->query($sql);
		$sql="INSERT INTO `quiz_question_answers`(`id`, `Qustion_no`,`question_answer`,`fraction`)VALUES 
		 ('','$id','$ans3','$c')";
		$this->db->query($sql);
		$sql="INSERT INTO `quiz_question_answers`(`id`, `Qustion_no`,`question_answer`,`fraction`)VALUES 
		 ('','$id','$ans4','$d')";
		$this->db->query($sql);
	}
	
	public function save_excel_question($que,$op1,$op2,$op3,$op4,$dis,$correct,$dif,$mar,$pen)
   {
			$subject_name=$this->input->post('subject_name');
			$category=$this->input->post('category');
			$question_name=$que;
			$ans1=$op1;
			$ans2=$op2;
			$ans3=$op3;
				$ans4=$op4;
			$correct_ans=$correct;
			$marks1=$mar;	
			$penalty=$pen;
			$discription=$dis;
			$sql="INSERT INTO `quiz_question`(`id`, `discription`, `correctoption`, `category`, `question_name`, `mark`, `penalty`,`level`) VALUES
			('','$discription','$correct_ans','$category','$question_name','$marks1','$penalty','$dif')";
			$this->db->query($sql);
			$id=$this->db->insert_id();
			$a=$b=$c=$d=$penalty;
			   switch($correct_ans)
			   {
			   case 'A':
			   $a=$marks1;
			   break;
				case 'B':
				$b=$marks1;
			   break;
			   case 'C':
			   $c=$marks1;
			   break;
			   case 'D':
			   $d=$marks1;
			   break;
			   }
		$sql="INSERT INTO `quiz_question_answers`(`id`, `Qustion_no`,`question_answer`,`fraction`)VALUES 
		 ('','$id','$ans1','$a')";
		$this->db->query($sql);
		$sql="INSERT INTO `quiz_question_answers`(`id`, `Qustion_no`,`question_answer`,`fraction`)VALUES 
		 ('','$id','$ans2','$b')";
		$this->db->query($sql);
		$sql="INSERT INTO `quiz_question_answers`(`id`, `Qustion_no`,`question_answer`,`fraction`)VALUES 
		 ('','$id','$ans3','$c')";
		$this->db->query($sql);
		$sql="INSERT INTO `quiz_question_answers`(`id`, `Qustion_no`,`question_answer`,`fraction`)VALUES 
		 ('','$id','$ans4','$d')";
		$this->db->query($sql);
	}
   
	public function save_studentinfo()
	{
		//$this->load->library('nativesession');
			//$sub_count=$this->nativesession->get('student_name');
		$name=$this->input->post('student_name');
		$Father_name=$this->input->post('father_name');
		$Email=$this->input->post('email');
		$phone_no=$this->input->post('phno');
		$rollno=$this->input->post('rollno');
		$subject=$this->input->post('subject');
		$pass=md5($this->input->post('pass'));
		$apass=$this->input->post('pass');
		$user_name=$this->input->post('username');
		$class_code=$this->input->post('class_code');
		$status_1=$this->input->post('status_1');
		 date_default_timezone_set('Asia/Kolkata');
		 $date = date('Y-m-d H:i:s');
		$this->load->library('nativesession');
		$org_id=$this->nativesession->get('org_id');
		 
 
		$sql="INSERT INTO `quiz_student`(`org_id`, `name`, `Father_name`, `Email`, `phone_no`, `user_name`, `role_no`, `subject_info`,`subject`,`class_code`,`status`) VALUES 
		('$org_id','$name','$Father_name','$Email','$phone_no','$user_name','$rollno','','$subject','$class_code','$status_1')";
		$query=$this->db->query($sql);
		//print_r($sql);
		$sql="INSERT INTO `user_details`(`org_id`, `user_name`, `password`, `first_name`, `last_name`, `role_id`, `user_status`, `actual_password`, `application`, `creation_date`, `modified_date`, `chapter_id`, `cluster_id`, `zone_id`, `VOLUNTEER_ID`) VALUES 
		('$org_id','$user_name','$pass','$name','','2',1,'$apass','','$date','$date','','','','')";
		$query=$this->db->query($sql);
		//print_r($sql);
		//add_teacher_info
	}
	
	public function save_teacherinfo()
	{
		$this->load->library('nativesession');
		$org_id=$this->nativesession->get('org_id');

		$name=$this->input->post('teacher_name');
		$Email=$this->input->post('email');
		$phone_no=$this->input->post('phno');
		//$rollno=$this->input->post('rollno');
		$pass=md5($this->input->post('pass'));
		$apass=$this->input->post('pass');
		$user_name=$this->input->post('username');
		$college_code=$this->input->post('college_code');
		$subject_name=$this->input->post('subject_name');
		date_default_timezone_set('Asia/Kolkata');
		$date = date('Y-m-d H:i:s');

		date_default_timezone_set('Asia/Kolkata');
		$date = date('Y-m-d H:i:s');
		 
		$sql="INSERT INTO `quiz_teacher`(`org_id`,`id`, `name`, `user_name`, `email`, `phone_no`, `subject_information`,college_code,subject_name) VALUES 
		('$org_id','','$name','$user_name','$Email','$phone_no','','$college_code','$subject_name')";
		$query=$this->db->query($sql);

		$sql="INSERT INTO `user_details`(`org_id`,`user_id`, `user_name`, `password`, `first_name`, `last_name`, `role_id`, `user_status`, `actual_password`, `application`, `creation_date`, `modified_date`, `chapter_id`, `cluster_id`,`phone`,`email`) VALUES 
		('$org_id','','$user_name','$pass','$name','','3','1','$apass','','$date','$date','','','$phone_no','$Email')";
		$query=$this->db->query($sql);
	}
	
	public function update_usersubject($sub,$user)
	{
		$sql="UPDATE `quiz_student` SET `subject_info`='$sub'
		WHERE user_name='$user'";
		$query=$this->db->query($sql);
	}	
	
	public function get_classcode_quiz()
	{
		$this->load->library('nativesession');
		$org_id=$this->nativesession->get('org_id');
		$sql="SELECT distinct class_code FROM quiz_student where org_id='$org_id'";
		$query=$this->db->query($sql);	
		return $query->result_array();
	}
	
	public function get_report()
	{
	    $sql="SELECT a.subject,a.category,count(b.category)
		FROM quiz_category a,quiz_question b
		WHERE a.id = b.category
		GROUP BY a.Subject_id,a.Unit_id ORDER BY a.Subject_id";
		$query=$this->db->query($sql);
		return $query->result_array();
	}
   
	/*public function get_static_quiz()
	{
		$sql="SELECT * FROM `quiz_info`";
		$query=$this->db->query($sql);	
		if($query->num_rows()>0)
		   {
				return $query->result_array();
		   }
	}*/
	public function get_static_quiz()
{
    $sql = "SELECT * FROM `quiz_info`";
    $query = db_query($sql);

    if ($query->num_rows() > 0) {
        return $query->result_array();
    }
    return null; 
}

	public function get_static_quizdetails(){
		$sql = "SELECT DISTINCT quiztype FROM `quiz_info`";
        $query = $this->db->query($sql);
		//print_r($query);
		if($query->num_rows()>0)
		   {
				return $query->result_array();
		   }
	}
	public function quiztypeget($quiztype)
	{
		$sql="SELECT * from `quiz_info` where quiztype='$quiztype'";
		$query=$this->db->query($sql);	
			if($query->num_rows()>0)
			   {
					return $query->result_array();
			   }
	}
	public function get_dynamic_quiz()
    {
		$sql="SELECT * FROM `quiz_dynamic`";
		$query=$this->db->query($sql);	
		if($query->num_rows() > 0)
				{
			  return $query->result_array();
			   }
	}
	
	public function save_quizDetails($result,$s_type)
	{
		if($s_type=="1")
		{
			for($i=0;$i<count($result);$i++)
			{
				$no=$result[$i]['id'];
				$val=$this->input->post('n'.$i);
				$sql="UPDATE `quiz_info` SET `no_of_attemps`='$val' WHERE id='$no'";
				$this->db->query($sql);	
			}
		}
		else
		{
			for($i=0;$i<count($result);$i++)
			{
				$no=$result[$i]['id'];
				$val=$this->input->post('n'.$i);
				$sql="UPDATE `quiz_dynamic` SET `no_of_attemps`='$val' WHERE id='$no'";
				$this->db->query($sql);	
			}
		}
	}
	
	public function chk_report_static_quiz($id)
    {
		$mixed="";
		$sql="SELECT * FROM quiz_mixed a,quiz_info b where a.Quiz_name=b.Quiz_name and b.id='$id'";
		$query=$this->db->query($sql);
			if ($query->num_rows() > 0)
            {
				$mixed=$query->result_array();
				if(is_array($mixed))
				{
					$array=array(1,2,3,4,5);
					$k = array_rand($array);
					$v = $array[$k];
					$sql="SELECT a.id,a.Quiz_name,a.subject_name,a.Time_limit,a.No_of_question,a.selected_Qustion FROM quiz_mixed a,quiz_info b where a.Quiz_name=b.Quiz_name and b.id='$id' and a.id='$v'";
					$query=$this->db->query($sql);
					$static=$query->result_array();
					$id=explode(",",$static[0]['selected_Qustion']);
					$query1="";
					$k=0;
					for($i=0;$i<count($id);$i++)
					{
						$sql1="SELECT a.id, a.question_name, b.question_answer FROM quiz_question a,quiz_question_answers b WHERE a.id = b.Qustion_no and a.id='$id[$i]'";
						$qurey1=$this->db->query($sql1); 	
						$result=$qurey1->result_array();
	
						for($j=0;$j<count($result);$j++)
						{
							if($k>0)
							$data1=$data1.",,,"; $k=1;
							$data1=$data1."".$result[$j]['id'];
							$data1=$data1."~,~,~".$result[$j]['question_name'];
							$data1=$data1."~,~,~".$result[$j++]['question_answer'];
							$data1=$data1."~,~,~".$result[$j++]['question_answer'];
							$data1=$data1."~,~,~".$result[$j++]['question_answer'];
							$data1=$data1."~,~,~".$result[$j]['question_answer'];
						}
	
					}	
					//print_r($data1);
					return $data1;
				}
			else
			{
			return "0";
			}
		}
			else
		{
			return "0";
		}
	} 
	
	public function report_static_quiz_result($id)
    {
		$result1="";
		$sql="SELECT * FROM `quiz_info` WHERE id='$id'"; 
		$query=$this->db->query($sql);	
		$static=$query->result_array();
		$id=explode(",",$static[0]['selected_Qustion']);
		$query1="";
		$k=0;
		$data1="";
		//	print_r($id);
			if (is_array($id))
			{
				$keys = array_keys($id);
				shuffle($keys);
				$random = array();
					foreach ($keys as $key)
					$random[$key] = $id[$key];
					$i=0;
				foreach ($keys as $key)
				{
					$id[$i]=$random[$key];
					$i++;
				}

			}
		//	print_r($id);

			for($i=0;$i<count($id);$i++)
			{
				$sql1="SELECT a.id, a.question_name,a.discription,b.question_answer
						FROM quiz_question a,quiz_question_answers b
						WHERE a.id = b.Qustion_no and a.id='$id[$i]' 
						and b.fraction=(SELECT max(`fraction`) FROM `quiz_question_answers` WHERE Qustion_no='$id[$i]')";	
				$qurey1=$this->db->query($sql1); 	
				$result=$qurey1->result_array();
	
				$sql="SELECT a.id, a.question_name, b.question_answer
					FROM quiz_question a,quiz_question_answers b
					WHERE a.id = b.Qustion_no and a.id='$id[$i]'";
				$qurey2=$this->db->query($sql); 
				$res=$qurey2->result_array();
				$ans_t="A";
	
				$i_m=-1;
					for($j=0;$j<count($result);$j++)
					{
						if($k>0)
						$data1=$data1.",,,"; $k=1;
						$data1=$data1."".$result[$j]['id'];
						$data1=$data1."~,~,~".$result[$j]['question_name'];
						$data1=$data1."~,~,~".$result[$j]['question_answer'];
						$data1=$data1."~,~,~".$result[$j]['question_answer'];
						$data1=$data1."~,~,~".$result[$j]['question_answer'];
						$data1=$data1."~,~,~".$result[$j]['question_answer'];
						$ans_t='';
						
						switch($result[$j]['question_answer'])
						{
							case $res[$i_m+1]['question_answer']:$ans_t="A";
							break;
							case $res[$i_m+2]['question_answer']:$ans_t="B";
							break;
							case $res[$i_m+3]['question_answer']:$ans_t="C";
							break;
							case $res[$i_m+4]['question_answer']:$ans_t="D";
							break;
						}
						$i_m=$i_m+4;
						$data1=$data1."~,~,~".$result[$j]['discription'];
						$data1=$data1."~,~,~".$ans_t;
					}
	
			}
	//print_r($data1);
	return $data1;
	}
	
	public function report_dynamic_quiz()
    {
		$id=$this->input->get('id');
		$sql="SELECT * FROM `quiz_dynamic` WHERE id='$id'";
		$query=$this->db->query($sql);	
		$dynamic=$query->result_array();
		return $dynamic;
	}
	
	public function get_allquedynamic_quiz_result($category,$leve1,$noofque)
    {
		$arr='';$k=0;
		$sql1="SELECT id FROM `quiz_question` WHERE level='$leve1' and category='$category'";
		$query=$this->db->query($sql1);
			if ($query->num_rows() > 0)
            {
				foreach ($query->result_array() as $row)
				{
					$rs=$row['id'];
					if($k==0)
					$arr=$rs;
				else
					$arr=$arr.",".$rs;
					$k=1;
		   
				}
			}
			//echo $arr;
			$a=explode(",",$arr);
			// print_r($a);
			$random_keys=array_rand($a,$noofque);
			$final='';
			// print_r($random_keys);
			if(is_array($random_keys))
			{
				for($i=0;$i<$noofque;$i++)
				$final[]=$a[$random_keys[$i]];
			}
			else
			{
				$final[]=$a[$random_keys];
			}
				$data1="";
				$k=0;
			for($i=0;$i<$noofque;$i++)
			{
				$sql1="SELECT a.id, a.question_name,a.discription,b.question_answer
				FROM quiz_question a,quiz_question_answers b
				WHERE a.id = b.Qustion_no and a.id='$final[$i]' 
				and b.fraction=(SELECT max(`fraction`) FROM `quiz_question_answers` WHERE Qustion_no='$final[$i]')";
		
				$qurey1=$this->db->query($sql1); 	
				$result=$qurey1->result_array();
				$sql="SELECT a.id, a.question_name,a.discription, b.question_answer
						FROM quiz_question a,quiz_question_answers b
						WHERE a.id = b.Qustion_no and a.id='$final[$i]'";
				$qurey2=$this->db->query($sql); 
				$res=$qurey2->result_array();
				$ans_t="A";
				$i_m=-1;
				
				for($j=0;$j<count($result);$j++)
				{
					if($k>0)
						$data1=$data1.",,,"; $k=1;
						$data1=$data1."".$result[$j]['id'];
						$data1=$data1."~,~,~".$result[$j]['question_name'];
						$data1=$data1."~,~,~".$result[$j]['question_answer'];
						$data1=$data1."~,~,~".$result[$j]['question_answer'];
						$data1=$data1."~,~,~".$result[$j]['question_answer'];
						$data1=$data1."~,~,~".$result[$j]['question_answer'];
						$ans_t="";
						switch($result[$j]['question_answer'])
						{
							case $res[$i_m+1]['question_answer']:$ans_t="A";
							break;
							case $res[$i_m+2]['question_answer']:$ans_t="B";
							break;
							case $res[$i_m+3]['question_answer']:$ans_t="C";
							break;
							case $res[$i_m+4]['question_answer']:$ans_t="D";
							break;
						}
						$i_m=$i_m+4;
						$data1=$data1."~,~,~".$result[$j]['discription'];
						$data1=$data1."~,~,~".$ans_t;
		
				}
	
			}
	return $data1;
	}
   
	 public function get_allquiz_category()
	{
		$sql="DROP TABLE IF EXISTS temp_cat";
		$query=$this->db->query($sql);
			$sql="CREATE TABLE temp_cat
			AS SELECT a.id,a.Subject_id,a.Unit_id,a.subject,concat(a.category,'(',ifnull(count(b.category),0),')') category,a.discription
			FROM quiz_category a inner join quiz_question b on a.id=b.category group by a.id 
			union
			select a.id,a.Subject_id,a.Unit_id,a.subject,concat(a.category,'(0)') category,a.discription
			FROM quiz_category a where a.id not in(select distinct category from quiz_question)";
		$query=$this->db->query($sql);
   
		$sql="SELECT * from temp_cat group by id";
		$query=$this->db->query($sql);
		return $query->result_array();
	}
	
	public function get_questionby_categoryandsubject1($subject_name,$category)
	{

		/*$sql="SELECT a.discription,a.correctoption,a.id, a.question_name, b.question_answer,a.qtype,a.correct_answer
        FROM quiz_question a,quiz_question_answers b
        WHERE a.id = b.Qustion_no and a.category='$category';";*/
		 $Qry = "select questions_list from quiz_category where category='$category'";
    	$resp = $this->db->query($Qry);
    	
    	
		$respRow = $resp->row();
		//print_r($respRow->questions_list);
		$qlist = $respRow->questions_list;
		
		$sql = "SET SESSION group_concat_max_len = 1000000";
        $query = $this->db->query($sql);
		$sql="SELECT a.question_name,a.discription,a.correctoption,a.id,
        SPLIT_STRING(group_concat(question_answer order by b.id separator '~'),'~',1) op_a ,
		SPLIT_STRING(group_concat(question_answer order by b.id separator '~'),'~',2) op_b ,
		SPLIT_STRING(group_concat(question_answer  order by b.id separator '~'),'~',3) op_c,
		SPLIT_STRING(group_concat(question_answer  order by b.id separator '~'),'~',4) op_d,
		a.qtype,a.correct_answer,a.grace
		FROM quiz_question a,quiz_question_answers b
		WHERE a.id = b.Qustion_no and a.id in ($qlist)
		group by a.discription,a.correctoption,a.id ORDER BY a.id;";
		print_r($sql);
		$qurey=$this->db->query($sql);
		//print_r($sql);
		return $qurey->result_array();
	}

	
	public function save_allSubject()
	{
	   $subject=$this->input->post('subject_name');
	   $discription=$this->input->post('description');
	   $sql=" INSERT INTO quiz_subject(id,subject,discription) VALUES
	   ('','$subject','$discription')";
	   $this->db->query($sql);
	}
   
	public function delete_question($id)
	{
		$sql="DELETE FROM `quiz_question` WHERE id='$id'";
		$this->db->query($sql);
		$sql="DELETE FROM `quiz_question_answers` WHERE Qustion_no='$id'";
		$this->db->query($sql);
	}
	
	public function save_allCategory($subject_id,$unit_id)
	{
	   $subject=$this->input->post('subject_name');
	   $category=$this->input->post('category_name');
	   $discription=$this->input->post('description');
	   $sql="INSERT INTO `quiz_category`(`id`,`Subject_id`,`Unit_id`,`subject`, `category`, `discription`) VALUES
	   ('','$subject_id','$unit_id','$subject','$category','$discription')";
	   $this->db->query($sql);
	}
	
	public function get_subjectidandunitid($rs) 
    {
		$sql="SELECT max(Unit_id) max FROM quiz_category WHERE subject='$rs'";
		$query=$this->db->query($sql);	
		if ($query->num_rows() > 0)
		{
			foreach ($query->result_array() as $row)
			{
			  $rs=$row['max'];
			 if($rs=$row['max']>0){ $rs=$row['max']; }else {$rs="o";}
			 return $rs; break; 
			}
		}
		else
		{
			return "o";
		}
	}
	
	public function get_subjectid($rs) 
    {
		$sql="SELECT Subject_id FROM quiz_subject WHERE subject='$rs'";
		$query=$this->db->query($sql);	
		if ($query->num_rows() > 0)
		{
			foreach ($query->result_array() as $row)
			{
			  $rs=$row['Subject_id'];
			 if($rs=$row['Subject_id']>0){ $rs=$row['Subject_id']; }else {$rs="o";}
			 return $rs; break; 
			}
		}
		else
		{
			 return "o";
		}
	}
	
	public function get_data($sql)
	{
		$query=$this->db->query($sql);
		if($query)
		{
			 if ($query->num_rows() > 0)
			{
			return $query->result_array();
			}
			else
			{
			return 0;
			}
		}
		else
		{
			return 0;
		}
	}
	
	public function get_subjectnamecategory_id($rs) 
    {
	    $sql="DROP TABLE IF EXISTS temp_cat";
		$query=$this->db->query($sql);
		/*$sql="CREATE TABLE temp_cat
			  AS SELECT id,Subject_id,Unit_id,subject,concat(category,'(',NULLIF((length(questions_list) - length(replace(questions_list, ',', '')) +1),''), ')')category,
				discription  
				FROM quiz_category";*/
				
				$sql="CREATE TABLE temp_cat
			  AS SELECT a.id,a.Subject_id,a.Unit_id,a.subject,a.category,a.discription FROM quiz_category a ";

		  /*SELECT a.id,a.Subject_id,a.Unit_id,a.subject,concat(a.category,'(',ifnull(count(b.category),0),')') category,a.discription
			FROM quiz_category a inner join quiz_question b on a.id=b.category and a.Subject_id='$rs' group by a.id 
			union
			select a.id,a.Subject_id,a.Unit_id,a.subject,concat(a.category,'(0)') category,a.discription
			FROM quiz_category a where a.id not in(select distinct category from quiz_question) and a.Subject_id='$rs' ";*/
		$query=$this->db->query($sql);
		//print_r($query);
	
		$sql="SELECT * from temp_cat where Subject_id='$rs' group by id";
		$query=$this->db->query($sql);
	  
		//	$sql="SELECT a.id,a.Subject_id,a.Unit_id,a.subject,concat(a.category,'(',ifnull(count(b.category),0),')') category,a.discription FROM quiz_category a inner join quiz_question b on a.id=b.category WHERE  group by a.id";
	
		//$query=$this->db->query($sql);	
		if($query->num_rows() > 0)
		{
			foreach ($query->result_array() as $row)
			{
				 if($rs=$row['id']>0){ $rs=$query->result_array(); }else {$rs="o";}
				 return $rs; break; 
			}
		}
		else
		{
			return "o";
		}
	}
	
	public function get_studentinfo()
	{
		$this->load->library('nativesession');
		$org_id=$this->nativesession->get('org_id');
		$sql="SELECT * FROM quiz_student a,user_details b WHERE a.org_id=b.org_id and a.user_name=b.user_name and a.org_id='$org_id'";
		$query=$this->db->query($sql);
		return $query->result_array();
	}
	
	public function chk_subjectname($rs) 
    {
		$sql="SELECT min(id) min FROM quiz_subject WHERE subject='$rs'";
		$query=$this->db->query($sql);	
		if ($query->num_rows() > 0)
				{
					foreach ($query->result_array() as $row)
					   {
						  $rs=$row['min'];
						 if($rs=$row['min']>0){ $rs=$row['min']; }else {$rs="o";}
						 return $rs; break; 
					   }
				}
				else
				{
					return "o";
				}
	}
	
	public function get_individualstudentinfos($student_name)
	{
		$sql="SELECT * FROM quiz_student a,user_details b WHERE a.user_name=b.user_name and a.user_name='$student_name'";
		$query=$this->db->query($sql);
		return $query->result_array();
	}
	
	public function get_teacherinfo()
	{
		$this->load->library('nativesession');
		$org_id=$this->nativesession->get('org_id');

		$sql="SELECT * FROM quiz_teacher a,user_details b WHERE a.org_id=b.org_id and a.user_name=b.user_name and a.org_id='$org_id'";
		$query=$this->db->query($sql);
		return $query->result_array();
	}
	
	
	public function get_allteacherinfo()
	{
		$this->load->library('nativesession');
		$org_id=$this->nativesession->get('org_id');

		$sql="SELECT * FROM quiz_teacher ";
		$query=$this->db->query($sql);
		return $query->result_array();
	}
	
	public function get_allgeneralteacherinfo()
	{
		$this->load->library('nativesession');
		$org_id=$this->nativesession->get('org_id');

		$sql="SELECT distinct(teacher) FROM student_general_feedback ";
		$query=$this->db->query($sql);
		return $query->result_array();
	}
	
	public function get_individualteacherinfo($name)
	{
		//$name=$this->input->post('name1');
		$sql="SELECT * FROM `quiz_teacher` WHERE class_code='$name'";
		$query=$this->db->query($sql);
		return $query->result_array();
	}
	
	public function save_searchinfo()
	{
		$data="";
		$i=0;
		$subject_name=$this->input->post('sub_ject');
		$type=$this->input->post('type');
		if(!empty($subject_name)){
			foreach($subject_name as $selected){
			if($i==0)
			$data=$data."".$selected."";
			else
			$data=$data.",".$selected."";
			$i=1;
			}
		}
		$student_name=$this->input->post('st');
		if(!empty($student_name)){
			foreach($student_name as $selected){
			if($type=="student")
			$sql="UPDATE quiz_student SET subject_info='$data' WHERE id='$selected'";
			else
			$sql="UPDATE quiz_teacher SET subject_information='$data' WHERE id='$selected'";
			$this->db->query($sql);
			}
		}
	}
	
	public function add_student_xl($name,$phone_no,$rollno,$class_code)
	{
		//$this->load->library('nativesession');
			//$sub_count=$this->nativesession->get('student_name');
		//$name=$this->input->post('student_name');
		$Father_name='';
		$Email='ganapathiskamathkarkala@gmail.com';
		$alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
			$pass = array(); //remember to declare $pass as an array
			$alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
			for ($i = 0; $i < 8; $i++) {
				$n = rand(0, $alphaLength);
				$pass[] = $alphabet[$n];
			}
			//$pass1=implode($pass);
			$pass1=$phone_no;
			$subject='science';
			$pass=md5($pass1);
			$apass=$pass1;
			$user_name=$rollno;
			date_default_timezone_set('Asia/Kolkata');
			$date = date('Y-m-d H:i:s');

		$sql="INSERT INTO `quiz_student`(`id`, `name`, `Father_name`, `Email`, `phone_no`, `user_name`, `role_no`, `subject_info`,`subject`,`class_code`) VALUES 
		('','$name','$Father_name','$Email','$phone_no','$user_name','$rollno','','$subject','$class_code')";
		$query=$this->db->query($sql);

		$sql="INSERT INTO `user_details`(`user_id`, `user_name`, `password`, `first_name`, `last_name`, `role_id`, `user_status`, `actual_password`, `application`, `creation_date`, `modified_date`, `chapter_id`, `cluster_id`, `zone_id`, `VOLUNTEER_ID`) VALUES 
		('','$user_name','$pass','$name','','2','','$apass','','$date','$date','','','','')";
		$query=$this->db->query($sql);
		//add_teacher_info
	}

	public function get_individualstudentinfo($name,$roll)
	{
		$this->load->library('nativesession');
		$user=$this->nativesession->get('username');
		$org_id=$this->nativesession->get('org_id');
		if($name=="")
		$sql="SELECT * FROM quiz_student a INNER JOIN user_details b ON a.user_name=b.user_name where a.role_no like '$roll%' and a.org_id='$org_id'";
		else if($roll=="")
		$sql="SELECT * FROM quiz_student a INNER JOIN user_details b ON a.user_name=b.user_name where a.class_code='$name' and a.org_id='$org_id'";
		else
		$sql="SELECT * FROM quiz_student a INNER JOIN user_details b ON a.user_name=b.user_name where a.class_code='$name' and a.role_no like '$roll%' and a.org_id='$org_id'";
		$query=$this->db->query($sql);
		return $query->result_array();
	}
	
	public function findByuserId($user_id) {
		$sql = "SELECT a.*,b.* from student_information a right outer join user_details b on a.user_name= b.user_name 
		where b.user_name = '$user_id'";
		//	print_r($sql);
		$query = $this->db->query($sql);
		if ($query->num_rows() == 1)
		{
			return $query->row();
		}
		elseif ($query->num_rows() > 1)
		{
		   return $query->result_array(); //This returns an array of results which you can whatever you need with it
		}
		else 
		{
			return $query->result_array();
		}
	}
	public function savestud_information($user_id) {
		//print_r($user_id);
		//print_r($_POST);
	$username=$this->input->post('user_name');
	$email=$this->input->post('email_id');
	$edu_board=$this->input->post('edu_board');
	$edu_medium=$this->input->post('edu_medium');
	$education= $this->input->post('education');
	$education_stream = $this->input->post('education_stream');
	$reg_attempt_sess = $this->input->post('reg_attempt_sess');
	$jnanasudha_student = $this->input->post('jnanasudha_student');
	$are_you_ntse_scholar = $this->input->post('are_you_ntse_scholar');
	
	$firstname = $this->input->post('firstname');
	$lastname = $this->input->post('lastname');
	$fathername =$this->input->post('fathername');
	$mothername=$this->input->post('mothername');

	$father_occu = $this->input->post('father_occu');
	$mother_occu = $this->input->post('mother_occu');
	$gender = $this->input->post('gender');
	$category = $this->input->post('category');

	$stud_day=$this->input->post('stud_day');
	$stud_month= $this->input->post('stud_month');
	$stud_year= $this->input->post('stud_year');
	$bg= $this->input->post('bg');
	$acn= $this->input->post('acn');
	
	$mphone= $this->input->post('mphone');
	$alt_mphone= $this->input->post('alt_mphone');
	
	$address= $this->input->post('address');
	$address2= $this->input->post('address2');
	
	$city= $this->input->post('city');
	$district= $this->input->post('district');
	$pincode= $this->input->post('pincode');
	$country= $this->input->post('country');
	$state= $this->input->post('state');
  
	$id=$this->db->replace('student_information',array(
	'user_name'=>$username,
	'email'=>$email,
	'education_board'=>$edu_board,
	'education_medium'=>$edu_medium,
	'education'=>$education,
	'education_stream'=>$education_stream,
	'reg_attempt_sess'=>$reg_attempt_sess,
	'jnanasudha_student' =>$jnanasudha_student,
	'are_you_ntse_scholar'=>$are_you_ntse_scholar,

	'first_name'=>$firstname,
	'last_name'=>$lastname,
	'fathername'=>$fathername,
	'mothername'=>$mothername,

	'father_occupation'=>$father_occu,
	'mother_occupation'=>$mother_occu,
	'gender'=>$gender,
	'category'=>$category,

	'stud_birthday'=>$stud_day,
	'stud_birthmonth'=>$stud_month,
	'stud_birthyear'=>$stud_year,
	'blood_grp'=>$bg,
	'aadhar_no'=>$acn,

	'mphone'=>$mphone,
	'alt_mphone'=>$alt_mphone,

	'address'=>$address,
	'address2'=>$address2,

	'city'=>$city,
	'district'=>$district,
	'pincode'=>$pincode,
	'country'=>$country,
	'state'=>$state,
	));

	$sql="update user_details set first_name ='$firstname',last_name ='$lastname',
	email='$email',aadhar_no='$acn',address='$address',state='$state',city='$city',pincode='$pincode'
	where user_id = '$user_id' and user_name='$username'";
		//	print_r($sql);
			$query = $this->db->query($sql);
//print_r($id);
return $id;		
	}

	public function findAllForquizdetails($package_id)
	{
		$sql="SELECT a.*,'$package_id' package_id FROM quiz_info a";
		$query = $this->db->query($sql);

		if ($query->num_rows() == 1)
		{
		return $query->result_array();
		}
		elseif ($query->num_rows() > 1)
		{
		return $query->result_array(); //This returns an array of results which you can whatever you need with it
		}
		else
		{
		return $query->result_array();
		}
	}
	
	
	
	public function findsubjectsforquiz($package_id)
	{
		$sql="SELECT distinct subject_name from student_feedback_params";
		$query = $this->db->query($sql);

		if ($query->num_rows() == 1)
		{
		return $query->result_array();
		}
		elseif ($query->num_rows() > 1)
		{
		return $query->result_array(); //This returns an array of results which you can whatever you need with it
		}
		else
		{
		return $query->result_array();
		}
	}
	
	public function getallteacher($subject)
	{
		$sql="SELECT distinct teachers from student_feedback_params where subject_name='$subject'";
		$query = $this->db->query($sql);

		if ($query->num_rows() == 1)
		{
		return $query->result_array();
		}
		elseif ($query->num_rows() > 1)
		{
		return $query->result_array(); //This returns an array of results which you can whatever you need with it
		}
		else
		{
		return $query->result_array();
		}
	}
	
	
	/*public function getallbatches($teacher)
	{
		$sql="SELECT distinct batch_name from student_feedback_params where teachers='$teacher'";
		$query = $this->db->query($sql);

		if ($query->num_rows() == 1)
		{
		return $query->result_array();
		}
		elseif ($query->num_rows() > 1)
		{
		return $query->result_array(); //This returns an array of results which you can whatever you need with it
		}
		else
		{
		return $query->result_array();
		}
	}*/
	//getallgenbatchesgf
	public function getallbatches($teacher)
{
    $sql = "SELECT DISTINCT batch_name FROM student_feedback_params WHERE teachers = ?";
    $query = db_query($sql, array($teacher));

    $num = $query->num_rows();

    if ($num == 1) {
        return $query->result_array();
    } elseif ($num > 1) {
        return $query->result_array();
    } else {
        return $query->result_array();
    }
}

	
	public function getallgenbatchesgf($teacher)
	{
		$sql="SELECT distinct(case when instr(a.batch,'(') > 0 then substring(a.batch,1,instr(a.batch,'(')-1)
        else a.batch end) batch from student_general_feedback a ,user_details b where b.user_name='$teacher'  and a.teacher collate utf8_general_ci = replace(b.first_name,' ','_')";
		//print_r($sql);
		$query = $this->db->query($sql);

		if ($query->num_rows() == 1)
		{
		return $query->result_array();
		}
		elseif ($query->num_rows() > 1)
		{
		return $query->result_array(); //This returns an array of results which you can whatever you need with it
		}
		else
		{
		return $query->result_array();
		}
	}
	
	public function getallgenbatches($teacher)
	{
		$sql="SELECT distinct batch from student_general_feedback where teacher='$teacher'";
		//print_r($sql);
		$query = $this->db->query($sql);

		if ($query->num_rows() == 1)
		{
		return $query->result_array();
		}
		elseif ($query->num_rows() > 1)
		{
		return $query->result_array(); //This returns an array of results which you can whatever you need with it
		}
		else
		{
		return $query->result_array();
		}
	}
	
		public function getallquizzes($batch)
	{
		//$sql="SELECT distinct a.quiz_id,b.quiz_name FROM student_feedback_details a,quiz_info b where a.quiz_id=b.id and batch_name= '$batch'";
		
		$sql="SELECT distinct b.id quiz_id, b.Quiz_name quiz_name FROM quiz_info b ";
		$query = $this->db->query($sql);

		if ($query->num_rows() == 1)
		{
		return $query->result_array();
		}
		elseif ($query->num_rows() > 1)
		{
		return $query->result_array(); //This returns an array of results which you can whatever you need with it
		}
		else
		{
		return $query->result_array();
		}
	}
	
	/*public function getsubjectmathquiz()
	{
		$sql="SELECT * from quiz_info  where quiztype='SUBJECTMATH'";
		$query = $this->db->query($sql);

		if ($query->num_rows() == 1)
		{
		return $query->result_array();
		}
		elseif ($query->num_rows() > 1)
		{
		return $query->result_array(); //This returns an array of results which you can whatever you need with it
		}
		else
		{
		return $query->result_array();
		}
	}*/
	public function getsubjectmathquiz()
{
    $sql = "SELECT * FROM quiz_info WHERE quiztype = 'SUBJECTMATH'";
    $query = db_query($sql);

    if ($query->num_rows() > 0) {
        return $query->result_array();
    } else {
        return []; // returns empty array if no records found
    }
}

	
	public function findAllquizforpackageadmin($package_id)
	{
		$sql="select distinct a.quiz_id id,b.quiz_name from quiz_package_link a,quiz_info b where a.quiz_id =b.id and a.package_id ='$package_id'";
		$query = $this->db->query($sql);
//print_r($sql);
//die; 
		if ($query->num_rows() == 1)
		{
		return $query->result_array();
		}
		elseif ($query->num_rows() > 1)
		{
		return $query->result_array(); //This returns an array of results which you can whatever you need with it
		}
		else
		{
		return $query->result_array();
		}
	}
	
	
	public function findAllquizforpackage($package_id)
	{
		$sql="select distinct a.quiz_id id,b.quiz_name from student_feedback_details a,quiz_info b where a.quiz_id =b.id";
		$query = $this->db->query($sql);

		if ($query->num_rows() == 1)
		{
		return $query->result_array();
		}
		elseif ($query->num_rows() > 1)
		{
		return $query->result_array(); //This returns an array of results which you can whatever you need with it
		}
		else
		{
		return $query->result_array();
		}
	}
	
	public function findAllquizforpackagestaff($teacher)
	{
		$sql="select distinct a.quiz_id id,b.quiz_name from student_feedback_details a,quiz_info b where a.quiz_id =b.id and teachers ='$teacher'";
		
		//print_r($sql);
	
		$query = $this->db->query($sql);

		if ($query->num_rows() == 1)
		{
		return $query->result_array();
		}
		elseif ($query->num_rows() > 1)
		{
		return $query->result_array(); //This returns an array of results which you can whatever you need with it
		}
		else
		{
		return $query->result_array();
		}
	}


	public function savefreesubgencoupon($id)
	{
		    //print_r($id);
			$coupon = $this->input->post('coupon');
			//print_r($coupon);
			$random = array('coupon_random_no' => $coupon);
			//print_r($random);
			$this->db->where('id', $id);
			$res=$this->db->update('free_subscription_login', $random);
			return $res;
	}
	
	public function checkcoupon($id)
	{
		   $sql="SELECT count(*) FROM free_subscription_login where id = '$id' ";
		$query = $this->db->query($sql);

		if ($query->num_rows() == 1)
		{
			return $query->result_array();
		}
		elseif ($query->num_rows() > 1)
		{
			return $query->result_array(); //This returns an array of results which you can whatever you need with it
		}
		else 
		{
			return $query->result_array();
		}
	}
	
	public function findAllForquizfreesubgencoupon($id) 
	{
		$sql="SELECT * FROM free_subscription_login where id = '$id'";
		$query = $this->db->query($sql);
		if ($query->num_rows() == 1)
		{
			return $query->result_array();
		}
		elseif ($query->num_rows() > 1)
		{
			return $query->result_array(); //This returns an array of results which you can whatever you need with it
		}
		else 
		{
			return $query->result_array();
		}
		
		return $res;
  
	}
	
	
	public function collectiondetail()
	{
		$paydate = $this->input->post('paydate');
		
		if (isset($paydate)) 
		{
			
			$sql="SELECT * FROM payment_gateway_status where datetime > '2019-05-27' and order_no > 0  ";
		}
		else 
		{
			$sql="SELECT * FROM payment_gateway_status where datetime > '2019-05-27' and order_no > 0 ";
		}
		//print_r($sql);
		$query = $this->db->query($sql);

		if ($query->num_rows() == 1)
		{
			return $query->result_array();
		}
		elseif ($query->num_rows() > 1)
		{
			return $query->result_array(); //This returns an array of results which you can whatever you need with it
		}
		else 
		{
			return $query->result_array();
		}
	}

	public function findById($id) 
	{
		$sql = "SELECT * FROM quiz_info where id = '$id'";
		//print_r($sql);
		$query = $this->db->query($sql);

		if ($query->num_rows() == 1)
		{
			return $query->row();
		}
		elseif ($query->num_rows() > 1)
		{
			return $query->result_array(); //This returns an array of results which you can whatever you need with it
		}
		else 
		{
			return $query->result_array();
		}
	}

	public function deleteuser($username) {
		$sql = "DELETE  FROM user_details where USER_NAME = '$username'";
		
		$query = $this->db->query($sql);


		$sql = "DELETE  FROM quiz_student where USER_NAME = '$username'";
		
		$query = $this->db->query($sql);

		
		$sql = "DELETE  FROM subscription_details where USERNAME = '$username'";
		
		$query = $this->db->query($sql);
$sql = "DELETE  FROM student_information where user_name = '$username'";
		$query = $this->db->query($sql);
		return "Succes";
	}

	// public function findForquizediteddetails($id) {
		// $srtdate=$this->input->post('srtdate');
	// $enddate=$this->input->post('enddate');
	
	// $id=$this->db->insert('quiz_info',array(
		 // 'start_date'=>$srtdate,
         // 'end_date'=>$enddate,
         // ));
        // return $id;		
		// }
	public function findForquizediteddetails($id){
		$data = array(	
			'start_date' =>  $this->input->post('srtdate'),
			'end_date'  => $this->input->post('enddate'),
					);

		$this->db->where('id',$this->input->post('id'));
		$id=$this->db->update('quiz_info', $data); 

		$start_date = $this->input->post('srtdate');
		$quiz_id = $this->input->post('id');

		$sql="update quiz_package_link set start_date ='$start_date' where quiz_id ='$quiz_id' and package_id = 1 ";
				//	print_r($sql);
					$query = $this->db->query($sql);

		return $id;	

	}	
			public function findsummaryForquizlist() {
			$sql="SELECT DISTINCT package_name ,id FROM package_info";
		
			$query = $this->db->query($sql);
			//print_r($query);
			if ($query->num_rows() == 1)
			{
			return $query->result_array();
			}
			elseif ($query->num_rows() > 1)
			{
			return $query->result_array(); //This returns an array of results which you can whatever you need with it
			}
			else
			{
			return $query->result_array();
			}

			}
		public function getallranksingle($cat, $batch)
{
    $db = get_db();

    // Reset rank table
    $db->query("TRUNCATE TABLE quiz_rank");
    $db->query("SET @prev_value := NULL");
    $db->query("SET @rank := 0");

    if ($batch === 'all') {

        $insertSql = "
            INSERT INTO quiz_rank (user_id, quiz_id, total, rank)
            SELECT 
                user_id,
                quiz_id,
                (physicsmark + chemistrymark + biologymark) AS total,
                @rank := IF(
                    @prev_value = (physicsmark + chemistrymark + biologymark),
                    @rank,
                    @rank + 1
                ) AS rank,
                @prev_value := (physicsmark + chemistrymark + biologymark)
            FROM student_quiz_result
            WHERE quiz_id = ?
            ORDER BY
                (physicsmark + chemistrymark + biologymark) DESC
        ";

        $db->query($insertSql, [$cat]);
    }

    // Update rank back to main table
    $updateSql = "
        UPDATE student_quiz_result a
        JOIN quiz_rank b 
            ON a.user_id = b.user_id
            AND a.quiz_id = b.quiz_id
        SET a.rank = b.rank
        WHERE a.quiz_id = ?
    ";
    $db->query($updateSql, [$cat]);

    if ($batch === 'all') {

        $selectSql = "
            SELECT 
                a.*,
                (a.physicsmark + a.chemistrymark + a.biologymark) AS total,
                CONCAT(b.first_name,' ',b.last_name) AS name,
                b.rollno,
                b.batch,
                b.accommodation,
                (SELECT Quiz_name FROM quiz_info WHERE id = ?) AS Quiz_name
            FROM student_quiz_result a
            JOIN user_details b 
                ON a.user_id COLLATE utf8_general_ci
                = b.user_name COLLATE utf8_general_ci
            WHERE a.quiz_id = ?
            ORDER BY CAST(a.rank AS UNSIGNED)
        ";

        $query = $db->query($selectSql, [$cat, $cat]);
        return $query->result_array();
    }

    return [];
}

		
		
			/*	public function getallranksingle($cat,$batch)
    {
        $this->load->library('nativesession');

        $sql = "truncate table quiz_rank";
        $query = $this->db->query($sql);
        $sql = "SET @prev_value = NULL";
        $query = $this->db->query($sql);
        $sql = "SET @rank_count = 0";
        $query = $this->db->query($sql);
		if ($batch=='all'){

        $sql = "INSERT into  quiz_rank
				select user_id,quiz_id ,physicsmark+chemistrymark+biologymark, CASE
		WHEN @prev_value = physicsmark+chemistrymark+biologymark THEN @rank_count
		WHEN @prev_value := physicsmark+chemistrymark+biologymark THEN @rank_count := @rank_count + 1

		END AS rank
		FROM student_quiz_result where quiz_id ='$cat'
		ORDER BY physicsmark+chemistrymark+biologymark desc";
		
		}
		
        $query = $this->db->query($sql);

        $sql = "update student_quiz_result a inner join quiz_rank b
		on a.quiz_id = b.quiz_id
		set a.rank= b.rank
		where a.user_id = b.user_id
		and a.quiz_id = '$cat'";
        //   print_r($sql);
        $query = $this->db->query($sql);
		if ($batch=='all'){
				$sql = "SELECT a.*,(a.physicsmark+a.chemistrymark+a.biologymark) total ,concat(b.first_name,' ',b.last_name) name,b.rollno,b.batch,b.accommodation,
				(select Quiz_name from quiz_info where id= '$cat') as Quiz_name from student_quiz_result a,user_details b 
				where a.user_id collate utf8_general_ci = b.user_name collate utf8_general_ci and   a.quiz_id ='$cat'
					 ORDER BY CONVERT( a.rank, SIGNED INTEGER )";
		}


        //   print_r($sql);
        $query = $this->db->query($sql);
        try {
            if ($query->num_rows() > 0) {
                return $query->result_array();
            } else {
                return $query->result_array();
            }

        } catch (exception $e) {echo $e->getMessage();
        }

    }
	// */
	
	// 		public function getallrankonesub($cat,$batch)
    // {
    //     $this->load->library('nativesession');

    //     $sql = "truncate table quiz_rank";
    //     $query = $this->db->query($sql);
    //     $sql = "SET @prev_value = NULL";
    //     $query = $this->db->query($sql);
    //     $sql = "SET @rank_count = 0";
    //     $query = $this->db->query($sql);
	// 	if ($batch=='all'){

    //     $sql = "INSERT into  quiz_rank
	// 			select user_id,quiz_id ,physicsmark+chemistrymark+biologymark, CASE
	// 	WHEN @prev_value = physicsmark+chemistrymark+biologymark THEN @rank_count
	// 	WHEN @prev_value := physicsmark+chemistrymark+biologymark THEN @rank_count := @rank_count + 1

	// 	END AS rank
	// 	FROM student_quiz_result where quiz_id ='$cat'
	// 	ORDER BY physicsmark+chemistrymark+biologymark desc";
		
	// 	}
		
    //     $query = $this->db->query($sql);

    //     $sql = "update student_quiz_result a inner join quiz_rank b
	// 	on a.quiz_id = b.quiz_id
	// 	set a.rank= b.rank
	// 	where a.user_id = b.user_id
	// 	and a.quiz_id = '$cat'";
    //     //   print_r($sql);
    //     $query = $this->db->query($sql);
	// 	if ($batch=='all'){
	// 			$sql = "SELECT a.*,(a.physicsmark+a.chemistrymark+a.biologymark) total ,concat(b.first_name,' ',b.last_name) name,b.rollno,b.batch,b.accommodation,
	// 			(select Quiz_name from quiz_info where id= '$cat') as Quiz_name from student_quiz_result a,user_details b 
	// 			where a.user_id collate utf8_general_ci = b.user_name collate utf8_general_ci and   a.quiz_id ='$cat'
	// 				 ORDER BY CONVERT( a.rank, SIGNED INTEGER )";
	// 	}


    //     //    print_r($sql);
    //     $query = $this->db->query($sql);
    //     try {
    //         if ($query->num_rows() > 0) {
    //             return $query->result_array();
    //         } else {
    //             return $query->result_array();
    //         }

    //     } catch (exception $e) {echo $e->getMessage();
    //     }

    // }
	public function getallrankonesub($cat, $batch)
{
    // TRUNCATE TABLE
    $sql = "TRUNCATE TABLE quiz_rank";
    db_query($sql);

    // SET VARIABLES
    $sql = "SET @prev_value = NULL";
    db_query($sql);

    $sql = "SET @rank_count = 0";
    db_query($sql);

    if ($batch == 'all') {
        $sql = "INSERT INTO quiz_rank
                SELECT user_id, quiz_id,
                       physicsmark + chemistrymark + biologymark,
                       CASE
                           WHEN @prev_value = physicsmark + chemistrymark + biologymark THEN @rank_count
                           WHEN @prev_value := physicsmark + chemistrymark + biologymark
                           THEN @rank_count := @rank_count + 1
                       END AS rank
                FROM student_quiz_result
                WHERE quiz_id = '$cat'
                ORDER BY physicsmark + chemistrymark + biologymark DESC";
        db_query($sql);
    }

    // UPDATE RANK BACK TO MAIN TABLE
    $sql = "UPDATE student_quiz_result a
            INNER JOIN quiz_rank b ON a.quiz_id = b.quiz_id
            SET a.rank = b.rank
            WHERE a.user_id = b.user_id
              AND a.quiz_id = '$cat'";
    db_query($sql);

    if ($batch == 'all') {
        $sql = "SELECT a.*,
                       (a.physicsmark + a.chemistrymark + a.biologymark) total,
                       CONCAT(b.first_name, ' ', b.last_name) name,
                       b.rollno,
                       b.batch,
                       b.accommodation,
                       (SELECT Quiz_name FROM quiz_info WHERE id = '$cat') AS Quiz_name
                FROM student_quiz_result a, user_details b
                WHERE a.user_id COLLATE utf8_general_ci = b.user_name COLLATE utf8_general_ci
                  AND a.quiz_id = '$cat'
                ORDER BY CONVERT(a.rank, SIGNED INTEGER)";
    }

    // Run the SELECT query
    $query = db_query($sql);

    // Use native mysqli methods for result
    if ($query && $query->num_rows > 0) {
        return $query->fetch_all(MYSQLI_ASSOC);
    } else {
        return array();
    }
}

public function getallrankneet2021($cat, $batch)
{
    $db = get_db();

    // Reset the ranking table and variables
    $db->query("TRUNCATE TABLE quiz_rank");
    $db->query("SET @prev_value = NULL");
    $db->query("SET @rank_count = 0");

    // Build the base query
    $baseSum = "physicsmarkseca + physicsmarksecb + chemistrymarkseca + chemistrymarksecb + zoologymarkseca + zoologymarksecb + botanymarkseca + botanymarksecb";

   $sql = "
			INSERT INTO quiz_rank (user_id, quiz_id, total, rank)
			SELECT 
				user_id,
				quiz_id,
				total,
				rank
			FROM (
				SELECT 
					user_id,
					quiz_id,
					($baseSum) AS total,
					@rank := IF(@prev = ($baseSum), @rank, @rank + 1) AS rank,
					@prev := ($baseSum)
				FROM student_quiz_result_neet2021
				CROSS JOIN (SELECT @rank := 0, @prev := NULL) r
				WHERE quiz_id = '$cat'
			";

			if ($batch == 'JNANASUDHAIPU') {
				$sql .= " AND user_id COLLATE utf8_general_ci IN (
							SELECT USER_NAME COLLATE utf8_general_ci
							FROM user_details
							WHERE jnanasudhastandard = 'JNANASUDHAIPU'
						)";
			} elseif ($batch == 'JNANASUDHAIIPU') {
				$sql .= " AND user_id COLLATE utf8_general_ci IN (
							SELECT USER_NAME COLLATE utf8_general_ci
							FROM user_details
							WHERE jnanasudhastandard = 'JNANASUDHAIIPU'
						)";
			}

			$sql .= " ORDER BY total DESC
			) x";

    // Execute the ranking insert
    $db->query($sql);

    // Update the rank in the original table
    $db->query("UPDATE student_quiz_result_neet2021 a
                INNER JOIN quiz_rank b
                ON a.user_id = b.user_id AND a.quiz_id = b.quiz_id
                SET a.rank = b.rank
                WHERE a.quiz_id = '$cat'");

    // Return the ranked results
    $sqlSelect = "SELECT a.*, ($baseSum) AS total, CONCAT(b.first_name,' ',b.last_name) AS name, b.rollno, b.batch, b.accommodation, b.no_of_communication,
                  (SELECT Quiz_name FROM quiz_info WHERE id = '$cat') AS Quiz_name
                  FROM student_quiz_result_neet2021 a
                  INNER JOIN user_details b
                  ON a.user_id COLLATE utf8_general_ci = b.user_name COLLATE utf8_general_ci
                  WHERE a.quiz_id = '$cat'";

    if ($batch == 'JNANASUDHAIPU') {
        $sqlSelect .= " AND b.jnanasudhastandard = 'JNANASUDHAIPU'";
    } elseif ($batch == 'JNANASUDHAIIPU') {
        $sqlSelect .= " AND b.jnanasudhastandard = 'JNANASUDHAIIPU'";
    } elseif (str_starts_with($batch, 'PackageId')) {
        $packageId = str_replace('PackageId', '', $batch);
        $sqlSelect = "SELECT a.*, ($baseSum) AS total, CONCAT(b.first_name,' ',b.last_name) AS name, c.package_id, b.rollno, b.batch, b.accommodation, b.no_of_communication,
                      (SELECT Quiz_name FROM quiz_info WHERE id = '$cat') AS Quiz_name
                      FROM student_quiz_result_neet2021 a
                      INNER JOIN user_details b
                      ON a.user_id COLLATE utf8_general_ci = b.user_name COLLATE utf8_general_ci
                      INNER JOIN subscription_details c
                      ON c.username = a.user_id
                      WHERE a.quiz_id = '$cat' AND c.package_id = $packageId";
    }

    $sqlSelect .= " ORDER BY CONVERT(a.rank, SIGNED INTEGER)";

			$query = $db->query($sqlSelect);
		if (!$query) {
			return [];
		}
		$result = [];
		while ($row = $query->fetch_assoc()) {
			$result[] = $row;
		}
		return $result;

}

	/*
public function getallrankneet2021($cat,$batch)
    {
      //  $this->load->library('nativesession');
		$db=get_db();

        $sql = "truncate table quiz_rank";
        $query = $db->query($sql);
        $sql = "SET @prev_value = NULL";
        $query = $db->query($sql);
        $sql = "SET @rank_count = 0";
        $query = $db->query($sql);
		if ($batch=='all'){

        $sql = "INSERT into  quiz_rank
				select user_id,quiz_id ,physicsmarkseca+physicsmarksecb+chemistrymarkseca+chemistrymarksecb+zoologymarkseca+zoologymarksecb+botanymarkseca+botanymarksecb, CASE
		WHEN @prev_value = physicsmarkseca+physicsmarksecb+chemistrymarkseca+chemistrymarksecb+zoologymarkseca+zoologymarksecb+botanymarkseca+botanymarksecb THEN @rank_count
		WHEN @prev_value := physicsmarkseca+physicsmarksecb+chemistrymarkseca+chemistrymarksecb+zoologymarkseca+zoologymarksecb+botanymarkseca+botanymarksecb THEN @rank_count := @rank_count + 1

		END AS rank
		FROM student_quiz_result_neet2021 where quiz_id ='$cat'
		ORDER BY physicsmark+chemistrymark+biologymark desc";
		
		}
		if ($batch=='JNANASUDHAIPU'){

        $sql = "INSERT into  quiz_rank
				select user_id,quiz_id ,physicsmarkseca+physicsmarksecb+chemistrymarkseca+chemistrymarksecb+zoologymarkseca+zoologymarksecb+botanymarkseca+botanymarksecb, CASE
		WHEN @prev_value = physicsmarkseca+physicsmarksecb+chemistrymarkseca+chemistrymarksecb+zoologymarkseca+zoologymarksecb+botanymarkseca+botanymarksecb THEN @rank_count
		WHEN @prev_value := physicsmarkseca+physicsmarksecb+chemistrymarkseca+chemistrymarksecb+zoologymarkseca+zoologymarksecb+botanymarkseca+botanymarksecb THEN @rank_count := @rank_count + 1


		END AS rank
		FROM student_quiz_result_neet2021 where quiz_id ='$cat' and user_id collate utf8_general_ci in
        (select USER_NAME collate utf8_general_ci from user_details 
        where jnanasudhastandard = 'JNANASUDHAIPU')
		ORDER BY physicsmark+chemistrymark+biologymark desc";
		
		}
		if ($batch=='JNANASUDHAIIPU'){

        $sql = "INSERT into  quiz_rank
				select user_id,quiz_id ,physicsmarkseca+physicsmarksecb+chemistrymarkseca+chemistrymarksecb+zoologymarkseca+zoologymarksecb+botanymarkseca+botanymarksecb, CASE
		WHEN @prev_value = physicsmarkseca+physicsmarksecb+chemistrymarkseca+chemistrymarksecb+zoologymarkseca+zoologymarksecb+botanymarkseca+botanymarksecb THEN @rank_count
		WHEN @prev_value := physicsmarkseca+physicsmarksecb+chemistrymarkseca+chemistrymarksecb+zoologymarkseca+zoologymarksecb+botanymarkseca+botanymarksecb THEN @rank_count := @rank_count + 1


		END AS rank
		FROM student_quiz_result_neet2021 where quiz_id ='$cat' and user_id collate utf8_general_ci in
        (select USER_NAME collate utf8_general_ci from user_details 
        where jnanasudhastandard = 'JNANASUDHAIIPU')
		ORDER BY physicsmark+chemistrymark+biologymark desc";
		
		}
		 if ($batch=='PackageId7'){

        $sql = "INSERT into  quiz_rank
				select user_id,quiz_id ,physicsmarkseca+physicsmarksecb+chemistrymarkseca+chemistrymarksecb+zoologymarkseca+zoologymarksecb+botanymarkseca+botanymarksecb, CASE
		WHEN @prev_value = physicsmarkseca+physicsmarksecb+chemistrymarkseca+chemistrymarksecb+zoologymarkseca+zoologymarksecb+botanymarkseca+botanymarksecb THEN @rank_count
		WHEN @prev_value := physicsmarkseca+physicsmarksecb+chemistrymarkseca+chemistrymarksecb+zoologymarkseca+zoologymarksecb+botanymarkseca+botanymarksecb THEN @rank_count := @rank_count + 1


		END AS rank
		FROM student_quiz_result_neet2021 where quiz_id ='$cat' and user_id  in
        (select username  from subscription_details 
        where package_id = 7)
		ORDER BY physicsmark+chemistrymark+biologymark desc";
		
		}
		 if ($batch=='PackageId8'){

        $sql = "INSERT into  quiz_rank
				select user_id,quiz_id ,physicsmarkseca+physicsmarksecb+chemistrymarkseca+chemistrymarksecb+zoologymarkseca+zoologymarksecb+botanymarkseca+botanymarksecb, CASE
		WHEN @prev_value = physicsmarkseca+physicsmarksecb+chemistrymarkseca+chemistrymarksecb+zoologymarkseca+zoologymarksecb+botanymarkseca+botanymarksecb THEN @rank_count
		WHEN @prev_value := physicsmarkseca+physicsmarksecb+chemistrymarkseca+chemistrymarksecb+zoologymarkseca+zoologymarksecb+botanymarkseca+botanymarksecb THEN @rank_count := @rank_count + 1

		END AS rank
		FROM student_quiz_result_neet2021 where quiz_id ='$cat' and user_id  in
        (select username  from subscription_details 
        where package_id = 8)
		ORDER BY physicsmark+chemistrymark+biologymark desc";
		
		}
		
		if ($batch=='PackageId41'){

        $sql = "INSERT into  quiz_rank
				select user_id,quiz_id ,physicsmarkseca+physicsmarksecb+chemistrymarkseca+chemistrymarksecb+zoologymarkseca+zoologymarksecb+botanymarkseca+botanymarksecb, CASE
		WHEN @prev_value = physicsmarkseca+physicsmarksecb+chemistrymarkseca+chemistrymarksecb+zoologymarkseca+zoologymarksecb+botanymarkseca+botanymarksecb THEN @rank_count
		WHEN @prev_value := physicsmarkseca+physicsmarksecb+chemistrymarkseca+chemistrymarksecb+zoologymarkseca+zoologymarksecb+botanymarkseca+botanymarksecb THEN @rank_count := @rank_count + 1


		END AS rank
		FROM student_quiz_result_neet2021 where quiz_id ='$cat' and user_id  in
        (select username  from subscription_details 
        where package_id = 41)
		ORDER BY physicsmark+chemistrymark+biologymark desc";
		
		}
		
		if ($batch=='PackageId42'){

        $sql = "INSERT into  quiz_rank
				select user_id,quiz_id ,physicsmarkseca+physicsmarksecb+chemistrymarkseca+chemistrymarksecb+zoologymarkseca+zoologymarksecb+botanymarkseca+botanymarksecb, CASE
		WHEN @prev_value = physicsmarkseca+physicsmarksecb+chemistrymarkseca+chemistrymarksecb+zoologymarkseca+zoologymarksecb+botanymarkseca+botanymarksecb THEN @rank_count
		WHEN @prev_value := physicsmarkseca+physicsmarksecb+chemistrymarkseca+chemistrymarksecb+zoologymarkseca+zoologymarksecb+botanymarkseca+botanymarksecb THEN @rank_count := @rank_count + 1


		END AS rank
		FROM student_quiz_result_neet2021 where quiz_id ='$cat' and user_id  in
        (select username  from subscription_details 
        where package_id = 42)
		ORDER BY physicsmark+chemistrymark+biologymark desc";
		
		}
		if ($batch=='PackageId26'){

        $sql = "INSERT into  quiz_rank
				select user_id,quiz_id ,physicsmarkseca+physicsmarksecb+chemistrymarkseca+chemistrymarksecb+zoologymarkseca+zoologymarksecb+botanymarkseca+botanymarksecb, CASE
		WHEN @prev_value = physicsmarkseca+physicsmarksecb+chemistrymarkseca+chemistrymarksecb+zoologymarkseca+zoologymarksecb+botanymarkseca+botanymarksecb THEN @rank_count
		WHEN @prev_value := physicsmarkseca+physicsmarksecb+chemistrymarkseca+chemistrymarksecb+zoologymarkseca+zoologymarksecb+botanymarkseca+botanymarksecb THEN @rank_count := @rank_count + 1


		END AS rank
		FROM student_quiz_result_neet2021 where quiz_id ='$cat' and user_id  in
        (select username  from subscription_details 
        where package_id = 26)
		ORDER BY physicsmark+chemistrymark+biologymark desc";
		
		}
		
		if ($batch=='54'){

        $sql = "INSERT into  quiz_rank
				select user_id,quiz_id ,physicsmarkseca+physicsmarksecb+chemistrymarkseca+chemistrymarksecb+zoologymarkseca+zoologymarksecb+botanymarkseca+botanymarksecb, CASE
		WHEN @prev_value = physicsmarkseca+physicsmarksecb+chemistrymarkseca+chemistrymarksecb+zoologymarkseca+zoologymarksecb+botanymarkseca+botanymarksecb THEN @rank_count
		WHEN @prev_value := physicsmarkseca+physicsmarksecb+chemistrymarkseca+chemistrymarksecb+zoologymarkseca+zoologymarksecb+botanymarkseca+botanymarksecb THEN @rank_count := @rank_count + 1

		END AS rank
		FROM student_quiz_result_neet2021 where quiz_id ='$cat' and user_id  in
        (select username  from subscription_details 
        where package_id = 54)
		ORDER BY physicsmark+chemistrymark+biologymark desc";
		
		}
		//print_r($sql);
        $query = $this->db->query($sql);

        $sql = "update student_quiz_result_neet2021 a inner join quiz_rank b
		on a.quiz_id = b.quiz_id
		set a.rank= b.rank
		where a.user_id = b.user_id
		and a.quiz_id = '$cat'";
        //   print_r($sql);
        $query = $this->db->query($sql);
		if ($batch=='all'){
				$sql = "SELECT a.*,(physicsmarkseca+physicsmarksecb+chemistrymarkseca+chemistrymarksecb+zoologymarkseca+zoologymarksecb+botanymarkseca+botanymarksecb) total ,concat(b.first_name,' ',b.last_name) name,b.rollno,b.batch,b.accommodation,b.no_of_communication,
				(select Quiz_name from quiz_info where id= '$cat') as Quiz_name from student_quiz_result_neet2021 a,user_details b 
				where a.user_id collate utf8_general_ci = b.user_name collate utf8_general_ci and   a.quiz_id ='$cat'
					 ORDER BY CONVERT( a.rank, SIGNED INTEGER )";
		}
		if ($batch=='JNANASUDHAIPU'){
				$sql = "SELECT a.*,(physicsmarkseca+physicsmarksecb+chemistrymarkseca+chemistrymarksecb+zoologymarkseca+zoologymarksecb+botanymarkseca+botanymarksecb) total ,concat(b.first_name,' ',b.last_name) name,b.rollno,b.batch,b.accommodation,b.no_of_communication,
				(select Quiz_name from quiz_info where id= '$cat') as Quiz_name from student_quiz_result_neet2021 a,user_details b 
				where a.user_id collate utf8_general_ci = b.user_name collate utf8_general_ci and   a.quiz_id ='$cat' and b.jnanasudhastandard='JNANASUDHAIPU'
					 ORDER BY CONVERT( a.rank, SIGNED INTEGER )";
		}
		if ($batch=='JNANASUDHAIIPU'){
				$sql = "SELECT a.*,(physicsmarkseca+physicsmarksecb+chemistrymarkseca+chemistrymarksecb+zoologymarkseca+zoologymarksecb+botanymarkseca+botanymarksecb) total ,concat(b.first_name,' ',b.last_name) name,b.rollno,b.batch,b.accommodation,b.no_of_communication,
				(select Quiz_name from quiz_info where id= '$cat') as Quiz_name from student_quiz_result_neet2021 a,user_details b 
				where a.user_id collate utf8_general_ci = b.user_name collate utf8_general_ci and   a.quiz_id ='$cat' and b.jnanasudhastandard='JNANASUDHAIIPU'
					 ORDER BY CONVERT( a.rank, SIGNED INTEGER )";
		}

		if ($batch=='PackageId7'){
				$sql = "SELECT a.*,(physicsmarkseca+physicsmarksecb+chemistrymarkseca+chemistrymarksecb+zoologymarkseca+zoologymarksecb+botanymarkseca+botanymarksecb) total ,concat(b.first_name,' ',b.last_name) name, c.package_id,b.rollno,b.batch,b.accommodation,b.no_of_communication,
						(select Quiz_name from quiz_info where id= '$cat') as Quiz_name
						from student_quiz_result_neet2021 a,user_details b,subscription_details c
						where a.user_id collate utf8_general_ci = b.user_name collate utf8_general_ci
						and c.username  = a.user_id and a.quiz_id ='$cat' and c.package_id='7'
					 ORDER BY CONVERT( a.rank, SIGNED INTEGER )";
		}
		if ($batch=='PackageId8'){
				$sql = "SELECT a.*,(physicsmarkseca+physicsmarksecb+chemistrymarkseca+chemistrymarksecb+zoologymarkseca+zoologymarksecb+botanymarkseca+botanymarksecb) total ,concat(b.first_name,' ',b.last_name) name, c.package_id,b.rollno,b.batch,b.accommodation,b.no_of_communication,
						(select Quiz_name from quiz_info where id= '$cat') as Quiz_name
						from student_quiz_result_neet2021 a,user_details b,subscription_details c
						where a.user_id collate utf8_general_ci = b.user_name collate utf8_general_ci
						and c.username  = a.user_id and a.quiz_id ='$cat' and c.package_id='8'
					 ORDER BY CONVERT( a.rank, SIGNED INTEGER )";
		}
		if ($batch=='PackageId41'){
				$sql = "SELECT a.*,(physicsmarkseca+physicsmarksecb+chemistrymarkseca+chemistrymarksecb+zoologymarkseca+zoologymarksecb+botanymarkseca+botanymarksecb) total ,concat(b.first_name,' ',b.last_name) name, c.package_id,b.rollno,b.batch,b.accommodation,b.no_of_communication,
						(select Quiz_name from quiz_info where id= '$cat') as Quiz_name
						from student_quiz_result_neet2021 a,user_details b,subscription_details c
						where a.user_id collate utf8_general_ci = b.user_name collate utf8_general_ci
						and c.username  = a.user_id and a.quiz_id ='$cat' and c.package_id='41'
					 ORDER BY CONVERT( a.rank, SIGNED INTEGER )";
		}
		if ($batch=='PackageId42'){
				$sql = "SELECT a.*,(physicsmarkseca+physicsmarksecb+chemistrymarkseca+chemistrymarksecb+zoologymarkseca+zoologymarksecb+botanymarkseca+botanymarksecb) total ,concat(b.first_name,' ',b.last_name) name, c.package_id,b.rollno,b.batch,b.accommodation,b.no_of_communication
						(select Quiz_name from quiz_info where id= '$cat') as Quiz_name
						from student_quiz_result_neet2021 a,user_details b,subscription_details c
						where a.user_id collate utf8_general_ci = b.user_name collate utf8_general_ci
						and c.username  = a.user_id and a.quiz_id ='$cat' and c.package_id='42'
					 ORDER BY CONVERT( a.rank, SIGNED INTEGER )";
		}

		if ($batch=='PackageId26'){
				$sql = "SELECT a.*,(physicsmarkseca+physicsmarksecb+chemistrymarkseca+chemistrymarksecb+zoologymarkseca+zoologymarksecb+botanymarkseca+botanymarksecb) total ,concat(b.first_name,' ',b.last_name) name, c.package_id,b.rollno,b.batch,b.accommodation,b.no_of_communication
						from student_quiz_result_neet2021 a,user_details b,subscription_details c
						where a.user_id collate utf8_general_ci = b.user_name collate utf8_general_ci
						and c.username  = a.user_id and a.quiz_id ='$cat' and c.package_id='26'
					 ORDER BY CONVERT( a.rank, SIGNED INTEGER )";
		}
	if ($batch=='54'){
				$sql = "SELECT a.*,(physicsmarkseca+physicsmarksecb+chemistrymarkseca+chemistrymarksecb+zoologymarkseca+zoologymarksecb+botanymarkseca+botanymarksecb) total ,concat(b.first_name,' ',b.last_name) name, c.package_id,b.rollno,b.batch,b.accommodation,b.no_of_communication,
						(select Quiz_name from quiz_info where id= '$cat') as Quiz_name
						from student_quiz_result_neet2021 a,user_details b,subscription_details c
						where a.user_id collate utf8_general_ci = b.user_name collate utf8_general_ci
						and c.username  = a.user_id and a.quiz_id ='$cat' and c.package_id='54'
					 ORDER BY CONVERT( a.rank, SIGNED INTEGER )";
		}


        //    print_r($sql);
        $query = $this->db->query($sql);
        try {
            if ($query->num_rows() > 0) {
                return $query->result_array();
            } else {
                return $query->result_array();
            }

        } catch (exception $e) {echo $e->getMessage();
        }

    }*/
	
	
	public function getallrankcafshort2($cat,$batch)
    {
        $this->load->library('nativesession');

        $sql = "truncate table quiz_rank";
        $query = $this->db->query($sql);
        $sql = "SET @prev_value = NULL";
        $query = $this->db->query($sql);
        $sql = "SET @rank_count = 0";
        $query = $this->db->query($sql);
		if ($batch=='all'){

        $sql = "INSERT into  quiz_rank
				select user_id,quiz_id ,bemark+bckmark, CASE
		WHEN @prev_value = bemark+bckmark THEN @rank_count
		WHEN @prev_value := bemark+bckmark THEN @rank_count := @rank_count + 1

		END AS rank
		FROM student_quiz_resultcafshort2 where quiz_id ='$cat'
		ORDER BY bemark+bckmark desc";
		
		}
		if ($batch=='JNANASUDHAIPU'){

        $sql = "INSERT into  quiz_rank
				select user_id,quiz_id ,bemark+bckmark, CASE
		WHEN @prev_value = bemark+bckmark THEN @rank_count
		WHEN @prev_value := bemark+bckmark THEN @rank_count := @rank_count + 1


		END AS rank
		FROM student_quiz_resultcafshort2 where quiz_id ='$cat' and user_id collate utf8_general_ci in
        (select USER_NAME collate utf8_general_ci from user_details 
        where jnanasudhastandard = 'JNANASUDHAIPU')
		ORDER BY bemark+bckmark desc";
		
		}
		if ($batch=='JNANASUDHAIIPU'){

        $sql = "INSERT into  quiz_rank
				select user_id,quiz_id ,bemark+bckmark, CASE
		WHEN @prev_value = bemark+bckmark THEN @rank_count
		WHEN @prev_value := bemark+bckmark THEN @rank_count := @rank_count + 1


		END AS rank
		FROM student_quiz_resultcafshort2 where quiz_id ='$cat' and user_id collate utf8_general_ci in
        (select USER_NAME collate utf8_general_ci from user_details 
        where jnanasudhastandard = 'JNANASUDHAIIPU')
		ORDER BY bemark+bckmark desc";
		
		}
		 if ($batch=='PackageId7'){

        $sql = "INSERT into  quiz_rank
				select user_id,quiz_id ,bemark+bckmark, CASE
		WHEN @prev_value = bemark+bckmark THEN @rank_count
		WHEN @prev_value := bemark+bckmark THEN @rank_count := @rank_count + 1


		END AS rank
		FROM student_quiz_resultcafshort2 where quiz_id ='$cat' and user_id  in
        (select username  from subscription_details 
        where package_id = 7)
		ORDER BY bemark+bckmark desc";
		
		}
		 if ($batch=='PackageId8'){

        $sql = "INSERT into  quiz_rank
				select user_id,quiz_id ,bemark+bckmark, CASE
		WHEN @prev_value = bemark+bckmark THEN @rank_count
		WHEN @prev_value := bemark+bckmark THEN @rank_count := @rank_count + 1

		END AS rank
		FROM student_quiz_resultcafshort2 where quiz_id ='$cat' and user_id  in
        (select username  from subscription_details 
        where package_id = 8)
		ORDER BY bemark+bckmark desc";
		
		}
		
		if ($batch=='PackageId41'){

        $sql = "INSERT into  quiz_rank
				select user_id,quiz_id ,bemark+bckmark, CASE
		WHEN @prev_value = bemark+bckmark THEN @rank_count
		WHEN @prev_value := bemark+bckmark THEN @rank_count := @rank_count + 1


		END AS rank
		FROM student_quiz_resultcafshort2 where quiz_id ='$cat' and user_id  in
        (select username  from subscription_details 
        where package_id = 41)
		ORDER BY bemark+bckmark desc";
		
		}
		
		if ($batch=='PackageId42'){

        $sql = "INSERT into  quiz_rank
				select user_id,quiz_id ,bemark+bckmark, CASE
		WHEN @prev_value = bemark+bckmark THEN @rank_count
		WHEN @prev_value := bemark+bckmark THEN @rank_count := @rank_count + 1


		END AS rank
		FROM student_quiz_resultcafshort2 where quiz_id ='$cat' and user_id  in
        (select username  from subscription_details 
        where package_id = 42)
		ORDER BY bemark+bckmark desc";
		
		}
		if ($batch=='PackageId26'){

        $sql = "INSERT into  quiz_rank
				select user_id,quiz_id ,bemark+bckmark, CASE
		WHEN @prev_value = bemark+bckmark THEN @rank_count
		WHEN @prev_value := bemark+bckmark THEN @rank_count := @rank_count + 1


		END AS rank
		FROM student_quiz_resultcafshort2 where quiz_id ='$cat' and user_id  in
        (select username  from subscription_details 
        where package_id = 26)
		ORDER BY bemark+bckmark desc";
		
		}
		
		if ($batch=='54'){

        $sql = "INSERT into  quiz_rank
				select user_id,quiz_id ,bemark+bckmark, CASE
		WHEN @prev_value = bemark+bckmark THEN @rank_count
		WHEN @prev_value := bemark+bckmark THEN @rank_count := @rank_count + 1

		END AS rank
		FROM student_quiz_resultcafshort2 where quiz_id ='$cat' and user_id  in
        (select username  from subscription_details 
        where package_id = 54)
		ORDER BY bemark+bckmark desc";
		
		}
		//print_r($sql);
        $query = $this->db->query($sql);

        $sql = "update student_quiz_resultcafshort2 a inner join quiz_rank b
		on a.quiz_id = b.quiz_id
		set a.rank= b.rank
		where a.user_id = b.user_id
		and a.quiz_id = '$cat'";
        //   print_r($sql);
        $query = $this->db->query($sql);
		if ($batch=='all'){
				$sql = "SELECT a.*,(bemark+bckmark) total ,concat(b.first_name,' ',b.last_name) name,b.rollno,b.batch,b.accommodation,
				(select Quiz_name from quiz_info where id= '$cat') as Quiz_name from student_quiz_resultcafshort2 a,user_details b 
				where a.user_id collate utf8_general_ci = b.user_name collate utf8_general_ci and   a.quiz_id ='$cat'
					 ORDER BY CONVERT( a.rank, SIGNED INTEGER )";
		}
		if ($batch=='JNANASUDHAIPU'){
				$sql = "SELECT a.*,(bemark+bckmark) total ,concat(b.first_name,' ',b.last_name) name,b.rollno,b.batch,b.accommodation,
				(select Quiz_name from quiz_info where id= '$cat') as Quiz_name from student_quiz_resultcafshort2 a,user_details b 
				where a.user_id collate utf8_general_ci = b.user_name collate utf8_general_ci and   a.quiz_id ='$cat' and b.jnanasudhastandard='JNANASUDHAIPU'
					 ORDER BY CONVERT( a.rank, SIGNED INTEGER )";
		}
		if ($batch=='JNANASUDHAIIPU'){
				$sql = "SELECT a.*,(bemark+bckmark) total ,concat(b.first_name,' ',b.last_name) name,b.rollno,b.batch,b.accommodation,
				(select Quiz_name from quiz_info where id= '$cat') as Quiz_name from student_quiz_resultcafshort2 a,user_details b 
				where a.user_id collate utf8_general_ci = b.user_name collate utf8_general_ci and   a.quiz_id ='$cat' and b.jnanasudhastandard='JNANASUDHAIIPU'
					 ORDER BY CONVERT( a.rank, SIGNED INTEGER )";
		}

		if ($batch=='PackageId7'){
				$sql = "SELECT a.*,(bemark+bckmark) total ,concat(b.first_name,' ',b.last_name) name, c.package_id,b.rollno,b.batch,b.accommodation,
						(select Quiz_name from quiz_info where id= '$cat') as Quiz_name
						from student_quiz_resultcafshort2 a,user_details b,subscription_details c
						where a.user_id collate utf8_general_ci = b.user_name collate utf8_general_ci
						and c.username  = a.user_id and a.quiz_id ='$cat' and c.package_id='7'
					 ORDER BY CONVERT( a.rank, SIGNED INTEGER )";
		}
		if ($batch=='PackageId8'){
				$sql = "SELECT a.*,(bemark+bckmark) total ,concat(b.first_name,' ',b.last_name) name, c.package_id,b.rollno,b.batch,b.accommodation,
						(select Quiz_name from quiz_info where id= '$cat') as Quiz_name
						from student_quiz_resultcafshort2 a,user_details b,subscription_details c
						where a.user_id collate utf8_general_ci = b.user_name collate utf8_general_ci
						and c.username  = a.user_id and a.quiz_id ='$cat' and c.package_id='8'
					 ORDER BY CONVERT( a.rank, SIGNED INTEGER )";
		}
		if ($batch=='PackageId41'){
				$sql = "SELECT a.*,(bemark+bckmark) total ,concat(b.first_name,' ',b.last_name) name, c.package_id,b.rollno,b.batch,b.accommodation,
						(select Quiz_name from quiz_info where id= '$cat') as Quiz_name
						from student_quiz_resultcafshort2 a,user_details b,subscription_details c
						where a.user_id collate utf8_general_ci = b.user_name collate utf8_general_ci
						and c.username  = a.user_id and a.quiz_id ='$cat' and c.package_id='41'
					 ORDER BY CONVERT( a.rank, SIGNED INTEGER )";
		}
		if ($batch=='PackageId42'){
				$sql = "SELECT a.*,(bemark+bckmark) total ,concat(b.first_name,' ',b.last_name) name, c.package_id,b.rollno,b.batch,b.accommodation,
						(select Quiz_name from quiz_info where id= '$cat') as Quiz_name
						from student_quiz_resultcafshort2 a,user_details b,subscription_details c
						where a.user_id collate utf8_general_ci = b.user_name collate utf8_general_ci
						and c.username  = a.user_id and a.quiz_id ='$cat' and c.package_id='42'
					 ORDER BY CONVERT( a.rank, SIGNED INTEGER )";
		}

		if ($batch=='PackageId26'){
				$sql = "SELECT a.*,(bemark+bckmark) total ,concat(b.first_name,' ',b.last_name) name, c.package_id,b.rollno,b.batch,b.accommodation
						from student_quiz_resultcafshort2 a,user_details b,subscription_details c
						where a.user_id collate utf8_general_ci = b.user_name collate utf8_general_ci
						and c.username  = a.user_id and a.quiz_id ='$cat' and c.package_id='26'
					 ORDER BY CONVERT( a.rank, SIGNED INTEGER )";
		}
	if ($batch=='54'){
				$sql = "SELECT a.*,(bemark+bckmark) total ,concat(b.first_name,' ',b.last_name) name, c.package_id,b.rollno,b.batch,b.accommodation,
						(select Quiz_name from quiz_info where id= '$cat') as Quiz_name
						from student_quiz_resultcafshort2 a,user_details b,subscription_details c
						where a.user_id collate utf8_general_ci = b.user_name collate utf8_general_ci
						and c.username  = a.user_id and a.quiz_id ='$cat' and c.package_id='54'
					 ORDER BY CONVERT( a.rank, SIGNED INTEGER )";
		}


        //    print_r($sql);
        $query = $this->db->query($sql);
        try {
            if ($query->num_rows() > 0) {
                return $query->result_array();
            } else {
                return $query->result_array();
            }

        } catch (exception $e) {echo $e->getMessage();
        }

    }

public function getallrankcafshort1($cat,$batch)
    {
        $this->load->library('nativesession');

        $sql = "truncate table quiz_rank";
        $query = $this->db->query($sql);
        $sql = "SET @prev_value = NULL";
        $query = $this->db->query($sql);
        $sql = "SET @rank_count = 0";
        $query = $this->db->query($sql);
		if ($batch=='all'){

        $sql = "INSERT into  quiz_rank
				select user_id,quiz_id ,bmathmark+lrmark+statmark, CASE
		WHEN @prev_value = bmathmark+lrmark+statmark THEN @rank_count
		WHEN @prev_value := bmathmark+lrmark+statmark THEN @rank_count := @rank_count + 1

		END AS rank
		FROM student_quiz_resultcafshort1 where quiz_id ='$cat'
		ORDER BY bmathmark+lrmark+statmark desc";
		
		}
		if ($batch=='JNANASUDHAIPU'){

        $sql = "INSERT into  quiz_rank
				select user_id,quiz_id ,bmathmark+lrmark+statmark, CASE
		WHEN @prev_value = bmathmark+lrmark+statmark THEN @rank_count
		WHEN @prev_value := bmathmark+lrmark+statmark THEN @rank_count := @rank_count + 1


		END AS rank
		FROM student_quiz_resultcafshort1 where quiz_id ='$cat' and user_id collate utf8_general_ci in
        (select USER_NAME collate utf8_general_ci from user_details 
        where jnanasudhastandard = 'JNANASUDHAIPU')
		ORDER BY bmathmark+lrmark+statmark desc";
		
		}
		if ($batch=='JNANASUDHAIIPU'){

        $sql = "INSERT into  quiz_rank
				select user_id,quiz_id ,bmathmark+lrmark+statmark, CASE
		WHEN @prev_value = bmathmark+lrmark+statmark THEN @rank_count
		WHEN @prev_value := bmathmark+lrmark+statmark THEN @rank_count := @rank_count + 1


		END AS rank
		FROM student_quiz_resultcafshort1 where quiz_id ='$cat' and user_id collate utf8_general_ci in
        (select USER_NAME collate utf8_general_ci from user_details 
        where jnanasudhastandard = 'JNANASUDHAIIPU')
		ORDER BY bmathmark+lrmark+statmark desc";
		
		}
		 if ($batch=='PackageId7'){

        $sql = "INSERT into  quiz_rank
				select user_id,quiz_id ,bmathmark+lrmark+statmark, CASE
		WHEN @prev_value = bmathmark+lrmark+statmark THEN @rank_count
		WHEN @prev_value := bmathmark+lrmark+statmark THEN @rank_count := @rank_count + 1


		END AS rank
		FROM student_quiz_resultcafshort1 where quiz_id ='$cat' and user_id  in
        (select username  from subscription_details 
        where package_id = 7)
		ORDER BY bmathmark+lrmark+statmark desc";
		
		}
		 if ($batch=='PackageId8'){

        $sql = "INSERT into  quiz_rank
				select user_id,quiz_id ,bmathmark+lrmark+statmark, CASE
		WHEN @prev_value = bmathmark+lrmark+statmark THEN @rank_count
		WHEN @prev_value := bmathmark+lrmark+statmark THEN @rank_count := @rank_count + 1

		END AS rank
		FROM student_quiz_resultcafshort1 where quiz_id ='$cat' and user_id  in
        (select username  from subscription_details 
        where package_id = 8)
		ORDER BY bmathmark+lrmark+statmark desc";
		
		}
		
		if ($batch=='PackageId41'){

        $sql = "INSERT into  quiz_rank
				select user_id,quiz_id ,bmathmark+lrmark+statmark, CASE
		WHEN @prev_value = bmathmark+lrmark+statmark THEN @rank_count
		WHEN @prev_value := bmathmark+lrmark+statmark THEN @rank_count := @rank_count + 1


		END AS rank
		FROM student_quiz_resultcafshort1 where quiz_id ='$cat' and user_id  in
        (select username  from subscription_details 
        where package_id = 41)
		ORDER BY bmathmark+lrmark+statmark desc";
		
		}
		
		if ($batch=='PackageId42'){

        $sql = "INSERT into  quiz_rank
				select user_id,quiz_id ,bmathmark+lrmark+statmark, CASE
		WHEN @prev_value = bmathmark+lrmark+statmark THEN @rank_count
		WHEN @prev_value := bmathmark+lrmark+statmark THEN @rank_count := @rank_count + 1


		END AS rank
		FROM student_quiz_resultcafshort1 where quiz_id ='$cat' and user_id  in
        (select username  from subscription_details 
        where package_id = 42)
		ORDER BY bmathmark+lrmark+statmark desc";
		
		}
		if ($batch=='PackageId26'){

        $sql = "INSERT into  quiz_rank
				select user_id,quiz_id ,bmathmark+lrmark+statmark, CASE
		WHEN @prev_value = bmathmark+lrmark+statmark THEN @rank_count
		WHEN @prev_value := bmathmark+lrmark+statmark THEN @rank_count := @rank_count + 1


		END AS rank
		FROM student_quiz_resultcafshort1 where quiz_id ='$cat' and user_id  in
        (select username  from subscription_details 
        where package_id = 26)
		ORDER BY bmathmark+lrmark+statmark desc";
		
		}
		
		if ($batch=='54'){

        $sql = "INSERT into  quiz_rank
				select user_id,quiz_id ,bmathmark+lrmark+statmark, CASE
		WHEN @prev_value = bmathmark+lrmark+statmark THEN @rank_count
		WHEN @prev_value := bmathmark+lrmark+statmark THEN @rank_count := @rank_count + 1

		END AS rank
		FROM student_quiz_resultcafshort1 where quiz_id ='$cat' and user_id  in
        (select username  from subscription_details 
        where package_id = 54)
		ORDER BY bmathmark+lrmark+statmark desc";
		
		}
		//print_r($sql);
        $query = $this->db->query($sql);

        $sql = "update student_quiz_resultcafshort1 a inner join quiz_rank b
		on a.quiz_id = b.quiz_id
		set a.rank= b.rank
		where a.user_id = b.user_id
		and a.quiz_id = '$cat'";
        //   print_r($sql);
        $query = $this->db->query($sql);
		if ($batch=='all'){
				$sql = "SELECT a.*,(bmathmark+lrmark+statmark) total ,concat(b.first_name,' ',b.last_name) name,b.rollno,b.batch,b.accommodation,
				(select Quiz_name from quiz_info where id= '$cat') as Quiz_name from student_quiz_resultcafshort1 a,user_details b 
				where a.user_id collate utf8_general_ci = b.user_name collate utf8_general_ci and   a.quiz_id ='$cat'
					 ORDER BY CONVERT( a.rank, SIGNED INTEGER )";
		}
		if ($batch=='JNANASUDHAIPU'){
				$sql = "SELECT a.*,(bmathmark+lrmark+statmark) total ,concat(b.first_name,' ',b.last_name) name,b.rollno,b.batch,b.accommodation,
				(select Quiz_name from quiz_info where id= '$cat') as Quiz_name from student_quiz_resultcafshort1 a,user_details b 
				where a.user_id collate utf8_general_ci = b.user_name collate utf8_general_ci and   a.quiz_id ='$cat' and b.jnanasudhastandard='JNANASUDHAIPU'
					 ORDER BY CONVERT( a.rank, SIGNED INTEGER )";
		}
		if ($batch=='JNANASUDHAIIPU'){
				$sql = "SELECT a.*,(bmathmark+lrmark+statmark) total ,concat(b.first_name,' ',b.last_name) name,b.rollno,b.batch,b.accommodation,
				(select Quiz_name from quiz_info where id= '$cat') as Quiz_name from student_quiz_resultcafshort1 a,user_details b 
				where a.user_id collate utf8_general_ci = b.user_name collate utf8_general_ci and   a.quiz_id ='$cat' and b.jnanasudhastandard='JNANASUDHAIIPU'
					 ORDER BY CONVERT( a.rank, SIGNED INTEGER )";
		}

		if ($batch=='PackageId7'){
				$sql = "SELECT a.*,(bmathmark+lrmark+statmark) total ,concat(b.first_name,' ',b.last_name) name, c.package_id,b.rollno,b.batch,b.accommodation,
						(select Quiz_name from quiz_info where id= '$cat') as Quiz_name
						from student_quiz_resultcafshort1 a,user_details b,subscription_details c
						where a.user_id collate utf8_general_ci = b.user_name collate utf8_general_ci
						and c.username  = a.user_id and a.quiz_id ='$cat' and c.package_id='7'
					 ORDER BY CONVERT( a.rank, SIGNED INTEGER )";
		}
		if ($batch=='PackageId8'){
				$sql = "SELECT a.*,(bmathmark+lrmark+statmark) total ,concat(b.first_name,' ',b.last_name) name, c.package_id,b.rollno,b.batch,b.accommodation,
						(select Quiz_name from quiz_info where id= '$cat') as Quiz_name
						from student_quiz_resultcafshort1 a,user_details b,subscription_details c
						where a.user_id collate utf8_general_ci = b.user_name collate utf8_general_ci
						and c.username  = a.user_id and a.quiz_id ='$cat' and c.package_id='8'
					 ORDER BY CONVERT( a.rank, SIGNED INTEGER )";
		}
		if ($batch=='PackageId41'){
				$sql = "SELECT a.*,(bmathmark+lrmark+statmark) total ,concat(b.first_name,' ',b.last_name) name, c.package_id,b.rollno,b.batch,b.accommodation,
						(select Quiz_name from quiz_info where id= '$cat') as Quiz_name
						from student_quiz_resultcafshort1 a,user_details b,subscription_details c
						where a.user_id collate utf8_general_ci = b.user_name collate utf8_general_ci
						and c.username  = a.user_id and a.quiz_id ='$cat' and c.package_id='41'
					 ORDER BY CONVERT( a.rank, SIGNED INTEGER )";
		}
		if ($batch=='PackageId42'){
				$sql = "SELECT a.*,(bmathmark+lrmark+statmark) total ,concat(b.first_name,' ',b.last_name) name, c.package_id,b.rollno,b.batch,b.accommodation,
						(select Quiz_name from quiz_info where id= '$cat') as Quiz_name
						from student_quiz_resultcafshort1 a,user_details b,subscription_details c
						where a.user_id collate utf8_general_ci = b.user_name collate utf8_general_ci
						and c.username  = a.user_id and a.quiz_id ='$cat' and c.package_id='42'
					 ORDER BY CONVERT( a.rank, SIGNED INTEGER )";
		}

		if ($batch=='PackageId26'){
				$sql = "SELECT a.*,(bmathmark+lrmark+statmark) total ,concat(b.first_name,' ',b.last_name) name, c.package_id,b.rollno,b.batch,b.accommodation
						from student_quiz_resultcafshort1 a,user_details b,subscription_details c
						where a.user_id collate utf8_general_ci = b.user_name collate utf8_general_ci
						and c.username  = a.user_id and a.quiz_id ='$cat' and c.package_id='26'
					 ORDER BY CONVERT( a.rank, SIGNED INTEGER )";
		}
	if ($batch=='54'){
				$sql = "SELECT a.*,(bmathmark+lrmark+statmark) total ,concat(b.first_name,' ',b.last_name) name, c.package_id,b.rollno,b.batch,b.accommodation,
						(select Quiz_name from quiz_info where id= '$cat') as Quiz_name
						from student_quiz_resultcafshort1 a,user_details b,subscription_details c
						where a.user_id collate utf8_general_ci = b.user_name collate utf8_general_ci
						and c.username  = a.user_id and a.quiz_id ='$cat' and c.package_id='54'
					 ORDER BY CONVERT( a.rank, SIGNED INTEGER )";
		}


        //    print_r($sql);
        $query = $this->db->query($sql);
        try {
            if ($query->num_rows() > 0) {
                return $query->result_array();
            } else {
                return $query->result_array();
            }

        } catch (exception $e) {echo $e->getMessage();
        }

    }


public function getallrankcaf2($cat,$batch)
    {
        $this->load->library('nativesession');

        $sql = "truncate table quiz_rank";
        $query = $this->db->query($sql);
        $sql = "SET @prev_value = NULL";
        $query = $this->db->query($sql);
        $sql = "SET @rank_count = 0";
        $query = $this->db->query($sql);
		if ($batch=='all'){

        $sql = "INSERT into  quiz_rank
				select user_id,quiz_id ,bemark+bckmark, CASE
		WHEN @prev_value = bemark+bckmark THEN @rank_count
		WHEN @prev_value := bemark+bckmark THEN @rank_count := @rank_count + 1

		END AS rank
		FROM student_quiz_resultcaf2 where quiz_id ='$cat'
		ORDER BY bemark+bckmark desc";
		
		}
		if ($batch=='JNANASUDHAIPU'){

        $sql = "INSERT into  quiz_rank
				select user_id,quiz_id ,bemark+bckmark, CASE
		WHEN @prev_value = bemark+bckmark THEN @rank_count
		WHEN @prev_value := bemark+bckmark THEN @rank_count := @rank_count + 1


		END AS rank
		FROM student_quiz_resultcaf2 where quiz_id ='$cat' and user_id collate utf8_general_ci in
        (select USER_NAME collate utf8_general_ci from user_details 
        where jnanasudhastandard = 'JNANASUDHAIPU')
		ORDER BY bemark+bckmark desc";
		
		}
		if ($batch=='JNANASUDHAIIPU'){

        $sql = "INSERT into  quiz_rank
				select user_id,quiz_id ,bemark+bckmark, CASE
		WHEN @prev_value = bemark+bckmark THEN @rank_count
		WHEN @prev_value := bemark+bckmark THEN @rank_count := @rank_count + 1


		END AS rank
		FROM student_quiz_resultcaf2 where quiz_id ='$cat' and user_id collate utf8_general_ci in
        (select USER_NAME collate utf8_general_ci from user_details 
        where jnanasudhastandard = 'JNANASUDHAIIPU')
		ORDER BY bemark+bckmark desc";
		
		}
		 if ($batch=='PackageId7'){

        $sql = "INSERT into  quiz_rank
				select user_id,quiz_id ,bemark+bckmark, CASE
		WHEN @prev_value = bemark+bckmark THEN @rank_count
		WHEN @prev_value := bemark+bckmark THEN @rank_count := @rank_count + 1


		END AS rank
		FROM student_quiz_resultcaf2 where quiz_id ='$cat' and user_id  in
        (select username  from subscription_details 
        where package_id = 7)
		ORDER BY bemark+bckmark desc";
		
		}
		 if ($batch=='PackageId8'){

        $sql = "INSERT into  quiz_rank
				select user_id,quiz_id ,bemark+bckmark, CASE
		WHEN @prev_value = bemark+bckmark THEN @rank_count
		WHEN @prev_value := bemark+bckmark THEN @rank_count := @rank_count + 1

		END AS rank
		FROM student_quiz_resultcaf2 where quiz_id ='$cat' and user_id  in
        (select username  from subscription_details 
        where package_id = 8)
		ORDER BY bemark+bckmark desc";
		
		}
		
		if ($batch=='PackageId41'){

        $sql = "INSERT into  quiz_rank
				select user_id,quiz_id ,bemark+bckmark, CASE
		WHEN @prev_value = bemark+bckmark THEN @rank_count
		WHEN @prev_value := bemark+bckmark THEN @rank_count := @rank_count + 1


		END AS rank
		FROM student_quiz_resultcaf2 where quiz_id ='$cat' and user_id  in
        (select username  from subscription_details 
        where package_id = 41)
		ORDER BY bemark+bckmark desc";
		
		}
		
		if ($batch=='PackageId42'){

        $sql = "INSERT into  quiz_rank
				select user_id,quiz_id ,bemark+bckmark, CASE
		WHEN @prev_value = bemark+bckmark THEN @rank_count
		WHEN @prev_value := bemark+bckmark THEN @rank_count := @rank_count + 1


		END AS rank
		FROM student_quiz_resultcaf2 where quiz_id ='$cat' and user_id  in
        (select username  from subscription_details 
        where package_id = 42)
		ORDER BY bemark+bckmark desc";
		
		}
		if ($batch=='PackageId26'){

        $sql = "INSERT into  quiz_rank
				select user_id,quiz_id ,bemark+bckmark, CASE
		WHEN @prev_value = bemark+bckmark THEN @rank_count
		WHEN @prev_value := bemark+bckmark THEN @rank_count := @rank_count + 1


		END AS rank
		FROM student_quiz_resultcaf2 where quiz_id ='$cat' and user_id  in
        (select username  from subscription_details 
        where package_id = 26)
		ORDER BY bemark+bckmark desc";
		
		}
		
		if ($batch=='54'){

        $sql = "INSERT into  quiz_rank
				select user_id,quiz_id ,bemark+bckmark, CASE
		WHEN @prev_value = bemark+bckmark THEN @rank_count
		WHEN @prev_value := bemark+bckmark THEN @rank_count := @rank_count + 1

		END AS rank
		FROM student_quiz_resultcaf2 where quiz_id ='$cat' and user_id  in
        (select username  from subscription_details 
        where package_id = 54)
		ORDER BY bemark+bckmark desc";
		
		}
		//print_r($sql);
        $query = $this->db->query($sql);

        $sql = "update student_quiz_resultcaf2 a inner join quiz_rank b
		on a.quiz_id = b.quiz_id
		set a.rank= b.rank
		where a.user_id = b.user_id
		and a.quiz_id = '$cat'";
        //   print_r($sql);
        $query = $this->db->query($sql);
		if ($batch=='all'){
				$sql = "SELECT a.*,(bemark+bckmark) total ,concat(b.first_name,' ',b.last_name) name,b.rollno,b.batch,b.accommodation,
				(select Quiz_name from quiz_info where id= '$cat') as Quiz_name from student_quiz_resultcaf2 a,user_details b 
				where a.user_id collate utf8_general_ci = b.user_name collate utf8_general_ci and   a.quiz_id ='$cat'
					 ORDER BY CONVERT( a.rank, SIGNED INTEGER )";
		}
		if ($batch=='JNANASUDHAIPU'){
				$sql = "SELECT a.*,(bemark+bckmark) total ,concat(b.first_name,' ',b.last_name) name,b.rollno,b.batch,b.accommodation,
				(select Quiz_name from quiz_info where id= '$cat') as Quiz_name from student_quiz_resultcaf2 a,user_details b 
				where a.user_id collate utf8_general_ci = b.user_name collate utf8_general_ci and   a.quiz_id ='$cat' and b.jnanasudhastandard='JNANASUDHAIPU'
					 ORDER BY CONVERT( a.rank, SIGNED INTEGER )";
		}
		if ($batch=='JNANASUDHAIIPU'){
				$sql = "SELECT a.*,(bemark+bckmark) total ,concat(b.first_name,' ',b.last_name) name,b.rollno,b.batch,b.accommodation,
				(select Quiz_name from quiz_info where id= '$cat') as Quiz_name from student_quiz_resultcaf2 a,user_details b 
				where a.user_id collate utf8_general_ci = b.user_name collate utf8_general_ci and   a.quiz_id ='$cat' and b.jnanasudhastandard='JNANASUDHAIIPU'
					 ORDER BY CONVERT( a.rank, SIGNED INTEGER )";
		}

		if ($batch=='PackageId7'){
				$sql = "SELECT a.*,(bemark+bckmark) total ,concat(b.first_name,' ',b.last_name) name, c.package_id,b.rollno,b.batch,b.accommodation,
						(select Quiz_name from quiz_info where id= '$cat') as Quiz_name
						from student_quiz_resultcaf2 a,user_details b,subscription_details c
						where a.user_id collate utf8_general_ci = b.user_name collate utf8_general_ci
						and c.username  = a.user_id and a.quiz_id ='$cat' and c.package_id='7'
					 ORDER BY CONVERT( a.rank, SIGNED INTEGER )";
		}
		if ($batch=='PackageId8'){
				$sql = "SELECT a.*,(bemark+bckmark) total ,concat(b.first_name,' ',b.last_name) name, c.package_id,b.rollno,b.batch,b.accommodation,
						(select Quiz_name from quiz_info where id= '$cat') as Quiz_name
						from student_quiz_resultcaf2 a,user_details b,subscription_details c
						where a.user_id collate utf8_general_ci = b.user_name collate utf8_general_ci
						and c.username  = a.user_id and a.quiz_id ='$cat' and c.package_id='8'
					 ORDER BY CONVERT( a.rank, SIGNED INTEGER )";
		}
		if ($batch=='PackageId41'){
				$sql = "SELECT a.*,(bemark+bckmark) total ,concat(b.first_name,' ',b.last_name) name, c.package_id,b.rollno,b.batch,b.accommodation,
						(select Quiz_name from quiz_info where id= '$cat') as Quiz_name
						from student_quiz_resultcaf2 a,user_details b,subscription_details c
						where a.user_id collate utf8_general_ci = b.user_name collate utf8_general_ci
						and c.username  = a.user_id and a.quiz_id ='$cat' and c.package_id='41'
					 ORDER BY CONVERT( a.rank, SIGNED INTEGER )";
		}
		if ($batch=='PackageId42'){
				$sql = "SELECT a.*,(bemark+bckmark) total ,concat(b.first_name,' ',b.last_name) name, c.package_id,b.rollno,b.batch,b.accommodation,
						(select Quiz_name from quiz_info where id= '$cat') as Quiz_name
						from student_quiz_resultcaf2 a,user_details b,subscription_details c
						where a.user_id collate utf8_general_ci = b.user_name collate utf8_general_ci
						and c.username  = a.user_id and a.quiz_id ='$cat' and c.package_id='42'
					 ORDER BY CONVERT( a.rank, SIGNED INTEGER )";
		}

		if ($batch=='PackageId26'){
				$sql = "SELECT a.*,(bemark+bckmark) total ,concat(b.first_name,' ',b.last_name) name, c.package_id,b.rollno,b.batch,b.accommodation
						from student_quiz_resultcaf2 a,user_details b,subscription_details c
						where a.user_id collate utf8_general_ci = b.user_name collate utf8_general_ci
						and c.username  = a.user_id and a.quiz_id ='$cat' and c.package_id='26'
					 ORDER BY CONVERT( a.rank, SIGNED INTEGER )";
		}
	if ($batch=='54'){
				$sql = "SELECT a.*,(bemark+bckmark) total ,concat(b.first_name,' ',b.last_name) name, c.package_id,b.rollno,b.batch,b.accommodation,
						(select Quiz_name from quiz_info where id= '$cat') as Quiz_name
						from student_quiz_resultcaf2 a,user_details b,subscription_details c
						where a.user_id collate utf8_general_ci = b.user_name collate utf8_general_ci
						and c.username  = a.user_id and a.quiz_id ='$cat' and c.package_id='54'
					 ORDER BY CONVERT( a.rank, SIGNED INTEGER )";
		}


        //    print_r($sql);
        $query = $this->db->query($sql);
        try {
            if ($query->num_rows() > 0) {
                return $query->result_array();
            } else {
                return $query->result_array();
            }

        } catch (exception $e) {echo $e->getMessage();
        }

    }

public function getallrankcaf1($cat,$batch)
    {
        $this->load->library('nativesession');

        $sql = "truncate table quiz_rank";
        $query = $this->db->query($sql);
        $sql = "SET @prev_value = NULL";
        $query = $this->db->query($sql);
        $sql = "SET @rank_count = 0";
        $query = $this->db->query($sql);
		if ($batch=='all'){

        $sql = "INSERT into  quiz_rank
				select user_id,quiz_id ,bmathmark+lrmark+statmark, CASE
		WHEN @prev_value = bmathmark+lrmark+statmark THEN @rank_count
		WHEN @prev_value := bmathmark+lrmark+statmark THEN @rank_count := @rank_count + 1

		END AS rank
		FROM student_quiz_resultcaf1 where quiz_id ='$cat'
		ORDER BY bmathmark+lrmark+statmark desc";
		
		}
		if ($batch=='JNANASUDHAIPU'){

        $sql = "INSERT into  quiz_rank
				select user_id,quiz_id ,bmathmark+lrmark+statmark, CASE
		WHEN @prev_value = bmathmark+lrmark+statmark THEN @rank_count
		WHEN @prev_value := bmathmark+lrmark+statmark THEN @rank_count := @rank_count + 1


		END AS rank
		FROM student_quiz_resultcaf1 where quiz_id ='$cat' and user_id collate utf8_general_ci in
        (select USER_NAME collate utf8_general_ci from user_details 
        where jnanasudhastandard = 'JNANASUDHAIPU')
		ORDER BY bmathmark+lrmark+statmark desc";
		
		}
		if ($batch=='JNANASUDHAIIPU'){

        $sql = "INSERT into  quiz_rank
				select user_id,quiz_id ,bmathmark+lrmark+statmark, CASE
		WHEN @prev_value = bmathmark+lrmark+statmark THEN @rank_count
		WHEN @prev_value := bmathmark+lrmark+statmark THEN @rank_count := @rank_count + 1


		END AS rank
		FROM student_quiz_resultcaf1 where quiz_id ='$cat' and user_id collate utf8_general_ci in
        (select USER_NAME collate utf8_general_ci from user_details 
        where jnanasudhastandard = 'JNANASUDHAIIPU')
		ORDER BY bmathmark+lrmark+statmark desc";
		
		}
		 if ($batch=='PackageId7'){

        $sql = "INSERT into  quiz_rank
				select user_id,quiz_id ,bmathmark+lrmark+statmark, CASE
		WHEN @prev_value = bmathmark+lrmark+statmark THEN @rank_count
		WHEN @prev_value := bmathmark+lrmark+statmark THEN @rank_count := @rank_count + 1


		END AS rank
		FROM student_quiz_resultcaf1 where quiz_id ='$cat' and user_id  in
        (select username  from subscription_details 
        where package_id = 7)
		ORDER BY bmathmark+lrmark+statmark desc";
		
		}
		 if ($batch=='PackageId8'){

        $sql = "INSERT into  quiz_rank
				select user_id,quiz_id ,bmathmark+lrmark+statmark, CASE
		WHEN @prev_value = bmathmark+lrmark+statmark THEN @rank_count
		WHEN @prev_value := bmathmark+lrmark+statmark THEN @rank_count := @rank_count + 1

		END AS rank
		FROM student_quiz_resultcaf1 where quiz_id ='$cat' and user_id  in
        (select username  from subscription_details 
        where package_id = 8)
		ORDER BY bmathmark+lrmark+statmark desc";
		
		}
		
		if ($batch=='PackageId41'){

        $sql = "INSERT into  quiz_rank
				select user_id,quiz_id ,bmathmark+lrmark+statmark, CASE
		WHEN @prev_value = bmathmark+lrmark+statmark THEN @rank_count
		WHEN @prev_value := bmathmark+lrmark+statmark THEN @rank_count := @rank_count + 1


		END AS rank
		FROM student_quiz_resultcaf1 where quiz_id ='$cat' and user_id  in
        (select username  from subscription_details 
        where package_id = 41)
		ORDER BY bmathmark+lrmark+statmark desc";
		
		}
		
		if ($batch=='PackageId42'){

        $sql = "INSERT into  quiz_rank
				select user_id,quiz_id ,bmathmark+lrmark+statmark, CASE
		WHEN @prev_value = bmathmark+lrmark+statmark THEN @rank_count
		WHEN @prev_value := bmathmark+lrmark+statmark THEN @rank_count := @rank_count + 1


		END AS rank
		FROM student_quiz_resultcaf1 where quiz_id ='$cat' and user_id  in
        (select username  from subscription_details 
        where package_id = 42)
		ORDER BY bmathmark+lrmark+statmark desc";
		
		}
		if ($batch=='PackageId26'){

        $sql = "INSERT into  quiz_rank
				select user_id,quiz_id ,bmathmark+lrmark+statmark, CASE
		WHEN @prev_value = bmathmark+lrmark+statmark THEN @rank_count
		WHEN @prev_value := bmathmark+lrmark+statmark THEN @rank_count := @rank_count + 1


		END AS rank
		FROM student_quiz_resultcaf1 where quiz_id ='$cat' and user_id  in
        (select username  from subscription_details 
        where package_id = 26)
		ORDER BY bmathmark+lrmark+statmark desc";
		
		}
		
		if ($batch=='54'){

        $sql = "INSERT into  quiz_rank
				select user_id,quiz_id ,bmathmark+lrmark+statmark, CASE
		WHEN @prev_value = bmathmark+lrmark+statmark THEN @rank_count
		WHEN @prev_value := bmathmark+lrmark+statmark THEN @rank_count := @rank_count + 1

		END AS rank
		FROM student_quiz_resultcaf1 where quiz_id ='$cat' and user_id  in
        (select username  from subscription_details 
        where package_id = 54)
		ORDER BY bmathmark+lrmark+statmark desc";
		
		}
		//print_r($sql);
        $query = $this->db->query($sql);

        $sql = "update student_quiz_resultcaf1 a inner join quiz_rank b
		on a.quiz_id = b.quiz_id
		set a.rank= b.rank
		where a.user_id = b.user_id
		and a.quiz_id = '$cat'";
        //   print_r($sql);
        $query = $this->db->query($sql);
		if ($batch=='all'){
				$sql = "SELECT a.*,(bmathmark+lrmark+statmark) total ,concat(b.first_name,' ',b.last_name) name,b.rollno,b.batch,b.accommodation,
				(select Quiz_name from quiz_info where id= '$cat') as Quiz_name from student_quiz_resultcaf1 a,user_details b 
				where a.user_id collate utf8_general_ci = b.user_name collate utf8_general_ci and   a.quiz_id ='$cat'
					 ORDER BY CONVERT( a.rank, SIGNED INTEGER )";
		}
		if ($batch=='JNANASUDHAIPU'){
				$sql = "SELECT a.*,(bmathmark+lrmark+statmark) total ,concat(b.first_name,' ',b.last_name) name,b.rollno,b.batch,b.accommodation,
				(select Quiz_name from quiz_info where id= '$cat') as Quiz_name from student_quiz_resultcaf1 a,user_details b 
				where a.user_id collate utf8_general_ci = b.user_name collate utf8_general_ci and   a.quiz_id ='$cat' and b.jnanasudhastandard='JNANASUDHAIPU'
					 ORDER BY CONVERT( a.rank, SIGNED INTEGER )";
		}
		if ($batch=='JNANASUDHAIIPU'){
				$sql = "SELECT a.*,(bmathmark+lrmark+statmark) total ,concat(b.first_name,' ',b.last_name) name,b.rollno,b.batch,b.accommodation,
				(select Quiz_name from quiz_info where id= '$cat') as Quiz_name from student_quiz_resultcaf1 a,user_details b 
				where a.user_id collate utf8_general_ci = b.user_name collate utf8_general_ci and   a.quiz_id ='$cat' and b.jnanasudhastandard='JNANASUDHAIIPU'
					 ORDER BY CONVERT( a.rank, SIGNED INTEGER )";
		}

		if ($batch=='PackageId7'){
				$sql = "SELECT a.*,(bmathmark+lrmark+statmark) total ,concat(b.first_name,' ',b.last_name) name, c.package_id,b.rollno,b.batch,b.accommodation,
						(select Quiz_name from quiz_info where id= '$cat') as Quiz_name
						from student_quiz_resultcaf1 a,user_details b,subscription_details c
						where a.user_id collate utf8_general_ci = b.user_name collate utf8_general_ci
						and c.username  = a.user_id and a.quiz_id ='$cat' and c.package_id='7'
					 ORDER BY CONVERT( a.rank, SIGNED INTEGER )";
		}
		if ($batch=='PackageId8'){
				$sql = "SELECT a.*,(bmathmark+lrmark+statmark) total ,concat(b.first_name,' ',b.last_name) name, c.package_id,b.rollno,b.batch,b.accommodation,
						(select Quiz_name from quiz_info where id= '$cat') as Quiz_name
						from student_quiz_resultcaf1 a,user_details b,subscription_details c
						where a.user_id collate utf8_general_ci = b.user_name collate utf8_general_ci
						and c.username  = a.user_id and a.quiz_id ='$cat' and c.package_id='8'
					 ORDER BY CONVERT( a.rank, SIGNED INTEGER )";
		}
		if ($batch=='PackageId41'){
				$sql = "SELECT a.*,(bmathmark+lrmark+statmark) total ,concat(b.first_name,' ',b.last_name) name, c.package_id,b.rollno,b.batch,b.accommodation,
						(select Quiz_name from quiz_info where id= '$cat') as Quiz_name
						from student_quiz_resultcaf1 a,user_details b,subscription_details c
						where a.user_id collate utf8_general_ci = b.user_name collate utf8_general_ci
						and c.username  = a.user_id and a.quiz_id ='$cat' and c.package_id='41'
					 ORDER BY CONVERT( a.rank, SIGNED INTEGER )";
		}
		if ($batch=='PackageId42'){
				$sql = "SELECT a.*,(bmathmark+lrmark+statmark) total ,concat(b.first_name,' ',b.last_name) name, c.package_id,b.rollno,b.batch,b.accommodation,
						(select Quiz_name from quiz_info where id= '$cat') as Quiz_name
						from student_quiz_resultcaf1 a,user_details b,subscription_details c
						where a.user_id collate utf8_general_ci = b.user_name collate utf8_general_ci
						and c.username  = a.user_id and a.quiz_id ='$cat' and c.package_id='42'
					 ORDER BY CONVERT( a.rank, SIGNED INTEGER )";
		}

		if ($batch=='PackageId26'){
				$sql = "SELECT a.*,(bmathmark+lrmark+statmark) total ,concat(b.first_name,' ',b.last_name) name, c.package_id,b.rollno,b.batch,b.accommodation
						from student_quiz_resultcaf1 a,user_details b,subscription_details c
						where a.user_id collate utf8_general_ci = b.user_name collate utf8_general_ci
						and c.username  = a.user_id and a.quiz_id ='$cat' and c.package_id='26'
					 ORDER BY CONVERT( a.rank, SIGNED INTEGER )";
		}
	if ($batch=='54'){
				$sql = "SELECT a.*,(bmathmark+lrmark+statmark) total ,concat(b.first_name,' ',b.last_name) name, c.package_id,b.rollno,b.batch,b.accommodation,
						(select Quiz_name from quiz_info where id= '$cat') as Quiz_name
						from student_quiz_resultcaf1 a,user_details b,subscription_details c
						where a.user_id collate utf8_general_ci = b.user_name collate utf8_general_ci
						and c.username  = a.user_id and a.quiz_id ='$cat' and c.package_id='54'
					 ORDER BY CONVERT( a.rank, SIGNED INTEGER )";
		}


       //     print_r($sql);
        $query = $this->db->query($sql);
        try {
            if ($query->num_rows() > 0) {
                return $query->result_array();
            } else {
                return $query->result_array();
            }

        } catch (exception $e) {echo $e->getMessage();
        }

    }

		
public function getallrankold($cat,$batch)
    {
        $this->load->library('nativesession');

        $sql = "truncate table quiz_rank";
        $query = $this->db->query($sql);
        $sql = "SET @prev_value = NULL";
        $query = $this->db->query($sql);
        $sql = "SET @rank_count = 0";
        $query = $this->db->query($sql);
		if ($batch=='all'){

        $sql = "INSERT into  quiz_rank
				select user_id,quiz_id ,physicsmark+chemistrymark+biologymark, CASE
		WHEN @prev_value = physicsmark+chemistrymark+biologymark THEN @rank_count
		WHEN @prev_value := physicsmark+chemistrymark+biologymark THEN @rank_count := @rank_count + 1

		END AS rank
		FROM student_quiz_result where quiz_id ='$cat'
		ORDER BY physicsmark+chemistrymark+biologymark desc";
		
		}
		if ($batch=='JNANASUDHAIPU'){

        $sql = "INSERT into  quiz_rank
				select user_id,quiz_id ,physicsmark+chemistrymark+biologymark, CASE
		WHEN @prev_value = physicsmark+chemistrymark+biologymark THEN @rank_count
		WHEN @prev_value := physicsmark+chemistrymark+biologymark THEN @rank_count := @rank_count + 1

		END AS rank
		FROM student_quiz_result where quiz_id ='$cat' and user_id collate utf8_general_ci in
        (select USER_NAME collate utf8_general_ci from user_details 
        where jnanasudhastandard = 'JNANASUDHAIPU')
		ORDER BY physicsmark+chemistrymark+biologymark desc";
		
		}
		if ($batch=='JNANASUDHAIIPU'){

        $sql = "INSERT into  quiz_rank
				select user_id,quiz_id ,physicsmark+chemistrymark+biologymark, CASE
		WHEN @prev_value = physicsmark+chemistrymark+biologymark THEN @rank_count
		WHEN @prev_value := physicsmark+chemistrymark+biologymark THEN @rank_count := @rank_count + 1

		END AS rank
		FROM student_quiz_result where quiz_id ='$cat' and user_id collate utf8_general_ci in
        (select USER_NAME collate utf8_general_ci from user_details 
        where jnanasudhastandard = 'JNANASUDHAIIPU')
		ORDER BY physicsmark+chemistrymark+biologymark desc";
		
		}
		 if ($batch=='PackageId7'){

        $sql = "INSERT into  quiz_rank
				select user_id,quiz_id ,physicsmark+chemistrymark+biologymark, CASE
		WHEN @prev_value = physicsmark+chemistrymark+biologymark THEN @rank_count
		WHEN @prev_value := physicsmark+chemistrymark+biologymark THEN @rank_count := @rank_count + 1

		END AS rank
		FROM student_quiz_result where quiz_id ='$cat' and user_id  in
        (select username  from subscription_details 
        where package_id = 7)
		ORDER BY physicsmark+chemistrymark+biologymark desc";
		
		}
		 if ($batch=='PackageId8'){

        $sql = "INSERT into  quiz_rank
				select user_id,quiz_id ,physicsmark+chemistrymark+biologymark, CASE
		WHEN @prev_value = physicsmark+chemistrymark+biologymark THEN @rank_count
		WHEN @prev_value := physicsmark+chemistrymark+biologymark THEN @rank_count := @rank_count + 1

		END AS rank
		FROM student_quiz_result where quiz_id ='$cat' and user_id  in
        (select username  from subscription_details 
        where package_id = 8)
		ORDER BY physicsmark+chemistrymark+biologymark desc";
		
		}
		
		if ($batch=='PackageId41'){

        $sql = "INSERT into  quiz_rank
				select user_id,quiz_id ,physicsmark+chemistrymark+biologymark, CASE
		WHEN @prev_value = physicsmark+chemistrymark+biologymark THEN @rank_count
		WHEN @prev_value := physicsmark+chemistrymark+biologymark THEN @rank_count := @rank_count + 1

		END AS rank
		FROM student_quiz_result where quiz_id ='$cat' and user_id  in
        (select username  from subscription_details 
        where package_id = 41)
		ORDER BY physicsmark+chemistrymark+biologymark desc";
		
		}
		
		if ($batch=='PackageId42'){

        $sql = "INSERT into  quiz_rank
				select user_id,quiz_id ,physicsmark+chemistrymark+biologymark, CASE
		WHEN @prev_value = physicsmark+chemistrymark+biologymark THEN @rank_count
		WHEN @prev_value := physicsmark+chemistrymark+biologymark THEN @rank_count := @rank_count + 1

		END AS rank
		FROM student_quiz_result where quiz_id ='$cat' and user_id  in
        (select username  from subscription_details 
        where package_id = 42)
		ORDER BY physicsmark+chemistrymark+biologymark desc";
		
		}
		if ($batch=='PackageId26'){

        $sql = "INSERT into  quiz_rank
				select user_id,quiz_id ,physicsmark+chemistrymark+biologymark, CASE
		WHEN @prev_value = physicsmark+chemistrymark+biologymark THEN @rank_count
		WHEN @prev_value := physicsmark+chemistrymark+biologymark THEN @rank_count := @rank_count + 1

		END AS rank
		FROM student_quiz_result where quiz_id ='$cat' and user_id  in
        (select username  from subscription_details 
        where package_id = 26)
		ORDER BY physicsmark+chemistrymark+biologymark desc";
		
		}
		
		if ($batch=='54'){

        $sql = "INSERT into  quiz_rank
				select user_id,quiz_id ,physicsmark+chemistrymark+biologymark, CASE
		WHEN @prev_value = physicsmark+chemistrymark+biologymark THEN @rank_count
		WHEN @prev_value := physicsmark+chemistrymark+biologymark THEN @rank_count := @rank_count + 1

		END AS rank
		FROM student_quiz_result where quiz_id ='$cat' and user_id  in
        (select username  from subscription_details 
        where package_id = 54)
		ORDER BY physicsmark+chemistrymark+biologymark desc";
		
		}
		print_r($sql);
        $query = $this->db->query($sql);

        $sql = "update student_quiz_result a inner join quiz_rank b
		on a.quiz_id = b.quiz_id
		set a.rank= b.rank
		where a.user_id = b.user_id
		and a.quiz_id = '$cat'";
           print_r($sql);
        $query = $this->db->query($sql);
		if ($batch=='all'){
				$sql = "SELECT a.*,(a.physicsmark+a.chemistrymark+a.biologymark) total ,concat(b.first_name,' ',b.last_name) name,b.rollno,b.batch,b.accommodation,
				(select Quiz_name from quiz_info where id= '$cat') as Quiz_name from student_quiz_result a,user_details b 
				where a.user_id collate utf8_general_ci = b.user_name collate utf8_general_ci and   a.quiz_id ='$cat'
					 ORDER BY CONVERT( a.rank, SIGNED INTEGER )";
		}
		if ($batch=='JNANASUDHAIPU'){
				$sql = "SELECT a.*,(a.physicsmark+a.chemistrymark+a.biologymark) total ,concat(b.first_name,' ',b.last_name) name,b.rollno,b.batch,b.accommodation,
				(select Quiz_name from quiz_info where id= '$cat') as Quiz_name from student_quiz_result a,user_details b 
				where a.user_id collate utf8_general_ci = b.user_name collate utf8_general_ci and   a.quiz_id ='$cat' and b.jnanasudhastandard='JNANASUDHAIPU'
					 ORDER BY CONVERT( a.rank, SIGNED INTEGER )";
		}
		if ($batch=='JNANASUDHAIIPU'){
				$sql = "SELECT a.*,(a.physicsmark+a.chemistrymark+a.biologymark) total ,concat(b.first_name,' ',b.last_name) name,b.rollno,b.batch,b.accommodation,
				(select Quiz_name from quiz_info where id= '$cat') as Quiz_name from student_quiz_result a,user_details b 
				where a.user_id collate utf8_general_ci = b.user_name collate utf8_general_ci and   a.quiz_id ='$cat' and b.jnanasudhastandard='JNANASUDHAIIPU'
					 ORDER BY CONVERT( a.rank, SIGNED INTEGER )";
		}

		if ($batch=='PackageId7'){
				$sql = "SELECT a.*,(a.physicsmark+a.chemistrymark+a.biologymark) total ,concat(b.first_name,' ',b.last_name) name, c.package_id,b.rollno,b.batch,b.accommodation,
						(select Quiz_name from quiz_info where id= '$cat') as Quiz_name
						from student_quiz_result a,user_details b,subscription_details c
						where a.user_id collate utf8_general_ci = b.user_name collate utf8_general_ci
						and c.username  = a.user_id and a.quiz_id ='$cat' and c.package_id='7'
					 ORDER BY CONVERT( a.rank, SIGNED INTEGER )";
		}
		if ($batch=='PackageId8'){
				$sql = "SELECT a.*,(a.physicsmark+a.chemistrymark+a.biologymark) total ,concat(b.first_name,' ',b.last_name) name, c.package_id,b.rollno,b.batch,b.accommodation,
						(select Quiz_name from quiz_info where id= '$cat') as Quiz_name
						from student_quiz_result a,user_details b,subscription_details c
						where a.user_id collate utf8_general_ci = b.user_name collate utf8_general_ci
						and c.username  = a.user_id and a.quiz_id ='$cat' and c.package_id='8'
					 ORDER BY CONVERT( a.rank, SIGNED INTEGER )";
		}
		if ($batch=='PackageId41'){
				$sql = "SELECT a.*,(a.physicsmark+a.chemistrymark+a.biologymark) total ,concat(b.first_name,' ',b.last_name) name, c.package_id,b.rollno,b.batch,b.accommodation,
						(select Quiz_name from quiz_info where id= '$cat') as Quiz_name
						from student_quiz_result a,user_details b,subscription_details c
						where a.user_id collate utf8_general_ci = b.user_name collate utf8_general_ci
						and c.username  = a.user_id and a.quiz_id ='$cat' and c.package_id='41'
					 ORDER BY CONVERT( a.rank, SIGNED INTEGER )";
		}
		if ($batch=='PackageId42'){
				$sql = "SELECT a.*,(a.physicsmark+a.chemistrymark+a.biologymark) total ,concat(b.first_name,' ',b.last_name) name, c.package_id,b.rollno,b.batch,b.accommodation,
						(select Quiz_name from quiz_info where id= '$cat') as Quiz_name
						from student_quiz_result a,user_details b,subscription_details c
						where a.user_id collate utf8_general_ci = b.user_name collate utf8_general_ci
						and c.username  = a.user_id and a.quiz_id ='$cat' and c.package_id='42'
					 ORDER BY CONVERT( a.rank, SIGNED INTEGER )";
		}

		if ($batch=='PackageId26'){
				$sql = "SELECT a.*,(a.physicsmark+a.chemistrymark+a.biologymark) total ,concat(b.first_name,' ',b.last_name) name, c.package_id,b.rollno,b.batch,b.accommodation
						from student_quiz_result a,user_details b,subscription_details c
						where a.user_id collate utf8_general_ci = b.user_name collate utf8_general_ci
						and c.username  = a.user_id and a.quiz_id ='$cat' and c.package_id='26'
					 ORDER BY CONVERT( a.rank, SIGNED INTEGER )";
		}
	if ($batch=='54'){
				$sql = "SELECT a.*,(a.physicsmark+a.chemistrymark+a.biologymark) total ,concat(b.first_name,' ',b.last_name) name, c.package_id,b.rollno,b.batch,b.accommodation,
						(select Quiz_name from quiz_info where id= '$cat') as Quiz_name
						from student_quiz_result a,user_details b,subscription_details c
						where a.user_id collate utf8_general_ci = b.user_name collate utf8_general_ci
						and c.username  = a.user_id and a.quiz_id ='$cat' and c.package_id='54'
					 ORDER BY CONVERT( a.rank, SIGNED INTEGER )";
		}


            print_r($sql);
        $query = $this->db->query($sql);
        try {
            if ($query->num_rows() > 0) {
                return $query->result_array();
            } else {
                return $query->result_array();
            }

        } catch (exception $e) {echo $e->getMessage();
        }

    }
		
		
		public function getallrank($cat, $batch)
		{
			$db = get_db();

			// Create temporary table (drop first to avoid stale state)
			$db->query("DROP TEMPORARY TABLE IF EXISTS temp_ranks");
			$db->query("CREATE TEMPORARY TABLE temp_ranks (
				id          INT AUTO_INCREMENT,
				user_id     VARCHAR(255),
				quiz_id     VARCHAR(255),
				total_marks INT,
				biologymark INT,
				full_name   VARCHAR(255),
				row_rank    INT,
				PRIMARY KEY (id)
			) ENGINE=MEMORY");

			// Populate with sorted data
			$db->query("INSERT INTO temp_ranks (user_id, quiz_id, total_marks, biologymark, full_name, row_rank)
				SELECT
					a.user_id,
					a.quiz_id,
					(COALESCE(a.physicsmark,0)+COALESCE(a.chemistrymark,0)+COALESCE(a.biologymark,0)) AS total_marks,
					COALESCE(a.biologymark,0) AS biologymark,
					CONCAT(COALESCE(b.first_name,''),' ',COALESCE(b.last_name,'')) AS full_name,
					0
				FROM student_quiz_result a
				INNER JOIN user_details b
					ON a.user_id COLLATE utf8_general_ci = b.user_name COLLATE utf8_general_ci
				WHERE a.quiz_id = '" . $db->escape($cat) . "'
				ORDER BY
					(COALESCE(a.physicsmark,0)+COALESCE(a.chemistrymark,0)+COALESCE(a.biologymark,0)) DESC,
					COALESCE(a.biologymark,0) DESC,
					CONCAT(COALESCE(b.first_name,''),' ',COALESCE(b.last_name,'')) ASC");

			// Write rank back to student_quiz_result using temp row id as rank
			$db->query("UPDATE student_quiz_result a
				INNER JOIN temp_ranks b ON a.user_id = b.user_id AND a.quiz_id = b.quiz_id
				SET a.rank = b.id
				WHERE a.quiz_id = '" . $db->escape($cat) . "'");

			// Drop temp table
			$db->query("DROP TEMPORARY TABLE IF EXISTS temp_ranks");

			// Final select
			$sql = "SELECT a.*,
					(a.physicsmark+a.chemistrymark+a.biologymark) total,
					CONCAT(b.first_name,' ',b.last_name) name,
					b.rollno, b.batch, b.accommodation, b.phone,
					(SELECT Quiz_name FROM quiz_info WHERE id='" . $db->escape($cat) . "') AS Quiz_name
				FROM student_quiz_result a, user_details b
				WHERE a.user_id COLLATE utf8_general_ci = b.user_name COLLATE utf8_general_ci
				  AND a.quiz_id = '" . $db->escape($cat) . "'
				ORDER BY CONVERT(a.rank, SIGNED INTEGER)";

			$query = $db->query($sql);
			return $query ? $db->get_result($query) : array();
		}
    public function insertquizquestionupload($arrInsertData)
	{
		 $this->db->insert('quiz_temp_question',$arrInsertData);
			 $insertId = $this->db->insert_id();
			 //print_r($insertId);
			 return $insertId;
	}  
	public function insertquizanswerupload($arrInsertData)
	{
		//print_r($arrInsertData);
		$sql=$this->db->insert('quiz_temp_answers',$arrInsertData);
			//print_r($sql);
			$insertId = $this->db->insert_id();
			// print_r($insertId);

			return $insertId;
	} 
	
	public function get_allquiz_upload_id()
  	{
		$sql="SELECT DISTINCT quiz_id from question_upload";
		$query=$this->db->query($sql);
		return $query->result_array();
   	}
	
	public function get_allquiz_upload()
  	{
		$sql="SELECT * from question_upload";
		$query=$this->db->query($sql);
		return $query->result_array();
   	}	
	
	public function get_questionby_categoryandsubject($subject_name,$category)
	{
		$sql="SELECT a.discription,a.correctoption,a.id, a.question_name, b.question_answer
        FROM quiz_question a,quiz_question_answers b
        WHERE a.id = b.Qustion_no and a.category='$category';";
		//print_r($sql);
		$qurey=$this->db->query($sql);
		return $qurey->result_array();
	}
	
	public function findAllForquizfreesub() 
	{
		$sql="SELECT * FROM free_subscription_login";
		$query = $this->db->query($sql);

		if ($query->num_rows() == 1)
		{
		return $query->result_array();
		}
		elseif ($query->num_rows() > 1)
		{
		return $query->result_array(); //This returns an array of results which you can whatever you need with it
		}
		else
		{
		return $query->result_array();
		}
	}
	public function deleterow($id)
	{ 
		//Print_r($id);
		$sql="INSERT INTO `freesubscription_copy` SELECT * FROM `free_subscription_login` WHERE `id` = '$id'";
		$query = $this->db->query($sql);
		//print_r($sql);

		$sql = "DELETE FROM free_subscription_login where id = '$id'";

		$query = $this->db->query($sql);
	}

	public function savediffrecords()
	{
		$difficulty_level=$this->input->post('difficulty_level');
	   // $quizid=$this->input->post('quizid');
		print_r($difficulty_level);
		//echo "hii";
	   $data=array();
		foreach ($_POST['difficulty_level'] as $key => $value){
		 // if($value=="1") {
		
		 $data[] = array(  
		 'difficulty_level' =>$_POST['difficulty_level'][$key],
		//'id' => $_POST['quizid'] [$key],
       // 'end_date' => $_POST['enddate'][$key],
        //'attachment' => $_POST['attachment'][$key]    
                     );
		// } 
		 }
			//print_r($data);
		$this->db->where('id=',$difficulty_level);
		$this->db->insert_batch('quiz_question',$data);
	}		
		// $difficulty_level= $this->input->post(difficulty_level);
		// $i = 0;
		// Foreach($difficulty_level as $key=>$val)
		// {
			  // $data[$i]['difficulty_level'] = $val;
			 // $data[$i]['question'] = $question[$key];
			  // $data[$i]['option1'] = $record[$key];
			  // $i++;
		// }
		//	$this->db->insert_batch('quiz_question', $data);
		// $data = array();
		// foreach ($_POST['difficulty_level'] as $key => $value){
		  // if($value=="-1") {
		
		 // $data[] = array(  
		// 'id' =>$_POST['id'][$key],
        // 'difficulty_level' => $_POST['difficulty_level'][$key]    
                     // );
		 // } 
		 // }
		// print_r($_POST['difficulty_level']); 
		// print_r($data);		
		
        // $query = $this->db->insert_batch('quiz_question',$data);
        // if ($query) {
            // return true;} 
        // else {
        // return false;}
    
	public function get_statusondateandstatus($fromdate,$todate,$Status,$package_id) 
	{ 
		$from_date=$fromdate;
		$fromdate=date('Y-m-d',strtotime($from_date));
		$to_date=$todate;
		$todate=date('Y-m-d',strtotime($to_date));
	
		if($Status == '0' && $package_id=='0'){
			$sql="SELECT * FROM payment_gateway_status WHERE order_no > 0 and date(datetime) >= '$fromdate' AND date(datetime) <= '$todate'";
			
		}
		elseif($Status == '0' && $package_id!='0')
		{
			$sql="SELECT * FROM payment_gateway_status WHERE order_no > 0 and date(datetime) >= '$fromdate' AND date(datetime) <= '$todate' and packageid = '$package_id'";
			
		}
		elseif($Status != '0' && $package_id =='0')
		{
			$sql="SELECT * FROM payment_gateway_status WHERE order_no > 0 and date(datetime) >= '$fromdate' AND date(datetime) <= '$todate' and status like '$Status%'";
			
		}else{
		$sql="SELECT *
				FROM payment_gateway_status
			
				WHERE order_no > 0 and status='$Status%' and date(datetime) >= '$fromdate' AND date(datetime) <= '$todate' and packageid='$package_id'";	
				
		}
	//	print_r($sql);
		$qurey=$this->db->query($sql);
		//print_r($qurey);
		return $qurey->result_array();

	}		
	
	public function get_user_details($role_id)
	{ 

		if ($role_id ==1){

		$sql="select * from user_details where  user_status = '1' and user_name in (select username from subscription_details where package_id =1)";

		}
		if ($role_id ==2){

		$sql="select * from user_details where user_name in (select username from subscription_details where package_id =2)";

		}
		if ($role_id ==3){

		$sql="select * from user_details where user_name in (select username from subscription_details where package_id =3 and ifnull(coupon_no,'*')  ='*')";

		}
		if ($role_id ==4){

		$sql="select * from user_details where user_name in (select username from subscription_details where package_id =3 and ifnull(coupon_no,'*')  !='*')";

		}
		if ($role_id ==5){

		$sql="select * from user_details where user_name in (select username from subscription_details where package_id =8)";

		}
		//print_r($sql);
		$qurey=$this->db->query($sql);
		return $qurey->result_array();
	}	

	public function findallsubscriptiondetails() 
	{
		$sql = "SELECT a.*,b.* from user_details a left outer join subscription_details
		b on a.user_name= b.username 
		where a.role_id='2'
		and ifnull(a.email,'*') != '*' and a.user_name collate utf8_general_ci in (select mobile_no from payment_gateway_status where status ='Successful') ";
		//	print_r($sql);
		$query = $this->db->query($sql);

			if ($query->num_rows() == 1)
			{
				return $query->row();
			}
			elseif ($query->num_rows() > 1)
			{
			   return $query->result_array(); //This returns an array of results which you can whatever you need with it
			}
			else 
			{
				return $query->result_array();
			}
	}
	public function get_sub_details($role_id)
	{ 
		if ($role_id ==1){

		$sql="SELECT a.*,b.* from user_details a left outer join subscription_details b on a.user_name= b.username where package_id =1";
		//print_r($sql);
		}
		if ($role_id ==2){

		$sql="SELECT a.*,b.* from user_details a left outer join subscription_details b on a.user_name= b.username where package_id =2";

		}
		if ($role_id ==3){

		$sql="SELECT a.*,b.* from user_details a left outer join subscription_details b on a.user_name= b.username where package_id =3 and ifnull(coupon_no,'*')  ='*' and a.jnanasudhastandard='JNANASUDHAIPU' ";

		}
		if ($role_id ==4){

		$sql="SELECT a.*,b.* from user_details a left outer join subscription_details b on a.user_name= b.username where package_id =3 and ifnull(coupon_no,'*')  ='*' and a.jnanasudhastandard='OTHER'";

		}
		if ($role_id ==5){

		$sql="SELECT a.*,b.* from user_details a left outer join subscription_details b on a.user_name= b.username where package_id =3 and ifnull(coupon_no,'*')  !='*'";

		}
		if ($role_id ==6){

		$sql="SELECT a.*,b.* from user_details a left outer join subscription_details b on a.user_name= b.username where package_id =6 and ifnull(coupon_no,'*')  ='*'";

		}
			if ($role_id ==7){

		$sql="SELECT a.*,b.* from user_details a left outer join subscription_details b on a.user_name= b.username where package_id =8";

		}
			if ($role_id ==8){

		$sql="SELECT a.*,b.* from user_details a left outer join subscription_details b on a.user_name= b.username where package_id =7";

		}
		//print_r($sql);
		$qurey=$this->db->query($sql);
		return $qurey->result_array();
	}

	public function get_student_details($user_id,$package_id)
	{
		$sql="SELECT a.*,b.* from user_details a left outer join subscription_details b on a.user_name= b.username where package_id ='$package_id' and user_id='$user_id'";
		$query = $this->db->query($sql);
		if ($query->num_rows() == 1)
		{
			return $query->result_array();
		}
		elseif ($query->num_rows() > 1)
		{
			return $query->result_array(); //This returns an array of results which you can whatever you need with it
		}
		else 
		{
			return $query->result_array();
		}
		
	}
	public function get_studentrankdetails($user_id)
	{
		//print_r($user_id);a.user_id collate utf8_general_ci = b.user_name collate utf8_general_ci
		//$sql="SELECT a.*,(a.physicsmark+a.chemistrymark+a.biologymark) total ,concat(b.first_name,' ',b.last_name) name from student_quiz_result a,user_details b  where a.user_id collate utf8_general_ci ='$user_id'";
		$sql="SELECT distinct b.*, (b.physicsmark+b.chemistrymark+b.biologymark) total,concat(a.first_name,' ',a.last_name) name  from user_details a  join student_quiz_result b on a.user_name collate utf8_general_ci= b.user_id collate utf8_general_ci where b.user_id='$user_id'";
		$query = $this->db->query($sql);
		//print_r($sql);
		if ($query->num_rows() == 1)
		{
			return $query->result_array();
		}
		elseif ($query->num_rows() > 1)
		{
			return $query->result_array(); //This returns an array of results which you can whatever you need with it
		}
		else 
		{
			return $query->result_array();
		}
		
	}
	public function get_userpackagedetails($user_id)
	{
		
		$sql1="SELECT * FROM user_details where user_id='$user_id'";
			$query = $this->db->query($sql1);
		if ($query->num_rows() == 1)
		{
			return $query->result_array();
		}
		elseif ($query->num_rows() > 1)
		{
			return $query->result_array(); //This returns an array of results which you can whatever you need with it
		}
		else 
		{
			return $query->result_array();
		}
		
	}
	
	public function packageinfo()
	{
			$sql="SELECT * FROM package_info where status='ACTIVE'";
		//	print_r($sql);
			$query = $this->db->query($sql);

		if ($query->num_rows() == 1)
		{
			return $query->result_array();

		}
		elseif ($query->num_rows() > 1)
		{

		   return $query->result_array(); //This returns an array of results which you can whatever you need with it
		}
		else 
		{
			return $query->result_array();
		}

	}
	
	public function insertuserdetails()
	{
		$user_id=$this->input->post('user_id');
		$user_name=$this->input->post('user_name');
		$actual_password=$this->input->post('actual_password');
		$first_name=$this->input->post('first_name');
		$last_name=$this->input->post('last_name');
		$phone= $this->input->post('phone');
		$email = $this->input->post('email');
		$package_id = $this->input->post('package_id');
		$package_name = $this->input->post('package_name');
		$price = $this->input->post('price');
		$order_no=$this->input->post('order_no');
		$pgref_no=$this->input->post('pgref_no');
		$payment_ref_no=$this->input->post('payment_ref_no');
		
		$sql="INSERT INTO `subscription_details`(`org_id`, `name`, `username`,`phone_no`, `email`, `password`,`package_id`,`package_name`,`package_amount`) VALUES
		('1','$first_name.' '.$last_name','$user_name','$phone','$email','$actual_password','$package_id','$package_name','$price')";
		//print_r($sql);
		$query = $this->db->query($sql);
		
		$sql="UPDATE `payment_gateway_status` SET `order_no`='$order_no' , status='Successful',	`payment_refno` ='$payment_ref_no' ,`pg_refno`='$pgref_no'
		WHERE mobile_no='$user_name'" ;
		print_r($sql);
		$query = $this->db->query($sql);
	}
				
	public function saveinforecords(){
	// $attachment=$this->input->post('attachment');
	// $user_name=$this->input->post('user_name');
	$data = array();
	foreach ($_POST['attachment'] as $key => $value){
	if($value=="JnanaSudhaIPU" || $value=="JnanaSudhaIIPU" || $value=="Other" ) {
	 $data[] = array(  
	 'attachment' =>$_POST['attachment'][$key],
	'user_name' => $_POST['user_name'] [$key]
	 );
		 }
	 }
	//print_r($data);
	$query = $this->db->insert_batch('js_student_or_not', $data);
	if ($query) {
		return true;} 
	else {
	return false;}
	}

	public function getquizinfo(){
		$sql="SELECT DISTINCT Quiz_name,id from  quiz_info";
		$query = $this->db->query($sql);

		if ($query->num_rows() == 1)
		{
			return $query->result_array();
		}
		elseif ($query->num_rows() > 1)
		{
			return $query->result_array(); //This returns an array of results which you can whatever you need with it
		}
		else 
		{
			return $query->result_array();
		}	
			
	}
	public function saveclearres($quiz_id,$user_name){
		$sql="insert into student_temp_response_deleted select * from student_temp_response where user_name = '$user_name' and quiz_id ='$quiz_id'";
		$this->db->query($sql);
		// print_r($sql);
		$sql="insert into quiz_answer_deleted select * from quiz_answer where user_name = '$user_name' and quiz_id ='$quiz_id'";
		$this->db->query($sql);
		//print_r($sql);
		$sql="insert into student_temp_response_details_deleted select * from student_temp_response_details where user_id  = ('$user_name') and quiz_id ='$quiz_id'";
		$this->db->query($sql);
		//print_r($sql);
		$sql="insert into student_attempted_quiz_deleted select * from student_attempted_quiz where user_id  = ('$user_name') and quiz_id ='$quiz_id'";
		$this->db->query($sql);
		//print_r($sql);
		$sql="insert into student_final_answer_deleted select * from student_final_answer where user_id  = ('$user_name') and quiz_id ='$quiz_id'";
		$this->db->query($sql);
		//print_r($sql);
		$sql="insert into student_quiz_result_deleted select * from student_quiz_result where user_id  = ('$user_name') and quiz_id ='$quiz_id'";
		$this->db->query($sql);
		//print_r($sql);
		$sql="delete from student_temp_response where user_name = '$user_name' and quiz_id ='$quiz_id'";
		$this->db->query($sql);
		//print_r($sql);
		$sql="delete from quiz_answer where user_name = ('$user_name') and quiz_id ='$quiz_id'";
		$this->db->query($sql);
		//print_r($sql);
		$sql="delete from student_temp_response_details where user_id  = ('$user_name') and quiz_id ='$quiz_id'";
		$this->db->query($sql);
		//print_r($sql);
		$sql="delete from student_attempted_quiz where user_id  = ('$user_name') and quiz_id ='$quiz_id'";
		$this->db->query($sql);
		//print_r($sql);
		$sql="delete from student_final_answer where user_id  = ('$user_name') and quiz_id ='$quiz_id'";
		$this->db->query($sql);
		//print_r($sql);
		$sql="delete from student_quiz_result where user_id  = ('$user_name') and quiz_id ='$quiz_id'";
		$this->db->query($sql);
		$sql="delete from student_quiz_result_kvpy where user_id  = ('$user_name') and quiz_id ='$quiz_id'";
		$this->db->query($sql);
		
		$sql="delete from student_quiz_result_neet2021 where user_id  = ('$user_name') and quiz_id ='$quiz_id'";
		$this->db->query($sql);
		
		$sql="delete from student_quiz_resultcaf where user_id  = ('$user_name') and quiz_id ='$quiz_id'";
		$this->db->query($sql);
		
		$sql="delete from student_quiz_resultcaf1 where user_id  = ('$user_name') and quiz_id ='$quiz_id'";
		$this->db->query($sql);
		
		$sql="delete from student_quiz_resultcaf2 where user_id  = ('$user_name') and quiz_id ='$quiz_id'";
		$this->db->query($sql);
		
		$sql="delete from student_quiz_resultcafshort1 where user_id  = ('$user_name') and quiz_id ='$quiz_id'";
		$this->db->query($sql);
		
		$sql="delete from student_quiz_resultcafshort2 where user_id  = ('$user_name') and quiz_id ='$quiz_id'";
		$this->db->query($sql);
		//print_r($sql);student_quiz_resultcafshort2 student_quiz_result_foundation
		$sql="delete from student_quiz_result_foundation where user_id  = ('$user_name') and quiz_id ='$quiz_id'";
		$this->db->query($sql);
		$sql="delete from student_quiz_result_cs2024 where user_id  = ('$user_name') and quiz_id ='$quiz_id'";
		$this->db->query($sql);
	}
	
	public function saveresume($quiz_id,$user_name)
	{
		$sql="insert into quiz_answer_deleted select * from quiz_answer where user_name = '$user_name' and quiz_id ='$quiz_id'";
		$this->db->query($sql);
		// print_r($sql);
		$sql="insert into student_temp_response_details_deleted select * from student_temp_response_details where user_id  = '$user_name' and quiz_id ='$quiz_id'";
		$this->db->query($sql);
		//print_r($sql);
		$sql="insert into student_attempted_quiz_deleted select * from student_attempted_quiz where user_id  = ('$user_name') and quiz_id ='$quiz_id'";
		$this->db->query($sql);
		//print_r($sql);
		$sql="insert into student_final_answer_deleted select * from student_final_answer where user_id  =('$user_name') and quiz_id ='$quiz_id'";
		$this->db->query($sql);
		//print_r($sql);
		$sql="insert into student_quiz_result_deleted select * from student_quiz_result where user_id  =('$user_name') and quiz_id ='$quiz_id'";
		$this->db->query($sql);
		//print_r($sql);
		$sql="delete from quiz_answer where user_name = ('$user_name') and quiz_id ='$quiz_id'";
		$this->db->query($sql);
		//print_r($sql);
		$sql="delete from student_temp_response_details where user_id  = ('$user_name') and quiz_id ='$quiz_id'";
		$this->db->query($sql);
		//print_r($sql);
		$sql="delete from student_attempted_quiz where user_id  = ('$user_name') and quiz_id ='$quiz_id'";
		$this->db->query($sql);
		//print_r($sql);
		$sql="delete from student_final_answer where user_id  = ('$user_name') and quiz_id ='$quiz_id'";
		$this->db->query($sql);
		//print_r($sql);
		$sql="delete from student_quiz_result where user_id  = ('$user_name') and quiz_id ='$quiz_id'";
		$this->db->query($sql);
		$sql="delete from student_quiz_result_kvpy where user_id  = ('$user_name') and quiz_id ='$quiz_id'";
		$this->db->query($sql);
		//print_r($sql); student_quiz_result_foundation
		$sql="delete from student_quiz_result_neet2021 where user_id  = ('$user_name') and quiz_id ='$quiz_id'";
		$this->db->query($sql);
		
		$sql="delete from student_quiz_result_foundation where user_id  = ('$user_name') and quiz_id ='$quiz_id'";
		$this->db->query($sql);
		
		$sql="UPDATE student_temp_response SET status ='WIP' WHERE  user_name = '$user_name' and quiz_id ='$quiz_id'";
		$this->db->query($sql);
		//print_r($sql);
		
	}

public function showresultForquiz($quiz_id,$user_name)
	{
		$sql = "select distinct st_id from student_final_answer where user_id = '$user_name' and quiz_id = '$quiz_id'";
        //print_r($sql);
        $query = $this->db->query($sql);
		$id = $query->row();
		$quiz_type = $id->st_id;// will echo only id one time
		if($quiz_type=="JUT")
		{
			$sql="select '' id,
			'$user_name' as user_id,
			'$quiz_id' as quiz_id,
			'' as st_id,
			(select count(*) physicsattempted from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 1 and 45 and sellectedans != '') physicsattempted,
			(select count(*) chemistryattempted from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 46 and 90 and sellectedans != '') chemistryattempted,
			(select count(*) biologyattempted from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 91 and 180 and sellectedans != '') biologyattempted,

			(select count(*) physicscorrect from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 1 and 45 and sellectedans = correctans) physicscorrect,
			(select count(*) chemistrycorrect from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 46 and 90 and sellectedans = correctans) chemistrycorrect,
			(select count(*) biologycorrect from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 91 and 180 and sellectedans = correctans) biologycorrect,

			(select count(*) physicswrong from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 1 and 45 and sellectedans <> correctans and sellectedans != '') physicswrong,
			(select count(*) chemistrywrong from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 46 and 90 and sellectedans <> correctans and sellectedans != '') chemistrywrong,
			(select count(*) biologywrong from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 91 and 180 and sellectedans <> correctans and sellectedans != '') biologywrong,

			(select sum(mark) physicsmark from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 1 and 45) physicsmark ,
			(select sum(mark) chemistrymark from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 46 and 90) chemistrymark,
			(select sum(mark) biologymark from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 91 and 180) biologymark,0";
			$query = $this->db->query($sql);
		}
		if($quiz_type=="NEETFDTNTEST")
		{
			$sql="select '' id,
			'$user_name' as user_id,
			'$quiz_id' as quiz_id,
			'' as st_id,
			(select count(*) physicsattempted from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 1 and 20 and sellectedans != '') physicsattempted,
			(select count(*) chemistryattempted from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 21 and 40 and sellectedans != '') chemistryattempted,
			(select count(*) biologyattempted from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 41 and 60 and sellectedans != '') biologyattempted,

			(select count(*) physicscorrect from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 1 and 20 and sellectedans = correctans) physicscorrect,
			(select count(*) chemistrycorrect from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 21 and 40 and sellectedans = correctans) chemistrycorrect,
			(select count(*) biologycorrect from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 41 and 60 and sellectedans = correctans) biologycorrect,

			(select count(*) physicswrong from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 1 and 20 and sellectedans <> correctans and sellectedans != '') physicswrong,
			(select count(*) chemistrywrong from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 21 and 40 and sellectedans <> correctans and sellectedans != '') chemistrywrong,
			(select count(*) biologywrong from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 41 and 60 and sellectedans <> correctans and sellectedans != '') biologywrong,

			(select sum(mark) physicsmark from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 1 and 20) physicsmark ,
			(select sum(mark) chemistrymark from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 21 and 40) chemistrymark,
			(select sum(mark) biologymark from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 41 and 60) biologymark,0";
			$query = $this->db->query($sql);
		}
		
		if($quiz_type=="JEE")
		{
			$sql="select '' id,
			'$user_name' as user_id,
			'$quiz_id' as quiz_id,
			'' as st_id,
			(select count(*) physicsattempted from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 1 and 30 and sellectedans != '') physicsattempted,
			(select count(*) chemistryattempted from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 31 and 60 and sellectedans != '') chemistryattempted,
			(select count(*) biologyattempted from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 61 and 90 and sellectedans != '') biologyattempted,
																																   
			(select count(*) physicscorrect from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 1 and 30 and sellectedans = correctans) physicscorrect,
			(select count(*) chemistrycorrect from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 31 and 60 and sellectedans = correctans) chemistrycorrect,
			(select count(*) biologycorrect from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 61 and 90 and sellectedans = correctans) biologycorrect,
																																   
			(select count(*) physicswrong from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 1 and 30 and sellectedans <> correctans and sellectedans != '') physicswrong,
			(select count(*) chemistrywrong from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 31 and 60 and sellectedans <> correctans and sellectedans != '') chemistrywrong,
			(select count(*) biologywrong from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 61 and 90 and sellectedans <> correctans and sellectedans != '') biologywrong,
																																   
			(select sum(mark) physicsmark from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 1 and 30) physicsmark ,
			(select sum(mark) chemistrymark from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 31 and 60) chemistrymark,
			(select sum(mark) biologymark from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 61 and 90) biologymark,0";
			 $query = $this->db->query($sql);
		}
		if($quiz_type=="NEWJEE")
		{
			$sql="select '' id,
		'$user_name' as user_id,
		'$quiz_id' as quiz_id,
		'' as st_id,
		(select count(*) physicsattempted from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 1 and 25 and sellectedans != '') physicsattempted,
		(select count(*) chemistryattempted from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 26 and 50 and sellectedans != '') chemistryattempted,
		(select count(*) biologyattempted from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 51 and 75 and sellectedans != '') biologyattempted,

		(select count(*) physicscorrect from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 1 and 25 and sellectedans = correctans) physicscorrect,
		(select count(*) chemistrycorrect from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 26 and 50 and sellectedans = correctans) chemistrycorrect,
		(select count(*) biologycorrect from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 51 and 75 and sellectedans = correctans) biologycorrect,

		(select count(*) physicswrong from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 1 and 25 and sellectedans <> correctans and sellectedans != '') physicswrong,
		(select count(*) chemistrywrong from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 26 and 50 and sellectedans <> correctans and sellectedans != '') chemistrywrong,
		(select count(*) biologywrong from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 51 and 75 and sellectedans <> correctans and sellectedans != '') biologywrong,

		(select sum(mark) physicsmark from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 1 and 25) physicsmark ,
		(select sum(mark) chemistrymark from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 26 and 50) chemistrymark,
		(select sum(mark) biologymark from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 51 and 75) biologymark,0";
		 $query = $this->db->query($sql);
		}
		if($quiz_type=="NEETSHORT")
		{
	
        $sql = "select '' id,
		'$user_name' as user_id,
		'$quiz_id' as quiz_id,
		'' as st_id,
		(select count(*) physicsattempted from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 1 and 15 and sellectedans != '') physicsattempted,
		(select count(*) chemistryattempted from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 16 and 30 and sellectedans != '') chemistryattempted,
		(select count(*) biologyattempted from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 31 and 60 and sellectedans != '') biologyattempted,

		(select count(*) physicscorrect from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 1 and 15 and sellectedans = correctans) physicscorrect,
		(select count(*) chemistrycorrect from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 16 and 30 and sellectedans = correctans) chemistrycorrect,
		(select count(*) biologycorrect from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 31 and 60 and sellectedans = correctans) biologycorrect,

		(select count(*) physicswrong from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 1 and 15 and sellectedans <> correctans and sellectedans != '') physicswrong,
		(select count(*) chemistrywrong from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 16 and 30 and sellectedans <> correctans and sellectedans != '') chemistrywrong,
		(select count(*) biologywrong from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 31 and 60 and sellectedans <> correctans and sellectedans != '') biologywrong,

		(select sum(mark) physicsmark from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 1 and 15) physicsmark ,
		(select sum(mark) chemistrymark from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 16 and 30) chemistrymark,
		(select sum(mark) biologymark from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 31 and 60) biologymark,0";
		$query = $this->db->query($sql);
		}
		if($quiz_type=="KCETPCB")
		{
	
        $sql = "select '' id,
		'$user_name' as user_id,
		'$quiz_id' as quiz_id,
		'' as st_id,
		(select count(*) physicsattempted from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 1 and 60 and sellectedans != '') physicsattempted,
		(select count(*) chemistryattempted from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 61 and 120 and sellectedans != '') chemistryattempted,
		(select count(*) biologyattempted from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 121 and 180 and sellectedans != '') biologyattempted,

		(select count(*) physicscorrect from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 1 and 60 and sellectedans = correctans) physicscorrect,
		(select count(*) chemistrycorrect from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 61 and 120 and sellectedans = correctans) chemistrycorrect,
		(select count(*) biologycorrect from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 121 and 180 and sellectedans = correctans) biologycorrect,

		(select count(*) physicswrong from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 1 and 60 and sellectedans <> correctans and sellectedans != '') physicswrong,
		(select count(*) chemistrywrong from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 61 and 120 and sellectedans <> correctans and sellectedans != '') chemistrywrong,
		(select count(*) biologywrong from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 121 and 180 and sellectedans <> correctans and sellectedans != '') biologywrong,

		(select sum(mark) physicsmark from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 1 and 60) physicsmark ,
		(select sum(mark) chemistrymark from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 61 and 120) chemistrymark,
		(select sum(mark) biologymark from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 121 and 180) biologymark,0";
        $query = $this->db->query($sql);
		}
		if($quiz_type=="KCETPCM")
		{
	
        $sql = "select '' id,
		'$user_name' as user_id,
		'$quiz_id' as quiz_id,
		'' as st_id,
		(select count(*) physicsattempted from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 1 and 60 and sellectedans != '') physicsattempted,
		(select count(*) chemistryattempted from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 61 and 120 and sellectedans != '') chemistryattempted,
		(select count(*) biologyattempted from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 121 and 180 and sellectedans != '') biologyattempted,

		(select count(*) physicscorrect from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 1 and 60 and sellectedans = correctans) physicscorrect,
		(select count(*) chemistrycorrect from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 61 and 120 and sellectedans = correctans) chemistrycorrect,
		(select count(*) biologycorrect from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 121 and 180 and sellectedans = correctans) biologycorrect,

		(select count(*) physicswrong from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 1 and 60 and sellectedans <> correctans and sellectedans != '') physicswrong,
		(select count(*) chemistrywrong from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 61 and 120 and sellectedans <> correctans and sellectedans != '') chemistrywrong,
		(select count(*) biologywrong from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 121 and 180 and sellectedans <> correctans and sellectedans != '') biologywrong,

		(select sum(mark) physicsmark from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 1 and 60) physicsmark ,
		(select sum(mark) chemistrymark from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 61 and 120) chemistrymark,
		(select sum(mark) biologymark from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 121 and 180) biologymark,0";
        $query = $this->db->query($sql);
		}
		if($quiz_type=="NEETCRASHCOURSE")
		{
	
        $sql = "select '' id,
		'$user_name' as user_id,
		'$quiz_id' as quiz_id,
		'' as st_id,
		(select count(*) physicsattempted from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 1 and 45 and sellectedans != '') physicsattempted,
		(select count(*) chemistryattempted from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 46 and 90 and sellectedans != '') chemistryattempted,
		(select count(*) biologyattempted from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 91 and 180 and sellectedans != '') biologyattempted,

		(select count(*) physicscorrect from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 1 and 45 and sellectedans = correctans) physicscorrect,
		(select count(*) chemistrycorrect from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 46 and 90 and sellectedans = correctans) chemistrycorrect,
		(select count(*) biologycorrect from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 91 and 180 and sellectedans = correctans) biologycorrect,

		(select count(*) physicswrong from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 1 and 45 and sellectedans <> correctans and sellectedans != '') physicswrong,
		(select count(*) chemistrywrong from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 46 and 90 and sellectedans <> correctans and sellectedans != '') chemistrywrong,
		(select count(*) biologywrong from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 91 and 180 and sellectedans <> correctans and sellectedans != '') biologywrong,

		(select sum(mark) physicsmark from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 1 and 45) physicsmark ,
		(select sum(mark) chemistrymark from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 46 and 90) chemistrymark,
		(select sum(mark) biologymark from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 91 and 180) biologymark,0";
        $query = $this->db->query($sql);
		}
		
		if($quiz_type=="NTSESAT")
		{
	
        $sql = "select '' id,
		'$user_name' as user_id,
		'$quiz_id' as quiz_id,
		'' as st_id,
		(select count(*) physicsattempted from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 1 and 40 and sellectedans != '') physicsattempted,
		(select count(*) chemistryattempted from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 41 and 80 and sellectedans != '') chemistryattempted,
		(select count(*) biologyattempted from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 81 and 100 and sellectedans != '') biologyattempted,

		(select count(*) physicscorrect from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 1 and 40 and sellectedans = correctans) physicscorrect,
		(select count(*) chemistrycorrect from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 41 and 80 and sellectedans = correctans) chemistrycorrect,
		(select count(*) biologycorrect from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 81 and 100 and sellectedans = correctans) biologycorrect,

		(select count(*) physicswrong from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 1 and 40 and sellectedans <> correctans and sellectedans != '') physicswrong,
		(select count(*) chemistrywrong from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 41 and 80 and sellectedans <> correctans and sellectedans != '') chemistrywrong,
		(select count(*) biologywrong from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 81 and 100 and sellectedans <> correctans and sellectedans != '') biologywrong,

		(select sum(mark) physicsmark from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 1 and 40) physicsmark ,
		(select sum(mark) chemistrymark from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 41 and 80) chemistrymark,
		(select sum(mark) biologymark from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 81 and 100) biologymark,0";
        $query = $this->db->query($sql);
		}
		if($quiz_type=="NTSEMAT")
		{
		$sql = "select '' id,
		'$user_name' as user_id,
		'$quiz_id' as quiz_id,
		'' as st_id,
		(select count(*) physicsattempted from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 1 and 100 and sellectedans != '') physicsattempted,
		(select count(*) physicscorrect from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 1 and 100 and sellectedans = correctans) physicscorrect,
		(select count(*) physicswrong from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 1 and 100 and sellectedans <> correctans and sellectedans != '') physicswrong,
		(select sum(mark) physicsmark from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 1 and 100) physicsmark ,0";
        $query = $this->db->query($sql);
		}
		if($quiz_type=="KVPY")
		{
			$sql = "select '' id,
		'$user_name' as user_id,
		'$quiz_id' as quiz_id,
		'' as st_id,
		(select count(*) mathsattempted from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 1 and 20 and sellectedans != '') mathsattempted,
		(select count(*) physicsattempted from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 21 and 40 and sellectedans != '') physicsattempted,
		(select count(*) chemistryattempted from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 41 and 60 and sellectedans != '') chemistryattempted,
		(select count(*) biologyattempted from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 61 and 80 and sellectedans != '') biologyattempted,

		(select count(*) mathscorrect from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 1 and 20 and sellectedans = correctans) mathscorrect,
		(select count(*) physicscorrect from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 21 and 40 and sellectedans = correctans) physicscorrect,
		(select count(*) chemistrycorrect from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 41 and 60 and sellectedans = correctans) chemistrycorrect,
		(select count(*) biologycorrect from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 61 and 80 and sellectedans = correctans) biologycorrect,

        (select count(*) mathswrong from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 1 and 20 and sellectedans <> correctans and sellectedans != '') mathswrong,
		(select count(*) physicswrong from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 21 and 40 and sellectedans <> correctans and sellectedans != '') physicswrong,
		(select count(*) chemistrywrong from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 41 and 60 and sellectedans <> correctans and sellectedans != '') chemistrywrong,
		(select count(*) biologywrong from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 61 and 80 and sellectedans <> correctans and sellectedans != '') biologywrong,

		(select sum(mark) mathsmark from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 1 and 20) mathsmark ,
		(select sum(mark) physicsmark from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 21 and 40) physicsmark ,
		(select sum(mark) chemistrymark from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 41 and 60) chemistrymark,
		(select sum(mark) biologymark from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 61 and 80) biologymark,0 rank,1";
			$query = $this->db->query($sql);
			//print_r($sql);
		
			$sql = "select '' id,
		'$user_name' as user_id,
		'$quiz_id' as quiz_id,
		'' as st_id,
		(select count(*) mathsattempted from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 81 and 90 and sellectedans != '') mathsattempted,
		(select count(*) physicsattempted from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 91 and 100 and sellectedans != '') physicsattempted,
		(select count(*) chemistryattempted from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 101 and 110 and sellectedans != '') chemistryattempted,
		(select count(*) biologyattempted from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 111 and 120 and sellectedans != '') biologyattempted,

		(select count(*) mathscorrect from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 81 and 90 and sellectedans = correctans) mathscorrect,
		(select count(*) physicscorrect from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 91 and 100 and sellectedans = correctans) physicscorrect,
		(select count(*) chemistrycorrect from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 101 and 110 and sellectedans = correctans) chemistrycorrect,
		(select count(*) biologycorrect from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 111 and 120 and sellectedans = correctans) biologycorrect,

        (select count(*) mathswrong from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 81 and 90 and sellectedans <> correctans and sellectedans != '') mathswrong,
		(select count(*) physicswrong from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 91 and 100 and sellectedans <> correctans and sellectedans != '') physicswrong,
		(select count(*) chemistrywrong from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 101 and 110 and sellectedans <> correctans and sellectedans != '') chemistrywrong,
		(select count(*) biologywrong from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 111 and 120 and sellectedans <> correctans and sellectedans != '') biologywrong,

		(select sum(mark) mathsmark from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 81 and 90) mathsmark ,
		(select sum(mark) physicsmark from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 91 and 100) physicsmark ,
		(select sum(mark) chemistrymark from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 101 and 110) chemistrymark,
		(select sum(mark) biologymark from student_final_answer where user_id= '$user_name' and quiz_id='$quiz_id' and qno between 111 and 120) biologymark,0 rank,2";
			$query = $this->db->query($sql);
			//print_r($sql);**/
		}
		if ($query->num_rows() == 1)
		{
			return $query->result_array();
		}
		elseif ($query->num_rows() > 1)
		{
			return $query->result_array(); //This returns an array of results which you can whatever you need with it
		}
		else{
			return $query->result_array();
		}
		
	}
	
	public function insertresultForcompletequizgrace($quiz_id)
	{
		
		


			$sql ="update student_final_answer a inner join quiz_question b
			on a.question_no = b.id
			set a.mark= b.mark 
			where a.quiz_id = '$quiz_id'
			and a.sellectedans <> '' and b.grace=1";

		$query = $this->db->query($sql);
		
		
	    set_time_limit(10000);
	    $sql = "select distinct user_id,st_id ,quiz_id from student_final_answer where quiz_id ='$quiz_id' ";
        $query = $this->db->query($sql);
	//	print_r($sql);
        foreach ($query->result_array() as $row) {
			
			print_r($row['user_id']);
			$insert_stored_proc = "CALL insert_student_quiz_result(?, ?, ?)";
		$data = array('userid' => $row['user_id'], 'stid' => $row['st_id'], 'quizid' => $quiz_id);
		print_r($data);
		$result = $this->db->query($insert_stored_proc,$data);
			
			
		}
	
	
	}
	public function insertresultForcompletequiz($quiz_id)
	{
	
	
	
	
	
	
	
	
		$sql ="update student_final_answer a inner join quiz_question b
		on a.question_no = b.id
		set a.correctans= b.correctoption 
		where a.quiz_id = '$quiz_id'";
		
		//print_r($sql);

		$query = $this->db->query($sql);
		
		/*
		
		update student_final_answer set mark =-1 where 
quiz_id =657 and sellectedans<>correctans and sellectedans <> '' and mark <> -1;



update student_final_answer set mark =4 where 
quiz_id =657 and sellectedans=correctans ;
		
		
		*/	
		

			$sql ="update student_final_answer a inner join quiz_question b
			on a.question_no = b.id
			set a.mark= b.penalty 
			where a.quiz_id = '$quiz_id'
			and a.sellectedans<>a.correctans and a.sellectedans <> '' ";

			$query = $this->db->query($sql);


			$sql ="update student_final_answer a inner join quiz_question b
			on a.question_no = b.id
			set a.mark= b.mark 
			where a.quiz_id = '$quiz_id'
			and a.sellectedans=a.correctans ";

		$query = $this->db->query($sql);
		
		 $sql = "update student_final_answer a inner join quiz_question b
		on a.question_no = b.id
		set a.mark= b.mark
		where a.answer = b.correct_answer and a.op_d=1 and a.quiz_id = '$quiz_id' and b.qtype='TEXT'  ";
        $query = $this->db->query($sql);
		
  //      print_r($sql);
        $sql = "update student_final_answer a inner join quiz_question b
		on a.question_no = b.id
		set a.mark= b.penalty
		where a.answer <> b.correct_answer and a.op_d=1 and a.quiz_id = '$quiz_id' and a.answer <> '' and b.qtype='TEXT' ";
        $query = $this->db->query($sql);
		
		 $sql = "update student_final_answer a inner join quiz_question b
		on a.question_no = b.id
		set a.mark= 0
		where a.answer = b.correct_answer and a.op_d=0 and a.quiz_id = '$quiz_id' and b.qtype='TEXT'  ";
        $query = $this->db->query($sql);
//print_r($sql);
        $sql = "update student_final_answer a inner join quiz_question b
		on a.question_no = b.id
		set a.sellectedans= 1
		where a.answer = b.correct_answer and a.op_d=1 and a.quiz_id = '$quiz_id' and b.qtype='TEXT' ";
		$query = $this->db->query($sql);
	//	print_r($sql);
		  $sql = "update student_final_answer a inner join quiz_question b
		on a.question_no = b.id
		set a.sellectedans= 4
		where a.answer <> b.correct_answer and a.op_d=1 and a.quiz_id = '$quiz_id' and a.answer <> '' and b.qtype='TEXT' ";
        $query = $this->db->query($sql);
		 $sql = "update student_final_answer a inner join quiz_question b
		on a.question_no = b.id
		set a.sellectedans=''
		where a.answer <> b.correct_answer and a.op_d=0 and a.quiz_id = '$quiz_id' and a.answer <> '' and b.qtype='TEXT' ";
        $query = $this->db->query($sql);
// print_r($sql);
         $sql = "update student_final_answer a inner join quiz_question b
		on a.question_no = b.id
		set a.correctoption= b.correctoption where  a.quiz_id = '$quiz_id' and b.qtype='TEXT' ";
        $query = $this->db->query($sql);
	
	//print_r($sql);
	
	 $sql = "update student_final_answer a inner join quiz_question b
		on a.question_no = b.id
		set a.correct_answer= b.correct_answer where  a.quiz_id = '$quiz_id' and b.qtype='TEXT' ";
        $query = $this->db->query($sql);
	    set_time_limit(10000);
	    $sql = "select distinct user_id,st_id ,quiz_id from student_final_answer where quiz_id ='$quiz_id' ";
        $query = $this->db->query($sql);
	//	print_r($sql);
        foreach ($query->result_array() as $row) {
			
		//	print_r($row['user_id']);
			$insert_stored_proc = "CALL insert_student_quiz_result(?, ?, ?)";
		$data = array('userid' => $row['user_id'], 'stid' => $row['st_id'], 'quizid' => $quiz_id);
		//print_r($data);
		$result = $this->db->query($insert_stored_proc,$data);
			
			
		}
	
	
	}
	
	public function calculateresultagain()
	{
	
 $sql = "select distinct user_id,st_id ,quiz_id from student_final_answer where quiz_id ='851' ";
        $query = $this->db->query($sql);
        foreach ($query->result_array() as $row) {
			
			
			$insert_stored_proc = "CALL insert_student_quiz_result(?, ?, ?)";
		$data = array('userid' => $row['user_id'], 'stid' => $row['st_id'], 'quizid' =>  $row['quiz_id']);
		print_r($data);
		$result = $this->db->query($insert_stored_proc,$data);
	
	}
	}
	
	public function insertresultForquiz($quiz_id,$user_name,$st_id)
	{
		$insert_stored_proc = "CALL insert_student_quiz_result(?, ?, ?)";
		$data = array('userid' => $user_name, 'stid' => $st_id, 'quizid' => $quiz_id);
		print_r($data);
	//	die;
		$result = $this->db->query($insert_stored_proc,$data);
		//print_r($result);
        if ($result !== NULL) {
            return TRUE;
        }
        return FALSE;
		/**if($this->nativesession->get('quiztype')=="JUT")
		{
		$insert_stored_proc_jut = "CALL insert_student_result_jut(?, ?, ?)";
		$data = array('userid' => $user_name, 'stid' => '', 'quizid' => $quiz_id);
		//print_r($data);
		$result = $this->db->query($insert_stored_proc_jut,$data);
		//print_r($result);
        if ($result !== NULL) {
            return TRUE;
        }
        return FALSE;
		}
		if($this->nativesession->get('quiztype')=="JEE")
		{
		$insert_stored_proc_jee = "CALL insert_student_result_jee(?, ?, ?)";
		$data = array('userid' => $user_name, 'stid' => '', 'quizid' => $quiz_id);
		//print_r($data);
		$result = $this->db->query($insert_stored_proc_jee,$data);
		//print_r($result);
        if ($result !== NULL) {
            return TRUE;
        }
        return FALSE;
		}
		if($this->nativesession->get('quiztype')=="NEWJEE")
		{
		$insert_stored_proc_kcetpcb = "CALL insert_student_result_kcetpcb(?, ?, ?)";
		$data = array('userid' => $user_name, 'stid' => '', 'quizid' => $quiz_id);
		//print_r($data);
		$result = $this->db->query($insert_stored_proc_kcetpcb,$data);
		//print_r($result);
        if ($result !== NULL) {
            return TRUE;
        }
        return FALSE;
		}
		if($this->nativesession->get('quiztype')=="KCETPCB")
		{
		$insert_stored_proc_kcetpcb = "CALL insert_student_result_kcetpcb(?, ?, ?)";
		$data = array('userid' => $user_name, 'stid' => '', 'quizid' => $quiz_id);
		//print_r($data);
		$result = $this->db->query($insert_stored_proc_kcetpcb,$data);
		//print_r($result);
        if ($result !== NULL) {
            return TRUE;
        }
        return FALSE;
		}
		if($this->nativesession->get('quiztype')=="KCETPCM")
		{
		$insert_stored_proc_kcetpcm = "CALL insert_student_result_kcetpcm(?, ?, ?)";
		$data = array('userid' => $user_name, 'stid' => '', 'quizid' => $quiz_id);
		//print_r($data);
		$result = $this->db->query($insert_stored_proc_kcetpcm,$data);
		//print_r($result);
        if ($result !== NULL) {
            return TRUE;
        }
        return FALSE;
		}
		if($this->nativesession->get('quiztype')=="NEWJEE")
		{
		$insert_stored_proc_newjee = "CALL insert_student_result_newjee(?, ?, ?)";
		$data = array('userid' => $user_name, 'stid' => '', 'quizid' => $quiz_id);
		//print_r($data);
		$result = $this->db->query($insert_stored_proc_newjee,$data);
		//print_r($result);
        if ($result !== NULL) {
            return TRUE;
        }
        return FALSE;
		}
		if($this->nativesession->get('quiztype')=="NEETSHORT")
		{
		$insert_stored_proc_neetshort = "CALL insert_student_result_neetshort(?, ?, ?)";
		$data = array('userid' => $user_name, 'stid' => '', 'quizid' => $quiz_id);
		//print_r($data);
		$result = $this->db->query($insert_stored_proc_neetshort,$data);
		//print_r($result);
        if ($result !== NULL) {
            return TRUE;
        }
        return FALSE;
		}
		if($this->nativesession->get('quiztype')=="NEETCRASHCOURSE")
		{
		$insert_stored_proc_neetcrash = "CALL insert_student_result_neetcrashcourse(?, ?, ?)";
		$data = array('userid' => $user_name, 'stid' => '', 'quizid' => $quiz_id);
		//print_r($data);
		$result = $this->db->query($insert_stored_proc_neetcrash,$data);
		//print_r($result);
        if ($result !== NULL) {
            return TRUE;
        }
        return FALSE;
		}
		if($this->nativesession->get('quiztype')=="NTSESAT")
		{
		$insert_stored_proc_sat = "CALL insert_student_result_sat(?, ?, ?)";
		$data = array('userid' => $user_name, 'stid' => '', 'quizid' => $quiz_id);
		//print_r($data);
		$result = $this->db->query($insert_stored_proc_sat,$data);
		//print_r($result);
        if ($result !== NULL) {
            return TRUE;
        }
        return FALSE;
		}
		if($this->nativesession->get('quiztype')=="NTSEMAT")
		{
		$insert_stored_proc_mat = "CALL insert_student_result_mat(?, ?, ?)";
		$data = array('userid' => $user_name, 'stid' => '', 'quizid' => $quiz_id);
		//print_r($data);
		$result = $this->db->query($insert_stored_proc_mat,$data);
		//print_r($result);
        if ($result !== NULL) {
            return TRUE;
        }
        return FALSE;
		}
		
		if($this->nativesession->get('quiztype')=="KVPY")
		{
		$insert_stored_proc_kvpy = "CALL insert_student_result_kvpy(?, ?, ?)";
		$data = array('userid' => $user_name, 'stid' => '', 'quizid' => $quiz_id);
		//print_r($data);
		$result = $this->db->query($insert_stored_proc_kvpy,$data);
		//print_r($result);
        if ($result !== NULL) {
            return TRUE;
        }
        return FALSE;
		}**/
	}
public function get_userdetails($user_name)
	{	
		$sql1="SELECT * FROM user_details where user_name='$user_name'";
		$query = $this->db->query($sql1);
		if ($query->num_rows() == 1)
		{
			return $query->row();
		}
		elseif ($query->num_rows() > 1)
		{
		   return $query->result_array(); //This returns an array of results which you can whatever you need with it
		}
		else 
		{
			return $query->result_array();
		}
	
	}
		
	public function updatenewpass()
	{
		$user_name=$this->input->post('user_name');
		$passwd=$this->input->post('newpass');
		$pass=md5($passwd);
		date_default_timezone_set('Asia/Kolkata');
		$date = date('Y-m-d H:i:s');
		$sql = "UPDATE user_details SET password='$pass',actual_password='$passwd',modified_date='$date'
				WHERE user_name='$user_name'";
				$query=$this->db->query($sql);
	}
		
	public function findAllForquizpacklink($package_id)
	{
		$sql="SELECT * FROM quiz_package_link where package_id='$package_id' ";
			$query = $this->db->query($sql);
			return $query->result_array();
	/*	if ($query->num_rows() == 1)
		{
			return $query->row();
		}
		elseif ($query->num_rows() > 1)
		{
		   return $query->result_array(); //This returns an array of results which you can whatever you need with it
		}
		else 
		{
			return $query->result_array();
		}	*/
			
	}
		
	public function savequiztypedetails($type)
	{
		$quiztype=$type['quiztype'];
		$crtmark= $type['crtmark'];
		$wrmark=$type['wrmark'];
		$sql = "INSERT INTO save_quiztypemarks (`quiztype`,`correctmark`,`wrongmark`) VALUES ('$quiztype','$crtmark','$wrmark')";
		//print_r($sql);
		$this->db->query($sql);
		$lastid = $this->db->insert_id();
		return $lastid;

	}
	
	public function savearraydetails($quiztypeid){
		$data=array();
		$type['quiztype']=$_POST['quiztype'];
		foreach ($_POST['subject'] as $key => $value){
		$data[]=array(
		   'id' => $quiztypeid,
		   'quiztype'=> $type['quiztype'],
		   'subject'=> $_POST['subject'][$key],
		   'questions' =>$_POST['questionno'][$key] ,
		   'order' => $_POST['order'][$key]
		);
	 }
		//print_r($data);
	$this->db->where('id=',$quiztypeid);
	//$this->db->order_by("order", "asc");
	$this->db->insert_batch('save_quiztype',$data);
	}
	public function save_static_quiz()
	{
		$qname = $this->input->post('qname');
		$tlimit = $this->input->post('tlimit');
		$tlimit1 = $this->input->post('tlimit1');
		$tlimit = $tlimit . ":" . $tlimit1;
		$subject_name = $this->input->post('subject_name');
		$nque = $this->input->post('nque');
		$seque = $this->input->post('seque');
		$quiztype = $this->input->post('quiztype');
		$sql = "INSERT INTO `quiz_info`(`id`,`Quiz_name`,`subject_name`,`Time_limit`, `No_of_question`, `selected_Qustion`,`no_of_attemps`,`quiztype`)
			VALUES ('','$qname','$subject_name','$tlimit','$nque','$seque','1','$quiztype')";
		$query = $this->db->query($sql);
	}
	public function save_category()
		{
			$subject_name = $this->input->post('subject_name');
			$seque = $this->input->post('seque');
			$category = $this->input->post('category');
			$sql="UPDATE `quiz_category` SET `questions_list`='$seque' WHERE category='$category' and Subject_id ='$subject_name'";
			$query=$this->db->query($sql);
		}
	public function get_questionby_categoryandsubject12($subject_name, $category, $level)
    {
        /*$sql = "SELECT a.id, a.question_name, b.question_answer
        FROM quiz_question a,quiz_question_answers b
        WHERE a.id = b.Qustion_no and a.category='$category' and a.level='$level';";*/
		 $Qry = "select questions_list from quiz_category where category='$category'";
    	$resp = $this->db->query($Qry);
    	
    	
		$respRow = $resp->row();
		//print_r($respRow->questions_list);
		$qlist = $respRow->questions_list;
		
		$sql = "SET SESSION group_concat_max_len = 1000000";
        $query = $this->db->query($sql);
		$sql="SELECT a.question_name,a.discription,a.correctoption,a.id,
        SPLIT_STRING(group_concat(question_answer order by b.id separator '~'),'~',1) op_a ,
		SPLIT_STRING(group_concat(question_answer order by b.id separator '~'),'~',2) op_b ,
		SPLIT_STRING(group_concat(question_answer  order by b.id separator '~'),'~',3) op_c,
		SPLIT_STRING(group_concat(question_answer  order by b.id separator '~'),'~',4) op_d,
		a.qtype,a.correct_answer
		FROM quiz_question a,quiz_question_answers b
		WHERE a.id = b.Qustion_no  and a.level='$level' and a.id in ($qlist)
		group by a.discription,a.correctoption,a.id ORDER BY a.id;";
		//print_r($sql);
        $qurey = $this->db->query($sql);
        return $qurey->result_array();
    }
	public function chk_subjectnamecategory($rs, $rs1)
    {
        $sql = "SELECT min(id) min FROM quiz_category WHERE subject='$rs' and category='$rs1'";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $rs = $row['min'];
                if ($rs = $row['min'] > 0) {$rs = $row['min'];} else { $rs = "o";}
                return $rs;
                break;
            }
        } else {
            return "o";
        }
    }
	public function add_upload_question($params)
    {
        $this->db->insert('upload_question', $params);
        return $this->db->insert_id();
    }
	public function authoriserecord($name, $st)
    {
        $sql = "UPDATE quiz_student SET status='$st' WHERE user_name='$name'";
        $this->db->query($sql);
    }
	public function save_data($sql)
    {
        $query = $this->db->query($sql);
    }
	
 public function get_consol_rank($batch,$sub)
    {

        //drop table all_jut_rank;
        $sql = "truncate table all_jut_rank";
        $query = $this->db->query($sql);
		if ($batch=='all' && $sub =='all'){
        $sql = "insert into  all_jut_rank
		select user_id,
		max(case when quiz_id ='1' THEN physicsmark+chemistrymark+biologymark END ) 'JUT1',
		max(case when quiz_id ='2' THEN physicsmark+chemistrymark+biologymark END ) 'JUT2',
		max(case when quiz_id ='3' THEN physicsmark+chemistrymark+biologymark END ) 'JUT3',
		max(case when quiz_id ='4' THEN physicsmark+chemistrymark+biologymark END ) 'JUT4',
		max(case when quiz_id ='5' THEN physicsmark+chemistrymark+biologymark END ) 'JUT5',
		max(case when quiz_id ='6' THEN physicsmark+chemistrymark+biologymark END ) 'JUT6',
		max(case when quiz_id ='7' THEN physicsmark+chemistrymark+biologymark END ) 'JUT7',
		max(case when quiz_id ='8' THEN physicsmark+chemistrymark+biologymark END ) 'JUT8',
		max(case when quiz_id ='9' THEN physicsmark+chemistrymark+biologymark END ) 'JUT9',
		max(case when quiz_id ='10' THEN physicsmark+chemistrymark+biologymark END ) 'JUT10',
		max(case when quiz_id ='11' THEN physicsmark+chemistrymark+biologymark END ) 'JUT11',
		max(case when quiz_id ='12' THEN physicsmark+chemistrymark+biologymark END ) 'JUT12',
		max(case when quiz_id ='13' THEN physicsmark+chemistrymark+biologymark END ) 'JUT13',
		max(case when quiz_id ='14' THEN physicsmark+chemistrymark+biologymark END ) 'JUT14',
		max(case when quiz_id ='15' THEN physicsmark+chemistrymark+biologymark END ) 'JUT15',
		max(case when quiz_id ='16' THEN physicsmark+chemistrymark+biologymark END ) 'JUT16',
		max(case when quiz_id ='17' THEN physicsmark+chemistrymark+biologymark END ) 'JUT17',
		max(case when quiz_id ='18' THEN physicsmark+chemistrymark+biologymark END ) 'JUT18',
		max(case when quiz_id ='19' THEN physicsmark+chemistrymark+biologymark END ) 'JUT19',
		max(case when quiz_id ='20' THEN physicsmark+chemistrymark+biologymark END ) 'JUT20',
		max(case when quiz_id ='21' THEN physicsmark+chemistrymark+biologymark END ) 'JUT21',
		max(case when quiz_id ='22' THEN physicsmark+chemistrymark+biologymark END ) 'JUT22',
		max(case when quiz_id ='23' THEN physicsmark+chemistrymark+biologymark END ) 'JUT23',
		max(case when quiz_id ='24' THEN physicsmark+chemistrymark+biologymark END ) 'JUT24',
		max(case when quiz_id ='25' THEN physicsmark+chemistrymark+biologymark END ) 'JUT25',
		max(case when quiz_id ='26' THEN physicsmark+chemistrymark+biologymark END ) 'JUT26',
		max(case when quiz_id ='27' THEN physicsmark+chemistrymark+biologymark END ) 'JUT27',
		max(case when quiz_id ='28' THEN physicsmark+chemistrymark+biologymark END ) 'JUT28',
		max(case when quiz_id ='29' THEN physicsmark+chemistrymark+biologymark END ) 'JUT29',
		max(case when quiz_id ='30' THEN physicsmark+chemistrymark+biologymark END ) 'JUT30',
		max(case when quiz_id ='31' THEN physicsmark+chemistrymark+biologymark END ) 'JUT31',
		max(case when quiz_id ='32' THEN physicsmark+chemistrymark+biologymark END ) 'JUT32',
		max(case when quiz_id ='33' THEN physicsmark+chemistrymark+biologymark END ) 'JUT33',
		max(case when quiz_id ='34' THEN physicsmark+chemistrymark+biologymark END ) 'JUT34',
		max(case when quiz_id ='35' THEN physicsmark+chemistrymark+biologymark END ) 'JUT35',
		max(case when quiz_id ='36' THEN physicsmark+chemistrymark+biologymark END ) 'JUT36',
		max(case when quiz_id ='37' THEN physicsmark+chemistrymark+biologymark END ) 'JUT37',
		max(case when quiz_id ='38' THEN physicsmark+chemistrymark+biologymark END ) 'JUT38',
		max(case when quiz_id ='39' THEN physicsmark+chemistrymark+biologymark END ) 'JUT39',
		max(case when quiz_id ='40' THEN physicsmark+chemistrymark+biologymark END ) 'JUT40',
		max(case when quiz_id ='41' THEN physicsmark+chemistrymark+biologymark END ) 'JUT41',
		max(case when quiz_id ='42' THEN physicsmark+chemistrymark+biologymark END ) 'JUT42',
		max(case when quiz_id ='43' THEN physicsmark+chemistrymark+biologymark END ) 'JUT43',
		max(case when quiz_id ='44' THEN physicsmark+chemistrymark+biologymark END ) 'JUT44',
		max(case when quiz_id ='45' THEN physicsmark+chemistrymark+biologymark END ) 'JUT45',
		max(case when quiz_id ='46' THEN physicsmark+chemistrymark+biologymark END ) 'JUT46',
		max(case when quiz_id ='47' THEN physicsmark+chemistrymark+biologymark END ) 'JUT47',
		max(case when quiz_id ='48' THEN physicsmark+chemistrymark+biologymark END ) 'JUT48',
		max(case when quiz_id ='49' THEN physicsmark+chemistrymark+biologymark END ) 'JUT49',
		max(case when quiz_id ='50' THEN physicsmark+chemistrymark+biologymark END ) 'JUT50',
		max(case when quiz_id ='51' THEN physicsmark+chemistrymark+biologymark END ) 'JUT51',
		max(case when quiz_id ='52' THEN physicsmark+chemistrymark+biologymark END ) 'JUT52',
		max(case when quiz_id ='53' THEN physicsmark+chemistrymark+biologymark END ) 'JUT53',
		max(case when quiz_id ='54' THEN physicsmark+chemistrymark+biologymark END ) 'JUT54',
		max(case when quiz_id ='55' THEN physicsmark+chemistrymark+biologymark END ) 'JUT55',
		max(case when quiz_id ='56' THEN physicsmark+chemistrymark+biologymark END ) 'JUT56',
		max(case when quiz_id ='57' THEN physicsmark+chemistrymark+biologymark END ) 'JUT57',
		max(case when quiz_id ='58' THEN physicsmark+chemistrymark+biologymark END ) 'JUT58',
		max(case when quiz_id ='59' THEN physicsmark+chemistrymark+biologymark END ) 'JUT59',
		max(case when quiz_id ='60' THEN physicsmark+chemistrymark+biologymark END ) 'JUT60',
		max(case when quiz_id ='61' THEN physicsmark+chemistrymark+biologymark END ) 'JUT61',
		max(case when quiz_id ='62' THEN physicsmark+chemistrymark+biologymark END ) 'JUT62',
		max(case when quiz_id ='63' THEN physicsmark+chemistrymark+biologymark END ) 'JUT63',
		max(case when quiz_id ='64' THEN physicsmark+chemistrymark+biologymark END ) 'JUT64',
		max(case when quiz_id ='65' THEN physicsmark+chemistrymark+biologymark END ) 'JUT65',
		max(case when quiz_id ='66' THEN physicsmark+chemistrymark+biologymark END ) 'JUT66',
		max(case when quiz_id ='67' THEN physicsmark+chemistrymark+biologymark END ) 'JUT67',
		max(case when quiz_id ='68' THEN physicsmark+chemistrymark+biologymark END ) 'JUT68',
		max(case when quiz_id ='69' THEN physicsmark+chemistrymark+biologymark END ) 'JUT69',
		max(case when quiz_id ='70' THEN physicsmark+chemistrymark+biologymark END ) 'JUT70',
		max(case when quiz_id ='71' THEN physicsmark+chemistrymark+biologymark END ) 'JUT71',
		max(case when quiz_id ='72' THEN physicsmark+chemistrymark+biologymark END ) 'JUT72',
		max(case when quiz_id ='73' THEN physicsmark+chemistrymark+biologymark END ) 'JUT73',
		max(case when quiz_id ='74' THEN physicsmark+chemistrymark+biologymark END ) 'JUT74',
		max(case when quiz_id ='75' THEN physicsmark+chemistrymark+biologymark END ) 'JUT75',
		
		max(case when quiz_id ='103' THEN physicsmark+chemistrymark+biologymark END ) 'parttest1',
		max(case when quiz_id ='104' THEN physicsmark+chemistrymark+biologymark END ) 'parttest2',
		max(case when quiz_id ='105' THEN physicsmark+chemistrymark+biologymark END ) 'parttest3',
		max(case when quiz_id ='106' THEN physicsmark+chemistrymark+biologymark END ) 'parttest4',
		max(case when quiz_id ='107' THEN physicsmark+chemistrymark+biologymark END ) 'parttest5',
		max(case when quiz_id ='108' THEN physicsmark+chemistrymark+biologymark END ) 'parttest6',
		max(case when quiz_id ='109' THEN physicsmark+chemistrymark+biologymark END ) 'parttest7',
		max(case when quiz_id ='110' THEN physicsmark+chemistrymark+biologymark END ) 'parttest8',
		max(case when quiz_id ='125' THEN physicsmark+chemistrymark+biologymark END ) 'fulltest1',
		max(case when quiz_id ='126' THEN physicsmark+chemistrymark+biologymark END ) 'fulltest2',
		max(case when quiz_id ='170' THEN physicsmark+chemistrymark+biologymark END ) 'fulltest3',
		max(case when quiz_id ='171' THEN physicsmark+chemistrymark+biologymark END ) 'fulltest4',
		max(case when quiz_id ='172' THEN physicsmark+chemistrymark+biologymark END ) 'fulltest5',
		max(case when quiz_id ='173' THEN physicsmark+chemistrymark+biologymark END ) 'fulltest6',
		max(case when quiz_id ='174' THEN physicsmark+chemistrymark+biologymark END ) 'fulltest7',
		max(case when quiz_id ='175' THEN physicsmark+chemistrymark+biologymark END ) 'fulltest8',
		max(case when quiz_id ='176' THEN physicsmark+chemistrymark+biologymark END ) 'fulltest9',
		max(case when quiz_id ='177' THEN physicsmark+chemistrymark+biologymark END ) 'fulltest10',
		max(case when quiz_id ='326' THEN physicsmark+chemistrymark+biologymark END ) 'fulltest11',
		max(case when quiz_id ='334' THEN physicsmark+chemistrymark+biologymark END ) 'fulltest12',
		max(case when quiz_id ='337' THEN physicsmark+chemistrymark+biologymark END ) 'fulltest13',
		max(case when quiz_id ='338' THEN physicsmark+chemistrymark+biologymark END ) 'fulltest14',
		max(case when quiz_id ='340' THEN physicsmark+chemistrymark+biologymark END ) 'fulltest15',
		max(case when quiz_id ='342' THEN physicsmark+chemistrymark+biologymark END ) 'fulltest16',
		max(case when quiz_id ='345' THEN physicsmark+chemistrymark+biologymark END ) 'fulltest17',
		max(case when quiz_id ='367' THEN physicsmark+chemistrymark+biologymark END ) 'fulltest18',
		max(case when quiz_id ='379' THEN physicsmark+chemistrymark+biologymark END ) 'fulltest19',
		max(case when quiz_id ='383' THEN physicsmark+chemistrymark+biologymark END ) 'fulltest20',
		max(case when quiz_id ='384' THEN physicsmark+chemistrymark+biologymark END ) 'fulltest21',
		max(case when quiz_id ='391' THEN physicsmark+chemistrymark+biologymark END ) 'fulltest22',
		max(case when quiz_id ='411' THEN physicsmark+chemistrymark+biologymark END ) 'fulltest23',
		max(case when quiz_id ='413' THEN physicsmark+chemistrymark+biologymark END ) 'fulltest24',
		max(case when quiz_id ='420' THEN physicsmark+chemistrymark+biologymark END ) 'fulltest25',

		ifnull(max(case when quiz_id ='1' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='2' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='3' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='4' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='5' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='6' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='7' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='8' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='9' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='10' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='11' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='12' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='13' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='14' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='15' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='16' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='17' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='18' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='19' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='20' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='21' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='22' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='23' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='24' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='25' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='26' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='27' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='28' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='29' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='30' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='31' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='32' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='33' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='34' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='35' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='36' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='37' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='38' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='39' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='40' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='41' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='42' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='43' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='44' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='45' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='46' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='47' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='48' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='49' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='50' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='51' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='52' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='53' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='54' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='55' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='56' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='57' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='58' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='59' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='60' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='61' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='62' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='63' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='64' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='65' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='66' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='67' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='68' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='69' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='70' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='71' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='72' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='73' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='74' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='75' THEN physicsmark+chemistrymark+biologymark END ),0) + 
		
		ifnull(max(case when quiz_id ='103' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='104' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='105' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='106' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='107' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='108' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='109' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='110' THEN physicsmark+chemistrymark+biologymark END ),0) +
		
		ifnull(max(case when quiz_id ='125' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='126' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='170' THEN physicsmark+chemistrymark+biologymark END ),0) + 
		ifnull(max(case when quiz_id ='171' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='172' THEN physicsmark+chemistrymark+biologymark END ),0) + 
		ifnull(max(case when quiz_id ='173' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='174' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='175' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='176' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='177' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='326' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='334' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='337' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='338' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='340' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='342' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='345' THEN physicsmark+chemistrymark+biologymark END ),0) + 
		ifnull(max(case when quiz_id ='367' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='379' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='383' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='384' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='391' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='411' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='413' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='420' THEN physicsmark+chemistrymark+biologymark END ),0)  total
		
		FROM student_quiz_result a , quiz_info b where a.quiz_id = b.id and b.quiztype='JUT' GROUP BY user_id";
				$query = $this->db->query($sql);
				//print_r($sql);
		}

		if ($batch=='JNANASUDHAIIPU' && $sub =='all'){
			$sql = "insert into  all_jut_rank
		select user_id,
		max(case when quiz_id ='1' THEN physicsmark+chemistrymark+biologymark END ) 'JUT1',
		max(case when quiz_id ='2' THEN physicsmark+chemistrymark+biologymark END ) 'JUT2',
		max(case when quiz_id ='3' THEN physicsmark+chemistrymark+biologymark END ) 'JUT3',
		max(case when quiz_id ='4' THEN physicsmark+chemistrymark+biologymark END ) 'JUT4',
		max(case when quiz_id ='5' THEN physicsmark+chemistrymark+biologymark END ) 'JUT5',
		max(case when quiz_id ='6' THEN physicsmark+chemistrymark+biologymark END ) 'JUT6',
		max(case when quiz_id ='7' THEN physicsmark+chemistrymark+biologymark END ) 'JUT7',
		max(case when quiz_id ='8' THEN physicsmark+chemistrymark+biologymark END ) 'JUT8',
		max(case when quiz_id ='9' THEN physicsmark+chemistrymark+biologymark END ) 'JUT9',
		max(case when quiz_id ='10' THEN physicsmark+chemistrymark+biologymark END ) 'JUT10',
		max(case when quiz_id ='11' THEN physicsmark+chemistrymark+biologymark END ) 'JUT11',
		max(case when quiz_id ='12' THEN physicsmark+chemistrymark+biologymark END ) 'JUT12',
		max(case when quiz_id ='13' THEN physicsmark+chemistrymark+biologymark END ) 'JUT13',
		max(case when quiz_id ='14' THEN physicsmark+chemistrymark+biologymark END ) 'JUT14',
		max(case when quiz_id ='15' THEN physicsmark+chemistrymark+biologymark END ) 'JUT15',
		max(case when quiz_id ='16' THEN physicsmark+chemistrymark+biologymark END ) 'JUT16',
		max(case when quiz_id ='17' THEN physicsmark+chemistrymark+biologymark END ) 'JUT17',
		max(case when quiz_id ='18' THEN physicsmark+chemistrymark+biologymark END ) 'JUT18',
		max(case when quiz_id ='19' THEN physicsmark+chemistrymark+biologymark END ) 'JUT19',
		max(case when quiz_id ='20' THEN physicsmark+chemistrymark+biologymark END ) 'JUT20',
		max(case when quiz_id ='21' THEN physicsmark+chemistrymark+biologymark END ) 'JUT21',
		max(case when quiz_id ='22' THEN physicsmark+chemistrymark+biologymark END ) 'JUT22',
		max(case when quiz_id ='23' THEN physicsmark+chemistrymark+biologymark END ) 'JUT23',
		max(case when quiz_id ='24' THEN physicsmark+chemistrymark+biologymark END ) 'JUT24',
		max(case when quiz_id ='25' THEN physicsmark+chemistrymark+biologymark END ) 'JUT25',
		max(case when quiz_id ='26' THEN physicsmark+chemistrymark+biologymark END ) 'JUT26',
		max(case when quiz_id ='27' THEN physicsmark+chemistrymark+biologymark END ) 'JUT27',
		max(case when quiz_id ='28' THEN physicsmark+chemistrymark+biologymark END ) 'JUT28',
		max(case when quiz_id ='29' THEN physicsmark+chemistrymark+biologymark END ) 'JUT29',
		max(case when quiz_id ='30' THEN physicsmark+chemistrymark+biologymark END ) 'JUT30',
		max(case when quiz_id ='31' THEN physicsmark+chemistrymark+biologymark END ) 'JUT31',
		max(case when quiz_id ='32' THEN physicsmark+chemistrymark+biologymark END ) 'JUT32',
		max(case when quiz_id ='33' THEN physicsmark+chemistrymark+biologymark END ) 'JUT33',
		max(case when quiz_id ='34' THEN physicsmark+chemistrymark+biologymark END ) 'JUT34',
		max(case when quiz_id ='35' THEN physicsmark+chemistrymark+biologymark END ) 'JUT35',
		max(case when quiz_id ='36' THEN physicsmark+chemistrymark+biologymark END ) 'JUT36',
		max(case when quiz_id ='37' THEN physicsmark+chemistrymark+biologymark END ) 'JUT37',
		max(case when quiz_id ='38' THEN physicsmark+chemistrymark+biologymark END ) 'JUT38',
		max(case when quiz_id ='39' THEN physicsmark+chemistrymark+biologymark END ) 'JUT39',
		max(case when quiz_id ='40' THEN physicsmark+chemistrymark+biologymark END ) 'JUT40',
		max(case when quiz_id ='41' THEN physicsmark+chemistrymark+biologymark END ) 'JUT41',
		max(case when quiz_id ='42' THEN physicsmark+chemistrymark+biologymark END ) 'JUT42',
		max(case when quiz_id ='43' THEN physicsmark+chemistrymark+biologymark END ) 'JUT43',
		max(case when quiz_id ='44' THEN physicsmark+chemistrymark+biologymark END ) 'JUT44',
		max(case when quiz_id ='45' THEN physicsmark+chemistrymark+biologymark END ) 'JUT45',
		max(case when quiz_id ='46' THEN physicsmark+chemistrymark+biologymark END ) 'JUT46',
		max(case when quiz_id ='47' THEN physicsmark+chemistrymark+biologymark END ) 'JUT47',
		max(case when quiz_id ='48' THEN physicsmark+chemistrymark+biologymark END ) 'JUT48',
		max(case when quiz_id ='49' THEN physicsmark+chemistrymark+biologymark END ) 'JUT49',
		max(case when quiz_id ='50' THEN physicsmark+chemistrymark+biologymark END ) 'JUT50',
		max(case when quiz_id ='51' THEN physicsmark+chemistrymark+biologymark END ) 'JUT51',
		max(case when quiz_id ='52' THEN physicsmark+chemistrymark+biologymark END ) 'JUT52',
		max(case when quiz_id ='53' THEN physicsmark+chemistrymark+biologymark END ) 'JUT53',
		max(case when quiz_id ='54' THEN physicsmark+chemistrymark+biologymark END ) 'JUT54',
		max(case when quiz_id ='55' THEN physicsmark+chemistrymark+biologymark END ) 'JUT55',
		max(case when quiz_id ='56' THEN physicsmark+chemistrymark+biologymark END ) 'JUT56',
		max(case when quiz_id ='57' THEN physicsmark+chemistrymark+biologymark END ) 'JUT57',
		max(case when quiz_id ='58' THEN physicsmark+chemistrymark+biologymark END ) 'JUT58',
		max(case when quiz_id ='59' THEN physicsmark+chemistrymark+biologymark END ) 'JUT59',
		max(case when quiz_id ='60' THEN physicsmark+chemistrymark+biologymark END ) 'JUT60',
		max(case when quiz_id ='61' THEN physicsmark+chemistrymark+biologymark END ) 'JUT61',
		max(case when quiz_id ='62' THEN physicsmark+chemistrymark+biologymark END ) 'JUT62',
		max(case when quiz_id ='63' THEN physicsmark+chemistrymark+biologymark END ) 'JUT63',
		max(case when quiz_id ='64' THEN physicsmark+chemistrymark+biologymark END ) 'JUT64',
		max(case when quiz_id ='65' THEN physicsmark+chemistrymark+biologymark END ) 'JUT65',
		max(case when quiz_id ='66' THEN physicsmark+chemistrymark+biologymark END ) 'JUT66',
		max(case when quiz_id ='67' THEN physicsmark+chemistrymark+biologymark END ) 'JUT67',
		max(case when quiz_id ='68' THEN physicsmark+chemistrymark+biologymark END ) 'JUT68',
		max(case when quiz_id ='69' THEN physicsmark+chemistrymark+biologymark END ) 'JUT69',
		max(case when quiz_id ='70' THEN physicsmark+chemistrymark+biologymark END ) 'JUT70',
		max(case when quiz_id ='71' THEN physicsmark+chemistrymark+biologymark END ) 'JUT71',
		max(case when quiz_id ='72' THEN physicsmark+chemistrymark+biologymark END ) 'JUT72',
		max(case when quiz_id ='73' THEN physicsmark+chemistrymark+biologymark END ) 'JUT73',
		max(case when quiz_id ='74' THEN physicsmark+chemistrymark+biologymark END ) 'JUT74',
		max(case when quiz_id ='75' THEN physicsmark+chemistrymark+biologymark END ) 'JUT75',
		
		max(case when quiz_id ='103' THEN physicsmark+chemistrymark+biologymark END ) 'parttest1',
		max(case when quiz_id ='104' THEN physicsmark+chemistrymark+biologymark END ) 'parttest2',
		max(case when quiz_id ='105' THEN physicsmark+chemistrymark+biologymark END ) 'parttest3',
		max(case when quiz_id ='106' THEN physicsmark+chemistrymark+biologymark END ) 'parttest4',
		max(case when quiz_id ='107' THEN physicsmark+chemistrymark+biologymark END ) 'parttest5',
		max(case when quiz_id ='108' THEN physicsmark+chemistrymark+biologymark END ) 'parttest6',
		max(case when quiz_id ='109' THEN physicsmark+chemistrymark+biologymark END ) 'parttest7',
		max(case when quiz_id ='110' THEN physicsmark+chemistrymark+biologymark END ) 'parttest8',
		max(case when quiz_id ='125' THEN physicsmark+chemistrymark+biologymark END ) 'fulltest1',
		max(case when quiz_id ='126' THEN physicsmark+chemistrymark+biologymark END ) 'fulltest2',
		max(case when quiz_id ='170' THEN physicsmark+chemistrymark+biologymark END ) 'fulltest3',
		max(case when quiz_id ='171' THEN physicsmark+chemistrymark+biologymark END ) 'fulltest4',
		max(case when quiz_id ='172' THEN physicsmark+chemistrymark+biologymark END ) 'fulltest5',
		max(case when quiz_id ='173' THEN physicsmark+chemistrymark+biologymark END ) 'fulltest6',
		max(case when quiz_id ='174' THEN physicsmark+chemistrymark+biologymark END ) 'fulltest7',
		max(case when quiz_id ='175' THEN physicsmark+chemistrymark+biologymark END ) 'fulltest8',
		max(case when quiz_id ='176' THEN physicsmark+chemistrymark+biologymark END ) 'fulltest9',
		max(case when quiz_id ='177' THEN physicsmark+chemistrymark+biologymark END ) 'fulltest10',
		max(case when quiz_id ='326' THEN physicsmark+chemistrymark+biologymark END ) 'fulltest11',
		max(case when quiz_id ='334' THEN physicsmark+chemistrymark+biologymark END ) 'fulltest12',
		max(case when quiz_id ='337' THEN physicsmark+chemistrymark+biologymark END ) 'fulltest13',
		max(case when quiz_id ='338' THEN physicsmark+chemistrymark+biologymark END ) 'fulltest14',
		max(case when quiz_id ='340' THEN physicsmark+chemistrymark+biologymark END ) 'fulltest15',
		max(case when quiz_id ='342' THEN physicsmark+chemistrymark+biologymark END ) 'fulltest16',
		max(case when quiz_id ='345' THEN physicsmark+chemistrymark+biologymark END ) 'fulltest17',
		max(case when quiz_id ='367' THEN physicsmark+chemistrymark+biologymark END ) 'fulltest18',
		max(case when quiz_id ='379' THEN physicsmark+chemistrymark+biologymark END ) 'fulltest19',
		max(case when quiz_id ='383' THEN physicsmark+chemistrymark+biologymark END ) 'fulltest20',
		max(case when quiz_id ='384' THEN physicsmark+chemistrymark+biologymark END ) 'fulltest21',
		max(case when quiz_id ='391' THEN physicsmark+chemistrymark+biologymark END ) 'fulltest22',
		max(case when quiz_id ='411' THEN physicsmark+chemistrymark+biologymark END ) 'fulltest23',
		max(case when quiz_id ='413' THEN physicsmark+chemistrymark+biologymark END ) 'fulltest24',
		max(case when quiz_id ='420' THEN physicsmark+chemistrymark+biologymark END ) 'fulltest25',


		ifnull(max(case when quiz_id ='1' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='2' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='3' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='4' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='5' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='6' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='7' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='8' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='9' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='10' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='11' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='12' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='13' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='14' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='15' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='16' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='17' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='18' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='19' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='20' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='21' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='22' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='23' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='24' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='25' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='26' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='27' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='28' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='29' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='30' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='31' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='32' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='33' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='34' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='35' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='36' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='37' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='38' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='39' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='40' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='41' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='42' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='43' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='44' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='45' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='46' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='47' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='48' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='49' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='50' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='51' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='52' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='53' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='54' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='55' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='56' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='57' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='58' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='59' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='60' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='61' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='62' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='63' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='64' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='65' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='66' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='67' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='68' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='69' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='70' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='71' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='72' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='73' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='74' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='75' THEN physicsmark+chemistrymark+biologymark END ),0) +
		
			ifnull(max(case when quiz_id ='103' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='104' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='105' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='106' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='107' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='108' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='109' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='110' THEN physicsmark+chemistrymark+biologymark END ),0) +
		
		ifnull(max(case when quiz_id ='125' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='126' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='170' THEN physicsmark+chemistrymark+biologymark END ),0) + 
		ifnull(max(case when quiz_id ='171' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='172' THEN physicsmark+chemistrymark+biologymark END ),0) + 
		ifnull(max(case when quiz_id ='173' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='174' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='175' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='176' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='177' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='326' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='334' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='337' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='338' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='340' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='342' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='345' THEN physicsmark+chemistrymark+biologymark END ),0) + 
		ifnull(max(case when quiz_id ='367' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='379' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='383' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='384' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='391' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='411' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='413' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='420' THEN physicsmark+chemistrymark+biologymark END ),0)
		total
		FROM student_quiz_result a , quiz_info b where a.quiz_id = b.id and b.quiztype='JUT' and user_id collate utf8_general_ci in
				(select USER_NAME collate utf8_general_ci from user_details 
				where jnanasudhastandard = 'JNANASUDHAIIPU') GROUP BY user_id";
				$query = $this->db->query($sql);
				//print_r($sql);
			
		}
		if ($batch=='JNANASUDHAIPU' && $sub =='all'){
				$sql = "insert into  all_jut_rank
		select user_id,
		max(case when quiz_id ='1' THEN physicsmark+chemistrymark+biologymark END ) 'JUT1',
		max(case when quiz_id ='2' THEN physicsmark+chemistrymark+biologymark END ) 'JUT2',
		max(case when quiz_id ='3' THEN physicsmark+chemistrymark+biologymark END ) 'JUT3',
		max(case when quiz_id ='4' THEN physicsmark+chemistrymark+biologymark END ) 'JUT4',
		max(case when quiz_id ='5' THEN physicsmark+chemistrymark+biologymark END ) 'JUT5',
		max(case when quiz_id ='6' THEN physicsmark+chemistrymark+biologymark END ) 'JUT6',
		max(case when quiz_id ='7' THEN physicsmark+chemistrymark+biologymark END ) 'JUT7',
		max(case when quiz_id ='8' THEN physicsmark+chemistrymark+biologymark END ) 'JUT8',
		max(case when quiz_id ='9' THEN physicsmark+chemistrymark+biologymark END ) 'JUT9',
		max(case when quiz_id ='10' THEN physicsmark+chemistrymark+biologymark END ) 'JUT10',
		max(case when quiz_id ='11' THEN physicsmark+chemistrymark+biologymark END ) 'JUT11',
		max(case when quiz_id ='12' THEN physicsmark+chemistrymark+biologymark END ) 'JUT12',
		max(case when quiz_id ='13' THEN physicsmark+chemistrymark+biologymark END ) 'JUT13',
		max(case when quiz_id ='14' THEN physicsmark+chemistrymark+biologymark END ) 'JUT14',
		max(case when quiz_id ='15' THEN physicsmark+chemistrymark+biologymark END ) 'JUT15',
		max(case when quiz_id ='16' THEN physicsmark+chemistrymark+biologymark END ) 'JUT16',
		max(case when quiz_id ='17' THEN physicsmark+chemistrymark+biologymark END ) 'JUT17',
		max(case when quiz_id ='18' THEN physicsmark+chemistrymark+biologymark END ) 'JUT18',
		max(case when quiz_id ='19' THEN physicsmark+chemistrymark+biologymark END ) 'JUT19',
		max(case when quiz_id ='20' THEN physicsmark+chemistrymark+biologymark END ) 'JUT20',
		max(case when quiz_id ='21' THEN physicsmark+chemistrymark+biologymark END ) 'JUT21',
		max(case when quiz_id ='22' THEN physicsmark+chemistrymark+biologymark END ) 'JUT22',
		max(case when quiz_id ='23' THEN physicsmark+chemistrymark+biologymark END ) 'JUT23',
		max(case when quiz_id ='24' THEN physicsmark+chemistrymark+biologymark END ) 'JUT24',
		max(case when quiz_id ='25' THEN physicsmark+chemistrymark+biologymark END ) 'JUT25',
		max(case when quiz_id ='26' THEN physicsmark+chemistrymark+biologymark END ) 'JUT26',
		max(case when quiz_id ='27' THEN physicsmark+chemistrymark+biologymark END ) 'JUT27',
		max(case when quiz_id ='28' THEN physicsmark+chemistrymark+biologymark END ) 'JUT28',
		max(case when quiz_id ='29' THEN physicsmark+chemistrymark+biologymark END ) 'JUT29',
		max(case when quiz_id ='30' THEN physicsmark+chemistrymark+biologymark END ) 'JUT30',
		max(case when quiz_id ='31' THEN physicsmark+chemistrymark+biologymark END ) 'JUT31',
		max(case when quiz_id ='32' THEN physicsmark+chemistrymark+biologymark END ) 'JUT32',
		max(case when quiz_id ='33' THEN physicsmark+chemistrymark+biologymark END ) 'JUT33',
		max(case when quiz_id ='34' THEN physicsmark+chemistrymark+biologymark END ) 'JUT34',
		max(case when quiz_id ='35' THEN physicsmark+chemistrymark+biologymark END ) 'JUT35',
		max(case when quiz_id ='36' THEN physicsmark+chemistrymark+biologymark END ) 'JUT36',
		max(case when quiz_id ='37' THEN physicsmark+chemistrymark+biologymark END ) 'JUT37',
		max(case when quiz_id ='38' THEN physicsmark+chemistrymark+biologymark END ) 'JUT38',
		max(case when quiz_id ='39' THEN physicsmark+chemistrymark+biologymark END ) 'JUT39',
		max(case when quiz_id ='40' THEN physicsmark+chemistrymark+biologymark END ) 'JUT40',
		max(case when quiz_id ='41' THEN physicsmark+chemistrymark+biologymark END ) 'JUT41',
		max(case when quiz_id ='42' THEN physicsmark+chemistrymark+biologymark END ) 'JUT42',
		max(case when quiz_id ='43' THEN physicsmark+chemistrymark+biologymark END ) 'JUT43',
		max(case when quiz_id ='44' THEN physicsmark+chemistrymark+biologymark END ) 'JUT44',
		max(case when quiz_id ='45' THEN physicsmark+chemistrymark+biologymark END ) 'JUT45',
		max(case when quiz_id ='46' THEN physicsmark+chemistrymark+biologymark END ) 'JUT46',
		max(case when quiz_id ='47' THEN physicsmark+chemistrymark+biologymark END ) 'JUT47',
		max(case when quiz_id ='48' THEN physicsmark+chemistrymark+biologymark END ) 'JUT48',
		max(case when quiz_id ='49' THEN physicsmark+chemistrymark+biologymark END ) 'JUT49',
		max(case when quiz_id ='50' THEN physicsmark+chemistrymark+biologymark END ) 'JUT50',
		max(case when quiz_id ='51' THEN physicsmark+chemistrymark+biologymark END ) 'JUT51',
		max(case when quiz_id ='52' THEN physicsmark+chemistrymark+biologymark END ) 'JUT52',
		max(case when quiz_id ='53' THEN physicsmark+chemistrymark+biologymark END ) 'JUT53',
		max(case when quiz_id ='54' THEN physicsmark+chemistrymark+biologymark END ) 'JUT54',
		max(case when quiz_id ='55' THEN physicsmark+chemistrymark+biologymark END ) 'JUT55',
		max(case when quiz_id ='56' THEN physicsmark+chemistrymark+biologymark END ) 'JUT56',
		max(case when quiz_id ='57' THEN physicsmark+chemistrymark+biologymark END ) 'JUT57',
		max(case when quiz_id ='58' THEN physicsmark+chemistrymark+biologymark END ) 'JUT58',
		max(case when quiz_id ='59' THEN physicsmark+chemistrymark+biologymark END ) 'JUT59',
		max(case when quiz_id ='60' THEN physicsmark+chemistrymark+biologymark END ) 'JUT60',
		max(case when quiz_id ='61' THEN physicsmark+chemistrymark+biologymark END ) 'JUT61',
		max(case when quiz_id ='62' THEN physicsmark+chemistrymark+biologymark END ) 'JUT62',
		max(case when quiz_id ='63' THEN physicsmark+chemistrymark+biologymark END ) 'JUT63',
		max(case when quiz_id ='64' THEN physicsmark+chemistrymark+biologymark END ) 'JUT64',
		max(case when quiz_id ='65' THEN physicsmark+chemistrymark+biologymark END ) 'JUT65',
		max(case when quiz_id ='66' THEN physicsmark+chemistrymark+biologymark END ) 'JUT66',
		max(case when quiz_id ='67' THEN physicsmark+chemistrymark+biologymark END ) 'JUT67',
		max(case when quiz_id ='68' THEN physicsmark+chemistrymark+biologymark END ) 'JUT68',
		max(case when quiz_id ='69' THEN physicsmark+chemistrymark+biologymark END ) 'JUT69',
		max(case when quiz_id ='70' THEN physicsmark+chemistrymark+biologymark END ) 'JUT70',
		max(case when quiz_id ='71' THEN physicsmark+chemistrymark+biologymark END ) 'JUT71',
		max(case when quiz_id ='72' THEN physicsmark+chemistrymark+biologymark END ) 'JUT72',
		max(case when quiz_id ='73' THEN physicsmark+chemistrymark+biologymark END ) 'JUT73',
		max(case when quiz_id ='74' THEN physicsmark+chemistrymark+biologymark END ) 'JUT74',
		max(case when quiz_id ='75' THEN physicsmark+chemistrymark+biologymark END ) 'JUT75',
		
		max(case when quiz_id ='103' THEN physicsmark+chemistrymark+biologymark END ) 'parttest1',
		max(case when quiz_id ='104' THEN physicsmark+chemistrymark+biologymark END ) 'parttest2',
		max(case when quiz_id ='105' THEN physicsmark+chemistrymark+biologymark END ) 'parttest3',
		max(case when quiz_id ='106' THEN physicsmark+chemistrymark+biologymark END ) 'parttest4',
		max(case when quiz_id ='107' THEN physicsmark+chemistrymark+biologymark END ) 'parttest5',
		max(case when quiz_id ='108' THEN physicsmark+chemistrymark+biologymark END ) 'parttest6',
		max(case when quiz_id ='109' THEN physicsmark+chemistrymark+biologymark END ) 'parttest7',
		max(case when quiz_id ='110' THEN physicsmark+chemistrymark+biologymark END ) 'parttest8',
		max(case when quiz_id ='125' THEN physicsmark+chemistrymark+biologymark END ) 'fulltest1',
		max(case when quiz_id ='126' THEN physicsmark+chemistrymark+biologymark END ) 'fulltest2',
		max(case when quiz_id ='170' THEN physicsmark+chemistrymark+biologymark END ) 'fulltest3',
		max(case when quiz_id ='171' THEN physicsmark+chemistrymark+biologymark END ) 'fulltest4',
		max(case when quiz_id ='172' THEN physicsmark+chemistrymark+biologymark END ) 'fulltest5',
		max(case when quiz_id ='173' THEN physicsmark+chemistrymark+biologymark END ) 'fulltest6',
		max(case when quiz_id ='174' THEN physicsmark+chemistrymark+biologymark END ) 'fulltest7',
		max(case when quiz_id ='175' THEN physicsmark+chemistrymark+biologymark END ) 'fulltest8',
		max(case when quiz_id ='176' THEN physicsmark+chemistrymark+biologymark END ) 'fulltest9',
		max(case when quiz_id ='177' THEN physicsmark+chemistrymark+biologymark END ) 'fulltest10',
		max(case when quiz_id ='326' THEN physicsmark+chemistrymark+biologymark END ) 'fulltest11',
		max(case when quiz_id ='334' THEN physicsmark+chemistrymark+biologymark END ) 'fulltest12',
		max(case when quiz_id ='337' THEN physicsmark+chemistrymark+biologymark END ) 'fulltest13',
		max(case when quiz_id ='338' THEN physicsmark+chemistrymark+biologymark END ) 'fulltest14',
		max(case when quiz_id ='340' THEN physicsmark+chemistrymark+biologymark END ) 'fulltest15',
		max(case when quiz_id ='342' THEN physicsmark+chemistrymark+biologymark END ) 'fulltest16',
		max(case when quiz_id ='345' THEN physicsmark+chemistrymark+biologymark END ) 'fulltest17',
		max(case when quiz_id ='367' THEN physicsmark+chemistrymark+biologymark END ) 'fulltest18',
		max(case when quiz_id ='379' THEN physicsmark+chemistrymark+biologymark END ) 'fulltest19',
		max(case when quiz_id ='383' THEN physicsmark+chemistrymark+biologymark END ) 'fulltest20',
		max(case when quiz_id ='384' THEN physicsmark+chemistrymark+biologymark END ) 'fulltest21',
		max(case when quiz_id ='391' THEN physicsmark+chemistrymark+biologymark END ) 'fulltest22',
		max(case when quiz_id ='411' THEN physicsmark+chemistrymark+biologymark END ) 'fulltest23',
		max(case when quiz_id ='413' THEN physicsmark+chemistrymark+biologymark END ) 'fulltest24',
		max(case when quiz_id ='420' THEN physicsmark+chemistrymark+biologymark END ) 'fulltest25',


		ifnull(max(case when quiz_id ='1' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='2' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='3' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='4' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='5' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='6' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='7' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='8' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='9' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='10' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='11' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='12' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='13' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='14' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='15' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='16' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='17' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='18' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='19' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='20' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='21' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='22' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='23' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='24' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='25' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='26' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='27' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='28' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='29' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='30' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='31' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='32' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='33' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='34' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='35' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='36' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='37' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='38' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='39' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='40' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='41' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='42' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='43' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='44' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='45' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='46' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='47' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='48' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='49' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='50' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='51' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='52' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='53' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='54' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='55' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='56' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='57' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='58' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='59' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='60' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='61' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='62' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='63' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='64' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='65' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='66' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='67' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='68' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='69' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='70' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='71' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='72' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='73' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='74' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='75' THEN physicsmark+chemistrymark+biologymark END ),0) +
	ifnull(max(case when quiz_id ='103' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='104' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='105' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='106' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='107' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='108' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='109' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='110' THEN physicsmark+chemistrymark+biologymark END ),0) +
		
		ifnull(max(case when quiz_id ='125' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='126' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='170' THEN physicsmark+chemistrymark+biologymark END ),0) + 
		ifnull(max(case when quiz_id ='171' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='172' THEN physicsmark+chemistrymark+biologymark END ),0) + 
		ifnull(max(case when quiz_id ='173' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='174' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='175' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='176' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='177' THEN physicsmark+chemistrymark+biologymark END ),0)	+
ifnull(max(case when quiz_id ='326' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='334' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='337' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='338' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='340' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='342' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='345' THEN physicsmark+chemistrymark+biologymark END ),0) + 
		ifnull(max(case when quiz_id ='367' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='379' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='383' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='384' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='391' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='411' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='413' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='420' THEN physicsmark+chemistrymark+biologymark END ),0)		total
		FROM student_quiz_result a , quiz_info b where a.quiz_id = b.id and b.quiztype='JUT' and user_id collate utf8_general_ci in
				(select USER_NAME collate utf8_general_ci from user_details 
				where jnanasudhastandard = 'JNANASUDHAIPU') GROUP BY user_id";
				$query = $this->db->query($sql);
				//print_r($sql);
			
		}
		if ($batch=='JNANAAMRUTHA' && $sub =='all'){
				$sql = "insert into  all_jut_rank
		select user_id,
		max(case when quiz_id ='1' THEN physicsmark+chemistrymark+biologymark END ) 'JUT1',
		max(case when quiz_id ='2' THEN physicsmark+chemistrymark+biologymark END ) 'JUT2',
		max(case when quiz_id ='3' THEN physicsmark+chemistrymark+biologymark END ) 'JUT3',
		max(case when quiz_id ='4' THEN physicsmark+chemistrymark+biologymark END ) 'JUT4',
		max(case when quiz_id ='5' THEN physicsmark+chemistrymark+biologymark END ) 'JUT5',
		max(case when quiz_id ='6' THEN physicsmark+chemistrymark+biologymark END ) 'JUT6',
		max(case when quiz_id ='7' THEN physicsmark+chemistrymark+biologymark END ) 'JUT7',
		max(case when quiz_id ='8' THEN physicsmark+chemistrymark+biologymark END ) 'JUT8',
		max(case when quiz_id ='9' THEN physicsmark+chemistrymark+biologymark END ) 'JUT9',
		max(case when quiz_id ='10' THEN physicsmark+chemistrymark+biologymark END ) 'JUT10',
		max(case when quiz_id ='11' THEN physicsmark+chemistrymark+biologymark END ) 'JUT11',
		max(case when quiz_id ='12' THEN physicsmark+chemistrymark+biologymark END ) 'JUT12',
		max(case when quiz_id ='13' THEN physicsmark+chemistrymark+biologymark END ) 'JUT13',
		max(case when quiz_id ='14' THEN physicsmark+chemistrymark+biologymark END ) 'JUT14',
		max(case when quiz_id ='15' THEN physicsmark+chemistrymark+biologymark END ) 'JUT15',
		max(case when quiz_id ='16' THEN physicsmark+chemistrymark+biologymark END ) 'JUT16',
		max(case when quiz_id ='17' THEN physicsmark+chemistrymark+biologymark END ) 'JUT17',
		max(case when quiz_id ='18' THEN physicsmark+chemistrymark+biologymark END ) 'JUT18',
		max(case when quiz_id ='19' THEN physicsmark+chemistrymark+biologymark END ) 'JUT19',
		max(case when quiz_id ='20' THEN physicsmark+chemistrymark+biologymark END ) 'JUT20',
		max(case when quiz_id ='21' THEN physicsmark+chemistrymark+biologymark END ) 'JUT21',
		max(case when quiz_id ='22' THEN physicsmark+chemistrymark+biologymark END ) 'JUT22',
		max(case when quiz_id ='23' THEN physicsmark+chemistrymark+biologymark END ) 'JUT23',
		max(case when quiz_id ='24' THEN physicsmark+chemistrymark+biologymark END ) 'JUT24',
		max(case when quiz_id ='25' THEN physicsmark+chemistrymark+biologymark END ) 'JUT25',
		max(case when quiz_id ='26' THEN physicsmark+chemistrymark+biologymark END ) 'JUT26',
		max(case when quiz_id ='27' THEN physicsmark+chemistrymark+biologymark END ) 'JUT27',
		max(case when quiz_id ='28' THEN physicsmark+chemistrymark+biologymark END ) 'JUT28',
		max(case when quiz_id ='29' THEN physicsmark+chemistrymark+biologymark END ) 'JUT29',
		max(case when quiz_id ='30' THEN physicsmark+chemistrymark+biologymark END ) 'JUT30',
		max(case when quiz_id ='31' THEN physicsmark+chemistrymark+biologymark END ) 'JUT31',
		max(case when quiz_id ='32' THEN physicsmark+chemistrymark+biologymark END ) 'JUT32',
		max(case when quiz_id ='33' THEN physicsmark+chemistrymark+biologymark END ) 'JUT33',
		max(case when quiz_id ='34' THEN physicsmark+chemistrymark+biologymark END ) 'JUT34',
		max(case when quiz_id ='35' THEN physicsmark+chemistrymark+biologymark END ) 'JUT35',
		max(case when quiz_id ='36' THEN physicsmark+chemistrymark+biologymark END ) 'JUT36',
		max(case when quiz_id ='37' THEN physicsmark+chemistrymark+biologymark END ) 'JUT37',
		max(case when quiz_id ='38' THEN physicsmark+chemistrymark+biologymark END ) 'JUT38',
		max(case when quiz_id ='39' THEN physicsmark+chemistrymark+biologymark END ) 'JUT39',
		max(case when quiz_id ='40' THEN physicsmark+chemistrymark+biologymark END ) 'JUT40',
		max(case when quiz_id ='41' THEN physicsmark+chemistrymark+biologymark END ) 'JUT41',
		max(case when quiz_id ='42' THEN physicsmark+chemistrymark+biologymark END ) 'JUT42',
		max(case when quiz_id ='43' THEN physicsmark+chemistrymark+biologymark END ) 'JUT43',
		max(case when quiz_id ='44' THEN physicsmark+chemistrymark+biologymark END ) 'JUT44',
		max(case when quiz_id ='45' THEN physicsmark+chemistrymark+biologymark END ) 'JUT45',
		max(case when quiz_id ='46' THEN physicsmark+chemistrymark+biologymark END ) 'JUT46',
		max(case when quiz_id ='47' THEN physicsmark+chemistrymark+biologymark END ) 'JUT47',
		max(case when quiz_id ='48' THEN physicsmark+chemistrymark+biologymark END ) 'JUT48',
		max(case when quiz_id ='49' THEN physicsmark+chemistrymark+biologymark END ) 'JUT49',
		max(case when quiz_id ='50' THEN physicsmark+chemistrymark+biologymark END ) 'JUT50',
		max(case when quiz_id ='51' THEN physicsmark+chemistrymark+biologymark END ) 'JUT51',
		max(case when quiz_id ='52' THEN physicsmark+chemistrymark+biologymark END ) 'JUT52',
		max(case when quiz_id ='53' THEN physicsmark+chemistrymark+biologymark END ) 'JUT53',
		max(case when quiz_id ='54' THEN physicsmark+chemistrymark+biologymark END ) 'JUT54',
		max(case when quiz_id ='55' THEN physicsmark+chemistrymark+biologymark END ) 'JUT55',
		max(case when quiz_id ='56' THEN physicsmark+chemistrymark+biologymark END ) 'JUT56',
		max(case when quiz_id ='57' THEN physicsmark+chemistrymark+biologymark END ) 'JUT57',
		max(case when quiz_id ='58' THEN physicsmark+chemistrymark+biologymark END ) 'JUT58',
		max(case when quiz_id ='59' THEN physicsmark+chemistrymark+biologymark END ) 'JUT59',
		max(case when quiz_id ='60' THEN physicsmark+chemistrymark+biologymark END ) 'JUT60',
		max(case when quiz_id ='61' THEN physicsmark+chemistrymark+biologymark END ) 'JUT61',
		max(case when quiz_id ='62' THEN physicsmark+chemistrymark+biologymark END ) 'JUT62',
		max(case when quiz_id ='63' THEN physicsmark+chemistrymark+biologymark END ) 'JUT63',
		max(case when quiz_id ='64' THEN physicsmark+chemistrymark+biologymark END ) 'JUT64',
		max(case when quiz_id ='65' THEN physicsmark+chemistrymark+biologymark END ) 'JUT65',
		max(case when quiz_id ='66' THEN physicsmark+chemistrymark+biologymark END ) 'JUT66',
		max(case when quiz_id ='67' THEN physicsmark+chemistrymark+biologymark END ) 'JUT67',
		max(case when quiz_id ='68' THEN physicsmark+chemistrymark+biologymark END ) 'JUT68',
		max(case when quiz_id ='69' THEN physicsmark+chemistrymark+biologymark END ) 'JUT69',
		max(case when quiz_id ='70' THEN physicsmark+chemistrymark+biologymark END ) 'JUT70',
		max(case when quiz_id ='71' THEN physicsmark+chemistrymark+biologymark END ) 'JUT71',
		max(case when quiz_id ='72' THEN physicsmark+chemistrymark+biologymark END ) 'JUT72',
		max(case when quiz_id ='73' THEN physicsmark+chemistrymark+biologymark END ) 'JUT73',
		max(case when quiz_id ='74' THEN physicsmark+chemistrymark+biologymark END ) 'JUT74',
		max(case when quiz_id ='75' THEN physicsmark+chemistrymark+biologymark END ) 'JUT75',
		
		max(case when quiz_id ='103' THEN physicsmark+chemistrymark+biologymark END ) 'parttest1',
		max(case when quiz_id ='104' THEN physicsmark+chemistrymark+biologymark END ) 'parttest2',
		max(case when quiz_id ='105' THEN physicsmark+chemistrymark+biologymark END ) 'parttest3',
		max(case when quiz_id ='106' THEN physicsmark+chemistrymark+biologymark END ) 'parttest4',
		max(case when quiz_id ='107' THEN physicsmark+chemistrymark+biologymark END ) 'parttest5',
		max(case when quiz_id ='108' THEN physicsmark+chemistrymark+biologymark END ) 'parttest6',
		max(case when quiz_id ='109' THEN physicsmark+chemistrymark+biologymark END ) 'parttest7',
		max(case when quiz_id ='110' THEN physicsmark+chemistrymark+biologymark END ) 'parttest8',
		max(case when quiz_id ='125' THEN physicsmark+chemistrymark+biologymark END ) 'fulltest1',
		max(case when quiz_id ='126' THEN physicsmark+chemistrymark+biologymark END ) 'fulltest2',
		max(case when quiz_id ='170' THEN physicsmark+chemistrymark+biologymark END ) 'fulltest3',
		max(case when quiz_id ='171' THEN physicsmark+chemistrymark+biologymark END ) 'fulltest4',
		max(case when quiz_id ='172' THEN physicsmark+chemistrymark+biologymark END ) 'fulltest5',
		max(case when quiz_id ='173' THEN physicsmark+chemistrymark+biologymark END ) 'fulltest6',
		max(case when quiz_id ='174' THEN physicsmark+chemistrymark+biologymark END ) 'fulltest7',
		max(case when quiz_id ='175' THEN physicsmark+chemistrymark+biologymark END ) 'fulltest8',
		max(case when quiz_id ='176' THEN physicsmark+chemistrymark+biologymark END ) 'fulltest9',
		max(case when quiz_id ='177' THEN physicsmark+chemistrymark+biologymark END ) 'fulltest10',
		max(case when quiz_id ='326' THEN physicsmark+chemistrymark+biologymark END ) 'fulltest11',
		max(case when quiz_id ='334' THEN physicsmark+chemistrymark+biologymark END ) 'fulltest12',
		max(case when quiz_id ='337' THEN physicsmark+chemistrymark+biologymark END ) 'fulltest13',
		max(case when quiz_id ='338' THEN physicsmark+chemistrymark+biologymark END ) 'fulltest14',
		max(case when quiz_id ='340' THEN physicsmark+chemistrymark+biologymark END ) 'fulltest15',
		max(case when quiz_id ='342' THEN physicsmark+chemistrymark+biologymark END ) 'fulltest16',
		max(case when quiz_id ='345' THEN physicsmark+chemistrymark+biologymark END ) 'fulltest17',
		max(case when quiz_id ='367' THEN physicsmark+chemistrymark+biologymark END ) 'fulltest18',
		max(case when quiz_id ='379' THEN physicsmark+chemistrymark+biologymark END ) 'fulltest19',
		max(case when quiz_id ='383' THEN physicsmark+chemistrymark+biologymark END ) 'fulltest20',
		max(case when quiz_id ='384' THEN physicsmark+chemistrymark+biologymark END ) 'fulltest21',
		max(case when quiz_id ='391' THEN physicsmark+chemistrymark+biologymark END ) 'fulltest22',
		max(case when quiz_id ='411' THEN physicsmark+chemistrymark+biologymark END ) 'fulltest23',
		max(case when quiz_id ='413' THEN physicsmark+chemistrymark+biologymark END ) 'fulltest24',
		max(case when quiz_id ='420' THEN physicsmark+chemistrymark+biologymark END ) 'fulltest25',


		ifnull(max(case when quiz_id ='1' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='2' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='3' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='4' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='5' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='6' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='7' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='8' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='9' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='10' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='11' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='12' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='13' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='14' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='15' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='16' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='17' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='18' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='19' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='20' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='21' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='22' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='23' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='24' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='25' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='26' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='27' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='28' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='29' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='30' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='31' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='32' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='33' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='34' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='35' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='36' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='37' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='38' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='39' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='40' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='41' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='42' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='43' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='44' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='45' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='46' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='47' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='48' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='49' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='50' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='51' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='52' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='53' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='54' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='55' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='56' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='57' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='58' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='59' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='60' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='61' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='62' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='63' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='64' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='65' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='66' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='67' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='68' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='69' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='70' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='71' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='72' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='73' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='74' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='75' THEN physicsmark+chemistrymark+biologymark END ),0) +
	ifnull(max(case when quiz_id ='103' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='104' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='105' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='106' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='107' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='108' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='109' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='110' THEN physicsmark+chemistrymark+biologymark END ),0) +
		
		ifnull(max(case when quiz_id ='125' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='126' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='170' THEN physicsmark+chemistrymark+biologymark END ),0) + 
		ifnull(max(case when quiz_id ='171' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='172' THEN physicsmark+chemistrymark+biologymark END ),0) + 
		ifnull(max(case when quiz_id ='173' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='174' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='175' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='176' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='177' THEN physicsmark+chemistrymark+biologymark END ),0)	+
ifnull(max(case when quiz_id ='326' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='334' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='337' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='338' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='340' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='342' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='345' THEN physicsmark+chemistrymark+biologymark END ),0) + 
		ifnull(max(case when quiz_id ='367' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='379' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='383' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='384' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='391' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='411' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='413' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='420' THEN physicsmark+chemistrymark+biologymark END ),0)		total
		FROM student_quiz_result a , quiz_info b where a.quiz_id = b.id and b.quiztype='JUT' and user_id collate utf8_general_ci in
				(select USER_NAME collate utf8_general_ci from user_details 
				where jnanasudhastandard = 'JNANAAMRUTHA') GROUP BY user_id";
				$query = $this->db->query($sql);
				//print_r($sql);
			
		}
		if ($batch=='all' && $sub =='physics'){
			$sql = "insert into  all_jut_rank
		select user_id,
		max(case when quiz_id ='1' THEN physicsmark END ) 'JUT1',
		max(case when quiz_id ='2' THEN physicsmark END ) 'JUT2',
		max(case when quiz_id ='3' THEN physicsmark END ) 'JUT3',
		max(case when quiz_id ='4' THEN physicsmark END ) 'JUT4',
		max(case when quiz_id ='5' THEN physicsmark END ) 'JUT5',
		max(case when quiz_id ='6' THEN physicsmark END ) 'JUT6',
		max(case when quiz_id ='7' THEN physicsmark END ) 'JUT7',
		max(case when quiz_id ='8' THEN physicsmark END ) 'JUT8',
		max(case when quiz_id ='9' THEN physicsmark END ) 'JUT9',
		max(case when quiz_id ='10' THEN physicsmark END ) 'JUT10',
		max(case when quiz_id ='11' THEN physicsmark END ) 'JUT11',
		max(case when quiz_id ='12' THEN physicsmark END ) 'JUT12',
		max(case when quiz_id ='13' THEN physicsmark END ) 'JUT13',
		max(case when quiz_id ='14' THEN physicsmark END ) 'JUT14',
		max(case when quiz_id ='15' THEN physicsmark END ) 'JUT15',
		max(case when quiz_id ='16' THEN physicsmark END ) 'JUT16',
		max(case when quiz_id ='17' THEN physicsmark END ) 'JUT17',
		max(case when quiz_id ='18' THEN physicsmark END ) 'JUT18',
		max(case when quiz_id ='19' THEN physicsmark END ) 'JUT19',
		max(case when quiz_id ='20' THEN physicsmark END ) 'JUT20',
		max(case when quiz_id ='21' THEN physicsmark END ) 'JUT21',
		max(case when quiz_id ='22' THEN physicsmark END ) 'JUT22',
		max(case when quiz_id ='23' THEN physicsmark END ) 'JUT23',
		max(case when quiz_id ='24' THEN physicsmark END ) 'JUT24',
		max(case when quiz_id ='25' THEN physicsmark END ) 'JUT25',
		max(case when quiz_id ='26' THEN physicsmark END ) 'JUT26',
		max(case when quiz_id ='27' THEN physicsmark END ) 'JUT27',
		max(case when quiz_id ='28' THEN physicsmark END ) 'JUT28',
		max(case when quiz_id ='29' THEN physicsmark END ) 'JUT29',
		max(case when quiz_id ='30' THEN physicsmark END ) 'JUT30',
		max(case when quiz_id ='31' THEN physicsmark END ) 'JUT31',
		max(case when quiz_id ='32' THEN physicsmark END ) 'JUT32',
		max(case when quiz_id ='33' THEN physicsmark END ) 'JUT33',
		max(case when quiz_id ='34' THEN physicsmark END ) 'JUT34',
		max(case when quiz_id ='35' THEN physicsmark END ) 'JUT35',
		max(case when quiz_id ='36' THEN physicsmark END ) 'JUT36',
		max(case when quiz_id ='37' THEN physicsmark END ) 'JUT37',
		max(case when quiz_id ='38' THEN physicsmark END ) 'JUT38',
		max(case when quiz_id ='39' THEN physicsmark END ) 'JUT39',
		max(case when quiz_id ='40' THEN physicsmark END ) 'JUT40',
		max(case when quiz_id ='41' THEN physicsmark END ) 'JUT41',
		max(case when quiz_id ='42' THEN physicsmark END ) 'JUT42',
		max(case when quiz_id ='43' THEN physicsmark END ) 'JUT43',
		max(case when quiz_id ='44' THEN physicsmark END ) 'JUT44',
		max(case when quiz_id ='45' THEN physicsmark END ) 'JUT45',
		max(case when quiz_id ='46' THEN physicsmark END ) 'JUT46',
		max(case when quiz_id ='47' THEN physicsmark END ) 'JUT47',
		max(case when quiz_id ='48' THEN physicsmark END ) 'JUT48',
		max(case when quiz_id ='49' THEN physicsmark END ) 'JUT49',
		max(case when quiz_id ='50' THEN physicsmark END ) 'JUT50',
		max(case when quiz_id ='51' THEN physicsmark END ) 'JUT51',
		max(case when quiz_id ='52' THEN physicsmark END ) 'JUT52',
		max(case when quiz_id ='53' THEN physicsmark END ) 'JUT53',
		max(case when quiz_id ='54' THEN physicsmark END ) 'JUT54',
		max(case when quiz_id ='55' THEN physicsmark END ) 'JUT55',
		max(case when quiz_id ='56' THEN physicsmark END ) 'JUT56',
		max(case when quiz_id ='57' THEN physicsmark END ) 'JUT57',
		max(case when quiz_id ='58' THEN physicsmark END ) 'JUT58',
		max(case when quiz_id ='59' THEN physicsmark END ) 'JUT59',
		max(case when quiz_id ='60' THEN physicsmark END ) 'JUT60',
		max(case when quiz_id ='61' THEN physicsmark END ) 'JUT61',
		max(case when quiz_id ='62' THEN physicsmark END ) 'JUT62',
		max(case when quiz_id ='63' THEN physicsmark END ) 'JUT63',
		max(case when quiz_id ='64' THEN physicsmark END ) 'JUT64',
		max(case when quiz_id ='65' THEN physicsmark END ) 'JUT65',
		max(case when quiz_id ='66' THEN physicsmark END ) 'JUT66',
		max(case when quiz_id ='67' THEN physicsmark END ) 'JUT67',
		max(case when quiz_id ='68' THEN physicsmark END ) 'JUT68',
		max(case when quiz_id ='69' THEN physicsmark END ) 'JUT69',
		max(case when quiz_id ='70' THEN physicsmark END ) 'JUT70',
		max(case when quiz_id ='71' THEN physicsmark END ) 'JUT71',
		max(case when quiz_id ='72' THEN physicsmark END ) 'JUT72',
		max(case when quiz_id ='73' THEN physicsmark END ) 'JUT73',
		max(case when quiz_id ='74' THEN physicsmark END ) 'JUT74',
		max(case when quiz_id ='75' THEN physicsmark END ) 'JUT75',
		
		max(case when quiz_id ='103' THEN physicsmark END ) 'parttest1',
		max(case when quiz_id ='104' THEN physicsmark END ) 'parttest2',
		max(case when quiz_id ='105' THEN physicsmark END ) 'parttest3',
		max(case when quiz_id ='106' THEN physicsmark END ) 'parttest4',
		max(case when quiz_id ='107' THEN physicsmark END ) 'parttest5',
		max(case when quiz_id ='108' THEN physicsmark END ) 'parttest6',
		max(case when quiz_id ='109' THEN physicsmark END ) 'parttest7',
		max(case when quiz_id ='110' THEN physicsmark END ) 'parttest8',
		max(case when quiz_id ='125' THEN physicsmark END ) 'fulltest1',
		max(case when quiz_id ='126' THEN physicsmark END ) 'fulltest2',
		max(case when quiz_id ='170' THEN physicsmark END ) 'fulltest3',
		max(case when quiz_id ='171' THEN physicsmark END ) 'fulltest4',
		max(case when quiz_id ='172' THEN physicsmark END ) 'fulltest5',
		max(case when quiz_id ='173' THEN physicsmark END ) 'fulltest6',
		max(case when quiz_id ='174' THEN physicsmark END ) 'fulltest7',
		max(case when quiz_id ='175' THEN physicsmark END ) 'fulltest8',
		max(case when quiz_id ='176' THEN physicsmark END ) 'fulltest9',
		max(case when quiz_id ='177' THEN physicsmark END ) 'fulltest10',
		max(case when quiz_id ='326' THEN physicsmark END ) 'fulltest11',
		max(case when quiz_id ='334' THEN physicsmark END ) 'fulltest12',
		max(case when quiz_id ='337' THEN physicsmark END ) 'fulltest13',
		max(case when quiz_id ='338' THEN physicsmark END ) 'fulltest14',
		max(case when quiz_id ='340' THEN physicsmark END ) 'fulltest15',
		max(case when quiz_id ='342' THEN physicsmark END ) 'fulltest16',
		max(case when quiz_id ='345' THEN physicsmark END ) 'fulltest17',
		max(case when quiz_id ='367' THEN physicsmark END ) 'fulltest18',
		max(case when quiz_id ='379' THEN physicsmark END ) 'fulltest19',
		max(case when quiz_id ='383' THEN physicsmark END ) 'fulltest20',
		max(case when quiz_id ='384' THEN physicsmark END ) 'fulltest21',
		max(case when quiz_id ='391' THEN physicsmark END ) 'fulltest22',
		max(case when quiz_id ='411' THEN physicsmark END ) 'fulltest23',
		max(case when quiz_id ='413' THEN physicsmark END ) 'fulltest24',
		max(case when quiz_id ='420' THEN physicsmark END ) 'fulltest25',


		ifnull(max(case when quiz_id ='1' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='2' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='3' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='4' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='5' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='6' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='7' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='8' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='9' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='10' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='11' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='12' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='13' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='14' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='15' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='16' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='17' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='18' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='19' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='20' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='21' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='22' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='23' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='24' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='25' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='26' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='27' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='28' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='29' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='30' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='31' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='32' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='33' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='34' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='35' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='36' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='37' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='38' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='39' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='40' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='41' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='42' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='43' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='44' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='45' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='46' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='47' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='48' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='49' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='50' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='51' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='52' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='53' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='54' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='55' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='56' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='57' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='58' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='59' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='60' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='61' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='62' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='63' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='64' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='65' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='66' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='67' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='68' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='69' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='70' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='71' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='72' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='73' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='74' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='75' THEN physicsmark END ),0) +
	    ifnull(max(case when quiz_id ='103' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='104' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='105' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='106' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='107' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='108' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='109' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='110' THEN physicsmark END ),0) +
		
		ifnull(max(case when quiz_id ='125' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='126' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='170' THEN physicsmark END ),0) + 
		ifnull(max(case when quiz_id ='171' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='172' THEN physicsmark END ),0) + 
		ifnull(max(case when quiz_id ='173' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='174' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='175' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='176' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='177' THEN physicsmark END ),0) +
        ifnull(max(case when quiz_id ='326' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='334' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='337' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='338' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='340' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='342' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='345' THEN physicsmark END ),0) + 
		ifnull(max(case when quiz_id ='367' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='379' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='383' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='384' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='391' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='411' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='413' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='420' THEN physicsmark END ),0)		total
		FROM student_quiz_result a , quiz_info b where a.quiz_id = b.id and b.quiztype='JUT' GROUP BY user_id ORDER BY physicsmark desc ";
				$query = $this->db->query($sql);
				// echo '<pre>';
			   // print_r($sql);
			   // echo '</pre>';
		}
		if ($batch=='all' && $sub =='chemistry'){
			$sql = "insert into  all_jut_rank
		select user_id,
		max(case when quiz_id ='1' THEN chemistrymark END ) 'JUT1',
		max(case when quiz_id ='2' THEN chemistrymark END ) 'JUT2',
		max(case when quiz_id ='3' THEN chemistrymark END ) 'JUT3',
		max(case when quiz_id ='4' THEN chemistrymark END ) 'JUT4',
		max(case when quiz_id ='5' THEN chemistrymark END ) 'JUT5',
		max(case when quiz_id ='6' THEN chemistrymark END ) 'JUT6',
		max(case when quiz_id ='7' THEN chemistrymark END ) 'JUT7',
		max(case when quiz_id ='8' THEN chemistrymark END ) 'JUT8',
		max(case when quiz_id ='9' THEN chemistrymark END ) 'JUT9',
		max(case when quiz_id ='10' THEN chemistrymark END ) 'JUT10',
		max(case when quiz_id ='11' THEN chemistrymark END ) 'JUT11',
		max(case when quiz_id ='12' THEN chemistrymark END ) 'JUT12',
		max(case when quiz_id ='13' THEN chemistrymark END ) 'JUT13',
		max(case when quiz_id ='14' THEN chemistrymark END ) 'JUT14',
		max(case when quiz_id ='15' THEN chemistrymark END ) 'JUT15',
		max(case when quiz_id ='16' THEN chemistrymark END ) 'JUT16',
		max(case when quiz_id ='17' THEN chemistrymark END ) 'JUT17',
		max(case when quiz_id ='18' THEN chemistrymark END ) 'JUT18',
		max(case when quiz_id ='19' THEN chemistrymark END ) 'JUT19',
		max(case when quiz_id ='20' THEN chemistrymark END ) 'JUT20',
		max(case when quiz_id ='21' THEN chemistrymark END ) 'JUT21',
		max(case when quiz_id ='22' THEN chemistrymark END ) 'JUT22',
		max(case when quiz_id ='23' THEN chemistrymark END ) 'JUT23',
		max(case when quiz_id ='24' THEN chemistrymark END ) 'JUT24',
		max(case when quiz_id ='25' THEN chemistrymark END ) 'JUT25',
		max(case when quiz_id ='26' THEN chemistrymark END ) 'JUT26',
		max(case when quiz_id ='27' THEN chemistrymark END ) 'JUT27',
		max(case when quiz_id ='28' THEN chemistrymark END ) 'JUT28',
		max(case when quiz_id ='29' THEN chemistrymark END ) 'JUT29',
		max(case when quiz_id ='30' THEN chemistrymark END ) 'JUT30',
		max(case when quiz_id ='31' THEN chemistrymark END ) 'JUT31',
		max(case when quiz_id ='32' THEN chemistrymark END ) 'JUT32',
		max(case when quiz_id ='33' THEN chemistrymark END ) 'JUT33',
		max(case when quiz_id ='34' THEN chemistrymark END ) 'JUT34',
		max(case when quiz_id ='35' THEN chemistrymark END ) 'JUT35',
		max(case when quiz_id ='36' THEN chemistrymark END ) 'JUT36',
		max(case when quiz_id ='37' THEN chemistrymark END ) 'JUT37',
		max(case when quiz_id ='38' THEN chemistrymark END ) 'JUT38',
		max(case when quiz_id ='39' THEN chemistrymark END ) 'JUT39',
		max(case when quiz_id ='40' THEN chemistrymark END ) 'JUT40',
		max(case when quiz_id ='41' THEN chemistrymark END ) 'JUT41',
		max(case when quiz_id ='42' THEN chemistrymark END ) 'JUT42',
		max(case when quiz_id ='43' THEN chemistrymark END ) 'JUT43',
		max(case when quiz_id ='44' THEN chemistrymark END ) 'JUT44',
		max(case when quiz_id ='45' THEN chemistrymark END ) 'JUT45',
		max(case when quiz_id ='46' THEN chemistrymark END ) 'JUT46',
		max(case when quiz_id ='47' THEN chemistrymark END ) 'JUT47',
		max(case when quiz_id ='48' THEN chemistrymark END ) 'JUT48',
		max(case when quiz_id ='49' THEN chemistrymark END ) 'JUT49',
		max(case when quiz_id ='50' THEN chemistrymark END ) 'JUT50',
		max(case when quiz_id ='51' THEN chemistrymark END ) 'JUT51',
		max(case when quiz_id ='52' THEN chemistrymark END ) 'JUT52',
		max(case when quiz_id ='53' THEN chemistrymark END ) 'JUT53',
		max(case when quiz_id ='54' THEN chemistrymark END ) 'JUT54',
		max(case when quiz_id ='55' THEN chemistrymark END ) 'JUT55',
		max(case when quiz_id ='56' THEN chemistrymark END ) 'JUT56',
		max(case when quiz_id ='57' THEN chemistrymark END ) 'JUT57',
		max(case when quiz_id ='58' THEN chemistrymark END ) 'JUT58',
		max(case when quiz_id ='59' THEN chemistrymark END ) 'JUT59',
		max(case when quiz_id ='60' THEN chemistrymark END ) 'JUT60',
		max(case when quiz_id ='61' THEN chemistrymark END ) 'JUT61',
		max(case when quiz_id ='62' THEN chemistrymark END ) 'JUT62',
		max(case when quiz_id ='63' THEN chemistrymark END ) 'JUT63',
		max(case when quiz_id ='64' THEN chemistrymark END ) 'JUT64',
		max(case when quiz_id ='65' THEN chemistrymark END ) 'JUT65',
		max(case when quiz_id ='66' THEN chemistrymark END ) 'JUT66',
		max(case when quiz_id ='67' THEN chemistrymark END ) 'JUT67',
		max(case when quiz_id ='68' THEN chemistrymark END ) 'JUT68',
		max(case when quiz_id ='69' THEN chemistrymark END ) 'JUT69',
		max(case when quiz_id ='70' THEN chemistrymark END ) 'JUT70',
		max(case when quiz_id ='71' THEN chemistrymark END ) 'JUT71',
		max(case when quiz_id ='72' THEN chemistrymark END ) 'JUT72',
		max(case when quiz_id ='73' THEN chemistrymark END ) 'JUT73',
		max(case when quiz_id ='74' THEN chemistrymark END ) 'JUT74',
		max(case when quiz_id ='75' THEN chemistrymark END ) 'JUT75',
		max(case when quiz_id ='103' THEN chemistrymark END ) 'parttest1',
		max(case when quiz_id ='104' THEN chemistrymark END ) 'parttest2',
		max(case when quiz_id ='105' THEN chemistrymark END ) 'parttest3',
		max(case when quiz_id ='106' THEN chemistrymark END ) 'parttest4',
		max(case when quiz_id ='107' THEN chemistrymark END ) 'parttest5',
		max(case when quiz_id ='108' THEN chemistrymark END ) 'parttest6',
		max(case when quiz_id ='109' THEN chemistrymark END ) 'parttest7',
		max(case when quiz_id ='110' THEN chemistrymark END ) 'parttest8',
		max(case when quiz_id ='125' THEN chemistrymark END ) 'fulltest1',
		max(case when quiz_id ='126' THEN chemistrymark END ) 'fulltest2',
		max(case when quiz_id ='170' THEN chemistrymark END ) 'fulltest3',
		max(case when quiz_id ='171' THEN chemistrymark END ) 'fulltest4',
		max(case when quiz_id ='172' THEN chemistrymark END ) 'fulltest5',
		max(case when quiz_id ='173' THEN chemistrymark END ) 'fulltest6',
		max(case when quiz_id ='174' THEN chemistrymark END ) 'fulltest7',
		max(case when quiz_id ='175' THEN chemistrymark END ) 'fulltest8',
		max(case when quiz_id ='176' THEN chemistrymark END ) 'fulltest9',
		max(case when quiz_id ='177' THEN chemistrymark END ) 'fulltest10',
		max(case when quiz_id ='326' THEN chemistrymark END ) 'fulltest11',
		max(case when quiz_id ='334' THEN chemistrymark END ) 'fulltest12',
		max(case when quiz_id ='337' THEN chemistrymark END ) 'fulltest13',
		max(case when quiz_id ='338' THEN chemistrymark END ) 'fulltest14',
		max(case when quiz_id ='340' THEN chemistrymark END ) 'fulltest15',
		max(case when quiz_id ='342' THEN chemistrymark END ) 'fulltest16',
		max(case when quiz_id ='345' THEN chemistrymark END ) 'fulltest17',
		max(case when quiz_id ='367' THEN chemistrymark END ) 'fulltest18',
		max(case when quiz_id ='379' THEN chemistrymark END ) 'fulltest19',
		max(case when quiz_id ='383' THEN chemistrymark END ) 'fulltest20',
		max(case when quiz_id ='384' THEN chemistrymark END ) 'fulltest21',
		max(case when quiz_id ='391' THEN chemistrymark END ) 'fulltest22',
		max(case when quiz_id ='411' THEN chemistrymark END ) 'fulltest23',
		max(case when quiz_id ='413' THEN chemistrymark END ) 'fulltest24',
		max(case when quiz_id ='420' THEN chemistrymark END ) 'fulltest25',


		ifnull(max(case when quiz_id ='1' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='2' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='3' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='4' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='5' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='6' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='7' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='8' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='9' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='10' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='11' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='12' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='13' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='14' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='15' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='16' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='17' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='18' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='19' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='20' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='21' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='22' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='23' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='24' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='25' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='26' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='27' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='28' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='29' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='30' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='31' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='32' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='33' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='34' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='35' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='36' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='37' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='38' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='39' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='40' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='41' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='42' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='43' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='44' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='45' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='46' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='47' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='48' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='49' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='50' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='51' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='52' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='53' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='54' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='55' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='56' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='57' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='58' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='59' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='60' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='61' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='62' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='63' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='64' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='65' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='66' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='67' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='68' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='69' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='70' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='71' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='72' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='73' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='74' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='75' THEN chemistrymark END ),0) +
	    ifnull(max(case when quiz_id ='103' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='104' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='105' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='106' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='107' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='108' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='109' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='110' THEN chemistrymark END ),0) +
		
		ifnull(max(case when quiz_id ='125' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='126' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='170' THEN chemistrymark END ),0) + 
		ifnull(max(case when quiz_id ='171' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='172' THEN chemistrymark END ),0) + 
		ifnull(max(case when quiz_id ='173' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='174' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='175' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='176' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='177' THEN chemistrymark END ),0) +
        ifnull(max(case when quiz_id ='326' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='334' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='337' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='338' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='340' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='342' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='345' THEN chemistrymark END ),0) + 
		ifnull(max(case when quiz_id ='367' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='379' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='383' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='384' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='391' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='411' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='413' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='420' THEN chemistrymark END ),0)		total
		FROM student_quiz_result a , quiz_info b where a.quiz_id = b.id and b.quiztype='JUT' GROUP BY user_id ORDER BY chemistrymark desc ";
				$query = $this->db->query($sql);
				// echo '<pre>';
			   // print_r($sql);
			   // echo '</pre>';
		}
		if ($batch=='all' && $sub =='biology'){
			$sql = "insert into  all_jut_rank
		select user_id,
		max(case when quiz_id ='1' THEN biologymark END ) 'JUT1',
		max(case when quiz_id ='2' THEN biologymark END ) 'JUT2',
		max(case when quiz_id ='3' THEN biologymark END ) 'JUT3',
		max(case when quiz_id ='4' THEN biologymark END ) 'JUT4',
		max(case when quiz_id ='5' THEN biologymark END ) 'JUT5',
		max(case when quiz_id ='6' THEN biologymark END ) 'JUT6',
		max(case when quiz_id ='7' THEN biologymark END ) 'JUT7',
		max(case when quiz_id ='8' THEN biologymark END ) 'JUT8',
		max(case when quiz_id ='9' THEN biologymark END ) 'JUT9',
		max(case when quiz_id ='10' THEN biologymark END ) 'JUT10',
		max(case when quiz_id ='11' THEN biologymark END ) 'JUT11',
		max(case when quiz_id ='12' THEN biologymark END ) 'JUT12',
		max(case when quiz_id ='13' THEN biologymark END ) 'JUT13',
		max(case when quiz_id ='14' THEN biologymark END ) 'JUT14',
		max(case when quiz_id ='15' THEN biologymark END ) 'JUT15',
		max(case when quiz_id ='16' THEN biologymark END ) 'JUT16',
		max(case when quiz_id ='17' THEN biologymark END ) 'JUT17',
		max(case when quiz_id ='18' THEN biologymark END ) 'JUT18',
		max(case when quiz_id ='19' THEN biologymark END ) 'JUT19',
		max(case when quiz_id ='20' THEN biologymark END ) 'JUT20',
		max(case when quiz_id ='21' THEN biologymark END ) 'JUT21',
		max(case when quiz_id ='22' THEN biologymark END ) 'JUT22',
		max(case when quiz_id ='23' THEN biologymark END ) 'JUT23',
		max(case when quiz_id ='24' THEN biologymark END ) 'JUT24',
		max(case when quiz_id ='25' THEN biologymark END ) 'JUT25',
		max(case when quiz_id ='26' THEN biologymark END ) 'JUT26',
		max(case when quiz_id ='27' THEN biologymark END ) 'JUT27',
		max(case when quiz_id ='28' THEN biologymark END ) 'JUT28',
		max(case when quiz_id ='29' THEN biologymark END ) 'JUT29',
		max(case when quiz_id ='30' THEN biologymark END ) 'JUT30',
		max(case when quiz_id ='31' THEN biologymark END ) 'JUT31',
		max(case when quiz_id ='32' THEN biologymark END ) 'JUT32',
		max(case when quiz_id ='33' THEN biologymark END ) 'JUT33',
		max(case when quiz_id ='34' THEN biologymark END ) 'JUT34',
		max(case when quiz_id ='35' THEN biologymark END ) 'JUT35',
		max(case when quiz_id ='36' THEN biologymark END ) 'JUT36',
		max(case when quiz_id ='37' THEN biologymark END ) 'JUT37',
		max(case when quiz_id ='38' THEN biologymark END ) 'JUT38',
		max(case when quiz_id ='39' THEN biologymark END ) 'JUT39',
		max(case when quiz_id ='40' THEN biologymark END ) 'JUT40',
		max(case when quiz_id ='41' THEN biologymark END ) 'JUT41',
		max(case when quiz_id ='42' THEN biologymark END ) 'JUT42',
		max(case when quiz_id ='43' THEN biologymark END ) 'JUT43',
		max(case when quiz_id ='44' THEN biologymark END ) 'JUT44',
		max(case when quiz_id ='45' THEN biologymark END ) 'JUT45',
		max(case when quiz_id ='46' THEN biologymark END ) 'JUT46',
		max(case when quiz_id ='47' THEN biologymark END ) 'JUT47',
		max(case when quiz_id ='48' THEN biologymark END ) 'JUT48',
		max(case when quiz_id ='49' THEN biologymark END ) 'JUT49',
		max(case when quiz_id ='50' THEN biologymark END ) 'JUT50',
		max(case when quiz_id ='51' THEN biologymark END ) 'JUT51',
		max(case when quiz_id ='52' THEN biologymark END ) 'JUT52',
		max(case when quiz_id ='53' THEN biologymark END ) 'JUT53',
		max(case when quiz_id ='54' THEN biologymark END ) 'JUT54',
		max(case when quiz_id ='55' THEN biologymark END ) 'JUT55',
		max(case when quiz_id ='56' THEN biologymark END ) 'JUT56',
		max(case when quiz_id ='57' THEN biologymark END ) 'JUT57',
		max(case when quiz_id ='58' THEN biologymark END ) 'JUT58',
		max(case when quiz_id ='59' THEN biologymark END ) 'JUT59',
		max(case when quiz_id ='60' THEN biologymark END ) 'JUT60',
		max(case when quiz_id ='61' THEN biologymark END ) 'JUT61',
		max(case when quiz_id ='62' THEN biologymark END ) 'JUT62',
		max(case when quiz_id ='63' THEN biologymark END ) 'JUT63',
		max(case when quiz_id ='64' THEN biologymark END ) 'JUT64',
		max(case when quiz_id ='65' THEN biologymark END ) 'JUT65',
		max(case when quiz_id ='66' THEN biologymark END ) 'JUT66',
		max(case when quiz_id ='67' THEN biologymark END ) 'JUT67',
		max(case when quiz_id ='68' THEN biologymark END ) 'JUT68',
		max(case when quiz_id ='69' THEN biologymark END ) 'JUT69',
		max(case when quiz_id ='70' THEN biologymark END ) 'JUT70',
		max(case when quiz_id ='71' THEN biologymark END ) 'JUT71',
		max(case when quiz_id ='72' THEN biologymark END ) 'JUT72',
		max(case when quiz_id ='73' THEN biologymark END ) 'JUT73',
		max(case when quiz_id ='74' THEN biologymark END ) 'JUT74',
		max(case when quiz_id ='75' THEN biologymark END ) 'JUT75',
		max(case when quiz_id ='103' THEN biologymark END ) 'parttest1',
		max(case when quiz_id ='104' THEN biologymark END ) 'parttest2',
		max(case when quiz_id ='105' THEN biologymark END ) 'parttest3',
		max(case when quiz_id ='106' THEN biologymark END ) 'parttest4',
		max(case when quiz_id ='107' THEN biologymark END ) 'parttest5',
		max(case when quiz_id ='108' THEN biologymark END ) 'parttest6',
		max(case when quiz_id ='109' THEN biologymark END ) 'parttest7',
		max(case when quiz_id ='110' THEN biologymark END ) 'parttest8',
		max(case when quiz_id ='125' THEN biologymark END ) 'fulltest1',
		max(case when quiz_id ='126' THEN biologymark END ) 'fulltest2',
		max(case when quiz_id ='170' THEN biologymark END ) 'fulltest3',
		max(case when quiz_id ='171' THEN biologymark END ) 'fulltest4',
		max(case when quiz_id ='172' THEN biologymark END ) 'fulltest5',
		max(case when quiz_id ='173' THEN biologymark END ) 'fulltest6',
		max(case when quiz_id ='174' THEN biologymark END ) 'fulltest7',
		max(case when quiz_id ='175' THEN biologymark END ) 'fulltest8',
		max(case when quiz_id ='176' THEN biologymark END ) 'fulltest9',
		max(case when quiz_id ='177' THEN biologymark END ) 'fulltest10',
		max(case when quiz_id ='326' THEN biologymark END ) 'fulltest11',
		max(case when quiz_id ='334' THEN biologymark END ) 'fulltest12',
		max(case when quiz_id ='337' THEN biologymark END ) 'fulltest13',
		max(case when quiz_id ='338' THEN biologymark END ) 'fulltest14',
		max(case when quiz_id ='340' THEN biologymark END ) 'fulltest15',
		max(case when quiz_id ='342' THEN biologymark END ) 'fulltest16',
		max(case when quiz_id ='345' THEN biologymark END ) 'fulltest17',
		max(case when quiz_id ='367' THEN biologymark END ) 'fulltest18',
		max(case when quiz_id ='379' THEN biologymark END ) 'fulltest19',
		max(case when quiz_id ='383' THEN biologymark END ) 'fulltest20',
		max(case when quiz_id ='384' THEN biologymark END ) 'fulltest21',
		max(case when quiz_id ='391' THEN biologymark END ) 'fulltest22',
		max(case when quiz_id ='411' THEN biologymark END ) 'fulltest23',
		max(case when quiz_id ='413' THEN biologymark END ) 'fulltest24',
		max(case when quiz_id ='420' THEN biologymark END ) 'fulltest25',


		ifnull(max(case when quiz_id ='1' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='2' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='3' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='4' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='5' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='6' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='7' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='8' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='9' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='10' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='11' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='12' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='13' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='14' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='15' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='16' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='17' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='18' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='19' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='20' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='21' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='22' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='23' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='24' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='25' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='26' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='27' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='28' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='29' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='30' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='31' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='32' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='33' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='34' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='35' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='36' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='37' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='38' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='39' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='40' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='41' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='42' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='43' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='44' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='45' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='46' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='47' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='48' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='49' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='50' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='51' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='52' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='53' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='54' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='55' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='56' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='57' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='58' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='59' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='60' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='61' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='62' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='63' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='64' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='65' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='66' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='67' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='68' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='69' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='70' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='71' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='72' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='73' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='74' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='75' THEN biologymark END ),0) +
	    ifnull(max(case when quiz_id ='103' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='104' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='105' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='106' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='107' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='108' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='109' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='110' THEN biologymark END ),0) +
		
		ifnull(max(case when quiz_id ='125' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='126' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='170' THEN biologymark END ),0) + 
		ifnull(max(case when quiz_id ='171' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='172' THEN biologymark END ),0) + 
		ifnull(max(case when quiz_id ='173' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='174' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='175' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='176' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='177' THEN biologymark END ),0) +
        ifnull(max(case when quiz_id ='326' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='334' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='337' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='338' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='340' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='342' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='345' THEN biologymark END ),0) + 
		ifnull(max(case when quiz_id ='367' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='379' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='383' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='384' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='391' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='411' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='413' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='420' THEN biologymark END ),0)		total
		FROM student_quiz_result a , quiz_info b where a.quiz_id = b.id and b.quiztype='JUT' GROUP BY user_id ORDER BY biologymark desc ";
				$query = $this->db->query($sql);
				// echo '<pre>';
			   // print_r($sql);
			   // echo '</pre>';
		}
		if ($batch=='JNANASUDHAIPU' && $sub =='physics'){
			$sql = "insert into  all_jut_rank
		select user_id,
		max(case when quiz_id ='1' THEN physicsmark END ) 'JUT1',
		max(case when quiz_id ='2' THEN physicsmark END ) 'JUT2',
		max(case when quiz_id ='3' THEN physicsmark END ) 'JUT3',
		max(case when quiz_id ='4' THEN physicsmark END ) 'JUT4',
		max(case when quiz_id ='5' THEN physicsmark END ) 'JUT5',
		max(case when quiz_id ='6' THEN physicsmark END ) 'JUT6',
		max(case when quiz_id ='7' THEN physicsmark END ) 'JUT7',
		max(case when quiz_id ='8' THEN physicsmark END ) 'JUT8',
		max(case when quiz_id ='9' THEN physicsmark END ) 'JUT9',
		max(case when quiz_id ='10' THEN physicsmark END ) 'JUT10',
		max(case when quiz_id ='11' THEN physicsmark END ) 'JUT11',
		max(case when quiz_id ='12' THEN physicsmark END ) 'JUT12',
		max(case when quiz_id ='13' THEN physicsmark END ) 'JUT13',
		max(case when quiz_id ='14' THEN physicsmark END ) 'JUT14',
		max(case when quiz_id ='15' THEN physicsmark END ) 'JUT15',
		max(case when quiz_id ='16' THEN physicsmark END ) 'JUT16',
		max(case when quiz_id ='17' THEN physicsmark END ) 'JUT17',
		max(case when quiz_id ='18' THEN physicsmark END ) 'JUT18',
		max(case when quiz_id ='19' THEN physicsmark END ) 'JUT19',
		max(case when quiz_id ='20' THEN physicsmark END ) 'JUT20',
		max(case when quiz_id ='21' THEN physicsmark END ) 'JUT21',
		max(case when quiz_id ='22' THEN physicsmark END ) 'JUT22',
		max(case when quiz_id ='23' THEN physicsmark END ) 'JUT23',
		max(case when quiz_id ='24' THEN physicsmark END ) 'JUT24',
		max(case when quiz_id ='25' THEN physicsmark END ) 'JUT25',
		max(case when quiz_id ='26' THEN physicsmark END ) 'JUT26',
		max(case when quiz_id ='27' THEN physicsmark END ) 'JUT27',
		max(case when quiz_id ='28' THEN physicsmark END ) 'JUT28',
		max(case when quiz_id ='29' THEN physicsmark END ) 'JUT29',
		max(case when quiz_id ='30' THEN physicsmark END ) 'JUT30',
		max(case when quiz_id ='31' THEN physicsmark END ) 'JUT31',
		max(case when quiz_id ='32' THEN physicsmark END ) 'JUT32',
		max(case when quiz_id ='33' THEN physicsmark END ) 'JUT33',
		max(case when quiz_id ='34' THEN physicsmark END ) 'JUT34',
		max(case when quiz_id ='35' THEN physicsmark END ) 'JUT35',
		max(case when quiz_id ='36' THEN physicsmark END ) 'JUT36',
		max(case when quiz_id ='37' THEN physicsmark END ) 'JUT37',
		max(case when quiz_id ='38' THEN physicsmark END ) 'JUT38',
		max(case when quiz_id ='39' THEN physicsmark END ) 'JUT39',
		max(case when quiz_id ='40' THEN physicsmark END ) 'JUT40',
		max(case when quiz_id ='41' THEN physicsmark END ) 'JUT41',
		max(case when quiz_id ='42' THEN physicsmark END ) 'JUT42',
		max(case when quiz_id ='43' THEN physicsmark END ) 'JUT43',
		max(case when quiz_id ='44' THEN physicsmark END ) 'JUT44',
		max(case when quiz_id ='45' THEN physicsmark END ) 'JUT45',
		max(case when quiz_id ='46' THEN physicsmark END ) 'JUT46',
		max(case when quiz_id ='47' THEN physicsmark END ) 'JUT47',
		max(case when quiz_id ='48' THEN physicsmark END ) 'JUT48',
		max(case when quiz_id ='49' THEN physicsmark END ) 'JUT49',
		max(case when quiz_id ='50' THEN physicsmark END ) 'JUT50',
		max(case when quiz_id ='51' THEN physicsmark END ) 'JUT51',
		max(case when quiz_id ='52' THEN physicsmark END ) 'JUT52',
		max(case when quiz_id ='53' THEN physicsmark END ) 'JUT53',
		max(case when quiz_id ='54' THEN physicsmark END ) 'JUT54',
		max(case when quiz_id ='55' THEN physicsmark END ) 'JUT55',
		max(case when quiz_id ='56' THEN physicsmark END ) 'JUT56',
		max(case when quiz_id ='57' THEN physicsmark END ) 'JUT57',
		max(case when quiz_id ='58' THEN physicsmark END ) 'JUT58',
		max(case when quiz_id ='59' THEN physicsmark END ) 'JUT59',
		max(case when quiz_id ='60' THEN physicsmark END ) 'JUT60',
		max(case when quiz_id ='61' THEN physicsmark END ) 'JUT61',
		max(case when quiz_id ='62' THEN physicsmark END ) 'JUT62',
		max(case when quiz_id ='63' THEN physicsmark END ) 'JUT63',
		max(case when quiz_id ='64' THEN physicsmark END ) 'JUT64',
		max(case when quiz_id ='65' THEN physicsmark END ) 'JUT65',
		max(case when quiz_id ='66' THEN physicsmark END ) 'JUT66',
		max(case when quiz_id ='67' THEN physicsmark END ) 'JUT67',
		max(case when quiz_id ='68' THEN physicsmark END ) 'JUT68',
		max(case when quiz_id ='69' THEN physicsmark END ) 'JUT69',
		max(case when quiz_id ='70' THEN physicsmark END ) 'JUT70',
		max(case when quiz_id ='71' THEN physicsmark END ) 'JUT71',
		max(case when quiz_id ='72' THEN physicsmark END ) 'JUT72',
		max(case when quiz_id ='73' THEN physicsmark END ) 'JUT73',
		max(case when quiz_id ='74' THEN physicsmark END ) 'JUT74',
		max(case when quiz_id ='75' THEN physicsmark END ) 'JUT75',
		max(case when quiz_id ='103' THEN physicsmark END ) 'parttest1',
		max(case when quiz_id ='104' THEN physicsmark END ) 'parttest2',
		max(case when quiz_id ='105' THEN physicsmark END ) 'parttest3',
		max(case when quiz_id ='106' THEN physicsmark END ) 'parttest4',
		max(case when quiz_id ='107' THEN physicsmark END ) 'parttest5',
		max(case when quiz_id ='108' THEN physicsmark END ) 'parttest6',
		max(case when quiz_id ='109' THEN physicsmark END ) 'parttest7',
		max(case when quiz_id ='110' THEN physicsmark END ) 'parttest8',
		max(case when quiz_id ='125' THEN physicsmark END ) 'fulltest1',
		max(case when quiz_id ='126' THEN physicsmark END ) 'fulltest2',
		max(case when quiz_id ='170' THEN physicsmark END ) 'fulltest3',
		max(case when quiz_id ='171' THEN physicsmark END ) 'fulltest4',
		max(case when quiz_id ='172' THEN physicsmark END ) 'fulltest5',
		max(case when quiz_id ='173' THEN physicsmark END ) 'fulltest6',
		max(case when quiz_id ='174' THEN physicsmark END ) 'fulltest7',
		max(case when quiz_id ='175' THEN physicsmark END ) 'fulltest8',
		max(case when quiz_id ='176' THEN physicsmark END ) 'fulltest9',
		max(case when quiz_id ='177' THEN physicsmark END ) 'fulltest10',
		
		max(case when quiz_id ='326' THEN physicsmark END ) 'fulltest11',
		max(case when quiz_id ='334' THEN physicsmark END ) 'fulltest12',
		max(case when quiz_id ='337' THEN physicsmark END ) 'fulltest13',
		max(case when quiz_id ='338' THEN physicsmark END ) 'fulltest14',
		max(case when quiz_id ='340' THEN physicsmark END ) 'fulltest15',
		max(case when quiz_id ='342' THEN physicsmark END ) 'fulltest16',
		max(case when quiz_id ='345' THEN physicsmark END ) 'fulltest17',
		max(case when quiz_id ='367' THEN physicsmark END ) 'fulltest18',
		max(case when quiz_id ='379' THEN physicsmark END ) 'fulltest19',
		max(case when quiz_id ='383' THEN physicsmark END ) 'fulltest20',
		max(case when quiz_id ='384' THEN physicsmark END ) 'fulltest21',
		max(case when quiz_id ='391' THEN physicsmark END ) 'fulltest22',
		max(case when quiz_id ='411' THEN physicsmark END ) 'fulltest23',
		max(case when quiz_id ='413' THEN physicsmark END ) 'fulltest24',
		max(case when quiz_id ='420' THEN physicsmark END ) 'fulltest25',


		ifnull(max(case when quiz_id ='1' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='2' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='3' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='4' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='5' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='6' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='7' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='8' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='9' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='10' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='11' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='12' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='13' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='14' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='15' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='16' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='17' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='18' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='19' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='20' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='21' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='22' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='23' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='24' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='25' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='26' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='27' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='28' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='29' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='30' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='31' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='32' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='33' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='34' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='35' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='36' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='37' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='38' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='39' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='40' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='41' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='42' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='43' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='44' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='45' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='46' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='47' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='48' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='49' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='50' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='51' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='52' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='53' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='54' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='55' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='56' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='57' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='58' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='59' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='60' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='61' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='62' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='63' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='64' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='65' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='66' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='67' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='68' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='69' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='70' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='71' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='72' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='73' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='74' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='75' THEN physicsmark END ),0) +
	    ifnull(max(case when quiz_id ='103' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='104' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='105' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='106' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='107' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='108' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='109' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='110' THEN physicsmark END ),0) +
		
		ifnull(max(case when quiz_id ='125' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='126' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='170' THEN physicsmark END ),0) + 
		ifnull(max(case when quiz_id ='171' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='172' THEN physicsmark END ),0) + 
		ifnull(max(case when quiz_id ='173' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='174' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='175' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='176' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='177' THEN physicsmark END ),0) +
        ifnull(max(case when quiz_id ='326' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='334' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='337' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='338' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='340' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='342' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='345' THEN physicsmark END ),0) + 
		ifnull(max(case when quiz_id ='367' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='379' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='383' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='384' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='391' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='411' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='413' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='420' THEN physicsmark END ),0)		total
		FROM student_quiz_result a , quiz_info b where a.quiz_id = b.id and b.quiztype='JUT' and user_id collate utf8_general_ci in
				(select USER_NAME collate utf8_general_ci from user_details 
				where jnanasudhastandard = 'JNANASUDHAIPU')Group By user_id ORDER BY physicsmark desc ";
				$query = $this->db->query($sql);
				// echo '<pre>';
			   // print_r($sql);
			   // echo '</pre>';
		}
		if ($batch=='JNANASUDHAIPU' && $sub =='chemistry'){
			$sql = "insert into  all_jut_rank
		select user_id,
		max(case when quiz_id ='1' THEN chemistrymark END ) 'JUT1',
		max(case when quiz_id ='2' THEN chemistrymark END ) 'JUT2',
		max(case when quiz_id ='3' THEN chemistrymark END ) 'JUT3',
		max(case when quiz_id ='4' THEN chemistrymark END ) 'JUT4',
		max(case when quiz_id ='5' THEN chemistrymark END ) 'JUT5',
		max(case when quiz_id ='6' THEN chemistrymark END ) 'JUT6',
		max(case when quiz_id ='7' THEN chemistrymark END ) 'JUT7',
		max(case when quiz_id ='8' THEN chemistrymark END ) 'JUT8',
		max(case when quiz_id ='9' THEN chemistrymark END ) 'JUT9',
		max(case when quiz_id ='10' THEN chemistrymark END ) 'JUT10',
		max(case when quiz_id ='11' THEN chemistrymark END ) 'JUT11',
		max(case when quiz_id ='12' THEN chemistrymark END ) 'JUT12',
		max(case when quiz_id ='13' THEN chemistrymark END ) 'JUT13',
		max(case when quiz_id ='14' THEN chemistrymark END ) 'JUT14',
		max(case when quiz_id ='15' THEN chemistrymark END ) 'JUT15',
		max(case when quiz_id ='16' THEN chemistrymark END ) 'JUT16',
		max(case when quiz_id ='17' THEN chemistrymark END ) 'JUT17',
		max(case when quiz_id ='18' THEN chemistrymark END ) 'JUT18',
		max(case when quiz_id ='19' THEN chemistrymark END ) 'JUT19',
		max(case when quiz_id ='20' THEN chemistrymark END ) 'JUT20',
		max(case when quiz_id ='21' THEN chemistrymark END ) 'JUT21',
		max(case when quiz_id ='22' THEN chemistrymark END ) 'JUT22',
		max(case when quiz_id ='23' THEN chemistrymark END ) 'JUT23',
		max(case when quiz_id ='24' THEN chemistrymark END ) 'JUT24',
		max(case when quiz_id ='25' THEN chemistrymark END ) 'JUT25',
		max(case when quiz_id ='26' THEN chemistrymark END ) 'JUT26',
		max(case when quiz_id ='27' THEN chemistrymark END ) 'JUT27',
		max(case when quiz_id ='28' THEN chemistrymark END ) 'JUT28',
		max(case when quiz_id ='29' THEN chemistrymark END ) 'JUT29',
		max(case when quiz_id ='30' THEN chemistrymark END ) 'JUT30',
		max(case when quiz_id ='31' THEN chemistrymark END ) 'JUT31',
		max(case when quiz_id ='32' THEN chemistrymark END ) 'JUT32',
		max(case when quiz_id ='33' THEN chemistrymark END ) 'JUT33',
		max(case when quiz_id ='34' THEN chemistrymark END ) 'JUT34',
		max(case when quiz_id ='35' THEN chemistrymark END ) 'JUT35',
		max(case when quiz_id ='36' THEN chemistrymark END ) 'JUT36',
		max(case when quiz_id ='37' THEN chemistrymark END ) 'JUT37',
		max(case when quiz_id ='38' THEN chemistrymark END ) 'JUT38',
		max(case when quiz_id ='39' THEN chemistrymark END ) 'JUT39',
		max(case when quiz_id ='40' THEN chemistrymark END ) 'JUT40',
		max(case when quiz_id ='41' THEN chemistrymark END ) 'JUT41',
		max(case when quiz_id ='42' THEN chemistrymark END ) 'JUT42',
		max(case when quiz_id ='43' THEN chemistrymark END ) 'JUT43',
		max(case when quiz_id ='44' THEN chemistrymark END ) 'JUT44',
		max(case when quiz_id ='45' THEN chemistrymark END ) 'JUT45',
		max(case when quiz_id ='46' THEN chemistrymark END ) 'JUT46',
		max(case when quiz_id ='47' THEN chemistrymark END ) 'JUT47',
		max(case when quiz_id ='48' THEN chemistrymark END ) 'JUT48',
		max(case when quiz_id ='49' THEN chemistrymark END ) 'JUT49',
		max(case when quiz_id ='50' THEN chemistrymark END ) 'JUT50',
		max(case when quiz_id ='51' THEN chemistrymark END ) 'JUT51',
		max(case when quiz_id ='52' THEN chemistrymark END ) 'JUT52',
		max(case when quiz_id ='53' THEN chemistrymark END ) 'JUT53',
		max(case when quiz_id ='54' THEN chemistrymark END ) 'JUT54',
		max(case when quiz_id ='55' THEN chemistrymark END ) 'JUT55',
		max(case when quiz_id ='56' THEN chemistrymark END ) 'JUT56',
		max(case when quiz_id ='57' THEN chemistrymark END ) 'JUT57',
		max(case when quiz_id ='58' THEN chemistrymark END ) 'JUT58',
		max(case when quiz_id ='59' THEN chemistrymark END ) 'JUT59',
		max(case when quiz_id ='60' THEN chemistrymark END ) 'JUT60',
		max(case when quiz_id ='61' THEN chemistrymark END ) 'JUT61',
		max(case when quiz_id ='62' THEN chemistrymark END ) 'JUT62',
		max(case when quiz_id ='63' THEN chemistrymark END ) 'JUT63',
		max(case when quiz_id ='64' THEN chemistrymark END ) 'JUT64',
		max(case when quiz_id ='65' THEN chemistrymark END ) 'JUT65',
		max(case when quiz_id ='66' THEN chemistrymark END ) 'JUT66',
		max(case when quiz_id ='67' THEN chemistrymark END ) 'JUT67',
		max(case when quiz_id ='68' THEN chemistrymark END ) 'JUT68',
		max(case when quiz_id ='69' THEN chemistrymark END ) 'JUT69',
		max(case when quiz_id ='70' THEN chemistrymark END ) 'JUT70',
		max(case when quiz_id ='71' THEN chemistrymark END ) 'JUT71',
		max(case when quiz_id ='72' THEN chemistrymark END ) 'JUT72',
		max(case when quiz_id ='73' THEN chemistrymark END ) 'JUT73',
		max(case when quiz_id ='74' THEN chemistrymark END ) 'JUT74',
		max(case when quiz_id ='75' THEN chemistrymark END ) 'JUT75',
		
		max(case when quiz_id ='103' THEN chemistrymark END ) 'parttest1',
		max(case when quiz_id ='104' THEN chemistrymark END ) 'parttest2',
		max(case when quiz_id ='105' THEN chemistrymark END ) 'parttest3',
		max(case when quiz_id ='106' THEN chemistrymark END ) 'parttest4',
		max(case when quiz_id ='107' THEN chemistrymark END ) 'parttest5',
		max(case when quiz_id ='108' THEN chemistrymark END ) 'parttest6',
		max(case when quiz_id ='109' THEN chemistrymark END ) 'parttest7',
		max(case when quiz_id ='110' THEN chemistrymark END ) 'parttest8',
		max(case when quiz_id ='125' THEN chemistrymark END ) 'fulltest1',
		max(case when quiz_id ='126' THEN chemistrymark END ) 'fulltest2',
		max(case when quiz_id ='170' THEN chemistrymark END ) 'fulltest3',
		max(case when quiz_id ='171' THEN chemistrymark END ) 'fulltest4',
		max(case when quiz_id ='172' THEN chemistrymark END ) 'fulltest5',
		max(case when quiz_id ='173' THEN chemistrymark END ) 'fulltest6',
		max(case when quiz_id ='174' THEN chemistrymark END ) 'fulltest7',
		max(case when quiz_id ='175' THEN chemistrymark END ) 'fulltest8',
		max(case when quiz_id ='176' THEN chemistrymark END ) 'fulltest9',
		max(case when quiz_id ='177' THEN chemistrymark END ) 'fulltest10',
		max(case when quiz_id ='326' THEN chemistrymarkEND ) 'fulltest11',
		max(case when quiz_id ='334' THEN chemistrymarkEND ) 'fulltest12',
		max(case when quiz_id ='337' THEN chemistrymarkEND ) 'fulltest13',
		max(case when quiz_id ='338' THEN chemistrymarkEND ) 'fulltest14',
		max(case when quiz_id ='340' THEN chemistrymarkEND ) 'fulltest15',
		max(case when quiz_id ='342' THEN chemistrymarkEND ) 'fulltest16',
		max(case when quiz_id ='345' THEN chemistrymark END ) 'fulltest17',
		max(case when quiz_id ='367' THEN chemistrymark END ) 'fulltest18',
		max(case when quiz_id ='379' THEN chemistrymark END ) 'fulltest19',
		max(case when quiz_id ='383' THEN chemistrymark END ) 'fulltest20',
		max(case when quiz_id ='384' THEN chemistrymark END ) 'fulltest21',
		max(case when quiz_id ='391' THEN chemistrymark END ) 'fulltest22',
		max(case when quiz_id ='411' THEN chemistrymark END ) 'fulltest23',
		max(case when quiz_id ='413' THEN chemistrymark END ) 'fulltest24',
		max(case when quiz_id ='420' THEN chemistrymark END ) 'fulltest25',


		ifnull(max(case when quiz_id ='1' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='2' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='3' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='4' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='5' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='6' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='7' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='8' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='9' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='10' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='11' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='12' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='13' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='14' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='15' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='16' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='17' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='18' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='19' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='20' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='21' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='22' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='23' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='24' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='25' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='26' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='27' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='28' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='29' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='30' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='31' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='32' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='33' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='34' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='35' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='36' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='37' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='38' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='39' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='40' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='41' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='42' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='43' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='44' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='45' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='46' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='47' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='48' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='49' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='50' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='51' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='52' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='53' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='54' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='55' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='56' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='57' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='58' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='59' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='60' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='61' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='62' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='63' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='64' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='65' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='66' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='67' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='68' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='69' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='70' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='71' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='72' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='73' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='74' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='75' THEN chemistrymark END ),0) +
	    ifnull(max(case when quiz_id ='103' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='104' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='105' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='106' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='107' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='108' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='109' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='110' THEN chemistrymark END ),0) +
		
		ifnull(max(case when quiz_id ='125' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='126' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='170' THEN chemistrymark END ),0) + 
		ifnull(max(case when quiz_id ='171' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='172' THEN chemistrymark END ),0) + 
		ifnull(max(case when quiz_id ='173' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='174' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='175' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='176' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='177' THEN chemistrymark END ),0)	+
        ifnull(max(case when quiz_id ='326' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='334' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='337' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='338' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='340' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='342' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='345' THEN chemistrymark END ),0) + 
		ifnull(max(case when quiz_id ='367' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='379' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='383' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='384' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='391' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='411' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='413' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='420' THEN chemistrymark END ),0)		total
		FROM student_quiz_result a , quiz_info b where a.quiz_id = b.id and b.quiztype='JUT' and user_id collate utf8_general_ci in
				(select USER_NAME collate utf8_general_ci from user_details 
				where jnanasudhastandard = 'JNANASUDHAIPU')Group By user_id ORDER BY chemistrymark desc ";
				$query = $this->db->query($sql);
				// echo '<pre>';
			   // print_r($sql);
			   // echo '</pre>';
		}
		if ($batch=='JNANASUDHAIPU' && $sub =='biology'){
			$sql = "insert into  all_jut_rank
		select user_id,
		max(case when quiz_id ='1' THEN biologymark END ) 'JUT1',
		max(case when quiz_id ='2' THEN biologymark END ) 'JUT2',
		max(case when quiz_id ='3' THEN biologymark END ) 'JUT3',
		max(case when quiz_id ='4' THEN biologymark END ) 'JUT4',
		max(case when quiz_id ='5' THEN biologymark END ) 'JUT5',
		max(case when quiz_id ='6' THEN biologymark END ) 'JUT6',
		max(case when quiz_id ='7' THEN biologymark END ) 'JUT7',
		max(case when quiz_id ='8' THEN biologymark END ) 'JUT8',
		max(case when quiz_id ='9' THEN biologymark END ) 'JUT9',
		max(case when quiz_id ='10' THEN biologymark END ) 'JUT10',
		max(case when quiz_id ='11' THEN biologymark END ) 'JUT11',
		max(case when quiz_id ='12' THEN biologymark END ) 'JUT12',
		max(case when quiz_id ='13' THEN biologymark END ) 'JUT13',
		max(case when quiz_id ='14' THEN biologymark END ) 'JUT14',
		max(case when quiz_id ='15' THEN biologymark END ) 'JUT15',
		max(case when quiz_id ='16' THEN biologymark END ) 'JUT16',
		max(case when quiz_id ='17' THEN biologymark END ) 'JUT17',
		max(case when quiz_id ='18' THEN biologymark END ) 'JUT18',
		max(case when quiz_id ='19' THEN biologymark END ) 'JUT19',
		max(case when quiz_id ='20' THEN biologymark END ) 'JUT20',
		max(case when quiz_id ='21' THEN biologymark END ) 'JUT21',
		max(case when quiz_id ='22' THEN biologymark END ) 'JUT22',
		max(case when quiz_id ='23' THEN biologymark END ) 'JUT23',
		max(case when quiz_id ='24' THEN biologymark END ) 'JUT24',
		max(case when quiz_id ='25' THEN biologymark END ) 'JUT25',
		max(case when quiz_id ='26' THEN biologymark END ) 'JUT26',
		max(case when quiz_id ='27' THEN biologymark END ) 'JUT27',
		max(case when quiz_id ='28' THEN biologymark END ) 'JUT28',
		max(case when quiz_id ='29' THEN biologymark END ) 'JUT29',
		max(case when quiz_id ='30' THEN biologymark END ) 'JUT30',
		max(case when quiz_id ='31' THEN biologymark END ) 'JUT31',
		max(case when quiz_id ='32' THEN biologymark END ) 'JUT32',
		max(case when quiz_id ='33' THEN biologymark END ) 'JUT33',
		max(case when quiz_id ='34' THEN biologymark END ) 'JUT34',
		max(case when quiz_id ='35' THEN biologymark END ) 'JUT35',
		max(case when quiz_id ='36' THEN biologymark END ) 'JUT36',
		max(case when quiz_id ='37' THEN biologymark END ) 'JUT37',
		max(case when quiz_id ='38' THEN biologymark END ) 'JUT38',
		max(case when quiz_id ='39' THEN biologymark END ) 'JUT39',
		max(case when quiz_id ='40' THEN biologymark END ) 'JUT40',
		max(case when quiz_id ='41' THEN biologymark END ) 'JUT41',
		max(case when quiz_id ='42' THEN biologymark END ) 'JUT42',
		max(case when quiz_id ='43' THEN biologymark END ) 'JUT43',
		max(case when quiz_id ='44' THEN biologymark END ) 'JUT44',
		max(case when quiz_id ='45' THEN biologymark END ) 'JUT45',
		max(case when quiz_id ='46' THEN biologymark END ) 'JUT46',
		max(case when quiz_id ='47' THEN biologymark END ) 'JUT47',
		max(case when quiz_id ='48' THEN biologymark END ) 'JUT48',
		max(case when quiz_id ='49' THEN biologymark END ) 'JUT49',
		max(case when quiz_id ='50' THEN biologymark END ) 'JUT50',
		max(case when quiz_id ='51' THEN biologymark END ) 'JUT51',
		max(case when quiz_id ='52' THEN biologymark END ) 'JUT52',
		max(case when quiz_id ='53' THEN biologymark END ) 'JUT53',
		max(case when quiz_id ='54' THEN biologymark END ) 'JUT54',
		max(case when quiz_id ='55' THEN biologymark END ) 'JUT55',
		max(case when quiz_id ='56' THEN biologymark END ) 'JUT56',
		max(case when quiz_id ='57' THEN biologymark END ) 'JUT57',
		max(case when quiz_id ='58' THEN biologymark END ) 'JUT58',
		max(case when quiz_id ='59' THEN biologymark END ) 'JUT59',
		max(case when quiz_id ='60' THEN biologymark END ) 'JUT60',
		max(case when quiz_id ='61' THEN biologymark END ) 'JUT61',
		max(case when quiz_id ='62' THEN biologymark END ) 'JUT62',
		max(case when quiz_id ='63' THEN biologymark END ) 'JUT63',
		max(case when quiz_id ='64' THEN biologymark END ) 'JUT64',
		max(case when quiz_id ='65' THEN biologymark END ) 'JUT65',
		max(case when quiz_id ='66' THEN biologymark END ) 'JUT66',
		max(case when quiz_id ='67' THEN biologymark END ) 'JUT67',
		max(case when quiz_id ='68' THEN biologymark END ) 'JUT68',
		max(case when quiz_id ='69' THEN biologymark END ) 'JUT69',
		max(case when quiz_id ='70' THEN biologymark END ) 'JUT70',
		max(case when quiz_id ='71' THEN biologymark END ) 'JUT71',
		max(case when quiz_id ='72' THEN biologymark END ) 'JUT72',
		max(case when quiz_id ='73' THEN biologymark END ) 'JUT73',
		max(case when quiz_id ='74' THEN biologymark END ) 'JUT74',
		max(case when quiz_id ='75' THEN biologymark END ) 'JUT75',
		
		max(case when quiz_id ='103' THEN biologymark END ) 'parttest1',
		max(case when quiz_id ='104' THEN biologymark END ) 'parttest2',
		max(case when quiz_id ='105' THEN biologymark END ) 'parttest3',
		max(case when quiz_id ='106' THEN biologymark END ) 'parttest4',
		max(case when quiz_id ='107' THEN biologymark END ) 'parttest5',
		max(case when quiz_id ='108' THEN biologymark END ) 'parttest6',
		max(case when quiz_id ='109' THEN biologymark END ) 'parttest7',
		max(case when quiz_id ='110' THEN biologymark END ) 'parttest8',
		max(case when quiz_id ='125' THEN biologymark END ) 'fulltest1',
		max(case when quiz_id ='126' THEN biologymark END ) 'fulltest2',
		max(case when quiz_id ='170' THEN biologymark END ) 'fulltest3',
		max(case when quiz_id ='171' THEN biologymark END ) 'fulltest4',
		max(case when quiz_id ='172' THEN biologymark END ) 'fulltest5',
		max(case when quiz_id ='173' THEN biologymark END ) 'fulltest6',
		max(case when quiz_id ='174' THEN biologymark END ) 'fulltest7',
		max(case when quiz_id ='175' THEN biologymark END ) 'fulltest8',
		max(case when quiz_id ='176' THEN biologymark END ) 'fulltest9',
		max(case when quiz_id ='177' THEN biologymark END ) 'fulltest10',
		max(case when quiz_id ='326' THEN biologymark END ) 'fulltest11',
		max(case when quiz_id ='334' THEN biologymark END ) 'fulltest12',
		max(case when quiz_id ='337' THEN biologymark END ) 'fulltest13',
		max(case when quiz_id ='338' THEN biologymark END ) 'fulltest14',
		max(case when quiz_id ='340' THEN biologymark END ) 'fulltest15',
		max(case when quiz_id ='342' THEN biologymark END ) 'fulltest16',
		max(case when quiz_id ='345' THEN biologymark END ) 'fulltest17',
		max(case when quiz_id ='367' THEN biologymark END ) 'fulltest18',
		max(case when quiz_id ='379' THEN biologymark END ) 'fulltest19',
		max(case when quiz_id ='383' THEN biologymark END ) 'fulltest20',
		max(case when quiz_id ='384' THEN biologymark END ) 'fulltest21',
		max(case when quiz_id ='391' THEN biologymark END ) 'fulltest22',
		max(case when quiz_id ='411' THEN biologymark END ) 'fulltest23',
		max(case when quiz_id ='413' THEN biologymark END ) 'fulltest24',
		max(case when quiz_id ='420' THEN biologymark END ) 'fulltest25',


		ifnull(max(case when quiz_id ='1' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='2' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='3' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='4' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='5' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='6' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='7' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='8' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='9' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='10' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='11' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='12' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='13' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='14' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='15' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='16' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='17' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='18' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='19' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='20' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='21' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='22' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='23' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='24' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='25' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='26' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='27' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='28' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='29' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='30' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='31' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='32' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='33' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='34' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='35' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='36' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='37' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='38' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='39' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='40' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='41' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='42' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='43' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='44' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='45' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='46' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='47' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='48' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='49' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='50' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='51' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='52' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='53' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='54' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='55' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='56' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='57' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='58' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='59' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='60' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='61' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='62' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='63' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='64' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='65' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='66' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='67' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='68' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='69' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='70' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='71' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='72' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='73' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='74' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='75' THEN biologymark END ),0) +
	    ifnull(max(case when quiz_id ='103' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='104' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='105' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='106' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='107' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='108' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='109' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='110' THEN biologymark END ),0) +
		
		ifnull(max(case when quiz_id ='125' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='126' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='170' THEN biologymark END ),0) + 
		ifnull(max(case when quiz_id ='171' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='172' THEN biologymark END ),0) + 
		ifnull(max(case when quiz_id ='173' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='174' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='175' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='176' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='177' THEN biologymark END ),0) +
        ifnull(max(case when quiz_id ='326' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='334' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='337' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='338' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='340' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='342' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='345' THEN biologymark END ),0) + 
		ifnull(max(case when quiz_id ='367' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='379' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='383' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='384' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='391' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='411' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='413' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='420' THEN biologymark END ),0)		total
		FROM student_quiz_result a , quiz_info b where a.quiz_id = b.id and b.quiztype='JUT' and user_id collate utf8_general_ci in
				(select USER_NAME collate utf8_general_ci from user_details 
				where jnanasudhastandard = 'JNANASUDHAIPU')Group By user_id ORDER BY biologymark desc ";
				$query = $this->db->query($sql);
				// echo '<pre>';
			   // print_r($sql);
			   // echo '</pre>';
		}

		if ($batch=='JNANASUDHAIIPU' && $sub =='physics'){
			$sql = "insert into  all_jut_rank
		select user_id,
		max(case when quiz_id ='1' THEN physicsmark END ) 'JUT1',
		max(case when quiz_id ='2' THEN physicsmark END ) 'JUT2',
		max(case when quiz_id ='3' THEN physicsmark END ) 'JUT3',
		max(case when quiz_id ='4' THEN physicsmark END ) 'JUT4',
		max(case when quiz_id ='5' THEN physicsmark END ) 'JUT5',
		max(case when quiz_id ='6' THEN physicsmark END ) 'JUT6',
		max(case when quiz_id ='7' THEN physicsmark END ) 'JUT7',
		max(case when quiz_id ='8' THEN physicsmark END ) 'JUT8',
		max(case when quiz_id ='9' THEN physicsmark END ) 'JUT9',
		max(case when quiz_id ='10' THEN physicsmark END ) 'JUT10',
		max(case when quiz_id ='11' THEN physicsmark END ) 'JUT11',
		max(case when quiz_id ='12' THEN physicsmark END ) 'JUT12',
		max(case when quiz_id ='13' THEN physicsmark END ) 'JUT13',
		max(case when quiz_id ='14' THEN physicsmark END ) 'JUT14',
		max(case when quiz_id ='15' THEN physicsmark END ) 'JUT15',
		max(case when quiz_id ='16' THEN physicsmark END ) 'JUT16',
		max(case when quiz_id ='17' THEN physicsmark END ) 'JUT17',
		max(case when quiz_id ='18' THEN physicsmark END ) 'JUT18',
		max(case when quiz_id ='19' THEN physicsmark END ) 'JUT19',
		max(case when quiz_id ='20' THEN physicsmark END ) 'JUT20',
		max(case when quiz_id ='21' THEN physicsmark END ) 'JUT21',
		max(case when quiz_id ='22' THEN physicsmark END ) 'JUT22',
		max(case when quiz_id ='23' THEN physicsmark END ) 'JUT23',
		max(case when quiz_id ='24' THEN physicsmark END ) 'JUT24',
		max(case when quiz_id ='25' THEN physicsmark END ) 'JUT25',
		max(case when quiz_id ='26' THEN physicsmark END ) 'JUT26',
		max(case when quiz_id ='27' THEN physicsmark END ) 'JUT27',
		max(case when quiz_id ='28' THEN physicsmark END ) 'JUT28',
		max(case when quiz_id ='29' THEN physicsmark END ) 'JUT29',
		max(case when quiz_id ='30' THEN physicsmark END ) 'JUT30',
		max(case when quiz_id ='31' THEN physicsmark END ) 'JUT31',
		max(case when quiz_id ='32' THEN physicsmark END ) 'JUT32',
		max(case when quiz_id ='33' THEN physicsmark END ) 'JUT33',
		max(case when quiz_id ='34' THEN physicsmark END ) 'JUT34',
		max(case when quiz_id ='35' THEN physicsmark END ) 'JUT35',
		max(case when quiz_id ='36' THEN physicsmark END ) 'JUT36',
		max(case when quiz_id ='37' THEN physicsmark END ) 'JUT37',
		max(case when quiz_id ='38' THEN physicsmark END ) 'JUT38',
		max(case when quiz_id ='39' THEN physicsmark END ) 'JUT39',
		max(case when quiz_id ='40' THEN physicsmark END ) 'JUT40',
		max(case when quiz_id ='41' THEN physicsmark END ) 'JUT41',
		max(case when quiz_id ='42' THEN physicsmark END ) 'JUT42',
		max(case when quiz_id ='43' THEN physicsmark END ) 'JUT43',
		max(case when quiz_id ='44' THEN physicsmark END ) 'JUT44',
		max(case when quiz_id ='45' THEN physicsmark END ) 'JUT45',
		max(case when quiz_id ='46' THEN physicsmark END ) 'JUT46',
		max(case when quiz_id ='47' THEN physicsmark END ) 'JUT47',
		max(case when quiz_id ='48' THEN physicsmark END ) 'JUT48',
		max(case when quiz_id ='49' THEN physicsmark END ) 'JUT49',
		max(case when quiz_id ='50' THEN physicsmark END ) 'JUT50',
		max(case when quiz_id ='51' THEN physicsmark END ) 'JUT51',
		max(case when quiz_id ='52' THEN physicsmark END ) 'JUT52',
		max(case when quiz_id ='53' THEN physicsmark END ) 'JUT53',
		max(case when quiz_id ='54' THEN physicsmark END ) 'JUT54',
		max(case when quiz_id ='55' THEN physicsmark END ) 'JUT55',
		max(case when quiz_id ='56' THEN physicsmark END ) 'JUT56',
		max(case when quiz_id ='57' THEN physicsmark END ) 'JUT57',
		max(case when quiz_id ='58' THEN physicsmark END ) 'JUT58',
		max(case when quiz_id ='59' THEN physicsmark END ) 'JUT59',
		max(case when quiz_id ='60' THEN physicsmark END ) 'JUT60',
		max(case when quiz_id ='61' THEN physicsmark END ) 'JUT61',
		max(case when quiz_id ='62' THEN physicsmark END ) 'JUT62',
		max(case when quiz_id ='63' THEN physicsmark END ) 'JUT63',
		max(case when quiz_id ='64' THEN physicsmark END ) 'JUT64',
		max(case when quiz_id ='65' THEN physicsmark END ) 'JUT65',
		max(case when quiz_id ='66' THEN physicsmark END ) 'JUT66',
		max(case when quiz_id ='67' THEN physicsmark END ) 'JUT67',
		max(case when quiz_id ='68' THEN physicsmark END ) 'JUT68',
		max(case when quiz_id ='69' THEN physicsmark END ) 'JUT69',
		max(case when quiz_id ='70' THEN physicsmark END ) 'JUT70',
		max(case when quiz_id ='71' THEN physicsmark END ) 'JUT71',
		max(case when quiz_id ='72' THEN physicsmark END ) 'JUT72',
		max(case when quiz_id ='73' THEN physicsmark END ) 'JUT73',
		max(case when quiz_id ='74' THEN physicsmark END ) 'JUT74',
		max(case when quiz_id ='75' THEN physicsmark END ) 'JUT75',
		
		max(case when quiz_id ='103' THEN physicsmark END ) 'parttest1',
		max(case when quiz_id ='104' THEN physicsmark END ) 'parttest2',
		max(case when quiz_id ='105' THEN physicsmark END ) 'parttest3',
		max(case when quiz_id ='106' THEN physicsmark END ) 'parttest4',
		max(case when quiz_id ='107' THEN physicsmark END ) 'parttest5',
		max(case when quiz_id ='108' THEN physicsmark END ) 'parttest6',
		max(case when quiz_id ='109' THEN physicsmark END ) 'parttest7',
		max(case when quiz_id ='110' THEN physicsmark END ) 'parttest8',
		max(case when quiz_id ='125' THEN physicsmark END ) 'fulltest1',
		max(case when quiz_id ='126' THEN physicsmark END ) 'fulltest2',
		max(case when quiz_id ='170' THEN physicsmark END ) 'fulltest3',
		max(case when quiz_id ='171' THEN physicsmark END ) 'fulltest4',
		max(case when quiz_id ='172' THEN physicsmark END ) 'fulltest5',
		max(case when quiz_id ='173' THEN physicsmark END ) 'fulltest6',
		max(case when quiz_id ='174' THEN physicsmark END ) 'fulltest7',
		max(case when quiz_id ='175' THEN physicsmark END ) 'fulltest8',
		max(case when quiz_id ='176' THEN physicsmark END ) 'fulltest9',
		max(case when quiz_id ='177' THEN physicsmark END ) 'fulltest10',
		max(case when quiz_id ='326' THEN physicsmark END ) 'fulltest11',
		max(case when quiz_id ='334' THEN physicsmark END ) 'fulltest12',
		max(case when quiz_id ='337' THEN physicsmark END ) 'fulltest13',
		max(case when quiz_id ='338' THEN physicsmark END ) 'fulltest14',
		max(case when quiz_id ='340' THEN physicsmark END ) 'fulltest15',
		max(case when quiz_id ='342' THEN physicsmark END ) 'fulltest16',
		max(case when quiz_id ='345' THEN physicsmark END ) 'fulltest17',
		max(case when quiz_id ='367' THEN physicsmark END ) 'fulltest18',
		max(case when quiz_id ='379' THEN physicsmark END ) 'fulltest19',
		max(case when quiz_id ='383' THEN physicsmark END ) 'fulltest20',
		max(case when quiz_id ='384' THEN physicsmark END ) 'fulltest21',
		max(case when quiz_id ='391' THEN physicsmark END ) 'fulltest22',
		max(case when quiz_id ='411' THEN physicsmark END ) 'fulltest23',
		max(case when quiz_id ='413' THEN physicsmark END ) 'fulltest24',
		max(case when quiz_id ='420' THEN physicsmark END ) 'fulltest25',


		ifnull(max(case when quiz_id ='1' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='2' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='3' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='4' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='5' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='6' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='7' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='8' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='9' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='10' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='11' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='12' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='13' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='14' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='15' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='16' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='17' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='18' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='19' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='20' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='21' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='22' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='23' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='24' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='25' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='26' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='27' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='28' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='29' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='30' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='31' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='32' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='33' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='34' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='35' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='36' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='37' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='38' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='39' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='40' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='41' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='42' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='43' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='44' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='45' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='46' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='47' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='48' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='49' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='50' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='51' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='52' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='53' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='54' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='55' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='56' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='57' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='58' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='59' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='60' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='61' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='62' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='63' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='64' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='65' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='66' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='67' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='68' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='69' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='70' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='71' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='72' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='73' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='74' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='75' THEN physicsmark END ),0) +
	    ifnull(max(case when quiz_id ='103' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='104' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='105' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='106' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='107' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='108' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='109' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='110' THEN physicsmark END ),0) +
		
		ifnull(max(case when quiz_id ='125' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='126' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='170' THEN physicsmark END ),0) + 
		ifnull(max(case when quiz_id ='171' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='172' THEN physicsmark END ),0) + 
		ifnull(max(case when quiz_id ='173' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='174' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='175' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='176' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='177' THEN physicsmark END ),0)+
        ifnull(max(case when quiz_id ='326' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='334' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='337' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='338' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='340' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='342' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='345' THEN physicsmark END ),0) + 
		ifnull(max(case when quiz_id ='367' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='379' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='383' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='384' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='391' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='411' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='413' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='420' THEN physicsmark END ),0)		total
		FROM student_quiz_result a , quiz_info b where a.quiz_id = b.id and b.quiztype='JUT' and user_id collate utf8_general_ci in
				(select USER_NAME collate utf8_general_ci from user_details 
				where jnanasudhastandard = 'JNANASUDHAIIPU')Group By user_id ORDER BY physicsmark desc ";
				$query = $this->db->query($sql);
				// echo '<pre>';
			   // print_r($sql);
			   // echo '</pre>';
		}
		if ($batch=='JNANASUDHAIIPU' && $sub =='chemistry'){
			$sql = "insert into  all_jut_rank
		select user_id,
		max(case when quiz_id ='1' THEN chemistrymark END ) 'JUT1',
		max(case when quiz_id ='2' THEN chemistrymark END ) 'JUT2',
		max(case when quiz_id ='3' THEN chemistrymark END ) 'JUT3',
		max(case when quiz_id ='4' THEN chemistrymark END ) 'JUT4',
		max(case when quiz_id ='5' THEN chemistrymark END ) 'JUT5',
		max(case when quiz_id ='6' THEN chemistrymark END ) 'JUT6',
		max(case when quiz_id ='7' THEN chemistrymark END ) 'JUT7',
		max(case when quiz_id ='8' THEN chemistrymark END ) 'JUT8',
		max(case when quiz_id ='9' THEN chemistrymark END ) 'JUT9',
		max(case when quiz_id ='10' THEN chemistrymark END ) 'JUT10',
		max(case when quiz_id ='11' THEN chemistrymark END ) 'JUT11',
		max(case when quiz_id ='12' THEN chemistrymark END ) 'JUT12',
		max(case when quiz_id ='13' THEN chemistrymark END ) 'JUT13',
		max(case when quiz_id ='14' THEN chemistrymark END ) 'JUT14',
		max(case when quiz_id ='15' THEN chemistrymark END ) 'JUT15',
		max(case when quiz_id ='16' THEN chemistrymark END ) 'JUT16',
		max(case when quiz_id ='17' THEN chemistrymark END ) 'JUT17',
		max(case when quiz_id ='18' THEN chemistrymark END ) 'JUT18',
		max(case when quiz_id ='19' THEN chemistrymark END ) 'JUT19',
		max(case when quiz_id ='20' THEN chemistrymark END ) 'JUT20',
		max(case when quiz_id ='21' THEN chemistrymark END ) 'JUT21',
		max(case when quiz_id ='22' THEN chemistrymark END ) 'JUT22',
		max(case when quiz_id ='23' THEN chemistrymark END ) 'JUT23',
		max(case when quiz_id ='24' THEN chemistrymark END ) 'JUT24',
		max(case when quiz_id ='25' THEN chemistrymark END ) 'JUT25',
		max(case when quiz_id ='26' THEN chemistrymark END ) 'JUT26',
		max(case when quiz_id ='27' THEN chemistrymark END ) 'JUT27',
		max(case when quiz_id ='28' THEN chemistrymark END ) 'JUT28',
		max(case when quiz_id ='29' THEN chemistrymark END ) 'JUT29',
		max(case when quiz_id ='30' THEN chemistrymark END ) 'JUT30',
		max(case when quiz_id ='31' THEN chemistrymark END ) 'JUT31',
		max(case when quiz_id ='32' THEN chemistrymark END ) 'JUT32',
		max(case when quiz_id ='33' THEN chemistrymark END ) 'JUT33',
		max(case when quiz_id ='34' THEN chemistrymark END ) 'JUT34',
		max(case when quiz_id ='35' THEN chemistrymark END ) 'JUT35',
		max(case when quiz_id ='36' THEN chemistrymark END ) 'JUT36',
		max(case when quiz_id ='37' THEN chemistrymark END ) 'JUT37',
		max(case when quiz_id ='38' THEN chemistrymark END ) 'JUT38',
		max(case when quiz_id ='39' THEN chemistrymark END ) 'JUT39',
		max(case when quiz_id ='40' THEN chemistrymark END ) 'JUT40',
		max(case when quiz_id ='41' THEN chemistrymark END ) 'JUT41',
		max(case when quiz_id ='42' THEN chemistrymark END ) 'JUT42',
		max(case when quiz_id ='43' THEN chemistrymark END ) 'JUT43',
		max(case when quiz_id ='44' THEN chemistrymark END ) 'JUT44',
		max(case when quiz_id ='45' THEN chemistrymark END ) 'JUT45',
		max(case when quiz_id ='46' THEN chemistrymark END ) 'JUT46',
		max(case when quiz_id ='47' THEN chemistrymark END ) 'JUT47',
		max(case when quiz_id ='48' THEN chemistrymark END ) 'JUT48',
		max(case when quiz_id ='49' THEN chemistrymark END ) 'JUT49',
		max(case when quiz_id ='50' THEN chemistrymark END ) 'JUT50',
		max(case when quiz_id ='51' THEN chemistrymark END ) 'JUT51',
		max(case when quiz_id ='52' THEN chemistrymark END ) 'JUT52',
		max(case when quiz_id ='53' THEN chemistrymark END ) 'JUT53',
		max(case when quiz_id ='54' THEN chemistrymark END ) 'JUT54',
		max(case when quiz_id ='55' THEN chemistrymark END ) 'JUT55',
		max(case when quiz_id ='56' THEN chemistrymark END ) 'JUT56',
		max(case when quiz_id ='57' THEN chemistrymark END ) 'JUT57',
		max(case when quiz_id ='58' THEN chemistrymark END ) 'JUT58',
		max(case when quiz_id ='59' THEN chemistrymark END ) 'JUT59',
		max(case when quiz_id ='60' THEN chemistrymark END ) 'JUT60',
		max(case when quiz_id ='61' THEN chemistrymark END ) 'JUT61',
		max(case when quiz_id ='62' THEN chemistrymark END ) 'JUT62',
		max(case when quiz_id ='63' THEN chemistrymark END ) 'JUT63',
		max(case when quiz_id ='64' THEN chemistrymark END ) 'JUT64',
		max(case when quiz_id ='65' THEN chemistrymark END ) 'JUT65',
		max(case when quiz_id ='66' THEN chemistrymark END ) 'JUT66',
		max(case when quiz_id ='67' THEN chemistrymark END ) 'JUT67',
		max(case when quiz_id ='68' THEN chemistrymark END ) 'JUT68',
		max(case when quiz_id ='69' THEN chemistrymark END ) 'JUT69',
		max(case when quiz_id ='70' THEN chemistrymark END ) 'JUT70',
		max(case when quiz_id ='71' THEN chemistrymark END ) 'JUT71',
		max(case when quiz_id ='72' THEN chemistrymark END ) 'JUT72',
		max(case when quiz_id ='73' THEN chemistrymark END ) 'JUT73',
		max(case when quiz_id ='74' THEN chemistrymark END ) 'JUT74',
		max(case when quiz_id ='75' THEN chemistrymark END ) 'JUT75',
		
		max(case when quiz_id ='103' THEN chemistrymark END ) 'parttest1',
		max(case when quiz_id ='104' THEN chemistrymark END ) 'parttest2',
		max(case when quiz_id ='105' THEN chemistrymark END ) 'parttest3',
		max(case when quiz_id ='106' THEN chemistrymark END ) 'parttest4',
		max(case when quiz_id ='107' THEN chemistrymark END ) 'parttest5',
		max(case when quiz_id ='108' THEN chemistrymark END ) 'parttest6',
		max(case when quiz_id ='109' THEN chemistrymark END ) 'parttest7',
		max(case when quiz_id ='110' THEN chemistrymark END ) 'parttest8',
		max(case when quiz_id ='125' THEN chemistrymark END ) 'fulltest1',
		max(case when quiz_id ='126' THEN chemistrymark END ) 'fulltest2',
		max(case when quiz_id ='170' THEN chemistrymark END ) 'fulltest3',
		max(case when quiz_id ='171' THEN chemistrymark END ) 'fulltest4',
		max(case when quiz_id ='172' THEN chemistrymark END ) 'fulltest5',
		max(case when quiz_id ='173' THEN chemistrymark END ) 'fulltest6',
		max(case when quiz_id ='174' THEN chemistrymark END ) 'fulltest7',
		max(case when quiz_id ='175' THEN chemistrymark END ) 'fulltest8',
		max(case when quiz_id ='176' THEN chemistrymark END ) 'fulltest9',
		max(case when quiz_id ='177' THEN chemistrymark END ) 'fulltest10',
		max(case when quiz_id ='326' THEN chemistrymark END ) 'fulltest11',
		max(case when quiz_id ='334' THEN chemistrymark END ) 'fulltest12',
		max(case when quiz_id ='337' THEN chemistrymark END ) 'fulltest13',
		max(case when quiz_id ='338' THEN chemistrymark END ) 'fulltest14',
		max(case when quiz_id ='340' THEN chemistrymark END ) 'fulltest15',
		max(case when quiz_id ='342' THEN chemistrymark END ) 'fulltest16',
		max(case when quiz_id ='345' THEN chemistrymark END ) 'fulltest17',
		max(case when quiz_id ='367' THEN chemistrymark END ) 'fulltest18',
		max(case when quiz_id ='379' THEN chemistrymark END ) 'fulltest19',
		max(case when quiz_id ='383' THEN chemistrymark END ) 'fulltest20',
		max(case when quiz_id ='384' THEN chemistrymark END ) 'fulltest21',
		max(case when quiz_id ='391' THEN chemistrymark END ) 'fulltest22',
		max(case when quiz_id ='411' THEN chemistrymark END ) 'fulltest23',
		max(case when quiz_id ='413' THEN chemistrymark END ) 'fulltest24',
		max(case when quiz_id ='420' THEN chemistrymark END ) 'fulltest25',


		ifnull(max(case when quiz_id ='1' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='2' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='3' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='4' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='5' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='6' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='7' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='8' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='9' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='10' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='11' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='12' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='13' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='14' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='15' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='16' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='17' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='18' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='19' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='20' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='21' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='22' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='23' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='24' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='25' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='26' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='27' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='28' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='29' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='30' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='31' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='32' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='33' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='34' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='35' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='36' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='37' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='38' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='39' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='40' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='41' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='42' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='43' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='44' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='45' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='46' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='47' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='48' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='49' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='50' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='51' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='52' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='53' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='54' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='55' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='56' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='57' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='58' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='59' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='60' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='61' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='62' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='63' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='64' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='65' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='66' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='67' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='68' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='69' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='70' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='71' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='72' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='73' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='74' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='75' THEN chemistrymark END ),0) +
	    ifnull(max(case when quiz_id ='103' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='104' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='105' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='106' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='107' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='108' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='109' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='110' THEN chemistrymark END ),0) +
		
		ifnull(max(case when quiz_id ='125' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='126' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='170' THEN chemistrymark END ),0) + 
		ifnull(max(case when quiz_id ='171' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='172' THEN chemistrymark END ),0) + 
		ifnull(max(case when quiz_id ='173' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='174' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='175' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='176' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='177' THEN chemistrymark END ),0)	+
        ifnull(max(case when quiz_id ='326' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='334' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='337' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='338' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='340' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='342' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='345' THEN chemistrymark END ),0) + 
		ifnull(max(case when quiz_id ='367' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='379' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='383' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='384' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='391' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='411' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='413' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='420' THEN chemistrymark END ),0)		total
		FROM student_quiz_result a , quiz_info b where a.quiz_id = b.id and b.quiztype='JUT' and user_id collate utf8_general_ci in
				(select USER_NAME collate utf8_general_ci from user_details 
				where jnanasudhastandard = 'JNANASUDHAIIPU')Group By user_id ORDER BY chemistrymark desc ";
				$query = $this->db->query($sql);
				// echo '<pre>';
			   // print_r($sql);
			   // echo '</pre>';
		}
		if ($batch=='JNANASUDHAIIPU' && $sub =='biology'){
			$sql = "insert into  all_jut_rank
		select user_id,
		max(case when quiz_id ='1' THEN biologymark END ) 'JUT1',
		max(case when quiz_id ='2' THEN biologymark END ) 'JUT2',
		max(case when quiz_id ='3' THEN biologymark END ) 'JUT3',
		max(case when quiz_id ='4' THEN biologymark END ) 'JUT4',
		max(case when quiz_id ='5' THEN biologymark END ) 'JUT5',
		max(case when quiz_id ='6' THEN biologymark END ) 'JUT6',
		max(case when quiz_id ='7' THEN biologymark END ) 'JUT7',
		max(case when quiz_id ='8' THEN biologymark END ) 'JUT8',
		max(case when quiz_id ='9' THEN biologymark END ) 'JUT9',
		max(case when quiz_id ='10' THEN biologymark END ) 'JUT10',
		max(case when quiz_id ='11' THEN biologymark END ) 'JUT11',
		max(case when quiz_id ='12' THEN biologymark END ) 'JUT12',
		max(case when quiz_id ='13' THEN biologymark END ) 'JUT13',
		max(case when quiz_id ='14' THEN biologymark END ) 'JUT14',
		max(case when quiz_id ='15' THEN biologymark END ) 'JUT15',
		max(case when quiz_id ='16' THEN biologymark END ) 'JUT16',
		max(case when quiz_id ='17' THEN biologymark END ) 'JUT17',
		max(case when quiz_id ='18' THEN biologymark END ) 'JUT18',
		max(case when quiz_id ='19' THEN biologymark END ) 'JUT19',
		max(case when quiz_id ='20' THEN biologymark END ) 'JUT20',
		max(case when quiz_id ='21' THEN biologymark END ) 'JUT21',
		max(case when quiz_id ='22' THEN biologymark END ) 'JUT22',
		max(case when quiz_id ='23' THEN biologymark END ) 'JUT23',
		max(case when quiz_id ='24' THEN biologymark END ) 'JUT24',
		max(case when quiz_id ='25' THEN biologymark END ) 'JUT25',
		max(case when quiz_id ='26' THEN biologymark END ) 'JUT26',
		max(case when quiz_id ='27' THEN biologymark END ) 'JUT27',
		max(case when quiz_id ='28' THEN biologymark END ) 'JUT28',
		max(case when quiz_id ='29' THEN biologymark END ) 'JUT29',
		max(case when quiz_id ='30' THEN biologymark END ) 'JUT30',
		max(case when quiz_id ='31' THEN biologymark END ) 'JUT31',
		max(case when quiz_id ='32' THEN biologymark END ) 'JUT32',
		max(case when quiz_id ='33' THEN biologymark END ) 'JUT33',
		max(case when quiz_id ='34' THEN biologymark END ) 'JUT34',
		max(case when quiz_id ='35' THEN biologymark END ) 'JUT35',
		max(case when quiz_id ='36' THEN biologymark END ) 'JUT36',
		max(case when quiz_id ='37' THEN biologymark END ) 'JUT37',
		max(case when quiz_id ='38' THEN biologymark END ) 'JUT38',
		max(case when quiz_id ='39' THEN biologymark END ) 'JUT39',
		max(case when quiz_id ='40' THEN biologymark END ) 'JUT40',
		max(case when quiz_id ='41' THEN biologymark END ) 'JUT41',
		max(case when quiz_id ='42' THEN biologymark END ) 'JUT42',
		max(case when quiz_id ='43' THEN biologymark END ) 'JUT43',
		max(case when quiz_id ='44' THEN biologymark END ) 'JUT44',
		max(case when quiz_id ='45' THEN biologymark END ) 'JUT45',
		max(case when quiz_id ='46' THEN biologymark END ) 'JUT46',
		max(case when quiz_id ='47' THEN biologymark END ) 'JUT47',
		max(case when quiz_id ='48' THEN biologymark END ) 'JUT48',
		max(case when quiz_id ='49' THEN biologymark END ) 'JUT49',
		max(case when quiz_id ='50' THEN biologymark END ) 'JUT50',
		max(case when quiz_id ='51' THEN biologymark END ) 'JUT51',
		max(case when quiz_id ='52' THEN biologymark END ) 'JUT52',
		max(case when quiz_id ='53' THEN biologymark END ) 'JUT53',
		max(case when quiz_id ='54' THEN biologymark END ) 'JUT54',
		max(case when quiz_id ='55' THEN biologymark END ) 'JUT55',
		max(case when quiz_id ='56' THEN biologymark END ) 'JUT56',
		max(case when quiz_id ='57' THEN biologymark END ) 'JUT57',
		max(case when quiz_id ='58' THEN biologymark END ) 'JUT58',
		max(case when quiz_id ='59' THEN biologymark END ) 'JUT59',
		max(case when quiz_id ='60' THEN biologymark END ) 'JUT60',
		max(case when quiz_id ='61' THEN biologymark END ) 'JUT61',
		max(case when quiz_id ='62' THEN biologymark END ) 'JUT62',
		max(case when quiz_id ='63' THEN biologymark END ) 'JUT63',
		max(case when quiz_id ='64' THEN biologymark END ) 'JUT64',
		max(case when quiz_id ='65' THEN biologymark END ) 'JUT65',
		max(case when quiz_id ='66' THEN biologymark END ) 'JUT66',
		max(case when quiz_id ='67' THEN biologymark END ) 'JUT67',
		max(case when quiz_id ='68' THEN biologymark END ) 'JUT68',
		max(case when quiz_id ='69' THEN biologymark END ) 'JUT69',
		max(case when quiz_id ='70' THEN biologymark END ) 'JUT70',
		max(case when quiz_id ='71' THEN biologymark END ) 'JUT71',
		max(case when quiz_id ='72' THEN biologymark END ) 'JUT72',
		max(case when quiz_id ='73' THEN biologymark END ) 'JUT73',
		max(case when quiz_id ='74' THEN biologymark END ) 'JUT74',
		max(case when quiz_id ='75' THEN biologymark END ) 'JUT75',
		
		max(case when quiz_id ='103' THEN biologymark END ) 'parttest1',
		max(case when quiz_id ='104' THEN biologymark END ) 'parttest2',
		max(case when quiz_id ='105' THEN biologymark END ) 'parttest3',
		max(case when quiz_id ='106' THEN biologymark END ) 'parttest4',
		max(case when quiz_id ='107' THEN biologymark END ) 'parttest5',
		max(case when quiz_id ='108' THEN biologymark END ) 'parttest6',
		max(case when quiz_id ='109' THEN biologymark END ) 'parttest7',
		max(case when quiz_id ='110' THEN biologymark END ) 'parttest8',
		max(case when quiz_id ='125' THEN biologymark END ) 'fulltest1',
		max(case when quiz_id ='126' THEN biologymark END ) 'fulltest2',
		max(case when quiz_id ='170' THEN biologymark END ) 'fulltest3',
		max(case when quiz_id ='171' THEN biologymark END ) 'fulltest4',
		max(case when quiz_id ='172' THEN biologymark END ) 'fulltest5',
		max(case when quiz_id ='173' THEN biologymark END ) 'fulltest6',
		max(case when quiz_id ='174' THEN biologymark END ) 'fulltest7',
		max(case when quiz_id ='175' THEN biologymark END ) 'fulltest8',
		max(case when quiz_id ='176' THEN biologymark END ) 'fulltest9',
		max(case when quiz_id ='177' THEN biologymark END ) 'fulltest10',
		max(case when quiz_id ='326' THEN biologymark END ) 'fulltest11',
		max(case when quiz_id ='334' THEN biologymark END ) 'fulltest12',
		max(case when quiz_id ='337' THEN biologymark END ) 'fulltest13',
		max(case when quiz_id ='338' THEN biologymark END ) 'fulltest14',
		max(case when quiz_id ='340' THEN biologymark END ) 'fulltest15',
		max(case when quiz_id ='342' THEN biologymark END ) 'fulltest16',
		max(case when quiz_id ='345' THEN biologymark END ) 'fulltest17',
		max(case when quiz_id ='367' THEN biologymark END ) 'fulltest18',
		max(case when quiz_id ='379' THEN biologymark END ) 'fulltest19',
		max(case when quiz_id ='383' THEN biologymark END ) 'fulltest20',
		max(case when quiz_id ='384' THEN biologymark END ) 'fulltest21',
		max(case when quiz_id ='391' THEN biologymark END ) 'fulltest22',
		max(case when quiz_id ='411' THEN biologymark END ) 'fulltest23',
		max(case when quiz_id ='413' THEN biologymark END ) 'fulltest24',
		max(case when quiz_id ='420' THEN biologymark END ) 'fulltest25',


		ifnull(max(case when quiz_id ='1' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='2' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='3' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='4' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='5' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='6' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='7' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='8' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='9' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='10' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='11' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='12' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='13' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='14' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='15' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='16' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='17' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='18' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='19' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='20' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='21' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='22' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='23' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='24' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='25' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='26' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='27' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='28' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='29' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='30' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='31' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='32' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='33' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='34' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='35' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='36' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='37' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='38' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='39' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='40' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='41' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='42' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='43' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='44' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='45' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='46' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='47' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='48' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='49' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='50' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='51' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='52' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='53' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='54' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='55' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='56' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='57' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='58' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='59' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='60' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='61' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='62' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='63' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='64' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='65' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='66' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='67' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='68' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='69' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='70' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='71' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='72' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='73' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='74' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='75' THEN biologymark END ),0) +
	    ifnull(max(case when quiz_id ='103' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='104' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='105' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='106' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='107' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='108' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='109' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='110' THEN biologymark END ),0) +
		
		ifnull(max(case when quiz_id ='125' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='126' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='170' THEN biologymark END ),0) + 
		ifnull(max(case when quiz_id ='171' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='172' THEN biologymark END ),0) + 
		ifnull(max(case when quiz_id ='173' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='174' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='175' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='176' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='177' THEN biologymark END ),0) +
        ifnull(max(case when quiz_id ='326' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='334' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='337' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='338' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='340' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='342' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='345' THEN biologymark END ),0) + 
		ifnull(max(case when quiz_id ='367' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='379' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='383' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='384' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='391' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='411' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='413' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='420' THEN biologymark END ),0)		total
		FROM student_quiz_result a , quiz_info b where a.quiz_id = b.id and b.quiztype='JUT' and user_id collate utf8_general_ci in
				(select USER_NAME collate utf8_general_ci from user_details 
				where jnanasudhastandard = 'JNANASUDHAIIPU')Group By user_id ORDER BY biologymark desc ";
				$query = $this->db->query($sql);
				// echo '<pre>';
			   // print_r($sql);
			   // echo '</pre>';
		}
		//**************JNANAAMRUTHA************//
		if ($batch=='JNANAAMRUTHA' && $sub =='physics'){
			$sql = "insert into  all_jut_rank
		select user_id,
		max(case when quiz_id ='1' THEN physicsmark END ) 'JUT1',
		max(case when quiz_id ='2' THEN physicsmark END ) 'JUT2',
		max(case when quiz_id ='3' THEN physicsmark END ) 'JUT3',
		max(case when quiz_id ='4' THEN physicsmark END ) 'JUT4',
		max(case when quiz_id ='5' THEN physicsmark END ) 'JUT5',
		max(case when quiz_id ='6' THEN physicsmark END ) 'JUT6',
		max(case when quiz_id ='7' THEN physicsmark END ) 'JUT7',
		max(case when quiz_id ='8' THEN physicsmark END ) 'JUT8',
		max(case when quiz_id ='9' THEN physicsmark END ) 'JUT9',
		max(case when quiz_id ='10' THEN physicsmark END ) 'JUT10',
		max(case when quiz_id ='11' THEN physicsmark END ) 'JUT11',
		max(case when quiz_id ='12' THEN physicsmark END ) 'JUT12',
		max(case when quiz_id ='13' THEN physicsmark END ) 'JUT13',
		max(case when quiz_id ='14' THEN physicsmark END ) 'JUT14',
		max(case when quiz_id ='15' THEN physicsmark END ) 'JUT15',
		max(case when quiz_id ='16' THEN physicsmark END ) 'JUT16',
		max(case when quiz_id ='17' THEN physicsmark END ) 'JUT17',
		max(case when quiz_id ='18' THEN physicsmark END ) 'JUT18',
		max(case when quiz_id ='19' THEN physicsmark END ) 'JUT19',
		max(case when quiz_id ='20' THEN physicsmark END ) 'JUT20',
		max(case when quiz_id ='21' THEN physicsmark END ) 'JUT21',
		max(case when quiz_id ='22' THEN physicsmark END ) 'JUT22',
		max(case when quiz_id ='23' THEN physicsmark END ) 'JUT23',
		max(case when quiz_id ='24' THEN physicsmark END ) 'JUT24',
		max(case when quiz_id ='25' THEN physicsmark END ) 'JUT25',
		max(case when quiz_id ='26' THEN physicsmark END ) 'JUT26',
		max(case when quiz_id ='27' THEN physicsmark END ) 'JUT27',
		max(case when quiz_id ='28' THEN physicsmark END ) 'JUT28',
		max(case when quiz_id ='29' THEN physicsmark END ) 'JUT29',
		max(case when quiz_id ='30' THEN physicsmark END ) 'JUT30',
		max(case when quiz_id ='31' THEN physicsmark END ) 'JUT31',
		max(case when quiz_id ='32' THEN physicsmark END ) 'JUT32',
		max(case when quiz_id ='33' THEN physicsmark END ) 'JUT33',
		max(case when quiz_id ='34' THEN physicsmark END ) 'JUT34',
		max(case when quiz_id ='35' THEN physicsmark END ) 'JUT35',
		max(case when quiz_id ='36' THEN physicsmark END ) 'JUT36',
		max(case when quiz_id ='37' THEN physicsmark END ) 'JUT37',
		max(case when quiz_id ='38' THEN physicsmark END ) 'JUT38',
		max(case when quiz_id ='39' THEN physicsmark END ) 'JUT39',
		max(case when quiz_id ='40' THEN physicsmark END ) 'JUT40',
		max(case when quiz_id ='41' THEN physicsmark END ) 'JUT41',
		max(case when quiz_id ='42' THEN physicsmark END ) 'JUT42',
		max(case when quiz_id ='43' THEN physicsmark END ) 'JUT43',
		max(case when quiz_id ='44' THEN physicsmark END ) 'JUT44',
		max(case when quiz_id ='45' THEN physicsmark END ) 'JUT45',
		max(case when quiz_id ='46' THEN physicsmark END ) 'JUT46',
		max(case when quiz_id ='47' THEN physicsmark END ) 'JUT47',
		max(case when quiz_id ='48' THEN physicsmark END ) 'JUT48',
		max(case when quiz_id ='49' THEN physicsmark END ) 'JUT49',
		max(case when quiz_id ='50' THEN physicsmark END ) 'JUT50',
		max(case when quiz_id ='51' THEN physicsmark END ) 'JUT51',
		max(case when quiz_id ='52' THEN physicsmark END ) 'JUT52',
		max(case when quiz_id ='53' THEN physicsmark END ) 'JUT53',
		max(case when quiz_id ='54' THEN physicsmark END ) 'JUT54',
		max(case when quiz_id ='55' THEN physicsmark END ) 'JUT55',
		max(case when quiz_id ='56' THEN physicsmark END ) 'JUT56',
		max(case when quiz_id ='57' THEN physicsmark END ) 'JUT57',
		max(case when quiz_id ='58' THEN physicsmark END ) 'JUT58',
		max(case when quiz_id ='59' THEN physicsmark END ) 'JUT59',
		max(case when quiz_id ='60' THEN physicsmark END ) 'JUT60',
		max(case when quiz_id ='61' THEN physicsmark END ) 'JUT61',
		max(case when quiz_id ='62' THEN physicsmark END ) 'JUT62',
		max(case when quiz_id ='63' THEN physicsmark END ) 'JUT63',
		max(case when quiz_id ='64' THEN physicsmark END ) 'JUT64',
		max(case when quiz_id ='65' THEN physicsmark END ) 'JUT65',
		max(case when quiz_id ='66' THEN physicsmark END ) 'JUT66',
		max(case when quiz_id ='67' THEN physicsmark END ) 'JUT67',
		max(case when quiz_id ='68' THEN physicsmark END ) 'JUT68',
		max(case when quiz_id ='69' THEN physicsmark END ) 'JUT69',
		max(case when quiz_id ='70' THEN physicsmark END ) 'JUT70',
		max(case when quiz_id ='71' THEN physicsmark END ) 'JUT71',
		max(case when quiz_id ='72' THEN physicsmark END ) 'JUT72',
		max(case when quiz_id ='73' THEN physicsmark END ) 'JUT73',
		max(case when quiz_id ='74' THEN physicsmark END ) 'JUT74',
		max(case when quiz_id ='75' THEN physicsmark END ) 'JUT75',
		
		max(case when quiz_id ='103' THEN physicsmark END ) 'parttest1',
		max(case when quiz_id ='104' THEN physicsmark END ) 'parttest2',
		max(case when quiz_id ='105' THEN physicsmark END ) 'parttest3',
		max(case when quiz_id ='106' THEN physicsmark END ) 'parttest4',
		max(case when quiz_id ='107' THEN physicsmark END ) 'parttest5',
		max(case when quiz_id ='108' THEN physicsmark END ) 'parttest6',
		max(case when quiz_id ='109' THEN physicsmark END ) 'parttest7',
		max(case when quiz_id ='110' THEN physicsmark END ) 'parttest8',
		max(case when quiz_id ='125' THEN physicsmark END ) 'fulltest1',
		max(case when quiz_id ='126' THEN physicsmark END ) 'fulltest2',
		max(case when quiz_id ='170' THEN physicsmark END ) 'fulltest3',
		max(case when quiz_id ='171' THEN physicsmark END ) 'fulltest4',
		max(case when quiz_id ='172' THEN physicsmark END ) 'fulltest5',
		max(case when quiz_id ='173' THEN physicsmark END ) 'fulltest6',
		max(case when quiz_id ='174' THEN physicsmark END ) 'fulltest7',
		max(case when quiz_id ='175' THEN physicsmark END ) 'fulltest8',
		max(case when quiz_id ='176' THEN physicsmark END ) 'fulltest9',
		max(case when quiz_id ='177' THEN physicsmark END ) 'fulltest10',
		max(case when quiz_id ='326' THEN physicsmark END ) 'fulltest11',
		max(case when quiz_id ='334' THEN physicsmark END ) 'fulltest12',
		max(case when quiz_id ='337' THEN physicsmark END ) 'fulltest13',
		max(case when quiz_id ='338' THEN physicsmark END ) 'fulltest14',
		max(case when quiz_id ='340' THEN physicsmark END ) 'fulltest15',
		max(case when quiz_id ='342' THEN physicsmark END ) 'fulltest16',
		max(case when quiz_id ='345' THEN physicsmark END ) 'fulltest17',
		max(case when quiz_id ='367' THEN physicsmark END ) 'fulltest18',
		max(case when quiz_id ='379' THEN physicsmark END ) 'fulltest19',
		max(case when quiz_id ='383' THEN physicsmark END ) 'fulltest20',
		max(case when quiz_id ='384' THEN physicsmark END ) 'fulltest21',
		max(case when quiz_id ='391' THEN physicsmark END ) 'fulltest22',
		max(case when quiz_id ='411' THEN physicsmark END ) 'fulltest23',
		max(case when quiz_id ='413' THEN physicsmark END ) 'fulltest24',
		max(case when quiz_id ='420' THEN physicsmark END ) 'fulltest25',


		ifnull(max(case when quiz_id ='1' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='2' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='3' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='4' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='5' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='6' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='7' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='8' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='9' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='10' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='11' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='12' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='13' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='14' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='15' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='16' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='17' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='18' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='19' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='20' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='21' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='22' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='23' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='24' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='25' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='26' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='27' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='28' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='29' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='30' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='31' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='32' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='33' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='34' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='35' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='36' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='37' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='38' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='39' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='40' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='41' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='42' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='43' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='44' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='45' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='46' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='47' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='48' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='49' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='50' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='51' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='52' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='53' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='54' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='55' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='56' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='57' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='58' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='59' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='60' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='61' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='62' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='63' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='64' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='65' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='66' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='67' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='68' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='69' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='70' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='71' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='72' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='73' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='74' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='75' THEN physicsmark END ),0) +
	    ifnull(max(case when quiz_id ='103' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='104' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='105' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='106' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='107' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='108' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='109' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='110' THEN physicsmark END ),0) +
		
		ifnull(max(case when quiz_id ='125' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='126' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='170' THEN physicsmark END ),0) + 
		ifnull(max(case when quiz_id ='171' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='172' THEN physicsmark END ),0) + 
		ifnull(max(case when quiz_id ='173' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='174' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='175' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='176' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='177' THEN physicsmark END ),0) +
        ifnull(max(case when quiz_id ='326' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='334' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='337' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='338' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='340' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='342' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='345' THEN physicsmark END ),0) + 
		ifnull(max(case when quiz_id ='367' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='379' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='383' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='384' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='391' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='411' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='413' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='420' THEN physicsmark END ),0)		total
		FROM student_quiz_result a , quiz_info b where a.quiz_id = b.id and b.quiztype='JUT' and user_id collate utf8_general_ci in
				(select USER_NAME collate utf8_general_ci from user_details 
				where jnanasudhastandard = 'JNANAAMRUTHA')Group By user_id ORDER BY physicsmark desc ";
				$query = $this->db->query($sql);
				// echo '<pre>';
			   // print_r($sql);
			   // echo '</pre>';
		}
		if ($batch=='JNANAAMRUTHA' && $sub =='chemistry'){
			$sql = "insert into  all_jut_rank
		select user_id,
		max(case when quiz_id ='1' THEN chemistrymark END ) 'JUT1',
		max(case when quiz_id ='2' THEN chemistrymark END ) 'JUT2',
		max(case when quiz_id ='3' THEN chemistrymark END ) 'JUT3',
		max(case when quiz_id ='4' THEN chemistrymark END ) 'JUT4',
		max(case when quiz_id ='5' THEN chemistrymark END ) 'JUT5',
		max(case when quiz_id ='6' THEN chemistrymark END ) 'JUT6',
		max(case when quiz_id ='7' THEN chemistrymark END ) 'JUT7',
		max(case when quiz_id ='8' THEN chemistrymark END ) 'JUT8',
		max(case when quiz_id ='9' THEN chemistrymark END ) 'JUT9',
		max(case when quiz_id ='10' THEN chemistrymark END ) 'JUT10',
		max(case when quiz_id ='11' THEN chemistrymark END ) 'JUT11',
		max(case when quiz_id ='12' THEN chemistrymark END ) 'JUT12',
		max(case when quiz_id ='13' THEN chemistrymark END ) 'JUT13',
		max(case when quiz_id ='14' THEN chemistrymark END ) 'JUT14',
		max(case when quiz_id ='15' THEN chemistrymark END ) 'JUT15',
		max(case when quiz_id ='16' THEN chemistrymark END ) 'JUT16',
		max(case when quiz_id ='17' THEN chemistrymark END ) 'JUT17',
		max(case when quiz_id ='18' THEN chemistrymark END ) 'JUT18',
		max(case when quiz_id ='19' THEN chemistrymark END ) 'JUT19',
		max(case when quiz_id ='20' THEN chemistrymark END ) 'JUT20',
		max(case when quiz_id ='21' THEN chemistrymark END ) 'JUT21',
		max(case when quiz_id ='22' THEN chemistrymark END ) 'JUT22',
		max(case when quiz_id ='23' THEN chemistrymark END ) 'JUT23',
		max(case when quiz_id ='24' THEN chemistrymark END ) 'JUT24',
		max(case when quiz_id ='25' THEN chemistrymark END ) 'JUT25',
		max(case when quiz_id ='26' THEN chemistrymark END ) 'JUT26',
		max(case when quiz_id ='27' THEN chemistrymark END ) 'JUT27',
		max(case when quiz_id ='28' THEN chemistrymark END ) 'JUT28',
		max(case when quiz_id ='29' THEN chemistrymark END ) 'JUT29',
		max(case when quiz_id ='30' THEN chemistrymark END ) 'JUT30',
		max(case when quiz_id ='31' THEN chemistrymark END ) 'JUT31',
		max(case when quiz_id ='32' THEN chemistrymark END ) 'JUT32',
		max(case when quiz_id ='33' THEN chemistrymark END ) 'JUT33',
		max(case when quiz_id ='34' THEN chemistrymark END ) 'JUT34',
		max(case when quiz_id ='35' THEN chemistrymark END ) 'JUT35',
		max(case when quiz_id ='36' THEN chemistrymark END ) 'JUT36',
		max(case when quiz_id ='37' THEN chemistrymark END ) 'JUT37',
		max(case when quiz_id ='38' THEN chemistrymark END ) 'JUT38',
		max(case when quiz_id ='39' THEN chemistrymark END ) 'JUT39',
		max(case when quiz_id ='40' THEN chemistrymark END ) 'JUT40',
		max(case when quiz_id ='41' THEN chemistrymark END ) 'JUT41',
		max(case when quiz_id ='42' THEN chemistrymark END ) 'JUT42',
		max(case when quiz_id ='43' THEN chemistrymark END ) 'JUT43',
		max(case when quiz_id ='44' THEN chemistrymark END ) 'JUT44',
		max(case when quiz_id ='45' THEN chemistrymark END ) 'JUT45',
		max(case when quiz_id ='46' THEN chemistrymark END ) 'JUT46',
		max(case when quiz_id ='47' THEN chemistrymark END ) 'JUT47',
		max(case when quiz_id ='48' THEN chemistrymark END ) 'JUT48',
		max(case when quiz_id ='49' THEN chemistrymark END ) 'JUT49',
		max(case when quiz_id ='50' THEN chemistrymark END ) 'JUT50',
		max(case when quiz_id ='51' THEN chemistrymark END ) 'JUT51',
		max(case when quiz_id ='52' THEN chemistrymark END ) 'JUT52',
		max(case when quiz_id ='53' THEN chemistrymark END ) 'JUT53',
		max(case when quiz_id ='54' THEN chemistrymark END ) 'JUT54',
		max(case when quiz_id ='55' THEN chemistrymark END ) 'JUT55',
		max(case when quiz_id ='56' THEN chemistrymark END ) 'JUT56',
		max(case when quiz_id ='57' THEN chemistrymark END ) 'JUT57',
		max(case when quiz_id ='58' THEN chemistrymark END ) 'JUT58',
		max(case when quiz_id ='59' THEN chemistrymark END ) 'JUT59',
		max(case when quiz_id ='60' THEN chemistrymark END ) 'JUT60',
		max(case when quiz_id ='61' THEN chemistrymark END ) 'JUT61',
		max(case when quiz_id ='62' THEN chemistrymark END ) 'JUT62',
		max(case when quiz_id ='63' THEN chemistrymark END ) 'JUT63',
		max(case when quiz_id ='64' THEN chemistrymark END ) 'JUT64',
		max(case when quiz_id ='65' THEN chemistrymark END ) 'JUT65',
		max(case when quiz_id ='66' THEN chemistrymark END ) 'JUT66',
		max(case when quiz_id ='67' THEN chemistrymark END ) 'JUT67',
		max(case when quiz_id ='68' THEN chemistrymark END ) 'JUT68',
		max(case when quiz_id ='69' THEN chemistrymark END ) 'JUT69',
		max(case when quiz_id ='70' THEN chemistrymark END ) 'JUT70',
		max(case when quiz_id ='71' THEN chemistrymark END ) 'JUT71',
		max(case when quiz_id ='72' THEN chemistrymark END ) 'JUT72',
		max(case when quiz_id ='73' THEN chemistrymark END ) 'JUT73',
		max(case when quiz_id ='74' THEN chemistrymark END ) 'JUT74',
		max(case when quiz_id ='75' THEN chemistrymark END ) 'JUT75',
		
		max(case when quiz_id ='103' THEN chemistrymark END ) 'parttest1',
		max(case when quiz_id ='104' THEN chemistrymark END ) 'parttest2',
		max(case when quiz_id ='105' THEN chemistrymark END ) 'parttest3',
		max(case when quiz_id ='106' THEN chemistrymark END ) 'parttest4',
		max(case when quiz_id ='107' THEN chemistrymark END ) 'parttest5',
		max(case when quiz_id ='108' THEN chemistrymark END ) 'parttest6',
		max(case when quiz_id ='109' THEN chemistrymark END ) 'parttest7',
		max(case when quiz_id ='110' THEN chemistrymark END ) 'parttest8',
		max(case when quiz_id ='125' THEN chemistrymark END ) 'fulltest1',
		max(case when quiz_id ='126' THEN chemistrymark END ) 'fulltest2',
		max(case when quiz_id ='170' THEN chemistrymark END ) 'fulltest3',
		max(case when quiz_id ='171' THEN chemistrymark END ) 'fulltest4',
		max(case when quiz_id ='172' THEN chemistrymark END ) 'fulltest5',
		max(case when quiz_id ='173' THEN chemistrymark END ) 'fulltest6',
		max(case when quiz_id ='174' THEN chemistrymark END ) 'fulltest7',
		max(case when quiz_id ='175' THEN chemistrymark END ) 'fulltest8',
		max(case when quiz_id ='176' THEN chemistrymark END ) 'fulltest9',
		max(case when quiz_id ='177' THEN chemistrymark END ) 'fulltest10',
		
		max(case when quiz_id ='326' THEN chemistrymark END ) 'fulltest11',
		max(case when quiz_id ='334' THEN chemistrymark END ) 'fulltest12',
		max(case when quiz_id ='337' THEN chemistrymark END ) 'fulltest13',
		max(case when quiz_id ='338' THEN chemistrymark END ) 'fulltest14',
		max(case when quiz_id ='340' THEN chemistrymark END ) 'fulltest15',
		max(case when quiz_id ='342' THEN chemistrymark END ) 'fulltest16',
		max(case when quiz_id ='345' THEN chemistrymark END ) 'fulltest17',
		max(case when quiz_id ='367' THEN chemistrymark END ) 'fulltest18',
		max(case when quiz_id ='379' THEN chemistrymark END ) 'fulltest19',
		max(case when quiz_id ='383' THEN chemistrymark END ) 'fulltest20',
		max(case when quiz_id ='384' THEN chemistrymark END ) 'fulltest21',
		max(case when quiz_id ='391' THEN chemistrymark END ) 'fulltest22',
		max(case when quiz_id ='411' THEN chemistrymark END ) 'fulltest23',
		max(case when quiz_id ='413' THEN chemistrymark END ) 'fulltest24',
		max(case when quiz_id ='420' THEN chemistrymark END ) 'fulltest25',


		ifnull(max(case when quiz_id ='1' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='2' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='3' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='4' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='5' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='6' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='7' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='8' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='9' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='10' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='11' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='12' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='13' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='14' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='15' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='16' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='17' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='18' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='19' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='20' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='21' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='22' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='23' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='24' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='25' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='26' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='27' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='28' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='29' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='30' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='31' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='32' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='33' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='34' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='35' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='36' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='37' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='38' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='39' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='40' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='41' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='42' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='43' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='44' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='45' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='46' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='47' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='48' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='49' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='50' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='51' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='52' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='53' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='54' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='55' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='56' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='57' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='58' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='59' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='60' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='61' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='62' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='63' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='64' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='65' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='66' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='67' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='68' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='69' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='70' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='71' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='72' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='73' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='74' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='75' THEN chemistrymark END ),0) +
	    ifnull(max(case when quiz_id ='103' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='104' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='105' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='106' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='107' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='108' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='109' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='110' THEN chemistrymark END ),0) +
		
		ifnull(max(case when quiz_id ='125' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='126' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='170' THEN chemistrymark END ),0) + 
		ifnull(max(case when quiz_id ='171' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='172' THEN chemistrymark END ),0) + 
		ifnull(max(case when quiz_id ='173' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='174' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='175' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='176' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='177' THEN chemistrymark END ),0) +
        ifnull(max(case when quiz_id ='326' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='334' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='337' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='338' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='340' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='342' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='345' THEN chemistrymark END ),0) + 
		ifnull(max(case when quiz_id ='367' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='379' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='383' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='384' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='391' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='411' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='413' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='420' THEN chemistrymark END ),0)		total
		FROM student_quiz_result a , quiz_info b where a.quiz_id = b.id and b.quiztype='JUT' and user_id collate utf8_general_ci in
				(select USER_NAME collate utf8_general_ci from user_details 
				where jnanasudhastandard = 'JNANAAMRUTHA')Group By user_id ORDER BY chemistrymark desc ";
				$query = $this->db->query($sql);
				// echo '<pre>';
			   // print_r($sql);
			   // echo '</pre>';
		}
		if ($batch=='JNANAAMRUTHA' && $sub =='biology'){
			$sql = "insert into  all_jut_rank
		select user_id,
		max(case when quiz_id ='1' THEN biologymark END ) 'JUT1',
		max(case when quiz_id ='2' THEN biologymark END ) 'JUT2',
		max(case when quiz_id ='3' THEN biologymark END ) 'JUT3',
		max(case when quiz_id ='4' THEN biologymark END ) 'JUT4',
		max(case when quiz_id ='5' THEN biologymark END ) 'JUT5',
		max(case when quiz_id ='6' THEN biologymark END ) 'JUT6',
		max(case when quiz_id ='7' THEN biologymark END ) 'JUT7',
		max(case when quiz_id ='8' THEN biologymark END ) 'JUT8',
		max(case when quiz_id ='9' THEN biologymark END ) 'JUT9',
		max(case when quiz_id ='10' THEN biologymark END ) 'JUT10',
		max(case when quiz_id ='11' THEN biologymark END ) 'JUT11',
		max(case when quiz_id ='12' THEN biologymark END ) 'JUT12',
		max(case when quiz_id ='13' THEN biologymark END ) 'JUT13',
		max(case when quiz_id ='14' THEN biologymark END ) 'JUT14',
		max(case when quiz_id ='15' THEN biologymark END ) 'JUT15',
		max(case when quiz_id ='16' THEN biologymark END ) 'JUT16',
		max(case when quiz_id ='17' THEN biologymark END ) 'JUT17',
		max(case when quiz_id ='18' THEN biologymark END ) 'JUT18',
		max(case when quiz_id ='19' THEN biologymark END ) 'JUT19',
		max(case when quiz_id ='20' THEN biologymark END ) 'JUT20',
		max(case when quiz_id ='21' THEN biologymark END ) 'JUT21',
		max(case when quiz_id ='22' THEN biologymark END ) 'JUT22',
		max(case when quiz_id ='23' THEN biologymark END ) 'JUT23',
		max(case when quiz_id ='24' THEN biologymark END ) 'JUT24',
		max(case when quiz_id ='25' THEN biologymark END ) 'JUT25',
		max(case when quiz_id ='26' THEN biologymark END ) 'JUT26',
		max(case when quiz_id ='27' THEN biologymark END ) 'JUT27',
		max(case when quiz_id ='28' THEN biologymark END ) 'JUT28',
		max(case when quiz_id ='29' THEN biologymark END ) 'JUT29',
		max(case when quiz_id ='30' THEN biologymark END ) 'JUT30',
		max(case when quiz_id ='31' THEN biologymark END ) 'JUT31',
		max(case when quiz_id ='32' THEN biologymark END ) 'JUT32',
		max(case when quiz_id ='33' THEN biologymark END ) 'JUT33',
		max(case when quiz_id ='34' THEN biologymark END ) 'JUT34',
		max(case when quiz_id ='35' THEN biologymark END ) 'JUT35',
		max(case when quiz_id ='36' THEN biologymark END ) 'JUT36',
		max(case when quiz_id ='37' THEN biologymark END ) 'JUT37',
		max(case when quiz_id ='38' THEN biologymark END ) 'JUT38',
		max(case when quiz_id ='39' THEN biologymark END ) 'JUT39',
		max(case when quiz_id ='40' THEN biologymark END ) 'JUT40',
		max(case when quiz_id ='41' THEN biologymark END ) 'JUT41',
		max(case when quiz_id ='42' THEN biologymark END ) 'JUT42',
		max(case when quiz_id ='43' THEN biologymark END ) 'JUT43',
		max(case when quiz_id ='44' THEN biologymark END ) 'JUT44',
		max(case when quiz_id ='45' THEN biologymark END ) 'JUT45',
		max(case when quiz_id ='46' THEN biologymark END ) 'JUT46',
		max(case when quiz_id ='47' THEN biologymark END ) 'JUT47',
		max(case when quiz_id ='48' THEN biologymark END ) 'JUT48',
		max(case when quiz_id ='49' THEN biologymark END ) 'JUT49',
		max(case when quiz_id ='50' THEN biologymark END ) 'JUT50',
		max(case when quiz_id ='51' THEN biologymark END ) 'JUT51',
		max(case when quiz_id ='52' THEN biologymark END ) 'JUT52',
		max(case when quiz_id ='53' THEN biologymark END ) 'JUT53',
		max(case when quiz_id ='54' THEN biologymark END ) 'JUT54',
		max(case when quiz_id ='55' THEN biologymark END ) 'JUT55',
		max(case when quiz_id ='56' THEN biologymark END ) 'JUT56',
		max(case when quiz_id ='57' THEN biologymark END ) 'JUT57',
		max(case when quiz_id ='58' THEN biologymark END ) 'JUT58',
		max(case when quiz_id ='59' THEN biologymark END ) 'JUT59',
		max(case when quiz_id ='60' THEN biologymark END ) 'JUT60',
		max(case when quiz_id ='61' THEN biologymark END ) 'JUT61',
		max(case when quiz_id ='62' THEN biologymark END ) 'JUT62',
		max(case when quiz_id ='63' THEN biologymark END ) 'JUT63',
		max(case when quiz_id ='64' THEN biologymark END ) 'JUT64',
		max(case when quiz_id ='65' THEN biologymark END ) 'JUT65',
		max(case when quiz_id ='66' THEN biologymark END ) 'JUT66',
		max(case when quiz_id ='67' THEN biologymark END ) 'JUT67',
		max(case when quiz_id ='68' THEN biologymark END ) 'JUT68',
		max(case when quiz_id ='69' THEN biologymark END ) 'JUT69',
		max(case when quiz_id ='70' THEN biologymark END ) 'JUT70',
		max(case when quiz_id ='71' THEN biologymark END ) 'JUT71',
		max(case when quiz_id ='72' THEN biologymark END ) 'JUT72',
		max(case when quiz_id ='73' THEN biologymark END ) 'JUT73',
		max(case when quiz_id ='74' THEN biologymark END ) 'JUT74',
		max(case when quiz_id ='75' THEN biologymark END ) 'JUT75',
		
		max(case when quiz_id ='103' THEN biologymark END ) 'parttest1',
		max(case when quiz_id ='104' THEN biologymark END ) 'parttest2',
		max(case when quiz_id ='105' THEN biologymark END ) 'parttest3',
		max(case when quiz_id ='106' THEN biologymark END ) 'parttest4',
		max(case when quiz_id ='107' THEN biologymark END ) 'parttest5',
		max(case when quiz_id ='108' THEN biologymark END ) 'parttest6',
		max(case when quiz_id ='109' THEN biologymark END ) 'parttest7',
		max(case when quiz_id ='110' THEN biologymark END ) 'parttest8',
		max(case when quiz_id ='125' THEN biologymark END ) 'fulltest1',
		max(case when quiz_id ='126' THEN biologymark END ) 'fulltest2',
		max(case when quiz_id ='170' THEN biologymark END ) 'fulltest3',
		max(case when quiz_id ='171' THEN biologymark END ) 'fulltest4',
		max(case when quiz_id ='172' THEN biologymark END ) 'fulltest5',
		max(case when quiz_id ='173' THEN biologymark END ) 'fulltest6',
		max(case when quiz_id ='174' THEN biologymark END ) 'fulltest7',
		max(case when quiz_id ='175' THEN biologymark END ) 'fulltest8',
		max(case when quiz_id ='176' THEN biologymark END ) 'fulltest9',
		max(case when quiz_id ='177' THEN biologymark END ) 'fulltest10',
		max(case when quiz_id ='326' THEN biologymark END ) 'fulltest11',
		max(case when quiz_id ='334' THEN biologymark END ) 'fulltest12',
		max(case when quiz_id ='337' THEN biologymark END ) 'fulltest13',
		max(case when quiz_id ='338' THEN biologymark END ) 'fulltest14',
		max(case when quiz_id ='340' THEN biologymark END ) 'fulltest15',
		max(case when quiz_id ='342' THEN biologymark END ) 'fulltest16',
		max(case when quiz_id ='345' THEN biologymark END ) 'fulltest17',
		max(case when quiz_id ='367' THEN biologymark END ) 'fulltest18',
		max(case when quiz_id ='379' THEN biologymark END ) 'fulltest19',
		max(case when quiz_id ='383' THEN biologymark END ) 'fulltest20',
		max(case when quiz_id ='384' THEN biologymark END ) 'fulltest21',
		max(case when quiz_id ='391' THEN biologymark END ) 'fulltest22',
		max(case when quiz_id ='411' THEN biologymark END ) 'fulltest23',
		max(case when quiz_id ='413' THEN biologymark END ) 'fulltest24',
		max(case when quiz_id ='420' THEN biologymark END ) 'fulltest25',


		ifnull(max(case when quiz_id ='1' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='2' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='3' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='4' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='5' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='6' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='7' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='8' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='9' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='10' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='11' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='12' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='13' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='14' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='15' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='16' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='17' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='18' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='19' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='20' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='21' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='22' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='23' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='24' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='25' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='26' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='27' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='28' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='29' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='30' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='31' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='32' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='33' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='34' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='35' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='36' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='37' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='38' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='39' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='40' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='41' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='42' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='43' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='44' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='45' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='46' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='47' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='48' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='49' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='50' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='51' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='52' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='53' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='54' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='55' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='56' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='57' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='58' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='59' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='60' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='61' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='62' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='63' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='64' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='65' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='66' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='67' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='68' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='69' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='70' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='71' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='72' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='73' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='74' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='75' THEN biologymark END ),0) +
	    ifnull(max(case when quiz_id ='103' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='104' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='105' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='106' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='107' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='108' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='109' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='110' THEN biologymark END ),0) +
		
		ifnull(max(case when quiz_id ='125' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='126' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='170' THEN biologymark END ),0) + 
		ifnull(max(case when quiz_id ='171' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='172' THEN biologymark END ),0) + 
		ifnull(max(case when quiz_id ='173' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='174' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='175' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='176' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='177' THEN biologymark END ),0) +
        ifnull(max(case when quiz_id ='326' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='334' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='337' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='338' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='340' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='342' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='345' THEN biologymark END ),0) + 
		ifnull(max(case when quiz_id ='367' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='379' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='383' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='384' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='391' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='411' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='413' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='420' THEN biologymark END ),0)		total
		FROM student_quiz_result a , quiz_info b where a.quiz_id = b.id and b.quiztype='JUT' and user_id collate utf8_general_ci in
				(select USER_NAME collate utf8_general_ci from user_details 
				where jnanasudhastandard = 'JNANAAMRUTHA')Group By user_id ORDER BY biologymark desc ";
				$query = $this->db->query($sql);
				// echo '<pre>';
			   
			   // echo '</pre>';
			  
		}
		
		// print_r($sql);
		 //die;
        $sql = "truncate table consol_jut_rank";
        $query = $this->db->query($sql);
        $sql = "SET @prev_value = NULL";
        $query = $this->db->query($sql);
        $sql = "SET @rank_count = 0";
        $query = $this->db->query($sql);
        $sql = "truncate table consol_jut_rank";
        $query = $this->db->query($sql);
        $sql = "insert into  consol_jut_rank
		select *, CASE
		WHEN @prev_value = total THEN @rank_count
		WHEN @prev_value := total THEN @rank_count := @rank_count + 1

		END AS rank
		FROM all_jut_rank

		ORDER BY total desc";

        $query = $this->db->query($sql);

   $sql = "SELECT a.* ,concat(b.first_name,' ',b.last_name) name,b.batch,b.rollno,b.accommodation from consol_jut_rank a,user_details b  where a.user_id collate utf8_general_ci = b.user_name collate utf8_general_ci
			 ORDER BY CONVERT( a.rank, SIGNED INTEGER )";
        //    print_r($sql);
        $query = $this->db->query($sql);

        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return $query->result_array();
        }

    }
	
	public function get_consol_rank_jee($batch,$sub)
    {
        $sql = "truncate table all_jee_rank";
        $query = $this->db->query($sql);
		if ($batch=='JEEBatch1920' && $sub =='all'){
        $sql = "insert into  all_jee_rank
		select user_id,
		max(case when quiz_id ='118' THEN physicsmark+chemistrymark+biologymark END ) 'JEEJUT1',
		max(case when quiz_id ='119' THEN physicsmark+chemistrymark+biologymark END ) 'JEEJUT2',
		max(case when quiz_id ='120' THEN physicsmark+chemistrymark+biologymark END ) 'JEEJUT3',
		max(case when quiz_id ='122' THEN physicsmark+chemistrymark+biologymark END ) 'JEEJUT4',
		max(case when quiz_id ='123' THEN physicsmark+chemistrymark+biologymark END ) 'JEEJUT5',
		max(case when quiz_id ='124' THEN physicsmark+chemistrymark+biologymark END ) 'JEEJUT6',
		max(case when quiz_id ='127' THEN physicsmark+chemistrymark+biologymark END ) 'JEEJUT7',
		max(case when quiz_id ='128' THEN physicsmark+chemistrymark+biologymark END ) 'JEEJUT8',
		max(case when quiz_id ='129' THEN physicsmark+chemistrymark+biologymark END ) 'JEEJUT9',
		max(case when quiz_id ='130' THEN physicsmark+chemistrymark+biologymark END ) 'JEEJUT10',
		max(case when quiz_id ='131' THEN physicsmark+chemistrymark+biologymark END ) 'JEEJUT11',
		max(case when quiz_id ='132' THEN physicsmark+chemistrymark+biologymark END ) 'JEEJUT12',
		max(case when quiz_id ='133' THEN physicsmark+chemistrymark+biologymark END ) 'JEEJUT13',
		max(case when quiz_id ='134' THEN physicsmark+chemistrymark+biologymark END ) 'JEEJUT14',
		max(case when quiz_id ='136' THEN physicsmark+chemistrymark+biologymark END ) 'JEEJUT15',
		max(case when quiz_id ='137' THEN physicsmark+chemistrymark+biologymark END ) 'JEEJUT16',
		max(case when quiz_id ='139' THEN physicsmark+chemistrymark+biologymark END ) 'JEEJUT17',
		max(case when quiz_id ='141' THEN physicsmark+chemistrymark+biologymark END ) 'JEEJUT18',
		max(case when quiz_id ='142' THEN physicsmark+chemistrymark+biologymark END ) 'JEEJUT19',
		max(case when quiz_id ='158' THEN physicsmark+chemistrymark+biologymark END ) 'JEEJUT20',
		max(case when quiz_id ='160' THEN physicsmark+chemistrymark+biologymark END ) 'JEEJUT21',
		max(case when quiz_id ='161' THEN physicsmark+chemistrymark+biologymark END ) 'JEEJUT22',
		max(case when quiz_id ='163' THEN physicsmark+chemistrymark+biologymark END ) 'JEEJUT23',
		max(case when quiz_id ='164' THEN physicsmark+chemistrymark+biologymark END ) 'JEEJUT24',
		max(case when quiz_id ='165' THEN physicsmark+chemistrymark+biologymark END ) 'JEEJUT25',
		max(case when quiz_id ='166' THEN physicsmark+chemistrymark+biologymark END ) 'JEEJUT26',
		max(case when quiz_id ='167' THEN physicsmark+chemistrymark+biologymark END ) 'JEEJUT27',
		max(case when quiz_id ='168' THEN physicsmark+chemistrymark+biologymark END ) 'JEEJUT28',
		max(case when quiz_id ='169' THEN physicsmark+chemistrymark+biologymark END ) 'JEEJUT29',
		max(case when quiz_id ='302' THEN physicsmark+chemistrymark+biologymark END ) 'JEEJUT30',
		max(case when quiz_id ='303' THEN physicsmark+chemistrymark+biologymark END ) 'JEEJUT31',
		max(case when quiz_id ='304' THEN physicsmark+chemistrymark+biologymark END ) 'JEEJUT32',
		max(case when quiz_id ='305' THEN physicsmark+chemistrymark+biologymark END ) 'JEEJUT33',
		max(case when quiz_id ='306' THEN physicsmark+chemistrymark+biologymark END ) 'JEEJUT34',
		max(case when quiz_id ='307' THEN physicsmark+chemistrymark+biologymark END ) 'JEEJUT35',
		max(case when quiz_id ='308' THEN physicsmark+chemistrymark+biologymark END ) 'JEEJUT36',
		max(case when quiz_id ='309' THEN physicsmark+chemistrymark+biologymark END ) 'JEEJUT37',
		max(case when quiz_id ='310' THEN physicsmark+chemistrymark+biologymark END ) 'JEEJUT38',
		max(case when quiz_id ='311' THEN physicsmark+chemistrymark+biologymark END ) 'JEEJUT39',
		max(case when quiz_id ='312' THEN physicsmark+chemistrymark+biologymark END ) 'JEEJUT40',
		max(case when quiz_id ='313' THEN physicsmark+chemistrymark+biologymark END ) 'JEEJUT41',
		max(case when quiz_id ='314' THEN physicsmark+chemistrymark+biologymark END ) 'JEEJUT42',
		max(case when quiz_id ='315' THEN physicsmark+chemistrymark+biologymark END ) 'JEEJUT43',
		max(case when quiz_id ='316' THEN physicsmark+chemistrymark+biologymark END ) 'JEEJUT44',
		max(case when quiz_id ='317' THEN physicsmark+chemistrymark+biologymark END ) 'JEEJUT45',
		max(case when quiz_id ='318' THEN physicsmark+chemistrymark+biologymark END ) 'JEEJUT46',
		max(case when quiz_id ='319' THEN physicsmark+chemistrymark+biologymark END ) 'JEEJUT47',
		max(case when quiz_id ='320' THEN physicsmark+chemistrymark+biologymark END ) 'JEEJUT48',
		max(case when quiz_id ='321' THEN physicsmark+chemistrymark+biologymark END ) 'JEEJUT49',
		max(case when quiz_id ='322' THEN physicsmark+chemistrymark+biologymark END ) 'JEEJUT50',
		max(case when quiz_id ='395' THEN physicsmark+chemistrymark+biologymark END ) 'JEEJUT51',
		max(case when quiz_id ='396' THEN physicsmark+chemistrymark+biologymark END ) 'JEEJUT52',
		max(case when quiz_id ='397' THEN physicsmark+chemistrymark+biologymark END ) 'JEEJUT53',
		max(case when quiz_id ='398' THEN physicsmark+chemistrymark+biologymark END ) 'JEEJUT54',
		max(case when quiz_id ='399' THEN physicsmark+chemistrymark+biologymark END ) 'JEEJUT55',
		max(case when quiz_id ='400' THEN physicsmark+chemistrymark+biologymark END ) 'JEEJUT56',
		max(case when quiz_id ='401' THEN physicsmark+chemistrymark+biologymark END ) 'JEEJUT57',
		max(case when quiz_id ='402' THEN physicsmark+chemistrymark+biologymark END ) 'JEEJUT58',
		max(case when quiz_id ='403' THEN physicsmark+chemistrymark+biologymark END ) 'JEEJUT59',
		max(case when quiz_id ='404' THEN physicsmark+chemistrymark+biologymark END ) 'JEEJUT60',
		
		max(case when quiz_id ='323' THEN physicsmark+chemistrymark+biologymark END ) 'JEE FULL LENGTH 01',
		max(case when quiz_id ='324' THEN physicsmark+chemistrymark+biologymark END ) 'JEE FULL LENGTH 02',
		max(case when quiz_id ='325' THEN physicsmark+chemistrymark+biologymark END ) 'JEE FULL LENGTH 03',
		max(case when quiz_id ='328' THEN physicsmark+chemistrymark+biologymark END ) 'JEE FULL LENGTH 04',
		max(case when quiz_id ='329' THEN physicsmark+chemistrymark+biologymark END ) 'JEE FULL LENGTH 05',
		max(case when quiz_id ='330' THEN physicsmark+chemistrymark+biologymark END ) 'JEE FULL LENGTH 06',
		max(case when quiz_id ='331' THEN physicsmark+chemistrymark+biologymark END ) 'JEE FULL LENGTH 07',
		max(case when quiz_id ='332' THEN physicsmark+chemistrymark+biologymark END ) 'JEE FULL LENGTH 08',
		max(case when quiz_id ='333' THEN physicsmark+chemistrymark+biologymark END ) 'JEE FULL LENGTH 09',
		max(case when quiz_id ='335' THEN physicsmark+chemistrymark+biologymark END ) 'JEE FULL LENGTH 10',
		max(case when quiz_id ='366' THEN physicsmark+chemistrymark+biologymark END ) 'JEE FULL LENGTH 11',
		max(case when quiz_id ='381' THEN physicsmark+chemistrymark+biologymark END ) 'JEE FULL LENGTH 12',
		max(case when quiz_id ='405' THEN physicsmark+chemistrymark+biologymark END ) 'JEE FULL LENGTH 13',
		max(case when quiz_id ='406' THEN physicsmark+chemistrymark+biologymark END ) 'JEE FULL LENGTH 14',
		max(case when quiz_id ='406' THEN physicsmark+chemistrymark+biologymark END ) 'JEE FULL LENGTH 15',

		ifnull(max(case when quiz_id ='118' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='119' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='120' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='122' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='123' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='124' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='127' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='128' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='129' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='130' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='131' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='132' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='133' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='134' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='136' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='137' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='139' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='141' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='142' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='158' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='160' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='161' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='163' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='164' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='165' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='166' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='167' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='168' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='169' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='302' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='303' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='304' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='305' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='306' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='307' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='308' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='309' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='310' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='311' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='312' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='313' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='314' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='315' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='316' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='317' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='318' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='319' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='320' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='321' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='322' THEN physicsmark+chemistrymark+biologymark END ),0) +
		
		ifnull(max(case when quiz_id ='395' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='396' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='397' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='398' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='399' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='400' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='401' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='402' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='403' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='404' THEN physicsmark+chemistrymark+biologymark END ),0) +
		
		ifnull(max(case when quiz_id ='323' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='324' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='325' THEN physicsmark+chemistrymark+biologymark END ),0) +
	
		ifnull(max(case when quiz_id ='328' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='329' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='330' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='331' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='332' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='333' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='366' THEN physicsmark+chemistrymark+biologymark END ),0)  +
		ifnull(max(case when quiz_id ='381' THEN physicsmark+chemistrymark+biologymark END ),0)  +
		ifnull(max(case when quiz_id ='405' THEN physicsmark+chemistrymark+biologymark END ),0)  +
		ifnull(max(case when quiz_id ='406' THEN physicsmark+chemistrymark+biologymark END ),0) total
		FROM student_quiz_result a , quiz_info b 
		where a.quiz_id = b.id and b.quiztype='NEWJEE' and a.user_id in (select username from subscription_details where package_id ='2')
		GROUP BY user_id";
				$query = $this->db->query($sql);
				//print_r($sql);
		}
		
		if ($batch=='JEEBatch2021' && $sub =='all'){
        $sql = "insert into  all_jee_rank
		select user_id,
		max(case when quiz_id ='118' THEN physicsmark+chemistrymark+biologymark END ) 'JEEJUT1',
		max(case when quiz_id ='119' THEN physicsmark+chemistrymark+biologymark END ) 'JEEJUT2',
		max(case when quiz_id ='120' THEN physicsmark+chemistrymark+biologymark END ) 'JEEJUT3',
		max(case when quiz_id ='122' THEN physicsmark+chemistrymark+biologymark END ) 'JEEJUT4',
		max(case when quiz_id ='123' THEN physicsmark+chemistrymark+biologymark END ) 'JEEJUT5',
		max(case when quiz_id ='124' THEN physicsmark+chemistrymark+biologymark END ) 'JEEJUT6',
		max(case when quiz_id ='127' THEN physicsmark+chemistrymark+biologymark END ) 'JEEJUT7',
		max(case when quiz_id ='128' THEN physicsmark+chemistrymark+biologymark END ) 'JEEJUT8',
		max(case when quiz_id ='129' THEN physicsmark+chemistrymark+biologymark END ) 'JEEJUT9',
		max(case when quiz_id ='130' THEN physicsmark+chemistrymark+biologymark END ) 'JEEJUT10',
		max(case when quiz_id ='131' THEN physicsmark+chemistrymark+biologymark END ) 'JEEJUT11',
		max(case when quiz_id ='132' THEN physicsmark+chemistrymark+biologymark END ) 'JEEJUT12',
		max(case when quiz_id ='133' THEN physicsmark+chemistrymark+biologymark END ) 'JEEJUT13',
		max(case when quiz_id ='134' THEN physicsmark+chemistrymark+biologymark END ) 'JEEJUT14',
		max(case when quiz_id ='136' THEN physicsmark+chemistrymark+biologymark END ) 'JEEJUT15',
		max(case when quiz_id ='137' THEN physicsmark+chemistrymark+biologymark END ) 'JEEJUT16',
		max(case when quiz_id ='139' THEN physicsmark+chemistrymark+biologymark END ) 'JEEJUT17',
		max(case when quiz_id ='141' THEN physicsmark+chemistrymark+biologymark END ) 'JEEJUT18',
		max(case when quiz_id ='142' THEN physicsmark+chemistrymark+biologymark END ) 'JEEJUT19',
		max(case when quiz_id ='158' THEN physicsmark+chemistrymark+biologymark END ) 'JEEJUT20',
		max(case when quiz_id ='160' THEN physicsmark+chemistrymark+biologymark END ) 'JEEJUT21',
		max(case when quiz_id ='161' THEN physicsmark+chemistrymark+biologymark END ) 'JEEJUT22',
		max(case when quiz_id ='163' THEN physicsmark+chemistrymark+biologymark END ) 'JEEJUT23',
		max(case when quiz_id ='164' THEN physicsmark+chemistrymark+biologymark END ) 'JEEJUT24',
		max(case when quiz_id ='165' THEN physicsmark+chemistrymark+biologymark END ) 'JEEJUT25',
		max(case when quiz_id ='166' THEN physicsmark+chemistrymark+biologymark END ) 'JEEJUT26',
		max(case when quiz_id ='167' THEN physicsmark+chemistrymark+biologymark END ) 'JEEJUT27',
		max(case when quiz_id ='168' THEN physicsmark+chemistrymark+biologymark END ) 'JEEJUT28',
		max(case when quiz_id ='169' THEN physicsmark+chemistrymark+biologymark END ) 'JEEJUT29',
		max(case when quiz_id ='302' THEN physicsmark+chemistrymark+biologymark END ) 'JEEJUT30',
		max(case when quiz_id ='303' THEN physicsmark+chemistrymark+biologymark END ) 'JEEJUT31',
		max(case when quiz_id ='304' THEN physicsmark+chemistrymark+biologymark END ) 'JEEJUT32',
		max(case when quiz_id ='305' THEN physicsmark+chemistrymark+biologymark END ) 'JEEJUT33',
		max(case when quiz_id ='306' THEN physicsmark+chemistrymark+biologymark END ) 'JEEJUT34',
		max(case when quiz_id ='307' THEN physicsmark+chemistrymark+biologymark END ) 'JEEJUT35',
		max(case when quiz_id ='308' THEN physicsmark+chemistrymark+biologymark END ) 'JEEJUT36',
		max(case when quiz_id ='309' THEN physicsmark+chemistrymark+biologymark END ) 'JEEJUT37',
		max(case when quiz_id ='310' THEN physicsmark+chemistrymark+biologymark END ) 'JEEJUT38',
		max(case when quiz_id ='311' THEN physicsmark+chemistrymark+biologymark END ) 'JEEJUT39',
		max(case when quiz_id ='312' THEN physicsmark+chemistrymark+biologymark END ) 'JEEJUT40',
		max(case when quiz_id ='313' THEN physicsmark+chemistrymark+biologymark END ) 'JEEJUT41',
		max(case when quiz_id ='314' THEN physicsmark+chemistrymark+biologymark END ) 'JEEJUT42',
		max(case when quiz_id ='315' THEN physicsmark+chemistrymark+biologymark END ) 'JEEJUT43',
		max(case when quiz_id ='316' THEN physicsmark+chemistrymark+biologymark END ) 'JEEJUT44',
		max(case when quiz_id ='317' THEN physicsmark+chemistrymark+biologymark END ) 'JEEJUT45',
		max(case when quiz_id ='318' THEN physicsmark+chemistrymark+biologymark END ) 'JEEJUT46',
		max(case when quiz_id ='319' THEN physicsmark+chemistrymark+biologymark END ) 'JEEJUT47',
		max(case when quiz_id ='320' THEN physicsmark+chemistrymark+biologymark END ) 'JEEJUT48',
		max(case when quiz_id ='321' THEN physicsmark+chemistrymark+biologymark END ) 'JEEJUT49',
		max(case when quiz_id ='322' THEN physicsmark+chemistrymark+biologymark END ) 'JEEJUT50',
		
		
		max(case when quiz_id ='395' THEN physicsmark+chemistrymark+biologymark END ) 'JEEJUT51',
		max(case when quiz_id ='396' THEN physicsmark+chemistrymark+biologymark END ) 'JEEJUT52',
		max(case when quiz_id ='397' THEN physicsmark+chemistrymark+biologymark END ) 'JEEJUT53',
		max(case when quiz_id ='398' THEN physicsmark+chemistrymark+biologymark END ) 'JEEJUT54',
		max(case when quiz_id ='399' THEN physicsmark+chemistrymark+biologymark END ) 'JEEJUT55',
		max(case when quiz_id ='400' THEN physicsmark+chemistrymark+biologymark END ) 'JEEJUT56',
		max(case when quiz_id ='401' THEN physicsmark+chemistrymark+biologymark END ) 'JEEJUT57',
		max(case when quiz_id ='402' THEN physicsmark+chemistrymark+biologymark END ) 'JEEJUT58',
		max(case when quiz_id ='403' THEN physicsmark+chemistrymark+biologymark END ) 'JEEJUT59',
		max(case when quiz_id ='404' THEN physicsmark+chemistrymark+biologymark END ) 'JEEJUT60',
		
		max(case when quiz_id ='323' THEN physicsmark+chemistrymark+biologymark END ) 'JEE FULL LENGTH 01',
		max(case when quiz_id ='324' THEN physicsmark+chemistrymark+biologymark END ) 'JEE FULL LENGTH 02',
		max(case when quiz_id ='325' THEN physicsmark+chemistrymark+biologymark END ) 'JEE FULL LENGTH 03',
		max(case when quiz_id ='328' THEN physicsmark+chemistrymark+biologymark END ) 'JEE FULL LENGTH 04',
		max(case when quiz_id ='329' THEN physicsmark+chemistrymark+biologymark END ) 'JEE FULL LENGTH 05',
		max(case when quiz_id ='330' THEN physicsmark+chemistrymark+biologymark END ) 'JEE FULL LENGTH 06',
		max(case when quiz_id ='331' THEN physicsmark+chemistrymark+biologymark END ) 'JEE FULL LENGTH 07',
		max(case when quiz_id ='332' THEN physicsmark+chemistrymark+biologymark END ) 'JEE FULL LENGTH 08',
		max(case when quiz_id ='333' THEN physicsmark+chemistrymark+biologymark END ) 'JEE FULL LENGTH 09',
		max(case when quiz_id ='335' THEN physicsmark+chemistrymark+biologymark END ) 'JEE FULL LENGTH 10',
		max(case when quiz_id ='366' THEN physicsmark+chemistrymark+biologymark END ) 'JEE FULL LENGTH 11',
		max(case when quiz_id ='381' THEN physicsmark+chemistrymark+biologymark END ) 'JEE FULL LENGTH 12',
		max(case when quiz_id ='405' THEN physicsmark+chemistrymark+biologymark END ) 'JEE FULL LENGTH 13',
		max(case when quiz_id ='406' THEN physicsmark+chemistrymark+biologymark END ) 'JEE FULL LENGTH 14',
		max(case when quiz_id ='406' THEN physicsmark+chemistrymark+biologymark END ) 'JEE FULL LENGTH 15',

		ifnull(max(case when quiz_id ='118' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='119' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='120' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='122' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='123' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='124' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='127' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='128' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='129' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='130' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='131' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='132' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='133' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='134' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='136' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='137' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='139' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='141' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='142' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='158' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='160' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='161' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='163' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='164' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='165' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='166' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='167' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='168' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='169' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='302' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='303' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='304' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='305' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='306' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='307' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='308' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='309' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='310' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='311' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='312' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='313' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='314' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='315' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='316' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='317' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='318' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='319' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='320' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='321' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='322' THEN physicsmark+chemistrymark+biologymark END ),0) +
		
		ifnull(max(case when quiz_id ='395' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='396' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='397' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='398' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='399' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='400' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='401' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='402' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='403' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='404' THEN physicsmark+chemistrymark+biologymark END ),0) +
		
		ifnull(max(case when quiz_id ='323' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='324' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='325' THEN physicsmark+chemistrymark+biologymark END ),0) +
	
		ifnull(max(case when quiz_id ='328' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='329' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='330' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='331' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='332' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='333' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='366' THEN physicsmark+chemistrymark+biologymark END ),0)  +
		ifnull(max(case when quiz_id ='381' THEN physicsmark+chemistrymark+biologymark END ),0)  +
		ifnull(max(case when quiz_id ='405' THEN physicsmark+chemistrymark+biologymark END ),0)  +
		ifnull(max(case when quiz_id ='406' THEN physicsmark+chemistrymark+biologymark END ),0) total
		FROM student_quiz_result a , quiz_info b 
		where a.quiz_id = b.id and b.quiztype='NEWJEE' and a.user_id in (select username from subscription_details where package_id ='14')
		GROUP BY user_id";
				$query = $this->db->query($sql);
				//print_r($sql);
		}
		if ($batch=='JEEBatch1920' && $sub =='physics'){
        $sql = "insert into  all_jee_rank
		select user_id,
		max(case when quiz_id ='118' THEN physicsmark END ) 'JEEJUT1',
		max(case when quiz_id ='119' THEN physicsmark END ) 'JEEJUT2',
		max(case when quiz_id ='120' THEN physicsmark END ) 'JEEJUT3',
		max(case when quiz_id ='122' THEN physicsmark END ) 'JEEJUT4',
		max(case when quiz_id ='123' THEN physicsmark END ) 'JEEJUT5',
		max(case when quiz_id ='124' THEN physicsmark END ) 'JEEJUT6',
		max(case when quiz_id ='127' THEN physicsmark END ) 'JEEJUT7',
		max(case when quiz_id ='128' THEN physicsmark END ) 'JEEJUT8',
		max(case when quiz_id ='129' THEN physicsmark END ) 'JEEJUT9',
		max(case when quiz_id ='130' THEN physicsmark END ) 'JEEJUT10',
		max(case when quiz_id ='131' THEN physicsmark END ) 'JEEJUT11',
		max(case when quiz_id ='132' THEN physicsmark END ) 'JEEJUT12',
		max(case when quiz_id ='133' THEN physicsmark END ) 'JEEJUT13',
		max(case when quiz_id ='134' THEN physicsmark END ) 'JEEJUT14',
		max(case when quiz_id ='136' THEN physicsmark END ) 'JEEJUT15',
		max(case when quiz_id ='137' THEN physicsmark END ) 'JEEJUT16',
		max(case when quiz_id ='139' THEN physicsmark END ) 'JEEJUT17',
		max(case when quiz_id ='141' THEN physicsmark END ) 'JEEJUT18',
		max(case when quiz_id ='142' THEN physicsmark END ) 'JEEJUT19',
		max(case when quiz_id ='158' THEN physicsmark END ) 'JEEJUT20',
		max(case when quiz_id ='160' THEN physicsmark END ) 'JEEJUT21',
		max(case when quiz_id ='161' THEN physicsmark END ) 'JEEJUT22',
		max(case when quiz_id ='163' THEN physicsmark END ) 'JEEJUT23',
		max(case when quiz_id ='164' THEN physicsmark END ) 'JEEJUT24',
		max(case when quiz_id ='165' THEN physicsmark END ) 'JEEJUT25',
		max(case when quiz_id ='166' THEN physicsmark END ) 'JEEJUT26',
		max(case when quiz_id ='167' THEN physicsmark END ) 'JEEJUT27',
		max(case when quiz_id ='168' THEN physicsmark END ) 'JEEJUT28',
		max(case when quiz_id ='169' THEN physicsmark END ) 'JEEJUT29',
		max(case when quiz_id ='302' THEN physicsmark END ) 'JEEJUT30',
		max(case when quiz_id ='303' THEN physicsmark END ) 'JEEJUT31',
		max(case when quiz_id ='304' THEN physicsmark END ) 'JEEJUT32',
		max(case when quiz_id ='305' THEN physicsmark END ) 'JEEJUT33',
		max(case when quiz_id ='306' THEN physicsmark END ) 'JEEJUT34',
		max(case when quiz_id ='307' THEN physicsmark END ) 'JEEJUT35',
		max(case when quiz_id ='308' THEN physicsmark END ) 'JEEJUT36',
		max(case when quiz_id ='309' THEN physicsmark END ) 'JEEJUT37',
		max(case when quiz_id ='310' THEN physicsmark END ) 'JEEJUT38',
		max(case when quiz_id ='311' THEN physicsmark END ) 'JEEJUT39',
		max(case when quiz_id ='312' THEN physicsmark END ) 'JEEJUT40',
		max(case when quiz_id ='313' THEN physicsmark END ) 'JEEJUT41',
		max(case when quiz_id ='314' THEN physicsmark END ) 'JEEJUT42',
		max(case when quiz_id ='315' THEN physicsmark END ) 'JEEJUT43',
		max(case when quiz_id ='316' THEN physicsmark END ) 'JEEJUT44',
		max(case when quiz_id ='317' THEN physicsmark END ) 'JEEJUT45',
		max(case when quiz_id ='318' THEN physicsmark END ) 'JEEJUT46',
		max(case when quiz_id ='319' THEN physicsmark END ) 'JEEJUT47',
		max(case when quiz_id ='320' THEN physicsmark END ) 'JEEJUT48',
		max(case when quiz_id ='321' THEN physicsmark END ) 'JEEJUT49',
		max(case when quiz_id ='322' THEN physicsmark END ) 'JEEJUT50',
		
		max(case when quiz_id ='395' THEN physicsmark END ) 'JEEJUT51',
		max(case when quiz_id ='396' THEN physicsmark END ) 'JEEJUT52',
		max(case when quiz_id ='397' THEN physicsmark END ) 'JEEJUT53',
		max(case when quiz_id ='398' THEN physicsmark END ) 'JEEJUT54',
		max(case when quiz_id ='399' THEN physicsmark END ) 'JEEJUT55',
		max(case when quiz_id ='400' THEN physicsmark END ) 'JEEJUT56',
		max(case when quiz_id ='401' THEN physicsmark END ) 'JEEJUT57',
		max(case when quiz_id ='402' THEN physicsmark END ) 'JEEJUT58',
		max(case when quiz_id ='403' THEN physicsmark END ) 'JEEJUT59',
		max(case when quiz_id ='404' THEN physicsmark END ) 'JEEJUT60',
		
		max(case when quiz_id ='323' THEN physicsmark END ) 'JEE FULL LENGTH 01',
		max(case when quiz_id ='324' THEN physicsmark END ) 'JEE FULL LENGTH 02',
		max(case when quiz_id ='325' THEN physicsmark END ) 'JEE FULL LENGTH 03',
		max(case when quiz_id ='328' THEN physicsmark END ) 'JEE FULL LENGTH 04',
		max(case when quiz_id ='329' THEN physicsmark END ) 'JEE FULL LENGTH 05',
		max(case when quiz_id ='330' THEN physicsmark END ) 'JEE FULL LENGTH 06',
		max(case when quiz_id ='331' THEN physicsmark END ) 'JEE FULL LENGTH 07',
		max(case when quiz_id ='332' THEN physicsmark END ) 'JEE FULL LENGTH 08',
		max(case when quiz_id ='333' THEN physicsmark END ) 'JEE FULL LENGTH 09',
		max(case when quiz_id ='335' THEN physicsmark END ) 'JEE FULL LENGTH 10',
		max(case when quiz_id ='366' THEN physicsmark END ) 'JEE FULL LENGTH 11',
		max(case when quiz_id ='381' THEN physicsmark END ) 'JEE FULL LENGTH 12',
		max(case when quiz_id ='405' THEN physicsmark END ) 'JEE FULL LENGTH 13',
		max(case when quiz_id ='406' THEN physicsmark END ) 'JEE FULL LENGTH 14',
		max(case when quiz_id ='406' THEN physicsmark END ) 'JEE FULL LENGTH 15',

		ifnull(max(case when quiz_id ='118' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='119' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='120' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='122' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='123' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='124' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='127' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='128' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='129' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='130' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='131' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='132' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='133' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='134' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='136' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='137' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='139' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='141' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='142' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='158' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='160' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='161' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='163' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='164' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='165' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='166' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='167' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='168' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='169' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='302' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='303' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='304' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='305' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='306' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='307' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='308' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='309' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='310' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='311' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='312' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='313' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='314' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='315' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='316' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='317' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='318' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='319' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='320' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='321' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='322' THEN physicsmark END ),0) +
		
		ifnull(max(case when quiz_id ='395' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='396' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='397' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='398' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='399' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='400' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='401' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='402' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='403' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='404' THEN physicsmark END ),0) +
		
		ifnull(max(case when quiz_id ='323' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='324' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='325' THEN physicsmark END ),0) +
	
		ifnull(max(case when quiz_id ='328' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='329' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='330' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='331' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='332' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='333' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='366' THEN physicsmark END ),0)  +
		ifnull(max(case when quiz_id ='381' THEN physicsmark END ),0)  +
		ifnull(max(case when quiz_id ='405' THEN physicsmark END ),0)  +
		ifnull(max(case when quiz_id ='406' THEN physicsmark END ),0) total
		FROM student_quiz_result a , quiz_info b 
		where a.quiz_id = b.id and b.quiztype='NEWJEE' and a.user_id in (select username from subscription_details where package_id ='2')
		GROUP BY user_id ORDER BY physicsmark desc";
				$query = $this->db->query($sql);
				//print_r($sql);
		}
		if ($batch=='JEEBatch1920' && $sub =='chemistry'){
        $sql = "insert into all_jee_rank
		select user_id,
		max(case when quiz_id ='118' THEN chemistrymark END ) 'JEEJUT1',
		max(case when quiz_id ='119' THEN chemistrymark END ) 'JEEJUT2',
		max(case when quiz_id ='120' THEN chemistrymark END ) 'JEEJUT3',
		max(case when quiz_id ='122' THEN chemistrymark END ) 'JEEJUT4',
		max(case when quiz_id ='123' THEN chemistrymark END ) 'JEEJUT5',
		max(case when quiz_id ='124' THEN chemistrymark END ) 'JEEJUT6',
		max(case when quiz_id ='127' THEN chemistrymark END ) 'JEEJUT7',
		max(case when quiz_id ='128' THEN chemistrymark END ) 'JEEJUT8',
		max(case when quiz_id ='129' THEN chemistrymark END ) 'JEEJUT9',
		max(case when quiz_id ='130' THEN chemistrymark END ) 'JEEJUT10',
		max(case when quiz_id ='131' THEN chemistrymark END ) 'JEEJUT11',
		max(case when quiz_id ='132' THEN chemistrymark END ) 'JEEJUT12',
		max(case when quiz_id ='133' THEN chemistrymark END ) 'JEEJUT13',
		max(case when quiz_id ='134' THEN chemistrymark END ) 'JEEJUT14',
		max(case when quiz_id ='136' THEN chemistrymark END ) 'JEEJUT15',
		max(case when quiz_id ='137' THEN chemistrymark END ) 'JEEJUT16',
		max(case when quiz_id ='139' THEN chemistrymark END ) 'JEEJUT17',
		max(case when quiz_id ='141' THEN chemistrymark END ) 'JEEJUT18',
		max(case when quiz_id ='142' THEN chemistrymark END ) 'JEEJUT19',
		max(case when quiz_id ='158' THEN chemistrymark END ) 'JEEJUT20',
		max(case when quiz_id ='160' THEN chemistrymark END ) 'JEEJUT21',
		max(case when quiz_id ='161' THEN chemistrymark END ) 'JEEJUT22',
		max(case when quiz_id ='163' THEN chemistrymark END ) 'JEEJUT23',
		max(case when quiz_id ='164' THEN chemistrymark END ) 'JEEJUT24',
		max(case when quiz_id ='165' THEN chemistrymark END ) 'JEEJUT25',
		max(case when quiz_id ='166' THEN chemistrymark END ) 'JEEJUT26',
		max(case when quiz_id ='167' THEN chemistrymark END ) 'JEEJUT27',
		max(case when quiz_id ='168' THEN chemistrymark END ) 'JEEJUT28',
		max(case when quiz_id ='169' THEN chemistrymark END ) 'JEEJUT29',
		max(case when quiz_id ='302' THEN chemistrymark END ) 'JEEJUT30',
		max(case when quiz_id ='303' THEN chemistrymark END ) 'JEEJUT31',
		max(case when quiz_id ='304' THEN chemistrymark END ) 'JEEJUT32',
		max(case when quiz_id ='305' THEN chemistrymark END ) 'JEEJUT33',
		max(case when quiz_id ='306' THEN chemistrymark END ) 'JEEJUT34',
		max(case when quiz_id ='307' THEN chemistrymark END ) 'JEEJUT35',
		max(case when quiz_id ='308' THEN chemistrymark END ) 'JEEJUT36',
		max(case when quiz_id ='309' THEN chemistrymark END ) 'JEEJUT37',
		max(case when quiz_id ='310' THEN chemistrymark END ) 'JEEJUT38',
		max(case when quiz_id ='311' THEN chemistrymark END ) 'JEEJUT39',
		max(case when quiz_id ='312' THEN chemistrymark END ) 'JEEJUT40',
		max(case when quiz_id ='313' THEN chemistrymark END ) 'JEEJUT41',
		max(case when quiz_id ='314' THEN chemistrymark END ) 'JEEJUT42',
		max(case when quiz_id ='315' THEN chemistrymark END ) 'JEEJUT43',
		max(case when quiz_id ='316' THEN chemistrymark END ) 'JEEJUT44',
		max(case when quiz_id ='317' THEN chemistrymark END ) 'JEEJUT45',
		max(case when quiz_id ='318' THEN chemistrymark END ) 'JEEJUT46',
		max(case when quiz_id ='319' THEN chemistrymark END ) 'JEEJUT47',
		max(case when quiz_id ='320' THEN chemistrymark END ) 'JEEJUT48',
		max(case when quiz_id ='321' THEN chemistrymark END ) 'JEEJUT49',
		max(case when quiz_id ='322' THEN chemistrymark END ) 'JEEJUT50',
		
		
		max(case when quiz_id ='395' THEN chemistrymark END ) 'JEEJUT51',
		max(case when quiz_id ='396' THEN chemistrymark END ) 'JEEJUT52',
		max(case when quiz_id ='397' THEN chemistrymark END ) 'JEEJUT53',
		max(case when quiz_id ='398' THEN chemistrymark END ) 'JEEJUT54',
		max(case when quiz_id ='399' THEN chemistrymark END ) 'JEEJUT55',
		max(case when quiz_id ='400' THEN chemistrymark END ) 'JEEJUT56',
		max(case when quiz_id ='401' THEN chemistrymark END ) 'JEEJUT57',
		max(case when quiz_id ='402' THEN chemistrymark END ) 'JEEJUT58',
		max(case when quiz_id ='403' THEN chemistrymark END ) 'JEEJUT59',
		max(case when quiz_id ='404' THEN chemistrymark END ) 'JEEJUT60',
		
		max(case when quiz_id ='323' THEN chemistrymark END ) 'JEE FULL LENGTH 01',
		max(case when quiz_id ='324' THEN chemistrymark END ) 'JEE FULL LENGTH 02',
		max(case when quiz_id ='325' THEN chemistrymark END ) 'JEE FULL LENGTH 03',
		max(case when quiz_id ='328' THEN chemistrymark END ) 'JEE FULL LENGTH 04',
		max(case when quiz_id ='329' THEN chemistrymark END ) 'JEE FULL LENGTH 05',
		max(case when quiz_id ='330' THEN chemistrymark END ) 'JEE FULL LENGTH 06',
		max(case when quiz_id ='331' THEN chemistrymark END ) 'JEE FULL LENGTH 07',
		max(case when quiz_id ='332' THEN chemistrymark END ) 'JEE FULL LENGTH 08',
		max(case when quiz_id ='333' THEN chemistrymark END ) 'JEE FULL LENGTH 09',
		max(case when quiz_id ='335' THEN chemistrymark END ) 'JEE FULL LENGTH 10',
		max(case when quiz_id ='366' THEN chemistrymark END ) 'JEE FULL LENGTH 11',
		max(case when quiz_id ='381' THEN chemistrymark END ) 'JEE FULL LENGTH 12',
		max(case when quiz_id ='405' THEN chemistrymark END ) 'JEE FULL LENGTH 13',
		max(case when quiz_id ='406' THEN chemistrymark END ) 'JEE FULL LENGTH 14',
		max(case when quiz_id ='406' THEN chemistrymark END ) 'JEE FULL LENGTH 15',

		ifnull(max(case when quiz_id ='118' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='119' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='120' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='122' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='123' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='124' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='127' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='128' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='129' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='130' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='131' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='132' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='133' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='134' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='136' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='137' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='139' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='141' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='142' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='158' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='160' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='161' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='163' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='164' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='165' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='166' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='167' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='168' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='169' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='302' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='303' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='304' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='305' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='306' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='307' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='308' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='309' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='310' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='311' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='312' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='313' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='314' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='315' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='316' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='317' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='318' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='319' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='320' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='321' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='322' THEN chemistrymark END ),0) +
		
		ifnull(max(case when quiz_id ='395' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='396' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='397' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='398' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='399' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='400' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='401' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='402' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='403' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='404' THEN chemistrymark END ),0) +
		
		ifnull(max(case when quiz_id ='323' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='324' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='325' THEN chemistrymark END ),0) +
	
		ifnull(max(case when quiz_id ='328' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='329' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='330' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='331' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='332' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='333' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='366' THEN chemistrymark END ),0)  +
		ifnull(max(case when quiz_id ='381' THEN chemistrymark END ),0)  +
		ifnull(max(case when quiz_id ='405' THEN chemistrymark END ),0)  +
		ifnull(max(case when quiz_id ='406' THEN chemistrymark END ),0) total
		FROM student_quiz_result a , quiz_info b 
		where a.quiz_id = b.id and b.quiztype='NEWJEE' and a.user_id in (select username from subscription_details where package_id ='2')
		GROUP BY user_id";
				$query = $this->db->query($sql);
				//print_r($sql);
		}
		if ($batch=='JEEBatch1920' && $sub =='maths'){
        $sql = "insert into  all_jee_rank
		select user_id,
		max(case when quiz_id ='118' THEN biologymark END ) 'JEEJUT1',
		max(case when quiz_id ='119' THEN biologymark END ) 'JEEJUT2',
		max(case when quiz_id ='120' THEN biologymark END ) 'JEEJUT3',
		max(case when quiz_id ='122' THEN biologymark END ) 'JEEJUT4',
		max(case when quiz_id ='123' THEN biologymark END ) 'JEEJUT5',
		max(case when quiz_id ='124' THEN biologymark END ) 'JEEJUT6',
		max(case when quiz_id ='127' THEN biologymark END ) 'JEEJUT7',
		max(case when quiz_id ='128' THEN biologymark END ) 'JEEJUT8',
		max(case when quiz_id ='129' THEN biologymark END ) 'JEEJUT9',
		max(case when quiz_id ='130' THEN biologymark END ) 'JEEJUT10',
		max(case when quiz_id ='131' THEN biologymark END ) 'JEEJUT11',
		max(case when quiz_id ='132' THEN biologymark END ) 'JEEJUT12',
		max(case when quiz_id ='133' THEN biologymark END ) 'JEEJUT13',
		max(case when quiz_id ='134' THEN biologymark END ) 'JEEJUT14',
		max(case when quiz_id ='136' THEN biologymark END ) 'JEEJUT15',
		max(case when quiz_id ='137' THEN biologymark END ) 'JEEJUT16',
		max(case when quiz_id ='139' THEN biologymark END ) 'JEEJUT17',
		max(case when quiz_id ='141' THEN biologymark END ) 'JEEJUT18',
		max(case when quiz_id ='142' THEN biologymark END ) 'JEEJUT19',
		max(case when quiz_id ='158' THEN biologymark END ) 'JEEJUT20',
		max(case when quiz_id ='160' THEN biologymark END ) 'JEEJUT21',
		max(case when quiz_id ='161' THEN biologymark END ) 'JEEJUT22',
		max(case when quiz_id ='163' THEN biologymark END ) 'JEEJUT23',
		max(case when quiz_id ='164' THEN biologymark END ) 'JEEJUT24',
		max(case when quiz_id ='165' THEN biologymark END ) 'JEEJUT25',
		max(case when quiz_id ='166' THEN biologymark END ) 'JEEJUT26',
		max(case when quiz_id ='167' THEN biologymark END ) 'JEEJUT27',
		max(case when quiz_id ='168' THEN biologymark END ) 'JEEJUT28',
		max(case when quiz_id ='169' THEN biologymark END ) 'JEEJUT29',
		max(case when quiz_id ='302' THEN biologymark END ) 'JEEJUT30',
		max(case when quiz_id ='303' THEN biologymark END ) 'JEEJUT31',
		max(case when quiz_id ='304' THEN biologymark END ) 'JEEJUT32',
		max(case when quiz_id ='305' THEN biologymark END ) 'JEEJUT33',
		max(case when quiz_id ='306' THEN biologymark END ) 'JEEJUT34',
		max(case when quiz_id ='307' THEN biologymark END ) 'JEEJUT35',
		max(case when quiz_id ='308' THEN biologymark END ) 'JEEJUT36',
		max(case when quiz_id ='309' THEN biologymark END ) 'JEEJUT37',
		max(case when quiz_id ='310' THEN biologymark END ) 'JEEJUT38',
		max(case when quiz_id ='311' THEN biologymark END ) 'JEEJUT39',
		max(case when quiz_id ='312' THEN biologymark END ) 'JEEJUT40',
		max(case when quiz_id ='313' THEN biologymark END ) 'JEEJUT41',
		max(case when quiz_id ='314' THEN biologymark END ) 'JEEJUT42',
		max(case when quiz_id ='315' THEN biologymark END ) 'JEEJUT43',
		max(case when quiz_id ='316' THEN biologymark END ) 'JEEJUT44',
		max(case when quiz_id ='317' THEN biologymark END ) 'JEEJUT45',
		max(case when quiz_id ='318' THEN biologymark END ) 'JEEJUT46',
		max(case when quiz_id ='319' THEN biologymark END ) 'JEEJUT47',
		max(case when quiz_id ='320' THEN biologymark END ) 'JEEJUT48',
		max(case when quiz_id ='321' THEN biologymark END ) 'JEEJUT49',
		max(case when quiz_id ='322' THEN biologymark END ) 'JEEJUT50',
		
	    max(case when quiz_id ='395' THEN biologymark END ) 'JEEJUT51',
		max(case when quiz_id ='396' THEN biologymark END ) 'JEEJUT52',
		max(case when quiz_id ='397' THEN biologymark END ) 'JEEJUT53',
		max(case when quiz_id ='398' THEN biologymark END ) 'JEEJUT54',
		max(case when quiz_id ='399' THEN biologymark END ) 'JEEJUT55',
		max(case when quiz_id ='400' THEN biologymark END ) 'JEEJUT56',
		max(case when quiz_id ='401' THEN biologymark END ) 'JEEJUT57',
		max(case when quiz_id ='402' THEN biologymark END ) 'JEEJUT58',
		max(case when quiz_id ='403' THEN biologymark END ) 'JEEJUT59',
		max(case when quiz_id ='404' THEN biologymark END ) 'JEEJUT60',
		
		max(case when quiz_id ='323' THEN biologymark END ) 'JEE FULL LENGTH 01',
		max(case when quiz_id ='324' THEN biologymark END ) 'JEE FULL LENGTH 02',
		max(case when quiz_id ='325' THEN biologymark END ) 'JEE FULL LENGTH 03',
		max(case when quiz_id ='328' THEN biologymark END ) 'JEE FULL LENGTH 04',
		max(case when quiz_id ='329' THEN biologymark END ) 'JEE FULL LENGTH 05',
		max(case when quiz_id ='330' THEN biologymark END ) 'JEE FULL LENGTH 06',
		max(case when quiz_id ='331' THEN biologymark END ) 'JEE FULL LENGTH 07',
		max(case when quiz_id ='332' THEN biologymark END ) 'JEE FULL LENGTH 08',
		max(case when quiz_id ='333' THEN biologymark END ) 'JEE FULL LENGTH 09',
		max(case when quiz_id ='335' THEN biologymark END ) 'JEE FULL LENGTH 10',
		max(case when quiz_id ='366' THEN biologymark END ) 'JEE FULL LENGTH 11',
		max(case when quiz_id ='381' THEN biologymark END ) 'JEE FULL LENGTH 12',
		max(case when quiz_id ='405' THEN biologymark END ) 'JEE FULL LENGTH 13',
		max(case when quiz_id ='406' THEN biologymark END ) 'JEE FULL LENGTH 14',
		max(case when quiz_id ='406' THEN biologymark END ) 'JEE FULL LENGTH 15',

		ifnull(max(case when quiz_id ='118' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='119' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='120' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='122' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='123' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='124' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='127' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='128' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='129' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='130' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='131' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='132' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='133' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='134' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='136' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='137' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='139' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='141' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='142' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='158' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='160' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='161' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='163' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='164' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='165' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='166' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='167' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='168' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='169' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='302' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='303' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='304' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='305' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='306' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='307' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='308' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='309' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='310' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='311' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='312' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='313' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='314' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='315' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='316' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='317' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='318' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='319' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='320' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='321' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='322' THEN biologymark END ),0) +
		
	    ifnull(max(case when quiz_id ='395' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='396' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='397' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='398' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='399' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='400' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='401' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='402' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='403' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='404' THEN biologymark END ),0) +
		
		ifnull(max(case when quiz_id ='323' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='324' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='325' THEN biologymark END ),0) +
	
		ifnull(max(case when quiz_id ='328' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='329' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='330' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='331' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='332' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='333' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='366' THEN biologymark END ),0)  +
		ifnull(max(case when quiz_id ='381' THEN biologymark END ),0)  +
		ifnull(max(case when quiz_id ='405' THEN biologymark END ),0)  +
		ifnull(max(case when quiz_id ='406' THEN biologymark END ),0) total
		FROM student_quiz_result a , quiz_info b 
		where a.quiz_id = b.id and b.quiztype='NEWJEE' and a.user_id in (select username from subscription_details where package_id ='2')
		GROUP BY user_id";
				$query = $this->db->query($sql);
				//print_r($sql);
		}
		
		/*******batch 20-21******/
		if ($batch=='JEEBatch2021' && $sub =='physics'){
        $sql = "insert into  all_jee_rank
		select user_id,
		max(case when quiz_id ='118' THEN physicsmark END ) 'JEEJUT1',
		max(case when quiz_id ='119' THEN physicsmark END ) 'JEEJUT2',
		max(case when quiz_id ='120' THEN physicsmark END ) 'JEEJUT3',
		max(case when quiz_id ='122' THEN physicsmark END ) 'JEEJUT4',
		max(case when quiz_id ='123' THEN physicsmark END ) 'JEEJUT5',
		max(case when quiz_id ='124' THEN physicsmark END ) 'JEEJUT6',
		max(case when quiz_id ='127' THEN physicsmark END ) 'JEEJUT7',
		max(case when quiz_id ='128' THEN physicsmark END ) 'JEEJUT8',
		max(case when quiz_id ='129' THEN physicsmark END ) 'JEEJUT9',
		max(case when quiz_id ='130' THEN physicsmark END ) 'JEEJUT10',
		max(case when quiz_id ='131' THEN physicsmark END ) 'JEEJUT11',
		max(case when quiz_id ='132' THEN physicsmark END ) 'JEEJUT12',
		max(case when quiz_id ='133' THEN physicsmark END ) 'JEEJUT13',
		max(case when quiz_id ='134' THEN physicsmark END ) 'JEEJUT14',
		max(case when quiz_id ='136' THEN physicsmark END ) 'JEEJUT15',
		max(case when quiz_id ='137' THEN physicsmark END ) 'JEEJUT16',
		max(case when quiz_id ='139' THEN physicsmark END ) 'JEEJUT17',
		max(case when quiz_id ='141' THEN physicsmark END ) 'JEEJUT18',
		max(case when quiz_id ='142' THEN physicsmark END ) 'JEEJUT19',
		max(case when quiz_id ='158' THEN physicsmark END ) 'JEEJUT20',
		max(case when quiz_id ='160' THEN physicsmark END ) 'JEEJUT21',
		max(case when quiz_id ='161' THEN physicsmark END ) 'JEEJUT22',
		max(case when quiz_id ='163' THEN physicsmark END ) 'JEEJUT23',
		max(case when quiz_id ='164' THEN physicsmark END ) 'JEEJUT24',
		max(case when quiz_id ='165' THEN physicsmark END ) 'JEEJUT25',
		max(case when quiz_id ='166' THEN physicsmark END ) 'JEEJUT26',
		max(case when quiz_id ='167' THEN physicsmark END ) 'JEEJUT27',
		max(case when quiz_id ='168' THEN physicsmark END ) 'JEEJUT28',
		max(case when quiz_id ='169' THEN physicsmark END ) 'JEEJUT29',
		max(case when quiz_id ='302' THEN physicsmark END ) 'JEEJUT30',
		max(case when quiz_id ='303' THEN physicsmark END ) 'JEEJUT31',
		max(case when quiz_id ='304' THEN physicsmark END ) 'JEEJUT32',
		max(case when quiz_id ='305' THEN physicsmark END ) 'JEEJUT33',
		max(case when quiz_id ='306' THEN physicsmark END ) 'JEEJUT34',
		max(case when quiz_id ='307' THEN physicsmark END ) 'JEEJUT35',
		max(case when quiz_id ='308' THEN physicsmark END ) 'JEEJUT36',
		max(case when quiz_id ='309' THEN physicsmark END ) 'JEEJUT37',
		max(case when quiz_id ='310' THEN physicsmark END ) 'JEEJUT38',
		max(case when quiz_id ='311' THEN physicsmark END ) 'JEEJUT39',
		max(case when quiz_id ='312' THEN physicsmark END ) 'JEEJUT40',
		max(case when quiz_id ='313' THEN physicsmark END ) 'JEEJUT41',
		max(case when quiz_id ='314' THEN physicsmark END ) 'JEEJUT42',
		max(case when quiz_id ='315' THEN physicsmark END ) 'JEEJUT43',
		max(case when quiz_id ='316' THEN physicsmark END ) 'JEEJUT44',
		max(case when quiz_id ='317' THEN physicsmark END ) 'JEEJUT45',
		max(case when quiz_id ='318' THEN physicsmark END ) 'JEEJUT46',
		max(case when quiz_id ='319' THEN physicsmark END ) 'JEEJUT47',
		max(case when quiz_id ='320' THEN physicsmark END ) 'JEEJUT48',
		max(case when quiz_id ='321' THEN physicsmark END ) 'JEEJUT49',
		max(case when quiz_id ='322' THEN physicsmark END ) 'JEEJUT50',
		
		max(case when quiz_id ='395' THEN physicsmark END ) 'JEEJUT51',
		max(case when quiz_id ='396' THEN physicsmark END ) 'JEEJUT52',
		max(case when quiz_id ='397' THEN physicsmark END ) 'JEEJUT53',
		max(case when quiz_id ='398' THEN physicsmark END ) 'JEEJUT54',
		max(case when quiz_id ='399' THEN physicsmark END ) 'JEEJUT55',
		max(case when quiz_id ='400' THEN physicsmark END ) 'JEEJUT56',
		max(case when quiz_id ='401' THEN physicsmark END ) 'JEEJUT57',
		max(case when quiz_id ='402' THEN physicsmark END ) 'JEEJUT58',
		max(case when quiz_id ='403' THEN physicsmark END ) 'JEEJUT59',
		max(case when quiz_id ='404' THEN physicsmark END ) 'JEEJUT60',
		
		max(case when quiz_id ='323' THEN physicsmark END ) 'JEE FULL LENGTH 01',
		max(case when quiz_id ='324' THEN physicsmark END ) 'JEE FULL LENGTH 02',
		max(case when quiz_id ='325' THEN physicsmark END ) 'JEE FULL LENGTH 03',
		max(case when quiz_id ='328' THEN physicsmark END ) 'JEE FULL LENGTH 04',
		max(case when quiz_id ='329' THEN physicsmark END ) 'JEE FULL LENGTH 05',
		max(case when quiz_id ='330' THEN physicsmark END ) 'JEE FULL LENGTH 06',
		max(case when quiz_id ='331' THEN physicsmark END ) 'JEE FULL LENGTH 07',
		max(case when quiz_id ='332' THEN physicsmark END ) 'JEE FULL LENGTH 08',
		max(case when quiz_id ='333' THEN physicsmark END ) 'JEE FULL LENGTH 09',
		max(case when quiz_id ='335' THEN physicsmark END ) 'JEE FULL LENGTH 10',
		max(case when quiz_id ='366' THEN physicsmark END ) 'JEE FULL LENGTH 11',
		max(case when quiz_id ='381' THEN physicsmark END ) 'JEE FULL LENGTH 12',
		max(case when quiz_id ='405' THEN physicsmark END ) 'JEE FULL LENGTH 13',
		max(case when quiz_id ='406' THEN physicsmark END ) 'JEE FULL LENGTH 14',
		max(case when quiz_id ='406' THEN physicsmark END ) 'JEE FULL LENGTH 15',

		ifnull(max(case when quiz_id ='118' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='119' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='120' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='122' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='123' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='124' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='127' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='128' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='129' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='130' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='131' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='132' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='133' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='134' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='136' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='137' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='139' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='141' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='142' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='158' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='160' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='161' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='163' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='164' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='165' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='166' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='167' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='168' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='169' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='302' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='303' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='304' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='305' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='306' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='307' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='308' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='309' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='310' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='311' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='312' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='313' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='314' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='315' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='316' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='317' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='318' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='319' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='320' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='321' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='322' THEN physicsmark END ),0) +
		
		ifnull(max(case when quiz_id ='395' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='396' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='397' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='398' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='399' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='400' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='401' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='402' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='403' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='404' THEN physicsmark END ),0) +
		
		ifnull(max(case when quiz_id ='323' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='324' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='325' THEN physicsmark END ),0) +
	
		ifnull(max(case when quiz_id ='328' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='329' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='330' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='331' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='332' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='333' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='366' THEN physicsmark END ),0)  +
		ifnull(max(case when quiz_id ='381' THEN physicsmark END ),0)  +
		ifnull(max(case when quiz_id ='405' THEN physicsmark END ),0)  +
		ifnull(max(case when quiz_id ='406' THEN physicsmark END ),0) total
		FROM student_quiz_result a , quiz_info b 
		where a.quiz_id = b.id and b.quiztype='NEWJEE' and a.user_id in (select username from subscription_details where package_id ='14')
		GROUP BY user_id ORDER BY physicsmark desc";
				$query = $this->db->query($sql);
				//print_r($sql);
		}
		if ($batch=='JEEBatch2021' && $sub =='chemistry'){
        $sql = "insert into all_jee_rank
		select user_id,
		max(case when quiz_id ='118' THEN chemistrymark END ) 'JEEJUT1',
		max(case when quiz_id ='119' THEN chemistrymark END ) 'JEEJUT2',
		max(case when quiz_id ='120' THEN chemistrymark END ) 'JEEJUT3',
		max(case when quiz_id ='122' THEN chemistrymark END ) 'JEEJUT4',
		max(case when quiz_id ='123' THEN chemistrymark END ) 'JEEJUT5',
		max(case when quiz_id ='124' THEN chemistrymark END ) 'JEEJUT6',
		max(case when quiz_id ='127' THEN chemistrymark END ) 'JEEJUT7',
		max(case when quiz_id ='128' THEN chemistrymark END ) 'JEEJUT8',
		max(case when quiz_id ='129' THEN chemistrymark END ) 'JEEJUT9',
		max(case when quiz_id ='130' THEN chemistrymark END ) 'JEEJUT10',
		max(case when quiz_id ='131' THEN chemistrymark END ) 'JEEJUT11',
		max(case when quiz_id ='132' THEN chemistrymark END ) 'JEEJUT12',
		max(case when quiz_id ='133' THEN chemistrymark END ) 'JEEJUT13',
		max(case when quiz_id ='134' THEN chemistrymark END ) 'JEEJUT14',
		max(case when quiz_id ='136' THEN chemistrymark END ) 'JEEJUT15',
		max(case when quiz_id ='137' THEN chemistrymark END ) 'JEEJUT16',
		max(case when quiz_id ='139' THEN chemistrymark END ) 'JEEJUT17',
		max(case when quiz_id ='141' THEN chemistrymark END ) 'JEEJUT18',
		max(case when quiz_id ='142' THEN chemistrymark END ) 'JEEJUT19',
		max(case when quiz_id ='158' THEN chemistrymark END ) 'JEEJUT20',
		max(case when quiz_id ='160' THEN chemistrymark END ) 'JEEJUT21',
		max(case when quiz_id ='161' THEN chemistrymark END ) 'JEEJUT22',
		max(case when quiz_id ='163' THEN chemistrymark END ) 'JEEJUT23',
		max(case when quiz_id ='164' THEN chemistrymark END ) 'JEEJUT24',
		max(case when quiz_id ='165' THEN chemistrymark END ) 'JEEJUT25',
		max(case when quiz_id ='166' THEN chemistrymark END ) 'JEEJUT26',
		max(case when quiz_id ='167' THEN chemistrymark END ) 'JEEJUT27',
		max(case when quiz_id ='168' THEN chemistrymark END ) 'JEEJUT28',
		max(case when quiz_id ='169' THEN chemistrymark END ) 'JEEJUT29',
		max(case when quiz_id ='302' THEN chemistrymark END ) 'JEEJUT30',
		max(case when quiz_id ='303' THEN chemistrymark END ) 'JEEJUT31',
		max(case when quiz_id ='304' THEN chemistrymark END ) 'JEEJUT32',
		max(case when quiz_id ='305' THEN chemistrymark END ) 'JEEJUT33',
		max(case when quiz_id ='306' THEN chemistrymark END ) 'JEEJUT34',
		max(case when quiz_id ='307' THEN chemistrymark END ) 'JEEJUT35',
		max(case when quiz_id ='308' THEN chemistrymark END ) 'JEEJUT36',
		max(case when quiz_id ='309' THEN chemistrymark END ) 'JEEJUT37',
		max(case when quiz_id ='310' THEN chemistrymark END ) 'JEEJUT38',
		max(case when quiz_id ='311' THEN chemistrymark END ) 'JEEJUT39',
		max(case when quiz_id ='312' THEN chemistrymark END ) 'JEEJUT40',
		max(case when quiz_id ='313' THEN chemistrymark END ) 'JEEJUT41',
		max(case when quiz_id ='314' THEN chemistrymark END ) 'JEEJUT42',
		max(case when quiz_id ='315' THEN chemistrymark END ) 'JEEJUT43',
		max(case when quiz_id ='316' THEN chemistrymark END ) 'JEEJUT44',
		max(case when quiz_id ='317' THEN chemistrymark END ) 'JEEJUT45',
		max(case when quiz_id ='318' THEN chemistrymark END ) 'JEEJUT46',
		max(case when quiz_id ='319' THEN chemistrymark END ) 'JEEJUT47',
		max(case when quiz_id ='320' THEN chemistrymark END ) 'JEEJUT48',
		max(case when quiz_id ='321' THEN chemistrymark END ) 'JEEJUT49',
		max(case when quiz_id ='322' THEN chemistrymark END ) 'JEEJUT50',
		
		max(case when quiz_id ='395' THEN chemistrymark END ) 'JEEJUT51',
		max(case when quiz_id ='396' THEN chemistrymark END ) 'JEEJUT52',
		max(case when quiz_id ='397' THEN chemistrymark END ) 'JEEJUT53',
		max(case when quiz_id ='398' THEN chemistrymark END ) 'JEEJUT54',
		max(case when quiz_id ='399' THEN chemistrymark END ) 'JEEJUT55',
		max(case when quiz_id ='400' THEN chemistrymark END ) 'JEEJUT56',
		max(case when quiz_id ='401' THEN chemistrymark END ) 'JEEJUT57',
		max(case when quiz_id ='402' THEN chemistrymark END ) 'JEEJUT58',
		max(case when quiz_id ='403' THEN chemistrymark END ) 'JEEJUT59',
		max(case when quiz_id ='404' THEN chemistrymark END ) 'JEEJUT60',
		
		max(case when quiz_id ='323' THEN chemistrymark END ) 'JEE FULL LENGTH 01',
		max(case when quiz_id ='324' THEN chemistrymark END ) 'JEE FULL LENGTH 02',
		max(case when quiz_id ='325' THEN chemistrymark END ) 'JEE FULL LENGTH 03',
		max(case when quiz_id ='328' THEN chemistrymark END ) 'JEE FULL LENGTH 04',
		max(case when quiz_id ='329' THEN chemistrymark END ) 'JEE FULL LENGTH 05',
		max(case when quiz_id ='330' THEN chemistrymark END ) 'JEE FULL LENGTH 06',
		max(case when quiz_id ='331' THEN chemistrymark END ) 'JEE FULL LENGTH 07',
		max(case when quiz_id ='332' THEN chemistrymark END ) 'JEE FULL LENGTH 08',
		max(case when quiz_id ='333' THEN chemistrymark END ) 'JEE FULL LENGTH 09',
		max(case when quiz_id ='335' THEN chemistrymark END ) 'JEE FULL LENGTH 10',
		max(case when quiz_id ='366' THEN chemistrymark END ) 'JEE FULL LENGTH 11',
		max(case when quiz_id ='381' THEN chemistrymark END ) 'JEE FULL LENGTH 12',
		max(case when quiz_id ='405' THEN chemistrymark END ) 'JEE FULL LENGTH 13',
		max(case when quiz_id ='406' THEN chemistrymark END ) 'JEE FULL LENGTH 14',
		max(case when quiz_id ='406' THEN chemistrymark END ) 'JEE FULL LENGTH 15',

		ifnull(max(case when quiz_id ='118' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='119' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='120' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='122' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='123' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='124' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='127' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='128' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='129' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='130' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='131' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='132' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='133' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='134' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='136' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='137' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='139' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='141' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='142' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='158' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='160' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='161' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='163' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='164' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='165' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='166' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='167' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='168' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='169' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='302' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='303' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='304' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='305' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='306' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='307' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='308' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='309' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='310' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='311' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='312' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='313' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='314' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='315' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='316' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='317' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='318' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='319' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='320' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='321' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='322' THEN chemistrymark END ),0) +
		
		ifnull(max(case when quiz_id ='395' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='396' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='397' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='398' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='399' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='400' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='401' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='402' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='403' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='404' THEN chemistrymark END ),0) +
		
		ifnull(max(case when quiz_id ='323' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='324' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='325' THEN chemistrymark END ),0) +
	
		ifnull(max(case when quiz_id ='328' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='329' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='330' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='331' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='332' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='333' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='366' THEN chemistrymark END ),0)  +
		ifnull(max(case when quiz_id ='381' THEN chemistrymark END ),0)  +
		ifnull(max(case when quiz_id ='405' THEN chemistrymark END ),0)  +
		ifnull(max(case when quiz_id ='406' THEN chemistrymark END ),0) total
		FROM student_quiz_result a , quiz_info b 
		where a.quiz_id = b.id and b.quiztype='NEWJEE' and a.user_id in (select username from subscription_details where package_id ='14')
		GROUP BY user_id";
				$query = $this->db->query($sql);
				//print_r($sql);
		}
		if ($batch=='JEEBatch2021' && $sub =='maths'){
        $sql = "insert into  all_jee_rank
		select user_id,
		max(case when quiz_id ='118' THEN biologymark END ) 'JEEJUT1',
		max(case when quiz_id ='119' THEN biologymark END ) 'JEEJUT2',
		max(case when quiz_id ='120' THEN biologymark END ) 'JEEJUT3',
		max(case when quiz_id ='122' THEN biologymark END ) 'JEEJUT4',
		max(case when quiz_id ='123' THEN biologymark END ) 'JEEJUT5',
		max(case when quiz_id ='124' THEN biologymark END ) 'JEEJUT6',
		max(case when quiz_id ='127' THEN biologymark END ) 'JEEJUT7',
		max(case when quiz_id ='128' THEN biologymark END ) 'JEEJUT8',
		max(case when quiz_id ='129' THEN biologymark END ) 'JEEJUT9',
		max(case when quiz_id ='130' THEN biologymark END ) 'JEEJUT10',
		max(case when quiz_id ='131' THEN biologymark END ) 'JEEJUT11',
		max(case when quiz_id ='132' THEN biologymark END ) 'JEEJUT12',
		max(case when quiz_id ='133' THEN biologymark END ) 'JEEJUT13',
		max(case when quiz_id ='134' THEN biologymark END ) 'JEEJUT14',
		max(case when quiz_id ='136' THEN biologymark END ) 'JEEJUT15',
		max(case when quiz_id ='137' THEN biologymark END ) 'JEEJUT16',
		max(case when quiz_id ='139' THEN biologymark END ) 'JEEJUT17',
		max(case when quiz_id ='141' THEN biologymark END ) 'JEEJUT18',
		max(case when quiz_id ='142' THEN biologymark END ) 'JEEJUT19',
		max(case when quiz_id ='158' THEN biologymark END ) 'JEEJUT20',
		max(case when quiz_id ='160' THEN biologymark END ) 'JEEJUT21',
		max(case when quiz_id ='161' THEN biologymark END ) 'JEEJUT22',
		max(case when quiz_id ='163' THEN biologymark END ) 'JEEJUT23',
		max(case when quiz_id ='164' THEN biologymark END ) 'JEEJUT24',
		max(case when quiz_id ='165' THEN biologymark END ) 'JEEJUT25',
		max(case when quiz_id ='166' THEN biologymark END ) 'JEEJUT26',
		max(case when quiz_id ='167' THEN biologymark END ) 'JEEJUT27',
		max(case when quiz_id ='168' THEN biologymark END ) 'JEEJUT28',
		max(case when quiz_id ='169' THEN biologymark END ) 'JEEJUT29',
		max(case when quiz_id ='302' THEN biologymark END ) 'JEEJUT30',
		max(case when quiz_id ='303' THEN biologymark END ) 'JEEJUT31',
		max(case when quiz_id ='304' THEN biologymark END ) 'JEEJUT32',
		max(case when quiz_id ='305' THEN biologymark END ) 'JEEJUT33',
		max(case when quiz_id ='306' THEN biologymark END ) 'JEEJUT34',
		max(case when quiz_id ='307' THEN biologymark END ) 'JEEJUT35',
		max(case when quiz_id ='308' THEN biologymark END ) 'JEEJUT36',
		max(case when quiz_id ='309' THEN biologymark END ) 'JEEJUT37',
		max(case when quiz_id ='310' THEN biologymark END ) 'JEEJUT38',
		max(case when quiz_id ='311' THEN biologymark END ) 'JEEJUT39',
		max(case when quiz_id ='312' THEN biologymark END ) 'JEEJUT40',
		max(case when quiz_id ='313' THEN biologymark END ) 'JEEJUT41',
		max(case when quiz_id ='314' THEN biologymark END ) 'JEEJUT42',
		max(case when quiz_id ='315' THEN biologymark END ) 'JEEJUT43',
		max(case when quiz_id ='316' THEN biologymark END ) 'JEEJUT44',
		max(case when quiz_id ='317' THEN biologymark END ) 'JEEJUT45',
		max(case when quiz_id ='318' THEN biologymark END ) 'JEEJUT46',
		max(case when quiz_id ='319' THEN biologymark END ) 'JEEJUT47',
		max(case when quiz_id ='320' THEN biologymark END ) 'JEEJUT48',
		max(case when quiz_id ='321' THEN biologymark END ) 'JEEJUT49',
		max(case when quiz_id ='322' THEN biologymark END ) 'JEEJUT50',
		
		max(case when quiz_id ='395' THEN biologymark END ) 'JEEJUT51',
		max(case when quiz_id ='396' THEN biologymark END ) 'JEEJUT52',
		max(case when quiz_id ='397' THEN biologymark END ) 'JEEJUT53',
		max(case when quiz_id ='398' THEN biologymark END ) 'JEEJUT54',
		max(case when quiz_id ='399' THEN biologymark END ) 'JEEJUT55',
		max(case when quiz_id ='400' THEN biologymark END ) 'JEEJUT56',
		max(case when quiz_id ='401' THEN biologymark END ) 'JEEJUT57',
		max(case when quiz_id ='402' THEN biologymark END ) 'JEEJUT58',
		max(case when quiz_id ='403' THEN biologymark END ) 'JEEJUT59',
		max(case when quiz_id ='404' THEN biologymark END ) 'JEEJUT60',
		
		max(case when quiz_id ='323' THEN biologymark END ) 'JEE FULL LENGTH 01',
		max(case when quiz_id ='324' THEN biologymark END ) 'JEE FULL LENGTH 02',
		max(case when quiz_id ='325' THEN biologymark END ) 'JEE FULL LENGTH 03',
		max(case when quiz_id ='328' THEN biologymark END ) 'JEE FULL LENGTH 04',
		max(case when quiz_id ='329' THEN biologymark END ) 'JEE FULL LENGTH 05',
		max(case when quiz_id ='330' THEN biologymark END ) 'JEE FULL LENGTH 06',
		max(case when quiz_id ='331' THEN biologymark END ) 'JEE FULL LENGTH 07',
		max(case when quiz_id ='332' THEN biologymark END ) 'JEE FULL LENGTH 08',
		max(case when quiz_id ='333' THEN biologymark END ) 'JEE FULL LENGTH 09',
		max(case when quiz_id ='335' THEN biologymark END ) 'JEE FULL LENGTH 10',
		max(case when quiz_id ='366' THEN biologymark END ) 'JEE FULL LENGTH 11',
		max(case when quiz_id ='381' THEN biologymark END ) 'JEE FULL LENGTH 12',
		max(case when quiz_id ='405' THEN biologymark END ) 'JEE FULL LENGTH 13',
		max(case when quiz_id ='406' THEN biologymark END ) 'JEE FULL LENGTH 14',
		max(case when quiz_id ='406' THEN biologymark END ) 'JEE FULL LENGTH 15',

		ifnull(max(case when quiz_id ='118' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='119' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='120' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='122' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='123' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='124' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='127' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='128' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='129' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='130' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='131' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='132' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='133' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='134' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='136' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='137' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='139' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='141' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='142' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='158' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='160' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='161' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='163' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='164' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='165' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='166' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='167' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='168' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='169' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='302' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='303' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='304' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='305' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='306' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='307' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='308' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='309' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='310' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='311' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='312' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='313' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='314' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='315' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='316' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='317' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='318' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='319' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='320' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='321' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='322' THEN biologymark END ),0) +
		
		ifnull(max(case when quiz_id ='395' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='396' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='397' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='398' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='399' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='400' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='401' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='402' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='403' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='404' THEN biologymark END ),0) +
		
		ifnull(max(case when quiz_id ='323' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='324' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='325' THEN biologymark END ),0) +
	
		ifnull(max(case when quiz_id ='328' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='329' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='330' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='331' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='332' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='333' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='366' THEN biologymark END ),0)  +
		ifnull(max(case when quiz_id ='381' THEN biologymark END ),0)  +
		ifnull(max(case when quiz_id ='405' THEN biologymark END ),0)  +
		ifnull(max(case when quiz_id ='406' THEN biologymark END ),0) total
		FROM student_quiz_result a , quiz_info b 
		where a.quiz_id = b.id and b.quiztype='NEWJEE' and a.user_id in (select username from subscription_details where package_id ='14')
		GROUP BY user_id";
				$query = $this->db->query($sql);
				
		}
		//print_r($sql);
		$sql = "truncate table consol_jee_rank";
        $query = $this->db->query($sql);
        $sql = "SET @prev_value = NULL";
        $query = $this->db->query($sql);
        $sql = "SET @rank_count = 0";
        $query = $this->db->query($sql);
        $sql = "truncate table consol_jee_rank";
        $query = $this->db->query($sql);
        $sql = "insert into  consol_jee_rank
		select *, CASE
		WHEN @prev_value = total THEN @rank_count
		WHEN @prev_value := total THEN @rank_count := @rank_count + 1

		END AS rank
		FROM all_jee_rank

		ORDER BY total desc";

        $query = $this->db->query($sql);

        $sql = "SELECT a.* ,concat(b.first_name,' ',b.last_name) name,b.batch,b.rollno,b.accommodation from consol_jee_rank a,user_details b  where a.user_id collate utf8_general_ci = b.user_name collate utf8_general_ci
			 ORDER BY CONVERT( a.rank, SIGNED INTEGER )";
        //    print_r($sql);
        $query = $this->db->query($sql);

        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return $query->result_array();
        }
		
	}
	
	/*public function getjeerank($cat,$batch)
    {

        $this->load->library('nativesession');

        $sql = "truncate table quiz_rank";
        $query = $this->db->query($sql);
        $sql = "SET @prev_value = NULL";
        $query = $this->db->query($sql);
        $sql = "SET @rank_count = 0";
        $query = $this->db->query($sql);
		
		if ($batch=='all'){

        $sql = "INSERT into  quiz_rank
				select user_id,quiz_id ,physicsmark+chemistrymark+biologymark, CASE
		WHEN @prev_value = physicsmark+chemistrymark+biologymark THEN @rank_count
		WHEN @prev_value := physicsmark+chemistrymark+biologymark THEN @rank_count := @rank_count + 1

		END AS rank
		FROM student_quiz_result where quiz_id ='$cat'
		ORDER BY physicsmark+chemistrymark+biologymark desc";
		
		}
		 if ($batch=='PackageId2'){

        $sql = "INSERT into  quiz_rank
				select user_id,quiz_id ,physicsmark+chemistrymark+biologymark, CASE
		WHEN @prev_value = physicsmark+chemistrymark+biologymark THEN @rank_count
		WHEN @prev_value := physicsmark+chemistrymark+biologymark THEN @rank_count := @rank_count + 1

		END AS rank
		FROM student_quiz_result where quiz_id ='$cat' and user_id  in
        (select username  from subscription_details 
        where package_id = 2)
		ORDER BY physicsmark+chemistrymark+biologymark desc";
		
		}
		 if ($batch=='PackageId14'){

        $sql = "INSERT into  quiz_rank
				select user_id,quiz_id ,physicsmark+chemistrymark+biologymark, CASE
		WHEN @prev_value = physicsmark+chemistrymark+biologymark THEN @rank_count
		WHEN @prev_value := physicsmark+chemistrymark+biologymark THEN @rank_count := @rank_count + 1

		END AS rank
		FROM student_quiz_result where quiz_id ='$cat' and user_id  in
        (select username  from subscription_details 
        where package_id = 14)
		ORDER BY physicsmark+chemistrymark+biologymark desc";
		
		}
		
		 if ($batch=='21'){

        $sql = "INSERT into  quiz_rank
				select user_id,quiz_id ,physicsmark+chemistrymark+biologymark, CASE
		WHEN @prev_value = physicsmark+chemistrymark+biologymark THEN @rank_count
		WHEN @prev_value := physicsmark+chemistrymark+biologymark THEN @rank_count := @rank_count + 1

		END AS rank
		FROM student_quiz_result where quiz_id ='$cat' and user_id  in
        (select username  from subscription_details 
        where package_id = 21)
		ORDER BY physicsmark+chemistrymark+biologymark desc";
		
		}
		
		if ($batch=='PackageId27'){

        $sql = "INSERT into  quiz_rank
				select user_id,quiz_id ,physicsmark+chemistrymark+biologymark, CASE
		WHEN @prev_value = physicsmark+chemistrymark+biologymark THEN @rank_count
		WHEN @prev_value := physicsmark+chemistrymark+biologymark THEN @rank_count := @rank_count + 1

		END AS rank
		FROM student_quiz_result where quiz_id ='$cat' and user_id  in
        (select username  from subscription_details 
        where package_id = 27)
		ORDER BY physicsmark+chemistrymark+biologymark desc";
		
		}
if ($batch=='PackageId44'){

        $sql = "INSERT into  quiz_rank
				select user_id,quiz_id ,physicsmark+chemistrymark+biologymark, CASE
		WHEN @prev_value = physicsmark+chemistrymark+biologymark THEN @rank_count
		WHEN @prev_value := physicsmark+chemistrymark+biologymark THEN @rank_count := @rank_count + 1

		END AS rank
		FROM student_quiz_result where quiz_id ='$cat' and user_id  in
        (select username  from subscription_details 
        where package_id = 44)
		ORDER BY physicsmark+chemistrymark+biologymark desc";
		
		}
		if ($batch=='PackageId45'){

        $sql = "INSERT into  quiz_rank
				select user_id,quiz_id ,physicsmark+chemistrymark+biologymark, CASE
		WHEN @prev_value = physicsmark+chemistrymark+biologymark THEN @rank_count
		WHEN @prev_value := physicsmark+chemistrymark+biologymark THEN @rank_count := @rank_count + 1

		END AS rank
		FROM student_quiz_result where quiz_id ='$cat' and user_id  in
        (select username  from subscription_details 
        where package_id = 45)
		ORDER BY physicsmark+chemistrymark+biologymark desc";
		
		}
        
			 if ($batch=='27'){

        $sql = "INSERT into  quiz_rank
				select user_id,quiz_id ,physicsmark+chemistrymark+biologymark, CASE
		WHEN @prev_value = physicsmark+chemistrymark+biologymark THEN @rank_count
		WHEN @prev_value := physicsmark+chemistrymark+biologymark THEN @rank_count := @rank_count + 1

		END AS rank
		FROM student_quiz_result where quiz_id ='$cat' and user_id  in
        (select username  from subscription_details 
        where package_id = 27)
		ORDER BY physicsmark+chemistrymark+biologymark desc";
		
		}
        $query = $this->db->query($sql);

        $sql = "update student_quiz_result a inner join quiz_rank b
		on a.quiz_id = b.quiz_id
		set a.rank= b.rank
		where a.user_id = b.user_id
		and a.quiz_id = '$cat'";
        //    print_r($sql);
        $query = $this->db->query($sql);
	if ($batch=='all'){
				$sql = "SELECT a.*,(a.physicsmark+a.chemistrymark+a.biologymark) total ,concat(b.first_name,' ',b.last_name) name,b.rollno,b.batch,b.accommodation,b.no_of_communication from student_quiz_result a,user_details b 
				where a.user_id collate utf8_general_ci = b.user_name collate utf8_general_ci and   a.quiz_id ='$cat'
					 ORDER BY CONVERT( a.rank, SIGNED INTEGER )";
		}
		
		if ($batch=='PackageId2'){
				$sql = "SELECT a.*,(a.physicsmark+a.chemistrymark+a.biologymark) total ,concat(b.first_name,' ',b.last_name) name, c.package_id,b.rollno,b.batch,b.accommodation,b.no_of_communication
						from student_quiz_result a,user_details b,subscription_details c
						where a.user_id collate utf8_general_ci = b.user_name collate utf8_general_ci
						and c.username  = a.user_id and a.quiz_id ='$cat' and c.package_id='2'
					 ORDER BY CONVERT( a.rank, SIGNED INTEGER )";
		}
			if ($batch=='PackageId14'){
				$sql = "SELECT a.*,(a.physicsmark+a.chemistrymark+a.biologymark) total ,concat(b.first_name,' ',b.last_name) name, c.package_id,b.rollno,b.batch,b.accommodation,b.no_of_communication
						from student_quiz_result a,user_details b,subscription_details c
						where a.user_id collate utf8_general_ci = b.user_name collate utf8_general_ci
						and c.username  = a.user_id and a.quiz_id ='$cat' and c.package_id='14'
					 ORDER BY CONVERT( a.rank, SIGNED INTEGER )";
		}
		if ($batch=='21'){
				$sql = "SELECT a.*,(a.physicsmark+a.chemistrymark+a.biologymark) total ,concat(b.first_name,' ',b.last_name) name, c.package_id,b.rollno,b.batch,b.accommodation,b.no_of_communication
						from student_quiz_result a,user_details b,subscription_details c
						where a.user_id collate utf8_general_ci = b.user_name collate utf8_general_ci
						and c.username  = a.user_id and a.quiz_id ='$cat' and c.package_id='21'
					 ORDER BY CONVERT( a.rank, SIGNED INTEGER )";
		}
		if ($batch=='27'){
				$sql = "SELECT a.*,(a.physicsmark+a.chemistrymark+a.biologymark) total ,concat(b.first_name,' ',b.last_name) name, c.package_id,b.rollno,b.batch,b.accommodation,b.no_of_communication
						from student_quiz_result a,user_details b,subscription_details c
						where a.user_id collate utf8_general_ci = b.user_name collate utf8_general_ci
						and c.username  = a.user_id and a.quiz_id ='$cat' and c.package_id='27'
					 ORDER BY CONVERT( a.rank, SIGNED INTEGER )";
		}
if ($batch=='PackageId44'){
				$sql = "SELECT a.*,(a.physicsmark+a.chemistrymark+a.biologymark) total ,concat(b.first_name,' ',b.last_name) name, c.package_id,b.rollno,b.batch,b.accommodation,b.no_of_communication
						from student_quiz_result a,user_details b,subscription_details c
						where a.user_id collate utf8_general_ci = b.user_name collate utf8_general_ci
						and c.username  = a.user_id and a.quiz_id ='$cat' and c.package_id='44'
					 ORDER BY CONVERT( a.rank, SIGNED INTEGER )";
		}
		if ($batch=='PackageId45'){
				$sql = "SELECT a.*,(a.physicsmark+a.chemistrymark+a.biologymark) total ,concat(b.first_name,' ',b.last_name) name, c.package_id,b.rollno,b.batch,b.accommodation,b.no_of_communication
						from student_quiz_result a,user_details b,subscription_details c
						where a.user_id collate utf8_general_ci = b.user_name collate utf8_general_ci
						and c.username  = a.user_id and a.quiz_id ='$cat' and c.package_id='45'
					 ORDER BY CONVERT( a.rank, SIGNED INTEGER )";
		}
        //    print_r($sql);
        $query = $this->db->query($sql);
        try {
            if ($query->num_rows() > 0) {
                return $query->result_array();
            } else {
                return $query->result_array();
            }

        } catch (exception $e) {echo $e->getMessage();
        }

    }*/
	
	
		public function getsubjectmathresult($cat)
		{
			$db  = get_db();
			$sql = "SELECT
					a.user_id,
					b.user_id AS db_user_id,
					a.quiz_id,
					CONCAT(b.first_name,' ',b.last_name) AS name,
					b.phone,
					b.batch,
					b.rollno,
					rd.role_name,
					a.physicsattempted  AS attempted,
					a.physicscorrect    AS correct,
					a.physicswrong      AS wrong,
					a.physicsmark       AS mark,
					(SELECT Quiz_name FROM quiz_info WHERE id='" . $db->escape($cat) . "') AS Quiz_name
				FROM student_quiz_result a
				INNER JOIN user_details b
					ON a.user_id COLLATE utf8_general_ci = b.user_name COLLATE utf8_general_ci
				LEFT JOIN role_details rd ON rd.role_id = b.role_id
				WHERE a.quiz_id = '" . $db->escape($cat) . "'
				ORDER BY a.physicsmark DESC, a.physicscorrect DESC";
			$query = $db->query($sql);
			if (!$query) return array();
			$rows = $db->get_result($query);
			// Add sequential rank since no rank column is written back for subject-math quizzes
			foreach ($rows as $i => $r) {
				$rows[$i]['rank'] = $i + 1;
			}
			return $rows;
		}

		public function getjeerank($cat, $batch)
		{
			$db = get_db();

			$db->query("DROP TEMPORARY TABLE IF EXISTS temp_ranks");
			$db->query("CREATE TEMPORARY TABLE temp_ranks (
				id          INT AUTO_INCREMENT,
				user_id     VARCHAR(255),
				quiz_id     VARCHAR(255),
				total_marks INT,
				biologymark INT,
				full_name   VARCHAR(255),
				row_rank    INT,
				PRIMARY KEY (id)
			) ENGINE=MEMORY");

			$db->query("INSERT INTO temp_ranks (user_id, quiz_id, total_marks, biologymark, full_name, row_rank)
				SELECT
					a.user_id,
					a.quiz_id,
					(COALESCE(a.physicsmark,0)+COALESCE(a.chemistrymark,0)+COALESCE(a.biologymark,0)) AS total_marks,
					COALESCE(a.biologymark,0) AS biologymark,
					CONCAT(COALESCE(b.first_name,''),' ',COALESCE(b.last_name,'')) AS full_name,
					0
				FROM student_quiz_result a
				INNER JOIN user_details b
					ON a.user_id COLLATE utf8_general_ci = b.user_name COLLATE utf8_general_ci
				WHERE a.quiz_id = '" . $db->escape($cat) . "'
				ORDER BY
					(COALESCE(a.physicsmark,0)+COALESCE(a.chemistrymark,0)+COALESCE(a.biologymark,0)) DESC,
					COALESCE(a.biologymark,0) DESC,
					CONCAT(COALESCE(b.first_name,''),' ',COALESCE(b.last_name,'')) ASC");

			$db->query("UPDATE student_quiz_result a
				INNER JOIN temp_ranks b ON a.user_id = b.user_id AND a.quiz_id = b.quiz_id
				SET a.rank = b.id
				WHERE a.quiz_id = '" . $db->escape($cat) . "'");

			$db->query("DROP TEMPORARY TABLE IF EXISTS temp_ranks");

			$sql = "SELECT a.*,
					(a.physicsmark+a.chemistrymark+a.biologymark) total,
					CONCAT(b.first_name,' ',b.last_name) name,
					b.rollno, b.batch, b.accommodation, b.phone,
					(SELECT Quiz_name FROM quiz_info WHERE id='" . $db->escape($cat) . "') AS Quiz_name
				FROM student_quiz_result a, user_details b
				WHERE a.user_id COLLATE utf8_general_ci = b.user_name COLLATE utf8_general_ci
				  AND a.quiz_id = '" . $db->escape($cat) . "'
				ORDER BY CONVERT(a.rank, SIGNED INTEGER)";

			$query = $db->query($sql);
			return $query ? $db->get_result($query) : array();
		}
	
	
	/*public function getsubmathrank($quiz_id){
			 $sql="select concat(b.first_name,' ',b.last_name) name,a.user_id,quiz_id,no_of_communication,accommodation,
rollno,batch,physicsattempted attempted ,
physicscorrect correct , physicswrong wrong, physicsmark mark from student_quiz_result a,user_details b 
where a.user_id collate utf8_general_ci = b.user_name collate utf8_general_ci and   a.quiz_id ='$quiz_id'";
//print_r($sql);
   $query = $this->db->query($sql);
        try {
            if ($query->num_rows() > 0) {
                return $query->result_array();
            } else {
                return $query->result_array();
            }

        } catch (exception $e) {echo $e->getMessage();
        }
	}*/
	public function getsubmathrank($quiz_id)
{
    $sql = "SELECT CONCAT(b.first_name,' ',b.last_name) AS name, a.user_id, quiz_id, no_of_communication, accommodation,
            rollno, batch, physicsattempted AS attempted,
            physicscorrect AS correct, physicswrong AS wrong, physicsmark AS mark
            FROM student_quiz_result a
            JOIN user_details b ON a.user_id COLLATE utf8_general_ci = b.user_name COLLATE utf8_general_ci
            WHERE a.quiz_id = ?";
    
    try {
        $query = db_query($sql, array($quiz_id));
        return $query->result_array();  // returns empty array if no rows
    } catch (Exception $e) {
        echo $e->getMessage();
        return array();
    }
}

/*	public function getpackagename(){
		$sql="Select distinct id,package_name FROM package_info;";
		$query = $this->db->query($sql);

		if ($query->num_rows() == 1)
		{
			return $query->result_array();
		}
		elseif ($query->num_rows() > 1)
		{
			return $query->result_array(); //This returns an array of results which you can whatever you need with it
		}
		else 
		{
			return $query->result_array();
		}
	}*/
	public function getpackagename()
{
    $sql = "SELECT DISTINCT id, package_name FROM package_info";
    $query = db_query($sql);

   
    if ($query->num_rows() > 0) {
        return $query->result_array();
    } else {
        return []; // return empty array explicitly if no rows
    }
}

	public function get_maxlevelquiz()
    {
        $this->load->library('nativesession');
        $max = $this->input->post('cat');
        $arr = explode(",", $max);
        $level = "";
        $cnt_que = "";
        $limit = "";
        $this->nativesession->set('category', $max);
        $unit = "";
        $sql = "SELECT category FROM `quiz_category` WHERE id in($max)";
        $query = $this->db->query($sql);
        foreach ($query->result_array() as $row) {
            if ($unit == "") {
                $unit = $row['category'];
            } else {
                $unit = $unit . ",,~,," . $row['category'];
            }

        }

        //print_R($arr);
        for ($i = 0; $i < count($arr); $i++) {
            $sql = "SELECT max(level) max FROM `quiz_question` WHERE category='$arr[$i]'";
            $query = $this->db->query($sql);
            if ($query->num_rows() > 0) {
                foreach ($query->result_array() as $row) {
                    if ($level == "") {
                        $level = $row['max'];
                    } else {
                        $level = $level . "," . $row['max'];
                    }

                    $limit = $row['max'];
                }
            } else {
            }
            //  echo "limit=".$limit;
            for ($j = 1; $j <= $limit; $j++) {
                $sql = "SELECT count(question_name) que FROM `quiz_question` WHERE category='$arr[$i]' and level='$j'";
                $query = $this->db->query($sql);
                foreach ($query->result_array() as $row) {
                    if ($cnt_que == "") {
                        $cnt_que = $row['que'];
                    } else {
                        $cnt_que = $cnt_que . "," . $row['que'];
                    }

                }
            }
        }
        $this->load->library('nativesession');
        $this->nativesession->set('t_level', $level);
        $this->nativesession->set('t_cnt_que', $cnt_que);
        $this->nativesession->set('t_unit', $unit);
		//echo "unit=".$unit;
        //echo "level=".$level;
        //echo "no of questions=".$cnt_que;

    }
    public function save_dynamic_quiz()
    {
        $this->load->library('nativesession');
        $qname = $this->input->post('qname');
        $tlimit = $this->input->post('tlimit');
        $tlimit1 = $this->input->post('tlimit1');
        $tlimit = $tlimit . ":" . $tlimit1;
        $sub_count = $this->nativesession->get('subject_name');
        $cat = $this->nativesession->get('category');
        $t_level = $this->nativesession->get('t_level');
        $len = explode(",", $cat);
        $t_a1 = explode(",", $this->nativesession->get('t_cnt_que'));
        $a1 = count($t_a1);
        $b1 = "";
        $tot = 0;
        //echo $a1;
        for ($i = 0; $i < $a1; $i++) {
            $tot += $this->input->post('levl' . $i);
            if ($i == 0) {
                $b1 = "" . $this->input->post('levl' . $i);
            } else {
                $b1 = $b1 . "," . $this->input->post('levl' . $i);
            }

        }
        $sql = "INSERT INTO `quiz_dynamic`(`id`, `quiz_name`, `time_limit`, `subject_name`, `category_name`, `difficulty_level`, `levels`,`noofquestions`)
				VALUES ('','$qname','$tlimit','$sub_count','$cat','$b1','$t_level','$tot')";
        $query = $this->db->query($sql);
    }
	public function get_max_dynamicquiz()
    {
        $sql = "SELECT * FROM `quiz_dynamic` where id=(select max(id) FROM `quiz_dynamic`)";
        $query = $this->db->query($sql);
        $dynamic = $query->result_array();
        return $dynamic;
    }
	/*public function get_allquedynamic_quiz_result($category, $leve1, $noofque)
    {
        $arr = '';
        $k = 0;
        $sql1 = "SELECT id FROM `quiz_question` WHERE level='$leve1' and category='$category'";
        $query = $this->db->query($sql1);
        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $rs = $row['id'];
                if ($k == 0) {
                    $arr = $rs;
                } else {
                    $arr = $arr . "," . $rs;
                }

                $k = 1;

            }
        }
        //echo $arr;
        $a = explode(",", $arr);
        // print_r($a);
        $random_keys = array_rand($a, $noofque);
        $final = '';
        // print_r($random_keys);
        if (is_array($random_keys)) {
            for ($i = 0; $i < $noofque; $i++) {
                $final[] = $a[$random_keys[$i]];
            }

        } else {
            $final[] = $a[$random_keys];
        }
        $data1 = "";
        $k = 0;
        for ($i = 0; $i < $noofque; $i++) {
            $sql1 = "SELECT a.id, a.question_name,a.discription,b.question_answer
        FROM quiz_question a,quiz_question_answers b
        WHERE a.id = b.Qustion_no and a.id='$final[$i]'
		and b.fraction=(SELECT max(`fraction`) FROM `quiz_question_answers` WHERE Qustion_no='$final[$i]')";

            $qurey1 = $this->db->query($sql1);
            $result = $qurey1->result_array();
            $sql = "SELECT a.id, a.question_name,a.discription, b.question_answer
        FROM quiz_question a,quiz_question_answers b
        WHERE a.id = b.Qustion_no and a.id='$final[$i]'";
            $qurey2 = $this->db->query($sql);
            $res = $qurey2->result_array();
            $ans_t = "A";

            $i_m = -1;
            for ($j = 0; $j < count($result); $j++) {
                if ($k > 0) {
                    $data1 = $data1 . ",,,";
                }

                $k = 1;
                $data1 = $data1 . "" . $result[$j]['id'];
                $data1 = $data1 . "~,~,~" . $result[$j]['question_name'];
                $data1 = $data1 . "~,~,~" . $result[$j]['question_answer'];
                $data1 = $data1 . "~,~,~" . $result[$j]['question_answer'];
                $data1 = $data1 . "~,~,~" . $result[$j]['question_answer'];
                $data1 = $data1 . "~,~,~" . $result[$j]['question_answer'];
                $ans_t = "";
                switch ($result[$j]['question_answer']) {
                    case $res[$i_m + 1]['question_answer']:$ans_t = "A";
                        break;
                    case $res[$i_m + 2]['question_answer']:$ans_t = "B";
                        break;
                    case $res[$i_m + 3]['question_answer']:$ans_t = "C";
                        break;
                    case $res[$i_m + 4]['question_answer']:$ans_t = "D";
                        break;
                }
                $i_m = $i_m + 4;
                $data1 = $data1 . "~,~,~" . $result[$j]['discription'];
                $data1 = $data1 . "~,~,~" . $ans_t;

            }

        }
        return $data1;
    }*/
	public function get_allquedynamic_quiz($category, $leve1, $noofque)
    {
        $arr = '';
        $k = 0;
        $sql1 = "SELECT id FROM `quiz_question` WHERE level='$leve1' and category='$category'";
        $query = $this->db->query($sql1);
        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $rs = $row['id'];
                if ($k == 0) {
                    $arr = $rs;
                } else {
                    $arr = $arr . "," . $rs;
                }

                $k = 1;

            }
        }
        //echo $arr;
        $a = explode(",", $arr);
        // print_r($a);
        $random_keys = array_rand($a, $noofque);
        $final = '';
        // print_r($random_keys);
        if (is_array($random_keys)) {
            for ($i = 0; $i < $noofque; $i++) {
                $final[] = $a[$random_keys[$i]];
            }

        } else {
            $final[] = $a[$random_keys];
        }
        $data1 = "";
        $k = 0;
        for ($i = 0; $i < $noofque; $i++) {
            $sql1 = "SELECT a.id, a.question_name, b.question_answer
        FROM quiz_question a,quiz_question_answers b
        WHERE a.id = b.Qustion_no and a.id='$final[$i]'";

            $qurey1 = $this->db->query($sql1);
            $result = $qurey1->result_array();

            for ($j = 0; $j < count($result); $j++) {
                if ($k > 0) {
                    $data1 = $data1 . ",,,";
                }

                $k = 1;
                $data1 = $data1 . "" . $result[$j]['id'];
                $data1 = $data1 . "~,~,~" . $result[$j]['question_name'];
                $data1 = $data1 . "~,~,~" . $result[$j++]['question_answer'];
                $data1 = $data1 . "~,~,~" . $result[$j++]['question_answer'];
                $data1 = $data1 . "~,~,~" . $result[$j++]['question_answer'];
                $data1 = $data1 . "~,~,~" . $result[$j]['question_answer'];
            }

        }
        return $data1;
    }
    public function save_static_quiz_mixed($qname, $tlimit, $subject_name, $nque, $seque,$quiz_type)
    {
        $jk = 1;
        $sql = "INSERT INTO `quiz_info`(`id`,`Quiz_name`,`subject_name`,`Time_limit`, `No_of_question`, `selected_Qustion`,quiztype)
				VALUES ('','$qname','$subject_name','$tlimit','$nque','$seque','$quiz_type')";
				//print_r($sql);
				//die;
        $query = $this->db->query($sql);
        $sql = "INSERT INTO `quiz_mixed`(`id`,`Quiz_name`,`subject_name`,`Time_limit`, `No_of_question`, `selected_Qustion`)
				VALUES ('$jk','$qname','$subject_name','$tlimit','$nque','$seque')";
        $query = $this->db->query($sql);
        $sql = "SELECT * FROM `quiz_info` WHERE id=(select max(id) from quiz_info)";
        $query = $this->db->query($sql);
        $static = $query->result_array();
        $id = explode(",", $static[0]['selected_Qustion']);
        $query1 = "";
        $k = 0;
        $data1 = "";
        $tid = $id;
		//print_r($id);
        if (is_array($id)) {
            for ($x1 = 1; $x1 < 5; $x1++) {
                $id = $tid;
                $jk++;
                $seque = "";
                $keys = array_keys($id);
                shuffle($keys);
                $random = array();
                foreach ($keys as $key) {
                    $random[$key] = $id[$key];
                }

                $i = 0;
                foreach ($keys as $key) {
                    $id[$i] = $random[$key];
                    $i++;
                }
                for ($m = 0; $m < count($id); $m++) {
                    if ($m == 0) {
                        $seque = $id[$m];
                    } else {
                        $seque = $seque . "," . $id[$m];
                    }

                }
                $random = "";
                $keys = "";
                $sql = "INSERT INTO `quiz_mixed`(`id`,`Quiz_name`,`subject_name`,`Time_limit`, `No_of_question`, `selected_Qustion`)
						VALUES ('$jk','$qname','$subject_name','$tlimit','$nque','$seque')";
                $query = $this->db->query($sql);
            }
        }

    }
	public function delete_static_quiz_mixed($id)
    {
        $sql = "delete FROM `quiz_dynamic` WHERE id='$id'";
        $query = $this->db->query($sql);
    }
	public function update_quizquestion1($que, $op1, $op2, $op3, $op4, $dis)
    {
        $question_name = stripslashes($this->input->post('question_name'));

        $question_name = str_replace("<p>", " ", $question_name);
        $question_name = str_replace("</p>", " ", $question_name);
        if ($que == "") {} else {
            $question_name = $question_name . '<img src="' . $que . '" height="100px" width="100px"></img>';}
        $ans1 = stripslashes($this->input->post('option1'));
        $ans1 = str_replace("<p>", " ", $ans1);
        $ans1 = str_replace("</p>", " ", $ans1);
        if ($op1 == "") {} else {
            $ans1 = $ans1 . '<img src="' . $op1 . '" height="100px" width="100px"></img>';}
        $ans2 = stripslashes($this->input->post('option2'));
        $ans2 = str_replace("<p>", " ", $ans2);
        $ans2 = str_replace("</p>", " ", $ans2);
        if ($op2 == "") {} else {
            $ans2 = $ans2 . '<img src="' . $op2 . '" height="100px" width="100px"></img>';}
        $ans3 = stripslashes($this->input->post('option3'));
        $ans3 = str_replace("<p>", " ", $ans3);
        $ans3 = str_replace("</p>", " ", $ans3);
        if ($op3 == "") {} else {
            $ans3 = $ans3 . '<img src="' . $op3 . '" height="100px" width="100px"></img>';}
        $ans4 = stripslashes($this->input->post('option4'));
        $ans4 = str_replace("<p>", " ", $ans4);
        $ans4 = str_replace("</p>", " ", $ans4);

        if ($op4 == "") {} else {
            $ans4 = $ans4 . '<img src="' . $op4 . '" height="100px" width="100px"></img>';}
        $discription = stripslashes($this->input->post('dis'));
        $discription = str_replace("<p>", " ", $discription);
        $discription = str_replace("</p>", " ", $discription);
        if ($dis == "") {} else {
            $discription = $discription . '<img src="' . $dis . '" height="100px" width="100px"></img>';}
        $id1 = $this->input->post('id1');
      // print_r($question_name);
           // print_r($id1);
        $marks1 = $this->input->post('marks1');
        $qtype = $this->input->post('qtype');
        $crtans = $this->input->post('crtans');
        $penalty = $this->input->post('penalty');
		$grace = $this->input->post('grace');
		if ($qtype == "MAQ")
		{
			$correctoption = $crtans;
			
		}
		else
		{
        $correctoption = $this->input->post('correctoption');
		}
        $sql = "UPDATE `quiz_question` SET `discription`='$discription',`correctoption`='$correctoption',`question_name`='$question_name',`mark`='$marks1',`qtype`='$qtype',`correct_answer`='$crtans',`penalty`='$penalty',`grace`='$grace' WHERE `id`='$id1'";
        $t1 = "`fraction`='$penalty'";
        $t2 = "`fraction`='$penalty'";
        $t3 = "`fraction`='$penalty'";
        $t4 = "`fraction`='$penalty'";
        echo "correct option " . $correctoption . " marks1 " . $marks1;
        //$ans_t=array("A","B","C","D");
        $ans_t = array("1", "2", "3", "4");
        switch ($correctoption) {
            case $ans_t[0]:$t1 = "`fraction`='$marks1'";
                break;
            case $ans_t[1]:$t2 = "`fraction`='$marks1'";
                break;
            case $ans_t[2]:$t3 = "`fraction`='$marks1'";
                break;
            case $ans_t[3]:$t4 = "`fraction`='$marks1'";
        }
        $query = $this->db->query($sql);
        $sql = "SELECT * FROM `quiz_question_answers` WHERE Qustion_no='$id1'";
        $query = $this->db->query($sql);
        $arr = $query->result_array();
        $a1 = $arr[0]['id'];
        echo $sql = "UPDATE `quiz_question_answers` SET `question_answer`='$ans1',$t1 WHERE `id`='$a1'";
        $query = $this->db->query($sql);
        $a1 = $arr[1]['id'];
        echo $sql = "UPDATE `quiz_question_answers` SET `question_answer`='$ans2',$t2 WHERE `id`='$a1'";
        $query = $this->db->query($sql);
        $a1 = $arr[2]['id'];
        echo $sql = "UPDATE `quiz_question_answers` SET `question_answer`='$ans3',$t3 WHERE `id`='$a1'";
        $query = $this->db->query($sql);
        $a1 = $arr[3]['id'];
        echo $sql = "UPDATE `quiz_question_answers` SET `question_answer`='$ans4',$t4 WHERE `id`='$a1'";
        $query = $this->db->query($sql);
		
    }
	 function getquiznametype()
	{
		$sql = "SELECT Quiz_name FROM `quiz_info` where  `quiztype` = 'NEETCRASHCOURSE'";
		//print_r($sql);
		$query=$this->db->query($sql);
		//print_r($query);
		$dynamic = $query->result_array();
		return $dynamic;
	}
	
	public function save_staticrandom_quiz()
	{ 	
		$qname = $this->input->post('qname');
		$qname1 = $this->input->post('qname1');
		$tlimit = $this->input->post('tlimit');
		$tlimit1 = $this->input->post('tlimit1');
		$tlimit = $tlimit . ":" . $tlimit1;
		$quiznametype = $this->input->post('quiznametype');
		$subject_name = $this->input->post('subject_name');
		$nque = $this->input->post('nque');
		$seque = $this->input->post('seque');
		$quiztype = $this->input->post('quiztype');	
		if($quiznametype == '1'){
			$sql = "INSERT INTO `quiz_info`(`id`,`Quiz_name`,`subject_name`,`Time_limit`, `No_of_question`, `selected_Qustion`,`no_of_attemps`,`quiztype`)
				VALUES ('','$qname','$subject_name','$tlimit','$nque','$seque','1','$quiztype')";
			$query = $this->db->query($sql);
		}
		else{
			$sql = "UPDATE `quiz_info` SET `No_of_question`=No_of_question+'$nque',`selected_Qustion`=concat(selected_Qustion,',','$seque') WHERE `Quiz_name`='$qname1'";
			$query = $this->db->query($sql);
		}
	}
	public function getcrashrank($cat)
    {
        $this->load->library('nativesession');
        $sql = "truncate table quiz_rank";
        $query = $this->db->query($sql);
        $sql = "SET @prev_value = NULL";
        $query = $this->db->query($sql);
        $sql = "SET @rank_count = 0";
        $query = $this->db->query($sql);

        $sql = "INSERT into  quiz_rank
				select user_id,quiz_id ,physicsmark+chemistrymark+biologymark, CASE
		WHEN @prev_value = physicsmark+chemistrymark+biologymark THEN @rank_count
		WHEN @prev_value := physicsmark+chemistrymark+biologymark THEN @rank_count := @rank_count + 1

		END AS rank
		FROM student_quiz_result where quiz_id ='$cat'
		ORDER BY physicsmark+chemistrymark+biologymark desc";
        $query = $this->db->query($sql);

        $sql = "update student_quiz_result a inner join quiz_rank b
		on a.quiz_id = b.quiz_id
		set a.rank= b.rank
		where a.user_id = b.user_id
		and a.quiz_id = '$cat'";
        //    print_r($sql);
        $query = $this->db->query($sql);

        $sql = "SELECT a.*,(a.physicsmark+a.chemistrymark+a.biologymark) total ,concat(b.first_name,' ',b.last_name) name from student_quiz_result a,user_details b  where a.user_id collate utf8_general_ci = b.user_name collate utf8_general_ci and   a.quiz_id ='$cat'
		 ORDER BY CONVERT( a.rank, SIGNED INTEGER )";
        //    print_r($sql);
        $query = $this->db->query($sql);
        try {
            if ($query->num_rows() > 0) {
                return $query->result_array();
            } else {
                return $query->result_array();
            }

        } catch (exception $e) {echo $e->getMessage();
        }
    }
   
    public function getkcetrank($cat)
    {
        $this->load->library('nativesession');
        $sql = "truncate table quiz_rank";
        $query = $this->db->query($sql);
        $sql = "SET @prev_value = NULL";
        $query = $this->db->query($sql);
        $sql = "SET @rank_count = 0";
        $query = $this->db->query($sql);

        $sql = "INSERT into  quiz_rank
				select user_id,quiz_id ,physicsmark+chemistrymark+biologymark, CASE
		WHEN @prev_value = physicsmark+chemistrymark+biologymark THEN @rank_count
		WHEN @prev_value := physicsmark+chemistrymark+biologymark THEN @rank_count := @rank_count + 1

		END AS rank
		FROM student_quiz_result where quiz_id ='$cat'
		ORDER BY physicsmark+chemistrymark+biologymark desc";
        $query = $this->db->query($sql);

        $sql = "update student_quiz_result a inner join quiz_rank b
		on a.quiz_id = b.quiz_id
		set a.rank= b.rank
		where a.user_id = b.user_id
		and a.quiz_id = '$cat'";
        //    print_r($sql);
        $query = $this->db->query($sql);

        $sql = "SELECT a.*,(a.physicsmark+a.chemistrymark+a.biologymark) total ,concat(b.first_name,' ',b.last_name) name ,b.batch,b.rollno,b.accommodation,b.no_of_communication from student_quiz_result a,user_details b  where a.user_id collate utf8_general_ci = b.user_name collate utf8_general_ci and   a.quiz_id ='$cat'
		 ORDER BY CONVERT( a.rank, SIGNED INTEGER )";
        //    print_r($sql);
        $query = $this->db->query($sql);
        try {
            if ($query->num_rows() > 0) {
                return $query->result_array();
            } else {
                return $query->result_array();
            }

        } catch (exception $e) {echo $e->getMessage();
        }
    }
	
	public function savequizpptlink(){
		
	//	print_r($this->input->post('url'));
		$package_id=$this->input->post('package_id');
		$quiz_id=$this->input->post('quiz_id');
		$url=$this->input->post('url');
		$ppt_name= $this->input->post('ppt_name');
		$ppt_date= $this->input->post('ppt_date');
		$status= $this->input->post('status');
		
		$res=$this->db->insert('ppt_presentation_link',array(
			'package_id'=>$package_id,
			'quiz_id'=>$quiz_id,
			'url'=>$url,
			'name'=>$ppt_name,
			'date'=>$ppt_date,
			'status'=>$status
			));
			
			
			
		return $res;
	}
	public function getpptlinkdetails()
	{
		$sql="SELECT * FROM ppt_presentation_link";
		$query = $this->db->query($sql);
		return $query->result_array();
	}
	public function getquizid(){
		$sql="Select * FROM quiz_info;";
		$query = $this->db->query($sql);
		return $query->result_array();
	}
		public function get_neet_consol_rank($batch,$sub)
    {

        //drop table all_neet_rank;
        $sql = "truncate table all_neet_rank";
        $query = $this->db->query($sql);
		if ($batch=='all' && $sub =='all'){
        $sql = "insert into all_neet_rank
		select user_id,
		max(case when quiz_id ='192' THEN physicsmark+chemistrymark+biologymark END ) 'NEETCRASHCOURSE1',
		max(case when quiz_id ='272' THEN physicsmark+chemistrymark+biologymark END ) 'NEETCRASHCOURSE2',
		max(case when quiz_id ='273' THEN physicsmark+chemistrymark+biologymark END ) 'NEETCRASHCOURSE3',
		max(case when quiz_id ='274' THEN physicsmark+chemistrymark+biologymark END ) 'NEETCRASHCOURSE4',
		max(case when quiz_id ='275' THEN physicsmark+chemistrymark+biologymark END ) 'NEETCRASHCOURSE5',
		max(case when quiz_id ='276' THEN physicsmark+chemistrymark+biologymark END ) 'NEETCRASHCOURSE6',
		max(case when quiz_id ='277' THEN physicsmark+chemistrymark+biologymark END ) 'NEETCRASHCOURSE7',
		max(case when quiz_id ='278' THEN physicsmark+chemistrymark+biologymark END ) 'NEETCRASHCOURSE8',
		max(case when quiz_id ='279' THEN physicsmark+chemistrymark+biologymark END ) 'NEETCRASHCOURSE9',
		max(case when quiz_id ='300' THEN physicsmark+chemistrymark+biologymark END ) 'NEETCRASHCOURSE10',
		max(case when quiz_id ='301' THEN physicsmark+chemistrymark+biologymark END ) 'NEETCRASHCOURSE11',
		max(case when quiz_id ='302' THEN physicsmark+chemistrymark+biologymark END ) 'NEETCRASHCOURSE12',
		max(case when quiz_id ='303' THEN physicsmark+chemistrymark+biologymark END ) 'NEETCRASHCOURSE13',
		max(case when quiz_id ='304' THEN physicsmark+chemistrymark+biologymark END ) 'NEETCRASHCOURSE14',
		max(case when quiz_id ='305' THEN physicsmark+chemistrymark+biologymark END ) 'NEETCRASHCOURSE15',
		
		ifnull(max(case when quiz_id ='192' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='272' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='273' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='274' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='275' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='276' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='277' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='278' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='279' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='300' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='301' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='302' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='303' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='304' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='305' THEN physicsmark+chemistrymark+biologymark END ),0)total
		FROM student_quiz_result a , quiz_info b where a.quiz_id = b.id and b.quiztype='NEETCRASHCOURSE' GROUP BY user_id";
				$query = $this->db->query($sql);
				//print_r($sql);
		}

		if ($batch=='JNANASUDHAIIPU' && $sub =='all'){
			$sql = "insert into all_neet_rank
		select user_id,
		max(case when quiz_id ='192' THEN physicsmark+chemistrymark+biologymark END ) 'NEETCRASHCOURSE1',
		max(case when quiz_id ='272' THEN physicsmark+chemistrymark+biologymark END ) 'NEETCRASHCOURSE2',
		max(case when quiz_id ='273' THEN physicsmark+chemistrymark+biologymark END ) 'NEETCRASHCOURSE3',
		max(case when quiz_id ='274' THEN physicsmark+chemistrymark+biologymark END ) 'NEETCRASHCOURSE4',
		max(case when quiz_id ='275' THEN physicsmark+chemistrymark+biologymark END ) 'NEETCRASHCOURSE5',
		max(case when quiz_id ='276' THEN physicsmark+chemistrymark+biologymark END ) 'NEETCRASHCOURSE6',
		max(case when quiz_id ='277' THEN physicsmark+chemistrymark+biologymark END ) 'NEETCRASHCOURSE7',
		max(case when quiz_id ='278' THEN physicsmark+chemistrymark+biologymark END ) 'NEETCRASHCOURSE8',
		max(case when quiz_id ='279' THEN physicsmark+chemistrymark+biologymark END ) 'NEETCRASHCOURSE9',
		max(case when quiz_id ='300' THEN physicsmark+chemistrymark+biologymark END ) 'NEETCRASHCOURSE10',
		max(case when quiz_id ='301' THEN physicsmark+chemistrymark+biologymark END ) 'NEETCRASHCOURSE11',
		max(case when quiz_id ='302' THEN physicsmark+chemistrymark+biologymark END ) 'NEETCRASHCOURSE12',
		max(case when quiz_id ='303' THEN physicsmark+chemistrymark+biologymark END ) 'NEETCRASHCOURSE13',
		max(case when quiz_id ='304' THEN physicsmark+chemistrymark+biologymark END ) 'NEETCRASHCOURSE14',
		max(case when quiz_id ='305' THEN physicsmark+chemistrymark+biologymark END ) 'NEETCRASHCOURSE15',
		
		ifnull(max(case when quiz_id ='192' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='272' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='273' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='274' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='275' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='276' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='277' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='278' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='279' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='300' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='301' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='302' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='303' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='304' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='305' THEN physicsmark+chemistrymark+biologymark END ),0)total
		FROM student_quiz_result a , quiz_info b where a.quiz_id = b.id and b.quiztype='NEETCRASHCOURSE' and user_id collate utf8_general_ci in
				(select USER_NAME collate utf8_general_ci from user_details 
				where jnanasudhastandard = 'JNANASUDHAIIPU') GROUP BY user_id";
				$query = $this->db->query($sql);
				//print_r($sql);
			
		}
		if ($batch=='JNANASUDHAIPU' && $sub =='all'){
				$sql = "insert into all_neet_rank
		select user_id,
		max(case when quiz_id ='192' THEN physicsmark+chemistrymark+biologymark END ) 'NEETCRASHCOURSE1',
		max(case when quiz_id ='272' THEN physicsmark+chemistrymark+biologymark END ) 'NEETCRASHCOURSE2',
		max(case when quiz_id ='273' THEN physicsmark+chemistrymark+biologymark END ) 'NEETCRASHCOURSE3',
		max(case when quiz_id ='274' THEN physicsmark+chemistrymark+biologymark END ) 'NEETCRASHCOURSE4',
		max(case when quiz_id ='275' THEN physicsmark+chemistrymark+biologymark END ) 'NEETCRASHCOURSE5',
		max(case when quiz_id ='276' THEN physicsmark+chemistrymark+biologymark END ) 'NEETCRASHCOURSE6',
		max(case when quiz_id ='277' THEN physicsmark+chemistrymark+biologymark END ) 'NEETCRASHCOURSE7',
		max(case when quiz_id ='278' THEN physicsmark+chemistrymark+biologymark END ) 'NEETCRASHCOURSE8',
		max(case when quiz_id ='279' THEN physicsmark+chemistrymark+biologymark END ) 'NEETCRASHCOURSE9',
		max(case when quiz_id ='300' THEN physicsmark+chemistrymark+biologymark END ) 'NEETCRASHCOURSE10',
		max(case when quiz_id ='301' THEN physicsmark+chemistrymark+biologymark END ) 'NEETCRASHCOURSE11',
		max(case when quiz_id ='302' THEN physicsmark+chemistrymark+biologymark END ) 'NEETCRASHCOURSE12',
		max(case when quiz_id ='303' THEN physicsmark+chemistrymark+biologymark END ) 'NEETCRASHCOURSE13',
		max(case when quiz_id ='304' THEN physicsmark+chemistrymark+biologymark END ) 'NEETCRASHCOURSE14',
		max(case when quiz_id ='305' THEN physicsmark+chemistrymark+biologymark END ) 'NEETCRASHCOURSE15',
		
		ifnull(max(case when quiz_id ='192' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='272' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='273' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='274' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='275' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='276' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='277' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='278' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='279' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='300' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='301' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='302' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='303' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='304' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='305' THEN physicsmark+chemistrymark+biologymark END ),0)total
		FROM student_quiz_result a , quiz_info b where a.quiz_id = b.id and b.quiztype='NEETCRASHCOURSE' and user_id collate utf8_general_ci in
				(select USER_NAME collate utf8_general_ci from user_details 
				where jnanasudhastandard = 'JNANASUDHAIPU') GROUP BY user_id";
				$query = $this->db->query($sql);
				//print_r($sql);
			
		}
		if ($batch=='all' && $sub =='physics'){
			$sql = "insert into  all_neet_rank
		select user_id,
		max(case when quiz_id ='192' THEN physicsmark END ) 'NEETCRASHCOURSE1',
		max(case when quiz_id ='272' THEN physicsmark END ) 'NEETCRASHCOURSE2',
		max(case when quiz_id ='273' THEN physicsmark END ) 'NEETCRASHCOURSE3',
		max(case when quiz_id ='274' THEN physicsmark END ) 'NEETCRASHCOURSE4',
		max(case when quiz_id ='275' THEN physicsmark END ) 'NEETCRASHCOURSE5',
		max(case when quiz_id ='276' THEN physicsmark END ) 'NEETCRASHCOURSE6',
		max(case when quiz_id ='277' THEN physicsmark END ) 'NEETCRASHCOURSE7',
		max(case when quiz_id ='278' THEN physicsmark END ) 'NEETCRASHCOURSE8',
		max(case when quiz_id ='279' THEN physicsmark END ) 'NEETCRASHCOURSE9',
		max(case when quiz_id ='300' THEN physicsmark END ) 'NEETCRASHCOURSE10',
		max(case when quiz_id ='301' THEN physicsmark END ) 'NEETCRASHCOURSE11',
		max(case when quiz_id ='302' THEN physicsmark END ) 'NEETCRASHCOURSE12',
		max(case when quiz_id ='303' THEN physicsmark END ) 'NEETCRASHCOURSE13',
		max(case when quiz_id ='304' THEN physicsmark END ) 'NEETCRASHCOURSE14',
		max(case when quiz_id ='305' THEN physicsmark END ) 'NEETCRASHCOURSE15',
		
		ifnull(max(case when quiz_id ='192' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='272' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='273' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='274' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='275' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='276' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='277' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='278' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='279' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='300' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='301' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='302' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='303' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='304' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='305' THEN physicsmark END ),0) total
		FROM student_quiz_result a , quiz_info b where a.quiz_id = b.id and b.quiztype='NEETCRASHCOURSE' GROUP BY user_id ORDER BY physicsmark desc ";
				$query = $this->db->query($sql);
				// echo '<pre>';
			   // print_r($sql);
			   // echo '</pre>';
		}
		if ($batch=='all' && $sub =='chemistry'){
			$sql = "insert into  all_neet_rank
		select user_id,
		max(case when quiz_id ='192' THEN chemistrymark END ) 'NEETCRASHCOURSE1',
		max(case when quiz_id ='272' THEN chemistrymark END ) 'NEETCRASHCOURSE2',
		max(case when quiz_id ='273' THEN chemistrymark END ) 'NEETCRASHCOURSE3',
		max(case when quiz_id ='274' THEN chemistrymark END ) 'NEETCRASHCOURSE4',
		max(case when quiz_id ='275' THEN chemistrymark END ) 'NEETCRASHCOURSE5',
		max(case when quiz_id ='276' THEN chemistrymark END ) 'NEETCRASHCOURSE6',
		max(case when quiz_id ='277' THEN chemistrymark END ) 'NEETCRASHCOURSE7',
		max(case when quiz_id ='278' THEN chemistrymark END ) 'NEETCRASHCOURSE8',
		max(case when quiz_id ='279' THEN chemistrymark END ) 'NEETCRASHCOURSE9',
		max(case when quiz_id ='300' THEN chemistrymark END ) 'NEETCRASHCOURSE10',
		max(case when quiz_id ='301' THEN chemistrymark END ) 'NEETCRASHCOURSE11',
		max(case when quiz_id ='302' THEN chemistrymark END ) 'NEETCRASHCOURSE12',
		max(case when quiz_id ='303' THEN chemistrymark END ) 'NEETCRASHCOURSE13',
		max(case when quiz_id ='304' THEN chemistrymark END ) 'NEETCRASHCOURSE14',
		max(case when quiz_id ='305' THEN chemistrymark END ) 'NEETCRASHCOURSE15',
		
		ifnull(max(case when quiz_id ='192' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='272' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='273' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='274' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='275' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='276' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='277' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='278' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='279' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='300' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='301' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='302' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='303' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='304' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='305' THEN chemistrymark END ),0) total
		FROM student_quiz_result a , quiz_info b where a.quiz_id = b.id and b.quiztype='NEETCRASHCOURSE' GROUP BY user_id ORDER BY chemistrymark desc ";
				$query = $this->db->query($sql);
				// echo '<pre>';
			   // print_r($sql);
			   // echo '</pre>';
		}
		if ($batch=='all' && $sub =='biology'){
			$sql = "insert into  all_neet_rank
		select user_id,
		max(case when quiz_id ='192' THEN biologymark END ) 'NEETCRASHCOURSE1',
		max(case when quiz_id ='272' THEN biologymark END ) 'NEETCRASHCOURSE2',
		max(case when quiz_id ='273' THEN biologymark END ) 'NEETCRASHCOURSE3',
		max(case when quiz_id ='274' THEN biologymark END ) 'NEETCRASHCOURSE4',
		max(case when quiz_id ='275' THEN biologymark END ) 'NEETCRASHCOURSE5',
		max(case when quiz_id ='276' THEN biologymark END ) 'NEETCRASHCOURSE6',
		max(case when quiz_id ='277' THEN biologymark END ) 'NEETCRASHCOURSE7',
		max(case when quiz_id ='278' THEN biologymark END ) 'NEETCRASHCOURSE8',
		max(case when quiz_id ='279' THEN biologymark END ) 'NEETCRASHCOURSE9',
		max(case when quiz_id ='300' THEN biologymark END ) 'NEETCRASHCOURSE10',
		max(case when quiz_id ='301' THEN biologymark END ) 'NEETCRASHCOURSE11',
		max(case when quiz_id ='302' THEN biologymark END ) 'NEETCRASHCOURSE12',
		max(case when quiz_id ='303' THEN biologymark END ) 'NEETCRASHCOURSE13',
		max(case when quiz_id ='304' THEN biologymark END ) 'NEETCRASHCOURSE14',
		max(case when quiz_id ='305' THEN biologymark END ) 'NEETCRASHCOURSE15',
		
		ifnull(max(case when quiz_id ='192' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='272' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='273' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='274' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='275' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='276' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='277' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='278' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='279' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='300' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='301' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='302' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='303' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='304' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='305' THEN biologymark END ),0) total
		FROM student_quiz_result a , quiz_info b where a.quiz_id = b.id and b.quiztype='NEETCRASHCOURSE' GROUP BY user_id ORDER BY biologymark desc ";
				$query = $this->db->query($sql);
				// echo '<pre>';
			   // print_r($sql);
			   // echo '</pre>';
		}
		if ($batch=='JNANASUDHAIPU' && $sub =='physics'){
			$sql = "insert into  all_neet_rank
		select user_id,
		max(case when quiz_id ='192' THEN physicsmark END ) 'NEETCRASHCOURSE1',
		max(case when quiz_id ='272' THEN physicsmark END ) 'NEETCRASHCOURSE2',
		max(case when quiz_id ='273' THEN physicsmark END ) 'NEETCRASHCOURSE3',
		max(case when quiz_id ='274' THEN physicsmark END ) 'NEETCRASHCOURSE4',
		max(case when quiz_id ='275' THEN physicsmark END ) 'NEETCRASHCOURSE5',
		max(case when quiz_id ='276' THEN physicsmark END ) 'NEETCRASHCOURSE6',
		max(case when quiz_id ='277' THEN physicsmark END ) 'NEETCRASHCOURSE7',
		max(case when quiz_id ='278' THEN physicsmark END ) 'NEETCRASHCOURSE8',
		max(case when quiz_id ='279' THEN physicsmark END ) 'NEETCRASHCOURSE9',
		max(case when quiz_id ='300' THEN physicsmark END ) 'NEETCRASHCOURSE10',
		max(case when quiz_id ='301' THEN physicsmark END ) 'NEETCRASHCOURSE11',
		max(case when quiz_id ='302' THEN physicsmark END ) 'NEETCRASHCOURSE12',
		max(case when quiz_id ='303' THEN physicsmark END ) 'NEETCRASHCOURSE13',
		max(case when quiz_id ='304' THEN physicsmark END ) 'NEETCRASHCOURSE14',
		max(case when quiz_id ='305' THEN physicsmark END ) 'NEETCRASHCOURSE15',
		
		ifnull(max(case when quiz_id ='192' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='272' THEN physicsmark END ),0) +	
		ifnull(max(case when quiz_id ='273' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='274' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='275' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='276' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='277' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='278' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='279' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='300' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='301' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='302' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='303' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='304' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='305' THEN physicsmark END ),0) total
		FROM student_quiz_result a , quiz_info b where a.quiz_id = b.id and b.quiztype='NEETCRASHCOURSE' and user_id collate utf8_general_ci in
				(select USER_NAME collate utf8_general_ci from user_details 
				where jnanasudhastandard = 'JNANASUDHAIPU')Group By user_id ORDER BY physicsmark desc ";
				$query = $this->db->query($sql);
				// echo '<pre>';
			   // print_r($sql);
			   // echo '</pre>';
		}
		if ($batch=='JNANASUDHAIPU' && $sub =='chemistry'){
			$sql = "insert into  all_neet_rank
		select user_id,
		max(case when quiz_id ='192' THEN chemistrymark END ) 'NEETCRASHCOURSE1',
		max(case when quiz_id ='272' THEN chemistrymark END ) 'NEETCRASHCOURSE2',
		max(case when quiz_id ='273' THEN chemistrymark END ) 'NEETCRASHCOURSE3',
		max(case when quiz_id ='274' THEN chemistrymark END ) 'NEETCRASHCOURSE4',
		max(case when quiz_id ='275' THEN chemistrymark END ) 'NEETCRASHCOURSE5',
		max(case when quiz_id ='276' THEN chemistrymark END ) 'NEETCRASHCOURSE6',
		max(case when quiz_id ='277' THEN chemistrymark END ) 'NEETCRASHCOURSE7',
		max(case when quiz_id ='278' THEN chemistrymark END ) 'NEETCRASHCOURSE8',
		max(case when quiz_id ='279' THEN chemistrymark END ) 'NEETCRASHCOURSE9',
		max(case when quiz_id ='300' THEN chemistrymark END ) 'NEETCRASHCOURSE10',
		max(case when quiz_id ='301' THEN chemistrymark END ) 'NEETCRASHCOURSE11',
		max(case when quiz_id ='302' THEN chemistrymark END ) 'NEETCRASHCOURSE12',
		max(case when quiz_id ='303' THEN chemistrymark END ) 'NEETCRASHCOURSE13',
		max(case when quiz_id ='304' THEN chemistrymark END ) 'NEETCRASHCOURSE14',
		max(case when quiz_id ='305' THEN chemistrymark END ) 'NEETCRASHCOURSE15',
		
		ifnull(max(case when quiz_id ='192' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='272' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='273' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='274' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='275' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='276' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='277' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='278' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='279' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='300' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='301' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='302' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='303' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='304' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='305' THEN chemistrymark END ),0) total
		FROM student_quiz_result a , quiz_info b where a.quiz_id = b.id and b.quiztype='NEETCRASHCOURSE' and user_id collate utf8_general_ci in
				(select USER_NAME collate utf8_general_ci from user_details 
				where jnanasudhastandard = 'JNANASUDHAIPU')Group By user_id ORDER BY chemistrymark desc ";
				$query = $this->db->query($sql);
				// echo '<pre>';
			   // print_r($sql);
			   // echo '</pre>';
		}
		if ($batch=='JNANASUDHAIPU' && $sub =='biology'){
			$sql = "insert into  all_neet_rank
		select user_id,
		max(case when quiz_id ='192' THEN biologymark END ) 'NEETCRASHCOURSE1',
		max(case when quiz_id ='272' THEN biologymark END ) 'NEETCRASHCOURSE2',
		max(case when quiz_id ='273' THEN biologymark END ) 'NEETCRASHCOURSE3',
		max(case when quiz_id ='274' THEN biologymark END ) 'NEETCRASHCOURSE4',
		max(case when quiz_id ='275' THEN biologymark END ) 'NEETCRASHCOURSE5',
		max(case when quiz_id ='276' THEN biologymark END ) 'NEETCRASHCOURSE6',
		max(case when quiz_id ='277' THEN biologymark END ) 'NEETCRASHCOURSE7',
		max(case when quiz_id ='278' THEN biologymark END ) 'NEETCRASHCOURSE8',
		max(case when quiz_id ='279' THEN biologymark END ) 'NEETCRASHCOURSE9',
		max(case when quiz_id ='300' THEN biologymark END ) 'NEETCRASHCOURSE10',
		max(case when quiz_id ='301' THEN biologymark END ) 'NEETCRASHCOURSE11',
		max(case when quiz_id ='302' THEN biologymark END ) 'NEETCRASHCOURSE12',
		max(case when quiz_id ='303' THEN biologymark END ) 'NEETCRASHCOURSE13',
		max(case when quiz_id ='304' THEN biologymark END ) 'NEETCRASHCOURSE14',
		max(case when quiz_id ='305' THEN biologymark END ) 'NEETCRASHCOURSE15',
		
		ifnull(max(case when quiz_id ='192' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='272' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='273' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='274' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='275' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='276' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='277' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='278' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='279' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='300' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='301' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='302' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='303' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='304' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='305' THEN biologymark END ),0) total
		FROM student_quiz_result a , quiz_info b where a.quiz_id = b.id and b.quiztype='NEETCRASHCOURSE' and user_id collate utf8_general_ci in
				(select USER_NAME collate utf8_general_ci from user_details 
				where jnanasudhastandard = 'JNANASUDHAIPU')Group By user_id ORDER BY biologymark desc ";
				$query = $this->db->query($sql);
				// echo '<pre>';
			   // print_r($sql);
			   // echo '</pre>';
		}

		if ($batch=='JNANASUDHAIIPU' && $sub =='physics'){
			$sql = "insert into  all_neet_rank
		select user_id,
		max(case when quiz_id ='192' THEN physicsmark END ) 'NEETCRASHCOURSE1',
		max(case when quiz_id ='272' THEN physicsmark END ) 'NEETCRASHCOURSE2',
		max(case when quiz_id ='273' THEN physicsmark END ) 'NEETCRASHCOURSE3',
		max(case when quiz_id ='274' THEN physicsmark END ) 'NEETCRASHCOURSE4',
		max(case when quiz_id ='275' THEN physicsmark END ) 'NEETCRASHCOURSE5',
		max(case when quiz_id ='276' THEN physicsmark END ) 'NEETCRASHCOURSE6',
		max(case when quiz_id ='277' THEN physicsmark END ) 'NEETCRASHCOURSE7',
		max(case when quiz_id ='278' THEN physicsmark END ) 'NEETCRASHCOURSE8',
		max(case when quiz_id ='279' THEN physicsmark END ) 'NEETCRASHCOURSE9',
		max(case when quiz_id ='300' THEN physicsmark END ) 'NEETCRASHCOURSE10',
		max(case when quiz_id ='301' THEN physicsmark END ) 'NEETCRASHCOURSE11',
		max(case when quiz_id ='302' THEN physicsmark END ) 'NEETCRASHCOURSE12',
		max(case when quiz_id ='303' THEN physicsmark END ) 'NEETCRASHCOURSE13',
		max(case when quiz_id ='304' THEN physicsmark END ) 'NEETCRASHCOURSE14',
		max(case when quiz_id ='305' THEN physicsmark END ) 'NEETCRASHCOURSE15',
		
		ifnull(max(case when quiz_id ='192' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='272' THEN physicsmark END ),0) +	
		ifnull(max(case when quiz_id ='273' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='274' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='275' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='276' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='277' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='278' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='279' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='300' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='301' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='302' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='303' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='304' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='305' THEN physicsmark END ),0) total
		FROM student_quiz_result a , quiz_info b where a.quiz_id = b.id and b.quiztype='NEETCRASHCOURSE' and user_id collate utf8_general_ci in
				(select USER_NAME collate utf8_general_ci from user_details 
				where jnanasudhastandard = 'JNANASUDHAIIPU')Group By user_id ORDER BY physicsmark desc ";
				$query = $this->db->query($sql);
				// echo '<pre>';
			   // print_r($sql);
			   // echo '</pre>';
		}
		if ($batch=='JNANASUDHAIIPU' && $sub =='chemistry'){
			$sql = "insert into  all_neet_rank
		select user_id,
		max(case when quiz_id ='192' THEN chemistrymark END ) 'NEETCRASHCOURSE1',
		max(case when quiz_id ='272' THEN chemistrymark END ) 'NEETCRASHCOURSE2',
		max(case when quiz_id ='273' THEN chemistrymark END ) 'NEETCRASHCOURSE3',
		max(case when quiz_id ='274' THEN chemistrymark END ) 'NEETCRASHCOURSE4',
		max(case when quiz_id ='275' THEN chemistrymark END ) 'NEETCRASHCOURSE5',
		max(case when quiz_id ='276' THEN chemistrymark END ) 'NEETCRASHCOURSE6',
		max(case when quiz_id ='277' THEN chemistrymark END ) 'NEETCRASHCOURSE7',
		max(case when quiz_id ='278' THEN chemistrymark END ) 'NEETCRASHCOURSE8',
		max(case when quiz_id ='279' THEN chemistrymark END ) 'NEETCRASHCOURSE9',
		max(case when quiz_id ='300' THEN chemistrymark END ) 'NEETCRASHCOURSE10',
		max(case when quiz_id ='301' THEN chemistrymark END ) 'NEETCRASHCOURSE11',
		max(case when quiz_id ='302' THEN chemistrymark END ) 'NEETCRASHCOURSE12',
		max(case when quiz_id ='303' THEN chemistrymark END ) 'NEETCRASHCOURSE13',
		max(case when quiz_id ='304' THEN chemistrymark END ) 'NEETCRASHCOURSE14',
		max(case when quiz_id ='305' THEN chemistrymark END ) 'NEETCRASHCOURSE15',
		
		ifnull(max(case when quiz_id ='192' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='272' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='273' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='274' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='275' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='276' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='277' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='278' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='279' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='300' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='301' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='302' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='303' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='304' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='305' THEN chemistrymark END ),0) total
		FROM student_quiz_result a , quiz_info b where a.quiz_id = b.id and b.quiztype='NEETCRASHCOURSE' and user_id collate utf8_general_ci in
				(select USER_NAME collate utf8_general_ci from user_details 
				where jnanasudhastandard = 'JNANASUDHAIIPU')Group By user_id ORDER BY chemistrymark desc ";
				$query = $this->db->query($sql);
				// echo '<pre>';
			   // print_r($sql);
			   // echo '</pre>';
		}
		if ($batch=='JNANASUDHAIIPU' && $sub =='biology'){
			$sql = "insert into  all_neet_rank
		select user_id,
		max(case when quiz_id ='192' THEN biologymark END ) 'NEETCRASHCOURSE1',
		max(case when quiz_id ='272' THEN biologymark END ) 'NEETCRASHCOURSE2',
		max(case when quiz_id ='273' THEN biologymark END ) 'NEETCRASHCOURSE3',
		max(case when quiz_id ='274' THEN biologymark END ) 'NEETCRASHCOURSE4',
		max(case when quiz_id ='275' THEN biologymark END ) 'NEETCRASHCOURSE5',
		max(case when quiz_id ='276' THEN biologymark END ) 'NEETCRASHCOURSE6',
		max(case when quiz_id ='277' THEN biologymark END ) 'NEETCRASHCOURSE7',
		max(case when quiz_id ='278' THEN biologymark END ) 'NEETCRASHCOURSE8',
		max(case when quiz_id ='279' THEN biologymark END ) 'NEETCRASHCOURSE9',
		max(case when quiz_id ='300' THEN biologymark END ) 'NEETCRASHCOURSE10',
		max(case when quiz_id ='301' THEN biologymark END ) 'NEETCRASHCOURSE11',
		max(case when quiz_id ='302' THEN biologymark END ) 'NEETCRASHCOURSE12',
		max(case when quiz_id ='303' THEN biologymark END ) 'NEETCRASHCOURSE13',
		max(case when quiz_id ='304' THEN biologymark END ) 'NEETCRASHCOURSE14',
		max(case when quiz_id ='305' THEN biologymark END ) 'NEETCRASHCOURSE15',
		
		ifnull(max(case when quiz_id ='192' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='272' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='273' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='274' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='275' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='276' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='277' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='278' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='279' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='300' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='301' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='302' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='303' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='304' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='305' THEN biologymark END ),0) total
		FROM student_quiz_result a , quiz_info b where a.quiz_id = b.id and b.quiztype='NEETCRASHCOURSE' and user_id collate utf8_general_ci in
				(select USER_NAME collate utf8_general_ci from user_details 
				where jnanasudhastandard = 'JNANASUDHAIIPU')Group By user_id ORDER BY biologymark desc ";
				$query = $this->db->query($sql);
				// echo '<pre>';
			   // print_r($sql);
			   // echo '</pre>';
		}
		
        $sql = "truncate table consol_neet_rank";
        $query = $this->db->query($sql);
        $sql = "SET @prev_value = NULL";
        $query = $this->db->query($sql);
        $sql = "SET @rank_count = 0";
        $query = $this->db->query($sql);
        $sql = "truncate table consol_neet_rank";
        $query = $this->db->query($sql);
        $sql = "insert into  consol_neet_rank
		select *, CASE
		WHEN @prev_value = total THEN @rank_count
		WHEN @prev_value := total THEN @rank_count := @rank_count + 1

		END AS rank
		FROM all_neet_rank

		ORDER BY total desc";

        $query = $this->db->query($sql);

        $sql = "SELECT a.* ,concat(b.first_name,' ',b.last_name) name,b.batch,b.rollno,b.accommodation from consol_neet_rank a,user_details b  where a.user_id collate utf8_general_ci = b.user_name collate utf8_general_ci
			 ORDER BY CONVERT( a.rank, SIGNED INTEGER )";
        //    print_r($sql);
        $query = $this->db->query($sql);

        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return $query->result_array();
        }

    }
	public function get_kcet_consol_rank($batch,$sub)
    {

        //drop table all_neet_rank;
        $sql = "truncate table all_kcet_rank";
        $query = $this->db->query($sql);
		if ($batch=='all' && $sub =='all'){
        $sql = "insert into all_kcet_rank
		select user_id,
		max(case when quiz_id ='210' THEN physicsmark+chemistrymark+biologymark END ) 'KCETPCM1',
		max(case when quiz_id ='211' THEN physicsmark+chemistrymark+biologymark END ) 'KCETPCM2',
		max(case when quiz_id ='212' THEN physicsmark+chemistrymark+biologymark END ) 'KCETPCM3',
		max(case when quiz_id ='213' THEN physicsmark+chemistrymark+biologymark END ) 'KCETPCM4',
		max(case when quiz_id ='214' THEN physicsmark+chemistrymark+biologymark END ) 'KCETPCM5',
		max(case when quiz_id ='215' THEN physicsmark+chemistrymark+biologymark END ) 'KCETPCM6',
		max(case when quiz_id ='216' THEN physicsmark+chemistrymark+biologymark END ) 'KCETPCM7',
		max(case when quiz_id ='217' THEN physicsmark+chemistrymark+biologymark END ) 'KCETPCM8',
		max(case when quiz_id ='218' THEN physicsmark+chemistrymark+biologymark END ) 'KCETPCM9',
		max(case when quiz_id ='219' THEN physicsmark+chemistrymark+biologymark END ) 'KCETPCM10',
		max(case when quiz_id ='286' THEN physicsmark+chemistrymark+biologymark END ) 'KCETPCM11',
		max(case when quiz_id ='287' THEN physicsmark+chemistrymark+biologymark END ) 'KCETPCM12',
		max(case when quiz_id ='288' THEN physicsmark+chemistrymark+biologymark END ) 'KCETPCM13',
		max(case when quiz_id ='289' THEN physicsmark+chemistrymark+biologymark END ) 'KCETPCM14',
		max(case when quiz_id ='290' THEN physicsmark+chemistrymark+biologymark END ) 'KCETPCM15',
		max(case when quiz_id ='291' THEN physicsmark+chemistrymark+biologymark END ) 'KCETPCM16',
		max(case when quiz_id ='292' THEN physicsmark+chemistrymark+biologymark END ) 'KCETPCM17',
		max(case when quiz_id ='293' THEN physicsmark+chemistrymark+biologymark END ) 'KCETPCM18',
		max(case when quiz_id ='294' THEN physicsmark+chemistrymark+biologymark END ) 'KCETPCM19',
		max(case when quiz_id ='295' THEN physicsmark+chemistrymark+biologymark END ) 'KCETPCM20',
		
		ifnull(max(case when quiz_id ='210' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='211' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='212' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='213' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='214' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='215' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='216' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='217' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='218' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='219' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='286' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='287' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='288' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='289' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='290' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='291' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='292' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='293' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='294' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='295' THEN physicsmark+chemistrymark+biologymark END ),0)  total
		FROM student_quiz_result a , quiz_info b where a.quiz_id = b.id and b.quiztype='KCETPCM' GROUP BY user_id";
				$query = $this->db->query($sql);
				//print_r($sql);
		}

		if ($batch=='JNANASUDHAIIPU' && $sub =='all'){
			$sql = "insert into all_kcet_rank
		select user_id,
		max(case when quiz_id ='210' THEN physicsmark+chemistrymark+biologymark END ) 'KCETPCM1',
		max(case when quiz_id ='211' THEN physicsmark+chemistrymark+biologymark END ) 'KCETPCM2',
		max(case when quiz_id ='212' THEN physicsmark+chemistrymark+biologymark END ) 'KCETPCM3',
		max(case when quiz_id ='213' THEN physicsmark+chemistrymark+biologymark END ) 'KCETPCM4',
		max(case when quiz_id ='214' THEN physicsmark+chemistrymark+biologymark END ) 'KCETPCM5',
		max(case when quiz_id ='215' THEN physicsmark+chemistrymark+biologymark END ) 'KCETPCM6',
		max(case when quiz_id ='216' THEN physicsmark+chemistrymark+biologymark END ) 'KCETPCM7',
		max(case when quiz_id ='217' THEN physicsmark+chemistrymark+biologymark END ) 'KCETPCM8',
		max(case when quiz_id ='218' THEN physicsmark+chemistrymark+biologymark END ) 'KCETPCM9',
		max(case when quiz_id ='219' THEN physicsmark+chemistrymark+biologymark END ) 'KCETPCM10',
		max(case when quiz_id ='286' THEN physicsmark+chemistrymark+biologymark END ) 'KCETPCM11',
		max(case when quiz_id ='287' THEN physicsmark+chemistrymark+biologymark END ) 'KCETPCM12',
		max(case when quiz_id ='288' THEN physicsmark+chemistrymark+biologymark END ) 'KCETPCM13',
		max(case when quiz_id ='289' THEN physicsmark+chemistrymark+biologymark END ) 'KCETPCM14',
		max(case when quiz_id ='290' THEN physicsmark+chemistrymark+biologymark END ) 'KCETPCM15',
		max(case when quiz_id ='291' THEN physicsmark+chemistrymark+biologymark END ) 'KCETPCM16',
		max(case when quiz_id ='292' THEN physicsmark+chemistrymark+biologymark END ) 'KCETPCM17',
		max(case when quiz_id ='293' THEN physicsmark+chemistrymark+biologymark END ) 'KCETPCM18',
		max(case when quiz_id ='294' THEN physicsmark+chemistrymark+biologymark END ) 'KCETPCM19',
		max(case when quiz_id ='295' THEN physicsmark+chemistrymark+biologymark END ) 'KCETPCM20',
		
		ifnull(max(case when quiz_id ='210' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='211' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='212' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='213' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='214' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='215' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='216' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='217' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='218' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='219' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='286' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='287' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='288' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='289' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='290' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='291' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='292' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='293' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='294' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='295' THEN physicsmark+chemistrymark+biologymark END ),0)  total
		FROM student_quiz_result a , quiz_info b where a.quiz_id = b.id and b.quiztype='KCETPCM' and user_id collate utf8_general_ci in
				(select USER_NAME collate utf8_general_ci from user_details 
				where jnanasudhastandard = 'JNANASUDHAIIPU') GROUP BY user_id";
				$query = $this->db->query($sql);
				//print_r($sql);
			
		}
		if ($batch=='JNANASUDHAIPU' && $sub =='all'){
				$sql = "insert into all_kcet_rank
		select user_id,
		max(case when quiz_id ='210' THEN physicsmark+chemistrymark+biologymark END ) 'KCETPCM1',
		max(case when quiz_id ='211' THEN physicsmark+chemistrymark+biologymark END ) 'KCETPCM2',
		max(case when quiz_id ='212' THEN physicsmark+chemistrymark+biologymark END ) 'KCETPCM3',
		max(case when quiz_id ='213' THEN physicsmark+chemistrymark+biologymark END ) 'KCETPCM4',
		max(case when quiz_id ='214' THEN physicsmark+chemistrymark+biologymark END ) 'KCETPCM5',
		max(case when quiz_id ='215' THEN physicsmark+chemistrymark+biologymark END ) 'KCETPCM6',
		max(case when quiz_id ='216' THEN physicsmark+chemistrymark+biologymark END ) 'KCETPCM7',
		max(case when quiz_id ='217' THEN physicsmark+chemistrymark+biologymark END ) 'KCETPCM8',
		max(case when quiz_id ='218' THEN physicsmark+chemistrymark+biologymark END ) 'KCETPCM9',
		max(case when quiz_id ='219' THEN physicsmark+chemistrymark+biologymark END ) 'KCETPCM10',
		
		max(case when quiz_id ='286' THEN physicsmark+chemistrymark+biologymark END ) 'KCETPCM11',
		max(case when quiz_id ='287' THEN physicsmark+chemistrymark+biologymark END ) 'KCETPCM12',
		max(case when quiz_id ='288' THEN physicsmark+chemistrymark+biologymark END ) 'KCETPCM13',
		max(case when quiz_id ='289' THEN physicsmark+chemistrymark+biologymark END ) 'KCETPCM14',
		max(case when quiz_id ='290' THEN physicsmark+chemistrymark+biologymark END ) 'KCETPCM15',
		max(case when quiz_id ='291' THEN physicsmark+chemistrymark+biologymark END ) 'KCETPCM16',
		max(case when quiz_id ='292' THEN physicsmark+chemistrymark+biologymark END ) 'KCETPCM17',
		max(case when quiz_id ='293' THEN physicsmark+chemistrymark+biologymark END ) 'KCETPCM18',
		max(case when quiz_id ='294' THEN physicsmark+chemistrymark+biologymark END ) 'KCETPCM19',
		max(case when quiz_id ='295' THEN physicsmark+chemistrymark+biologymark END ) 'KCETPCM20',
		
		ifnull(max(case when quiz_id ='210' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='211' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='212' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='213' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='214' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='215' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='216' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='217' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='218' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='219' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='286' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='287' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='288' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='289' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='290' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='291' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='292' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='293' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='294' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='295' THEN physicsmark+chemistrymark+biologymark END ),0)  total
		FROM student_quiz_result a , quiz_info b where a.quiz_id = b.id and b.quiztype='KCETPCM' and user_id collate utf8_general_ci in
				(select USER_NAME collate utf8_general_ci from user_details 
				where jnanasudhastandard = 'JNANASUDHAIPU') GROUP BY user_id";
				$query = $this->db->query($sql);
				//print_r($sql);
			
		}
		if ($batch=='all' && $sub =='physics'){
			$sql = "insert into  all_kcet_rank
		select user_id,
		max(case when quiz_id ='210' THEN physicsmark END ) 'KCETPCM1',
		max(case when quiz_id ='211' THEN physicsmark END ) 'KCETPCM2',
		max(case when quiz_id ='212' THEN physicsmark END ) 'KCETPCM3',
		max(case when quiz_id ='213' THEN physicsmark END ) 'KCETPCM4',
		max(case when quiz_id ='214' THEN physicsmark END ) 'KCETPCM5',
		max(case when quiz_id ='215' THEN physicsmark END ) 'KCETPCM6',
		max(case when quiz_id ='216' THEN physicsmark END ) 'KCETPCM7',
		max(case when quiz_id ='217' THEN physicsmark END ) 'KCETPCM8',
		max(case when quiz_id ='218' THEN physicsmark END ) 'KCETPCM9',
		max(case when quiz_id ='219' THEN physicsmark END ) 'KCETPCM10',
		
		max(case when quiz_id ='286' THEN physicsmark END ) 'KCETPCM11',
		max(case when quiz_id ='287' THEN physicsmark END ) 'KCETPCM12',
		max(case when quiz_id ='288' THEN physicsmark END ) 'KCETPCM13',
		max(case when quiz_id ='289' THEN physicsmark END ) 'KCETPCM14',
		max(case when quiz_id ='290' THEN physicsmark END ) 'KCETPCM15',
		max(case when quiz_id ='291' THEN physicsmark END ) 'KCETPCM16',
		max(case when quiz_id ='292' THEN physicsmark END ) 'KCETPCM17',
		max(case when quiz_id ='293' THEN physicsmark END ) 'KCETPCM18',
		max(case when quiz_id ='294' THEN physicsmark END ) 'KCETPCM19',
		max(case when quiz_id ='295' THEN physicsmark END ) 'KCETPCM20',
		
		ifnull(max(case when quiz_id ='210' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='211' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='212' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='213' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='214' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='215' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='216' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='217' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='218' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='219' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='286' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='287' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='288' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='289' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='290' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='291' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='292' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='293' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='294' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='295' THEN physicsmark END ),0)  total
		FROM student_quiz_result a , quiz_info b where a.quiz_id = b.id and b.quiztype='KCETPCM' GROUP BY user_id ORDER BY physicsmark desc ";
				$query = $this->db->query($sql);
				// echo '<pre>';
			   // print_r($sql);
			   // echo '</pre>';
		}
		if ($batch=='all' && $sub =='chemistry'){
			$sql = "insert into  all_kcet_rank
		select user_id,
		max(case when quiz_id ='210' THEN chemistrymark END ) 'KCETPCM1',
		max(case when quiz_id ='211' THEN chemistrymark END ) 'KCETPCM2',
		max(case when quiz_id ='212' THEN chemistrymark END ) 'KCETPCM3',
		max(case when quiz_id ='213' THEN chemistrymark END ) 'KCETPCM4',
		max(case when quiz_id ='214' THEN chemistrymark END ) 'KCETPCM5',
		max(case when quiz_id ='215' THEN chemistrymark END ) 'KCETPCM6',
		max(case when quiz_id ='216' THEN chemistrymark END ) 'KCETPCM7',
		max(case when quiz_id ='217' THEN chemistrymark END ) 'KCETPCM8',
		max(case when quiz_id ='218' THEN chemistrymark END ) 'KCETPCM9',
		max(case when quiz_id ='219' THEN chemistrymark END ) 'KCETPCM10',
		
		max(case when quiz_id ='286' THEN chemistrymark END ) 'KCETPCM11',
		max(case when quiz_id ='287' THEN chemistrymark END ) 'KCETPCM12',
		max(case when quiz_id ='288' THEN chemistrymark END ) 'KCETPCM13',
		max(case when quiz_id ='289' THEN chemistrymark END ) 'KCETPCM14',
		max(case when quiz_id ='290' THEN chemistrymark END ) 'KCETPCM15',
		max(case when quiz_id ='291' THEN chemistrymark END ) 'KCETPCM16',
		max(case when quiz_id ='292' THEN chemistrymark END ) 'KCETPCM17',
		max(case when quiz_id ='293' THEN chemistrymark END ) 'KCETPCM18',
		max(case when quiz_id ='294' THEN chemistrymark END ) 'KCETPCM19',
		max(case when quiz_id ='295' THEN chemistrymark END ) 'KCETPCM20',
		
		ifnull(max(case when quiz_id ='210' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='211' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='212' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='213' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='214' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='215' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='216' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='217' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='218' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='219' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='286' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='287' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='288' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='289' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='290' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='291' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='292' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='293' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='294' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='295' THEN chemistrymark END ),0)  total
		FROM student_quiz_result a , quiz_info b where a.quiz_id = b.id and b.quiztype='KCETPCM' GROUP BY user_id ORDER BY chemistrymark desc ";
				$query = $this->db->query($sql);
				// echo '<pre>';
			   // print_r($sql);
			   // echo '</pre>';
		}
		if ($batch=='all' && $sub =='biology'){
			$sql = "insert into  all_kcet_rank
		select user_id,
		max(case when quiz_id ='210' THEN biologymark END ) 'KCETPCM1',
		max(case when quiz_id ='211' THEN biologymark END ) 'KCETPCM2',
		max(case when quiz_id ='212' THEN biologymark END ) 'KCETPCM3',
		max(case when quiz_id ='213' THEN biologymark END ) 'KCETPCM4',
		max(case when quiz_id ='214' THEN biologymark END ) 'KCETPCM5',
		max(case when quiz_id ='215' THEN biologymark END ) 'KCETPCM6',
		max(case when quiz_id ='216' THEN biologymark END ) 'KCETPCM7',
		max(case when quiz_id ='217' THEN biologymark END ) 'KCETPCM8',
		max(case when quiz_id ='218' THEN biologymark END ) 'KCETPCM7',
		max(case when quiz_id ='219' THEN biologymark END ) 'KCETPCM8',
		
		max(case when quiz_id ='286' THEN biologymark END ) 'KCETPCM11',
		max(case when quiz_id ='287' THEN biologymark END ) 'KCETPCM12',
		max(case when quiz_id ='288' THEN biologymark END ) 'KCETPCM13',
		max(case when quiz_id ='289' THEN biologymark END ) 'KCETPCM14',
		max(case when quiz_id ='290' THEN biologymark END ) 'KCETPCM15',
		max(case when quiz_id ='291' THEN biologymark END ) 'KCETPCM16',
		max(case when quiz_id ='292' THEN biologymark END ) 'KCETPCM17',
		max(case when quiz_id ='293' THEN biologymark END ) 'KCETPCM18',
		max(case when quiz_id ='294' THEN biologymark END ) 'KCETPCM19',
		max(case when quiz_id ='295' THEN biologymark END ) 'KCETPCM20',
		
		ifnull(max(case when quiz_id ='210' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='211' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='212' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='213' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='214' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='215' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='216' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='217' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='218' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='219' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='286' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='287' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='288' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='289' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='290' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='291' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='292' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='293' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='294' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='295' THEN biologymark END ),0)   total
		FROM student_quiz_result a , quiz_info b where a.quiz_id = b.id and b.quiztype='KCETPCM' GROUP BY user_id ORDER BY biologymark desc ";
				$query = $this->db->query($sql);
				// echo '<pre>';
			   // print_r($sql);
			   // echo '</pre>';
		}
		if ($batch=='JNANASUDHAIPU' && $sub =='physics'){
			$sql = "insert into  all_kcet_rank
		select user_id,
		max(case when quiz_id ='210' THEN physicsmark END ) 'KCETPCM1',
		max(case when quiz_id ='211' THEN physicsmark END ) 'KCETPCM2',
		max(case when quiz_id ='212' THEN physicsmark END ) 'KCETPCM3',
		max(case when quiz_id ='213' THEN physicsmark END ) 'KCETPCM4',
		max(case when quiz_id ='214' THEN physicsmark END ) 'KCETPCM5',
		max(case when quiz_id ='215' THEN physicsmark END ) 'KCETPCM6',
		max(case when quiz_id ='216' THEN physicsmark END ) 'KCETPCM7',
		max(case when quiz_id ='217' THEN physicsmark END ) 'KCETPCM8',
		max(case when quiz_id ='218' THEN physicsmark END ) 'KCETPCM9',
		max(case when quiz_id ='219' THEN physicsmark END ) 'KCETPCM10',
		
		max(case when quiz_id ='286' THEN physicsmark END ) 'KCETPCM11',
		max(case when quiz_id ='287' THEN physicsmark END ) 'KCETPCM12',
		max(case when quiz_id ='288' THEN physicsmark END ) 'KCETPCM13',
		max(case when quiz_id ='289' THEN physicsmark END ) 'KCETPCM14',
		max(case when quiz_id ='290' THEN physicsmark END ) 'KCETPCM15',
		max(case when quiz_id ='291' THEN physicsmark END ) 'KCETPCM16',
		max(case when quiz_id ='292' THEN physicsmark END ) 'KCETPCM17',
		max(case when quiz_id ='293' THEN physicsmark END ) 'KCETPCM18',
		max(case when quiz_id ='294' THEN physicsmark END ) 'KCETPCM19',
		max(case when quiz_id ='295' THEN physicsmark END ) 'KCETPCM20',
		
		ifnull(max(case when quiz_id ='210' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='211' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='212' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='213' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='214' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='215' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='216' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='217' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='218' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='219' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='286' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='287' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='288' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='289' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='290' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='291' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='292' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='293' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='294' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='295' THEN physicsmark END ),0)  total
		FROM student_quiz_result a , quiz_info b where a.quiz_id = b.id and b.quiztype='KCETPCM' and user_id collate utf8_general_ci in
				(select USER_NAME collate utf8_general_ci from user_details 
				where jnanasudhastandard = 'JNANASUDHAIPU')Group By user_id ORDER BY physicsmark desc ";
				$query = $this->db->query($sql);
				// echo '<pre>';
			   // print_r($sql);
			   // echo '</pre>';
		}
		if ($batch=='JNANASUDHAIPU' && $sub =='chemistry'){
			$sql = "insert into  all_kcet_rank
		select user_id,
		max(case when quiz_id ='210' THEN chemistrymark END ) 'KCETPCM1',
		max(case when quiz_id ='211' THEN chemistrymark END ) 'KCETPCM2',
		max(case when quiz_id ='212' THEN chemistrymark END ) 'KCETPCM3',
		max(case when quiz_id ='213' THEN chemistrymark END ) 'KCETPCM4',
		max(case when quiz_id ='214' THEN chemistrymark END ) 'KCETPCM5',
		max(case when quiz_id ='215' THEN chemistrymark END ) 'KCETPCM6',
		max(case when quiz_id ='216' THEN chemistrymark END ) 'KCETPCM7',
		max(case when quiz_id ='217' THEN chemistrymark END ) 'KCETPCM8',
		max(case when quiz_id ='218' THEN chemistrymark END ) 'KCETPCM9',
		max(case when quiz_id ='219' THEN chemistrymark END ) 'KCETPCM10',
		
	max(case when quiz_id ='286' THEN physicsmark+chemistrymark+biologymark END ) 'KCETPCM11',
		max(case when quiz_id ='287' THEN chemistrymark END ) 'KCETPCM12',
		max(case when quiz_id ='288' THEN chemistrymark END ) 'KCETPCM13',
		max(case when quiz_id ='289' THEN chemistrymark END ) 'KCETPCM14',
		max(case when quiz_id ='290' THEN chemistrymark END ) 'KCETPCM15',
		max(case when quiz_id ='291' THEN chemistrymark END ) 'KCETPCM16',
		max(case when quiz_id ='292' THEN chemistrymark END ) 'KCETPCM17',
		max(case when quiz_id ='293' THEN chemistrymark END ) 'KCETPCM18',
		max(case when quiz_id ='294' THEN chemistrymark END ) 'KCETPCM19',
		max(case when quiz_id ='295' THEN chemistrymark END ) 'KCETPCM20',
		
		ifnull(max(case when quiz_id ='210' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='211' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='212' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='213' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='214' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='215' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='216' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='217' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='218' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='219' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='286' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='287' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='288' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='289' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='290' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='291' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='292' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='293' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='294' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='295' THEN chemistrymark END ),0)  total
		FROM student_quiz_result a , quiz_info b where a.quiz_id = b.id and b.quiztype='KCETPCM' and user_id collate utf8_general_ci in
				(select USER_NAME collate utf8_general_ci from user_details 
				where jnanasudhastandard = 'JNANASUDHAIPU')Group By user_id ORDER BY chemistrymark desc ";
				$query = $this->db->query($sql);
				// echo '<pre>';
			   // print_r($sql);
			   // echo '</pre>';
		}
		if ($batch=='JNANASUDHAIPU' && $sub =='biology'){
			$sql = "insert into  all_kcet_rank
		select user_id,
		max(case when quiz_id ='210' THEN biologymark END ) 'KCETPCM1',
		max(case when quiz_id ='211' THEN biologymark END ) 'KCETPCM2',
		max(case when quiz_id ='212' THEN biologymark END ) 'KCETPCM3',
		max(case when quiz_id ='213' THEN biologymark END ) 'KCETPCM4',
		max(case when quiz_id ='214' THEN biologymark END ) 'KCETPCM5',
		max(case when quiz_id ='215' THEN biologymark END ) 'KCETPCM6',
		max(case when quiz_id ='216' THEN biologymark END ) 'KCETPCM7',
		max(case when quiz_id ='217' THEN biologymark END ) 'KCETPCM8',
		max(case when quiz_id ='218' THEN biologymark END ) 'KCETPCM9',
		max(case when quiz_id ='219' THEN biologymark END ) 'KCETPCM10',
		
		max(case when quiz_id ='286' THEN biologymark END ) 'KCETPCM11',
		max(case when quiz_id ='287' THEN biologymark END ) 'KCETPCM12',
		max(case when quiz_id ='288' THEN biologymark END ) 'KCETPCM13',
		max(case when quiz_id ='289' THEN biologymark END ) 'KCETPCM14',
		max(case when quiz_id ='290' THEN biologymark END ) 'KCETPCM15',
		max(case when quiz_id ='291' THEN biologymark END ) 'KCETPCM16',
		max(case when quiz_id ='292' THEN biologymark END ) 'KCETPCM17',
		max(case when quiz_id ='293' THEN biologymark END ) 'KCETPCM18',
		max(case when quiz_id ='294' THEN biologymark END ) 'KCETPCM19',
		max(case when quiz_id ='295' THEN biologymark END ) 'KCETPCM20',
		
		ifnull(max(case when quiz_id ='210' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='211' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='212' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='213' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='214' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='215' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='216' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='217' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='218' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='219' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='286' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='287' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='288' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='289' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='290' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='291' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='292' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='293' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='294' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='295' THEN biologymark END ),0)  total
		FROM student_quiz_result a , quiz_info b where a.quiz_id = b.id and b.quiztype='KCETPCM' and user_id collate utf8_general_ci in
				(select USER_NAME collate utf8_general_ci from user_details 
				where jnanasudhastandard = 'JNANASUDHAIPU')Group By user_id ORDER BY biologymark desc ";
				$query = $this->db->query($sql);
				// echo '<pre>';
			   // print_r($sql);
			   // echo '</pre>';
		}

		if ($batch=='JNANASUDHAIIPU' && $sub =='physics'){
			$sql = "insert into  all_kcet_rank
		select user_id,
		max(case when quiz_id ='210' THEN physicsmark END ) 'KCETPCM1',
		max(case when quiz_id ='211' THEN physicsmark END ) 'KCETPCM2',
		max(case when quiz_id ='212' THEN physicsmark END ) 'KCETPCM3',
		max(case when quiz_id ='213' THEN physicsmark END ) 'KCETPCM4',
		max(case when quiz_id ='214' THEN physicsmark END ) 'KCETPCM5',
		max(case when quiz_id ='215' THEN physicsmark END ) 'KCETPCM6',
		max(case when quiz_id ='216' THEN physicsmark END ) 'KCETPCM7',
		max(case when quiz_id ='217' THEN physicsmark END ) 'KCETPCM8',
				max(case when quiz_id ='218' THEN physicsmark END ) 'KCETPCM9',
		max(case when quiz_id ='219' THEN physicsmark END ) 'KCETPCM10',
		
		max(case when quiz_id ='286' THEN physicsmark END ) 'KCETPCM11',
		max(case when quiz_id ='287' THEN physicsmark END ) 'KCETPCM12',
		max(case when quiz_id ='288' THEN physicsmark END ) 'KCETPCM13',
		max(case when quiz_id ='289' THEN physicsmark END ) 'KCETPCM14',
		max(case when quiz_id ='290' THEN physicsmark END ) 'KCETPCM15',
		max(case when quiz_id ='291' THEN physicsmark END ) 'KCETPCM16',
		max(case when quiz_id ='292' THEN physicsmark END ) 'KCETPCM17',
		max(case when quiz_id ='293' THEN physicsmark END ) 'KCETPCM18',
		max(case when quiz_id ='294' THEN physicsmark END ) 'KCETPCM19',
		max(case when quiz_id ='295' THEN physicsmark END ) 'KCETPCM20',
		
		ifnull(max(case when quiz_id ='210' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='211' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='212' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='213' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='214' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='215' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='216' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='217' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='218' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='219' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='286' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='287' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='288' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='289' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='290' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='291' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='292' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='293' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='294' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='295' THEN physicsmark END ),0)  total
		FROM student_quiz_result a , quiz_info b where a.quiz_id = b.id and b.quiztype='KCETPCM' and user_id collate utf8_general_ci in
				(select USER_NAME collate utf8_general_ci from user_details 
				where jnanasudhastandard = 'JNANASUDHAIIPU')Group By user_id ORDER BY physicsmark desc ";
				$query = $this->db->query($sql);
				// echo '<pre>';
			   // print_r($sql);
			   // echo '</pre>';
		}
		if ($batch=='JNANASUDHAIIPU' && $sub =='chemistry'){
			$sql = "insert into  all_kcet_rank
		select user_id,
		max(case when quiz_id ='210' THEN chemistrymark END ) 'KCETPCM1',
		max(case when quiz_id ='211' THEN chemistrymark END ) 'KCETPCM2',
		max(case when quiz_id ='212' THEN chemistrymark END ) 'KCETPCM3',
		max(case when quiz_id ='213' THEN chemistrymark END ) 'KCETPCM4',
		max(case when quiz_id ='214' THEN chemistrymark END ) 'KCETPCM5',
		max(case when quiz_id ='215' THEN chemistrymark END ) 'KCETPCM6',
		max(case when quiz_id ='216' THEN chemistrymark END ) 'KCETPCM7',
		max(case when quiz_id ='217' THEN chemistrymark END ) 'KCETPCM8',
		max(case when quiz_id ='218' THEN chemistrymark END ) 'KCETPCM9',
		max(case when quiz_id ='219' THEN chemistrymark END ) 'KCETPCM10',
		
		max(case when quiz_id ='286' THEN chemistrymark END ) 'KCETPCM11',
		max(case when quiz_id ='287' THEN chemistrymark END ) 'KCETPCM12',
		max(case when quiz_id ='288' THEN chemistrymark END ) 'KCETPCM13',
		max(case when quiz_id ='289' THEN chemistrymark END ) 'KCETPCM14',
		max(case when quiz_id ='290' THEN chemistrymark END ) 'KCETPCM15',
		max(case when quiz_id ='291' THEN chemistrymark END ) 'KCETPCM16',
		max(case when quiz_id ='292' THEN chemistrymark END ) 'KCETPCM17',
		max(case when quiz_id ='293' THEN chemistrymark END ) 'KCETPCM18',
		max(case when quiz_id ='294' THEN chemistrymark END ) 'KCETPCM19',
		max(case when quiz_id ='295' THEN chemistrymark END ) 'KCETPCM20',
		
		ifnull(max(case when quiz_id ='210' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='211' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='212' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='213' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='214' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='215' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='216' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='217' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='218' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='219' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='286' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='287' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='288' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='289' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='290' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='291' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='292' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='293' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='294' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='295' THEN chemistrymark END ),0)  total
		FROM student_quiz_result a , quiz_info b where a.quiz_id = b.id and b.quiztype='KCETPCM' and user_id collate utf8_general_ci in
				(select USER_NAME collate utf8_general_ci from user_details 
				where jnanasudhastandard = 'JNANASUDHAIIPU')Group By user_id ORDER BY chemistrymark desc ";
				$query = $this->db->query($sql);
				// echo '<pre>';
			   // print_r($sql);
			   // echo '</pre>';
		}
		if ($batch=='JNANASUDHAIIPU' && $sub =='biology'){
			$sql = "insert into  all_kcet_rank
		select user_id,
		max(case when quiz_id ='210' THEN biologymark END ) 'KCETPCM1',
		max(case when quiz_id ='211' THEN biologymark END ) 'KCETPCM2',
		max(case when quiz_id ='212' THEN biologymark END ) 'KCETPCM3',
		max(case when quiz_id ='213' THEN biologymark END ) 'KCETPCM4',
		max(case when quiz_id ='214' THEN biologymark END ) 'KCETPCM5',
		max(case when quiz_id ='215' THEN biologymark END ) 'KCETPCM6',
		max(case when quiz_id ='216' THEN biologymark END ) 'KCETPCM7',
		max(case when quiz_id ='217' THEN biologymark END ) 'KCETPCM8',
			max(case when quiz_id ='218' THEN biologymark END ) 'KCETPCM9',
		max(case when quiz_id ='219' THEN biologymark END ) 'KCETPCM10',
		
		max(case when quiz_id ='286' THEN biologymark END ) 'KCETPCM11',
		max(case when quiz_id ='287' THEN biologymark END ) 'KCETPCM12',
		max(case when quiz_id ='288' THEN biologymark END ) 'KCETPCM13',
		max(case when quiz_id ='289' THEN biologymark END ) 'KCETPCM14',
		max(case when quiz_id ='290' THEN biologymark END ) 'KCETPCM15',
		max(case when quiz_id ='291' THEN biologymark END ) 'KCETPCM16',
		max(case when quiz_id ='292' THEN biologymark END ) 'KCETPCM17',
		max(case when quiz_id ='293' THEN biologymark END ) 'KCETPCM18',
		max(case when quiz_id ='294' THEN biologymark END ) 'KCETPCM19',
		max(case when quiz_id ='295' THEN biologymark END ) 'KCETPCM20',
		
		ifnull(max(case when quiz_id ='210' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='211' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='212' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='213' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='214' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='215' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='216' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='217' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='218' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='219' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='286' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='287' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='288' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='289' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='290' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='291' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='292' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='293' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='294' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='295' THEN biologymark END ),0)  total
		FROM student_quiz_result a , quiz_info b where a.quiz_id = b.id and b.quiztype='KCETPCM' and user_id collate utf8_general_ci in
				(select USER_NAME collate utf8_general_ci from user_details 
				where jnanasudhastandard = 'JNANASUDHAIIPU')Group By user_id ORDER BY biologymark desc ";
				$query = $this->db->query($sql);
				// echo '<pre>';
			   // print_r($sql);
			   // echo '</pre>';
		}
		
        $sql = "truncate table consol_kcet_rank";
        $query = $this->db->query($sql);
        $sql = "SET @prev_value = NULL";
        $query = $this->db->query($sql);
        $sql = "SET @rank_count = 0";
        $query = $this->db->query($sql);
        $sql = "truncate table consol_kcet_rank";
        $query = $this->db->query($sql);
        $sql = "insert into  consol_kcet_rank
		select *, CASE
		WHEN @prev_value = total THEN @rank_count
		WHEN @prev_value := total THEN @rank_count := @rank_count + 1

		END AS rank
		FROM all_kcet_rank

		ORDER BY total desc";

        $query = $this->db->query($sql);

          $sql = "SELECT a.* ,concat(b.first_name,' ',b.last_name) name,b.batch,b.rollno,b.accommodation from consol_kcet_rank a,user_details b  where a.user_id collate utf8_general_ci = b.user_name collate utf8_general_ci
			 ORDER BY CONVERT( a.rank, SIGNED INTEGER )";
        //    print_r($sql);
        $query = $this->db->query($sql);

        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return $query->result_array();
        }

    }
public function get_neet_consol_rankjs($batch,$sub)
    {

        //drop table all_neet_rankjs;
        $sql = "truncate table all_neet_rankjs";
        $query = $this->db->query($sql);
		if ($batch=='all' && $sub =='all'){
        $sql = "insert into all_neet_rankjs
		select user_id,
		max(case when quiz_id ='296' THEN physicsmark+chemistrymark+biologymark END ) 'NEETCRASHCOURSE1',
		max(case when quiz_id ='297' THEN physicsmark+chemistrymark+biologymark END ) 'NEETCRASHCOURSE2',
		max(case when quiz_id ='298' THEN physicsmark+chemistrymark+biologymark END ) 'NEETCRASHCOURSE3',
		max(case when quiz_id ='125' THEN physicsmark+chemistrymark+biologymark END ) 'NEETCRASHCOURSE4',
		max(case when quiz_id ='1001' THEN physicsmark+chemistrymark+biologymark END ) 'NEETCRASHCOURSE5',
		
		
		ifnull(max(case when quiz_id ='296' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='297' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='298' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='125' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='1001' THEN physicsmark+chemistrymark+biologymark END ),0) total
		FROM student_quiz_result a , quiz_info b where a.quiz_id = b.id and b.quiztype in ('NEETCRASHCOURSE','JUT') GROUP BY user_id";
				$query = $this->db->query($sql);
				//print_r($sql);
		}

		if ($batch=='JNANASUDHAIIPU' && $sub =='all'){
			$sql = "insert into all_neet_rankjs
		select user_id,
		max(case when quiz_id ='296' THEN physicsmark+chemistrymark+biologymark END ) 'NEETCRASHCOURSE1',
		max(case when quiz_id ='297' THEN physicsmark+chemistrymark+biologymark END ) 'NEETCRASHCOURSE2',
		max(case when quiz_id ='298' THEN physicsmark+chemistrymark+biologymark END ) 'NEETCRASHCOURSE3',
		max(case when quiz_id ='125' THEN physicsmark+chemistrymark+biologymark END ) 'NEETCRASHCOURSE4',
		max(case when quiz_id ='1001' THEN physicsmark+chemistrymark+biologymark END ) 'NEETCRASHCOURSE5',
		
		
		ifnull(max(case when quiz_id ='296' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='297' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='298' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='125' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='1001' THEN physicsmark+chemistrymark+biologymark END ),0) total
		FROM student_quiz_result a , quiz_info b where a.quiz_id = b.id and b.quiztype in ('NEETCRASHCOURSE','JUT') and user_id collate utf8_general_ci in
				(select USER_NAME collate utf8_general_ci from user_details 
				where jnanasudhastandard = 'JNANASUDHAIIPU') GROUP BY user_id";
				$query = $this->db->query($sql);
				//print_r($sql);
			
		}
		if ($batch=='JNANASUDHAIPU' && $sub =='all'){
				$sql = "insert into all_neet_rankjs
		select user_id,
	max(case when quiz_id ='296' THEN physicsmark+chemistrymark+biologymark END ) 'NEETCRASHCOURSE1',
		max(case when quiz_id ='297' THEN physicsmark+chemistrymark+biologymark END ) 'NEETCRASHCOURSE2',
		max(case when quiz_id ='298' THEN physicsmark+chemistrymark+biologymark END ) 'NEETCRASHCOURSE3',
		max(case when quiz_id ='125' THEN physicsmark+chemistrymark+biologymark END ) 'NEETCRASHCOURSE4',
		max(case when quiz_id ='1001' THEN physicsmark+chemistrymark+biologymark END ) 'NEETCRASHCOURSE5',
		
		
		ifnull(max(case when quiz_id ='296' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='297' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='298' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='125' THEN physicsmark+chemistrymark+biologymark END ),0) +
		ifnull(max(case when quiz_id ='1001' THEN physicsmark+chemistrymark+biologymark END ),0) total
		FROM student_quiz_result a , quiz_info b where a.quiz_id = b.id and b.quiztype in ('NEETCRASHCOURSE','JUT') and user_id collate utf8_general_ci in
				(select USER_NAME collate utf8_general_ci from user_details 
				where jnanasudhastandard = 'JNANASUDHAIPU') GROUP BY user_id";
				$query = $this->db->query($sql);
				//print_r($sql);
			
		}
		if ($batch=='all' && $sub =='physics'){
			$sql = "insert into  all_neet_rankjs
		select user_id,
		max(case when quiz_id ='296' THEN physicsmark END ) 'NEETCRASHCOURSE1',
		max(case when quiz_id ='297' THEN physicsmark END ) 'NEETCRASHCOURSE2',
		max(case when quiz_id ='298' THEN physicsmark END ) 'NEETCRASHCOURSE3',
		max(case when quiz_id ='125' THEN physicsmark END ) 'NEETCRASHCOURSE4',
		max(case when quiz_id ='1001' THEN physicsmark END ) 'NEETCRASHCOURSE5',
		
		
		ifnull(max(case when quiz_id ='296' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='297' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='298' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='125' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='1001' THEN physicsmark END ),0)  total
		FROM student_quiz_result a , quiz_info b where a.quiz_id = b.id and b.quiztype in ('NEETCRASHCOURSE','JUT') GROUP BY user_id ORDER BY physicsmark desc ";
				$query = $this->db->query($sql);
				// echo '<pre>';
			   // print_r($sql);
			   // echo '</pre>';
		}
		if ($batch=='all' && $sub =='chemistry'){
			$sql = "insert into  all_neet_rankjs
		select user_id,
		max(case when quiz_id ='296' THEN chemistrymark END ) 'NEETCRASHCOURSE1',
		max(case when quiz_id ='297' THEN chemistrymark END ) 'NEETCRASHCOURSE2',
		max(case when quiz_id ='298' THEN chemistrymark END ) 'NEETCRASHCOURSE3',
		max(case when quiz_id ='125' THEN chemistrymark END ) 'NEETCRASHCOURSE4',
		max(case when quiz_id ='1001' THEN chemistrymark END ) 'NEETCRASHCOURSE5',
		
		
		ifnull(max(case when quiz_id ='296' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='297' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='298' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='125' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='1001' THEN chemistrymark END ),0)  total
		FROM student_quiz_result a , quiz_info b where a.quiz_id = b.id and b.quiztype in ('NEETCRASHCOURSE','JUT') GROUP BY user_id ORDER BY chemistrymark desc ";
				$query = $this->db->query($sql);
				// echo '<pre>';
			   // print_r($sql);
			   // echo '</pre>';
		}
		if ($batch=='all' && $sub =='biology'){
			$sql = "insert into  all_neet_rankjs
		select user_id,
		max(case when quiz_id ='296' THEN biologymark END ) 'NEETCRASHCOURSE1',
		max(case when quiz_id ='297' THEN biologymark END ) 'NEETCRASHCOURSE2',
		max(case when quiz_id ='298' THEN biologymark END ) 'NEETCRASHCOURSE3',
		max(case when quiz_id ='125' THEN biologymark END ) 'NEETCRASHCOURSE4',
		max(case when quiz_id ='1001' THEN biologymark END ) 'NEETCRASHCOURSE5',
		
		ifnull(max(case when quiz_id ='296' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='297' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='298' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='125' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='1001' THEN biologymark END ),0)  total
		FROM student_quiz_result a , quiz_info b where a.quiz_id = b.id and b.quiztype in ('NEETCRASHCOURSE','JUT') GROUP BY user_id ORDER BY biologymark desc ";
				$query = $this->db->query($sql);
				// echo '<pre>';
			   // print_r($sql);
			   // echo '</pre>';
		}
		if ($batch=='JNANASUDHAIPU' && $sub =='physics'){
			$sql = "insert into  all_neet_rankjs
		select user_id,
		max(case when quiz_id ='296' THEN physicsmark END ) 'NEETCRASHCOURSE1',
		max(case when quiz_id ='297' THEN physicsmark END ) 'NEETCRASHCOURSE2',
		max(case when quiz_id ='298' THEN physicsmark END ) 'NEETCRASHCOURSE3',
		max(case when quiz_id ='125' THEN physicsmark END ) 'NEETCRASHCOURSE4',
		max(case when quiz_id ='1001' THEN physicsmark END ) 'NEETCRASHCOURSE5',
		
		ifnull(max(case when quiz_id ='296' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='297' THEN physicsmark END ),0) +	
		ifnull(max(case when quiz_id ='298' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='125' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='1001' THEN physicsmark END ),0)  total
		FROM student_quiz_result a , quiz_info b where a.quiz_id = b.id and b.quiztype in ('NEETCRASHCOURSE','JUT') and user_id collate utf8_general_ci in
				(select USER_NAME collate utf8_general_ci from user_details 
				where jnanasudhastandard = 'JNANASUDHAIPU')Group By user_id ORDER BY physicsmark desc ";
				$query = $this->db->query($sql);
				// echo '<pre>';
			   // print_r($sql);
			   // echo '</pre>';
		}
		if ($batch=='JNANASUDHAIPU' && $sub =='chemistry'){
			$sql = "insert into  all_neet_rankjs
		select user_id,
		max(case when quiz_id ='296' THEN chemistrymark END ) 'NEETCRASHCOURSE1',
		max(case when quiz_id ='297' THEN chemistrymark END ) 'NEETCRASHCOURSE2',
		max(case when quiz_id ='298' THEN chemistrymark END ) 'NEETCRASHCOURSE3',
		max(case when quiz_id ='125' THEN chemistrymark END ) 'NEETCRASHCOURSE4',
		max(case when quiz_id ='1001' THEN chemistrymark END ) 'NEETCRASHCOURSE5',
		
		ifnull(max(case when quiz_id ='296' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='297' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='298' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='125' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='1001' THEN chemistrymark END ),0)  total
		FROM student_quiz_result a , quiz_info b where a.quiz_id = b.id and b.quiztype in ('NEETCRASHCOURSE','JUT') and user_id collate utf8_general_ci in
				(select USER_NAME collate utf8_general_ci from user_details 
				where jnanasudhastandard = 'JNANASUDHAIPU')Group By user_id ORDER BY chemistrymark desc ";
				$query = $this->db->query($sql);
				// echo '<pre>';
			   // print_r($sql);
			   // echo '</pre>';
		}
		if ($batch=='JNANASUDHAIPU' && $sub =='biology'){
			$sql = "insert into  all_neet_rankjs
		select user_id,
		max(case when quiz_id ='296' THEN biologymark END ) 'NEETCRASHCOURSE1',
		max(case when quiz_id ='297' THEN biologymark END ) 'NEETCRASHCOURSE2',
		max(case when quiz_id ='298' THEN biologymark END ) 'NEETCRASHCOURSE3',
		max(case when quiz_id ='125' THEN biologymark END ) 'NEETCRASHCOURSE4',
		max(case when quiz_id ='1001' THEN biologymark END ) 'NEETCRASHCOURSE5',
		
		ifnull(max(case when quiz_id ='296' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='297' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='298' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='125' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='1001' THEN biologymark END ),0)  total
		FROM student_quiz_result a , quiz_info b where a.quiz_id = b.id and b.quiztype in ('NEETCRASHCOURSE','JUT') and user_id collate utf8_general_ci in
				(select USER_NAME collate utf8_general_ci from user_details 
				where jnanasudhastandard = 'JNANASUDHAIPU')Group By user_id ORDER BY biologymark desc ";
				$query = $this->db->query($sql);
				// echo '<pre>';
			   // print_r($sql);
			   // echo '</pre>';
		}

		if ($batch=='JNANASUDHAIIPU' && $sub =='physics'){
			$sql = "insert into  all_neet_rankjs
		select user_id,
		max(case when quiz_id ='296' THEN physicsmark END ) 'NEETCRASHCOURSE1',
		max(case when quiz_id ='297' THEN physicsmark END ) 'NEETCRASHCOURSE2',
		max(case when quiz_id ='298' THEN physicsmark END ) 'NEETCRASHCOURSE3',
		max(case when quiz_id ='125' THEN physicsmark END ) 'NEETCRASHCOURSE4',
		max(case when quiz_id ='1001' THEN physicsmark END ) 'NEETCRASHCOURSE5',
		
		ifnull(max(case when quiz_id ='296' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='297' THEN physicsmark END ),0) +	
		ifnull(max(case when quiz_id ='298' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='125' THEN physicsmark END ),0) +
		ifnull(max(case when quiz_id ='1001' THEN physicsmark END ),0)  total
		FROM student_quiz_result a , quiz_info b where a.quiz_id = b.id and b.quiztype in ('NEETCRASHCOURSE','JUT') and user_id collate utf8_general_ci in
				(select USER_NAME collate utf8_general_ci from user_details 
				where jnanasudhastandard = 'JNANASUDHAIIPU')Group By user_id ORDER BY physicsmark desc ";
				$query = $this->db->query($sql);
				// echo '<pre>';
			   // print_r($sql);
			   // echo '</pre>';
		}
		if ($batch=='JNANASUDHAIIPU' && $sub =='chemistry'){
			$sql = "insert into  all_neet_rankjs
		select user_id,
		max(case when quiz_id ='296' THEN chemistrymark END ) 'NEETCRASHCOURSE1',
		max(case when quiz_id ='297' THEN chemistrymark END ) 'NEETCRASHCOURSE2',
		max(case when quiz_id ='298' THEN chemistrymark END ) 'NEETCRASHCOURSE3',
		max(case when quiz_id ='125' THEN chemistrymark END ) 'NEETCRASHCOURSE4',
		max(case when quiz_id ='1001' THEN chemistrymark END ) 'NEETCRASHCOURSE5',
		
		ifnull(max(case when quiz_id ='296' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='297' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='298' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='125' THEN chemistrymark END ),0) +
		ifnull(max(case when quiz_id ='1001' THEN chemistrymark END ),0)  total
		FROM student_quiz_result a , quiz_info b where a.quiz_id = b.id and b.quiztype in ('NEETCRASHCOURSE','JUT') and user_id collate utf8_general_ci in
				(select USER_NAME collate utf8_general_ci from user_details 
				where jnanasudhastandard = 'JNANASUDHAIIPU')Group By user_id ORDER BY chemistrymark desc ";
				$query = $this->db->query($sql);
				// echo '<pre>';
			   // print_r($sql);
			   // echo '</pre>';
		}
		if ($batch=='JNANASUDHAIIPU' && $sub =='biology'){
			$sql = "insert into  all_neet_rankjs
		select user_id,
		max(case when quiz_id ='296' THEN biologymark END ) 'NEETCRASHCOURSE1',
		max(case when quiz_id ='297' THEN biologymark END ) 'NEETCRASHCOURSE2',
		max(case when quiz_id ='298' THEN biologymark END ) 'NEETCRASHCOURSE3',
		max(case when quiz_id ='125' THEN biologymark END ) 'NEETCRASHCOURSE4',
		max(case when quiz_id ='1001' THEN biologymark END ) 'NEETCRASHCOURSE5',
		
		ifnull(max(case when quiz_id ='296' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='297' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='298' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='125' THEN biologymark END ),0) +
		ifnull(max(case when quiz_id ='1001' THEN biologymark END ),0) total
		FROM student_quiz_result a , quiz_info b where a.quiz_id = b.id and b.quiztype in ('NEETCRASHCOURSE','JUT') and user_id collate utf8_general_ci in
				(select USER_NAME collate utf8_general_ci from user_details 
				where jnanasudhastandard = 'JNANASUDHAIIPU')Group By user_id ORDER BY biologymark desc ";
				$query = $this->db->query($sql);
				// echo '<pre>';
			   // print_r($sql);
			   // echo '</pre>';
		}
		
        $sql = "truncate table consol_neet_rankjs";
        $query = $this->db->query($sql);
        $sql = "SET @prev_value = NULL";
        $query = $this->db->query($sql);
        $sql = "SET @rank_count = 0";
        $query = $this->db->query($sql);
        $sql = "truncate table consol_neet_rankjs";
        $query = $this->db->query($sql);
        $sql = "insert into  consol_neet_rankjs
		select *, CASE
		WHEN @prev_value = total THEN @rank_count
		WHEN @prev_value := total THEN @rank_count := @rank_count + 1

		END AS rank
		FROM all_neet_rankjs

		ORDER BY total desc";

        $query = $this->db->query($sql);

        $sql = "SELECT a.* ,concat(b.first_name,' ',b.last_name) name from consol_neet_rankjs a,user_details b  where a.user_id collate utf8_general_ci = b.user_name collate utf8_general_ci
			 ORDER BY CONVERT( a.rank, SIGNED INTEGER )";
        //    print_r($sql);
        $query = $this->db->query($sql);

        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return $query->result_array();
        }

    }
	public function update_rollno_standard($user_id)
	{
		$rollno = $_POST['rollno'];
		$standard = $_POST['standard'];
		
		$sql="UPDATE `user_details` SET `rollno`='$rollno',`jnanasudhastandard`='$standard'
		WHERE user_id='$user_id'";
		
		print_r($sql);
		$query=$this->db->query($sql);
		if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return $query->result_array();
        }
	}
	public function update_batch_acc_names($user_id)
	{   	
		$user_name = $_POST['user_name'];
		$batch = $_POST['batch'];
		$accommodation = $_POST['accommodation'];
		$first_name = $_POST['first_name'];
		$last_name = $_POST['last_name'];
		$no_of_communication = $_POST['no_of_communication'];
		
		$sql="UPDATE `user_details` SET `batch`='$batch',`accommodation`='$accommodation',`first_name`='$first_name',`last_name`='$last_name',no_of_communication='$no_of_communication'
		WHERE user_id='$user_id' and user_name='$user_name'";
		$query=$this->db->query($sql);
	//	print_r($sql);
		
		$sql="UPDATE `subscription_details` SET `name`=concat('$first_name',' ','$last_name')
		WHERE  username='$user_name'";
		$query=$this->db->query($sql);
		//print_r($sql);
	}
	public function update_batch_accom($user_id)
	{   
		$batch = $_POST['batch'];
		$accommodation = $_POST['accommodation'];
		
		$sql="UPDATE `user_details` SET `batch`='$batch',`accommodation`='$accommodation'
		WHERE user_id='$user_id'";
		$query=$this->db->query($sql);
	}
	public function getntsesatrank($cat,$batch)
    {
        $this->load->library('nativesession');

        $sql = "truncate table quiz_rank";
        $query = $this->db->query($sql);
        $sql = "SET @prev_value = NULL";
        $query = $this->db->query($sql);
        $sql = "SET @rank_count = 0";
        $query = $this->db->query($sql);
if ($batch=='all'){

        $sql = "INSERT into  quiz_rank
select user_id,quiz_id ,physicsmark+chemistrymark+biologymark, CASE
WHEN @prev_value = physicsmark+chemistrymark+biologymark THEN @rank_count
WHEN @prev_value := physicsmark+chemistrymark+biologymark THEN @rank_count := @rank_count + 1

END AS rank
FROM student_quiz_result where quiz_id ='$cat'
ORDER BY physicsmark+chemistrymark+biologymark desc";

}

if ($batch=='22'){

        $sql = "INSERT into  quiz_rank
select user_id,quiz_id ,physicsmark+chemistrymark+biologymark, CASE
WHEN @prev_value = physicsmark+chemistrymark+biologymark THEN @rank_count
WHEN @prev_value := physicsmark+chemistrymark+biologymark THEN @rank_count := @rank_count + 1

END AS rank
FROM student_quiz_result where quiz_id ='$cat' and user_id  in
        (select username  from subscription_details
        where package_id = 22)
ORDER BY physicsmark+chemistrymark+biologymark desc";

}
if ($batch=='57'){

        $sql = "INSERT into  quiz_rank
select user_id,quiz_id ,physicsmark+chemistrymark+biologymark, CASE
WHEN @prev_value = physicsmark+chemistrymark+biologymark THEN @rank_count
WHEN @prev_value := physicsmark+chemistrymark+biologymark THEN @rank_count := @rank_count + 1

END AS rank
FROM student_quiz_result where quiz_id ='$cat' and user_id  in
        (select username  from subscription_details
        where package_id = 57)
ORDER BY physicsmark+chemistrymark+biologymark desc";

}
//print_r($sql);
        $query = $this->db->query($sql);

        $sql = "update student_quiz_result a inner join quiz_rank b
on a.quiz_id = b.quiz_id
set a.rank= b.rank
where a.user_id = b.user_id
and a.quiz_id = '$cat'";
        //    print_r($sql);
        $query = $this->db->query($sql);
if ($batch=='all'){
$sql = "SELECT a.*,(a.physicsmark+a.chemistrymark+a.biologymark) total ,concat(b.first_name,' ',b.last_name) name,b.rollno,b.batch,b.accommodation from student_quiz_result a,user_details b
where a.user_id collate utf8_general_ci = b.user_name collate utf8_general_ci and   a.quiz_id ='$cat'
ORDER BY CONVERT( a.rank, SIGNED INTEGER )";
}

if ($batch=='22'){
$sql = "SELECT a.*,(a.physicsmark+a.chemistrymark+a.biologymark) total ,concat(b.first_name,' ',b.last_name) name, c.package_id,b.rollno,b.batch,b.accommodation
from student_quiz_result a,user_details b,subscription_details c
where a.user_id collate utf8_general_ci = b.user_name collate utf8_general_ci
and c.username  = a.user_id and a.quiz_id ='$cat' and c.package_id='22'
ORDER BY CONVERT( a.rank, SIGNED INTEGER )";
}
if ($batch=='57'){
$sql = "SELECT a.*,(a.physicsmark+a.chemistrymark+a.biologymark) total ,concat(b.first_name,' ',b.last_name) name, c.package_id,b.rollno,b.batch,b.accommodation
from student_quiz_result a,user_details b,subscription_details c
where a.user_id collate utf8_general_ci = b.user_name collate utf8_general_ci
and c.username  = a.user_id and a.quiz_id ='$cat' and c.package_id='57'
ORDER BY CONVERT( a.rank, SIGNED INTEGER )";
}

        //    print_r($sql);
        $query = $this->db->query($sql);
        try {
            if ($query->num_rows() > 0) {
                return $query->result_array();
            } else {
                return $query->result_array();
            }

        } catch (exception $e) {echo $e->getMessage();
        }

    }
	public function getntsematrank($cat,$batch)
    {
        $this->load->library('nativesession');

        $sql = "truncate table quiz_rank";
        $query = $this->db->query($sql);
        $sql = "SET @prev_value = NULL";
        $query = $this->db->query($sql);
        $sql = "SET @rank_count = 0";
        $query = $this->db->query($sql);
		if ($batch=='all'){

        $sql = "INSERT into  quiz_rank
				select user_id,quiz_id ,physicsmark+chemistrymark+biologymark, CASE
		WHEN @prev_value = physicsmark+chemistrymark+biologymark THEN @rank_count
		WHEN @prev_value := physicsmark+chemistrymark+biologymark THEN @rank_count := @rank_count + 1

		END AS rank
		FROM student_quiz_result where quiz_id ='$cat'
		ORDER BY physicsmark+chemistrymark+biologymark desc";
		
		}
		
		 if ($batch=='22'){

        $sql = "INSERT into  quiz_rank
				select user_id,quiz_id ,physicsmark+chemistrymark+biologymark, CASE
		WHEN @prev_value = physicsmark+chemistrymark+biologymark THEN @rank_count
		WHEN @prev_value := physicsmark+chemistrymark+biologymark THEN @rank_count := @rank_count + 1

		END AS rank
		FROM student_quiz_result where quiz_id ='$cat' and user_id  in
        (select username  from subscription_details 
        where package_id =22)
		ORDER BY physicsmark+chemistrymark+biologymark desc";
		
		}
		 if ($batch=='57'){

        $sql = "INSERT into  quiz_rank
				select user_id,quiz_id ,physicsmark+chemistrymark+biologymark, CASE
		WHEN @prev_value = physicsmark+chemistrymark+biologymark THEN @rank_count
		WHEN @prev_value := physicsmark+chemistrymark+biologymark THEN @rank_count := @rank_count + 1

		END AS rank
		FROM student_quiz_result where quiz_id ='$cat' and user_id  in
        (select username  from subscription_details 
        where package_id = 57)
		ORDER BY physicsmark+chemistrymark+biologymark desc";
		
		}
		//print_r($sql);
        $query = $this->db->query($sql);

        $sql = "update student_quiz_result a inner join quiz_rank b
		on a.quiz_id = b.quiz_id
		set a.rank= b.rank
		where a.user_id = b.user_id
		and a.quiz_id = '$cat'";
        //    print_r($sql);
        $query = $this->db->query($sql);
		if ($batch=='all'){
				$sql = "SELECT a.*,(a.physicsmark+a.chemistrymark+a.biologymark) total ,concat(b.first_name,' ',b.last_name) name,b.rollno,b.batch,b.accommodation from student_quiz_result a,user_details b 
				where a.user_id collate utf8_general_ci = b.user_name collate utf8_general_ci and   a.quiz_id ='$cat'
					 ORDER BY CONVERT( a.rank, SIGNED INTEGER )";
		}
	

		if ($batch=='22'){
				$sql = "SELECT a.*,(a.physicsmark+a.chemistrymark+a.biologymark) total ,concat(b.first_name,' ',b.last_name) name, c.package_id,b.rollno,b.batch,b.accommodation
						from student_quiz_result a,user_details b,subscription_details c
						where a.user_id collate utf8_general_ci = b.user_name collate utf8_general_ci
						and c.username  = a.user_id and a.quiz_id ='$cat' and c.package_id='22'
					 ORDER BY CONVERT( a.rank, SIGNED INTEGER )";
		}
			if ($batch=='57'){
				$sql = "SELECT a.*,(a.physicsmark+a.chemistrymark+a.biologymark) total ,concat(b.first_name,' ',b.last_name) name, c.package_id,b.rollno,b.batch,b.accommodation
						from student_quiz_result a,user_details b,subscription_details c
						where a.user_id collate utf8_general_ci = b.user_name collate utf8_general_ci
						and c.username  = a.user_id and a.quiz_id ='$cat' and c.package_id='57'
					 ORDER BY CONVERT( a.rank, SIGNED INTEGER )";
		}

        //    print_r($sql);
        $query = $this->db->query($sql);
        try {
            if ($query->num_rows() > 0) {
                return $query->result_array();
            } else {
                return $query->result_array();
            }

        } catch (exception $e) {echo $e->getMessage();
        }

    }
public function upload_data()
    {
		$sql = "truncate table uploaded_csv_details";
        $query=$this->db->query($sql);
		
        $count=0;
        $fp = fopen($_FILES['userfile']['tmp_name'],'r') or die("can't open file");
        while($csv_line = fgetcsv($fp,1024))
        {
            $count++;
            if($count == 1)
            {
                continue;
            }//keep this if condition if you want to remove the first row
            for($i = 0, $j = count($csv_line); $i < $j; $i++)
            {
                $insert_csv = array();
                $insert_csv['first_name'] = $csv_line[0];//remove if you want to have primary key,
                $insert_csv['rollno'] = $csv_line[1];
                $insert_csv['batch'] = $csv_line[2];
                $insert_csv['user_name'] = $csv_line[3];
                $insert_csv['jnanasudhastandard'] = $csv_line[4];
                $insert_csv['actual_password'] = $csv_line[5];
                $insert_csv['phone'] = $csv_line[6];
                $insert_csv['email'] = $csv_line[7];
                $insert_csv['role_id'] = $csv_line[8];
            }
            $i++;
            $data = array(
                'first_name' => $insert_csv['first_name'] ,
                'rollno' => $insert_csv['rollno'],
                'batch' => $insert_csv['batch'],
                'user_name' => $insert_csv['user_name'],
                'jnanasudhastandard' => $insert_csv['jnanasudhastandard'],
                'actual_password' => $insert_csv['actual_password'],
                'phone' => $insert_csv['phone'],
                'email' => $insert_csv['email'],
                'role_id' => $insert_csv['role_id']
               );

            $data['csv_details']=$this->db->insert('uploaded_csv_details', $data);
        }
        fclose($fp) or die("can't close file");
        $data['success']="success";
        return $data;
    }
	public function insert_to_details()
{
$sql = "UPDATE uploaded_csv_details SET error_code='ERROR' ,error_msg='User Already Exists' WHERE user_name in ( select user_name collate utf8_general_ci from user_details)";
$query=$this->db->query($sql);

$sql = "UPDATE uploaded_csv_details SET error_code='ERROR' ,error_msg='email Already Exists' WHERE email in ( select email collate utf8_general_ci from user_details)";
$query=$this->db->query($sql);

$sql = "UPDATE uploaded_csv_details SET error_code='ERROR' ,error_msg='phone_no is not Proper' WHERE phone NOT REGEXP '[6-9]{1}[0-9]{9}' ";
$query=$this->db->query($sql);

$sql="INSERT INTO `user_details`(`org_id`, `user_name`, `password`, `actual_password`, `first_name`, `role_id`, `phone`, `email`, `batch`, `rollno`, `jnanasudhastandard`, `last_name`, `application`, `creation_date`, `modified_date`, `chapter_id`, `cluster_id`, `otp`,otp_confirmed)
select 1, `user_name`, md5(`actual_password`), `actual_password`, `first_name`, `role_id`, `phone`, `email`, `batch`, `rollno`, `jnanasudhastandard`,'','','','','','','','Y' from uploaded_csv_details where error_code!='ERROR'";
$query=$this->db->query($sql);

$sql="INSERT INTO `quiz_student`(`org_id`, `name`, `Father_name`, `Email`, `phone_no`, `user_name`, `role_no`, `subject_info`, `subject`, `class_code`, `status`)
select 1, `first_name`, '', `email`, `phone`, `user_name`, `rollno`,'','','',1 from uploaded_csv_details where error_code!='ERROR'";
$query=$this->db->query($sql);
//echo($sql);

}
public function upload_dataforsubscribe()
    {
		
		
		$sql = "truncate table  uploaded_csv_subscription_details ";
		$query=$this->db->query($sql);
        $count=0;
        $fp = fopen($_FILES['userfile']['tmp_name'],'r') or die("can't open file");
        while($csv_line = fgetcsv($fp,1024))
        {
            $count++;
            if($count == 1)
            {
                continue;
            }//keep this if condition if you want to remove the first row
            for($i = 0, $j = count($csv_line); $i < $j; $i++)
            {
                $insert_csv = array();
                $insert_csv['user_name'] = $csv_line[0];//remove if you want to have primary key,
                $insert_csv['package_id'] = $csv_line[1];
            }
            $i++;
            $data = array(
                'username' => $insert_csv['user_name'],
                'package_id' => $insert_csv['package_id']
               );
            $data['csv_details']=$this->db->insert('uploaded_csv_subscription_details', $data);
        }
        fclose($fp) or die("can't close file");
        $data['success']="success";
        return $data;
    }
	public function insert_to_subscription_detls()
	{	
		$sql = "UPDATE uploaded_csv_subscription_details SET error_code='ERROR' ,error_msg='User Not Exists' WHERE user_name Not in ( select user_name collate utf8_general_ci from user_details)";
		$query=$this->db->query($sql);
		
		$sql = "UPDATE uploaded_csv_subscription_details a SET a.error_code='ERROR' ,a.error_msg='Package Id Already Exists' WHERE a.package_id in ( select b.package_id from subscription_details b where b.user_id = a.user_id)";
		$query=$this->db->query($sql);	

		$sql ='insert into subscription_details (`org_id`,`name`,`phone_no`,`email`,`username`,
`password`,`package_id`,`package_name`,`package_amount`,`subscribed_on`,`end_date`,`coupon_no`)

select  distinct 1, a.first_name,a.user_name,a.email,a.user_name,a.password,b.package_id
,c.package_name,"2000",curdate(),date_add(curdate() , interval 1 year),
"123"  from user_details a,uploaded_csv_subscription_details b,package_info c where a.user_name collate utf8_general_ci =b.username
and b.package_id=c.id and b.error_code!="ERROR"';
//print_r($sql);
$query=$this->db->query($sql);	

	}
	public function uploadVideoData($url,$video_name)
	{
		$type = 'video';
		$sql = "INSERT INTO `Video_or_ppt_details`(`location`,`name`,`type`,`url` ) VALUES ('$url','$video_name','$type','')";
        $query = $this->db->query($sql);
	}
	public function VideoUploadData($url,$video_name)
	{
		$type = 'video';
		$sql = "INSERT INTO `Video_or_ppt_details`(`location`,`name`,`type`,`url` ) VALUES ('$url','$video_name','$type','')";
        $query = $this->db->query($sql);
	}
	public function upload_video_url()
	{
		$videourl = $this->input->post('videourl');
		$type = 'video Link';

		$sql = "INSERT INTO `Video_or_ppt_details`(`location`,`name`,`type`,`url` ) VALUES ('','','$type','$videourl')";
        $query = $this->db->query($sql);
	}
	public function uploadPptData($url,$ppt_name)
	{
		$type = 'ppt';
		$sql = "INSERT INTO `Video_or_ppt_details`(`location`,`name`,`type`,`url` ) VALUES ('$url','$ppt_name','$type','')";
        $query = $this->db->query($sql);
	}
	public function upload_ppt_url()
	{
		$ppt_url = $this->input->post('ppt_url');
		$type = 'ppt Link';

		$sql = "INSERT INTO `Video_or_ppt_details`(`location`,`name`,`type`,`url` ) VALUES ('','','$type','$ppt_url')";
        $query = $this->db->query($sql);
	}
	
	public function savequizpackagedetails($type)
	{
		$package_name = $type['package_name'];
		$package_amt = $type['package_amt'];
		$package_srtdate = $type['package_srtdate'];
		$package_enddate = $type['package_enddate'];
		$gst_amt = $type['gst_amt'];
	
		$sql = "INSERT INTO `package_info`(org_id,`id`, `package_name`,`price`,`start_date`,`end_date`,`cgst_amt`,`package_info`, `subject_name`) VALUES (1,'','$package_name','$package_amt','$package_srtdate','$package_enddate','$gst_amt','','')";
		//print_r($sql);
		
		$this->db->query($sql);
		$lastid = $this->db->insert_id();
		//print_R($lastid);
		return $lastid;

	}
	
	public function saveofflinepackageassignment($userid,$packageid)
	{
		
	
		$sql = "INSERT INTO `offline_assigned_package`(user_name,package_id,creation_time) VALUES ('$userid','$packageid',now())";
	//	print_r($sql);
		
		$this->db->query($sql);
		$lastid = $this->db->insert_id();
		//print_R($lastid);
		return $lastid;

	}
	
		public function saveofflinepackage($type)
	{
		$package_code = $type['package_code'];
		$package_name = $type['package_name'];
		$package_amt = $type['package_amt'];
		$package_srtdate = $type['package_srtdate'];
		$package_enddate = $type['package_enddate'];
		$cgst_amt = $type['cgst_amt'];
		$sgst_amt = $type['sgst_amt'];
		$price =$package_amt+$cgst_amt+$sgst_amt;
	
		$sql = "INSERT INTO `package_info_offline`(org_id,`id`,package_code, `package_name`,packageamount,`price`,`start_date`,`end_date`,`cgst_amt`,`sgst_amt`,`package_info`, `subject_name`) 
		VALUES (1,'','$package_code','$package_name','$package_amt','$price','$package_srtdate','$package_enddate','$cgst_amt','$sgst_amt','','')";
		//print_r($sql);
		
		$this->db->query($sql);
		$lastid = $this->db->insert_id();
		//print_R($lastid);
		return $lastid;

	}
	
	/*public function getofflinepackage(){
		
	
		$sql="SELECT * from package_info_offline";
		$query = $this->db->query($sql);

		if ($query->num_rows() == 1)
		{
			return $query->result_array();
		}
		elseif ($query->num_rows() > 1)
		{
			return $query->result_array(); //This returns an array of results which you can whatever you need with it
		}
		else 
		{
			return $query->result_array();
		}
	}*/
	
// 	public function getofflinepackage()
// {
//     $sql = "SELECT * FROM package_info_offline";
//     $query = db_query($sql);

//     $num_rows = $query->num_rows();

//     if ($num_rows == 1) {
//         return $query->result_array();
//     } elseif ($num_rows > 1) {
//         return $query->result_array();
//     } else {
//         return $query->result_array();
//     }
// }
 public function getoffline_package()
{
    $sql = "SELECT * FROM some_offline_users_table"; // replace with correct table
    $query = db_query($sql);

    if ($query) {
        return $query->fetch_all(MYSQLI_ASSOC);
    } else {
        return array();
    }
}


public function savepackagearraydetails($packagetypeid){
		$data=array();
		foreach ($_POST['subject_name'] as $key => $value){
		$data[]=array(
		   'package_id' => $packagetypeid,
		   'subject_name'=> $_POST['subject_name'][$key]
		);
	 }
		//print_r($data);
	$this->db->where('package_id=',$packagetypeid);
	//$this->db->order_by("order", "asc");
	$this->db->insert_batch('package_subject',$data);
	}
	
	public function findquizpackage() {
		$sql="SELECT DISTINCT package_name ,id FROM package_info";

		$query = $this->db->query($sql);
		// print_r($query);
		if ($query->num_rows() == 1)
		{
		return $query->result_array();
		}
		elseif ($query->num_rows() > 1)
		{
		return $query->result_array(); //This returns an array of results which you can whatever you need with it
		}
		else
		{
		return $query->result_array();
		}

	}
	public function quizsubjectname() {
		$package_id = $_POST['package_id'];
		$sql="Select subject_name FROM package_subject where package_id ='$package_id'";
		//print_r($sql);
		//die;
		$query = $this->db->query($sql);
		// print_r($query);
		if ($query->num_rows() == 1)
		{
		return $query->result_array();
		}
		elseif ($query->num_rows() > 1)
		{
		return $query->result_array(); //This returns an array of results which you can whatever you need with it
		}
		else
		{
		return $query->result_array();
		}

	}
	public function findquizpackagename() {
$sql="SELECT DISTINCT package_name ,id FROM package_info";

$query = $this->db->query($sql);
//print_r($query);
if ($query->num_rows() == 1)
{
return $query->result_array();
}
elseif ($query->num_rows() > 1)
{
return $query->result_array(); //This returns an array of results which you can whatever you need with it
}
else
{
return $query->result_array();
}

}

	public function findquizsubjectname($package_id) {

//$package_id = $_POST['package_id'];
$sql="Select subject_name FROM package_subject where id ='$package_id'";
//print_r($sql);
//die;
$query = $this->db->query($sql);
 //print_r($query->result_array());
if ($query->num_rows() == 1)
{
return $query->result_array();
}
elseif ($query->num_rows() > 1)
{
return $query->result_array(); //This returns an array of results which you can whatever you need with it
}
else
{
return $query->result_array();
}
}
	public function savepackagesyllabus()
	{
		//print_r($_POST);
		$pname = explode("_",trim($_POST['package_id']));
		$package_id = $pname[0];
		$package_name = $pname[1];
		//print_r($package_id);
		//print_r($package_name);
		//die;
		$subject_name = trim($_POST['subject_name']);
		$unit = trim($_POST['unit']);
		$test_type = trim($_POST['test_type']);
		$syllabus = trim($_POST['syllabus']);
		$quiz_id = trim($_POST['quiz_id']);
		$start_date = date_format(date_create($_POST['start_date']), 'Y-m-d');
		$end_date = date_format(date_create($_POST['end_date']), 'Y-m-d');
		
		if(($this->input->post('video_name')!= '')){
			$video = $_POST['video_name'];
		}else{
			//echo 'hi';
			$video = trim($_POST['video_url']);
		}
		if(($this->input->post('ppt_name')!= '')){
			$ppt = trim($_POST['ppt_name']);
		}else{
			//echo 'hi1';
			$ppt = trim($_POST['ppt_url']);
		}
		
	$sylQry = "SELECT id FROM syllabus_details WHERE trim(package_id)='$package_id'  and trim(subject_name)='$subject_name' and trim(unit) ='$unit'
		and trim(test_type)='$test_type'";
		$sylRw = $this->db->query($sylQry);
		//print_r($sylRw);
		//print_r($sylRw->num_rows());
		$sylRslt = $sylRw->row();
		if($sylRw->num_rows() > 0){
			$id = $sylRslt->id;
			//print_r($id);
			$sql = "UPDATE syllabus_details SET package_id='$package_id',package_name='$package_name',subject_name='$subject_name',unit ='$unit',
			test_type='$test_type',syllabus='$syllabus',video='$video',ppt='$ppt',start_date='$start_date',end_date='$end_date',quiz_id='$quiz_id' WHERE id = '$id'";
		}else{
			$sql="INSERT INTO `syllabus_details`(`quiz_id`,`package_id`,`package_name`,`subject_name`,`unit`,`test_type`,`syllabus`,`video`,`ppt`,`start_date`,`end_date`) 
			VALUES ('$quiz_id','$package_id','$package_name','$subject_name','$unit','$test_type','$syllabus','$video','$ppt','$start_date','$end_date')";
		}
		$query=$this->db->query($sql);
		//print_r($sql);
		
		log_message('debug',$sql);
		
		$rslt = $this->db->affected_rows();
		if($rslt > 0){
			log_message('debug','Data updated or inserted successfully');
		}else{
			log_message('debug','Error updating or insering data');
		}
		
		return $rslt;
		
	}
	public function insertnewquizpackage()
	{
		//print_r($_POST);
		$qname = explode("_",$_POST['quiz_name']);
		$quiz_id = $qname[0];
		$quiz_name = $qname[1];
		$package_id = $_POST['pack_id'];
		$start_date = $_POST['start_date'];
		$end_date = $_POST['end_date'];
		
		//if($quiz_id){}else{}
		
		$sql="INSERT INTO `quiz_package_link`(`quiz_id`, `quiz_name`,`package_id`,`start_date`,`end_date`) 
		VALUES ('$quiz_id','$quiz_name','$package_id','$start_date','$end_date')";
		$query=$this->db->query($sql);
		
	}
	
	public function delete_details($quiz_id)
	{
		$sql="DELETE FROM `quiz_package_link` WHERE quiz_id='$quiz_id'";
		$this->db->query($sql);
	}
	
	public function get_jjut_consol_rank($batch,$sub)
    {
        //drop table all_jjut_rank;
        $sql = "truncate table all_jjut_rank";
        $query = $this->db->query($sql);
		
		if ($sub == 'all')
		{
			$subs= 'physicsmark+chemistrymark+biologymark';
		}
		
		if ($sub == 'physics')
		{
			$subs= 'physicsmark';
		}
		if ($sub == 'chemistry')
		{
			$subs= 'chemistrymark';
		}
		if ($sub == 'biology')
		{
			$subs= 'biologymark';
		}
		
		
		
if ($batch=='all' && $sub =='all'){
        $sql = "insert into  all_jjut_rank
select user_id,
max(case when quiz_id ='346' THEN ".$subs." END ) 'JJUT01',
max(case when quiz_id ='347' THEN ".$subs." END ) 'JJUT02',
max(case when quiz_id ='348' THEN ".$subs." END ) 'JJUT03',
max(case when quiz_id ='352' THEN ".$subs." END ) 'JJUT04',
max(case when quiz_id ='353' THEN ".$subs." END ) 'JJUT05',
max(case when quiz_id ='355' THEN ".$subs." END ) 'JJUT06',
max(case when quiz_id ='356' THEN ".$subs." END ) 'JJUT07',
max(case when quiz_id ='357' THEN ".$subs." END ) 'JJUT08',
max(case when quiz_id ='358' THEN ".$subs." END ) 'JJUT09',
max(case when quiz_id ='359' THEN ".$subs." END ) 'JJUT10',
max(case when quiz_id ='369' THEN ".$subs." END ) 'JJUT11',
max(case when quiz_id ='388' THEN ".$subs." END ) 'JJUT12',
max(case when quiz_id ='370' THEN ".$subs." END ) 'JJUT13',
max(case when quiz_id ='371' THEN ".$subs." END ) 'JJUT14',
max(case when quiz_id ='372' THEN ".$subs." END ) 'JJUT15',
max(case when quiz_id ='387' THEN ".$subs." END ) 'JJUT16',
max(case when quiz_id ='373' THEN ".$subs." END ) 'JJUT17',
max(case when quiz_id ='374' THEN ".$subs." END ) 'JJUT18',
max(case when quiz_id ='415' THEN ".$subs." END ) 'JJUT19',
max(case when quiz_id ='416' THEN ".$subs." END ) 'JJUT20',
max(case when quiz_id ='417' THEN ".$subs." END ) 'JJUT21',
max(case when quiz_id ='418' THEN ".$subs." END ) 'JJUT22',
max(case when quiz_id ='419' THEN ".$subs." END ) 'JJUT23',
max(case when quiz_id ='427' THEN ".$subs." END ) 'JJUT24',
max(case when quiz_id ='428' THEN ".$subs." END ) 'JJUT25',
max(case when quiz_id ='429' THEN ".$subs." END ) 'JJUT26',
max(case when quiz_id ='430' THEN ".$subs." END ) 'JJUT27',
max(case when quiz_id ='431' THEN ".$subs." END ) 'JJUT28',
max(case when quiz_id ='440' THEN ".$subs." END ) 'JJUT29',
max(case when quiz_id ='441' THEN ".$subs." END ) 'JJUT30',
max(case when quiz_id ='442' THEN ".$subs." END ) 'JJUT31',
max(case when quiz_id ='443' THEN ".$subs." END ) 'JJUT32',
max(case when quiz_id ='448' THEN ".$subs." END ) 'JJUT33',
max(case when quiz_id ='449' THEN ".$subs." END ) 'JJUT34',
max(case when quiz_id ='450' THEN ".$subs." END ) 'JJUT35',
max(case when quiz_id ='451' THEN ".$subs." END ) 'JJUT36',
max(case when quiz_id ='452' THEN ".$subs." END ) 'JJUT37',
max(case when quiz_id ='453' THEN ".$subs." END ) 'JJUT38',
max(case when quiz_id ='454' THEN ".$subs." END ) 'JJUT39',
max(case when quiz_id ='458' THEN ".$subs." END ) 'JJUT40',
max(case when quiz_id ='588' THEN ".$subs." END ) 'JJUT41',
max(case when quiz_id ='589' THEN ".$subs." END ) 'JJUT42',
max(case when quiz_id ='591' THEN ".$subs." END ) 'JJUT43',
max(case when quiz_id ='592' THEN ".$subs." END ) 'JJUT44',
max(case when quiz_id ='593' THEN ".$subs." END ) 'JJUT45',
max(case when quiz_id ='599' THEN ".$subs." END ) 'JJUT46',
max(case when quiz_id ='601' THEN ".$subs." END ) 'JJUT47',
max(case when quiz_id ='616' THEN ".$subs." END ) 'JJUT48',
max(case when quiz_id ='617' THEN ".$subs." END ) 'JJUT49',
max(case when quiz_id ='618' THEN ".$subs." END ) 'JJUT50',
max(case when quiz_id ='623' THEN ".$subs." END ) 'JJUT51',
max(case when quiz_id ='624' THEN ".$subs." END ) 'JJUT52',
max(case when quiz_id ='625' THEN ".$subs." END ) 'JJUT53',
max(case when quiz_id ='626' THEN ".$subs." END ) 'JJUT54',
max(case when quiz_id ='627' THEN ".$subs." END ) 'JJUT55',
max(case when quiz_id ='628' THEN ".$subs." END ) 'JJUT56',
max(case when quiz_id ='629' THEN ".$subs." END ) 'JJUT57',
max(case when quiz_id ='630' THEN ".$subs." END ) 'JJUT58',
max(case when quiz_id ='631' THEN ".$subs." END ) 'JJUT59',
max(case when quiz_id ='634' THEN ".$subs." END ) 'JJUT60',
max(case when quiz_id ='637' THEN ".$subs." END ) 'JJUT61',
max(case when quiz_id ='638' THEN ".$subs." END ) 'JJUT62',
max(case when quiz_id ='639' THEN ".$subs." END ) 'JJUT63',
max(case when quiz_id ='640' THEN ".$subs." END ) 'JJUT64',
max(case when quiz_id ='641' THEN ".$subs." END ) 'JJUT65',
max(case when quiz_id ='642' THEN ".$subs." END ) 'JJUT66',
max(case when quiz_id ='643' THEN ".$subs." END ) 'JJUT67',
max(case when quiz_id ='644' THEN ".$subs." END ) 'JJUT68',
max(case when quiz_id ='645' THEN ".$subs." END ) 'JJUT69',
max(case when quiz_id ='646' THEN ".$subs." END ) 'JJUT70',
max(case when quiz_id ='656' THEN ".$subs." END ) 'JJUT71',
max(case when quiz_id ='657' THEN ".$subs." END ) 'JJUT72',
max(case when quiz_id ='660' THEN ".$subs." END ) 'JJUT73',
max(case when quiz_id ='663' THEN ".$subs." END ) 'JJUT74',
max(case when quiz_id ='665' THEN ".$subs." END ) 'JJUT75',
max(case when quiz_id ='666' THEN ".$subs." END ) 'JJUT76',
max(case when quiz_id ='667' THEN ".$subs." END ) 'JJUT77',
max(case when quiz_id ='668' THEN ".$subs." END ) 'JJUT78',
max(case when quiz_id ='669' THEN ".$subs." END ) 'JJUT79',
max(case when quiz_id ='670' THEN ".$subs." END ) 'JJUT80',


ifnull(max(case when quiz_id ='346' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='347' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='348' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='352' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='353' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='355' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='356' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='357' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='358' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='359' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='369' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='388' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='370' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='371' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='372' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='387' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='373' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='374' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='415' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='416' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='417' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='418' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='419' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='427' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='428' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='429' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='430' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='431' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='440' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='441' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='442' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='443' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='448' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='449' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='450' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='451' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='452' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='453' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='454' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='458' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='588' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='589' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='591' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='592' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='593' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='599' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='601' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='616' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='617' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='618' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='623' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='624' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='625' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='626' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='627' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='628' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='629' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='630' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='631' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='634' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='637' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='638' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='639' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='640' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='641' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='642' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='643' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='644' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='645' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='646' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='656' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='657' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='660' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='663' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='665' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='666' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='667' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='668' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='669' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='670' THEN ".$subs." END ),0)  total

FROM student_quiz_result a , quiz_info b where a.quiz_id = b.id and b.quiztype='JUT' GROUP BY user_id";
$query = $this->db->query($sql);
//print_r($sql);
}
else
{
	
	$sql = "insert into  all_jjut_rank
select user_id,
max(case when quiz_id ='346' THEN ".$subs." END ) 'JJUT01',
max(case when quiz_id ='347' THEN ".$subs." END ) 'JJUT02',
max(case when quiz_id ='348' THEN ".$subs." END ) 'JJUT03',
max(case when quiz_id ='352' THEN ".$subs." END ) 'JJUT04',
max(case when quiz_id ='353' THEN ".$subs." END ) 'JJUT05',
max(case when quiz_id ='355' THEN ".$subs." END ) 'JJUT06',
max(case when quiz_id ='356' THEN ".$subs." END ) 'JJUT07',
max(case when quiz_id ='357' THEN ".$subs." END ) 'JJUT08',
max(case when quiz_id ='358' THEN ".$subs." END ) 'JJUT09',
max(case when quiz_id ='359' THEN ".$subs." END ) 'JJUT10',
max(case when quiz_id ='369' THEN ".$subs." END ) 'JJUT11',
max(case when quiz_id ='388' THEN ".$subs." END ) 'JJUT12',
max(case when quiz_id ='370' THEN ".$subs." END ) 'JJUT13',
max(case when quiz_id ='371' THEN ".$subs." END ) 'JJUT14',
max(case when quiz_id ='372' THEN ".$subs." END ) 'JJUT15',
max(case when quiz_id ='387' THEN ".$subs." END ) 'JJUT16',
max(case when quiz_id ='373' THEN ".$subs." END ) 'JJUT17',
max(case when quiz_id ='374' THEN ".$subs." END ) 'JJUT18',
max(case when quiz_id ='415' THEN ".$subs." END ) 'JJUT19',
max(case when quiz_id ='416' THEN ".$subs." END ) 'JJUT20',
max(case when quiz_id ='417' THEN ".$subs." END ) 'JJUT21',
max(case when quiz_id ='418' THEN ".$subs." END ) 'JJUT22',
max(case when quiz_id ='419' THEN ".$subs." END ) 'JJUT23',
max(case when quiz_id ='427' THEN ".$subs." END ) 'JJUT24',
max(case when quiz_id ='428' THEN ".$subs." END ) 'JJUT25',
max(case when quiz_id ='429' THEN ".$subs." END ) 'JJUT26',
max(case when quiz_id ='430' THEN ".$subs." END ) 'JJUT27',
max(case when quiz_id ='431' THEN ".$subs." END ) 'JJUT28',
max(case when quiz_id ='440' THEN ".$subs." END ) 'JJUT29',
max(case when quiz_id ='441' THEN ".$subs." END ) 'JJUT30',
max(case when quiz_id ='442' THEN ".$subs." END ) 'JJUT31',
max(case when quiz_id ='443' THEN ".$subs." END ) 'JJUT32',
max(case when quiz_id ='448' THEN ".$subs." END ) 'JJUT33',
max(case when quiz_id ='449' THEN ".$subs." END ) 'JJUT34',
max(case when quiz_id ='450' THEN ".$subs." END ) 'JJUT35',
max(case when quiz_id ='451' THEN ".$subs." END ) 'JJUT36',
max(case when quiz_id ='452' THEN ".$subs." END ) 'JJUT37',
max(case when quiz_id ='453' THEN ".$subs." END ) 'JJUT38',
max(case when quiz_id ='454' THEN ".$subs." END ) 'JJUT39',
max(case when quiz_id ='458' THEN ".$subs." END ) 'JJUT40',
max(case when quiz_id ='588' THEN ".$subs." END ) 'JJUT41',
max(case when quiz_id ='589' THEN ".$subs." END ) 'JJUT42',
max(case when quiz_id ='591' THEN ".$subs." END ) 'JJUT43',
max(case when quiz_id ='592' THEN ".$subs." END ) 'JJUT44',
max(case when quiz_id ='593' THEN ".$subs." END ) 'JJUT45',
max(case when quiz_id ='599' THEN ".$subs." END ) 'JJUT46',
max(case when quiz_id ='601' THEN ".$subs." END ) 'JJUT47',
max(case when quiz_id ='616' THEN ".$subs." END ) 'JJUT48',
max(case when quiz_id ='617' THEN ".$subs." END ) 'JJUT49',
max(case when quiz_id ='618' THEN ".$subs." END ) 'JJUT50',
max(case when quiz_id ='623' THEN ".$subs." END ) 'JJUT51',
max(case when quiz_id ='624' THEN ".$subs." END ) 'JJUT52',
max(case when quiz_id ='625' THEN ".$subs." END ) 'JJUT53',
max(case when quiz_id ='626' THEN ".$subs." END ) 'JJUT54',
max(case when quiz_id ='627' THEN ".$subs." END ) 'JJUT55',
max(case when quiz_id ='628' THEN ".$subs." END ) 'JJUT56',
max(case when quiz_id ='629' THEN ".$subs." END ) 'JJUT57',
max(case when quiz_id ='630' THEN ".$subs." END ) 'JJUT58',
max(case when quiz_id ='631' THEN ".$subs." END ) 'JJUT59',
max(case when quiz_id ='634' THEN ".$subs." END ) 'JJUT60',
max(case when quiz_id ='637' THEN ".$subs." END ) 'JJUT61',
max(case when quiz_id ='638' THEN ".$subs." END ) 'JJUT62',
max(case when quiz_id ='639' THEN ".$subs." END ) 'JJUT63',
max(case when quiz_id ='640' THEN ".$subs." END ) 'JJUT64',
max(case when quiz_id ='641' THEN ".$subs." END ) 'JJUT65',
max(case when quiz_id ='642' THEN ".$subs." END ) 'JJUT66',
max(case when quiz_id ='643' THEN ".$subs." END ) 'JJUT67',
max(case when quiz_id ='644' THEN ".$subs." END ) 'JJUT68',
max(case when quiz_id ='645' THEN ".$subs." END ) 'JJUT69',
max(case when quiz_id ='646' THEN ".$subs." END ) 'JJUT70',
max(case when quiz_id ='656' THEN ".$subs." END ) 'JJUT71',
max(case when quiz_id ='657' THEN ".$subs." END ) 'JJUT72',
max(case when quiz_id ='660' THEN ".$subs." END ) 'JJUT73',
max(case when quiz_id ='663' THEN ".$subs." END ) 'JJUT74',
max(case when quiz_id ='665' THEN ".$subs." END ) 'JJUT75',
max(case when quiz_id ='666' THEN ".$subs." END ) 'JJUT76',
max(case when quiz_id ='667' THEN ".$subs." END ) 'JJUT77',
max(case when quiz_id ='668' THEN ".$subs." END ) 'JJUT78',
max(case when quiz_id ='669' THEN ".$subs." END ) 'JJUT79',
max(case when quiz_id ='670' THEN ".$subs." END ) 'JJUT80',


ifnull(max(case when quiz_id ='346' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='347' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='348' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='352' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='353' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='355' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='356' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='357' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='358' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='359' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='369' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='388' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='370' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='371' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='372' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='387' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='373' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='374' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='415' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='416' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='417' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='418' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='419' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='427' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='428' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='429' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='430' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='431' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='440' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='441' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='442' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='443' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='448' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='449' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='450' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='451' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='452' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='453' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='454' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='458' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='588' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='589' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='591' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='592' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='593' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='599' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='601' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='616' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='617' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='618' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='623' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='624' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='625' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='626' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='627' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='628' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='629' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='630' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='631' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='634' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='637' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='638' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='639' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='640' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='641' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='642' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='643' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='644' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='645' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='646' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='656' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='657' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='660' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='663' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='665' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='666' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='667' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='668' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='669' THEN ".$subs." END ),0) +
ifnull(max(case when quiz_id ='670' THEN ".$subs." END ),0)  total

FROM student_quiz_result a , quiz_info b where a.quiz_id = b.id and b.quiztype='JUT' and 
user_id  in
				(select USERNAME  from subscription_details 
				where package_id = '$batch') GROUP BY user_id";
$query = $this->db->query($sql);
	

}


 //print_r($sql);
        $sql = "truncate table consol_jjut_rank";
        $query = $this->db->query($sql);
        $sql = "SET @prev_value = NULL";
        $query = $this->db->query($sql);
        $sql = "SET @rank_count = 0";
        $query = $this->db->query($sql);
        $sql = "truncate table consol_jjut_rank";
        $query = $this->db->query($sql);
        $sql = "insert into  consol_jjut_rank
select *, CASE
WHEN @prev_value = total THEN @rank_count
WHEN @prev_value := total THEN @rank_count := @rank_count + 1

END AS rank
FROM all_jjut_rank

ORDER BY total desc";

        $query = $this->db->query($sql);

        $sql = "SELECT a.* ,concat(b.first_name,' ',b.last_name) name,b.batch,b.rollno,b.accommodation from consol_jjut_rank a,user_details b  where a.user_id collate utf8_general_ci = b.user_name collate utf8_general_ci
ORDER BY CONVERT( a.rank, SIGNED INTEGER )";
         //print_r($sql);
        $query = $this->db->query($sql);

        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return $query->result_array();
        }

  }

public function updateuserstatus()
	{
		$user_id=$this->input->post('user_id');
		$user_name=$this->input->post('user_name');
		$status=$this->input->post('status');
		
		
		$sql="UPDATE `user_details` SET `user_status`='$status' WHERE user_name='$user_name' and user_id='$user_id'" ;
		//print_r($sql);
		$query = $this->db->query($sql);
	}

public function getallkvpyrank($cat,$batch)
{
	
	$this->load->library('nativesession');
		
		$sql="truncate table student_rank_report_kvpy";
   $query = $this->db->query($sql);
		
		$sql="insert into student_rank_report_kvpy(user_id,quiz_id,st_id) select distinct user_id,quiz_id,st_id from 
  student_quiz_result_kvpy where quiz_id ='$cat'";
   $query = $this->db->query($sql);

        $sql = "update student_rank_report_kvpy a inner join student_quiz_result_kvpy b
on a.quiz_id = b.quiz_id
set a.maths1marks =b.mathsmark
, a.physics1mark= b.physicsmark
, a.chemistry1marks= b.chemistrymark
, a.biology1marks= b.biologymark
where a.quiz_id = '$cat'
and a.user_id=b.user_id
and part =1 ";
        $query = $this->db->query($sql);
		//print_r($sql);
		
  $sql = "update student_rank_report_kvpy a inner join student_quiz_result_kvpy b 
on a.quiz_id = b.quiz_id set a.maths2marks =b.mathsmark , a.physics2marks= b.physicsmark , 
a.chemistry2marks= b.chemistrymark , a.biology2mark= b.biologymark 
where a.quiz_id = '$cat'
and a.user_id=b.user_id
and part =2 ";
//print_r($sql);
        $query = $this->db->query($sql);		
		
		$sql="select a.* ,sum(maths1marks+physics1mark+chemistry1marks+biology1marks)-least(maths1marks,physics1mark,chemistry1marks,biology1marks) part1total,b.rollno,b.batch,b.accommodation,
		concat(b.first_name,' ',b.last_name) name
		from  student_rank_report_kvpy a,user_details b 
				where a.user_id collate utf8_general_ci = b.user_name collate utf8_general_ci group by a.user_id";
		//print_r($sql);
		//b.rollno,b.batch,b.accommodation from student_quiz_result a,user_details b 
			//	where a.user_id collate utf8_general_ci = b.user_name collate utf8_general_ci
		   $query = $this->db->query($sql);
        try {
            if ($query->num_rows() > 0) {
                return $query->result_array();
            } else {
                return $query->result_array();
            }

        } catch (exception $e) {echo $e->getMessage();
        }
		
}
public function getstid($quiz_id,$user_name)
	{
		$sql = "select distinct st_id from student_final_answer where user_id = '$user_name' and quiz_id = '$quiz_id'";
        //print_r($sql);
        $query = $this->db->query($sql);
		$id = $query->row();
		return $id->st_id;// will echo only id one time
        
	}
	public function get_stud_package($user_name)
	{
		$sql="SELECT * FROM subscription_details where username='$user_name'";
		$query = $this->db->query($sql);

		if ($query->num_rows() == 1)
		{
			return $query->result_array();
		}
		elseif ($query->num_rows() > 1)
		{
			return $query->result_array(); //This returns an array of results which you can whatever you need with it
		}
		else 
		{
			return $query->result_array();
		}
	}
	public function delete_subpackagedet($user_name,$package_id)
	{
		$sql="DELETE FROM `subscription_details` WHERE username='$user_name' and package_id ='$package_id'";
		//print_r($sql);
		$this->db->query($sql);
	}
	public function insertorupdatenewpackage()
	{
		$name = $_POST['name'];
		$email = $_POST['email'];
		$username = $_POST['username'];
		$packageid = $_POST['package_id'];
		$packagename = $_POST['package_name'];
		$packageamt = $_POST['packageamt'];
		
		$sql = "select count(*) count from subscription_details where username = '$username' and  package_id = '$packageid' ";

        $query = $this->db->query($sql);
        $result = $query->row();

        if ($result->count < 1) {

            $sql = "insert into subscription_details(name,email,phone_no,username,package_name,package_id,package_amount) VALUES 
			('$name','$email','$username','$username','$packagename','$packageid','')";
            $query = $this->db->query($sql);
        } else {
            $sql = "update subscription_details set package_name='$packagename',package_id='$packageid'
			where username = '$username' and package_id = '$packageid'";
			//print_r($sql);
            $query = $this->db->query($sql);
        }
		
	}
	public function findquizpgname() {
		$package_id = $_POST['package_id'];
		$sql="Select package_name FROM package_info where id ='$package_id'";
		$query = $this->db->query($sql);
		if ($query->num_rows() == 1)
		{
		return $query->result_array();
		}
		elseif ($query->num_rows() > 1)
		{
		return $query->result_array(); //This returns an array of results which you can whatever you need with it
		}
		else
		{
		return $query->result_array();
		}

	}
		
	/***** duplicate quiz update *****/
    public function insertorupdateresultForquiz($quiz_id,$user_name,$st_id)
	{
		$insert_stored_proc = "CALL insert_student_quiz_result(?, ?, ?)";
		$data = array('userid' => $user_name, 'stid' => $st_id, 'quizid' => $quiz_id);
		//print_r($data);
		$result = $this->db->query($insert_stored_proc,$data);
		//print_r($result);
        if ($result !== NULL) {
            return TRUE;
        }
        return FALSE;
	}
	
	public function getQuizResult($user_id,$quiz_id){
		//echo 'hello';
		$quesQry = "SELECT id,user_id,(select Quiz_name from quiz_info where id='$quiz_id') as quiz_name,quiz_id,physicsmark,chemistrymark,
		biologymark,(select first_name from user_details where user_name='$user_id') as user_name,st_id FROM student_quiz_result
		WHERE user_id='$user_id' AND quiz_id='$quiz_id'";
		//print_r($quesQry);
		$quesRw = $this->db->query($quesQry);
		$rslt = $quesRw->result_array();
		// if(sizeof($qusRslt) && is_array($qusRslt)){
			 // $rslt = $qusRslt;
		// }else{
			// $rslt="No data found for provided criteria";
		// }
		
		return $rslt;
	}
	
	public function deleteDuplicate($user_id,$quiz_id,$id,$st_id){
		try{
			log_message('info','Admin_model.deleteDuplicate()');
			
			$quizQry = "INSERT INTO student_quiz_result_deleted SELECT * FROM student_quiz_result where id='$id' and st_id='$st_id'";
			$quizRslt = $this->db->query($quizQry);
			$quizRw = $this->db->affected_rows();
			if($quizRw > 0){
				$pckgQry = "SELECT package_id from quiz_package_link where quiz_id='$quiz_id'";
				$pckgRslt = $this->db->query($pckgQry);
				$pckgRw = $pckgRslt->row();
				$package_id = $pckgRw->package_id;
				
				log_message('debug',$package_id);
				
				if($package_id == '41' || $package_id == '42' || $package_id == '47' || $package_id == '48' || $package_id == '49' || $package_id == '50' || $package_id == '54'){
					$quizQry = "SELECT id FROM student_final_answer where st_id='$st_id' and user_id= '$user_id' and quiz_id='$quiz_id' ORDER BY id asc LIMIT 180";
				}else if($package_id == '44' || $package_id == '45'){
					$quizQry = "SELECT id FROM student_final_answer where st_id='$st_id' and user_id= '$user_id' and quiz_id='$quiz_id' ORDER BY id asc LIMIT 75";
				}else if($package_id == '53'){
					$quizQry = "SELECT id FROM student_final_answer where st_id='$st_id' and user_id= '$user_id' and quiz_id='$quiz_id' ORDER BY id asc LIMIT 120";
				}
				
				log_message('debug',$quizQry);
				$quizRslt = $this->db->query($quizQry);
				$quizRw = $quizRslt->result();
				log_message('debug',$quizRw);
				$output ="";

				foreach ($quizRw as $row) {
					$output .= $row->id . ",";
				}
				
				$quiz_ansId = rtrim($output, ",");
				log_message('debug',$quiz_ansId);
				$quizAnsQry = "INSERT INTO student_final_answer_deleted SELECT * FROM student_final_answer WHERE st_id='$st_id' and user_id= '$user_id' and id not in ($quiz_ansId)";
				$quizAnsRslt = $this->db->query($quizAnsQry);
				$quizAnsRw = $this->db->affected_rows();
				
				log_message('debug',$quizAnsQry);
				log_message('debug',$quizAnsRw);
				
				if($quizAnsRw > 0){
					$quizQry = "DELETE from student_quiz_result where id='$id' and st_id='$st_id'";
					$quizRslt = $this->db->query($quizQry);
					$quizRw = $this->db->affected_rows();
					
					if($quizRw > 0){
						$quizansQry = "DELETE FROM student_final_answer WHERE st_id='$st_id' and user_id= '$user_id' and id not in ($quiz_ansId)";
						$quizRslt = $this->db->query($quizansQry);
						$rslt = $this->db->affected_rows();
						if($rslt > 0){
							log_message('info','Duplicate data deleted from student_quiz_result  and student_final_answer for user id:' .$user_id);
						}else{
							log_message('info','Could not delete data from student_quiz_result  and student_final_answer for user id:' .$user_id);
						}
					}else {
						log_message('info','Could not delete data from student_quiz_result for user id:' .$user_id.' and st_id: '.$st_id);
					}
				}else{
					log_message('info','Data could not be inserted to student_final_answer_deleted for user id: '.$user_id);
				}
				
				
			}else{
				log_message('info','Data could not be inserted to student_quiz_result_deleted for user id: '.$user_id.' and st_id: '.$st_id);
			}
			return $rslt;
		}catch (exception $e)
	 	 {
			 echo $e->getMessage();
	 	 }
	}
	
	/*public function truncate_question_upload(){
		try{
			$upldQry = "TRUNCATE TABLE question_upload";
			//$upldRslt = $this->db->query($upldQry);
			log_message('debug',$upldQry);
			return true;
		}catch (exception $e)
	 	 {
			 echo $e->getMessage();
	 	 }
	}*/
	public function truncate_question_upload()
{
    try {
        $upldQry = "TRUNCATE TABLE question_upload";
        $upldRslt = db_query($upldQry);   // capture result like $this->db->query()
        log_message('debug', $upldQry);
        return $upldRslt;                 // return result instead of true
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}
/*
	public function getQuestionUpload(){
		try{
			$upldQry = "SELECT * FROM question_upload";
			$upldRslt = $this->db->query($upldQry);
			$rslt = $upldRslt->result_array();
			
	        if ($upldRslt->num_rows() > 0) {
	           log_message('info', 'Successfully retrieved question data!');
	        } else {
	          log_message('info', 'No questions data found!');
	        }
            return $rslt;
		}catch (exception $e)
	 	 {
			 echo $e->getMessage();
	 	 }
	}*/
	public function getQuestionUpload()
{
    try {
        $upldQry = "SELECT * FROM question_upload";
        $upldRslt = db_query($upldQry);  // Use helper
        $rslt = $upldRslt->result_array();  // Get results

        if ($upldRslt->num_rows() > 0) {
            log_message('info', 'Successfully retrieved question data!');
        } else {
            log_message('info', 'No questions data found!');
        }

        return $rslt;
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}

	public function get_user($uploaded_date){
		$rslt = array();
		try{
			log_message('info','Admin_model.get_user()');
			
			$upld_date = date_format(date_create($uploaded_date), 'Y-m-d');
			
			$usrQry = "SELECT user_name,password,first_name,last_name,actual_password,creation_date,phone,email 
						FROM user_details 
						WHERE DATE(creation_date) = '$upld_date'";
						
			log_message('debug',$usrQry);
			
			$usrRslt = $this->db->query($usrQry);
			$rslt = $usrRslt->result_array();
			if ( is_array($rslt) && (sizeof($rslt) > 0) ) {
	        	log_message('info', 'user data found for the date'.$upld_date);
	        } else {
	        	//$rslt = 'user data not found for the date'.$upld_date;
	        	log_message('info', 'user data not found for the date'.$upld_date);
	        } 
	        ////print_r($rslt);
	        return $rslt;
			
		}catch (exception $e)
	 	 {
			 echo $e->getMessage();
	 	 }
		
	}
	
	public function findallenquirydetails()
{
$sql = "SELECT * from quiz_puc_enquiry";
// print_r($sql);
$query = $this->db->query($sql);

if ($query->num_rows() == 1)
{
return $query->row();
}
elseif ($query->num_rows() > 1)
{
   return $query->result_array(); //This returns an array of results which you can whatever you need with it
}
else
{
return $query->result_array();
}
}


public function findallfoundationdetails()
{
$sql = "select * from user_details a,student_information b where b.education_stream='foundation'
and a.user_name=b.user_name ";
// print_r($sql);
$query = $this->db->query($sql);

if ($query->num_rows() == 1)
{
return $query->row();
}
elseif ($query->num_rows() > 1)
{
   return $query->result_array(); //This returns an array of results which you can whatever you need with it
}
else
{
return $query->result_array();
}
}

public function findallcommercedetails($frmdate,$todate)
{
$sql = "select * from user_details a,student_information b where b.education_stream in ('EBAS','BBAS','COMMERCE') 
and a.user_name=b.user_name and date(a.creation_date) between '$frmdate' and '$todate' ";
 //print_r($sql);
$query = $this->db->query($sql);

if ($query->num_rows() == 1)
{
return $query->result_array();
}
elseif ($query->num_rows() > 1)
{
   return $query->result_array(); //This returns an array of results which you can whatever you need with it
}
else
{
return $query->result_array();
}
}


public function save_college()
	{
	   $college_name=$this->input->post('college_name');
	   $college_code=$this->input->post('college_code');
	   $sql=" INSERT INTO colleges(id,college_name,college_code) VALUES
	   ('','$college_name','$college_code')";
	   
	   print_r( $sql);
	   $this->db->query($sql);
	}
	
public function save_batch()
	{
	   $college_code=$this->input->post('college_code');
	   $batch=$this->input->post('batch');
	   $sql=" INSERT INTO batch(id,college_code,batch_name) VALUES
	   ('','$college_code','$batch')";
	   $this->db->query($sql);
	}
	
	
	public function getallcolleges()
{
$sql = "select * from colleges";
 //print_r($sql);
$query = $this->db->query($sql);

if ($query->num_rows() == 1)
{
return $query->result_array();
}
elseif ($query->num_rows() > 1)
{
   return $query->result_array(); //This returns an array of results which you can whatever you need with it
}
else
{
return $query->result_array();
}
}


public function getallbatch()
{
$sql = "select * from batch ";
 //print_r($sql);
$query = $this->db->query($sql);

if ($query->num_rows() == 1)
{
return $query->result_array();
}
elseif ($query->num_rows() > 1)
{
   return $query->result_array(); //This returns an array of results which you can whatever you need with it
}
else
{
return $query->result_array();
}
}


public function getallbatchstaff($teacher)
{
$sql = "select distinct batch_name from student_feedback_params where batch_name not in ('23-KJS NEET DTB','23-UJS NEET DTB','23-KJS JEE DTB','23-UJS JEE DTB','23-KJS KCET DTB','23-UJS KCET DTB','23-KJS NEET WTB','23-UJS NEET WTB','23-KJS KCET WTB','23-UJS KCET WTB','23-KJS NEET NLT','23-MJS NEET NLT','KJS_PCMB_STATE','24 B1','24 B2','24 B3','25 B4','24 B5','24 B6','24 C1','24 C2','24 C3','24 COM','24 B6 (KAN)','24 B6 (SAN)','24 C1 (CS-KAN)','24 C1 (CS-HIN)','24 C1 (CS-SAN)','24 C1 (ST-KAN)','24 C1 (ST-HIN)','24 C1 (ST-SAN)','24 C2 (HIN)','24 C2 (SAN)','24 C3 (CS-KAN)','24 C3 (CS-HIN)','24 C3 (CS-SAN)','24 C3 (ST-KAN)','24 C3 (ST-HIN)','24 C3 (ST-SAN)','23 KCET WTB (CS-KAN)','23 KCET WTB (CS-HIN)','23 KCET WTB (CS-SAN)','23 KCET WTB (ST-KAN)','23 KCET WTB (ST-HIN)','23 KCET WTB (ST-SAN)','23 NEET WTB-01','23 NEET WTB-02 (HIN)','23 NEET WTB-02 (SAN)','MJS-24 B1 (HIN)','MJS-24 B1 (SAN)','MJS-24 C1 (HIN)','MJS-24 C1 (SAN)','MJS-24 B/CS (HIN)','MJS-24 B/CS (SAN)','24 EBAC (HIN)','24 EBAC (KAN)','24 EBAC (SAN)','24 EBAS (HIN)','24 EBAS (KAN)','24 EBAS (SAN)','23 BBAS (HIN)','23 BBAS (KAN)','23 BBAS (SAN)','23 EBAS (HIN)','23 EBAS (KAN)','23 EBAS (SAN)','MJS-24 B/CS (CS-HIN)','MJS-24 B/CS (CS-SAN)','UJS-24 B1 (KAN)','UJS-24 B1 (HIN)','UJS-24 B1 (SAN)','UJS-24 B2 (KAN)','UJS-24 B2 (HIN)','UJS-24 B2 (SAN)','UJS-24 C1 (KAN)','UJS-24 C1 (HIN)','UJS-24 C1 (SAN)','UJS-24 C2 (KAN)','UJS-24 C2 (SAN)','KJS-24 N1','KJS-24 N2','KJS-24 N3','KJS-24 N4','KJS-24 N5','KJS-24 N6','KJS-24 J1 CS','KJS-24 J1 ST','KJS-24 J2','KJS-24 J3 CS','KJS-24 J3 ST','UJS-24 N1','UJS-24 N2','UJS-24 J1','UJS-24 J2','MJS-24 N1','MJS-24 J1 (BIO)','MJS-24 J1 (CS)','MJS-24 J2','KJS-24 9TH-A','KJS-24 9TH-B','24-MJS-COM','KJS-24 EBAC','KJS-24 EBAS') " ; //where teachers='$teacher' ";
 //print_r($sql);
$query = $this->db->query($sql);

if ($query->num_rows() == 1)
{
return $query->result_array();
}
elseif ($query->num_rows() > 1)
{
   return $query->result_array(); //This returns an array of results which you can whatever you need with it
}
else
{
return $query->result_array();
}
}

public function getallsubject()
{
$sql = "select distinct(subject_name) from package_subject ";
 //print_r($sql);
$query = $this->db->query($sql);

if ($query->num_rows() == 1)
{
return $query->result_array();
}
elseif ($query->num_rows() > 1)
{
   return $query->result_array(); //This returns an array of results which you can whatever you need with it
}
else
{
return $query->result_array();
}
}


public function savequiz_feedback_param(){
		$data=array();
		
		$quiz_id = $_POST['quiz_name'];
		$batch_name = $_POST['batch_name'];
		$sql = "delete from student_feedback_params where quiz_id ='$quiz_id' and batch_name = '$batch_name'";
		$query = $this->db->query($sql);
		foreach ($_POST['subject_name'] as $key => $value){
		$data[]=array(
		
			'quiz_id' => $_POST['quiz_name'],
			'type'=> 'content',
			'batch_name'=> $_POST['batch_name'],
			'subject_name'=> $_POST['subject_name'][$key],
			'teachers'=> $_POST['teacher'][$key]
		);
		};
	foreach ($_POST['subject_names'] as $key => $value){
		$data[]=array(
		
			'quiz_id' => $_POST['quiz_name'],
			'type'=> 'discussion',
			'batch_name'=> $_POST['batch_name'],
			'subject_name'=> $_POST['subject_names'][$key],
			'teachers'=> $_POST['teachers'][$key]
		);
	 };
		//echo "</pre>";
	//	print_r($data);
		//echo "</pre>";
		//die;
	//$this->db->where('quiz_id=',$quiz_id);
	//$this->db->order_by("order", "asc");
	$this->db->insert_batch('student_feedback_params',$data);
	//die;
	}
	
	public function get_quiz_feedback_param($package_id)
	{
		$sql="SELECT a.*,b.quiz_name FROM student_feedback_params a,quiz_info b where a.quiz_id=b.id";
		$query = $this->db->query($sql);

		if ($query->num_rows() == 1)
		{
		return $query->result_array();
		}
		elseif ($query->num_rows() > 1)
		{
		return $query->result_array(); //This returns an array of results which you can whatever you need with it
		}
		else
		{
		return $query->result_array();
		}
	}
	
	public function feedback_report_admin()
	{
		
		//$quiz_id=;
		//$teachers=;
		//print_r($_POST);
		
		$batch_name = $_POST['batch_name'];
		
		$quiz_id= $_POST['quiz_name'];
		
		$sql="select sum(concept_coverage)/count(*) ccavg, sum(error_free)/count(*) efavg,sum(standard)/count(*) stavg,
sum(content)/count(*) cavg,sum(overall)/count(*) oaavg ,teachers,count(*) count,type from 
student_feedback_details where batch_name='$batch_name' and quiz_id='$quiz_id' group by teachers,type";
				
			//	print_r($sql);
		$query = $this->db->query($sql);

		if ($query->num_rows() == 1)
		{
		return $query->result_array();
		}
		elseif ($query->num_rows() > 1)
		{
		return $query->result_array(); //This returns an array of results which you can whatever you need with it
		}
		else
		{
		return $query->result_array();
		}
	}
	
	public function feedback_new_report_admin()
	{
		
		//$quiz_id=;
		//$teachers=;
		//print_r($_POST);
		
		$batch_name = $_POST['batch_name'];
		
		$quiz_id= $_POST['quiz_name'];
		
		if ($batch_name == '')
		{
					$sql="select sum(concept_coverage)/count(*) ccavg, sum(error_free)/count(*) efavg,sum(standard)/count(*) stavg,
sum(content)/count(*) cavg,sum(overall)/count(*) oaavg ,teachers,count(*) count,type,batch_name from 
student_feedback_details where  quiz_id='$quiz_id' group by teachers,type,batch_name";
		}
		else{
		
		$sql="select sum(concept_coverage)/count(*) ccavg, sum(error_free)/count(*) efavg,sum(standard)/count(*) stavg,
sum(content)/count(*) cavg,sum(overall)/count(*) oaavg ,teachers,count(*) count,type,batch_name from 
student_feedback_details where batch_name='$batch_name' and quiz_id='$quiz_id' group by teachers,type,batch_name";
		}
				
			//	print_r($sql);
		$query = $this->db->query($sql);

		if ($query->num_rows() == 1)
		{
		return $query->result_array();
		}
		elseif ($query->num_rows() > 1)
		{
		return $query->result_array(); //This returns an array of results which you can whatever you need with it
		}
		else
		{
		return $query->result_array();
		}
	}
	
	
	public function feedback_report_general()
	{
		
		//$quiz_id=;
		//$teachers=;
	//	print_r($_POST);
	 $this->load->library('nativesession');
       $userrole =  $this->nativesession->get('userrole');
	//	print_r($userrole);
		$batch_name = $_POST['batch_name'];
		
		 $teacher= $_POST['teacher'];
		 $session= $_POST['session'];
	if 	($userrole== 1){
		$sql="select sum(rating)/count(*) rating,count(*) count,param_id,b.fb_prmtr param from student_general_feedback a, tv_feedback_paramtrs b , user_details c
where a.batch like '$batch_name%' and teacher collate utf8_general_ci = replace(c.first_name,' ','_') and a.param_id=b.id and a.teacher = '$teacher' and session='$session'
group by teacher,param_id union 
select sum(rating)/count(*) rating,'' count,'' param_id,'Total Average' param from student_general_feedback a, tv_feedback_paramtrs b , user_details c
where a.batch like '$batch_name%' and teacher collate utf8_general_ci = replace(c.first_name,' ','_') and a.param_id=b.id and a.teacher = '$teacher' and session='$session'
group by teacher,param_id";

$sql="select sum(rating)/count(*) rating,count(*) count,param_id,b.fb_prmtr param from student_general_feedback a, tv_feedback_paramtrs b , user_details c
where  teacher collate utf8_general_ci = replace(c.first_name,' ','_') and a.param_id=b.id and a.teacher = '$teacher' and session='$session'
group by teacher,param_id union 
select sum(rating)/count(*) rating,'' count,'' param_id,'Total Average' param from student_general_feedback a, tv_feedback_paramtrs b , user_details c
where  teacher collate utf8_general_ci = replace(c.first_name,' ','_') and a.param_id=b.id and a.teacher = '$teacher' and session='$session'
group by teacher,param_id";
	}
	else{
		$sql="select sum(rating)/count(*) rating,count(*) count,param_id,b.fb_prmtr param from student_general_feedback a, tv_feedback_paramtrs b , user_details c
where a.batch like '$batch_name%' and teacher collate utf8_general_ci = replace(c.first_name,' ','_') and a.param_id=b.id and c.user_name = '$teacher' and session='$session'
group by teacher,param_id union
select sum(rating)/count(*) rating,'' count,'' param_id,'Total Average' param from student_general_feedback a, tv_feedback_paramtrs b , user_details c
where a.batch like '$batch_name%' and teacher collate utf8_general_ci = replace(c.first_name,' ','_') and a.param_id=b.id and c.user_name = '$teacher' and session='$session'
group by teacher 
;";

		$sql="select sum(rating)/count(*) rating,count(*) count,param_id,b.fb_prmtr param from student_general_feedback a, tv_feedback_paramtrs b , user_details c
where  teacher collate utf8_general_ci = replace(c.first_name,' ','_') and a.param_id=b.id and c.user_name = '$teacher' and session='$session'
group by teacher,param_id union
select sum(rating)/count(*) rating,'' count,'' param_id,'Total Average' param from student_general_feedback a, tv_feedback_paramtrs b , user_details c
where teacher collate utf8_general_ci = replace(c.first_name,' ','_') and a.param_id=b.id and c.user_name = '$teacher' and session='$session'
group by teacher 
;";
	}
			//	print_r($sql);
		$query = $this->db->query($sql);

		if ($query->num_rows() == 1)
		{
		return $query->result_array();
		}
		elseif ($query->num_rows() > 1)
		{
		return $query->result_array(); //This returns an array of results which you can whatever you need with it
		}
		else
		{
		return $query->result_array();
		}
	}
	
	public function feedback_report_staff($staff_name)
	{
		
		//$quiz_id=;
		//$teachers=;
	//	print_r($_POST);
		
		$batch_name = $_POST['batch_name'];
		
		$quiz_id= $_POST['quiz_name'];
		
		$sql="select sum(concept_coverage)/count(*) ccavg, sum(error_free)/count(*) efavg,sum(standard)/count(*) stavg,
sum(content)/count(*) cavg,sum(overall)/count(*) oaavg ,teachers,count(*) count,type from 
student_feedback_details where batch_name='$batch_name' and quiz_id='$quiz_id' and teachers='$staff_name' group by teachers,type";
				
			//	print_r($sql);
		$query = $this->db->query($sql);

		if ($query->num_rows() == 1)
		{
		return $query->result_array();
		}
		elseif ($query->num_rows() > 1)
		{
		return $query->result_array(); //This returns an array of results which you can whatever you need with it
		}
		else
		{
		return $query->result_array();
		}
	}
	//get_quiz_feedback_param
	
	public function getfeedbackparam($quiz_id,$batch_id)
	{
		$sql="SELECT a.*,b.quiz_name FROM student_feedback_params a,quiz_info b where a.quiz_id ='$quiz_id' and a.batch_name = '$batch_id'  and a.quiz_id=b.id";
		$query = $this->db->query($sql);

		if ($query->num_rows() == 1)
		{
		return $query->result_array();
		}
		elseif ($query->num_rows() > 1)
		{
		return $query->result_array(); //This returns an array of results which you can whatever you need with it
		}
		else
		{
		return $query->result_array();
		}
	}
	//
	
	public function getfeedbackcomments($quiz_id,$batch_name,$teachers)
	{
		$sql="select remarks from student_feedback_details where quiz_id ='$quiz_id' and batch_name ='$batch_name' and teachers ='$teachers' and remarks <> '' ";
		//print_r($sql);
		$query = $this->db->query($sql);

		if ($query->num_rows() == 1)
		{
		return $query->result_array();
		}
		elseif ($query->num_rows() > 1)
		{
		return $query->result_array(); //This returns an array of results which you can whatever you need with it
		}
		else
		{
		return $query->result_array();
		}
	}
	
	public function save_student_params(){
		$data=array();
		
	
		foreach ($_POST['subject_name'] as $key => $value){
		$data[]=array(
		
			'quiz_id' => $_POST['quiz_id'][$key],
			'type'=> $_POST['type'][$key],
			'batch_name'=> $_POST['batch_name'][$key],
			'subject_name'=> $_POST['subject_name'][$key],
			'teachers'=> $_POST['teacher'][$key],
			'concept_coverage'=> $_POST['concept_coverage'][$key],
			'error_free'=> $_POST['error_free'][$key],
			'standard'=> $_POST['standard'][$key],
			'content'=> $_POST['content'][$key],
			'overall'=> $_POST['overall'][$key],
			'user_name'=> $_POST['user'][$key],
			'remarks'=> $_POST['remarks'][$key]
			
		);
		};

		//echo "</pre>";
	//	print_r($data);
		//echo "</pre>";
		//die;
	//$this->db->where('quiz_id=',$quiz_id);
	//$this->db->order_by("order", "asc");
	$this->db->insert_batch('student_feedback_details',$data);
	//die;
	}
	public function get_batchteacherinfo()
	{
		$this->load->library('nativesession');
		$batch = $this->nativesession->get('batch');
		$org_id=$this->nativesession->get('org_id');

		$sql="SELECT * FROM student_feedback_params where batch_name='$batch' ";
		print_r($sql);
		$query=$this->db->query($sql);
		return $query->result_array();
	}
	//getfeedbackparams
	
		public function getfeedbackparams()
	{
		$this->load->library('nativesession');
		$org_id=$this->nativesession->get('org_id');

		$sql="SELECT * FROM tv_feedback_paramtrs ";
		$query=$this->db->query($sql);
		return $query->result_array();
	}
	
	public function enable_disable_feedback()
	{
		$batch_name = $this->input->post('batch_name');
		$quiz_name = $this->input->post('quiz_name');
		$status = $this->input->post('status');

		$sql="update student_feedback_params set status ='$status' where batch_name='$batch_name' and quiz_id ='$quiz_name'";
		//print_r($sql);
		$query=$this->db->query($sql);
		return "1";
	}
	
	public function get_batches_by_quiz_model($quiz_id)
	{
		$this->db->distinct();
		$this->db->select('batch_name');
		$this->db->where('quiz_id', $quiz_id);
		$query = $this->db->get('student_feedback_details');
		return $query->result_array();
	}

	public function get_quizzes_by_batch_model($batch_name)
	{
		$this->db->distinct();
		$this->db->select('quiz_id, quiz_name');
		$this->db->where('batch_name', $batch_name);
		$query = $this->db->get('student_feedback_details');
		return $query->result_array();
	}

	public function get_all_quizzes()
	{
		$this->db->distinct();
		$this->db->select('a.quiz_id, b.quiz_name');
		$this->db->from('student_feedback_details a');
		$this->db->join('quiz_info b', 'a.quiz_id = b.id', 'left');
		return $this->db->get()->result_array();
	}


	public function get_all_batches()
	{
		//return $this->db->select('batch_name')->distinct()->get('student_feedback_details')->result_array();
		
		$sql = "select distinct batch_name from student_feedback_details where batch_name not in ('23-KJS NEET DTB','23-UJS NEET DTB','23-KJS JEE DTB','23-UJS JEE DTB','23-KJS KCET DTB','23-UJS KCET DTB','23-KJS NEET WTB','23-UJS NEET WTB','23-KJS KCET WTB','23-UJS KCET WTB','23-KJS NEET NLT','23-MJS NEET NLT','KJS_PCMB_STATE','24 B1','24 B2','24 B3','25 B4','24 B5','24 B6','24 C1','24 C2','24 C3','24 COM','24 B6 (KAN)','24 B6 (SAN)','24 C1 (CS-KAN)','24 C1 (CS-HIN)','24 C1 (CS-SAN)','24 C1 (ST-KAN)','24 C1 (ST-HIN)','24 C1 (ST-SAN)','24 C2 (HIN)','24 C2 (SAN)','24 C3 (CS-KAN)','24 C3 (CS-HIN)','24 C3 (CS-SAN)','24 C3 (ST-KAN)','24 C3 (ST-HIN)','24 C3 (ST-SAN)','23 KCET WTB (CS-KAN)','23 KCET WTB (CS-HIN)','23 KCET WTB (CS-SAN)','23 KCET WTB (ST-KAN)','23 KCET WTB (ST-HIN)','23 KCET WTB (ST-SAN)','23 NEET WTB-01','23 NEET WTB-02 (HIN)','23 NEET WTB-02 (SAN)','MJS-24 B1 (HIN)','MJS-24 B1 (SAN)','MJS-24 C1 (HIN)','MJS-24 C1 (SAN)','MJS-24 B/CS (HIN)','MJS-24 B/CS (SAN)','24 EBAC (HIN)','24 EBAC (KAN)','24 EBAC (SAN)','24 EBAS (HIN)','24 EBAS (KAN)','24 EBAS (SAN)','23 BBAS (HIN)','23 BBAS (KAN)','23 BBAS (SAN)','23 EBAS (HIN)','23 EBAS (KAN)','23 EBAS (SAN)','MJS-24 B/CS (CS-HIN)','MJS-24 B/CS (CS-SAN)','UJS-24 B1 (KAN)','UJS-24 B1 (HIN)','UJS-24 B1 (SAN)','UJS-24 B2 (KAN)','UJS-24 B2 (HIN)','UJS-24 B2 (SAN)','UJS-24 C1 (KAN)','UJS-24 C1 (HIN)','UJS-24 C1 (SAN)','UJS-24 C2 (KAN)','UJS-24 C2 (SAN)','KJS-24 N1','KJS-24 N2','KJS-24 N3','KJS-24 N4','KJS-24 N5','KJS-24 N6','KJS-24 J1 CS','KJS-24 J1 ST','KJS-24 J2','KJS-24 J3 CS','KJS-24 J3 ST','UJS-24 N1','UJS-24 N2','UJS-24 J1','UJS-24 J2','MJS-24 N1','MJS-24 J1 (BIO)','MJS-24 J1 (CS)','MJS-24 J2','KJS-24 9TH-A','KJS-24 9TH-B','24-MJS-COM','KJS-24 EBAC','KJS-24 EBAS') " ; //where teachers='$teacher' ";
 //print_r($sql);
$query = $this->db->query($sql);

if ($query->num_rows() == 1)
{
return $query->result_array();
}
elseif ($query->num_rows() > 1)
{
   return $query->result_array(); //This returns an array of results which you can whatever you need with it
}
else
{
return $query->result_array();
}
	}
	public function getofflinepackagedelete()
	{
		$sql = "SELECT oap.package_id AS id, pio.package_name
            FROM offline_assigned_package oap
            JOIN package_info_offline pio ON oap.package_id = pio.id
            GROUP BY oap.package_id, pio.package_name";

		$query = $this->db->query($sql);
		return $query->result_array();
	}
		public function insert_batch_csv_data($data)
{
    return $this->db->insert_batch('offline_assigned_package', $data);
}
}
?>