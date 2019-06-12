<?php

class filesActions extends sfActions
{
  public function executeIndex()
  {
    return $this->forward('files', 'list');
  }

  public function executeSetOrder()
  {
  	if($this->getUser()->getAttribute('setOrderOnOff') == 'show')
			$this->getUser()->setAttribute('setOrderOnOff', 'hide');
		else
			$this->getUser()->setAttribute('setOrderOnOff', 'show');

    return $this->redirect($_SERVER['HTTP_REFERER']);
  }

  public function executeList()
  {
		if($this->getUser()->hasAttribute('recperpage_fl') == FALSE)
			$this->getUser()->setAttribute('recperpage_fl', 10);
		if($this->getUser()->hasAttribute('setOrderOnOff') == FALSE)
			$this->getUser()->setAttribute('setOrderOnOff', 'hide');
	  if($this->getRequestParameter('cid') || $this->getRequestParameter('setord')=='true')
	  	$this->setOrderOnOff = $this->getUser()->getAttribute('setOrderOnOff');
	  else
	  	$this->setOrderOnOff = 'hide';

	  $this->recperpage = $this->getUser()->getAttribute('recperpage_fl');

		if($this->getRequestParameter('setord')!='true'){
		$c = new Criteria();
		if($this->getRequestParameter('cid')){
		  $this->parent = $this->getRequestParameter('cid');
		  $this->parentCategory = CategoryPeer::retrieveByPk($this->getRequestParameter('cid'));
			$c->add(FilesPeer::CATEGORY_ID, $this->getRequestParameter('cid'));
			//$this->fileUrls = WapFileTablePeer::getFileUrls($this->getRequestParameter('cid'));
		}
		if($this->getRequestParameter('name'))
			$c->add(FilesPeer::FILE_NAME , '%'.$this->getRequestParameter('name').'%', Criteria::LIKE);
	
		if($this->getRequestParameter('size')=='zero')
			$c->add(FilesPeer::SIZE, 0);

		if($this->getRequestParameter('searchText')){
			$searchText = str_replace(' ','%',$this->getRequestParameter('searchText'));
			if($this->getRequestParameter('search')=='Id')
				$c->add(FilesPeer::ID, $this->getRequestParameter('searchText'));
			if($this->getRequestParameter('search')=='FileName')
				$c->add(FilesPeer::FILE_NAME , '%'.$this->getRequestParameter('searchText').'%', Criteria::LIKE);
			if($this->getRequestParameter('search')=='Tag')
				$c->add(FilesPeer::TAGS , '%'.$this->getRequestParameter('searchText').'%', Criteria::LIKE);
			if($this->getRequestParameter('search')=='Artist')
				$c->add(FilesPeer::SINGER , '%'.$this->getRequestParameter('searchText').'%', Criteria::LIKE);
			if($this->getRequestParameter('search')=='Url')
				$c->add(FilesPeer::URL , '%'.$this->getRequestParameter('searchText').'%', Criteria::LIKE);
		}
		
		if($this->getRequestParameter('status')=='b')
			$c->add(FilesPeer::STATUS, 'B');
		elseif($this->getRequestParameter('status')=='a')
			$c->add(FilesPeer::STATUS, 'A');
		elseif($this->getRequestParameter('status')=='f')
			$c->add(FilesPeer::STATUS, 'F');

		if($this->getRequestParameter('extension'))
			$c->add(FilesPeer::EXTENSION, strtoupper($this->getRequestParameter('extension')));
	
		if($this->getRequestParameter('sort')=='d')
			$c->addDescendingOrderByColumn(FilesPeer::DOWNLOAD);
		elseif($this->getRequestParameter('sort')=='dt')
			$c->addDescendingOrderByColumn(FilesPeer::TODAY);
		elseif($this->getRequestParameter('sort')=='a2z')
			$c->addAscendingOrderByColumn(FilesPeer::FILE_NAME);
		elseif($this->getRequestParameter('sort')=='z2a')
			$c->addDescendingOrderByColumn(FilesPeer::FILE_NAME);
		else{
			if($this->getRequestParameter('cid'))
				$c->addAscendingOrderByColumn(FilesPeer::ORD);
			$c->addDescendingOrderByColumn(FilesPeer::ID);
		}
	
		if($this->setOrderOnOff == "hide"){
	    $pager = new sfPropelPager('Files', $this->getUser()->getAttribute('recperpage_fl'));
	    $pager->setCriteria($c);
	    $pager->setPage($this->getRequestParameter('page', 1));
	    $pager->init();
	    $this->pager = $pager;
	    $this->filess = $pager->getResults();
  	}
  	else
  		$this->filess = FilesPeer::doSelect($c);
  	}
  }

  
  public function executeShow()
  {
    $this->files = FilesPeer::retrieveByPk($this->getRequestParameter('id'));
    $this->forward404Unless($this->files);
  }
 
