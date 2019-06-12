<?php



class SearchMapBuilder {

	
	const CLASS_NAME = 'lib.model.map.SearchMapBuilder';

	
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

		$tMap = $this->dbMap->addTable('search');
		$tMap->setPhpName('Search');

		$tMap->setUseIdGenerator(true);

		$tMap->addPrimaryKey('ID', 'Id', 'int', CreoleTypes::INTEGER, true, null);

		$tMap->addColumn('TAG', 'Tag', 'string', CreoleTypes::VARCHAR, false, 50);

		$tMap->addColumn('HITS', 'Hits', 'int', CreoleTypes::INTEGER, false, 5);

	} 
} 