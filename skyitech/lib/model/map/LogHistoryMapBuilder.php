<?php



class LogHistoryMapBuilder {

	
	const CLASS_NAME = 'lib.model.map.LogHistoryMapBuilder';

	
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

		$tMap = $this->dbMap->addTable('log_history');
		$tMap->setPhpName('LogHistory');

		$tMap->setUseIdGenerator(true);

		$tMap->addPrimaryKey('ID', 'Id', 'int', CreoleTypes::INTEGER, true, null);

		$tMap->addColumn('DATE', 'Date', 'int', CreoleTypes::INTEGER, false, 8);

		$tMap->addColumn('HOST', 'Host', 'int', CreoleTypes::INTEGER, false, 6);

		$tMap->addColumn('HITS', 'Hits', 'int', CreoleTypes::INTEGER, false, 6);

		$tMap->addColumn('DOWNLOAD', 'Download', 'int', CreoleTypes::INTEGER, false, 8);

		$tMap->addColumn('FILES', 'Files', 'string', CreoleTypes::VARCHAR, false, 255);

	} 
} 