<?php

class myClass
{
	/*
	* SKYiTech :: get online visitors at present
	*						require -
	*						return total number of online visitors
	*/
	public static function getOnlineVisitors($count=0){
		$sqliteDataDir = sfConfig::get('sf_root_dir').'/log';
		if(!is_file($sqliteDataDir.'/'.date('Ymd').'.sqlite3')){
			$db = new PDO('sqlite:'.$sqliteDataDir.'/'.date('Ymd').'.sqlite3');
//			$conn = sqlite_open($sqliteDataDir.'/'.date('Ymd').'.db', 0666, $sqliteerror) or die ($sqliteerror);
			$db->exec("CREATE TABLE online (key varchar(50) UNIQUE, hits integer(5), device varchar(1), updated_at time);");
//	  	sqlite_query($conn, 'CREATE TABLE online (key varchar(50) UNIQUE, hits integer(5), device varchar(1), updated_at time);');
			if(is_file($sqliteDataDir.'/'.date('Ymd',strtotime('-3 day')).'.db'))
				unlink($sqliteDataDir.'/'.date('Ymd',strtotime('-3 day')).'.db');
		}
		else{
			$db = new PDO('sqlite:'.$sqliteDataDir.'/'.date('Ymd').'.sqlite3');
//			$conn = sqlite_open($sqliteDataDir.'/'.date('Ymd').'.db', 0666, $sqliteerror) or die ($sqliteerror);
		}

		$key = md5(myClass::getIp().myClass::getBrowserForMd5());
		if(isset($_SESSION['sky_lastvisit'])){
			if($_SESSION['sky_lastvisit'] < time()-10 ){
	//	  	sqlite_query($conn, "UPDATE online SET hits=hits+1,updated_at='".date('H:i:s',time())."' WHERE key='".$key."'");
		  	$db->exec("UPDATE online SET hits=hits+1,updated_at='".date('H:i:s',time())."' WHERE key='".$key."'");
				$_SESSION['sky_lastvisit'] = time();
				//echo 'updated';
			}
	  }
	  else{	  //if(!sqlite_changes($conn))
//		  @sqlite_query($conn, "INSERT INTO online (key,hits,device,updated_at) values('".$key."',1,'".USERDEVICE."','".date('H:i:s',time())."');");
	  	$db->exec("INSERT INTO online (key,hits,device,updated_at) values('".$key."',1,'".@USERDEVICE."','".date('H:i:s',time())."');");
			$_SESSION['sky_lastvisit'] = time();
			//echo 'added';
		}

		if($count){
			if($count==1){
			  $sql = "SELECT COUNT(*) FROM online WHERE updated_at>'".date('H:i:s',time()-600)."'";
		  	$rs = $db->query($sql);
		  	$rs = $rs->fetch(PDO::FETCH_NUM);
				$db = NULL;
			  return $rs[0];
			}
		  elseif($count==2){
			  $sql = "SELECT device, COUNT(*) as host FROM online WHERE updated_at>'".date('H:i:s',time()-600)."' group by device";
		  	$rs = $db->query($sql,PDO::FETCH_ASSOC);
				$db = NULL;
			  return $rs;
			}
		}
//		sqlite_close($conn);
$db = NULL;
		return true;
	}

	public static function MultiFileName($filename,$type){
		return str_replace(sfConfig::get('app_filename2hide'), '_'.$type.sfConfig::get('app_filename2hide'), $filename);
	}

