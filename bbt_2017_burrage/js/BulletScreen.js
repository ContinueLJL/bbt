var socket = new WebSocket("ws://119.29.191.172:5555/");
//var socket = new WebSocket("ws://localhost:3000/");

function showMessage(username, msg){
    var span = $("<span></span>")
    span.text(msg);
    var div = $("<div></div>");
    div.text(username + ":" + msg);
    $("#screen").append(span);

    if(code == 0){
        var x = 50; var y = 150;
        var randomtop = Math.random()*(x - y + 1) + y + "px";
        span.css("top", randomtop);
        span.addClass("emit1");
        setTimeout(function(){
            $(span).remove();
        }, 13000);
    }else if(code == 1){
        span.addClass("emit2");
        setTimeout(function(){
            $(span).remove();
        }, 3000);
    }

    // 设置弹幕颜色
    if(color == 0){
        span.css("color", "white");
    }else if(color == 1){
        span.css("color", "red");
    }
    else if(color == 2){
        span.css("color", "blue");
    }
    else if(color == 3){
        span.css("color", "yellow");
    }
    else if(color == 4){
        span.css("color", "purple");
    }
    else if(color == 5){
        span.css("color", "green");
    }

    $("#discuss").append(div);
    $("#discuss").scrollTop(999999999999999);
}


socket.onopen = function(){
    console.log("connect succeed");
}

socket.onclose = function(){
	console.log("already close");
}

socket.onmessage = function(e){
    var data = JSON.parse(e.data);
	showMessage(data["username"], data["msg"]);
}

$(document).keyup(function(event){
    if(event.keyCode ==13){
      $("#sendbtn").trigger("click");
    }
})

var code = 0;
var color = 0;
var user = $.cookie("user");
$("#name").text(user);
$("#extend").slideUp();
$("#scroll").addClass("select");
$("#white").addClass("select");

if(user == "tourist"){
    $("input[name=sendtext]").attr("placeholder", "登录后才能发言");
}

$("#option").click(function(){
    $("#extend").slideDown();
})

$("#hide").click(function(){
    $("#extend").slideUp();
})

$("#back").click(function(){
    window.location.href = "./index.html";
})

$("#sendbtn").click(function(){
    if(user == "tourist"){
        $("input[name=sendtext]").val("");
        $("input[name=sendtext]").attr("placeholder", "登录后才能发言");
    }else{
        var text = $("#sendtext").val();
        if(text){
            var msg = {};
            msg.username = user;
            msg.str = text;
            socket.send(JSON.stringify(msg));
            $("input[name=sendtext]").val("");
            $("input[name=sendtext]").attr("placeholder", "");
        }else{
            $("input[name=sendtext]").val("");
            $("input[name=sendtext]").attr("placeholder", "弹幕不能为空");
        }
    }
})

$("#fix").click(function(){
    $("#fix").addClass("select");
    $("#scroll").removeClass("select");
    code=1;
})

$("#scroll").click(function(){
    $("#scroll").addClass("select");
    $("#fix").removeClass("select");
    code=0;
})

$("#white").click(function(){
    $("#white").addClass("select");
    $("#red").removeClass("select");
    $("#blue").removeClass("select");
    $("#yellow").removeClass("select");
    $("#purple").removeClass("select");
    $("#green").removeClass("select");
    color = 0;
})

$("#red").click(function(){
    $("#red").addClass("select");
    $("#white").removeClass("select");
    $("#blue").removeClass("select");
    $("#yellow").removeClass("select");
    $("#purple").removeClass("select");
    $("#green").removeClass("select");
    color = 1;
})

$("#blue").click(function(){
    $("#blue").addClass("select");
    $("#red").removeClass("select");
    $("#white").removeClass("select");
    $("#yellow").removeClass("select");
    $("#purple").removeClass("select");
    $("#green").removeClass("select");
    color = 2;
})

$("#yellow").click(function(){
    $("#yellow").addClass("select");
    $("#red").removeClass("select");
    $("#blue").removeClass("select");
    $("#white").removeClass("select");
    $("#purple").removeClass("select");
    $("#green").removeClass("select");
    color = 3;
})

$("#purple").click(function(){
    $("#purple").addClass("select");
    $("#red").removeClass("select");
    $("#blue").removeClass("select");
    $("#yellow").removeClass("select");
    $("#white").removeClass("select");
    $("#green").removeClass("select");
    color = 4;
})

$("#green").click(function(){
    $("#green").addClass("select");
    $("#red").removeClass("select");
    $("#blue").removeClass("select");
    $("#yellow").removeClass("select");
    $("#purple").removeClass("select");
    $("#white").removeClass("select");
    color = 5;
})
