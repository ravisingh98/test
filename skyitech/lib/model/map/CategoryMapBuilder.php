<?php



class CategoryMapBuilder {

	
	const CLASS_NAME = 'lib.model.map.CategoryMapBuilder';

	
	private $dbMap;

	
	public function isBuilt()
	{
		return ($this->dbMap !== null);
	}

	
	public function getDatabaseMap()
	{
		return $this->dbMap;
	}

	
	public function doBuild()
	{
		$this->dbMap = Propel::getDatabaseMap('propel');

		$tMap = $this->dbMap->addTable('category');
		$tMap->setPhpName('Category');

		$tMap->setUseIdGenerator(true);

		$tMap->addPrimaryKey('ID', 'Id', 'int', CreoleTypes::INTEGER, true, null);

		$tMap->addColumn('CATEGORY_NAME', 'CategoryName', 'string', CreoleTypes::VARCHAR, false, 100);

		$tMap->addColumn('TITLE', 'Title', 'string', CreoleTypes::VARCHAR, false, 255);

		$tMap->addColumn('DESCRIPTION', 'Description', 'string', CreoleTypes::LONGVARCHAR, false, null);

		$tMap->addColumn('PARENTS', 'Parents', 'string', CreoleTypes::VARCHAR, false, 100);

		$tMap->addColumn('PARENTSARRAY', 'Parentsarray', 'string', CreoleTypes::LONGVARCHAR, false, null);

		$tMap->addColumn('STATUS', 'Status', 'string', CreoleTypes::VARCHAR, false, 1);

		$tMap->addColumn('CHILD', 'Child', 'string', CreoleTypes::VARCHAR, false, 1);

		$tMap->addColumn('LIST_ORD', 'ListOrd', 'int', CreoleTypes::INTEGER, false, 1);

		$tMap->addColumn('ORD', 'Ord', 'int', CreoleTypes::INTEGER, false, 4);

		$tMap->addColumn('FLAG_NEW', 'FlagNew', 'int', CreoleTypes::INTEGER, false, 2);

		$tMap->addColumn('FLAG_UPDATED', 'FlagUpdated', 'int', CreoleTypes::INTEGER, false, 2);

		$tMap->addColumn('FLAG_HOT', 'FlagHot', 'int', CreoleTypes::INTEGER, false, 2);

		$tMap->addColumn('FILES', 'Files', 'int', CreoleTypes::INTEGER, false, 5);

		$tMap->addColumn('URL', 'Url', 'string', CreoleTypes::VARCHAR, false, 255);

	} 
} 