<?php
// auto-generated by sfPropelCrud
// date: 2009/06/07 18:21:54
?>
<?php

/**
 * files actions.
 *
 * @package    
 * @subpackage files
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 3335 2007-01-23 16:19:56Z fabien $
 */
class filesActions extends sfActions
{
  public function executeIndex()
  {
    return $this->forward('files', 'list');
  }

  public function executeThumb()
  {
  	if($this->getUser()->getAttribute('thumbOnOff') == 'show')
			$this->getUser()->setAttribute('thumbOnOff', 'hide');
		else
			$this->getUser()->setAttribute('thumbOnOff', 'show');
		
    return $this->redirect($_SERVER['HTTP_REFERER']);
  }


  public function executeList()
  {
  	if($this->getRequestParameter('parent')){
			if(!is_numeric($this->getRequestParameter('parent')))
				$this->forward404();
			sfLoader::loadHelpers(array('Url', 'Tag'));
			$this->parent = $this->getRequestParameter('parent');

  		$this->category = skyGetRecordById('category',$this->getRequestParameter('parent'));
  		$this->forward404Unless($this->category);
  		$this->catName = $this->category->category_name;
			$this->catTitle = $this->category->title;

  		/*
  		* SKYiTech :: make category title and category path from parentsArray
  		*/
			$parentsArray = explode('||',$this->category->parentsarray);
			array_shift($parentsArray);
			$this->categoryPath = '';
			for($i = 0; $i<count($parentsArray); $i+=2){
				$this->categoryPath .= link_to($parentsArray[$i+1],'/category/list?parent='.$parentsArray[$i].'&fname='.myUser::slugify($parentsArray[$i+1])) . ' &raquo; ';
				$this->catTitle .= $parentsArray[$i+1].' > ';
			}
	    $this->getResponse()->setTitle($this->catName .' :: '.substr($this->catTitle,0,-2) . ' ' . SETTING_TITLE.' - '.sfConfig::get('app_sitename'));
//	    $this->getResponse()->setTitle('Free Download '.$this->category->category_name.', '.myUser::parentsName($this->category->parentsarray,', ').' - '.sfConfig::get('app_sitename'));
//	    $this->getResponse()->addMeta('description', 
//		    str_replace(array('.','-','(',')',"'",),'', 
//		  	  'Free Download '.$this->category->category_name.' '.array_pop($parentsArray).' '.myUser::parentsName($this->category->parentsarray,' ',false,-2).' from '
//		    ).sfConfig::get('app_sitename')
//	    );
//	    $this->getResponse()->addMeta('keywords', str_replace(array('.','-','(',')',"'",),'', 'free, download, '.$this->category->category_name.', '.myUser::parentsName($this->category->parentsarray,', ',true).' from, mirchifuninfo'));

	    unset($this->catTitle);

			$sql = 'select * from files where status!="B" and category_id='.$this->getRequestParameter('parent');
			$this->totalRecords = skyMysqlGetCount($sql);

			if($this->getRequestParameter('sort')=='download')
				$sql .= ' order by download desc';
			elseif($this->getRequestParameter('sort')=='new2old')
				$sql .= ' order by ord asc,id desc';
			elseif($this->getRequestParameter('sort')=='a2z')
				$sql .= ' order by file_name asc';
			elseif($this->getRequestParameter('sort')=='z2a')
				$sql .= ' order by file_name desc';

			$this->page = $this->getRequestParameter('page', 1);
			$startLimit = skyGetStartLimit($this->totalRecords, $this->page, SETTING_FILES_PER_PAGE);
			$sql .= ' limit '.$startLimit.','.SETTING_FILES_PER_PAGE;
	    $filess = skyMysqlQuery($sql);

			$this->filess = array();
			while ($value = mysql_fetch_object($filess)):
				$this->filess[]=$value;
			endwhile;

	  }
		else{
	    return $this->redirect('');
	  }
  }

  public function executeSinger()
  {
  	if($this->getRequestParameter('singer')){

			$sql = 'select * from files where status!="B" and singer like "%'.$this->getRequestParameter('singer').'%"';
			$this->totalRecords = skyMysqlGetCount($sql);

			if($this->getRequestParameter('sort')=='download')
				$sql .= ' order by download desc';
			elseif($this->getRequestParameter('sort')=='new2old')
				$sql .= ' order by created_at desc';
			elseif($this->getRequestParameter('sort')=='a2z')
				$sql .= ' order by file_name asc';
			elseif($this->getRequestParameter('sort')=='z2a')
				$sql .= ' order by file_name desc';

			$this->page = $this->getRequestParameter('page', 1);
			$startLimit = skyGetStartLimit($this->totalRecords, $this->page, SETTING_FILES_PER_PAGE);
			$sql .= ' limit '.$startLimit.','.SETTING_FILES_PER_PAGE;
	    $filess = skyMysqlQuery($sql);

	    $this->getResponse()->setTitle('Free Download '.$this->getRequestParameter('singer').' Mp3 Songs - '.sfConfig::get('app_sitename'));
	    $this->getResponse()->addMeta('description', str_replace(array('.','-','(',')',"'",),'', 'Free Download '.$this->getRequestParameter('singer').' Mp3 Songs from ').sfConfig::get('app_sitename'));
	    $this->getResponse()->addMeta('keywords', str_replace(array('.','-','(',')',"'",),'', 'free, download, '.$this->getRequestParameter('singer').', mp3 songs, from, mirchifuninfo'));

			$this->filess = array();
			while ($value = mysql_fetch_object($filess)):
				$this->filess[]=$value;
			endwhile;
  	}
  }
  
