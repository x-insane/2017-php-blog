<?php
/*
该类不检查传入参数的正确性，调用该类前必须对参数进行检查
*/
class Category
{
	function getoption() // 直接获取所有分类，无需分级
	{
		global $blog_db;
		$re = $blog_db->query("SELECT id,name FROM category");
		while($rs = $re->fetch_array())
		{
			if($rs["id"] == 1)
				continue;
			?><option value="<?php echo $rs["id"]; ?>"><?php echo $rs["name"]; ?></option><?php
		}
	}
	function get($callback="radio",$parent=0,$checkedid=1,$breakid=-1,$breakid2=-1)
	{
		// 分级显示所有分类，递归输出
		global $blog_db;
		$re = $blog_db->query("SELECT category.id,category.name,COUNT(passage_release.id) as num FROM category left JOIN passage ON passage.category=category.id left JOIN passage_release ON passage_release.version=passage.id WHERE parent=$parent GROUP BY category.id");
		while($rs = $re->fetch_array())
		{
			if($rs["id"] == $breakid)
				continue;
			if($rs["id"] == $breakid2)
				continue;
			$this->$callback($rs,$parent,$checkedid,$breakid,$breakid2);
		}
	}
	private function radio($rs,$parent,$checkedid,$breakid,$breakid2)
	{
		?>
		<div class="category">
			<input type="radio" name="category" value="<?php echo $rs['id']; ?>" data-name="<?php echo $rs['name']; ?>" <?php if($rs['id']==$checkedid) echo 'checked'; ?>><?php echo $rs['name'].'('.$rs['num'].')' ?>
			<?php $this->get("radio",$rs['id'],$checkedid,$breakid,$breakid2); ?>
		</div>
		<?php
	}
	private function link($rs,$parent,$checkedid,$breakid,$breakid2)
	{
		global $path_blog;
		?>
		<div class="category">
			<a href="<?php echo $path_blog.'/?c='.$rs['id']; ?>"><?php echo $rs['name'].'('.$rs['num'].')' ?></a>
			<?php $this->get("link",$rs['id'],$checkedid,$breakid,$breakid2); ?>
		</div>
		<?php
	}
	private function option($rs,$parent,$checkedid,$breakid,$breakid2)
	{
		?>
		<option value="<?php echo $rs["id"]; ?>"><?php echo $rs["name"]; ?></option>
		<?php
		$this->get("option",$rs['id'],$checkedid,$breakid,$breakid2);
	}
	private function manage($rs,$parent,$checkedid,$breakid,$breakid2)
	{
		?>
		<div class="category">
			<input type="radio" name="category" value="<?php echo $rs['id']; ?>" data-name="<?php echo $rs['name']; ?>" <?php if($rs['id']==$checkedid) echo 'checked'; ?>><?php echo $rs['name'].'('.$rs['num'].')' ?>
			<?php $this->get("manage",$rs['id'],$checkedid,$breakid,$breakid2); ?>
		</div>
		<?php
	}
	function getname($cid)
	{
		global $blog_db;
		$re = $blog_db->query("SELECT name FROM category WHERE id=$cid");
		$rs = $re->fetch_array();
		echo $rs["name"];
	}
	function is($cid)
	{
		global $blog_db;
		if(!is_numeric($cid))
			return false;
		$re = $blog_db->query("SELECT id FROM category WHERE id=$cid");
		if($re->fetch_array())
			return true;
		return false;
	}
	function is_empty($cid)
	{
		global $blog_db;
		$re = $blog_db->query("SELECT passage_release.id FROM passage,passage_release WHERE passage.category=$cid AND passage.id=passage_release.version");
		if($re->fetch_array())
			return false;
		$re = $blog_db->query("SELECT id FROM category WHERE parent=$cid");
		while($rs = $re->fetch_array())
		{
			if(!$this->is_empty($rs["id"]))
				return false;
		}
		return true;
	}
	function add($parent,$name)
	{
		global $blog_db;
		$re = $blog_db->query("SELECT * FROM category WHERE name='$name'");
		if($re->fetch_array())
			return "新建分类失败：分类 ".$name." 已存在";
		if($blog_db->query("INSERT INTO category(parent,name) VALUES($parent,'$name')"))
		{
			$re = $blog_db->query("SELECT @@IDENTITY");
			$rs = $re->fetch_array();
			if($rs && $rs[0])
				return $rs[0];
			else
				return "数据库错误";
		}
		else
			return "数据库错误";
	}
	function del($cat_id,$unconditional=false,$cat_to=1)
	{
		global $blog_db;
		if($unconditional || $this->is_empty($cat_id))
		{
			$blog_db->query("DELETE FROM category WHERE id=$cat_id");
			$blog_db->query("UPDATE passage SET category=$cat_to WHERE category=$cat_id");
			$re = $blog_db->query("SELECT id FROM category WHERE parent=$cat_id");
			while($rs = $re->fetch_array())
				$this->del($rs["id"],true,$cat_to);
		}
	}
	function setparent($cat_id,$cat_par)
	{
		global $blog_db;
		if($blog_db->query("UPDATE category SET parent=$cat_par WHERE id=$cat_id"))
			return true;
		return false;
	}
	function rename($cat_id,$cat_name)
	{
		global $blog_db;
		$re = $blog_db->query("SELECT * FROM category WHERE name='$cat_name'");
		if($re->fetch_array())
			return "已存在该分类";
		if($blog_db->query("UPDATE category SET name='$cat_name' WHERE id=$cat_id"))
			return null;
		return "数据库错误";
	}
}