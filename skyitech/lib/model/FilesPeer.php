<?php

/**
 * Subclass for performing query and update operations on the 'files' table.
 *
 * 
 *
 * @package lib.model
 */ 
class FilesPeer extends BaseFilesPeer
{
	public static function getFileInfo($id){
		$filesss = FilesPeer::retrieveByPk($id);
		return $filesss;
	}

	public static function getFilesById($ids){
		$filesss = FilesPeer::retrieveByPks($ids);
		return $filesss;
	}
}