  public function executeFeatured()
  {
			//$c->setLimit(3);
			$sql = 'select id,file_name,category_id,size,download,description,extension,created_at from files where status="F"';
			$this->totalRecords = skyMysqlGetCount($sql);
			$sql .= ' order by created_at desc';

			$this->page = $this->getRequestParameter('page', 1);
			$startLimit = skyGetStartLimit($this->totalRecords, $this->page, SETTING_FILES_PER_PAGE);
			$sql .= ' limit '.$startLimit.','.SETTING_FILES_PER_PAGE;
	    $filess = skyMysqlQuery($sql);

			$this->filess = array();
			while ($value = mysql_fetch_object($filess)):
				$this->filess[]=$value;
			endwhile;
  }

  public function executeLastadded()
  {
  	
  	if(SETTING_LATEST_FILES_LIST=='ON'){
			//$c->setLimit(3);
			$sql = 'select id,file_name,category_id,size,download,description,extension,created_at from files where status!="B"';
			$this->totalRecords = skyMysqlGetCount($sql);
			$sql .= ' order by created_at desc';

			$this->page = $this->getRequestParameter('page', 1);
			$startLimit = skyGetStartLimit($this->totalRecords, $this->page, SETTING_FILES_PER_PAGE);
			$sql .= ' limit '.$startLimit.','.SETTING_FILES_PER_PAGE;
	    $filess = skyMysqlQuery($sql);

			$this->filess = array();
			while ($value = mysql_fetch_object($filess)):
				$this->filess[]=$value;
			endwhile;
		}
		else
			$this->redirect('/');
  }

  public function executeSearch()
  {
  	checkDB();
  	if($this->getRequestParameter('commit')=='Singer'){
  		if($this->getRequestParameter('find'))
	  		$this->redirect('artist/list?type=find&find='.$this->getRequestParameter('find'));
	  	else
	  		$this->redirect('artist/list');
  	}
  	if($this->getRequestParameter('commit')=='Album'){
  		$this->redirect('category/search?type=find&find='.$this->getRequestParameter('find'));
  	}
  	if($this->getRequestParameter('find')){

//  		$sql = 'select * from files where status!="B" and file_name like "%'.str_replace(' ','%',mysql_real_escape_string(($this->getRequestParameter('find')))).'%"';
  		$sql = 'select files.*,category.category_name,category.parentsarray from files 
  		LEFT JOIN category ON category.id=files.category_id 
  		where files.status!="B" and 
  		(tags like "%'.str_replace(' ','%',mysql_real_escape_string(($this->getRequestParameter('find')))).'%" OR singer like "%'.str_replace(' ','%',mysql_real_escape_string(($this->getRequestParameter('find')))).'%") ';
//  		echo $sql = 'select id,file_name,category_id,size,download,description,extension,created_at from files where status!="B" and MATCH(tags) AGAINST ("*'.str_replace(' ','*',str_replace(' ','*',mysql_real_escape_string(($this->getRequestParameter('find'))))).'*" IN BOOLEAN MODE)';
  		if($this->getRequestParameter('ext') && strtoupper($this->getRequestParameter('ext'))!='ALL')
  			$sql .= ' and extension="'. strtoupper(mysql_real_escape_string($this->getRequestParameter('ext'))).'"';
  		if($this->getRequestParameter('cat') && strtoupper($this->getRequestParameter('cat'))!=0)
  			$sql .= ' and category_id IN (select id from category where parents like "|' .(mysql_real_escape_string($this->getRequestParameter('cat'))). '|%")';
			$this->totalRecords = skyMysqlGetCount($sql,true);

			if($this->getRequestParameter('sort')=='download')
				$sql .= ' order by download desc';
			elseif($this->getRequestParameter('sort')=='a2z')
				$sql .= ' order by file_name asc';
			elseif($this->getRequestParameter('sort')=='z2a')
				$sql .= ' order by file_name desc';
			else
				$sql .= ' order by created_at desc';

			$this->page = $this->getRequestParameter('page', 1);
			$startLimit = skyGetStartLimit($this->totalRecords, $this->page, SETTING_FILES_PER_PAGE);
			$sql .= ' limit '.$startLimit.','.SETTING_FILES_PER_PAGE;
	    $this->filess = skyMysqlQuery($sql);

  		$this->catName = 'Your Search Result'; // .'"'.strip_tags($this->getRequestParameter('find')).'"';
	    $this->getResponse()->setTitle($this->getRequestParameter('find').' - Your Search Result - '.sfConfig::get('app_sitename'));
	  }
		else{
	    return $this->redirect('');
	  }
  }

