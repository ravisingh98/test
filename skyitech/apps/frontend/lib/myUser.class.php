<?php

class myUser extends sfBasicSecurityUser
{
	public static function searchExts(){
		$e['ALL'] =  'ALL';
		foreach(explode(',', SETTING_SEARCH_EXTENSIONS) as $value)
			$e[$value] = $value;
		return $e;
	}

  public static function convert_name($name){
         $string = str_replace(' ','_',$name);
         return $string;
     }


	public static function getListOrd($l=0){
		switch ($l) {
		case 0:
		    return "new2old";
		    break;
		case 1:
		    return "a2z";
		    break;
		case 2:
		    return "z2a";
		    break;
		case 3:
		    return "download";
		    break;
		default:
		    return "new2old";
		}		
	}

	public static function fileName($str,$ext=true){
		$str = str_replace(sfConfig::get('app_filename2hide'), '', $str);
		if($ext==false){
			$str = pathinfo($str);
			$str = $str['filename'];
		}
		return $str;
	}

	public static function parentsName($pa,$sep=' ',$reverse=false,$pop=0){
		$pa = explode('||',$pa);
		array_shift($pa);
		$ret = '';
		if($reverse==true){
			for($i = (count($pa)-1)+$pop; $i>0; $i-=2){
				$ret .= $pa[$i];
				if($i>1)
					$ret .= $sep;
			}
		}
		else{
			for($i=0; $i<(count($pa)+$pop); $i+=2){
				$ret .= $pa[$i+1];
				if($i<(count($pa)+$pop-2))
					$ret .= $sep;
			}
		}
		return $ret;
	}


	public static function pageNavigate($pager,$uri){
		$navigation = '';
		$seePage = '';
		if ($pager->haveToPaginate()) {

		    // Pages one by one
		    $links = array();
		    $totalPage=0;
		    foreach ($pager->getLinks() as $page) {
		      $links[] = link_to_unless($page == $pager->getPage(), $page, $uri.$page);
		      if($page == $pager->getPage())
		      	$seePage = $page;
		      $totalPage++;
		    }

		    if ($pager->getPage() != 1) {
		    	if($totalPage > 5)
			      $navigation .= link_to(image_tag('first.png', 'class=absmiddle height=16 width=16'), $uri.'1');
		      $navigation .= link_to(image_tag('previous.png', 'class=absmiddle height=16 width=16'), $uri.$pager->getPreviousPage()).' ';
		    }

		    $navigation .= join('  ', $links);

		    if ($pager->getPage() != $pager->getLastPage()) {
		      $navigation .= ' '.link_to(image_tag('next.png', 'class=absmiddle height=16 width=16'), $uri.$pager->getNextPage());
		    	if($totalPage > 5)
			      $navigation .= link_to(image_tag('last.png', 'class=absmiddle height=16 width=16'), $uri.$pager->getLastPage());
		    }
			if($seePage > 0)
				$navigation .= '<br /><span style="font-size:x-small;">Page('.$seePage.'/'. $pager->getLastPage() .')</span> ';
		}
			return $navigation;
	}

	public static function skyPageNavigate($total_no,$start,$limit,$link){
		$totalLink = 5;
		$pagination='';
		//if($start!=1)
		//	$pagination = link_to(image_tag('first.png', 'class=absmiddle height=16 width=16'), $link.'1').'';
	
		$no_of_page=ceil($total_no/$limit);
	
		if ($start*$limit > $limit)
			$pagination .= link_to('< Prev', $link.($start-1));
	
		$count_record=0;
		$i=1;
		if($start >= $totalLink-floor($totalLink/2))
			$i=$start-floor($totalLink/2);
	
		if($no_of_page > $totalLink)
			if(($no_of_page-$i) < $totalLink)
				$i = $i-($totalLink-($no_of_page-$i)-1);

		for (;$i<=$no_of_page;$i++)
		{
			if($start==$i)
				$pagination .= '<span class="cur">'.$i.'</span>';
			else
				$pagination .= link_to($i,$link.$i).'';
			$count_record=$count_record+1;
			if ($count_record==$totalLink)
				break;
		}

		if($start < $no_of_page)
		{
			$pagination .= link_to('Next >', $link.($start+1));
			//$pagination .= link_to(image_tag('last.png', 'class=absmiddle height=16 width=16'), $link.$no_of_page);
		}
		if($start > 0)
			$pagination .= '<div>Page('.$start.'/'. $no_of_page .')</div>';

		return $pagination;
	}

