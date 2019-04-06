var type = "sign_up"; 
var fo1="formtips-smal" , fo2 ="formtips";
$(function(){
	//$('div.md-content').css("width",$('div.body').css("width"));
	locate_but();
	$div = $('form>div');
	for(var i = 0;i < $div.length;i ++){
		$now = $div.eq(i);
		var str1 = '<span class="pre-name">';
		var str2 = i < 9 ? '&nbsp<\span>' : '<br><\span>';
		$now.prepend(str1+$now.attr("value")+str2);
	}
	
	$('select').mouseup(function(){if($(this).val()!="")    $(this).parent().next('.formtips').animate({"height":"0px"},function(){$(this).remove();});})
	$('input[type=radio]').click(function(){$(this).parent().parent().next('.formtips').animate({"height":"0px"},function(){$(this).remove();});})
	$('form input,textarea,select').focus(function(){$(this).parent().find('span').addClass("onfoucs");})
									.blur(function(){$(this).parent().find('span').removeClass("onfoucs");})
	$('form input[type=text]').focus(function(){$(this).trigger('keyup');})
	
	$("form>div:last").css("marginTop","10px");
	var color1="rgba(255, 255, 255, 0.88)" , color2="rgba(52, 236, 101, 0.82)";	
	$left = $("#left_tag") , $right = $("#right_tag");
	$left.find('span').addClass("onfoucs");
	$left.css('background-color',color1);
	$right.css({'background-color':color2});
	$left.click(function(){
		$('div>h3').text("报名结果");
		$('img:eq(0)').animate({"opacity":"0"},function(){$(this).attr("src","./img/intro.png").animate({"opacity":"1"});})
		fo1="formtips-smal"; fo2="formtips";
		$left.find('span').addClass("onfoucs");
		$left.css({'background-color':color1});
		$right.find('span').removeClass("onfoucs");
		$right.css({'background-color':color2});
		$('div#left_tag span').css('fontWeight',"10px");
		$('form .'+fo1).remove();
		$("input[type=text],textarea").val("");
		$("form>div").show("fast");
		$("div[value=姓名],div[value=手机号]").css({"margin":"10px 0px 0px 0px"});
		$("span.pre-name").css("float","left");
		$("form").removeClass('mar-big');
		$("input[name=name],input[name=tell]").removeClass('width-big');
		$("button.md-trigger").removeClass("query").text("提交");
		locate_but();
		type="sign_up";
	})
	$right.click(function(){
		$('div>h3').text("查询结果");
		$('img:eq(0)').animate({"opacity":"0"},function(){$(this).attr("src","./img/query.png").animate({"opacity":"1"});})
		fo2="formtips-smal"; fo1="formtips";
		$right.find('span').addClass("onfoucs");
		$right.css({'background-color':color1});
		$left.find('span').removeClass("onfoucs");
		$left.css({'background-color':color2});
		$('form .'+fo1).remove();
		$("span.pre-name").css("float","none");
		$("form>div:not(div[value=姓名],div[value=手机号])").hide("fast");
		$("div[value=姓名],div[value=手机号]").css({"margin":"15% 0px 0px 0px"});
		$("form").addClass('mar-big');
		$("input[name=name],input[name=tell]").addClass('width-big');
		$("button.md-trigger").text("查询").addClass("query");
		locate_but();
		type="inquiry";
	})
	
})

function locate_but(){
	$butt = $('button.md-trigger');
	var len1 = parseInt($butt.css("width")) + 2*parseInt($butt.css("borderLeftWidth")) + 2*parseInt($butt.css("paddingLeft"));
	var len2 = (parseInt($('div.body').css("width")) - len1 )/2 - parseInt($('form').css("marginLeft"));
	$butt.css("width",parseInt($('div.body').css('width'))*0.4+'px');
	$butt.css("marginLeft",len2+"px");
}

$(window).resize(function(){
	locate_but();
	$('body').css("backgroundSize",$(window).width()+"px "+$(window).height()+"px");
	$('div.md-content').css("width",parseInt($('div.body').css("width"))*0.95+'px');
})

