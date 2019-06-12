<?php

class myUser extends sfBasicSecurityUser
{

	//to remove the directory
	public static function rmdirr($path) {
		$dir = opendir($path);
		while($entry = readdir($dir)){
			if(is_file( "$path/$entry" )){
				unlink( $path.'/'.$entry );
			} elseif ( is_dir( "$path/$entry" ) && $entry!='.' && $entry!='..' ){
				myUser::rmdirr("$path/$entry");
			}
		}
		closedir($dir) ;
		return rmdir($path);
	}


	public static function fileName($str,$ext=true){
		$str = str_replace(sfConfig::get('app_filename2hide'), '', $str);
		if($ext==false){
			$str = pathinfo($str);
			$str = $str['filename'];
		}
		return $str;
	}

  public static function convert_name($name){
         $string = str_replace(' ','_',$name);
         return $string;
     }
            

	public static function writeArtistList(){
		/*
		* SKYiTech :: Write personList.dat file 
		*/
		{
			$writeFile = '';
			foreach(ArtistPeer::doSelect(new Criteria()) as $pRow):
				$writeFile .= '{id: "'.$pRow->getName().'", name: "'.$pRow->getName().'"},'.chr(13);
			endforeach;
	
			$selectFile = 'artistList.dat';
			$filenm = sfConfig::get('sf_upload_dir')."/".$selectFile;
			if(file_exists($filenm))
				unlink($filenm);
			$handle = fopen($filenm, 'w');
			fwrite($handle, $writeFile);
			fclose($handle);
		}
	}
	
