function showdatamenu()
{
	document.getElementById("appitems").style.display = "none";
	document.getElementById("dataitems").style.display = "block";
	// document.getElementById("appmenu").setAttribute("class","");
	// document.getElementById("datamenu").setAttribute("class","menuchecked");
}
function showappmenu()
{
	document.getElementById("dataitems").style.display = "none";
	document.getElementById("appitems").style.display = "block";
	// document.getElementById("datamenu").setAttribute("class","");
	// document.getElementById("appmenu").setAttribute("class","menuchecked");
}
function clearmenuitems()
{
	document.getElementById("dataitems").style.display = "none";
	document.getElementById("appitems").style.display = "none";
}
document.getElementById("content").onclick = clearmenuitems;
document.getElementById("content").onscroll = clearmenuitems;

function tologin()
{
	$.get("seturl.php?url=individual.html");
	location.href = "login.html";
}
function logout()
{
	$.get("logout.php");
	tologin();
}
var d = null;
var entertodo = null;

function basedata()
{
	entertodo = null;
	var s="<div id='basedataframe'>"+
	"<div><p>基本资料</p>"+
		"<table>"+
			"<tr><td>用户名：</td><td>"+d.name+"</td></tr>"+
			"<tr><td>性别：</td><td>"+d.sex+"</td></tr>"+
			"<tr><td>邮箱：</td><td>"+d.mailbox+"</td></tr>"+
			"<tr><td>手机号：</td><td>"+d.tel+"</td></tr>"+
			"<tr><td>QQ号：</td><td>"+d.qq+"</td></tr>"+
			"<tr><td>真实姓名：</td><td>"+d.true_name+"</td></tr>"+
			"<tr><td>最后登录：</td><td>"+d.ltime+"</td></tr>"+
			"<tr><td>注册时间：</td><td>"+d.rtime+"</td></tr>"+
		"</table>"+
		"<p><button class='bn1' onclick='modifybasedata()'>修改</button></p>"+
	"</div></div>";
	document.getElementById("content").innerHTML = s;
	history.pushState(history.state,null,"individual.html?request=basedata");
}
function modifybasedata()
{
	var s="<div id='basedataframe'>"+
	"<div><p>基本资料</p>"+
		"<table>"+
			"<tr><td>用户名：</td><td>"+d.name+"</td></tr>"+
			"<tr><td>性别：</td><td><select id='sex' value='"+d.sex+"''>"+
				"<option>保密</option>"+
				"<option>男</option>"+
				"<option>女</option>"+
				"</select></td></tr>"+
			"<tr><td>邮箱：</td><td><input id='mailbox' value='"+d.mailbox+"'></td></tr>"+
			"<tr><td>手机号：</td><td><input id='tel' value='"+d.tel+"'></td></tr>"+
			"<tr><td>QQ号：</td><td><input id='qq' value='"+d.qq+"'></td></tr>"+
			"<tr><td>真实姓名：</td><td><input id='true_name' value='"+d.true_name+"'></td></tr>"+
			"<tr><td>最后登录：</td><td>"+d.ltime+"</td></tr>"+
			"<tr><td>注册时间：</td><td>"+d.rtime+"</td></tr>"+
		"</table>"+
		"<p>"+
			"<button class='bn2' onclick='basedata()'>取消</button>"+
			"&nbsp;&nbsp;"+
			"<button id='bn_savebasedata' class='bn1' onclick='savebasedata()'>保存</button>"+
		"</p>"+
	"</div></div>";
	entertodo = savebasedata;
	document.getElementById("content").innerHTML = s;
}
function savebasedata()
{
	entertodo = null;
	var pass = true;
	var sex = document.getElementById("sex");
	if(!testsex(sex.value))
	{
		sex.style.borderColor = "red";
		if(pass)
			sex.focus();
		pass = false;
	}
	else
		sex.style.borderColor = "#D5D5D5";
	var mailbox = document.getElementById("mailbox");
	if(!testmailbox(mailbox.value))
	{
		mailbox.style.borderColor = "red";
		if(pass)
			mailbox.focus();
		pass = false;
	}
	else
		mailbox.style.borderColor = "#D5D5D5";
	var tel = document.getElementById("tel");
	if(!testtel(tel.value))
	{
		tel.style.borderColor = "red";
		if(pass)
			tel.focus();
		pass = false;
	}
	else
		tel.style.borderColor = "#D5D5D5";
	var qq = document.getElementById("qq");
	if(!testqq(qq.value))
	{
		qq.style.borderColor = "red";
		if(pass)
			qq.focus();
		pass = false;
	}
	else
		qq.style.borderColor = "#D5D5D5";
	var true_name = document.getElementById("true_name");
	if(!testtrue_name(true_name.value))
	{
		true_name.style.borderColor = "red";
		if(pass)
			true_name.focus();
		pass = false;
	}
	else
		true_name.style.borderColor = "#D5D5D5";
	if(!pass)
	{
		entertodo = savebasedata;
		return;
	}
	var bn = document.getElementById("bn_savebasedata");
	bn.disabled = true;
	bn.innerHTML = "正在保存…";
	bn.style.backgroundColor = "#C1CCC7";
	bn.style.cursor = "default";
	$.post("individual.php",
	{
		name:d.name,
		sex:sex.value,
		mailbox:mailbox.value,
		tel:tel.value,
		qq:qq.value,
		true_name:true_name.value
	},function(data,status)
	{
		if(status=="success")
		{
			var re = JSON.parse(data);
			if(re.result == "ok")
			{
				d.sex = sex.value;
				d.mailbox = mailbox.value;
				d.tel = tel.value;
				d.qq = qq.value;
				d.true_name = true_name.value;
				basedata();
			}
			else
			{
				bn.disabled = false;
				bn.innerHTML = "修改";
				bn.style.backgroundColor = "#BFF7AE";
				bn.style.cursor = "pointer";
				alert(re.result);
				entertodo = savebasedata;
			}
		}
	});
}
function pwddata()
{
	entertodo = null;
	var s='<div id="modifypwdframe">'+
		'<div class="title">修改密码</div>'+
		'<div class="itemframe">'+
			'<img src="img/userpwd.png" height="23px" align="left">'+
			'<input type="password" id="oldpwd" placeholder="请输入旧密码">'+
		'</div>'+
		'<div class="itemframe">'+
			'<img src="img/userpwd.png" height="23px" align="left">'+
			'<input type="password" id="newpwd" placeholder="请输入新密码">'+
		'</div>'+
		'<div class="itemframe">'+
			'<img src="img/userpwd.png" height="23px" align="left">'+
			'<input type="password" id="renewpwd" placeholder="请重复输入新密码">'+
		'</div>'+
		'<div class="vcodeframe">'+
			'<input type="text" id="vcode" placeholder="请输入验证码">'+
			'<img title="点击刷新" id="vcodeimg" src="../extends/captcha.php" align="right" onclick="vcodefresh()"></img>'+
		'</div><br/><span id="tip_resetpwd"></span>'+
		'<button class="bn1" id="modifypwd" onclick="modifypwd()">修 改</button>'+
		'</div>';
	document.getElementById("content").innerHTML = s;
	entertodo = modifypwd;
	history.pushState(history.state,null,"individual.html?request=modifypwd");
}
function vcodefresh()
{
	document.getElementById("vcodeimg").src='../extends/captcha.php?'+Math.random();
	document.getElementById("vcode").value = "";
	document.getElementById("vcode").focus();
}
function modifypwd()
{
	var oldpwd = document.getElementById("oldpwd");
	var newpwd = document.getElementById("newpwd");
	var renewpwd = document.getElementById("renewpwd");
	var vcode = document.getElementById("vcode");
	var tip = document.getElementById("tip_resetpwd");
	tip.innerHTML= "";
	if(oldpwd.value == "")
	{
		tip.innerHTML= "请输入旧密码！<br/>";
		oldpwd.focus();
		return;
	}
	if(newpwd.value == "")
	{
		tip.innerHTML= "请输入新密码！<br/>";
		newpwd.focus();
		return;
	}
	if(renewpwd.value == "")
	{
		tip.innerHTML= "请重复输入新密码！<br/>";
		renewpwd.focus();
		return;
	}
	if(newpwd.value != renewpwd.value)
	{
		tip.innerHTML= "两次输入的新密码不相同！<br/>";
		renewpwd.focus();
		return;
	}
	if(oldpwd.value == newpwd.value)
	{
		tip.innerHTML= "新旧密码不能相同！<br/>";
		newpwd.focus();
		return;
	}
	if(vcode.value == "")
	{
		tip.innerHTML= "请输入验证码！<br/>";
		vcode.focus();
		return;
	}
	entertodo = null;
	var bn = document.getElementById("modifypwd");
	bn.disabled = true;
	bn.innerHTML = "提交中…";
	$.post("resetpwd.php",
	{
		oldpwd:oldpwd.value,
		newpwd:newpwd.value,
		vcode:vcode.value
	},function(data,status)
	{
		if(status == "success")
		{
			var d = JSON.parse(data);
			if(d.result == "ok")
			{
				if(d.url == "")
				{
					pwddata();
					alert("修改成功！");
				}
				else
					location.href = d.url;
			}
			else if(d.result == "wrong_vcode")
			{
				tip.innerHTML = "验证码错误！<br/>";
				vcodefresh();
				bn.disabled = false;
				bn.innerHTML = "修 改";
			}
			else if(d.result == "wrong_pwd")
			{
				tip.innerHTML = "旧密码错误！<br/>";
				vcodefresh();
				bn.disabled = false;
				bn.innerHTML = "修 改";
				oldpwd.focus();
			}
			else
			{
				tip.innerHTML = d.result + "<br/>";
				vcodefresh();
				bn.disabled = false;
				bn.innerHTML = "修 改";
			}
			if(d.result != "ok")
				entertodo = modifypwd;
		}
	});
}
function securitydata()
{
	entertodo = null;
	var sobj1 = null;
	var sobj2 = null;
	var sec2="";
	var sec1="";
	function show()
	{
		var s="<div id='secframe'>"+
				"<div>"+
					"<div class='title'>第二代密保</div>"+
					sec2+
					"<div class='info_tip'>第二代密保工具通过绑定安全邮箱保护您的帐号，当您忘记密码时，可以通过该邮箱重置您的密码。</div>"+
				"</div>"+
				"<div>"+
					"<div class='title'>第一代密保</div>"+
					sec1+
					"<div class='info_tip'>第一代密保工具通过设定一个问题和答案保护您的帐号，当您忘记密码时，可以通过回答问题重置您的密码。</div>"+
				"</div>"+
			"</div>";
		document.getElementById("content").innerHTML = s;
		history.pushState(history.state,null,"individual.html?request=security");
	}
	function freshsec1(callback,argu)
	{
		$.get("setsecurity_1.0.php",function(data,status)
		{
			if(status == "success")
			{
				sobj1 = JSON.parse(data);
				combinesec1();
				if(callback)
					callback(argu);
			}
		});
	}
	function freshsec2(callback,argu)
	{
		$.get("individual.php?request=securitydata",function(data,status)
		{
			if(status == "success")
			{
				sobj2 = JSON.parse(data);
				if(sobj2.result == "ok")
				{
					combinesec2();
					if(callback)
						callback(argu);
				}
			}
		});
	}
	function combinesec1()
	{
		if(sobj1.result == "ok")
		{
			sec1 = "<div>"+
				"<div>您已设定安全问题：</div>"+
				"<div>"+
					"<div class='ques_ans'><input id='ques' type='text' value='"+sobj1.ques+"' placeholder='问题'></div>"+
					"<div class='ques_ans'><input id='ans' type='text' placeholder='点此修改答案'></div>"+
					"<div id='tip_sec1'></div>"+
					"<div><a href='javascript:void(0)' id='bn_updatesec1'>修改</a></div>"+
				"</div>"+
			"</div>";
		}
		else if(sobj1.result == "no_ques")
		{
			sec1 = "<div>"+
				"<div>您还未设定安全问题，建议马上设定</div>"+
				"<div>"+
					"<div class='ques_ans'><input id='ques' type='text' placeholder='问题'></div>"+
					"<div class='ques_ans'><input id='ans' type='text' placeholder='答案'></div>"+
					"<div id='tip_sec1'></div>"+
					"<div><a href='javascript:void(0)' id='bn_updatesec1'>保存</a></div>"+
				"</div>"+
			"</div>";
		}
	}
	function combinesec2()
	{
		if(sobj2.bind=="bound")
		{
			sec2="<div>"+
				"<div>您已绑定安全邮箱：</div>"+
				"<div class='mailbox'><span>"+sobj2.mailbox+"</span>"+
				"<a href='javascript:unbindmailbox()'>取消绑定</a></div>"+
			"</div>";
		}
		else if(sobj2.bind=="binding")
		{
			sec2="<div>"+
				"<div>您正在绑定安全邮箱：</div>"+
				"<div class='mailbox'><span>"+sobj2.mailbox+"</span>"+
				"<a href='javascript:unbindmailbox()'>取消绑定</a></div>"+
				"<div>请尽快登陆您的邮箱进行身份验证。</div>"+
			"</div>";
		}
		else if(sobj2.bind=="no_bind")
		{
			sec2="<div>"+
				"<div>您尚未绑定安全邮箱</div>"+
				"<div class='mailbox'><input id='bind_mailbox' type='text' placeholder='请输入您想绑定的邮箱地址' value='"+sobj2.mailbox+"'>"+
				"<a id='bn_bindmailbox' href='javascript:bindmailbox()'>开始绑定</a></div>"+
			"</div>";
		}
	}
	function updatesec1()
	{
		var ques = document.getElementById("ques");
		var ans = document.getElementById("ans");
		if(ques.value == "")
		{
			document.getElementById("tip_sec1").innerHTML = "请输入问题！";
			return;
		}
		if(ans.value == "")
		{
			document.getElementById("tip_sec1").innerHTML = "请输入答案！";
			return;
		}
		$.post("setsecurity_1.0.php",{ques:ques.value,ans:ans.value},function(data,status)
		{
			if(status == "success")
			{
				var re = JSON.parse(data);
				if(re.result == "ok")
				{
					alert("修改成功！");
					securitydata();
					return;
				}
			}
		});
	}
	freshsec2(freshsec1,function()
	{
		combinesec2();
		combinesec1();
		show();
		document.getElementById("bn_updatesec1").onclick = updatesec1;
	});
}
function unbindmailbox()
{
	$.get("bindmailbox.php?request=unbind",
		function(data,status)
		{
			if(status == "success")
			{
				location.reload();
			}
		});
}
function bindmailbox()
{
	var mailbox = document.getElementById("bind_mailbox");
	if(mailbox.value == "")
	{
		alert("请输入邮箱地址！");
		mailbox.focus();
		return;
	}
	if(!testmailbox(mailbox.value))
	{
		alert("该邮箱地址无效！");
		mailbox.focus();
		return;
	}
	var bn = document.getElementById("bn_bindmailbox");
	bn.disabled = true;
	bn.value = "正在请求..";
	$.post("bindmailbox.php",{mailbox:mailbox.value},function(data,status)
	{
		if(status == "success")
		{
			var re = JSON.parse(data);
			if(re.result == "ok")
			{
				alert("验证邮件发送成功，请注意查收！");
				location.reload();
				return;
			}
			else
				alert(re.result);
		}
	});
}
function getbasedata(callback)
{
	$.get("individual.php?request=basedata",function(data,status)
	{
		if(status == "success")
		{
			d = JSON.parse(data);
			if(d.result == "ok")
			{
				var s="";
				for(var i=0;i<d.applist.length;++i)
					s+="<div>"+d.applist[i]+"</div>";
				s+="<div>管理</div>";
				document.getElementById("appitems").innerHTML = s;
				document.getElementById("appitems_side").innerHTML = 
					"<span>我的应用</span>" + s;
				callback();
			}
			else if(d.result == "not_login")
				tologin();
			else
			{
				alert(d.result);
			}
		}
	});
}
getbasedata(function()
{
	if(request == "basedata")
		basedata();
	else if(request == "modifypwd")
		pwddata();
	else if(request == "security")
		securitydata();
});
document.onkeydown = function (event)
{
    var e = event || window.event || arguments.callee.caller.arguments[0];
    if (e && e.keyCode == 13 && entertodo)
        entertodo();
};