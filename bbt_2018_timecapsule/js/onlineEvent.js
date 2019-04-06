const  canvasWidth = 350;
const canvasHeight = 250;
const X = canvasWidth / 2;
const Y = canvasHeight / 2;

var receiver , phone , send_time ,content , type ,server_id, reciverID, index ;
//var wx;
var arryPic = [];

window.addEventListener('load',addEvent);

function addEvent(){
	//sface+i 表示界面i
	var sface1 = document.getElementById('recive') ,			 
		sface2 = document.getElementById('choice') , 		
		sface3 = document.getElementById('text') , 			
		sface4 = document.getElementById('voice') , 			
		sface5 = document.getElementById('over') , 		
	//but+i 表示当前按钮	
		but1 = sface1.querySelector('a.but') , 					//收件人信息界面的确定按钮
		but2 = sface2.querySelector('a.but:nth-of-type(1)'),	//选择界面的文字按钮
		but3 = sface2.querySelector('a.but:nth-of-type(2)') , 	//选择界面的语音按钮
		but4 = sface2.querySelector('a.but:nth-of-type(3)') , 	//选择界面的返回修改
		but5 = sface3.querySelector('a.but') , 					//文字界面的确定按钮
		but6 = sface4.querySelector('canvas'), 					//语音界面的点击回放
		but7 = sface4.querySelector('a.but:nth-of-type(2)'),	//语音界面的开始录音
		but8 = sface4.querySelector('a.but:nth-of-type(3)'), 	//语音界面的确定提交
		but9 = sface4.querySelector('a.but:nth-of-type(4)'),	//语音界面的重新录制
		but10 = sface4.querySelector('a.but:nth-of-type(5)'),	//语音界面的完成
		but11 = sface5.querySelector('a.but:nth-of-type(1)') ,	// 结束界面的在写一封
		but12 = sface5.querySelector('a.but:nth-of-type(2)') , 	//返回主页
	//back + i 表示第几个返回	
		back1 = sface1.getElementsByTagName('a')[0] , 
		back2 = sface3.getElementsByTagName('a')[0] , 
		back3 = sface4.getElementsByTagName('a')[0] ;
	
		var canvas = document.getElementsByTagName('canvas')[0];
		var ctx = canvas.getContext('2d');
		var gra = ctx.createLinearGradient(0,0,0,150);
			gra.addColorStop(0,'#c7eeed');
			gra.addColorStop(1,'#8ecacd');
	
    	var  preTime = 0 , recordTime = 0 ;
    	var  audio_status = false, doLoop = false, localId ;
	
	

	
	//获得wetchat jssdk
	init_wxsdk(ctx, gra);
	//判断是否通过二维码
	init_receiver(sface1);
	//切换录音界面的标题
	changeTitle(but9, but10);

	draw(ctx , gra, 0);
	
	
	//各种button，无脑
	
	but1.onclick = function(){
		
		receiver = sface1.getElementsByTagName('input')[0].value ;
		phone = sface1.getElementsByTagName('input')[1].value ; 
		if(sface1.querySelector('input[type=radio][name=year]:checked') != null )
			send_time = sface1.querySelector('input[type=radio][name=year]:checked').getAttribute('data-year');
		
		if( ((receiver == '' || !(/^[1][3,4,5,7,8][0-9]{9}$/).test(phone) ) &&  reciverID == null)|| send_time == null){	
		    var str = '';
			if(receiver == '') str += '你没有填名字哦<br>';
			if(!(/^[1][3,4,5,7,8][0-9]{9}$/).test(phone) && reciverID == null) str += '收件人的手机号码格式不对<br>';
			if(send_time == null) str += '请选择寄出时间哦<br>';
			showError(str);
			return ;
		}
		
	 	var sface2_div = sface2.getElementsByTagName('div')[0];
		if(sface2_div.childNodes.length > 0) sface2_div.removeChild(sface2_div.firstChild);
			form = sface1.getElementsByTagName('form')[0].cloneNode(true);
			sface2.getElementsByTagName('div')[0].appendChild(form);
	 
		slide(sface1, sface2);
	}
	
	
	but2.onclick = function(){
		slide(sface2, sface3);
	}
	
	but3.onclick = function(){
		slide(sface2, sface4);
	}
	
	but4.onclick = function(){
		slide(sface2, sface1);
	}
	
	but5.onclick = function(){
		content = sface3.getElementsByTagName('textarea')[0].value ; 
		if(content != ''){
			type = 0 ; 
			AJAX();
		}
		else {			
			var str = '信封不能为空哦<br>';
			showError(str);
		}
	}
	
	

	
	
	but6.onclick = function(event){
	   if(event.clientX > (X - 75) && event.clientX < (X + 75))
		if(but9.style.display == 'block'){
			if(!audio_status){
				wx.playVoice({localId: localId});
				audio_status = !audio_status;
				drawImg(ctx, gra, 'img/pause.png');
			}
			else {
				wx.pauseVoice({localId: localId});
				audio_status = !audio_status
				drawImg(ctx, gra, 'img/play.png');
			}
		}
	}
	
	but7.onclick = function(){
		wx.startRecord();
		this.style.display = 'none';
		but10.style.display = 'block';
		doLoop = true;
		changeTitle(but9, but10);
	}
	
	but8.onclick = function(){
		wx.uploadVoice({
			localId: localId, // 需要上传的音频的本地ID，由stopRecord接口获得
			isShowProgressTips: 1, // 默认为1，显示进度提示
			success: function (res) {
				server_id = res.serverId; // 返回音频的服务器端ID
				type = 1;
				AJAX();
			}
		});
		
	}
	
	but9.onclick = function(){
		audio_status = 0;
		this.style.display = 'none';
		but7.style.display = 'block';
		but8.style.display = 'none';
		preTime = 0;
		arryPic = [];
		draw(ctx, gra, 0);
		changeTitle(but9,but10);
	}
	
	but10.onclick = function(){
		wx.stopRecord({
			success: function (res) {
			localId = res.localId;}
		});
		this.style.display = 'none';
		but8.style.display = 'block';
		but9.style.display = 'block';
		changeTitle(but9, but10);
		drawImg(ctx, gra, 'img/play.png');
		doLoop = false;
	}
	//当按再写一封时对所有存储内容进行清空
	but11.onclick = function(){
		sface3.getElementsByTagName('textarea')[0].value = '';
		if(reciverID == null){
			sface1.getElementsByTagName('form')[0].reset();
			receiver = '', phone ='', send_time = null;
		}
		content = null , server_id = null;
		arryPic = [];
		slide(sface5, sface1);
	}
	
	but12.onclick = function(){
		location.href = './content.html';
	}
	
	
	back1.onclick = function(){
		location.href = './content.html';
	}
	
	back2.onclick = function(){
		slide(sface3, sface2);
	}
	back3.onclick = function(){
		slide(sface4, sface2);
	}
	
	
	setInterval(function(){
		if(doLoop == true){
			recordTime = preTime + 0.05;
			updataPic();
			draw(ctx, gra , recordTime);
			preTime = recordTime;
			if(preTime >= 59.6)
				but10.click();
		}
	},50);
	
}

