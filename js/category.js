function updatedata(e)
{
	document.getElementById("newname").value = e.target.dataset.name;
	document.getElementById("cat-ctrl").className = "";
	var caterrortip = document.getElementById("cat-error-tip");
	if(caterrortip)
		caterrortip.parentElement.removeChild(caterrortip);
	var delframe = document.getElementById("delframe");
	var moveframe = document.getElementById("moveframe");
	delframe.className = "hidden";
	moveframe.className = "hidden";
	$.get("api/category/is-empty.php?cid="+e.target.value,
	function(data,status)
	{
		if(status == "success")
		{
			var d = JSON.parse(data);
			var tip = document.getElementById("forcedeltip");
			var adel = document.getElementById("a-del");
			var aforcedel = document.getElementById("a-forcedel");
			if(d.result == "ok")
			{
				tip.className = "hidden";
				adel.className = "";
				aforcedel.className = "hidden";
			}
			else
			{
				tip.className = "";
				adel.className = "hidden";
				aforcedel.className = "";
			}
			delframe.className = "";
		}
	});
	$.get("api/category/getcatopt.php?cid="+e.target.value,
	function(data,status)
	{
		if(status == "success")
		{
			document.getElementById("movesel").innerHTML = 
				'<option value="0">顶级分类</option>' + data;
			moveframe.className = "";
		}
	});
}
function init()
{
	document.getElementById("cat-ctrl").className = "hidden";
	var opts = document.getElementsByName("category");
	for(var i=0;i<opts.length;++i)
	{
		opts[i].checked = false;
		opts[i].onclick = updatedata;
	}
}
function getRadioValue()
{
    var radio = document.getElementsByName("category");
    for (i=0; i<radio.length; i++)
    {
        if (radio[i].checked)
            return radio[i].value;
    }
    return -1;
}
function add()
{
	var name = document.getElementById("newcatname");
	var parent = document.getElementById("newcatparent");
	if(name.value == "")
	{
		name.focus();
		return;
	}
	$.post("api/category/add.php",{
		name:name.value,
		parent:parent.value,
		redirect:"manage",
		checked:getRadioValue()
	},function(data,status)
	{
		if(status == "success")
		{
			name.value = "";
			parent.value = 0;
			document.getElementById("catfrm").innerHTML = data;
			init();
			freshparentcat();
		}
	});
}
function freshparentcat()
{
	$.post("api/category/getcatopt.php",{},function(data,status)
	{
		if(status == "success")
		{
			document.getElementById("newcatparent").innerHTML = 
				'<option value="0">顶级分类</option>' + data;
		}
	});
}
function del()
{
	$.post("api/category/del.php",
	{
		cid:getRadioValue()
	},
	function(data,status)
	{
		if(status == "success")
		{
			document.getElementById("catfrm").innerHTML = data;
			init();
			freshparentcat();
		}
	});
}
function forcedel()
{
	$.post("api/category/del.php",
	{
		force:true,
		cid:getRadioValue()
	},
	function(data,status)
	{
		if(status == "success")
		{
			document.getElementById("catfrm").innerHTML = data;
			init();
			freshparentcat();
		}
	});
}
function move()
{
	$.post("api/category/setparent.php",
	{
		cid:getRadioValue(),
		parent:document.getElementById("movesel").value
	},
	function(data,status)
	{
		if(status == "success")
		{
			document.getElementById("catfrm").innerHTML = data;
			init();
			freshparentcat();
		}
	});
}
function rename()
{
	$.post("api/category/rename.php",
	{
		cid:getRadioValue(),
		name:document.getElementById("newname").value
	},
	function(data,status)
	{
		if(status == "success")
		{
			document.getElementById("catfrm").innerHTML = data;
			init();
			freshparentcat();
		}
	});
}
init();