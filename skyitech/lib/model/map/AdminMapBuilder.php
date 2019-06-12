<?php



class AdminMapBuilder {

	
	const CLASS_NAME = 'lib.model.map.AdminMapBuilder';

	
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

		$tMap = $this->dbMap->addTable('admin');
		$tMap->setPhpName('Admin');

		$tMap->setUseIdGenerator(true);

		$tMap->addPrimaryKey('ID', 'Id', 'int', CreoleTypes::INTEGER, true, null);

		$tMap->addColumn('USERNAME', 'Username', 'string', CreoleTypes::VARCHAR, true, 50);

		$tMap->addColumn('PASSWORD', 'Password', 'string', CreoleTypes::VARCHAR, true, 50);

		$tMap->addColumn('LEVEL', 'Level', 'int', CreoleTypes::INTEGER, true, 1);

		$tMap->addColumn('IS_SUPERADMIN', 'IsSuperadmin', 'string', CreoleTypes::VARCHAR, true, 1);

	} 
} 