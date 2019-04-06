
function upface(){
	init_wxsdk();
	if(IDurl != null)
		showFace() , share();
	addEventWithButton();
};

//生成
function showFace(type){
	
	slide(document.getElementById('codeinfo'), document.getElementById('showcode'));
	
	document.querySelector('#paint>img').setAttribute('src',IDurl);
	
}


function addEventWithButton(){
 	var links = document.getElementsByTagName('a');
	
	links[1].onclick = function(){			//生成收件人信息
		var receiver = document.getElementsByTagName('input')[0].value;
		var phone = document.getElementsByTagName('input')[1].value;
		
		if(receiver == '' || !(/^[1][3,4,5,7,8][0-9]{9}$/).test(phone)){  
		    var str = '';
			if(receiver == '') str += '你没有填名字哦<br>';
			if(!(/^[1][3,4,5,7,8][0-9]{9}$/).test(phone)) str += '你的手机号码格式不对<br>';
			showError(str);
				return ;
		}			
		AJAX(receiver , phone);
		return false;	
	}
	
	links[2].onclick = function(){			//返回content.html
		location.href = './content.html';
	} 
}

function AJAX(receiver , phone){
	var xhr = new XMLHttpRequest();
		xhr.open('POST','./php/QRCode.php');
		xhr.setRequestHeader('Content-type','application/x-www-form-urlencoded');
		xhr.send('receiver='+receiver+'&phone='+phone);
		xhr.onreadystatechange = function(){
			if(xhr.status == 200 && xhr.readyState == 4){
				var arry = JSON.parse(xhr.response);
				if(!arry.status) {
					IDurl = arry.url  ,ID = arry.id;
					showFace();
					share();
				}
				else {									
					var str = '服务器出现问题，请稍后重试哦<br>';
					showError(str);
				} 
			}
		}
}

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
								'onMenuShareTimeline','onMenuShareAppMessage'
							]  
			
					})
			}
		}
}

function share(){
 setTimeout(function(){
	var href = 'https://hemc.100steps.net/2018/time-capsule/online.html?ID='+ID;
	var imgurl = 'https://hemc.100steps.net/2018/time-capsule/img/capsule-moments.png';
	
	wx.onMenuShareTimeline({
		title: '时光胶囊 | 来给我写信吧', // 分享标题
		link: href, // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
		imgUrl: imgurl
	})
	wx.onMenuShareAppMessage({
		title: '时光胶囊 | 来给我写信吧', // 分享标题
		link: href, // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
		imgUrl:imgurl
	})
 },2000);
}