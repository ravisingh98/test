<?php


abstract class BaseFilesPeer {

	
	const DATABASE_NAME = 'propel';

	
	const TABLE_NAME = 'files';

	
	const CLASS_DEFAULT = 'lib.model.Files';

	
	const NUM_COLUMNS = 14;

	
	const NUM_LAZY_LOAD_COLUMNS = 0;


	
	const ID = 'files.ID';

	
	const FILE_NAME = 'files.FILE_NAME';

	
	const CATEGORY_ID = 'files.CATEGORY_ID';

	
	const DESCRIPTION = 'files.DESCRIPTION';

	
	const SINGER = 'files.SINGER';

	
	const TAGS = 'files.TAGS';

	
	const SIZE = 'files.SIZE';

	
	const TODAY = 'files.TODAY';

	
	const DOWNLOAD = 'files.DOWNLOAD';

	
	const EXTENSION = 'files.EXTENSION';

	
	const ORD = 'files.ORD';

	
	const STATUS = 'files.STATUS';

	
	const URL = 'files.URL';

	
	const CREATED_AT = 'files.CREATED_AT';

	
	private static $phpNameMap = null;


	
	private static $fieldNames = array (
		BasePeer::TYPE_PHPNAME => array ('Id', 'FileName', 'CategoryId', 'Description', 'Singer', 'Tags', 'Size', 'Today', 'Download', 'Extension', 'Ord', 'Status', 'Url', 'CreatedAt', ),
		BasePeer::TYPE_COLNAME => array (FilesPeer::ID, FilesPeer::FILE_NAME, FilesPeer::CATEGORY_ID, FilesPeer::DESCRIPTION, FilesPeer::SINGER, FilesPeer::TAGS, FilesPeer::SIZE, FilesPeer::TODAY, FilesPeer::DOWNLOAD, FilesPeer::EXTENSION, FilesPeer::ORD, FilesPeer::STATUS, FilesPeer::URL, FilesPeer::CREATED_AT, ),
		BasePeer::TYPE_FIELDNAME => array ('id', 'file_name', 'category_id', 'description', 'singer', 'tags', 'size', 'today', 'download', 'extension', 'ord', 'status', 'url', 'created_at', ),
		BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, )
	);

	
	private static $fieldKeys = array (
		BasePeer::TYPE_PHPNAME => array ('Id' => 0, 'FileName' => 1, 'CategoryId' => 2, 'Description' => 3, 'Singer' => 4, 'Tags' => 5, 'Size' => 6, 'Today' => 7, 'Download' => 8, 'Extension' => 9, 'Ord' => 10, 'Status' => 11, 'Url' => 12, 'CreatedAt' => 13, ),
		BasePeer::TYPE_COLNAME => array (FilesPeer::ID => 0, FilesPeer::FILE_NAME => 1, FilesPeer::CATEGORY_ID => 2, FilesPeer::DESCRIPTION => 3, FilesPeer::SINGER => 4, FilesPeer::TAGS => 5, FilesPeer::SIZE => 6, FilesPeer::TODAY => 7, FilesPeer::DOWNLOAD => 8, FilesPeer::EXTENSION => 9, FilesPeer::ORD => 10, FilesPeer::STATUS => 11, FilesPeer::URL => 12, FilesPeer::CREATED_AT => 13, ),
		BasePeer::TYPE_FIELDNAME => array ('id' => 0, 'file_name' => 1, 'category_id' => 2, 'description' => 3, 'singer' => 4, 'tags' => 5, 'size' => 6, 'today' => 7, 'download' => 8, 'extension' => 9, 'ord' => 10, 'status' => 11, 'url' => 12, 'created_at' => 13, ),
		BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, )
	);

	
	public static function getMapBuilder()
	{
		include_once 'lib/model/map/FilesMapBuilder.php';
		return BasePeer::getMapBuilder('lib.model.map.FilesMapBuilder');
	}
	
	public static function getPhpNameMap()
	{
		if (self::$phpNameMap === null) {
			$map = FilesPeer::getTableMap();
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
		return str_replace(FilesPeer::TABLE_NAME.'.', $alias.'.', $column);
	}

	
	public static function addSelectColumns(Criteria $criteria)
	{

		$criteria->addSelectColumn(FilesPeer::ID);

		$criteria->addSelectColumn(FilesPeer::FILE_NAME);

		$criteria->addSelectColumn(FilesPeer::CATEGORY_ID);

		$criteria->addSelectColumn(FilesPeer::DESCRIPTION);

		$criteria->addSelectColumn(FilesPeer::SINGER);

		$criteria->addSelectColumn(FilesPeer::TAGS);

		$criteria->addSelectColumn(FilesPeer::SIZE);

		$criteria->addSelectColumn(FilesPeer::TODAY);

		$criteria->addSelectColumn(FilesPeer::DOWNLOAD);

		$criteria->addSelectColumn(FilesPeer::EXTENSION);

		$criteria->addSelectColumn(FilesPeer::ORD);

		$criteria->addSelectColumn(FilesPeer::STATUS);

		$criteria->addSelectColumn(FilesPeer::URL);

		$criteria->addSelectColumn(FilesPeer::CREATED_AT);

	}

	const COUNT = 'COUNT(files.ID)';
	const COUNT_DISTINCT = 'COUNT(DISTINCT files.ID)';

	
	public static function doCount(Criteria $criteria, $distinct = false, $con = null)
	{
				$criteria = clone $criteria;

				$criteria->clearSelectColumns()->clearOrderByColumns();
		if ($distinct || in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->addSelectColumn(FilesPeer::COUNT_DISTINCT);
		} else {
			$criteria->addSelectColumn(FilesPeer::COUNT);
		}

				foreach($criteria->getGroupByColumns() as $column)
		{
			$criteria->addSelectColumn($column);
		}

		$rs = FilesPeer::doSelectRS($criteria, $con);
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
		$objects = FilesPeer::doSelect($critcopy, $con);
		if ($objects) {
			return $objects[0];
		}
		return null;
	}
	
	public static function doSelect(Criteria $criteria, $con = null)
	{
		return FilesPeer::populateObjects(FilesPeer::doSelectRS($criteria, $con));
	}
	
	public static function doSelectRS(Criteria $criteria, $con = null)
	{
		if ($con === null) {
			$con = Propel::getConnection(self::DATABASE_NAME);
		}

		if (!$criteria->getSelectColumns()) {
			$criteria = clone $criteria;
			FilesPeer::addSelectColumns($criteria);
		}

				$criteria->setDbName(self::DATABASE_NAME);

						return BasePeer::doSelect($criteria, $con);
	}
	
	public static function populateObjects(ResultSet $rs)
	{
		$results = array();
	
				$cls = FilesPeer::getOMClass();
		$cls = Propel::import($cls);
				while($rs->next()) {
		
			$obj = new $cls();
			$obj->hydrate($rs);
			$results[] = $obj;
			
		}
		return $results;
	}

	
	public static function doCountJoinCategory(Criteria $criteria, $distinct = false, $con = null)
	{
				$criteria = clone $criteria;

				$criteria->clearSelectColumns()->clearOrderByColumns();
		if ($distinct || in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->addSelectColumn(FilesPeer::COUNT_DISTINCT);
		} else {
			$criteria->addSelectColumn(FilesPeer::COUNT);
		}

				foreach($criteria->getGroupByColumns() as $column)
		{
			$criteria->addSelectColumn($column);
		}

		$criteria->addJoin(FilesPeer::CATEGORY_ID, CategoryPeer::ID);

		$rs = FilesPeer::doSelectRS($criteria, $con);
		if ($rs->next()) {
			return $rs->getInt(1);
		} else {
						return 0;
		}
	}


	
	public static function doSelectJoinCategory(Criteria $c, $con = null)
	{
		$c = clone $c;

				if ($c->getDbName() == Propel::getDefaultDB()) {
			$c->setDbName(self::DATABASE_NAME);
		}

		FilesPeer::addSelectColumns($c);
		$startcol = (FilesPeer::NUM_COLUMNS - FilesPeer::NUM_LAZY_LOAD_COLUMNS) + 1;
		CategoryPeer::addSelectColumns($c);

		$c->addJoin(FilesPeer::CATEGORY_ID, CategoryPeer::ID);
		$rs = BasePeer::doSelect($c, $con);
		$results = array();

		while($rs->next()) {

			$omClass = FilesPeer::getOMClass();

			$cls = Propel::import($omClass);
			$obj1 = new $cls();
			$obj1->hydrate($rs);

			$omClass = CategoryPeer::getOMClass();

			$cls = Propel::import($omClass);
			$obj2 = new $cls();
			$obj2->hydrate($rs, $startcol);

			$newObject = true;
			foreach($results as $temp_obj1) {
				$temp_obj2 = $temp_obj1->getCategory(); 				if ($temp_obj2->getPrimaryKey() === $obj2->getPrimaryKey()) {
					$newObject = false;
										$temp_obj2->addFiles($obj1); 					break;
				}
			}
			if ($newObject) {
				$obj2->initFiless();
				$obj2->addFiles($obj1); 			}
			$results[] = $obj1;
		}
		return $results;
	}


	
	public static function doCountJoinAll(Criteria $criteria, $distinct = false, $con = null)
	{
		$criteria = clone $criteria;

				$criteria->clearSelectColumns()->clearOrderByColumns();
		if ($distinct || in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->addSelectColumn(FilesPeer::COUNT_DISTINCT);
		} else {
			$criteria->addSelectColumn(FilesPeer::COUNT);
		}

				foreach($criteria->getGroupByColumns() as $column)
		{
			$criteria->addSelectColumn($column);
		}

		$criteria->addJoin(FilesPeer::CATEGORY_ID, CategoryPeer::ID);

		$rs = FilesPeer::doSelectRS($criteria, $con);
		if ($rs->next()) {
			return $rs->getInt(1);
		} else {
						return 0;
		}
	}


	
	public static function doSelectJoinAll(Criteria $c, $con = null)
	{
		$c = clone $c;

				if ($c->getDbName() == Propel::getDefaultDB()) {
			$c->setDbName(self::DATABASE_NAME);
		}

		FilesPeer::addSelectColumns($c);
		$startcol2 = (FilesPeer::NUM_COLUMNS - FilesPeer::NUM_LAZY_LOAD_COLUMNS) + 1;

		CategoryPeer::addSelectColumns($c);
		$startcol3 = $startcol2 + CategoryPeer::NUM_COLUMNS;

		$c->addJoin(FilesPeer::CATEGORY_ID, CategoryPeer::ID);

		$rs = BasePeer::doSelect($c, $con);
		$results = array();

		while($rs->next()) {

			$omClass = FilesPeer::getOMClass();


			$cls = Propel::import($omClass);
			$obj1 = new $cls();
			$obj1->hydrate($rs);


					
			$omClass = CategoryPeer::getOMClass();


			$cls = Propel::import($omClass);
			$obj2 = new $cls();
			$obj2->hydrate($rs, $startcol2);

			$newObject = true;
			for ($j=0, $resCount=count($results); $j < $resCount; $j++) {
				$temp_obj1 = $results[$j];
				$temp_obj2 = $temp_obj1->getCategory(); 				if ($temp_obj2->getPrimaryKey() === $obj2->getPrimaryKey()) {
					$newObject = false;
					$temp_obj2->addFiles($obj1); 					break;
				}
			}

			if ($newObject) {
				$obj2->initFiless();
				$obj2->addFiles($obj1);
			}

			$results[] = $obj1;
		}
		return $results;
	}

	
	public static function getTableMap()
	{
		return Propel::getDatabaseMap(self::DATABASE_NAME)->getTable(self::TABLE_NAME);
	}

	
	public static function getOMClass()
	{
		return FilesPeer::CLASS_DEFAULT;
	}

	
	public static function doInsert($values, $con = null)
	{
		if ($con === null) {
			$con = Propel::getConnection(self::DATABASE_NAME);
		}

		if ($values instanceof Criteria) {
			$criteria = clone $values; 		} else {
			$criteria = $values->buildCriteria(); 		}

		$criteria->remove(FilesPeer::ID); 

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
			$comparison = $criteria->getComparison(FilesPeer::ID);
			$selectCriteria->add(FilesPeer::ID, $criteria->remove(FilesPeer::ID), $comparison);

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
			$affectedRows += FilesPeer::doOnDeleteCascade(new Criteria(), $con);
			$affectedRows += BasePeer::doDeleteAll(FilesPeer::TABLE_NAME, $con);
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
			$con = Propel::getConnection(FilesPeer::DATABASE_NAME);
		}

		if ($values instanceof Criteria) {
			$criteria = clone $values; 		} elseif ($values instanceof Files) {

			$criteria = $values->buildPkeyCriteria();
		} else {
						$criteria = new Criteria(self::DATABASE_NAME);
			$criteria->add(FilesPeer::ID, (array) $values, Criteria::IN);
		}

				$criteria->setDbName(self::DATABASE_NAME);

		$affectedRows = 0; 
		try {
									$con->begin();
			$affectedRows += FilesPeer::doOnDeleteCascade($criteria, $con);
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

				$objects = FilesPeer::doSelect($criteria, $con);
		foreach($objects as $obj) {


			include_once 'lib/model/DownloadHistory.php';

						$c = new Criteria();
			
			$c->add(DownloadHistoryPeer::FILE_ID, $obj->getId());
			$affectedRows += DownloadHistoryPeer::doDelete($c, $con);
		}
		return $affectedRows;
	}

	
	public static function doValidate(Files $obj, $cols = null)
	{
		$columns = array();

		if ($cols) {
			$dbMap = Propel::getDatabaseMap(FilesPeer::DATABASE_NAME);
			$tableMap = $dbMap->getTable(FilesPeer::TABLE_NAME);

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

		$res =  BasePeer::doValidate(FilesPeer::DATABASE_NAME, FilesPeer::TABLE_NAME, $columns);
    if ($res !== true) {
        $request = sfContext::getInstance()->getRequest();
        foreach ($res as $failed) {
            $col = FilesPeer::translateFieldname($failed->getColumn(), BasePeer::TYPE_COLNAME, BasePeer::TYPE_PHPNAME);
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

		$criteria = new Criteria(FilesPeer::DATABASE_NAME);

		$criteria->add(FilesPeer::ID, $pk);


		$v = FilesPeer::doSelect($criteria, $con);

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
			$criteria->add(FilesPeer::ID, $pks, Criteria::IN);
			$objs = FilesPeer::doSelect($criteria, $con);
		}
		return $objs;
	}

} 
if (Propel::isInit()) {
			try {
		BaseFilesPeer::getMapBuilder();
	} catch (Exception $e) {
		Propel::log('Could not initialize Peer: ' . $e->getMessage(), Propel::LOG_ERR);
	}
} else {
			require_once 'lib/model/map/FilesMapBuilder.php';
	Propel::registerMapBuilder('lib.model.map.FilesMapBuilder');
}
