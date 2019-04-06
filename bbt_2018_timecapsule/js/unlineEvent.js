var sender_name , sender_phone , code , receiver 
	,receiver_phone, addr, send_time, isseal, form;



window.addEventListener('load',addEvent);



function addEvent(){
	//sface+i 表示界面i
	var sface1 = document.getElementById('sendpeo') ,			 
		sface2 = document.getElementById('recivepeo') , 		
		sface3 = document.getElementById('sureMsg') , 			
		sface4 = document.getElementById('submit') , 			
		notice = document.getElementById('notice') , 			
	//	butrt = document.getElementById('return'), 		// 寄件人界面返回主页按钮
		but1 = sface1.querySelector('a.but') , 			//寄件人界面确认按钮
		but2 = sface1.querySelector('label>span'),		//寄件人界面阅读事项按钮
		but3 = notice.querySelector('a.but') , 			//阅读界面的同意按钮
		but4 = sface2.querySelector('a.but') , 			//收件人界面的下一步按钮
		but5 = sface3.querySelector('.toRet'), 			 //确定界面的返回修改
		but6 = sface3.querySelector('.toNext'),			//确定界面的确定信息
		but7 = sface4.querySelector('.toRet'), 			//结束界面的再写一封
		but8 = sface4.querySelector('.toNext'),			//结束界面的结束寄信
		
	// back+i 表示第i个返回键
	   back = document.getElementsByClassName('return') , 
	   back1 = back[0] , 
	   back2 = back[1] ,
	   back3 = back[2] ;
	

	//各种无脑buttuon绑定

	but1.onclick = function (){
		sender_name = sface1.getElementsByTagName('input')[0].value;
		sender_phone = sface1.getElementsByTagName('input')[1].value;
		
		var read = sface1.getElementsByTagName('input')[2].checked ;
		if(sender_name == '' || !(/^[1][3,4,5,7,8][0-9]{9}$/).test(sender_phone) || !read){   /**********/
			   var str = '';
			   if(sender_name == '') str += '请填写寄件人姓名<br>';
			   if(!(/^[1][3,4,5,7,8][0-9]{9}$/).test(sender_phone)) str += '寄件人手机号格式不对哦<br>';
				if(!read) str += '请在我已阅读寄信须知前打勾哦<br>';
				showError(str);
				return ;
		}
		
		slide(sface1, sface2);
	}
	
	but2.onclick = function(){
		slide(sface1, notice);
	}
	
	but3.onclick = function(){
		sface1.getElementsByTagName('input')[2].checked = true;
		slide(notice, sface1);
	}
	
	but4.onclick = function(){
		code = sface2.getElementsByTagName('input')[0].value;
		receiver = 	sface2.getElementsByTagName('input')[1].value;
		receiver_phone = sface2.getElementsByTagName('input')[2].value;
		addr =  sface2.getElementsByTagName('textarea')[0].value;
		
		if(sface2.querySelector('#recivepeo input[type=radio][name=year]:checked') != null )
			send_time = sface2.querySelector('#recivepeo input[type=radio][name=year]:checked').getAttribute('data-year');
		if(sface2.querySelector('#recivepeo input[type=radio][name=fire]:checked') != null )
			isseal = sface2.querySelector('#recivepeo input[type=radio][name=fire]:checked').getAttribute('data-fire');
	
		if(code != '' && receiver != '' && (/^[1][3,4,5,7,8][0-9]{9}$/).test(receiver_phone) && addr != '' && send_time != null && isseal != null){
			slide(sface2, sface3);
			var sface3_div = sface3.getElementsByTagName('div')[0];
			if(sface3_div.childNodes.length > 0) sface3_div.removeChild(sface3_div.firstChild);
			form = sface2.getElementsByTagName('form')[0].cloneNode(true);
			sface3.getElementsByTagName('div')[0].appendChild(form);
		}
		else {           /**********/
			var str = '';
			if(code == '') str += '请填写信封上的编码哦<br>';
			if(receiver == '') str += '请填写收件人姓名<br>';
			if(!(/^[1][3,4,5,7,8][0-9]{9}$/).test(receiver_phone)) str += '请填写正确的收件人电话<br>';
			if(addr == '') str += '请填写收件人所在宿舍楼哦<br>';
			if(send_time == null) str += '请选择寄件时间哦<br>';
			if(isseal == null ) str += '请选择是否火漆哦<br>';
			showError(str);
			
		}
			
	}
	
	but5.onclick = function(){
		slide(sface3, sface2);
	}
	
	but6.onclick = function(){
		var xhr = new XMLHttpRequest();
		xhr.open('POST','./php/UnderLine.php');
		xhr.setRequestHeader('Content-type','application/x-www-form-urlencoded');
		xhr.send('sender_name='+sender_name+'&sender_phone='+sender_phone+'&code='+code+'&receiver='+receiver+'&receiver_phone='+receiver_phone+'&addr='+addr+'&send_time='+send_time+'&isseal='+isseal);
		xhr.onreadystatechange = function(){
		if(xhr.status == 200 && xhr.readyState == 4){  //将得到的值设为全局变量
			var arry = JSON.parse(xhr.response);
			if(arry.status == 0){
				slide(sface3, sface4);
				var sface4_div = sface4.getElementsByTagName('div')[0];
				if(sface4_div.childNodes.length > 0) sface4_div.removeChild(sface4_div.firstChild);
				sface4.getElementsByTagName('div')[0].appendChild(form);

				code = null , receiver = null,receiver_phone = null, addr = null , send_time = null , isseal = null;
			}
			else {
				var str = '';
				if(arry.status == 5) str +=  '信封编码已经存在啦<br>';
				else 
					if(arry.status == 2) str += '你填的信息有误哦<br>';
					else 
						if(arry.status == 6) str += '你填的信封编号不存在哦<br>';
						else 
							str += '服务器出现问题，等等来哦<br>';
				showError(str);
			}
		}
	}	

	}
	
	but7.onclick = function(){
		sface2.getElementsByTagName('form')[0].reset();
		slide(sface4, sface2);
	}
	
	but8.onclick = function(){
		location.href = './content.html';
	}
	
	back1.onclick = function(){
		location.href = './content.html';
	}
			
	back2.onclick = function(){
		slide(notice, sface1);
	}
	
	back3.onclick = function(){
		slide(sface2, sface1);
	}
	

	
}



