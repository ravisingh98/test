<?php



class DownloadHistoryMapBuilder {

	
	const CLASS_NAME = 'lib.model.map.DownloadHistoryMapBuilder';

	
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

		$tMap = $this->dbMap->addTable('download_history');
		$tMap->setPhpName('DownloadHistory');

		$tMap->setUseIdGenerator(true);

		$tMap->addPrimaryKey('ID', 'Id', 'int', CreoleTypes::INTEGER, true, null);

		$tMap->addColumn('DATE', 'Date', 'string', CreoleTypes::VARCHAR, false, 8);

		$tMap->addForeignKey('FILE_ID', 'FileId', 'int', CreoleTypes::INTEGER, 'files', 'ID', false, null);

		$tMap->addColumn('EXTENSION', 'Extension', 'string', CreoleTypes::VARCHAR, false, 4);

		$tMap->addColumn('HITS', 'Hits', 'int', CreoleTypes::INTEGER, false, 5);

	} 
} 