  public function executeCreate()
  {
    $this->files = new Files();
  	if(!$this->getRequestParameter('cid')){
  		die("choose category");
  	}
  	if($this->getRequestParameter('cid')){
  		$this->category = CategoryPeer::retrieveByPk($this->getRequestParameter('cid'));
  		if($this->category->getChild()=='D'){
		  	$this->setFlash('attrib', ' can\' add files here, <b>'.$category->getCategoryName().'</b> has sub directories');
		  	return $this->redirect($_SERVER['HTTP_REFERER']);
		  }
	    $this->files->setCategoryId($this->category->getId());
  	}
  	if($this->getRequestParameter('lastFile')){
  		$this->lastFile = base64_decode($this->getRequestParameter('lastFile'));
  	}
		$this->setTemplate('edit');
  }

 
  public function executeEdit()
  {
    $this->files = FilesPeer::retrieveByPk($this->getRequestParameter('id'));
    $this->forward404Unless($this->files);

  }

  public function executeUpdate()
  {
    if(!$this->getRequestParameter('id') || $this->getRequestParameter('id')=='')
    {
			if(!$this->getRequest()->getFileSize('file_name') && !$this->getRequestParameter('url_path'))
			{
			    $this->getRequest()->setError('file_name', 'Please upload files...');
					$this->forward('files', 'create', array('cid' => $this->getRequestParameter('category_id')));
			}
			$files = new Files();
    }
    else
    {
      $files = FilesPeer::retrieveByPk($this->getRequestParameter('id'));
      $this->forward404Unless($files);
    }
		$ipUpdated=false;
		if($files->getCategoryId() && $files->getCategoryId()!=$this->getRequestParameter('category_id'))
		{
	    $parentCategory = CategoryPeer::retrieveByPk($files->getCategoryId());
			myUser::updateFilesTotal($files->getCategoryId(),$parentCategory->getParents(),'remove');
		  if($parentCategory->getFiles() == 1){
		    $parentCategory->setChild('N');
		    $parentCategory->save();
			}
			$ipUpdated = true;
		}

	    $files->setId($this->getRequestParameter('id'));
	    $files->setCategoryId($this->getRequestParameter('category_id') ? $this->getRequestParameter('category_id') : null);
	    $files->setDescription($this->getRequestParameter('description') ? $this->getRequestParameter('description') : '');

  	$con = Propel::getConnection();
//	    $files->setSinger($this->getRequestParameter('singer') ? $this->getRequestParameter('singer') : '');
		if($this->getRequestParameter('singer')){
			$singers = explode(',',$this->getRequestParameter('singer'));
			$tagNames = $con->executeQuery("select group_concat(name) from artist where name IN ('".implode("','",$singers)."')", ResultSet::FETCHMODE_NUM);
			$tagNames->next();
			$files->setSinger(','.$tagNames->getString(1).',');
		}
		else{
			$files->setSinger('');
		}

	    $files->setTags($this->getRequestParameter('tags') ? $this->getRequestParameter('tags') : '');
	    $files->setExtension($this->getRequestParameter('extension'));
	    $files->setStatus($this->getRequestParameter('status')=='A' ? ($this->getRequestParameter('featured') ? 'F' : 'A') : 'B');
	    $files->setUrl('');
	    $files->save();

	    $parentCategory = CategoryPeer::retrieveByPk($this->getRequestParameter('category_id'));
	    $parentCategory->setChild('F');
	    $parentCategory->save();


		/*
		* SKYiTech :: define file types to allow & to identify
		*/
		$wallpaper = array('jpg','gif','png','jpeg');
		
		/*
		* SKYiTech :: making dataserver and thumbserver directory if not present
		*/
		$dataServer = 'sfd'.ceil($files->getId()/500);
		$thumbServer = 'sft'.ceil($files->getId()/500);
		if(!is_dir(sfConfig::get('sf_upload_dir').'/files/'.$dataServer))
			mkdir(sfConfig::get('sf_upload_dir').'/files/'.$dataServer);
		if(!is_dir(sfConfig::get('sf_upload_dir').'/thumb/'.$thumbServer))
			mkdir(sfConfig::get('sf_upload_dir').'/thumb/'.$thumbServer);
		if(!is_dir(sfConfig::get('sf_upload_dir').'/files/'.$dataServer.'/'.$files->getId()))
			mkdir(sfConfig::get('sf_upload_dir').'/files/'.$dataServer.'/'.$files->getId());


		/*
		* SKYiTech :: upload file from other server directly
		*/
		if($this->getRequestParameter('url_path') && $this->getRequestParameter('urlType') == 'CopyToServer')
		{
			########## url
			if($this->getRequestParameter('dirCatch')):
				$donwloadLink = trim(base64_decode($this->getRequestParameter('url_path')));
				$NewFileName = trim(str_replace(array(SETTING_REPLACE_SITENAME,'%20'),' ',base64_decode($this->getRequestParameter('url_path'))));
			else:
				$donwloadLink = trim($this->getRequestParameter('url_path'));
				$NewFileName = trim(str_replace(array(SETTING_REPLACE_SITENAME,'%20'),' ',$this->getRequestParameter('url_path')));
			endif;
			$urlInfo = pathinfo($donwloadLink);
			$fileNameInfo = pathinfo($NewFileName);
			
			$fileName = ($fileNameInfo['filename'] ? $fileNameInfo['filename'] : str_replace(array(SETTING_REPLACE_SITENAME,'%20'),' ',$urlInfo['filename']));
			//$fileExt = ($fileNameInfo['extension'] ? $fileNameInfo['extension'] : $urlInfo['extension']);
			$fileExt = explode('.',$NewFileName);
			$fileExt = end($fileExt);

			$type = 'o';
			if(in_array(strtolower($fileExt),$wallpaper))
				$type = 'w';

			if($this->getRequestParameter('rename_file_name')!=''){
				$saveAs = explode('.',$this->getRequestParameter('rename_file_name'));
				array_pop($saveAs);	// remove extension from array
				$saveAs = trim(str_replace(array(SETTING_REPLACE_SITENAME,'%20'),' ',implode('.',$saveAs)));	// join remaining array
			}
			else	
				$saveAs = $fileName;
		
			$saveAs = $saveAs.'.'.$fileExt;
			if(strstr($saveAs, sfConfig::get('app_filename2hide').'.'.substr(strrchr($saveAs,'.'),1)))
				$saveAs = str_replace(sfConfig::get('app_filename2hide').'.'.substr(strrchr($saveAs,'.'),1), sfConfig::get('app_filename2hide').'.'.substr(strrchr($saveAs,'.'),1), $saveAs);
			else
				$saveAs = str_replace('.'.substr(strrchr($saveAs,'.'),1), sfConfig::get('app_filename2hide').'.'.substr(strrchr($saveAs,'.'),1), $saveAs);
			$filePathToSave = sfConfig::get('sf_upload_dir').'/files/'.$dataServer.'/'.$files->getId().'/'.$saveAs;

			########## url //
			myUser::urlCopy($donwloadLink, $filePathToSave);

			/*
			* SKYiTech :: create thumb images if file type is wallpaper
			*/
			if($type=='w')
			{
				myUser::fileThumb($filePathToSave, $files->getId());
				copy($filePathToSave, sfConfig::get('sf_upload_dir').'/files/'.$dataServer.'/'.$files->getId().'/org_'.$files->getId().'_'.$saveAs);
				/*
				* SKYiTech :: Water mark original wallpaper, yet original size is only used in gif
				*/
				if(strtoupper($fileExt)!='GIF'){
			    $img = new sfImage(sfConfig::get('sf_upload_dir').'/files/'.$dataServer.'/'.$files->getId().'/'.$saveAs); // using MIME detection
			    $img->overlay(new sfImage(sfConfig::get('sf_upload_dir').'/logo.png'), 'bottom-right'); // or you can use coords array($x,$y)
			    $img->setQuality(100);
			    $img->save();
		  	}
		  }

			$files = FilesPeer::retrieveByPk($files->getId());
			$files->setFileName($saveAs);
			if($files->getTags()=='' && !$this->getRequestParameter('tags'))
				$files->setTags(myUser::getCategoryTag($parentCategory->getParentsarray()).$parentCategory->getCategoryName().', '.myUser::fileName($saveAs,false));
	    $files->setSize( filesize($filePathToSave) );
	    $files->setExtension(strtoupper($fileExt));
			$files->save();
		}
		elseif($this->getRequestParameter('url_path') && $this->getRequestParameter('urlType') != 'CopyToServer')
		{
			/*
			* SKYiTech :: direct download file from other server
			*							save 'url address' and 'type' => 'url'
			*/
			$NewFileName = trim(urldecode($this->getRequestParameter('rename_file_name')));
			$fileNameInfo = pathinfo($NewFileName);
			
			$fileName = $fileNameInfo['filename'];
			$fileExt = explode('.',$NewFileName);
			$fileExt = end($fileExt);

			if(strstr($NewFileName, sfConfig::get('app_filename2hide').'.'.substr(strrchr($NewFileName,'.'),1)))
				$NewFileName = str_replace(sfConfig::get('app_filename2hide').'.'.substr(strrchr($NewFileName,'.'),1), sfConfig::get('app_filename2hide').'.'.substr(strrchr($NewFileName,'.'),1), $NewFileName);
			else
				$NewFileName = str_replace('.'.substr(strrchr($NewFileName,'.'),1), sfConfig::get('app_filename2hide').'.'.substr(strrchr($NewFileName,'.'),1), $NewFileName);
			//$files = FilesPeer::retrieveByPk($files->getId());
			$files->setFileName($NewFileName);
			if($files->getTags()=='' && !$this->getRequestParameter('tags'))
				$files->setTags(myUser::getCategoryTag($parentCategory->getParentsarray()).$parentCategory->getCategoryName().', '.myUser::fileName($NewFileName,false));
	    $files->setSize( $this->getRequestParameter('size') );
	    $files->setExtension('URL');
	    $files->setUrl($this->getRequestParameter('url_path'));
			$files->save();
		}

		/*
		* SKYiTech :: upload file from local PC
		*/
		if($this->getRequest()->getFileSize('file_name') > 0)
		{
			$fileName = $this->getRequest()->getFileName('file_name');
			$fileExt = explode('.',$this->getRequest()->getFileName('file_name'));
			$fileExt = end($fileExt);
			$type = '';
			if(in_array(strtolower($fileExt),$wallpaper))
				$type = 'w';

			$saveAs = $this->getRequest()->getFileName('file_name');

			/*
			* SKYiTech :: create thumb images if file type is wallpaper
			*/
			$filePathToSave = sfConfig::get('sf_upload_dir').'/files/'.$dataServer.'/'.$files->getId().'/'.$saveAs;
			$this->getRequest()->moveFile('file_name', $filePathToSave);
			if($type=='w')
			{
				myUser::fileThumb($filePathToSave, $files->getId());
				copy($filePathToSave, sfConfig::get('sf_upload_dir').'/files/'.$dataServer.'/'.$files->getId().'/org_'.$files->getId().'_'.$saveAs);
				/*
				* SKYiTech :: Water mark original wallpaper, yet original size is only used in gif
				*/
				if(strtoupper($fileExt)!='GIF'){
			    $img = new sfImage($filePathToSave); // using MIME detection
			    $img->overlay(new sfImage(sfConfig::get('sf_upload_dir').'/logo.png'), 'bottom-right'); // or you can use coords array($x,$y)
			    $img->setQuality(95);
			    $img->save();
		  	}
		  	//echo memory_get_usage(true); exit;
			}

			$files = FilesPeer::retrieveByPk($files->getId());
			$files->setFileName($saveAs);
//			if($files->getTags()=='' && !$this->getRequestParameter('tags'))
//				$files->setTags($parentCategory->getCategoryName().', '.myUser::fileName($saveAs,false));
		  $files->setSize($this->getRequest()->getFileSize('file_name'));
		  $files->setExtension(strtoupper($fileExt));
			$files->save();
		}

		if($files->getExtension()=='MP3' && $this->getRequestParameter('url_path2')!="")
		{
			$saveAs = myClass::MultiFileName($files->getFileName(),'64');
			$fPath = sfConfig::get('sf_upload_dir').'/files/'.$dataServer.'/'.$files->getId().'/'.$saveAs;
			myUser::urlCopy($this->getRequestParameter('url_path2'), $fPath);
			if($this->getRequestParameter('mp3tags')=='true')
				readfile(sfConfig::get('app_webpath').'/SkyWriteMp3.php?Filename='.urlencode($dataServer.'/'.$files->getId().'/'.$saveAs).'&cid='.$files->getCategoryId().'&catName='.base64_encode(CategoryPeer::getCategoryName($files->getCategoryId())).'&lastFile='.base64_encode($files->getFileName()).'&multi=true' );
		}
		elseif($files->getExtension()=='MP3' && $this->getRequest()->getFileSize('file_name2') > 0)
		{
			$fileExt = explode('.',$this->getRequest()->getFileName('file_name2'));
			$fileExt = end($fileExt);
			if(!in_array(strtolower($fileExt),array('mp3'))){
		    $this->getRequest()->setError('file_name2', 'Please upload mp3 files only');
				$this->forward('files','edit','?id='.$files->getId());
			}

			$saveAs = myClass::MultiFileName($files->getFileName(),'64');
			$fPath = sfConfig::get('sf_upload_dir').'/files/'.$dataServer.'/'.$files->getId().'/'.$saveAs;
			$this->getRequest()->moveFile('file_name2', $fPath);
			if($this->getRequestParameter('mp3tags')=='true')
				readfile(sfConfig::get('app_webpath').'/SkyWriteMp3.php?Filename='.urlencode($dataServer.'/'.$files->getId().'/'.$saveAs).'&cid='.$files->getCategoryId().'&catName='.base64_encode(CategoryPeer::getCategoryName($files->getCategoryId())).'&lastFile='.base64_encode($files->getFileName()).'&multi=true' );
		}

		if($files->getExtension()=='MP3' && $this->getRequestParameter('url_path3')!="")
		{
			$saveAs = myClass::MultiFileName($files->getFileName(),'192');
			$fPath = sfConfig::get('sf_upload_dir').'/files/'.$dataServer.'/'.$files->getId().'/'.$saveAs;
			myUser::urlCopy($this->getRequestParameter('url_path3'), $fPath);
			if($this->getRequestParameter('mp3tags')=='true')
				readfile(sfConfig::get('app_webpath').'/SkyWriteMp3.php?Filename='.urlencode($dataServer.'/'.$files->getId().'/'.$saveAs).'&cid='.$files->getCategoryId().'&catName='.base64_encode(CategoryPeer::getCategoryName($files->getCategoryId())).'&lastFile='.base64_encode($files->getFileName()).'&multi=true' );
		}
		elseif($files->getExtension()=='MP3' && $this->getRequest()->getFileSize('file_name3') > 0)
		{
			$fileExt = explode('.',$this->getRequest()->getFileName('file_name3'));
			$fileExt = end($fileExt);
			if(!in_array(strtolower($fileExt),array('mp3'))){
		    $this->getRequest()->setError('file_name3', 'Please upload mp3 files only');
				$this->forward('files','edit','?id='.$files->getId());
			}

			$saveAs = myClass::MultiFileName($files->getFileName(),'192');
			$fPath = sfConfig::get('sf_upload_dir').'/files/'.$dataServer.'/'.$files->getId().'/'.$saveAs;
			$this->getRequest()->moveFile('file_name3', $fPath);
			if($this->getRequestParameter('mp3tags')=='true')
				readfile(sfConfig::get('app_webpath').'/SkyWriteMp3.php?Filename='.urlencode($dataServer.'/'.$files->getId().'/'.$saveAs).'&cid='.$files->getCategoryId().'&catName='.base64_encode(CategoryPeer::getCategoryName($files->getCategoryId())).'&lastFile='.base64_encode($files->getFileName()).'&multi=true' );
		}

		if($files->getExtension()=='MP3' && $this->getRequestParameter('url_path4')!="")
		{
			$saveAs = myClass::MultiFileName($files->getFileName(),'320');
			$fPath = sfConfig::get('sf_upload_dir').'/files/'.$dataServer.'/'.$files->getId().'/'.$saveAs;
			myUser::urlCopy($this->getRequestParameter('url_path4'), $fPath);
			if($this->getRequestParameter('mp3tags')=='true')
				readfile(sfConfig::get('app_webpath').'/SkyWriteMp3.php?Filename='.urlencode($dataServer.'/'.$files->getId().'/'.$saveAs).'&cid='.$files->getCategoryId().'&catName='.base64_encode(CategoryPeer::getCategoryName($files->getCategoryId())).'&lastFile='.base64_encode($files->getFileName()).'&multi=true' );
		}
		elseif($files->getExtension()=='MP3' && $this->getRequest()->getFileSize('file_name4') > 0)
		{
			$fileExt = explode('.',$this->getRequest()->getFileName('file_name4'));
			$fileExt = end($fileExt);
			if(!in_array(strtolower($fileExt),array('mp3'))){
		    $this->getRequest()->setError('file_name4', 'Please upload mp3 files only');
				$this->forward('files','edit','?id='.$files->getId());
			}

			$saveAs = myClass::MultiFileName($files->getFileName(),'320');
			$fPath = sfConfig::get('sf_upload_dir').'/files/'.$dataServer.'/'.$files->getId().'/'.$saveAs;
			$this->getRequest()->moveFile('file_name4', $fPath);
			if($this->getRequestParameter('mp3tags')=='true')
				readfile(sfConfig::get('app_webpath').'/SkyWriteMp3.php?Filename='.urlencode($dataServer.'/'.$files->getId().'/'.$saveAs).'&cid='.$files->getCategoryId().'&catName='.base64_encode(CategoryPeer::getCategoryName($files->getCategoryId())).'&lastFile='.base64_encode($files->getFileName()).'&multi=true' );
		}

		/*
		* SKYiTech :: rename file name
									do not rename if it is url upload
		*/
		if($this->getRequestParameter('rename_file_name') && !$this->getRequestParameter('url_path'))
		{
			$newFileName = $this->getRequestParameter('rename_file_name');

			/*
			* SKYiTech :: replace sfConfing::get('app_filename2hide') -(sitename.ext) or add it
			*/
			if(strstr($newFileName, sfConfig::get('app_filename2hide').'.'.substr(strrchr($newFileName,'.'),1)))
				$newFileName = str_replace(sfConfig::get('app_filename2hide').'.'.substr(strrchr($newFileName,'.'),1), sfConfig::get('app_filename2hide').'.'.substr(strrchr($newFileName,'.'),1), $newFileName);
			else
				$newFileName = str_replace('.'.substr(strrchr($newFileName,'.'),1), sfConfig::get('app_filename2hide').'.'.substr(strrchr($newFileName,'.'),1), $newFileName);
			//echo $newFileName; exit;
			if($newFileName != $files->getFileName())
			{
				rename(sfConfig::get('sf_upload_dir').'/files/'.$dataServer.'/'.$files->getId().'/'.$files->getFileName(), sfConfig::get('sf_upload_dir').'/files/'.$dataServer.'/'.$files->getId().'/'.$newFileName);
				if(is_file(sfConfig::get('sf_upload_dir').'/files/'.$dataServer.'/'.$files->getId().'/org_'.$files->getId().'_'.$files->getFileName()))
					rename(sfConfig::get('sf_upload_dir').'/files/'.$dataServer.'/'.$files->getId().'/org_'.$files->getId().'_'.$files->getFileName(), sfConfig::get('sf_upload_dir').'/files/'.$dataServer.'/'.$files->getId().'/org_'.$files->getId().'_'.$newFileName);

				if(is_file(sfConfig::get('sf_upload_dir').'/files/'.$dataServer.'/'.$files->getId().'/'.myClass::MultiFileName($files->getFileName(),'64') ))
					rename(sfConfig::get('sf_upload_dir').'/files/'.$dataServer.'/'.$files->getId().'/'.myClass::MultiFileName($files->getFileName(),'64'), sfConfig::get('sf_upload_dir').'/files/'.$dataServer.'/'.$files->getId().'/'.myClass::MultiFileName($newFileName,'64'));
				if(is_file(sfConfig::get('sf_upload_dir').'/files/'.$dataServer.'/'.$files->getId().'/'.myClass::MultiFileName($files->getFileName(),'192')))
					rename(sfConfig::get('sf_upload_dir').'/files/'.$dataServer.'/'.$files->getId().'/'.myClass::MultiFileName($files->getFileName(),'192'), sfConfig::get('sf_upload_dir').'/files/'.$dataServer.'/'.$files->getId().'/'.myClass::MultiFileName($newFileName,'192'));
				if(is_file(sfConfig::get('sf_upload_dir').'/files/'.$dataServer.'/'.$files->getId().'/'.myClass::MultiFileName($files->getFileName(),'320')))
					rename(sfConfig::get('sf_upload_dir').'/files/'.$dataServer.'/'.$files->getId().'/'.myClass::MultiFileName($files->getFileName(),'320'), sfConfig::get('sf_upload_dir').'/files/'.$dataServer.'/'.$files->getId().'/'.myClass::MultiFileName($newFileName,'320'));

				$files->setFileName($newFileName);
				if($files->getTags()=='' && !$this->getRequestParameter('tags'))
					$files->setTags(myUser::getCategoryTag($parentCategory->getParentsarray()).$parentCategory->getCategoryName().', '.myUser::fileName($newFileName,false));
				$files->save();
			}
		}


		/*
		* SKYiTech :: updating total files count for all parent categories
		*/
		if($ipUpdated==true || !$this->getRequestParameter('id') || $this->getRequestParameter('id')=='')
		{
			myUser::updateFilesTotal($files->getCategoryId(),$parentCategory->getParents(),'add');
		}

		
		$videoExt = array('AVI','3GP','MP4','FLV');
		if(in_array($files->getExtension(),$videoExt))
		{
			myUser::setVideoFrame($files->getId(),10);
		}

		if($files->getExtension()=='NTH')
		{
			myUser::setNthPreview($files->getId());
		}
		if($files->getExtension()=='THM')
		{
			myUser::setThmPreview($files->getId());
		}
		if($files->getExtension()=='JAR')
		{
			myUser::setJarPreview($files->getId());
		}
		if($files->getExtension()=='APK')
		{
			myUser::setApkPreview($files->getId());
		}


		/*
		* SKYiTech :: create thumb if upload by dirCatch and we found thumb on dir like "thumb-filename.jpg" or "thumb-filename.ext.jpg"
		*/
		if($this->getRequestParameter('dirCatch'))
		{
			if($files->getExtension()!='JPG' && $files->getExtension()!='JPEG' && $files->getExtension()!='PNG'):
				$fileThumb = base64_decode($this->getRequestParameter('url_path'));
				$fileThumb = str_replace('%20',' ',$fileThumb);
				$fileThumb = str_replace('http://'.$_SERVER['HTTP_HOST'],$_SERVER['DOCUMENT_ROOT'],$fileThumb);
				$fileInfo = pathinfo($fileThumb);
				$bname = $fileInfo['basename']; // filname.ext
				$fname = $fileInfo['filename']; // filename
				$fileHasThumb = false;

				if(is_file(str_replace($bname,'thumb-'.$fname.'.jpg',$fileThumb)))
					$fileHasThumb = str_replace($bname,'thumb-'.$fname.'.jpg',$fileThumb);
				elseif(is_file(str_replace($fname,'thumb-'.$fname.'.jpg',$fileThumb)))
					$fileHasThumb = str_replace($fname,'thumb-'.$fname.'.jpg',$fileThumb);
				elseif(is_file(str_replace($bname,'thumb-'.$fname.'.gif',$fileThumb)))
					$fileHasThumb = str_replace($bname,'thumb-'.$fname.'.gif',$fileThumb);
				elseif(is_file(str_replace($fname,'thumb-'.$fname.'.gif',$fileThumb)))
					$fileHasThumb = str_replace($fname,'thumb-'.$fname.'.gif',$fileThumb);
				elseif(is_file(str_replace($bname,'thumb-'.$fname.'.png',$fileThumb)))
					$fileHasThumb = str_replace($bname,'thumb-'.$fname.'.png',$fileThumb);
				elseif(is_file(str_replace($fname,'thumb-'.$fname.'.png',$fileThumb)))
					$fileHasThumb = str_replace($fname,'thumb-'.$fname.'.png',$fileThumb);

				if($fileHasThumb!=false)
				{
					myUser::fileThumb($fileHasThumb, $files->getId());
					copy($fileHasThumb, sfConfig::get('sf_upload_dir').'/files/'.$dataServer.'/'.$files->getId().'/thumb-'.$files->getId().'.jpg');
				}
			endif;
		}
		/*
		* SKYiTech :: create thumb images if file type is not wallpaper and thumb image uploaded
		*/
		if($this->getRequest()->getFileSize('thumb_name') > 0 && ($files->getExtension()!='JPG' && $files->getExtension()!='JPEG' && $files->getExtension()!='PNG'))
		{
			$thumbName = $this->getRequest()->getFileName('thumb_name');
			$thumbExt = explode('.',$this->getRequest()->getFileName('thumb_name'));
			if(in_array(strtolower(end($thumbExt)),$wallpaper))
			{
				myUser::fileThumb($this->getRequest()->getFilePath('thumb_name'), $files->getId());
			}
			$this->getRequest()->moveFile('thumb_name', sfConfig::get('sf_upload_dir').'/files/'.$dataServer.'/'.$files->getId().'/thumb-'.$files->getId().'.jpg');
		}

		if($this->getRequestParameter('thumb_url_path')){
			$thumbSave = sfConfig::get('sf_upload_dir').'/files/'.$dataServer.'/'.$files->getId().'/thumb-'.$files->getId().'.jpg';
			myUser::urlCopy($this->getRequestParameter('thumb_url_path'), $thumbSave);
			myUser::fileThumb($thumbSave, $files->getId());
		}

		/* sKYiTech:: batch upload */
		if($this->getRequestParameter('dirCatch')){
			if($this->getRequestParameter('removeFile')=='yes'):
				$fileRemove = base64_decode($this->getRequestParameter('url_path'));
				$fileRemove = str_replace('%20',' ',$fileRemove);
				$fileRemove = str_replace('http://'.$_SERVER['HTTP_HOST'],$_SERVER['DOCUMENT_ROOT'],$fileRemove);
				if(is_file($fileRemove))
					unlink($fileRemove);
			endif;

			if($files->getExtension()=='MP3' && $this->getRequestParameter('mp3tags')=='yes')
				$this->redirect(sfConfig::get('app_webpath').'/SkyWriteMp3.php?Filename='.htmlentities($dataServer.'/'.$files->getId().'/'.$files->getFileName()).'&cid='.$files->getCategoryId().'&catName='.base64_encode(CategoryPeer::getCategoryName($files->getCategoryId())).'&lastFile='.base64_encode($files->getFileName()).'&dirCatch='.$this->getRequestParameter('url_path') );
			else
				echo $this->getRequestParameter('url_path');
			exit;
		}	

		myUser::clearCategoryCache($files->getCategoryId());
		myUser::clearFileCache($files->getId());

		/* sKYiTech:: single upload */
		if($files->getExtension()=='MP3' && $this->getRequestParameter('mp3tags')=='true' && ($this->getRequestParameter('url_path') || $this->getRequest()->getFileSize('file_name') > 0) ){
			header('Location: /mm-admin4589/SkyWriteMp3.php?Filename='.$dataServer.'/'.$files->getId().'/'.$files->getFileName().'&cid='.$files->getCategoryId().'&singer='.base64_encode(str_replace(',',' ,',$this->getRequestParameter('singer'))).'&catName='.base64_encode(CategoryPeer::getCategoryName($files->getCategoryId())).'&lastFile='.base64_encode($files->getFileName()) );
			exit;
		}
		else
	    return $this->redirect('files/create?cid='.$files->getCategoryId().'&lastFile='.base64_encode($files->getFileName()));
  }

