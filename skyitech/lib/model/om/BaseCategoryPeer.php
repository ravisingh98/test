<?php


abstract class BaseCategoryPeer {

	
	const DATABASE_NAME = 'propel';

	
	const TABLE_NAME = 'category';

	
	const CLASS_DEFAULT = 'lib.model.Category';

	
	const NUM_COLUMNS = 15;

	
	const NUM_LAZY_LOAD_COLUMNS = 0;


	
	const ID = 'category.ID';

	
	const CATEGORY_NAME = 'category.CATEGORY_NAME';

	
	const TITLE = 'category.TITLE';

	
	const DESCRIPTION = 'category.DESCRIPTION';

	
	const PARENTS = 'category.PARENTS';

	
	const PARENTSARRAY = 'category.PARENTSARRAY';

	
	const STATUS = 'category.STATUS';

	
	const CHILD = 'category.CHILD';

	
	const LIST_ORD = 'category.LIST_ORD';

	
	const ORD = 'category.ORD';

	
	const FLAG_NEW = 'category.FLAG_NEW';

	
	const FLAG_UPDATED = 'category.FLAG_UPDATED';

	
	const FLAG_HOT = 'category.FLAG_HOT';

	
	const FILES = 'category.FILES';

	
	const URL = 'category.URL';

	
	private static $phpNameMap = null;


	
	private static $fieldNames = array (
		BasePeer::TYPE_PHPNAME => array ('Id', 'CategoryName', 'Title', 'Description', 'Parents', 'Parentsarray', 'Status', 'Child', 'ListOrd', 'Ord', 'FlagNew', 'FlagUpdated', 'FlagHot', 'Files', 'Url', ),
		BasePeer::TYPE_COLNAME => array (CategoryPeer::ID, CategoryPeer::CATEGORY_NAME, CategoryPeer::TITLE, CategoryPeer::DESCRIPTION, CategoryPeer::PARENTS, CategoryPeer::PARENTSARRAY, CategoryPeer::STATUS, CategoryPeer::CHILD, CategoryPeer::LIST_ORD, CategoryPeer::ORD, CategoryPeer::FLAG_NEW, CategoryPeer::FLAG_UPDATED, CategoryPeer::FLAG_HOT, CategoryPeer::FILES, CategoryPeer::URL, ),
		BasePeer::TYPE_FIELDNAME => array ('id', 'category_name', 'title', 'description', 'parents', 'parentsarray', 'status', 'child', 'list_ord', 'ord', 'flag_new', 'flag_updated', 'flag_hot', 'files', 'url', ),
		BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, )
	);

	
	private static $fieldKeys = array (
		BasePeer::TYPE_PHPNAME => array ('Id' => 0, 'CategoryName' => 1, 'Title' => 2, 'Description' => 3, 'Parents' => 4, 'Parentsarray' => 5, 'Status' => 6, 'Child' => 7, 'ListOrd' => 8, 'Ord' => 9, 'FlagNew' => 10, 'FlagUpdated' => 11, 'FlagHot' => 12, 'Files' => 13, 'Url' => 14, ),
		BasePeer::TYPE_COLNAME => array (CategoryPeer::ID => 0, CategoryPeer::CATEGORY_NAME => 1, CategoryPeer::TITLE => 2, CategoryPeer::DESCRIPTION => 3, CategoryPeer::PARENTS => 4, CategoryPeer::PARENTSARRAY => 5, CategoryPeer::STATUS => 6, CategoryPeer::CHILD => 7, CategoryPeer::LIST_ORD => 8, CategoryPeer::ORD => 9, CategoryPeer::FLAG_NEW => 10, CategoryPeer::FLAG_UPDATED => 11, CategoryPeer::FLAG_HOT => 12, CategoryPeer::FILES => 13, CategoryPeer::URL => 14, ),
		BasePeer::TYPE_FIELDNAME => array ('id' => 0, 'category_name' => 1, 'title' => 2, 'description' => 3, 'parents' => 4, 'parentsarray' => 5, 'status' => 6, 'child' => 7, 'list_ord' => 8, 'ord' => 9, 'flag_new' => 10, 'flag_updated' => 11, 'flag_hot' => 12, 'files' => 13, 'url' => 14, ),
		BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, )
	);

	
	public static function getMapBuilder()
	{
		include_once 'lib/model/map/CategoryMapBuilder.php';
		return BasePeer::getMapBuilder('lib.model.map.CategoryMapBuilder');
	}
	
	public static function getPhpNameMap()
	{
		if (self::$phpNameMap === null) {
			$map = CategoryPeer::getTableMap();
			$columns = $map->getColumns();
			$nameMap = array();
			foreach ($columns as $column) {
				$nameMap[$column->getPhpName()] = $column->getColumnName();
			}
			self::$phpNameMap = $nameMap;
		}
		return self::$phpNameMap;
	}
	
	static public function translateFieldName($name, $fromType, $toType)
	{
		$toNames = self::getFieldNames($toType);
		$key = isset(self::$fieldKeys[$fromType][$name]) ? self::$fieldKeys[$fromType][$name] : null;
		if ($key === null) {
			throw new PropelException("'$name' could not be found in the field names of type '$fromType'. These are: " . print_r(self::$fieldKeys[$fromType], true));
		}
		return $toNames[$key];
	}

	

	static public function getFieldNames($type = BasePeer::TYPE_PHPNAME)
	{
		if (!array_key_exists($type, self::$fieldNames)) {
			throw new PropelException('Method getFieldNames() expects the parameter $type to be one of the class constants TYPE_PHPNAME, TYPE_COLNAME, TYPE_FIELDNAME, TYPE_NUM. ' . $type . ' was given.');
		}
		return self::$fieldNames[$type];
	}

	
	public static function alias($alias, $column)
	{
		return str_replace(CategoryPeer::TABLE_NAME.'.', $alias.'.', $column);
	}

	
	public static function addSelectColumns(Criteria $criteria)
	{

		$criteria->addSelectColumn(CategoryPeer::ID);

		$criteria->addSelectColumn(CategoryPeer::CATEGORY_NAME);

		$criteria->addSelectColumn(CategoryPeer::TITLE);

		$criteria->addSelectColumn(CategoryPeer::DESCRIPTION);

		$criteria->addSelectColumn(CategoryPeer::PARENTS);

		$criteria->addSelectColumn(CategoryPeer::PARENTSARRAY);

		$criteria->addSelectColumn(CategoryPeer::STATUS);

		$criteria->addSelectColumn(CategoryPeer::CHILD);

		$criteria->addSelectColumn(CategoryPeer::LIST_ORD);

		$criteria->addSelectColumn(CategoryPeer::ORD);

		$criteria->addSelectColumn(CategoryPeer::FLAG_NEW);

		$criteria->addSelectColumn(CategoryPeer::FLAG_UPDATED);

		$criteria->addSelectColumn(CategoryPeer::FLAG_HOT);

		$criteria->addSelectColumn(CategoryPeer::FILES);

		$criteria->addSelectColumn(CategoryPeer::URL);

	}

	const COUNT = 'COUNT(category.ID)';
	const COUNT_DISTINCT = 'COUNT(DISTINCT category.ID)';

	
	public static function doCount(Criteria $criteria, $distinct = false, $con = null)
	{
				$criteria = clone $criteria;

				$criteria->clearSelectColumns()->clearOrderByColumns();
		if ($distinct || in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->addSelectColumn(CategoryPeer::COUNT_DISTINCT);
		} else {
			$criteria->addSelectColumn(CategoryPeer::COUNT);
		}

				foreach($criteria->getGroupByColumns() as $column)
		{
			$criteria->addSelectColumn($column);
		}

		$rs = CategoryPeer::doSelectRS($criteria, $con);
		if ($rs->next()) {
			return $rs->getInt(1);
		} else {
						return 0;
		}
	}
	
	public static function doSelectOne(Criteria $criteria, $con = null)
	{
		$critcopy = clone $criteria;
		$critcopy->setLimit(1);
		$objects = CategoryPeer::doSelect($critcopy, $con);
		if ($objects) {
			return $objects[0];
		}
		return null;
	}
	
	public static function doSelect(Criteria $criteria, $con = null)
	{
		return CategoryPeer::populateObjects(CategoryPeer::doSelectRS($criteria, $con));
	}
	
	public static function doSelectRS(Criteria $criteria, $con = null)
	{
		if ($con === null) {
			$con = Propel::getConnection(self::DATABASE_NAME);
		}

		if (!$criteria->getSelectColumns()) {
			$criteria = clone $criteria;
			CategoryPeer::addSelectColumns($criteria);
		}

				$criteria->setDbName(self::DATABASE_NAME);

						return BasePeer::doSelect($criteria, $con);
	}
	
	public static function populateObjects(ResultSet $rs)
	{
		$results = array();
	
				$cls = CategoryPeer::getOMClass();
		$cls = Propel::import($cls);
				while($rs->next()) {
		
			$obj = new $cls();
			$obj->hydrate($rs);
			$results[] = $obj;
			
		}
		return $results;
	}
	
	public static function getTableMap()
	{
		return Propel::getDatabaseMap(self::DATABASE_NAME)->getTable(self::TABLE_NAME);
	}

	
	public static function getOMClass()
	{
		return CategoryPeer::CLASS_DEFAULT;
	}

	
	public static function doInsert($values, $con = null)
	{
		if ($con === null) {
			$con = Propel::getConnection(self::DATABASE_NAME);
		}

		if ($values instanceof Criteria) {
			$criteria = clone $values; 		} else {
			$criteria = $values->buildCriteria(); 		}

		$criteria->remove(CategoryPeer::ID); 

				$criteria->setDbName(self::DATABASE_NAME);

		try {
									$con->begin();
			$pk = BasePeer::doInsert($criteria, $con);
			$con->commit();
		} catch(PropelException $e) {
			$con->rollback();
			throw $e;
		}

		return $pk;
	}

	
	public static function doUpdate($values, $con = null)
	{
		if ($con === null) {
			$con = Propel::getConnection(self::DATABASE_NAME);
		}

		$selectCriteria = new Criteria(self::DATABASE_NAME);

		if ($values instanceof Criteria) {
			$criteria = clone $values; 
			$comparison = $criteria->getComparison(CategoryPeer::ID);
			$selectCriteria->add(CategoryPeer::ID, $criteria->remove(CategoryPeer::ID), $comparison);

		} else { 			$criteria = $values->buildCriteria(); 			$selectCriteria = $values->buildPkeyCriteria(); 		}

				$criteria->setDbName(self::DATABASE_NAME);

		return BasePeer::doUpdate($selectCriteria, $criteria, $con);
	}

	
	public static function doDeleteAll($con = null)
	{
		if ($con === null) {
			$con = Propel::getConnection(self::DATABASE_NAME);
		}
		$affectedRows = 0; 		try {
									$con->begin();
			$affectedRows += CategoryPeer::doOnDeleteCascade(new Criteria(), $con);
			$affectedRows += BasePeer::doDeleteAll(CategoryPeer::TABLE_NAME, $con);
			$con->commit();
			return $affectedRows;
		} catch (PropelException $e) {
			$con->rollback();
			throw $e;
		}
	}

	
	 public static function doDelete($values, $con = null)
	 {
		if ($con === null) {
			$con = Propel::getConnection(CategoryPeer::DATABASE_NAME);
		}

		if ($values instanceof Criteria) {
			$criteria = clone $values; 		} elseif ($values instanceof Category) {

			$criteria = $values->buildPkeyCriteria();
		} else {
						$criteria = new Criteria(self::DATABASE_NAME);
			$criteria->add(CategoryPeer::ID, (array) $values, Criteria::IN);
		}

				$criteria->setDbName(self::DATABASE_NAME);

		$affectedRows = 0; 
		try {
									$con->begin();
			$affectedRows += CategoryPeer::doOnDeleteCascade($criteria, $con);
			$affectedRows += BasePeer::doDelete($criteria, $con);
			$con->commit();
			return $affectedRows;
		} catch (PropelException $e) {
			$con->rollback();
			throw $e;
		}
	}

	
	protected static function doOnDeleteCascade(Criteria $criteria, Connection $con)
	{
				$affectedRows = 0;

				$objects = CategoryPeer::doSelect($criteria, $con);
		foreach($objects as $obj) {


			include_once 'lib/model/Files.php';

						$c = new Criteria();
			
			$c->add(FilesPeer::CATEGORY_ID, $obj->getId());
			$affectedRows += FilesPeer::doDelete($c, $con);
		}
		return $affectedRows;
	}

	
	public static function doValidate(Category $obj, $cols = null)
	{
		$columns = array();

		if ($cols) {
			$dbMap = Propel::getDatabaseMap(CategoryPeer::DATABASE_NAME);
			$tableMap = $dbMap->getTable(CategoryPeer::TABLE_NAME);

			if (! is_array($cols)) {
				$cols = array($cols);
			}

			foreach($cols as $colName) {
				if ($tableMap->containsColumn($colName)) {
					$get = 'get' . $tableMap->getColumn($colName)->getPhpName();
					$columns[$colName] = $obj->$get();
				}
			}
		} else {

		}

		$res =  BasePeer::doValidate(CategoryPeer::DATABASE_NAME, CategoryPeer::TABLE_NAME, $columns);
    if ($res !== true) {
        $request = sfContext::getInstance()->getRequest();
        foreach ($res as $failed) {
            $col = CategoryPeer::translateFieldname($failed->getColumn(), BasePeer::TYPE_COLNAME, BasePeer::TYPE_PHPNAME);
            $request->setError($col, $failed->getMessage());
        }
    }

    return $res;
	}

	
	public static function retrieveByPK($pk, $con = null)
	{
		if ($con === null) {
			$con = Propel::getConnection(self::DATABASE_NAME);
		}

		$criteria = new Criteria(CategoryPeer::DATABASE_NAME);

		$criteria->add(CategoryPeer::ID, $pk);


		$v = CategoryPeer::doSelect($criteria, $con);

		return !empty($v) > 0 ? $v[0] : null;
	}

	
	public static function retrieveByPKs($pks, $con = null)
	{
		if ($con === null) {
			$con = Propel::getConnection(self::DATABASE_NAME);
		}

		$objs = null;
		if (empty($pks)) {
			$objs = array();
		} else {
			$criteria = new Criteria();
			$criteria->add(CategoryPeer::ID, $pks, Criteria::IN);
			$objs = CategoryPeer::doSelect($criteria, $con);
		}
		return $objs;
	}

} 
if (Propel::isInit()) {
			try {
		BaseCategoryPeer::getMapBuilder();
	} catch (Exception $e) {
		Propel::log('Could not initialize Peer: ' . $e->getMessage(), Propel::LOG_ERR);
	}
} else {
			require_once 'lib/model/map/CategoryMapBuilder.php';
	Propel::registerMapBuilder('lib.model.map.CategoryMapBuilder');
}
