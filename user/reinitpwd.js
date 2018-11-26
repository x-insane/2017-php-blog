var statu = 0;
var info = new Object;
function loaddata()
{
	$.get("reinitpwd.php?request=getstatu",function(data,status)
	{
		if(status == "success")
		{
			var re = JSON.parse(data);
			if(re.result == "prepare")
				statu = 0;
			else
			{
				info.mailbox = re.mailbox;
				info.ques = re.ques;
				info.name = re.name;
				if(re.result == "need method")
					statu = 1;
				else if(re.result == "ques&ans method")
					statu = 2;
				else if(re.result == "mailbox method")
					statu = 3;
				else if(re.result == "set password")
					statu = 6;
				else 
					statu = 0;
			}
			loadbody();
		}
	});
// statu ==
// 0 -- prepare
// 1 -- need method
// 2 -- ques&ans method *
// 3 -- mailbox method *
// 4 -- verifying
// 5 -- verify wrong
// 6 -- set password
}
function vcodefresh()
{
	document.getElementById("vcodeimg").src='../extends/captcha.php?'+Math.random();
	document.getElementById("vcode").value = "";
	document.getElementById("vcode").focus();
}
function loadques()
{
	if(statu == 2)
		return;
	var bn_mail = document.getElementById("method_mailbox");
	var bn_ques = document.getElementById("method_ques_ans");
	bn_mail.className = "";
	bn_ques.className = "checked";
	var body = document.getElementById("method_body");
	document.getElementById("next").innerHTML = "验 证";
	document.getElementById("tip").innerHTML = "";
	if(info.ques == "")
	{
		body.innerHTML = "您没有设置问题，不能通过此方法重置密码。";
		return;
	}
	body.innerHTML = 
	'<div class="itemframe">'+
		'<img src="img/username.png" height="23px" align="top">'+
		'<input type="text" id="ques">'+
	'</div>'+
	'<div class="itemframe">'+
		'<img src="img/username.png" height="23px" align="top">'+
		'<input type="text" id="ans" placeholder="答案">'+
	'</div>';
	var ques = document.getElementById("ques");
	ques.disabled = true;
	ques.style.backgroundColor = "white";
	ques.value = info.ques;
	statu = 2;
}
function loadmail()
{
	if(statu == 3)
		return;
	var bn_mail = document.getElementById("method_mailbox");
	var bn_ques = document.getElementById("method_ques_ans");
	bn_mail.className = "checked";
	bn_ques.className = "";
	var body = document.getElementById("method_body");
	document.getElementById("next").innerHTML = "验 证";
	document.getElementById("tip").innerHTML = "";
	if(!info.mailbox || info.mailbox == "")
	{
		body.innerHTML = "您没有绑定安全邮箱，不能通过此方法重置密码。";
		return;
	}
	body.innerHTML = '<div id="mail_info">'+info.mailbox+'</div>'+
		'<div><button id="bn_request_mail" onclick="requestsendmail()">'+
		'点击立即发送验证邮件</button></div>';
	statu = 3;
}
function requestsendmail()
{
	if(statu != 3)
		return;
	var bn_mail = document.getElementById("bn_request_mail");
	bn_mail.innerHTML = "正在发送...请稍等...";
	bn_mail.disabled = true;
	$.post("reinitpwd.php",{method:"mailbox"},function(data,status)
	{
		if(status == "success")
		{
			var re = JSON.parse(data);
			if(re.result == "ok")
			{
				alert("验证邮件已发送，请注意查收！");
				location.reload();
				return;
			}
			else
			{
				alert(re.result);
				location.reload();
				return;
			}
		}
	});
}
function loadbody()
{
	if(statu == 5)
	{
		return;
	}
	if(statu <= 3 || statu == 6)
	{
		document.getElementById("body").innerHTML = '<div>'+
				'<div class="title">'+
				(statu == 6? '重设密码':'找回密码')+
				' <span id="tip"></span></div>'+
				'<div class="itemframe">'+
					'<img src="img/username.png" height="23px" align="top">'+
					'<input type="text" id="name" placeholder="用户名">'+
				'</div>'+
				(statu == 0?('<div class="vcodeframe" id="vcodeframe">'+
					'<input type="text" id="vcode" placeholder="请输入验证码">'+
					'<img title="点击刷新" id="vcodeimg" src="../extends/captcha.php" align="right" onclick="vcodefresh()"></img>'+
				'</div>'):((statu>=1&&statu<=4)?('<div id="method_frame">'+
						'<div>请选择验证方式：</div>'+
						'<div id="method_mailbox" onclick="loadmail()">邮箱验证(推荐)</div>'+
						'<div id="method_ques_ans" onclick="loadques()">问题答案验证</div>'+
					'</div>'+
				'<div id="method_body"></div>'):''))+
				(statu==6?('<div class="itemframe">'+
					'<img src="img/userpwd.png" height="23px" align="top">'+
					'<input type="password" id="newpwd" placeholder="请输入新密码">'+
					'</div><div class="itemframe">'+
					'<img src="img/userpwd.png" height="23px" align="top">'+
					'<input type="password" id="renewpwd" placeholder="请重复输入新密码">'+
					'</div>'):'')+
				'<p>'+
					'<a href="login.html">记得密码?直接登录</a>&nbsp;'+
					'<a href="register.html">没有账号?我要注册</a>'+
				'</p>'+
				'<center>'+
					'<button class="submit" id="prev" onclick="prev()">取 消</button>&nbsp;&nbsp;'+
					'&nbsp;&nbsp;<button class="submit" id="next" onclick="next()">'+
					(statu==2||statu==3?'验 证':(statu==6?'修改':'下一步'))+'</button>'+
				'</center>'+
			'</div>';
		if(statu == 0)
		{
			document.getElementById("prev").style.display = "none";
			if(info.name)
				document.getElementById("name").value = info.name;
		}
		else if(statu == 1)
		{
			document.getElementById("prev").style.display = "inline";
			// document.getElementById("vcodeframe").style.display = "none";
			var name = document.getElementById("name");
			name.value = info.name;
			name.disabled = true;
			name.style.backgroundColor = "white";
		}
		else if(statu == 6)
		{
			var name = document.getElementById("name");
			name.value = info.name;
			name.disabled = true;
			name.style.backgroundColor = "white";
		}
	}
}
function prev()
{
	if((statu >= 1 && statu <= 4) || statu==6)
	{
		$.get("reinitpwd.php?request=unbinduser",function(data,status)
		{
			if(status == "success")
			{
				var re = JSON.parse(data);
				if(re.result == "ok")
				{
					statu = 0;
					loadbody();
					return;
				}
				else
					tip.innerHTML = re.result;
			}
		});
	}
}
function next()
{
	var tip = document.getElementById("tip");
	tip.innerHTML = "";
	if(statu == 0)
	{
		var name = document.getElementById("name");
		var vcode = document.getElementById("vcode");
		if(name.value == "")
		{
			tip.innerHTML = "请输入用户名！";
			name.focus();
			return;
		}
		if(vcode.value == "")
		{
			tip.innerHTML = "请输入验证码！";
			vcode.focus();
			return;
		}
		var bn_next = document.getElementById("next");
		bn_next.disabled = true;
		bn_next.style.cursor = "normal";
		$.get("reinitpwd.php?request=binduser&name="+name.value+"&vcode="+vcode.value,
			function(data,status)
		{
			if(status == "success")
			{
				var re = JSON.parse(data);
				if(re.result == "ok")
				{
					statu = 1;
					loaddata();
					return;
				}
				else if(re.result == "wrong_vcode")
				{
					tip.innerHTML = "验证码错误！";
					bn_next.disabled = false;
					bn_next.style.cursor = "pointer";
					vcodefresh();
				}
				else
				{
					tip.innerHTML = re.result;
					bn_next.disabled = false;
					bn_next.style.cursor = "pointer";
					vcodefresh();
				}
			}
		});
	}
	else if(statu == 1)
	{
		tip.innerHTML = "请选择验证方式";
		return;
	}
	else if(statu == 2)
	{
		if(info.ques == "")
		{
			tip.innerHTML = "请选择其他验证方式";
			return;
		}
		var ans = document.getElementById("ans");
		if(ans.value == "")
		{
			tip.innerHTML = "请输入答案";
			return;
		}
		statu = 4;
		tip.innerHTML = "正在验证中,请稍等..";
		$.post("reinitpwd.php",{method:"ques_ans",ans:ans.value},
		function(data,status)
		{
			if(status == "success")
			{
				var re = JSON.parse(data);
				if(re.result == "ok")
				{
					statu = 6;
					loadbody();
					return;
				}
				else if(re.result == "wrong_ans")
				{
					tip.innerHTML = "答案错误";
					statu = 2;
					return;
				}
				else
				{
					tip.innerHTML = re.result;
					statu = 2;
					return;
				}
			}
		});
	}
	else if(statu == 3)
	{
		var bn_mail = document.getElementById("bn_request_mail");
		if(bn_mail.disabled)
			return;
		requestsendmail();
	}
	else if(statu == 4)
	{
		tip.innerHTML = "正在验证中，请稍等";
		return;
	}
	else if(statu == 6)
	{
		var newpwd = document.getElementById("newpwd");
		var renewpwd = document.getElementById("renewpwd");
		var bn_next = document.getElementById("next");
		if(newpwd.value == "")
		{
			tip.innerHTML = "请输入新密码！";
			newpwd.focus();
			return;
		}
		if(renewpwd.value == "")
		{
			tip.innerHTML = "请再次输入新密码！";
			renewpwd.focus();
			return;
		}
		if(newpwd.value != renewpwd.value)
		{
			tip.innerHTML = "<br/><br/>请确保两次输入的密码相同！";
			renewpwd.focus();
			return;
		}
		bn_next.disabled = true;
		tip.innerHTML = "正在提交...";
		$.post("reinitpwd.php",{method:"setpwd",newpwd:newpwd.value},
			function(data,status)
		{
			if(status == "success")
			{
				var re = JSON.parse(data);
				if(re.result == "ok")
				{
					alert("修改成功！请用新密码登陆。");
					location.href = "login.html";
					return;
				}
				else
				{
					alert(re.result);
					location.reload();
				}
			}
		});
	}
}
loaddata();
document.onkeydown = function (event)
{
    var e = event || window.event || arguments.callee.caller.arguments[0];
    if (e && e.keyCode == 13)
        next();
};