function $()
{
}
$.get = function(url,callback)
{
	var obj=new XMLHttpRequest();
	obj.open('GET',url);
	obj.onreadystatechange=function()
	{
		if(obj.readyState == 4)
		{
			if(!callback)
				return;
			if(obj.status == 200 || obj.status == 304)
				callback(obj.responseText,"success");
			else
				callback(obj.responseText,obj.status);
		}
	}
	obj.send();
}
$.post = function(url,argulist,callback)
{
	var obj=new XMLHttpRequest();
	obj.open('POST',url);
	obj.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	obj.onreadystatechange=function()
	{
		if(obj.readyState == 4)
		{
			if(!callback)
				return;
			if(obj.status == 200 || obj.status == 304)
				callback(obj.responseText,"success");
			else
				callback(obj.responseText,obj.status);
		}
	}
	var s = new String;
	for (var x in argulist)
		s += x+"="+argulist[x].toString().replace("&","%26")+"&";
	if(s.length == 0)
		obj.send("");
	else
		obj.send(s.substr(0,s.length-1));
}
function getQueryString(name)
{
	var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i"); 
	var r = window.location.search.substr(1).match(reg); 
	if (r != null)
		return unescape(r[2]);
	return null; 
}