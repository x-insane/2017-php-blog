function getRadioValue()
{
    var radio = document.getElementsByName("category");
    for (i=0; i<radio.length; i++)
    {
        if (radio[i].checked)
            return radio[i].value;
    }
}
function onaddcatfrm()
{
	document.getElementById("addcatctrlfrm").className = "hidden";
	document.getElementById("addcatfrm").className = "";
}
function cancelnewcat()
{
	document.getElementById("addcatctrlfrm").className = "";
	document.getElementById("addcatfrm").className = "hidden";
}
function addnewcat()
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
		checked:getRadioValue()
	},function(data,status)
	{
		if(status == "success")
		{
			name.value = "";
			parent.value = 0;
			cancelnewcat();
			document.getElementById("catfrm").innerHTML = data;
			freshparentcat();
		}
	});
}
function freshparentcat()
{
	$.get("api/category/getcatopt.php",function(data,status)
	{
		if(status == "success")
		{
			document.getElementById("newcatparent").innerHTML = 
				'<option value="0">顶级分类</option>' + data;
		}
	});
}