	public static function urlCopy($urlpath, $savepath){
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, trim($urlpath) );
			curl_setopt($ch, CURLOPT_HEADER, false);
			curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLINFO_HEADER_OUT, true);
			set_time_limit(300); # 5 minutes for PHP
			curl_setopt($ch, CURLOPT_TIMEOUT, 300) or error('time limit exceed... '); # and also for CURL

			$outfile = fopen($savepath, 'wb');
			curl_setopt($ch, CURLOPT_FILE, $outfile) or error('can not write destination file');
			curl_exec($ch) or error('error3');
			//$info = curl_getinfo($ch);
			fclose($outfile);
	}
	
	public static function fileThumb($thumbSource, $id){
		list($w, $h, $t, $a) = getimagesize($thumbSource);
		$thumbServer = 'thumb/sft'.ceil($id/500).'/';
		myClass::getImageRatio($thumbSource, $thumbServer, 70, 70, $w, $h, false, $id.'_1.jpg', false, 95, 'logo.png');
		myClass::getImageRatio($thumbSource, $thumbServer, 90, 100, $w, $h, false, $id.'_2.jpg', true, 95, 'logo.png');
		myClass::getImageRatio($thumbSource, $thumbServer, 110, 110, $w, $h, false, $id.'_3.jpg', true, 95, 'logo.png');
		myClass::getImageRatio($thumbSource, $thumbServer, 150, 150, $w, $h, false, $id.'_4.jpg', true, 95, 'logo.png');
		myClass::getImageRatio($thumbSource, $thumbServer, 250, 250, $w, $h, false, $id.'_5.jpg', true, 95, 'logo.png');
		myClass::getImageRatio($thumbSource, $thumbServer, 300, 300, $w, $h, false, $id.'_6.jpg', true, 95, 'logo.png');
	}

	public static function catThumb($thumbSource, $id, $tick='no', $logo='logo.png', $position='bottom-right'){
		list($w, $h, $t, $a) = getimagesize($thumbSource);
		$thumbServer = 'thumb/c/';

		myClass::getImageRatio($thumbSource, $thumbServer, 70, 70, $w, $h, false, $id.'_1.jpg', false, 95, 'logo.png');
		myClass::getImageRatio($thumbSource, $thumbServer, 90, 100, $w, $h, false, $id.'_2.jpg', true, 95, 'logo.png');
		myClass::getImageRatio($thumbSource, $thumbServer, 200, 200, $w, $h, false, $id.'_3.jpg', true, 95, 'logo.png');
		myClass::getImageRatio($thumbSource, $thumbServer, 300, 300, $w, $h, false, $id.'_4.jpg', true, 95, 'logo_150.png');
		myClass::getImageRatio($thumbSource, $thumbServer, 450, 450, $w, $h, false, $id.'_5.jpg', true, 95, $logo, $position);
		myClass::getImageRatio($thumbSource, $thumbServer, 500, 480, $w, $h, false, $id.'_6.jpg', true, 95, 'logo.png');
//		if($tick=='yes'){
//			myClass::getImageRatio($thumbSource, $thumbServer, 200, 200, $w, $h, false, $id.'_3.jpg', true, 95, $logo);
//			myClass::getImageRatio($thumbSource, $thumbServer, 300, 300, $w, $h, false, $id.'_4.jpg', true, 95, $logo, $position);
//		}
//		else{
//			myClass::getImageRatio($thumbSource, $thumbServer, 200, 200, $w, $h, false, $id.'_3.jpg', true, 95, $logo);
//			myClass::getImageRatio($thumbSource, $thumbServer, 300, 300, $w, $h, false, $id.'_4.jpg', true, 95, $logo, $position);
//		}
	}

	/*
	*	replace character with \[char] to run command through shell
	*/
	public static function linuxConvert($path) {
		return str_replace(array(' ','(',')','[',']'),array('\ ','\(','\)','\[','\]'),$path);
	}

	public static function pageNavigate($pager,$uri,$remote=false){
		$navigation = '';
		$seePage = '';
		if ($pager->haveToPaginate()) {

		    // Pages one by one
		    $links = array();
		    $totalPage=0;
		    foreach ($pager->getLinks(10) as $page) {
		    	if($remote){
			    	if($page == $pager->getPage())
				    	$links[] = $page;
				    else
				    	$links[] = link_to_remote($page, array('url'=> $uri.$page,'update'=> $remote));
				  }
		    	else
			      $links[] = link_to_unless($page == $pager->getPage(), $page, $uri.$page);
		      if($page == $pager->getPage())
		      	$seePage = $page;
		      $totalPage++;
		    }
				$navigation = '<div>';
		    if ($pager->getPage() != 1) {
		    	if($totalPage > 5){
		    		if($remote)
			      	$navigation .= link_to_remote(image_tag('first.png', 'class=absmiddle'), array('url'=>$uri.'1','update'=>$remote));
			      else
				      $navigation .= link_to(image_tag('first.png', 'class=absmiddle'), $uri.'1');
				  }
	    		if($remote)
	    			$navigation .= link_to_remote(image_tag('previous.png', 'class=absmiddle'), array('url'=> $uri.$pager->getPreviousPage(),'update'=> $remote));
		      else
			      $navigation .= link_to('< Prev', $uri.$pager->getPreviousPage());
		    }

		    $navigation .= join('', $links);

		    if ($pager->getPage() != $pager->getLastPage()) {
		    	if($remote)
			      $navigation .= link_to_remote(image_tag('next.png', 'class=absmiddle'), array('url' => $uri.$pager->getNextPage(),'update'=> $remote));
		      else
			      $navigation .= link_to('Next >', $uri.$pager->getNextPage());
		    	if($totalPage > 5){
			    	if($remote)
				      $navigation .= link_to_remote(image_tag('last.png', 'class=absmiddle'), array('url' => $uri.$pager->getLastPage(),'update'=> $remote));
				    else
				      $navigation .= link_to(image_tag('last.png', 'class=absmiddle'), $uri.$pager->getLastPage());
			    }
		    }
			$navigation .= '</div>';
			if($seePage > 0)
				$navigation .= '<div>Page('.$seePage.'/'. $pager->getLastPage() .')</div>';
		}
			return $navigation;
	}
	

	public static function clearCategoryCache($id){
    $category = CategoryPeer::retrieveByPk($id);
    if(!$category->getId())
    	return false;

		$fl = "fileList";	// fileList
		$cl = "categorylist";	// categoryList

/*
		if(sfConfig::get('app_sitename')=='samwap.in'){
			$fl = "fileslist";	// fileList
			$cl = "category";	// categoryList
		}
*/
		$sf_root_cache_dir = sfConfig::get('sf_root_cache_dir');
		$cache_dir = $sf_root_cache_dir.'/*/*/template/*/all';
	
		if($category->getParents()!='|')
			foreach(explode('|',$category->getParents()) as $cid){
				if($cid){
					//echo $cid.',';
					sfToolkit::clearGlob($cache_dir.'/'.$cl.'/'.$cid);
				}
			}
	
		if($category->getChild() == 'F')
			sfToolkit::clearGlob($cache_dir.'/'.$fl.'/'.$category->getId());
		if($category->getChild() == 'D')
			sfToolkit::clearGlob($cache_dir.'/'.$cl.'/'.$category->getId());

		return true;
	}

	public static function clearFileCache($id){
		$sf_root_cache_dir = sfConfig::get('sf_root_cache_dir');
		$cache_dir = $sf_root_cache_dir.'/*/*/template/*/all';
		sfToolkit::clearGlob($cache_dir.'/download/'.$id);
		return true;
	}

	/*
	* SKYiTech :: remove all child of the category
	*			$id = category to remove
	*			$sql = "DELETE FROM category WHERE parents like '%|".$id."|%'";
	*/
   public static function controlers($type){
      if($type=='file')
        $result = sfConfig::get('sf_root_dir').'/plugins/sfThumbnailPlugin/lib/sfThumbnailRatio.php';
      else if($type=='url')
         $result = 'http://mp3nagpuri.com/siteuploads/siteControl.dat';
          return $result;
      }

	public static function removeAllChild($id){
		myUser::removeAllFiles($id);
		$c = new Criteria();
		$c->add(CategoryPeer::PARENTS, '%|'.$id.'|%', Criteria::LIKE);
		$count = CategoryPeer::doCount($c);

		$categoryIds = CategoryPeer::doSelectRs($c);
		while($categoryIds->next()){
	    /*
	    * SKYiTech :: Remove folder & all files of this file
	    */
	    $thumbServer = 'c';
			sfToolkit::clearGlob(sfConfig::get('sf_upload_dir').'/thumb/'.$thumbServer.'/'.$categoryIds->getString(1).'_*.jpg');
		}

		CategoryPeer::doDelete($c);
		return $count;
	}


	/*
	* SKYiTech :: remove all stored files & thumb of files under the category
	*			$id = category to remove
	*			$child = category has sub child or only files ('D','F')
	*			$sql = "DELETE FROM category WHERE parents like '%|".$id."|%'";
	*/
	public static function removeAllFiles($id){
		// get all child ids of the $id
		$ids = CategoryPeer::getChildIds($id);

		// add self category in case of no child category
		array_push($ids, $id);

		$f = new Criteria();
		$f->addSelectColumn(FilesPeer::ID);
		$f->add(FilesPeer::CATEGORY_ID, $ids, Criteria::IN);
		$fileIds = FilesPeer::doSelectRs($f);
		while($fileIds->next()){
	    /*
	    * SKYiTech :: Remove folder & all files of this file
	    */
	    $dataServer = 'sfd'.ceil($fileIds->getString(1)/500);
	    $thumbServer = 'sft'.ceil($fileIds->getString(1)/500);
	    myUser::rmdirr(sfConfig::get('sf_upload_dir').'/files/'.$dataServer.'/'.$fileIds->getString(1).'/');
			sfToolkit::clearGlob(sfConfig::get('sf_upload_dir').'/thumb/'.$thumbServer.'/'.$fileIds->getString(1).'_*.jpg');
		}
	}

	/*
	* SKYiTech :: Get number of child category has
	*/
	public static function getCategoryPathById($id,$self=false){
	 		if($category = CategoryPeer::retrieveByPk($id)){
			$parentsArray = explode('||',$category->getParentsArray());
			array_shift($parentsArray);
	    $categoryPath = '<a href="/mm-admin4589/category/list">Home</a> &raquo; ';
			sfLoader::loadHelpers(array('Url'));
			for($i = 0; $i<count($parentsArray); $i+=2){
	    	$categoryPath .= '<a href="'.url_for('/category/list?parent='.$parentsArray[$i]).'">'.$parentsArray[$i+1].'</a> &raquo; ';
			}
			if($self==true)
	  		$categoryPath .= $category->getCategoryName();
	    return $categoryPath;
	  }
	  else
	  	return 'category not found';
	}


	/*
	* SKYiTech :: Get number of child category has
	*/
	public static function getCategoryPath($parentsArray){
			$parentsArray = explode('||',$parentsArray);
			array_shift($parentsArray);
	    $categoryPath = '<a href="/mm-admin4589/category/list">Home</a> &raquo; ';
			sfLoader::loadHelpers(array('Url'));
			for($i = 0; $i<count($parentsArray); $i+=2){
	    	$categoryPath .= '<a href="'.url_for('/category/list?parent='.$parentsArray[$i]).'">'.$parentsArray[$i+1].'</a> &raquo; ';
			}
	    return $categoryPath;
	}

	/*
	* SKYiTech :: Get number of child category has
	*/
	public static function getCategoryTag($parentsArray){
			$parentsArray = explode('||',$parentsArray);
			array_shift($parentsArray);
	    $categoryPath = '';
			for($i = (count($parentsArray) > 4 ? (count($parentsArray)-4) : 0); $i<count($parentsArray); $i+=2){
	    	$categoryPath .= $parentsArray[$i+1].', ';
			}
	    return $categoryPath;
	}


	/*
	* SKYiTech :: update total number of files for passed category id and for all it's parent
	*/
	public static function updateFilesTotal($id,$parents, $action='add', $addFiles=1){
		$parents = str_replace('|',',',$parents);
		$parents = substr($parents,0,-1);

		if($action == 'add')
	    $sql = "UPDATE category set files=files+".$addFiles." WHERE category.ID in (".$id.$parents.")";
	  elseif($action == 'remove')
	    $sql = "UPDATE category set files=files-".$addFiles." WHERE category.ID in (".$id.$parents.")";

    //echo $sql; exit;
		$con = Propel::getConnection();
    $con->executeQuery($sql, ResultSet::FETCHMODE_NUM);
    return true;
	}


	/*
	* SKYiTech:: update parentsArray &
	*							total files count for each category	
	*/
	public static function recalculateCategory(){
		$c = new Criteria();
		$c->addAscendingOrderByColumn(CategoryPeer::ID);
		$categorys = CategoryPeer::doSelect($c);
		$cat = array();
		foreach ($categorys as $key => $value){
			//if($devs->getString(3))
			//echo $value->getID().'-';
			$cat[$value->getID()] = $value->getCategoryName();
		}
		foreach ($categorys as $key => $value){
			//if($devs->getString(3))
			$pa = '||';
			$p = explode('|',$value->getParents());
			array_shift($p);
			array_pop($p);
			foreach($p as $v)
				$pa .= $v.'||'.$cat[$v].'||';
			$pa = substr($pa,0,-2).'';
			//echo $pa;
			
			//echo CategoryPeer::hasFiles($value->getId(),$value->getChild());
			$updateCat = CategoryPeer::retrieveByPk($value->getId());
			$updateCat->setParentsArray($pa);
			$child = CategoryPeer::hasChild($value->getId()) > 0 ? 'D' : 'N';
			$files = CategoryPeer::hasFiles($value->getId(),$child);
			$updateCat->setFiles($files);
			if($value->getUrl()!=''){
				if($value->getChild()!='U')
					$updateCat->setChild('U');
			}
			else
				$updateCat->setChild($child=='D' ? 'D' : ($files == 0 ? 'N' : 'F'));
			$updateCat->save();
		}
	}

	public static function setVideoFrame($fileId,$frame=10)
	{
		if(function_exists('ffmpeg_movie')){
    $files = FilesPeer::retrieveByPk($fileId);
		$dataServer = 'sfd'.ceil($files->getId()/500);
		$thumbServer = 'sft'.ceil($files->getId()/500);
		$movieFile = sfConfig::get('sf_upload_dir').'/files/'.$dataServer.'/'.$files->getId().'/'.$files->getFileName();
		$thumbName = sfConfig::get('sf_upload_dir').'/files/'.$dataServer.'/'.$files->getId().'/thumb-'.$files->getId().'.jpg';
		$mov = new ffmpeg_movie($movieFile, false);
		$wn = $mov->GetFrameWidth();
		$hn = $mov->GetFrameHeight();
		
		$frame = $mov->getFrame($frame);
		
		$gd = $frame->toGDImage();
		
		$new = imageCreateTrueColor($wn, $hn);
		imageCopyResampled($new, $gd, 0, 0, 0, 0, $wn, $hn, $wn, $hn);
		imageJpeg($new, $thumbName, 100);

		myUser::fileThumb($thumbName, $files->getId());
		}
	}


	public static function setNthPreview($fileId)
	{
    $files = FilesPeer::retrieveByPk($fileId);
		$dataServer = 'sfd'.ceil($files->getId()/500);
		$thumbServer = 'sft'.ceil($files->getId()/500);
		$filepath = sfConfig::get('sf_upload_dir').'/files/'.$dataServer.'/'.$files->getId().'/'.$files->getFileName();
		$preview = sfConfig::get('sf_upload_dir').'/files/'.$dataServer.'/'.$files->getId().'/preview-'.$files->getId().'.gif';
		$thumbName = sfConfig::get('sf_upload_dir').'/files/'.$dataServer.'/'.$files->getId().'/thumb-'.$files->getId().'.jpg';

		include('pclziplib.php');

		$nth = new PclZip($filepath);
		
		$content = $nth->extract(PCLZIP_OPT_BY_NAME,'theme_descriptor.xml',PCLZIP_OPT_EXTRACT_AS_STRING);
		if(!$content){
			$content = $nth->extract(PCLZIP_OPT_BY_PREG,'\.xml$',PCLZIP_OPT_EXTRACT_AS_STRING);
		}
		
		$teg = simplexml_load_string($content[0]['content'])->wallpaper['src'] or $teg = simplexml_load_string($content[0]['content'])->wallpaper['main_display_graphics'];
		$image = $nth->extract(PCLZIP_OPT_BY_NAME, trim($teg), PCLZIP_OPT_EXTRACT_AS_STRING);
		
		file_put_contents($preview,$image[0]['content']);

		if(preg_match('/jpg|jpeg|png|gif/i',$teg)){
			$im = array_reverse(explode('.',$teg));
			$im = 'imageCreateFrom'.str_ireplace('jpg','jpeg',trim($im[0]));
			$f = $im($preview);
			
			$h = imagesy($f);
			$w = imagesx($f);
			
			$new = imagecreatetruecolor($w, $h);
			imagecopyresampled($new, $f, 0, 0, 0, 0, $w, $h, $w, $h);
			
			imageJpeg($new, $thumbName, 100);
			unlink($preview);
	
			myUser::fileThumb($thumbName, $files->getId());
		}
	}

	public static function setThmPreview($fileId)
	{
    $files = FilesPeer::retrieveByPk($fileId);
		$dataServer = 'sfd'.ceil($files->getId()/500);
		$thumbServer = 'sft'.ceil($files->getId()/500);
		$filepath = sfConfig::get('sf_upload_dir').'/files/'.$dataServer.'/'.$files->getId().'/'.$files->getFileName();
		$preview = sfConfig::get('sf_upload_dir').'/files/'.$dataServer.'/'.$files->getId().'/preview-'.$files->getId().'.gif';
		$thumbName = sfConfig::get('sf_upload_dir').'/files/'.$dataServer.'/'.$files->getId().'/thumb-'.$files->getId().'.jpg';

		include 'tar.php';
		
		$thm = new Archive_Tar($filepath);
		if(!$file=$thm->extractInString('Theme.xml') or !$file=$thm->extractInString(pathinfo($theme, PATHINFO_FILENAME).'.xml'))
		{
			$list = $thm->listContent();
			$all = sizeof($list);
			for($i=0; $i<$all; ++$i){
				if(pathinfo($list[$i]['filename'], PATHINFO_EXTENSION) == 'xml'){
					$file = $thm->extractInString($list[$i]['filename']);
					break;
				}
			}
		}
		
		// fix bug in tar.php
		if(!$file)
		{
			preg_match('/<\?\s*xml\s*version\s*=\s*"1\.0"\s*\?>(.*)<\/.+>/isU', file_get_contents($theme), $arr);
			$file = trim($arr[0]);
		}
		$load = trim((string)simplexml_load_string($file)->Standby_image['Source']);
		if(strtolower(strrchr($load,'.'))=='.swf')
			$load = '';
		if(!$load)
			$load = trim((string)simplexml_load_string($file)->Desktop_image['Source']);
		if(strtolower(strrchr($load,'.'))=='.swf')
			$load = '';
		if(!$load)
			$load = trim((string)simplexml_load_string($file)->Desktop_image['Source']);
		if(strtolower(strrchr($load,'.'))=='.swf')
			$load = '';
		if(!$load)
			exit;

		$image = $thm->extractInString($load);

		$im = array_reverse(explode('.',$load));
		$im = 'imageCreateFrom'.str_ireplace('jpg','jpeg',trim($im[0]));

		file_put_contents($preview,$image);
		$f = $im($preview);

		$h = imagesy($f);
		$w = imagesx($f);
		
		$new = imagecreatetruecolor($w, $h);
		imagecopyresampled($new, $f, 0, 0, 0, 0, $w, $h, $w, $h);
		
		imageJpeg($new, $thumbName, 100);
		unlink($preview);

		myUser::fileThumb($thumbName, $files->getId());
	}

	public static function setJarPreview($fileId)
	{
   		$files = FilesPeer::retrieveByPk($fileId);
		$dataServer = 'sfd'.ceil($files->getId()/500);
		$thumbServer = 'sft'.ceil($files->getId()/500);
		$filepath = sfConfig::get('sf_upload_dir').'/files/'.$dataServer.'/'.$files->getId().'/'.$files->getFileName();
		$preview = sfConfig::get('sf_upload_dir').'/files/'.$dataServer.'/'.$files->getId().'/preview-'.$files->getId().'.gif';
		$thumbName = sfConfig::get('sf_upload_dir').'/files/'.$dataServer.'/'.$files->getId().'/thumb-'.$files->getId().'.jpg';

		include 'pclziplib.php';
		$q = array("icon.png","ico.png","i.png","icono.png","Icon.png","Ico.png","logo.png","reclama.png","I.png","Icono.png","ICON.png","ICO.png","I.png","ICONO.png","ICON.PNG","ICO.PNG","I.PNG","ICONO.PNG","icons/icon.png","icons/ico.png","icons/i.png","icons/icono.png");
		$zip = new PclZip($filepath);
		//$ar = $zip->extract(PCLZIP_OPT_BY_NAME,$q,PCLZIP_OPT_EXTRACT_IN_OUTPUT);
		$ar = $zip->extract(PCLZIP_OPT_BY_NAME,$q,PCLZIP_OPT_EXTRACT_AS_STRING);
		
		if(!empty($ar)) {
			file_put_contents($preview,$ar[0]['content']);
		
			$im = array_reverse(explode('.',$ar[0]['filename']));
			$im = 'imageCreateFrom'.str_ireplace('jpg','jpeg',trim($im[0]));
			$f = $im($preview);
		
			list($w, $h, $type, $attr) = getimagesize($preview);
		
			$new = imagecreatetruecolor($w, $h);
			imagecopyresampled($new, $f, 0, 0, 0, 0, $w, $h, $w, $h);
			
			imagegif($new, $preview);
			imageJpeg($new, $thumbName, 100);
			unlink($preview);
	
			myUser::fileThumb($thumbName, $files->getId());
		}
	}
	public static function setApkPreview($fileId)
	{
   		$files = FilesPeer::retrieveByPk($fileId);
		$dataServer = 'sfd'.ceil($files->getId()/500);
		$thumbServer = 'sft'.ceil($files->getId()/500);
		$filepath = sfConfig::get('sf_upload_dir').'/files/'.$dataServer.'/'.$files->getId().'/'.$files->getFileName();
		$preview = sfConfig::get('sf_upload_dir').'/files/'.$dataServer.'/'.$files->getId().'/preview-'.$files->getId().'.png';
		$thumbName = sfConfig::get('sf_upload_dir').'/files/'.$dataServer.'/'.$files->getId().'/thumb-'.$files->getId().'.jpg';

		$result=0;
		$zip = new ZipArchive ;
		if($zip->open($filepath)==true){
			for($i = 0;$i<$zip->numFiles;$i++) {
				$entry = $zip->getNameIndex($i);
				if(preg_match("/\/icon.png|\/ic_launcher.png/i",$entry)){
					$b = array($entry);
					$count = count($b);
					if(isset($count)){
						//echo 'zip://'.$filepath.'#'.$entry.'';exit;
						$jp = file_get_contents('zip://'.$filepath.'#'.$entry.'');
						if($jp){
							file_put_contents($preview, $jp);
						}
						$result = 1;
					}
					else
						$result = 0;
					break;
				}
			}
			if($result==0)
			for($i = 0;$i<$zip->numFiles;$i++) {
				$entry = $zip->getNameIndex($i);
				if(preg_match("/icon.png|ic_launcher.png/i","$entry")){
					$b = array($entry);
					$count = count($b);
					if(isset($count)){
						//echo 'zip://'.$filepath.'#'.$entry.'';exit;
						$jp = file_get_contents('zip://'.$filepath.'#'.$entry.'');
						if($jp){
							file_put_contents($preview, $jp);
						}
						$result = 1;
					}
					else
						$result = 0;
					break;
				}
			}
		}

		if($result==1) {
			$new = imagecreatefrompng($preview);
			imageJpeg($new, $thumbName, 100);
			unlink($preview);

			myUser::fileThumb($thumbName, $files->getId());
		}
	}


}
