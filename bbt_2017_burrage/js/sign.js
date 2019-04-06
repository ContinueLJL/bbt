$("#success").hide();

$("#submit").click(function(){
    var username = $("#username").val();
    var telephone = $("#telephone").val();
    var password1 = $("#password1").val();
    var password2 = $("#password2").val();

    if(username==""||username==null){
        $("#username").attr("placeholder", "请输入用户名");
        $("#username").addClass("error");
    };

    if(telephone==null||telephone==""){
        $("#telephone").attr("placeholder", "请输入手机号");
        $("#telephone").addClass("error");
    }else if(!(/^1[34578]\d{9}$/.test(telephone))){
        $("#telephone").val("");
        $("#telephone").attr("placeholder", "请输入正确的格式");
        $("#telephone").addClass("error");
        return;
    }
    
    if(password1==""||password1==null){
        $("#password1").attr("placeholder", "请输入密码");
        $("#password1").addClass("error");
    }
    
    if(password2==""||password2==null){
        $("#password2").attr("placeholder", "请再次输入密码");
        $("#password2").addClass("error");
        return;
    }
    
    if(password1===password2){
        $.ajax({
            type:"POST",
            url:"./php/SignUp.php",//填写后台文件路径
            data:{
                username : username,
                phone : telephone,
                password : password1,
            },
            success:function(data){
                var obj = JSON.parse(data);
                if(obj["status"]==0){
                    $(".container").hide();
                    $("#success").fadeIn();
                }else{
                    var text = "";
                    text = obj["msg"];
                    $("#response").text(text);
                    setTimeout('$("#response").fadeOut()',6000);
                }
            },
            error:function(){
                alert("网络连接失败");
                return;
            },
        })
    }else{
        $("#response").text("两次输入的密码不一致");
        etTimeout('$("#response").fadeOut()',6000);
    }
  
})

$("#return").click(function(){
    window.location.href = "./index.html";
})