  public function executeTop()
  {
    if($this->getRequestParameter('type')=='today')
    	$titleText = 'Today\'s';
    elseif($this->getRequestParameter('type')=='yesterday')
    	$titleText = 'Yesterday\'s';
    elseif($this->getRequestParameter('type')=='week')
    	$titleText = 'Last Week\'s';
    elseif($this->getRequestParameter('type')=='month')
    	$titleText = 'This Month\'s';
    elseif($this->getRequestParameter('type')=='all')
    	$titleText = 'All Time\'s';
    $this->getResponse()->setTitle($titleText.' Top Downloaded Files'.' :: '.sfConfig::get('app_sitename'));

		if($this->getRequestParameter('type')=='yesterday')
			$filess = DownloadHistoryPeer::getMostPopuler(1);
		elseif($this->getRequestParameter('type')=='week')
			$filess = DownloadHistoryPeer::getMostPopuler(7);
		elseif($this->getRequestParameter('type')=='month')
			$filess = DownloadHistoryPeer::getMostPopuler(date('j')-1);
		elseif($this->getRequestParameter('type')=='today')
			$filess = DownloadHistoryPeer::getMostPopuler(0);
		elseif($this->getRequestParameter('type')=='all')
			$filess = DownloadHistoryPeer::getMostPopuler(100);

		$this->filess = array();
		while ($value = mysql_fetch_object($filess)):
			$this->filess[]=$value;
		endwhile;
  }

  public function executeShow()
  {
		sfLoader::loadHelpers(array('Url', 'Tag'));
		if(!is_numeric($this->getRequestParameter('id')))
			$this->forward404();
    $this->files = skyGetRecordById('files',$this->getRequestParameter('id'));
    $this->forward404Unless($this->files);

    /* SKYitech:: redirect to home if file not found */
    if(!$this->files || $this->files->status=='B')
    	$this->redirect('default','');
    	
 		$parent = skyGetRecordById('category',$this->files->category_id);
    $this->catName = $parent->category_name;
    $this->list_ord = $parent->list_ord;

		/*
		* SKYiTech :: make category title and category path from parentsArray
		*/
		$parentsArray = explode('||',$parent->parentsarray);
		array_shift($parentsArray);
		$this->categoryPath = '';
		for($i = 0; $i<count($parentsArray); $i+=2){
			$this->categoryPath .= link_to($parentsArray[$i+1],'/category/list?parent='.$parentsArray[$i].'&fname='.myUser::slugify($parentsArray[$i+1])) . ' &raquo; ';
			$this->catTitle .= $parentsArray[$i+1].' > ';
		}
    $this->getResponse()->setTitle(str_replace('_',' ',myUser::fileName($this->files->file_name,false)).' - '.$this->catName.' Free Download - '.sfConfig::get('app_sitename'));

//    $this->getResponse()->setTitle('Free Download '. str_replace('_',' ',myUser::fileName($this->files->file_name,false)).', '.$parentsArray[1].' - '.sfConfig::get('app_sitename'));
//    $this->getResponse()->addMeta('description', str_replace(array('.','-','(',')',"'",),'', 
//    					'Free Download '.str_replace('_',' ',myUser::fileName($this->files->file_name,false)).' '.$parent->category_name.' '.array_pop($parentsArray).' '.myUser::parentsName($parent->parentsarray,' ',false,-2).' from '
//    					).sfConfig::get('app_sitename')
//    			);
//    $this->getResponse()->addMeta('keywords', str_replace(array('.','-','(',')',"'",),'', 'free, download, '.str_replace('_',' ',myUser::fileName($this->files->file_name,false)).', '.$parent->category_name.', '.myUser::parentsName($parent->parentsarray,', ',true).' from, mirchifuninfo'));
    unset($this->catTitle);

/*
		$sql = "select id,file_name,category_id,extension,size,download from files where category_id=".$this->files->category_id.' order by rand() limit 3';
		$randomfiless = skyMysqlQuery($sql);
		$this->randomfiles = array();
		while ($value = mysql_fetch_object($randomfiless)):
			$this->randomfiles[]=$value;
		endwhile;
*/
  }

	public function executeLimitover()
	{
	}

	public function executePermission()
	{
	}

	public function executeDisclaimer()
	{
	}

	public function executeAdvancesearch()
	{
	}
}
