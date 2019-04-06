<?php
    $url='';//服务器地址
    $user='';//用户名
    $password='';//密码
    $dateBase='';//数据库名称
    $table='';//数据表名称
    $postName=array('name','sex','grade','college','dorm','phone_number','branch','second_branch','adjust','introduction');

    //链接数据库
    $con=mysqli_connect($url,$user,$password,$dateBase);
    if (!$con) {die('Could not connect: ' . mysqli_error());}
    mysqli_set_charset($con,"utf8");
    //同步时钟
    date_default_timezone_set("Asia/Shanghai");

    //存储
    function save()
    {
        global $table,$student,$con;
        $stmt=mysqli_stmt_init($con);
        mysqli_stmt_prepare($stmt,"SELECT phone_number FROM $table WHERE phone_number=?");
        mysqli_stmt_bind_param($stmt,"s",$student['phone_number']);
        mysqli_stmt_execute($stmt);
        if (mysqli_stmt_fetch($stmt)){
            $back=array('status'=>2);
            echo json_encode($back);
            return;
        }
        mysqli_stmt_prepare($stmt,"INSERT INTO $table".
        "(name,sex,grade,college,dorm,phone_number,branch,second_branch,adjust,introduction,time)".
        "VALUES".
        "(?,?,?,?,?,?,?,?,?,?,?)");
        $a=$student;
        $time=date('Y-m-d H:i:s');
        mysqli_stmt_bind_param($stmt,"sssssssssss",$a[0],$a[1],$a[2],$a[3],$a[4],$a[5],$a[6],$a[7],$a[8],$a[9],$time);  
        if (mysqli_stmt_execute($stmt)){
            $back=array('status'=>1);
            echo json_encode($back);
        }
        else {
            $back=array('status'=>0);
            echo json_encode($back);
        }

    }

    //查询
    function query()
    {
        global $table,$student,$con;
        $stmt=mysqli_stmt_init($con);
        mysqli_stmt_prepare($stmt,"SELECT branch,second_branch,adjust FROM $table WHERE name=? AND phone_number=?");
        mysqli_stmt_bind_param($stmt,"ss",$student[0],$student[5]);
        mysqli_stmt_bind_result($stmt,$branch,$second_branch,$adjust);
        mysqli_stmt_execute($stmt);
        if (mysqli_stmt_fetch($stmt)){
            $back=array('status'=>1,'branch'=>$branch,'second_branch'=>$second_branch,'adjust'=>$adjust);
            echo json_encode($back);
        }
        else {
            $back=array('status'=>0);
            echo json_encode($back);
        }
    }

    
    //接受指令
    if (isset($_POST['action'])){
        $j=0;
        foreach ($postName as $i)
        {
            $student[$j++]=isset($_POST[$i])?$_POST[$i]:'';
        }
        switch ($_POST['action'])
        {
            case 'sign_up':save();break;
            case 'inquiry':query();break;
        }
    }

    mysqli_close($con);
?>
