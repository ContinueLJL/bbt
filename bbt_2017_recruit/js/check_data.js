function hehe(){
		var msg = null;
	//	$('form .'+fo2).animate({"height":"0px"},function(){$(this).remove();})
		$('form .'+fo2).remove();
		if(type == "sign_up"){
			if(!$('input[name=adjust]:checked').length) 
						msg= "调剂吗？(˘•ω•˘)" ,
						$('input[name=adjust]').parent().parent().after('<span class="'+fo2+'">'+msg+'</span>');
			if($('select[name=department1]').val()=="")		
						msg =  "请选择第一意向(˘•ω•˘)",
						$('select[name=department1]').parent().after('<span class="'+fo2+'">'+msg+'</span>');
			if($('select[name=department2]').val()=="")		
						msg =  "请选择第二意向(˘•ω•˘)",
						$('select[name=department2]').parent().after('<span class="'+fo2+'">'+msg+'</span>');
			if($('select[name=college]').val()=="")       
						msg="学院学院(˘•ω•˘)",
						$('select[name=college]').parent().after('<span class="'+fo2+'">'+msg+'</span>');
			if(!$('input[name=grade]:checked').length) 
						msg= "年级(˘•ω•˘)",
						$('input[name=grade]').parent().parent().after('<span class="'+fo2+'">'+msg+'</span>');
			if(!$('input[name=sex]:checked').length) 
						msg="性别不详？(˘•ω•˘)",
						$('input[name=sex]').parent().parent().after('<span class="'+fo2+'">'+msg+'</span>');
			$('form input[type=text]').trigger("keyup");
		}
		else $('input[name=name],input[name=tell]').trigger("keyup");
		if($('span.'+fo2).length == 0 ) {submitData() ; return true;}
		else return false;
		
}
$(function(){
	$('form input[name=tell]').keyup(function(){
					$formtip = $(this).parent().next('.'+fo2);
					if($(this).val()=="" || !/^1[0-9]{10}$/.test($(this).val())){
						if($formtip.length == 0)
								msg = "十一位手机号呢(˘•ω•˘)",
								$(this).parent().after('<span class="'+fo2+'">'+msg+'</span>');
					}
					else $formtip.animate({"height":"0px"},function(){$(this).remove();});
	})
	$('form input[name=dorm]').keyup(function(){
					$formtip = $(this).parent().next('.'+fo2);
					if(($(this).val()=="" || !/^ *(C|c)([1-9]|1[0-9]) *(|)? *-? *[1-9][0-9]{2} *$/.test($('input[name=dorm]').val())))
						{if($formtip.length == 0)	
								msg = "宿舍格式cx-xxx哦(˘•ω•˘)",
								$(this).parent().after('<span class="'+fo2+'">'+msg+'</span>');
						}
					else $formtip.animate({"height":"0px"},function(){$(this).remove();});
	})
	$('form input[name=name]').keyup(function(){
					$formtip = $(this).parent().next('.'+fo2);
					if($(this).val()=="" || !/^[\u4E00-\u9FA5]{2,4}$/.test($(this).val()) ){
						if($formtip.length == 0)
							msg = "你的名字是(˘•ω•˘)",
							$(this).parent().after('<span class="'+fo2+'">'+msg+'</span>');
					}
					else $formtip.animate({"height":"0px"},function(){$(this).remove();});
	})	
})
function submitData(){
	$.ajax({
        type:'POST',
        url:'./php/bbt.php',
        data:{
            action:	type,
			name:	$('input[name=name]').val(),
			sex:	$('input[name=sex]:checked').val(),
			grade:  $('input[name=grade]:checked').val(),
			college:$('select[name=college]').val(),
			dorm:	$('input[name=dorm]').val(),
			phone_number:	$('input[name=tell]').val(),
			branch:$('select[name=department1]').val(),
			second_branch:$('select[name=department2]').val(),
			adjust: $('input[name=adjust]:checked').val(),
			introduction: $('textarea').val()
        },
        success:function(data){
            $('#top').show();
			var now = eval('('+data+')');
			if(type == "sign_up"){
			//	$('#back_info').html()
				if(now.status==2) $('#back_info').html('<p>你已经报名了<\p>');
				else if(now.status==1) $('#back_info').html('<p>成功报名<\p>');
				else $('#back_info').html('<p>报名失败<\p>');
			}
			else {
				if(now.status==0)  $('#back_info').html('<p>查询不到你的消息<\p>');
				else $('#back_info').html('<p>你报的第一志愿是'+now.branch+'<br>第二志愿是'+now.second_branch+'<br>是否调剂为'+now.adjust+'<\p>');
			}
		}
    })
		
}