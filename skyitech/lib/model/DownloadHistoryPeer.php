<?php

/**
 * Subclass for performing query and update operations on the 'download_history' table.
 *
 * 
 *
 * @package lib.model
 */ 
class DownloadHistoryPeer extends BaseDownloadHistoryPeer
{
	/*
	* SKYiTech :: @return count
	* 		return 21 files which are most downloaded for the day($limit)
	*/
	public static function getMostPopuler($day=0,$limit=21,$ext=''){
		if($day==0){
			if($ext)
				$sql = 'select files.id, files.file_name, files.category_id, files.today as download, files.description, files.extension,files.size ,files.singer, category.category_name as category_name,category.parentsarray as parentsarray from files LEFT JOIN category ON category.id=files.category_id where files.status!="B" and extension="'.$ext.'" order by today desc limit '.$limit;
			else
				$sql = 'select files.id, files.file_name, files.category_id, files.today as download, files.description, files.extension,files.size ,files.singer, category.category_name as category_name,category.parentsarray as parentsarray from files LEFT JOIN category ON category.id=files.category_id where files.status!="B" order by today desc limit '.$limit;
		}
		elseif($day==100){
			if($ext)
				$sql = 'select files.*,category.category_name as category_name,category.parentsarray as parentsarray from files LEFT JOIN category ON category.id=files.category_id where files.status!="B" and extension="'.$ext.'" order by download desc limit '.$limit;
			else
				$sql = 'select files.*,category.category_name as category_name,category.parentsarray as parentsarray from files LEFT JOIN category ON category.id=files.category_id where files.status!="B" order by download desc limit '.$limit;
		}
		else{
			if($ext)
			$sql = "select files.id, files.file_name, files.category_id, files.today as download, files.description, files.extension,files.size ,files.singer,sum(download_history.hits) as download,category.category_name as category_name,category.parentsarray as parentsarray from files JOIN download_history LEFT JOIN category ON category.id=files.category_id where files.extension='".$ext."' and files.id=download_history.file_id and files.status!='B' and download_history.date >= '".date('Ymd',strtotime('-'.$day.' day'))."' group by files.id order by download desc limit ".$limit;
			else
			$sql = "select files.id, files.file_name, files.category_id, files.today as download, files.description, files.extension,files.size ,files.singer,sum(download_history.hits) as download,category.category_name as category_name,category.parentsarray as parentsarray from files JOIN download_history LEFT JOIN category ON category.id=files.category_id where files.id=download_history.file_id and files.status!='B' and download_history.date >= '".date('Ymd',strtotime('-'.$day.' day'))."' group by files.id order by download desc limit ".$limit;
		}
		return skyMysqlQuery($sql);
	}

}