	public function executeMoveid(){
    	$targetCategory = CategoryPeer::retrieveByPk($this->getRequestParameter('movetoid'));
		if($targetCategory){
			if($targetCategory->getChild()=='D' || $targetCategory->getChild()=='U')
				die('Target Directory contain sub-category OR it is URL category');
	    $files = FilesPeer::retrieveByPk($this->getRequestParameter('id'));
	
	    $parentCategory = CategoryPeer::retrieveByPk($files->getCategoryId());
			myUser::updateFilesTotal($files->getCategoryId(),$parentCategory->getParents(),'remove');
		  if($parentCategory->getFiles() == 1){
		    $parentCategory->setChild('N');
		    $parentCategory->save();
			}
	
	    $files->setCategoryId($this->getRequestParameter('movetoid'));
	    $files->save();
			if($targetCategory->getChild()=='N'){
				$targetCategory->setChild('F');
				$targetCategory->save();
			}
			myUser::updateFilesTotal($files->getCategoryId(),$targetCategory->getParents(),'add');
			header('Refresh: 0; '.$_SERVER['HTTP_REFERER']);
			die('Moved');
		}
		else{
			die('Target Category not Exist');
		}
	}

  public function executeActivation()
  {
  	if($this->getRequestParameter('status') == 'B' || $this->getRequestParameter('status') == 'F')
  		$status = 'A';
  	else
  		$status = 'B';
    $this->files = FilesPeer::retrieveByPk($this->getRequestParameter('id'));
    $this->forward404Unless($this->files);
    $this->files->setStatus($status);
    $this->files->save();
  	if($this->getRequestParameter('status') == 'B' || $this->getRequestParameter('status') == 'F')
  		echo '<a onclick="new Ajax.Updater(\'active_'.$this->getRequestParameter('id').'\', \'/mm-admin4589/files/activation/id/'.$this->getRequestParameter('id').'/status/A\', {asynchronous:true, evalScripts:false});; return false;" href="#">Active</a>';
  	else
  		echo '<a onclick="new Ajax.Updater(\'active_'.$this->getRequestParameter('id').'\', \'/mm-admin4589/files/activation/id/'.$this->getRequestParameter('id').'/status/B\', {asynchronous:true, evalScripts:false});; return false;" href="#"><span class="red">Block</span></a>';
		return sfView::NONE;
  }
 
}