//进行信息的提交
function AJAX(){
	var str = '' , URL ; 
	
	if(reciverID == null){
		str = 'receiver='+receiver+'&phone='+phone;
		URL = './php/OnlineMsg.php';
	}
	else{
		str = 'uid='+reciverID;
		URL = './php/QRSendMsg.php';
	}
	
	if(type == 1) str += '&server_id='+server_id;
	if(content == null) content = '';
	str += '&content='+content+'&send_time='+send_time;
	
	var xhr = new XMLHttpRequest();
	xhr.open('POST',URL);
	xhr.setRequestHeader('Content-type','application/x-www-form-urlencoded');
	xhr.send(str);
	xhr.onreadystatechange = function(){
		if(xhr.status == 200 && xhr.readyState == 4){  //将得到的值设为全局变量
			var arry = JSON.parse(xhr.response);
			if(arry.status != 0){
				var str = '';
				if(arry.status == 4) str += '音频上传失败了呢<br>';
				else str += '服务器出现问题，请稍后重试哦<br>';
				showError(str);
			}
			else{
				if(type == 0)
					slide(document.getElementById('text'), document.getElementById('over'));
				else {
					slide( document.getElementById('voice'), document.getElementById('over'));
					document.querySelector('#voice a.but:nth-of-type(4)').click();
				}
			}
		}
	}
}

//取得微信sdk权限
function init_wxsdk(ctx, gra){
		var recordUrl = encodeURIComponent(location.href);
		var xhr = new XMLHttpRequest();
		xhr.open('POST','https://hemc.100steps.net/2017/wechat/Home/Public/getJsApi');
		xhr.setRequestHeader('Content-type','application/x-www-form-urlencoded');
		xhr.send('url='+recordUrl);
		xhr.onreadystatechange = function(){
			if(xhr.status == 200 && xhr.readyState == 4){  //将得到的值设为全局变量
					var data = JSON.parse(xhr.response);
					wx.config({  
						debug: false,  
						appId: data.appId,  
						timestamp:data.timestamp,  
						nonceStr:data.nonceStr,  
						signature:data.signature,  
						jsApiList: [  
								'startRecord','stopRecord', 
								'playVoice',  'pauseVoice' ,'onVoicePlayEnd' , 'uploadVoice'
							]  
			
					})
					wx.ready(function(){
						wx.onVoicePlayEnd({
							success: function () {
							audio_status = false ;
							drawImg(ctx, gra , 'img/play.png');
							}
						});
					});
			}
		}
}

