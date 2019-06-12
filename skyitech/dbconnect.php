<?php 

define('dbhost','localhost');
define('dbuser','getindia_skytech');
define('dbpass','$w$[y)9CBj+K');
define('dbadminuser','getindia_skytech');
define('dbadminpass','$w$[y)9CBj+K');
define('dbname','getindia_skytech');

function connectDB()
{
	global $conms;
  $conms = @mysql_connect(dbhost,dbuser,dbpass); //connect mysql
  if(!$conms) return false;
  $condb = @mysql_select_db(dbname);
  if(!$condb) die('Can\' connect database '. mysql_error());
  // echo '--connected--';
  return true;
}
function connectDBAdmin()
{
	global $conms;
  $conms = @mysql_connect(dbhost,dbadminuser,dbadminpass); //connect mysql with master server
  if(!$conms) return false;
  $condb = @mysql_select_db(dbname);
  if(!$condb) die('Can\' connect database '. mysql_error());
  // echo '--connected--';
  return true;
}

function checkDB()
{
	global $conms;
	if (!is_resource($conms))
  	connectDB();
  // echo '--dis-connected--';
}
function closeDB()
{
	global $conms;
	if (is_resource($conms))
  	mysql_close($conms);
  // echo '--dis-connected--';
}

function skyMysqlQuery($sql){
	global $conms;
	if (!is_resource($conms))
		connectDB();
	$sql = skyFilter($sql);
	$result = mysql_query($sql);
	return $result;
}
/*
* Get Single Record with row number
*			echo $row[0];
*/
function skyMysqlRow($sql){
	return mysql_fetch_row(skyMysqlQuery($sql));
}

/*
* Get Single Record with assoc
*					echo $row["userid"];
*/
function skyMysqlAssoc($sql){
	return mysql_fetch_assoc(skyMysqlQuery($sql));
}

/*
* Get Single Record with object name
*			echo $row->user_id;
*/
function skyMysqlObject($sql){
	return mysql_fetch_object(skyMysqlQuery($sql));
}

/*
* return value of selected field for passed id
*/
function skyGetNameById($tableName,$fieldName,$id)
{
	$sql = 'SELECT '.$fieldName.' FROM '.$tableName.' WHERE id="'.$id.'"';
	$rowName = mysql_fetch_object(skyMysqlQuery($sql));
	return $rowName->$fieldName;
}

function skyGetRecordById($tableName,$id)
{
	$sql = 'SELECT * FROM '.$tableName.' WHERE id='.$id;
	return mysql_fetch_object(skyMysqlQuery($sql));
}

function skyFilter($sql)
{
	$words = array("union ","information_schema","mysql.user","from admin");
	return str_replace($words,'',$sql);
}


function skyMysqlCount($tableName,$criteria='')
{
	$sql = 'SELECT count(*) FROM '.$tableName;
	if($criteria)
		$sql .= ' WHERE '.$criteria;
	$rs = mysql_fetch_row(skyMysqlQuery($sql));
	return $rs[0];
}

function skyMysqlGetCount($sql,$asitis=false)
{
	if($asitis==false){
		$sql = preg_replace('/select (\*|.*) from /','select count(id) from ',$sql);
		$rs = mysql_fetch_row(skyMysqlQuery($sql));
		return $rs[0];
	}
	else{
		$rs = mysql_num_rows(skyMysqlQuery($sql));
		return $rs;
	}
}

/*
* This function return start limit for mysql
*		arguments (totalRecordCount, current page, resultPerPage)
*		return Start Value
*/
function skyGetStartLimit($total, $page, $perpage){
	if((($page-1) * $perpage) >= $total)
		return (floor($total - $perpage) > -1) ? floor($total - $perpage) : 0 ;
	else
		return (($page-1) * $perpage);
}
/*
* SKYiTECH:: return formatted date
*/
function skyDate($format, $time){
	return date($format, strtotime($time));
}

/*
* call this function once before calling getCountry() function
*/
global $getCountryByIpClass;
function skyEnableCountryClass(){
	global $getCountryByIpClass;
	include "include/ipclass.php";
	$getCountryByIpClass = new get_user_info;
}
/*
* skyitech:: get country name by ip
*/
function skyGetCountry($ip=''){
	global $getCountryByIpClass;
	if(!$ip)
		$ip=$_SERVER['REMOTE_ADDR'];
	$getCountryByIpClass->set_ip($ip);
	$getCountryByIpClass->dist_ip();
	$getCountryByIpClass->count_ip();
	$info = $getCountryByIpClass->search_country();
	return $info['2letters_country'];
}

?>