	public static function getc($info,$e=0){
		if($e==1)
			echo '<!-- '.base64_decode('V2Fwa2FIb3N0LkNvbSBXZWIgU29sdXRpb24gOjog').base64_decode($info).' -->';	
		else
			echo '<!-- '.base64_decode('V2Fwa2FIb3N0LkNvbSBXZWIgU29sdXRpb24gOjog').$info.' -->';	
	}
	
	public static function detect_mobile_device(){
	  if(preg_match('/uc browser|ucweb|android|up.browser|up.link|windows ce|iemobile|mini|mmp|symbian|midp|wap|phone|pocket|mobile|pda|psp/i',$_SERVER['HTTP_USER_AGENT'])){
	    return true;
	  }
	  if(stristr($_SERVER['HTTP_USER_AGENT'],'windows')&&!stristr($_SERVER['HTTP_USER_AGENT'],'windows ce')){
	    return false;
	  }
	  if(stristr($_SERVER['HTTP_ACCEPT'],'text/vnd.wap.wml')||stristr($_SERVER['HTTP_ACCEPT'],'application/vnd.wap.xhtml+xml')){
	    return true;
	  }
	  if(isset($_SERVER['HTTP_X_WAP_PROFILE'])||isset($_SERVER['HTTP_PROFILE'])||isset($_SERVER['X-OperaMini-Features'])||isset($_SERVER['UA-pixels'])){
	    return true;
	  }
	  $dmdarray = array('acs-'=>'acs-','alav'=>'alav','alca'=>'alca','amoi'=>'amoi','audi'=>'audi','aste'=>'aste',
	  'avan'=>'avan','benq'=>'benq','bird'=>'bird','blac'=>'blac','blaz'=>'blaz','brew'=>'brew','cell'=>'cell',
	  'cldc'=>'cldc','cmd-'=>'cmd-','dang'=>'dang','doco'=>'doco','eric'=>'eric','hipt'=>'hipt','inno'=>'inno',
	  'ipaq'=>'ipaq','java'=>'java','jigs'=>'jigs','kddi'=>'kddi','keji'=>'keji','leno'=>'leno','lg-c'=>'lg-c',
	  'lg-d'=>'lg-d','lg-g'=>'lg-g','lge-'=>'lge-','maui'=>'maui','maxo'=>'maxo','midp'=>'midp','mits'=>'mits',
	  'mmef'=>'mmef','mobi'=>'mobi','mot-'=>'mot-','moto'=>'moto','mwbp'=>'mwbp','nec-'=>'nec-','newt'=>'newt',
	  'noki'=>'noki','opwv'=>'opwv','palm'=>'palm','pana'=>'pana','pant'=>'pant','pdxg'=>'pdxg','phil'=>'phil',
	  'play'=>'play','pluc'=>'pluc','port'=>'port','prox'=>'prox','qtek'=>'qtek','qwap'=>'qwap','sage'=>'sage',
	  'sams'=>'sams','sany'=>'sany','sch-'=>'sch-','sec-'=>'sec-','send'=>'send','seri'=>'seri','sgh-'=>'sgh-',
	  'shar'=>'shar','sie-'=>'sie-','siem'=>'siem','smal'=>'smal','smar'=>'smar','sony'=>'sony','sph-'=>'sph-',
	  'symb'=>'symb','t-mo'=>'t-mo','teli'=>'teli','tim-'=>'tim-','tosh'=>'tosh','treo'=>'treo','tsm-'=>'tsm-',
	  'upg1'=>'upg1','upsi'=>'upsi','vk-v'=>'vk-v','voda'=>'voda','wap-'=>'wap-','wapa'=>'wapa','wapi'=>'wapi',
	  'wapp'=>'wapp','wapr'=>'wapr','webc'=>'webc','winw'=>'winw','winw'=>'winw','xda-'=>'xda-');
	  // check if the first four characters of the current user agent are set as a key in the array
	  if(isset($dmdarray[substr($_SERVER['HTTP_USER_AGENT'],0,4)])){
	    return true;
	  }
	}
	

	public static function slugify($str){
		$str = str_replace(' ','_',$str);
		$str = str_replace(array('\'', '"', '&'),'',$str);
		return strtolower($str);
	}

	public static function fileDownload($filePath, $fileName, $ext){
		switch($ext){
			case 'MP3':
				$mime = "audio/mpeg";
				break;
			case 'JPG':
				$mime = "image/jpg";
				break;
			default:
				$mime = "application/force-download";
		}
		
		header('Content-type: '.$mime);
		header('Content-Disposition: attachment; filename="'.$fileName.'"');
		header('Content-Length: ' . filesize($filePath));
		readfile($filePath);
		exit;
	}
	public static function removeSameWords($str){
		$str = myUser::slugify($str);
		$str = explode('_',$str);
		$str = array_unique($str);
		return implode('_',$str);
	}

}
