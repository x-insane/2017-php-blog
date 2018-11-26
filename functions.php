<?php
require_once( dirname(__FILE__) . "/config.php");
function getlevel($uid)
{
	global $blog_db;
	$re = $blog_db->query("SELECT * FROM user WHERE uid=$uid");
	$rs = $re->fetch_array(MYSQLI_BOTH);
	$re->free();
	if($rs)
		return $rs['level'];
	return null;
}
function blog_base_li()
{
	global $file,$path_blog,$level;
	if($file != 'index')
		echo "<li><a href='$path_blog/'>主页</a></li>";
	if($level & 3)
	{
		if($file != 'post-passage')
			echo "<li><a href='$path_blog/post-passage.php'>写文章</a></li>";
		if($file == 'single')
		{
			if(!isset($_GET['p']))
				$pid = getpassageid($_GET['r']);
			else
				$pid = $_GET['p'];
			echo "<li><a href='$path_blog/post-passage.php?p=$pid'>编辑文章</a></li>";
		}
		echo "<li><a href='http://localhost/release/messageboard/'>留言</a></li>";
	}
}
function blog_header()
{
	global $path_blog,$level,$file,$path_root,$user_db,$uid,$conf;
	?>
	<header class="header-bar">
		<ul>
			<?php blog_base_li(); ?>
		</ul>
		<?php $user_db->user_sign_list(); ?>
		<div>
			<img src="<?php echo $path_blog ?>/img/logo.jpg" width="100%" height="150px">
			<span id="welcome">欢迎您，<?php
			if($uid)
				echo $user_db->getnamebyid($uid);
			else
				echo "游客";
			?></span>
		</div>
		<div><a href="<?php echo $path_blog ?>/"><h1 class="title"><?php echo $conf["title"]; ?></h1></a></div>
	</header>
	<?php
}
function blog_footer()
{
	?>
	<footer></footer>
	<?php
}
function blog_script()
{
	global $path_blog,$path_lib;
	?>
	<script src="<?php echo $path_lib ?>/compatible.js"></script>
	<script src="<?php echo $path_blog ?>/global.js"></script>
	<?php
}
function blog_sidebar()
{
	global $user_db,$the_cat;
	?>
	<aside>
		<article>
			<h2>功能</h2>
			<ul>
				<?php blog_base_li(); ?>
			</ul>
			<?php $user_db->user_sign_list(); ?>
		</article>
		<article>
			<h2>分类目录</h2>
			<div class="category-frame">
				<?php $the_cat->get("link"); ?>
			</div>
		</article>
	</aside>
	<?php
}
function getuploadfiles()
{
	global $path_blog;
	$path =  dirname(__FILE__) . "/uploads/";
	foreach(scandir($path) as $afile)
	{
		if($afile=='.' || $afile=='..')
			continue;
		if(is_dir($path.'/'.$afile))
			continue;
		else
		{
			?>
			<article>
				<img src="<?php echo $path_blog; ?>/uploads/<?php echo iconv("gbk","UTF-8",$afile); ?>">
			</article>
			<?php
		}
	}
}
function getpassage($id,$is_p = true)
{
	global $blog_db,$path_blog,$file,$user_db;
	if($is_p)
		$pid = $id;
	else
		$pid = getpassageid($id);
	if($re = $blog_db->query("SELECT passage.*,category.name as category_name FROM passage,category WHERE category.id=passage.category AND passage.id=$pid"))
		$rs = $re->fetch_array(MYSQLI_BOTH);
	else
		blog_die(905);
	$re->free();
	if($rs)
	{
		?>
		<article id=<?php echo "'passage-".$rs['id']."'"; ?>>
			<header>
				<h2><?php
				if($file != 'single')
				{
					echo '<a href="'.$path_blog.'/?r='.$rs['rid'].'">';
					echo $rs['title'].'</a>';
				}
				else
					echo $rs['title'];
				?></h2>
				<div class="passage-header">
					<span class='meta-auther'><?php
						echo $user_db->getnamebyid($rs['uid']); ?></span>
					<span class='meta-date'><?php
						echo $rs['releasetime']; ?></span>
					<span class='meta-category'><?php
						echo $rs['category_name']; ?></span>
					<span class='meta-comments'></span>
				</div>
			</header>
			<?php
			if($file == 'single')
			{?>
				<div class='content'><?php echo $rs['content']; ?></div><?php
			}?>
			<footer></footer>
		</article>
		<?php
		return true;
	}
	else
		return false;
}
function getAllpassages()
{
	global $blog_db;
	$re = $blog_db->query("SELECT * FROM passage_release ORDER BY id DESC");
	while($rs = $re->fetch_array(MYSQLI_BOTH))
		getpassage($rs['version']);
}
function getpassageid($rid)
{
	global $blog_db;
	$re = $blog_db->query("SELECT version FROM passage_release WHERE id=$rid");
	$rs = $re->fetch_array(MYSQLI_BOTH);
	$re->free();
	return $rs["version"];
}
function get_passage_release_id($pid)
{
	global $blog_db;
	$re = $blog_db->query("SELECT rid FROM passage WHERE id=$pid");
	$rs = $re->fetch_array(MYSQLI_BOTH);
	$re->free();
	return $rs["rid"];
}
function getpassagetitle($pid)
{
	global $blog_db;
	$re = $blog_db->query("SELECT title FROM passage WHERE id=$pid");
	$rs = $re->fetch_array(MYSQLI_BOTH);
	$re->free();
	return $rs["title"];
}
function getpassagecategory($pid)
{
	global $blog_db;
	$re = $blog_db->query("SELECT category FROM passage WHERE id=$pid");
	$rs = $re->fetch_array(MYSQLI_BOTH);
	$re->free();
	return $rs["category"];
}
function getpassagecontent($pid)
{
	global $blog_db;
	$re = $blog_db->query("SELECT content FROM passage WHERE id=$pid");
	$rs = $re->fetch_array(MYSQLI_BOTH);
	$re->free();
	return $rs["content"];
}
function is_passage_release_id($rid)
{
	global $blog_db;
	if(!is_numeric($rid))
		return false;
	$re = $blog_db->query("SELECT id FROM passage_release WHERE id=$rid");
	$rs = $re->fetch_array();
	$re->free();
	if($rs)
		return true;
	return false;
}
function is_passage_id($pid)
{
	global $blog_db;
	if(!is_numeric($pid))
		return false;
	$re = $blog_db->query("SELECT id FROM passage WHERE id=$pid");
	$rs = $re->fetch_array();
	$re->free();
	if($rs)
		return true;
	return false;
}
function create_passage($uid)
{
	global $blog_db;
	if($blog_db->query("INSERT INTO passage_release(uid) VALUES($uid)"))
	{
		$re = $blog_db->query("SELECT @@IDENTITY");
		$rs = $re->fetch_array(MYSQLI_BOTH);
		$re->free();
		if($rs && $rs[0])
			return $rs[0];
		else
			blog_die(904);
	}
	else
		blog_die(903);
}
function post_passage($rid,$uid,$title,$content,$category,$is_update_uid=false)
{
	global $blog_db,$path_blog;
	if($blog_db->query("INSERT INTO passage(rid,uid,title,category,content) VALUES($rid,$uid,'$title',$category,'$content')"))
	{
		$re = $blog_db->query("SELECT @@IDENTITY");
		$rs = $re->fetch_array(MYSQLI_BOTH);
		$re->free();
		if($rs && $rs[0])
		{
			$pid = $rs[0];
			if($is_update_uid)
			{
				$blog_db->query("UPDATE passage_release SET uid=$uid,version=$pid WHERE id=$rid");
			}
			else
			{
				$blog_db->query("UPDATE passage_release SET version=$pid WHERE id=$rid");
			}
			header("Location:".$path_blog."/?p=$pid");
		}
		else
			blog_die(902);
	}
	else
		blog_die(901);
}
function getofcategory($cid)
{
	global $blog_db;
	$re = $blog_db->query("SELECT version FROM passage,passage_release WHERE version=passage.id AND category=$cid ORDER BY passage_release.id DESC");
	while($rs = $re->fetch_array(MYSQLI_BOTH))
		getpassage($rs['version']);
}
function login()
{
	global $path_user,$path_root;
	$_SESSION['url'] = $path_root.$_SERVER['REQUEST_URI'];
	header("Location:".$path_user."/login.html");
}
function blog_die($eid)
{
	switch($eid)
	{
	case 403:
	case 404:
		global $path_blog;
		header("Location:".$path_blog."/?e=".$eid);
		exit();
		break;
	// 7 系列 = 用户操作错误或权限问题
	case 701: // 未登录
		login();
		exit();
		break;
	case 751: // 该用户没有发布文章的权限
		global $path_blog;
		header("Location:".$path_blog."/?e=".$eid);
		exit();
		break;
	case 752: // 文章标题为空
	case 753: // 文章内容为空
	case 754: // 文章分类编号错误
		global $path_blog;
		header("Location:".$path_blog."/post-passage?e=".$eid);
		exit();
		break;
	case 755: // 该用户没有编辑分类的权限
	case 756: // 该用户没有编辑素材的权限
		global $path_blog;
		header("Location:".$path_blog."/?e=".$eid);
		exit();
		break;
	// 8 系列 = 参数错误
	case 801: // 文章发布号错误 (wrong passage release id)
	case 802: // 文章编号错误 (wrong passage id)
		global $path_blog;
		header("Location:".$path_blog."/?e=".$eid);
		exit();
		break;
	// 9 系列 = 数据库错误
	case 901:
	case 902:
	case 903:
	case 904:
	case 905:
		global $path_blog;
		header("Location:".$path_blog."/?e=".$eid);
		exit();
		break;
	}
}
function blog_info()
{
	if(isset($_GET['e']) && is_numeric($_GET['e']))
		$eid = $_GET['e'];
	else
		return true;
	switch($eid)
	{
	case 403:
		?>
		<article>
			<header>403: FORBIDDEN TO VIEW</header>
			<div>
				<p>It looks like you have no permission to view this page. </p>
			</div>
		</article>
		<?php
		break;
	case 404:
		?>
		<article>
			<header>404: PAGE NOT FOUND</header>
			<div>
				<p>It looks like nothing was found at this location. </p>
			</div>
		</article>
		<?php
		break;
	// 7 系列 = 用户操作错误或权限问题
	case 701: // 未登录
		return false;
		break;
	case 751: // 该用户没有发布文章的权限
		?>
		<div class="blog_error">
			<p>你没有发布文章的权限。<button>关闭</button></p>
		</div>
		<?php
		break;
	case 752: // 文章标题为空
		?>
		<div class="blog_error">
			<p>标题不能为空。<button>关闭</button></p>
		</div>
		<?php
		break;
	case 753: // 文章内容为空
		?>
		<div class="blog_error">
			<p>内容不能为空。<button>关闭</button></p>
		</div>
		<?php
		break;
	case 754: // 文章分类编号错误
		?>
		<div class="blog_error">
			<p>请选择正确的文章分类。<button>关闭</button></p>
		</div>
		<?php
		break;
	case 755: // 用户没有编辑分类的权限
		?>
		<div class="blog_error">
			<p>你没有管理分类的权限。<button>关闭</button></p>
		</div>
		<?php
		break;
	case 756: // 用户没有管理素材的权限
		?>
		<div class="blog_error">
			<p>你没有管理素材的权限。<button>关闭</button></p>
		</div>
		<?php
		break;
	// 8 系列 = 参数错误
	case 801: // 文章发布号错误 (wrong passage release id)
		?>
		<div class="blog_error">
			<p>错误的发布号，或者链接已失效。<button>关闭</button></p>
		</div>
		<?php
		break;
	case 802: // 文章编号错误 (wrong passage id)
		?>
		<div class="blog_error">
			<p>错误的文章编号，或者链接已失效。<button>关闭</button></p>
		</div>
		<?php
		break;
	// 9 系列 = 数据库错误
	case 901:
	case 902:
	case 903:
	case 904:
	case 905:
		?>
		<div class="blog_error">
			<p>数据库错误，代码 <?php echo $eid; ?> ，请联系管理员。<button>关闭</button></p>
		</div>
		<?php
		break;
	}
	return true;
}
function get_real_ip()
{
    static $realip;
    if(isset($_SERVER))
    {
        if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $realip=$_SERVER['HTTP_X_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_CLIENT_IP']))
            $realip=$_SERVER['HTTP_CLIENT_IP'];
        else
            $realip=$_SERVER['REMOTE_ADDR'];
    }
    else
    {
        if(getenv('HTTP_X_FORWARDED_FOR'))
            $realip=getenv('HTTP_X_FORWARDED_FOR');
        else if(getenv('HTTP_CLIENT_IP'))
            $realip=getenv('HTTP_CLIENT_IP');
        else
            $realip=getenv('REMOTE_ADDR');
    }
    return $realip;
}
function getip()
{
    $unknown = 'unknown';
    if ( isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'] && strcasecmp($_SERVER['HTTP_X_FORWARDED_FOR'], $unknown) )
    {
    	$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    elseif ( isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], $unknown) )
    {
    	$ip = $_SERVER['REMOTE_ADDR'];
  	}
/*
处理多层代理的情况
或者使用正则方式：$ip = preg_match("/[\d\.]{7,15}/", $ip, $matches) ? $matches[0] : $unknown;
*/
	if (false !== strpos($ip, ','))
    	$ip = reset(explode(',', $ip));
    return $ip;
}