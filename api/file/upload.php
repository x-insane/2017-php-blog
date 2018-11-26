<?php
if($_FILES["file"]["name"] == "")
	echo "请选择文件";
else if ($_FILES["file"]["error"] > 0)
	echo "错误状态：" . $_FILES["file"]["error"];
else
{
	move_uploaded_file($_FILES["file"]["tmp_name"] , "../../uploads/" . iconv("UTF-8","gbk",$_FILES["file"]["name"]));
	echo "uploads/" . $_FILES["file"]["name"];
}