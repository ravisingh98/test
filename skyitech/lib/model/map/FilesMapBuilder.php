<?php



class FilesMapBuilder {

	
	const CLASS_NAME = 'lib.model.map.FilesMapBuilder';

	
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

		$tMap = $this->dbMap->addTable('files');
		$tMap->setPhpName('Files');

		$tMap->setUseIdGenerator(true);

		$tMap->addPrimaryKey('ID', 'Id', 'int', CreoleTypes::INTEGER, true, null);

		$tMap->addColumn('FILE_NAME', 'FileName', 'string', CreoleTypes::VARCHAR, false, 255);

		$tMap->addForeignKey('CATEGORY_ID', 'CategoryId', 'int', CreoleTypes::INTEGER, 'category', 'ID', false, null);

		$tMap->addColumn('DESCRIPTION', 'Description', 'string', CreoleTypes::LONGVARCHAR, false, null);

		$tMap->addColumn('SINGER', 'Singer', 'string', CreoleTypes::VARCHAR, false, 255);

		$tMap->addColumn('TAGS', 'Tags', 'string', CreoleTypes::VARCHAR, false, 255);

		$tMap->addColumn('SIZE', 'Size', 'int', CreoleTypes::INTEGER, false, 9);

		$tMap->addColumn('TODAY', 'Today', 'int', CreoleTypes::INTEGER, false, 5);

		$tMap->addColumn('DOWNLOAD', 'Download', 'int', CreoleTypes::INTEGER, false, 6);

		$tMap->addColumn('EXTENSION', 'Extension', 'string', CreoleTypes::VARCHAR, false, 4);

		$tMap->addColumn('ORD', 'Ord', 'int', CreoleTypes::INTEGER, false, 4);

		$tMap->addColumn('STATUS', 'Status', 'string', CreoleTypes::VARCHAR, false, 1);

		$tMap->addColumn('URL', 'Url', 'string', CreoleTypes::VARCHAR, false, 255);

		$tMap->addColumn('CREATED_AT', 'CreatedAt', 'int', CreoleTypes::TIMESTAMP, false, null);

	} 
} 