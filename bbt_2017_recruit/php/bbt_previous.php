<?php
include("conn.php");
if ($_POST['action'] == 'sign_up') {
	$student = array(
	    'name' => $_POST['name'],
	    'sex'  => $_POST['sex'],
	    'grade'  => $_POST['grade'],
	    'college'  => $_POST['college'],
	    'dorm'  => $_POST['dorm'],
	    'phone_number'  => (string)$_POST['phone_number'],
	    'branch'  => $_POST['branch'],
        'second_branch' => $_POST['second_branch'],
	    'adjust'  => $_POST['adjust'],
	    'introduction'  => $_POST['introduction']
	);
}else{
	$student = array(
	    'name' => $_POST['name'],
	    'phone_number'  => (string)$_POST['phone_number']
	);
}
// 报名类
class SignUp{

	private $name, $sex, $grade, $college, $dorm, $phone_number, $branch, $second_branch,
			$adjust, $introduction;

	public function __construct($student){
		//截取前50字符串
		if(mb_strlen($student['introduction'],"utf-8")>50){
			$student['introduction'] = mb_substr($student['introduction'], 0, 50, 'utf-8');
		}
		//赋值
		$this->name = $student['name'];
		$this->sex = $student['sex'];
		$this->grade = $student['grade'];
		$this->college = $student['college'];
		$this->dorm = $student['dorm'];
		$this->phone_number = $student['phone_number'];
		$this->branch = $student['branch'];
        $this->second_branch = $student['second_branch'];
		$this->adjust = $student['adjust'];
		$this->introduction = $student['introduction'];
	}
	//判断是否存在
	public function isExist(){
		global $link;
		$check = $link->prepare("SELECT * FROM messages WHERE name=? AND phone_number=?");
		$check->bind_param("ss", $name, $phone_number);
		$name = $this->name;
		$phone_number = $this->phone_number;
		$check->execute();
		$results = $check->get_result();
		if($results->num_rows > 0){
			return 1;
		}else{
			return 0;
		}
	}
	//写入数据库
	public function signUp(){
		global $link;
		$stmt = $link->prepare("INSERT INTO messages (name,sex,grade,college,dorm,phone_number,branch,second_branch,adjust,introduction) VALUES (?,?,?,?,?,?,?,?,?,?)");
		$stmt->bind_param("ssssssssss", $name, $sex, $grade, $college, $dorm, $phone_number, $branch, $second_branch, $adjust, $introduction);
		$name=$this->name;
		$sex=$this->sex;
		$grade=$this->grade;
		$college=$this->college;
		$dorm=$this->dorm;
		$phone_number=$this->phone_number;
		$branch=$this->branch;
        $second_branch =$this->second_branch;
		$adjust=$this->adjust;
		$introduction=$this->introduction;

		$query = $stmt->execute();

		$data = null;
		if($query){
			$data = [
				"status" => 1
			];
		}else{
			$data = [
				"status" => 0
			];
		}

		echo json_encode($data);

		$stmt->close();
	}

}
//查询类
class Inquiry{
	private $name, $phone_number, $branch, $second_branch, $adjust;

	public function __construct($student){

		$this->name = $student['name'];
		$this->phone_number = $student['phone_number'];

	}
	//查询方法
	public function inquiry(){
		global $link;

		$stmt = $link->prepare("SELECT branch, second_branch, adjust FROM messages WHERE name=? AND phone_number=?");

		$stmt->bind_param("ss", $name, $phone_number);
		$name = $this->name;
		$phone_number = $this->phone_number;

		$stmt->execute();

        $data = null;
        $result = $stmt->get_result();
        if($result->num_rows > 0){
            $row = $result->fetch_assoc();
            $data = [
                "status" => 1,
                "branch" => $row['branch'],
                "second_branch" => $row['second_branch'],
                "adjust" => $row['adjust']
            ];
        }else{

            $data = [
                "status" => 0
            ];
        }
        echo json_encode($data);

		$stmt->close();
	}
}
//调用SignUp
if($_POST['action']=='sign_up'){
	$sign = new SignUp($student);
	if($sign->isExist()){
		$data = [
			"status" => 2
		];
		echo json_encode($data);
	}else{
		$sign->signUp();
	}
}
//调用Inquiry
if($_POST['action']=='inquiry'){
	$inq = new Inquiry($student);
	$inq->inquiry();
}
