<?php

/**
 * Subclass for performing query and update operations on the 'category' table.
 *
 * 
 *
 * @package lib.model
 */ 
class CategoryPeer extends BaseCategoryPeer
{
	
	public static function getCombo(){
		$arr_category_combo = array();
		$c = new Criteria();
		$c->addSelectColumn(self::ID);
		$c->addSelectColumn(self::CATEGORY_NAME);
		$rs = self::doSelectRs($c);
		foreach($rs as $k => $v) {
			$arr_category_combo[$v[0]] = $v[1];
		}
		return $arr_category_combo;
	}

	public static function getCategoryName($id){
		$c = new Criteria();
	  $c->addSelectColumn(CategoryPeer::CATEGORY_NAME);
	  $c->add(CategoryPeer::ID, $id);
		$cname = CategoryPeer::doSelectRs($c);
		$cname->next();
		return $cname->getString(1);
	}

	/*
	* SKYiTech :: get all child ids of the category
	*			$id = category to check
	*			$returnCount = ( true: return count, false: return 0/1(true/false) )
	*			$sql = "SELECT id FROM category WHERE parents like '%|".$id."|%'";
	*	Return: array of child ids
	*/
	public static function getChildIds($id){
		$childIds = array();
		$c = new Criteria();
		$c->addSelectColumn(CategoryPeer::ID);
		$c->add(CategoryPeer::PARENTS, '%|'.$id.'|%', Criteria::LIKE);
		$count = CategoryPeer::doSelectRs($c);
		while($count->next())
			$childIds[] = $count->getString(1);
		return $childIds;
	}


	/*
	* SKYiTech :: Get parent id of the category
	*/
	public static function getParentId($parents){
	    $a = (explode('|',$parents));
	    array_pop($a);
	    return end($a);
	}

	/*
	* SKYiTech :: check passed id has sub category or not
	*			$id = category to check
	*			$returnCount = ( true: return count, false: return 0/1(true/false) )
	*			$sql = "SELECT COUNT(*) FROM category WHERE parents like '%|".$id."|%'";
	*/
	public static function hasChild($id){
		$c = new Criteria();
		//$c->addSelectColumn('count('.CategoryPeer::ID.')');
		$c->add(CategoryPeer::PARENTS, '%|'.$id.'|%', Criteria::LIKE);
		return CategoryPeer::doCount($c);
	}


	/*
	* SKYiTech :: check passed id has files or not
	*			$id = category to check
	*			$returnCount = ( true: return count, false: return 0/1(true/false) )
	*			$sql = "SELECT COUNT(*) FROM files WHERE category_id=$id";
	*/
	public static function hasFiles($id,$child='F'){
		$count = 0;
		$c = new Criteria();
		if($child=='D'){
			$ids = CategoryPeer::getChildIds($id);
			if($ids)
			$c->add(FilesPeer::CATEGORY_ID, $ids , Criteria::IN);
		}
		else{
			$c->add(FilesPeer::CATEGORY_ID, $id);
		}
		return FilesPeer::doCount($c);
	}


	/*
	* SKYiTech :: calculate total file size under this category
	*			$id = category to check
	*			$returnCount = ( true: return sum of all file size, false: return 0 )
	*			$sql = "SELECT sum( size ) , count( id ) FROM files WHERE category_id IN (SELECT id FROM category WHERE parents LIKE '%|".$id."|%' OR id=".$id.")";
	*/
	public static function totalFileAndSize($id){
		$con = Propel::getConnection();
    $sql = "SELECT sum( size ) , count( id ), sum(today), sum(download) FROM files WHERE category_id IN (SELECT id FROM category WHERE parents LIKE '%|".$id."|%' OR id=".$id.")";
    $rs = $con->executeQuery($sql, ResultSet::FETCHMODE_NUM);
    $rs->next();
    return array(myClass::formatSize($rs->getString(1)),$rs->getString(2),$rs->getString(3),$rs->getString(4));
	}


}
