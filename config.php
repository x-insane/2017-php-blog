<?php
// 开启 session
session_start();

// 引入数据库文件
require_once( dirname( __FILE__ ) . '/lib/mysql.php' );

// 引入所有类
require_once( dirname( __FILE__ ) . '/class/user-class.php' );
require_once( dirname( __FILE__ ) . '/class/category-class.php' );

// 创建类对象实例
$blog_db = new mysql("blog");
$user_db = new User;
$the_cat = new Category;

// 网站基本信息配置
$conf["title"] = "梦的天空之城"; // 网站默认标题

// 网站路径配置
$path_user = "http://localhost/release/blog/user";
$path_blog = "http://localhost/release/blog";
$path_lib = "http://localhost/release/blog/lib";
$path_root = "http://localhost";

// $path_user = "http://h.gotohope.cn:88/user";
// $path_blog = "http://h.gotohope.cn:88/blog";
// $path_lib = "http://h.gotohope.cn:88/lib";
// $path_root = "http://h.gotohope.cn:88";

// 用户信息初始化
$uid = isset($_SESSION["userid"]) ? $_SESSION["userid"] : null;
if($uid)
{
	$re = $blog_db->query("SELECT * FROM user WHERE uid=$uid");
	if(!$re->num_rows)
	{
		if(!$blog_db->query("INSERT INTO user(uid,level) VALUES($uid,0)"))
		{
			echo "MYSQLI ERROR";
			exit();
		}
	}
	$rs = $re->fetch_array(MYSQLI_BOTH);
	$re->free();
	if($rs)
		$level = $rs['level'];
}

// 配置当前页面签名
$file = 'index';
