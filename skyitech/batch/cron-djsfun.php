<?php

// batch process here
include(realpath(dirname(__FILE__).'/../dbconnect.php'));
connectDBAdmin();
/*
* SKYiTech:: enter data to download_history
*/
$sql = "SELECT id,today,extension FROM files ORDER BY today DESC LIMIT 21";
$rs = SkyMysqlQuery($sql);
//if(mysql_num_rows($rs))
{
	$sqlNew='INSERT INTO download_history (date,file_id,extension,hits) values';
	while($row = mysql_fetch_object($rs)){
		$sqlNew .= "('".date('Ymd',strtotime('-1 day'))."',".$row->id.",'".$row->extension."',".$row->today."),";
	}
	$sqlNew = substr($sqlNew,0,-1).';';
	SkyMysqlQuery($sqlNew);
}

/*
* get previous day's download
*/
$sql = "SELECT sum(today) as downloads FROM files";
$rs = skyMysqlQuery($sql);
$rs = mysql_fetch_object($rs);
$totalDownloads = $rs->downloads;

/*
* get downloads by filetype 
*/
$sql = "SELECT extension,sum(today) as cnt FROM files group by extension ORDER BY cnt DESC";
$files = '||';
if($rs = mysql_query($sql))
	while($row = mysql_fetch_object($rs)){
		if($row->cnt > 0)
			$files .= $row->extension.'-'.$row->cnt.'||';
	}
/*
* insert log of host,hits,download,files
*/
$sql = "INSERT INTO log_history (date,download,files) values('".date('Ymd',strtotime('-1 day'))."',".$totalDownloads.",'".$files."')";
mysql_query($sql);


/*
* SKYiTech:: enpty download_history
*/
$sql = "update files set today=0";
SkyMysqlQuery($sql);

/*
* SKYiTech:: manage flag
*/
$sql = "update category set flag_new=flag_new-1 where flag_new > 0";
SkyMysqlQuery($sql);
$sql = "update category set flag_updated=flag_updated-1 where flag_updated > 0";
SkyMysqlQuery($sql);
$sql = "update category set flag_hot=flag_hot-1 where flag_hot > 0";
SkyMysqlQuery($sql);


?>