// 取得通过扫码得到的id
function init_receiver(sface1){
	index = location.href.indexOf('ID');
	if(index == -1) return ;
	reciverID = location.href.substr(index+3);
	getReciver(reciverID, sface1);
}
//将id与后台交互取得姓名，并出去手机号
function getReciver(id,sface1){
 var xhr = new XMLHttpRequest();
	 xhr.open('POST','./php/QRGetMsg.php');
	 xhr.setRequestHeader('Content-type','application/x-www-form-urlencoded');
	 xhr.send('id='+id);
	 xhr.onreadystatechange = function(){
		if(xhr.status == 200 && xhr.readyState == 4){
			var arry = JSON.parse(xhr.response);
			if(arry.status == 0){
				var reciverInput = sface1.getElementsByTagName('input');
				
				reciverInput[0].value = arry.receiver;
				reciverInput[0].setAttribute('disabled','disabled');
				reciverInput[1].setAttribute('disabled','disabled');
				reciverInput[1].style.display = 'none';
				reciverInput[1].previousSibling.previousSibling.style.display = 'none';
				reciverInput[1].previousSibling.style.display = 'none';
			}
			else {  
				var str = '不存在这个网址哦<br>';
				showError(str);
			}
		}	
	}	
}

//切换标题函数
function changeTitle(but9, but10){
	var p = document.querySelector('#voice>p');
	if(but10.style.display == 'block') 
		p.innerHTML = '点击"完成"试听及重录~<br>(录音请控制在一分钟之内哦)';
	else if(but9.style.display == 'block')
		p.innerHTML = '梯仔已经录好了你的语音,<br>点击试听一下吧~';
	else
		p.innerHTML = '点击"开始"录制语音~<br>(录音请控制在一分钟之内哦)';

} 

//更新arrypic的图片，并进行图片个数的增删
function updataPic(){
	var pos = 0;
	for(var i = 0; i < arryPic.length; i ++)
		if(arryPic[i].x < (canvasWidth + 10) && arryPic[i].y > -10 && arryPic[i].alpha > 0.05) 
			arryPic[pos++] = arryPic[i];
	while(arryPic.length > pos)
		arryPic.pop();
	for(var i = 0 ;i < arryPic.length;i ++){
		arryPic[i].x += arryPic[i].vx;
		arryPic[i].y += arryPic[i].vy;
		arryPic[i].alpha -= 0.015;
	}
	for(var i = arryPic.length; i <= 4;i ++){
	 var aPic = {
		   x: X + 60 + Math.random()*30,
           y: 45 + Math.random()*75,
           rad: Math.random()*360,
		   url: parseInt(Math.random() * 3.9),
		   vx: 0.6 + 1 * Math.random() , 
		   vy:-1 + 1 * Math.random() ,
		   alpha:Math.random() * 0.4 + 0.6 
         }
		arryPic.push(aPic);
	}
}

//画播放和暂停时的三角形和2条竖线
function drawImg(ctx , gra , imgUrl){
	ctx.clearRect(0,0,canvasWidth,canvasHeight);
	drawBackCircle(ctx, gra);
	var image = new Image();
	image.src = imgUrl;
	image.onload = function(){
	  if(imgUrl.indexOf('play') != -1)
		ctx.drawImage(image, X - 15,Y - 22.5, 45, 45);
	  else 
		ctx.drawImage(image, X - 22.5,Y - 22.5, 45, 45);  
	}
}
//画路音乐的进度条 以及 时间  以及音符
function draw(ctx , gra, recordTime){

	ctx.clearRect(0,0, canvasWidth, canvasHeight);
	ctx.lineWidth = 10;
	
	drawBackCircle(ctx, '#dcdcdc');
	drawCircle(ctx , 6 * recordTime);
	var src = parseInt(recordTime / 60) + ':';
	if(parseInt(recordTime % 60) < 10) src += '0';
	src += parseInt(recordTime % 60);
	drawDigit(ctx, src);
	for(var i = 0;i < arryPic.length; i++)
		drawMusic(ctx, arryPic[i]);
}
//画背景圆
function drawBackCircle(ctx, strokeColor){
	ctx.strokeStyle = strokeColor;
	ctx.fillStyle = '#ffffff';
	ctx.beginPath();
	ctx.shadowColor = 'gray';
	ctx.shadowOffsetX = 0;
	ctx.shadowOffsetY = 0;
	ctx.shadowBlur = 30;
	ctx.arc(X, Y, 70, 0, 360 * Math.PI / 180);
	ctx.fill();
	ctx.shadowBlur = 0;
	ctx.stroke();
	ctx.closePath();
}
//画进度条圆	
function drawCircle(ctx, percen){
	ctx.strokeStyle = '#8ecacd';
	ctx.beginPath();
	ctx.arc(X, Y, 70, 270 * Math.PI / 180, (270 + percen) % 360 * Math.PI / 180, false);
	ctx.stroke();	
	ctx.closePath();
}
//画数字
function drawDigit(ctx, str){
	ctx.fillStyle = '#8ecacd';
	ctx.textAlign = 'center';
	ctx.textBaseline = 'middle';
	ctx.font = '40px serif';
	ctx.fillText(str, X, Y);
}

//画音符
function drawMusic(ctx, pic){
	ctx.save();
	var image = new Image();
	image.src = 'img/'+pic.url+'.png';
	ctx.beginPath();
	ctx.globalAlpha = pic.alpha;
	ctx.translate(pic.x, pic.y);
	ctx.rotate(pic.rad * Math.PI / 180);
	ctx.drawImage(image,0,0,35,35);
	ctx.closePath();
	ctx.restore();
}