$("#login").click(function(){
    var telephone = $("#telephone").val();
    var password = $("#password").val();

    if(telephone==""||telephone==null){
        $("#telephone").attr("placeholder", "请输入用户名");
        $("#telephone").addClass("error");
    };
    
    if(password==""||password==null){
        $("#password").attr("placeholder", "请输入密码");
        $("#password").addClass("error");
        return;
    }

    $.ajax({
        type:"POST",
        url:"./php/SignIn.php",//填写后台文件路径
        data:{
            phone:telephone,
            password:password,
        },
        success:function(data){
            var obj = JSON.parse(data);
            $.cookie("user", obj["user"]);
            if(obj["status"]==0){
                window.location="./BulletScreen.html";
            }else{
                var text = "";
                text = obj["msg"];
                $("#response").text(text);
                setTimeout('$("#response").text("")', 6000);
            }
        },
        error:function(){
            alert("网络连接失败");
            return;
        },
    })
})

$("#sign").click(function(){
    window.location.href = "./sign.html";
})

$("#tour").click(function(){
    window.location.href="./BulletScreen.html";
    $.cookie("user", "tourist");
})