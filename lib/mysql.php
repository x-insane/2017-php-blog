<?php
class mysql extends mysqli
{
	public function __construct($database)
	{
		require( dirname( __FILE__ ) . "/../ini.php");
		parent::__construct($mysql_addr,$mysql_user,$mysql_pwd,$database);
		if($mysql_debug && mysqli_connect_error())
		{
    		die('Connect Error ('.mysqli_connect_errno().') '.mysqli_connect_error());
		}
		if (!parent::set_charset("utf8") && $mysql_debug)
		{
		    printf("Error loading character set utf8: %s\n", mysqli_connect_error());
		    exit();
		}
	}
}
class mysql_user extends mysql
{
	public function __construct()
	{
		parent::__construct("user");
	}
	public function getnamebyid($id)
	{
		$result = parent::query("SELECT name FROM user WHERE id='$id'");
		$row = $result->fetch_array(MYSQLI_BOTH);
		$result->free();
		return $row["name"];
	}
	public function getidbyname($name)
	{
		$result = parent::query("SELECT id FROM user WHERE name='$name'");
		$row = $result->fetch_array(MYSQLI_BOTH);
		$result->free();
		return $row["id"];
	}
	public function getlevel($id=null)
	{
		if(!$id)
		{
			if(isset($_SESSION["userid"]))
				$id = $_SESSION["userid"];
			else
				return "none";
		}
		$result = parent::query("SELECT level FROM user WHERE id='$id'");
		if($levelitem = $result->fetch_array(MYSQLI_BOTH))
		{
			$result->free();
			return $levelitem["level"];
		}
		$result->free();
		return "none";
	}
	public function setcode($name,$key,$content=null,$uid=null,$lasttime=1800,$fresh=60)
	{
		if(!$uid)
		{
			if(!isset($_SESSION["userid"]))
				return "not_login";
			$uid = $_SESSION["userid"];
		}
		date_default_timezone_set('Etc/GMT-8');
		$t = time();
		$now = date('Y-m-d H:i:s',$t);
		if($result = parent::query("SELECT id,canfreshtime FROM verifylist WHERE uid='$uid' AND todo='$name'"))
		{
			$rs = $result->fetch_array(MYSQLI_BOTH);
			$canfresh = strtotime($rs["canfreshtime"]);
			if($canfresh > $t)
				return "waiting";
			$id = $rs["id"];
			parent::query("DELETE FROM verifylist WHERE id='$id'");
		}
		$deadline = date('Y-m-d H:i:s',$t+$lasttime);
		$canfreshtime = date('Y-m-d H:i:s',$t+$fresh);
		if(parent::query("INSERT INTO verifylist(uid,todo,code,content,addtime,canfreshtime,deadline) VALUES('$uid','$name','$key','$content','$now','$canfreshtime','$deadline')"))
		{
			return "ok";
		}
		return "mysql_error";
	}
	public function getcode($uid,$name)
	{
		date_default_timezone_set('Etc/GMT-8');
		$now = time();
		if($result = parent::query("SELECT id,code,deadline,pass FROM verifylist WHERE uid='$uid' AND todo='$name'"))
		{
			$rs = $result->fetch_array(MYSQLI_BOTH);
			$deadline = strtotime($rs["deadline"]);
			$id = $rs["id"];
			if($now > $deadline && !$rs["pass"])
			{
				parent::query("DELETE FROM verifylist WHERE id='$id'");
				return null;
			}
			return $rs["code"];
		}
		else
			return null;
	}
	public function unsetcode($uid,$name)
	{
		if(parent::query("DELETE FROM verifylist WHERE uid='$uid' AND todo='$name'"))
			return "ok";
		return "mysql_error";
	}
	public function verifycode($uid,$name,$code,$content_unique=false)
	{
		$src = $this->getcode($uid,$name);
		if(!$src)
			return "code_unset";
		if($src == $code)
		{
			if($content_unique)
			{
				if($re = parent::query("SELECT id,content FROM verifylist WHERE uid='$uid' AND todo='$name'"))
				{
					$rs = $re->fetch_array(MYSQLI_BOTH);
					$id = $rs["id"];
					$content = $rs["content"];
					$re = parent::query("SELECT pass FROM verifylist WHERE content='$content' AND todo='$name' AND uid!='$uid'");
					while($rs = $re->fetch_array(MYSQLI_BOTH))
					{
						if($rs["pass"])
						{
							parent::query("DELETE FROM verifylist WHERE id=$id");
							return "mailbox_has_been_bound";
						}
					}
				}
			}
			parent::query("UPDATE verifylist SET pass=TRUE WHERE uid='$uid' AND todo='$name'");
			return "pass";
		}
		return "wrong_code";
	}
	public function getcodestatus($uid,$name)
	{
		date_default_timezone_set('Etc/GMT-8');
		$now = time();
		$result = parent::query("SELECT id,pass,deadline FROM verifylist WHERE uid=$uid AND todo='$name'");
		if($rs = $result->fetch_array(MYSQLI_BOTH))
		{
			$deadline = strtotime($rs["deadline"]);
			$id = $rs["id"];
			if($rs["pass"])
				return "pass";
			if($now > $deadline)
			{
				parent::query("DELETE FROM verifylist WHERE id='$id'");
				return "out_of_time";
			}
			else
				return "waiting";
		}
		return "no_code";
	}
	public function getcodecontent($uid,$name)
	{
		if($result = parent::query("SELECT content FROM verifylist WHERE uid='$uid' AND todo='$name'"))
		{
			$rs = $result->fetch_array(MYSQLI_BOTH);
			return $rs["content"];
		}
		else
			return null;
	}
}
?>