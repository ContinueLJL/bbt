<?php
include("conn.php");
// 报名类
class SignUp{
	private $introduction;

	public function __construct(){
        $this->introduction = $_POST['introduction'];
		//截取前50字符串
		if(mb_strlen($_POST['introduction'],"utf-8")>50){
			$this->introduction = mb_substr($_POST['introduction'], 0, 50, 'utf-8');
		}
	}
	//判断是否存在
	public function isExist(){
		global $link;
		$check = $link->prepare("SELECT * FROM messages WHERE name=? AND phone_number=?");
		$check->bind_param("ss", $_POST['name'], $_POST['phone_number']);
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
		$stmt->bind_param("ssssssssss", $_POST['name'], $_POST['sex'], $_POST['grade'], $_POST['college'], $_POST['dorm'], $_POST['phone_number'], $_POST['branch'], $_POST['second_branch'], $_POST['adjust'], $this->introduction);

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
	//查询方法
	public function to_check(){
		global $link;

		$stmt = $link->prepare("SELECT branch, second_branch, adjust FROM messages WHERE name=? AND phone_number=?");

		$stmt->bind_param("ss", $_POST['name'], $_POST['phone_number']);

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
	$sign = new SignUp();
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
	$inq = new Inquiry();
    $inq->to_check();
}
