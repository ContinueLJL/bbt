
var ID = null , IDurl = null;										
var half_num = null , year_num = null;
var clientHeight = document.documentElement.clientHeight;

checkLogin();		
checkQRcode();


//检查用户是否绑定微信

function checkLogin(){			
		
	var xhr = new XMLHttpRequest();
	xhr.open('POST','./php/CheckLogin.php');
	xhr.setRequestHeader('Content-type','application/x-www-form-urlencoded');
	xhr.send('check=1');
	xhr.onreadystatechange = function(){
		if(xhr.status == 200 && xhr.readyState == 4){  //将得到的值设为全局变量
			var arry = JSON.parse(xhr.response);
			if(arry.status == 1)
				location.href = 'https://hemc.100steps.net/2017/wechat/Home/Index/index?state='+encodeURIComponent(location.href);
		}
	}

}


//检查当前是否已经生成二维码 type = 1 为 生成二维码 type = 2 为查询信件
function checkQRcode(){

	var type = null;
	if(location.href.indexOf('code') != -1) type = 1;
	if(location.href.indexOf('query') != -1) type = 2;
	if(type == null) return ;

	 var xhr = new XMLHttpRequest();
	 xhr.open('POST','./php/CheckQRCode.php');
	 xhr.setRequestHeader('Content-type','application/x-www-form-urlencoded');
	 xhr.send('check=1&type='+type);
	 xhr.onreadystatechange = function(){
		if(xhr.status == 200 && xhr.readyState == 4){  //将得到的值设为全局变量
			var arry = JSON.parse(xhr.response);
			if(type == 1 && arry.status == 0)
				IDurl = arry.url , ID = arry.id;
			if(type == 2 && arry.status == 0)
				half_num = arry.half_num , year_num = arry.year_num;
			document.readystate == 'loading'?window.addEventListener('load',upface):upface();	
			 
		}
	}	
}


function slide(left , right){
	// alert('sdfasd');
	window.scrollTo(0,0);
	left.setAttribute('class','slideUp');
	right.setAttribute('class','slideLeft');
}


	//focus input 添加动画
window.addEventListener('load',function(){
	
	var inputs = document.querySelectorAll('input[type=text],#unline textarea');
	for(var i = 0;i < inputs.length; i++){
		inputs[i].addEventListener('focus', function(){
			this.setAttribute('class', 'lineLight');
			this.previousSibling.previousSibling.setAttribute('class','lineFont');
		
		});
		inputs[i].addEventListener('blur', function(){
			this.setAttribute('class', '');
			this.previousSibling.previousSibling.setAttribute('class','');
		});
	}
})



//showError && removeRoot 是生成和去掉提示信息
function showError(str){
	var contain = document.createElement('div');
	var stage = document.createElement('div');
	var but = document.createElement('button');
	var p = document.createElement('p');
	p.innerHTML = str;
	but.innerHTML = '确定';
	stage.innerHTML = p.outerHTML + but.outerHTML;
	contain.innerHTML = stage.outerHTML ;
	contain.setAttribute('id','warm');
	contain.style.height = clientHeight + 'px';
	document.getElementsByTagName('body')[0].appendChild(contain);
	document.querySelector('#warm button').addEventListener('click',removeRoot);
}
function removeRoot(){
	var warm = document.getElementById('warm');
	document.body.removeChild(warm);
	
}
	