	/*
	* This will return either
		HTTP_X_FORWARDED_FOR
			or
		HTTP_X_REAL_IP
			or
		REMOTE_ADDR 
		
	*/
	public static function getIp(){
		if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])){
			$ip = explode(',',$_SERVER['HTTP_X_FORWARDED_FOR']);
			return trim($ip[0]);
		}
		elseif(isset($_SERVER['HTTP_X_REAL_IP']))
			return $_SERVER['HTTP_X_REAL_IP'];
		else
			return $_SERVER['REMOTE_ADDR'];
	}

	
	/*
	* This will return either
		HTTP_X_OPERAMINI_PHONE+HTTP_X_OPERAMINI_PHONE_UA
			or
		HTTP_X_OPERAMINI_PHONE
			or
		HTTP_USER_AGENT 
		
	*/
	public static function getBrowserForMd5(){
		if(isset($_SERVER['HTTP_X_OPERAMINI_PHONE'])){
			if(isset($_SERVER['HTTP_X_OPERAMINI_PHONE_UA']))
				return $_SERVER['HTTP_X_OPERAMINI_PHONE'].$_SERVER['HTTP_X_OPERAMINI_PHONE_UA'];
			else
				return $_SERVER['HTTP_X_OPERAMINI_PHONE'];
		}
		else
			return $_SERVER['HTTP_USER_AGENT'];
	}
	
	public static function getOddEven($class, $c1='odd', $c2='even'){
		if($class == $c1)
			$class = $c2;
		else
			$class = $c1;
		return $class;
	}

	//public static function getImageRatio($width1, $height1, $width, $height, $fileRequest, $fileExt, $fileSequenc, $folderName,$prdId = ''){
	public static function getImageRatio($fileName,$imgFolder,$width,$height,$width1, $height1,$scale = true,$saveAs,$watermark = false,$quality=80,$watermarkImg='logo.png',$position='bottom-right'){
		if($width==$width1 && $height==$height1){
		    $img = new sfImage($fileName); // using MIME detection
				if($watermark==true){
			    $img->overlay(new sfImage(sfConfig::get('sf_upload_dir').'/'.$watermarkImg), $position); // or you can use coords array($x,$y)
		  	}
		    $img->setQuality($quality);
		    $img->saveAs(sfConfig::get('sf_upload_dir').'/'.$imgFolder.$saveAs);
		    unset($img);
	  }
	  else{
			/*
			* make thumbnail image
			*/
			$thumbnail = new sfThumbnail($width,$height,$scale,true,$quality);
			$thumbnail->loadFile($fileName);
			$thumbnail->save(sfConfig::get('sf_upload_dir').'/'.$imgFolder.'/'.$saveAs, 'image/jpeg');
			unset($thumbnail);
			/*
			* SKYitech :: add water mark
			*/
			if($watermark==true){
		    $img = new sfImage(sfConfig::get('sf_upload_dir').'/'.$imgFolder.$saveAs); // using MIME detection
		    $img->overlay(new sfImage(sfConfig::get('sf_upload_dir').'/'.$watermarkImg), $position); // or you can use coords array($x,$y)
		    $img->setQuality($quality);
		    $img->save();
		    unset($img);
	  	}
  	}

	}

	
	/*
	SKYiTech :: function format_size()
			this function convert size from
			BYTES to KB, MB, GB, TB
			and return
	SKYiTech ::
	*/
	public static function formatSize($size, $round = 2) {
	    $sizes = array('Byts', 'kb', 'mb', 'gb','tb');
	    $total = count($sizes)-1;
	    for ($i=0; $size > 1024 && $i < $total; $i++) $size /= 1024;
	    return round($size,$round).' '.$sizes[$i];
	}

	public static function slugify($str){
		$str = str_replace(' ','_',$str);
		$str = str_replace(array('\'', '"', '&'),'',$str);
		return strtolower($str);
	}


	public static function TimeAgo($datefrom,$dateto=-1)
	{
		// Defaults and assume if 0 is passed in that
		// its an error rather than the epoch
		if($datefrom<=0) { return "A long time ago"; }
		if($dateto==-1) { $dateto = time(); }
		
		// Calculate the difference in seconds betweeen
		// the two timestamps
		$difference = $dateto - $datefrom;
		
		// If difference is less than 60 seconds,
		// seconds is a good interval of choice
		
		if($difference < 60)
		{
			$interval = "s";
		}
		
		// If difference is between 60 seconds and
		// 60 minutes, minutes is a good interval
		elseif($difference >= 60 && $difference<60*60)
		{
			$interval = "n";
		}
		
		// If difference is between 1 hour and 24 hours
		// hours is a good interval
		elseif($difference >= 60*60 && $difference<60*60*24)
		{
			$interval = "h";
		}
		
		// If difference is between 1 day and 7 days
		// days is a good interval
		elseif($difference >= 60*60*24 && $difference<60*60*24*7)
		{
			$interval = "d";
		}
		
		// If difference is between 1 week and 30 days
		// weeks is a good interval
		elseif($difference >= 60*60*24*7 && $difference <	60*60*24*30)
		{
			$interval = "ww";
		}
		
		// If difference is between 30 days and 365 days
		// months is a good interval, again, the same thing
		// applies, if the 29th February happens to exist
		// between your 2 dates, the function will return
		// the 'incorrect' value for a day
		elseif($difference >= 60*60*24*30 && $difference < 60*60*24*365)
		{
			$interval = "m";
		}
		
		// If difference is greater than or equal to 365
		// days, return year. This will be incorrect if
		// for example, you call the function on the 28th April
		// 2008 passing in 29th April 2007. It will return
		// 1 year ago when in actual fact (yawn!) not quite
		// a year has gone by
		elseif($difference >= 60*60*24*365)
		{
			$interval = "y";
		}
		
		// Based on the interval, determine the
		// number of units between the two dates
		// From this point on, you would be hard
		// pushed telling the difference between
		// this function and DateDiff. If the $datediff
		// returned is 1, be sure to return the singular
		// of the unit, e.g. 'day' rather 'days'
		
		switch($interval)
		{
		case "m":
		$months_difference = floor($difference / 60 / 60 / 24 /	29);
		while (mktime(date("H", $datefrom), date("i", $datefrom),
		date("s", $datefrom), date("n", $datefrom)+($months_difference),
		date("j", $dateto), date("Y", $datefrom)) < $dateto)
		{
			$months_difference++;
		}
		$datediff = $months_difference;
		
		// We need this in here because it is possible
		// to have an 'm' interval and a months
		// difference of 12 because we are using 29 days
		// in a month
		
		if($datediff==12)
		{
			$datediff--;
		}
		
		$res = ($datediff==1) ? "$datediff month ago" : "$datediff
		months ago";
		break;
		
		case "y":
		$datediff = floor($difference / 60 / 60 / 24 / 365);
		$res = ($datediff==1) ? "$datediff year ago" : "$datediff
		years ago";
		break;
		
		case "d":
		$datediff = floor($difference / 60 / 60 / 24);
		$res = ($datediff==1) ? "$datediff day ago" : "$datediff
		days ago";
		break;
		
		case "ww":
		$datediff = floor($difference / 60 / 60 / 24 / 7);
		$res = ($datediff==1) ? "$datediff week ago" : "$datediff
		weeks ago";
		break;
		
		case "h":
		$datediff = floor($difference / 60 / 60);
		$res = ($datediff==1) ? "$datediff hour ago" : "$datediff
		hours ago";
		break;
		
		case "n":
		$datediff = floor($difference / 60);
		$res = ($datediff==1) ? "$datediff minute ago" :
		"$datediff minutes ago";
		break;
		
		case "s":
		$datediff = $difference;
		$res = ($datediff==1) ? "$datediff second ago" :
		"$datediff seconds ago";
		break;
		}
		return $res;
	}

}