<?php

/**
 * category actions.
 *
 * @package    
 * @subpackage category
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 2692 2006-11-15 21:03:55Z fabien $
 */
class categoryActions extends sfActions
{
  /**
   * Executes index action
   *
   */
  public function executeIndex()
  {
    $this->forward('default', 'module');
  }

  public function executeSearch()
  {
  	if($this->getRequestParameter('find')){

			if($this->getRequestParameter('type')=='chr')
	  		$sql = "select * from category where child='F' and category_name like '".($this->getRequestParameter('find'))."%'";
	  	else
	  		$sql = "select * from category where child='F' and category_name like '%".($this->getRequestParameter('find'))."%'";
			$this->totalRecords = skyMysqlGetCount($sql,true);

			if($this->getRequestParameter('sort')=='a2z')
				$sql .= ' order by category_name asc';
			else
				$sql .= ' order by id desc';

			$this->page = $this->getRequestParameter('page', 1);
			$startLimit = skyGetStartLimit($this->totalRecords, $this->page, SETTING_CATEGORY_PER_PAGE);
			$sql .= ' limit '.$startLimit.','.SETTING_CATEGORY_PER_PAGE;
	    $this->categories = skyMysqlQuery($sql);

  		$this->catName = 'Your Search Result'; // for "'.strip_tags($this->getRequestParameter('find')).'"';
	    $this->getResponse()->setTitle($this->getRequestParameter('find').' - Your Search Result :: '.sfConfig::get('app_sitename'));
  	}
  }

  public function executeList()
  {
		sfLoader::loadHelpers(array('Url', 'Tag'));
		$this->parent = '';
		$this->parentName = '';
		$this->categoryPath = '';
  	if($this->getRequestParameter('parent')){
			if(!is_numeric($this->getRequestParameter('parent')))
				$this->forward404();
  		$this->parent = $this->getRequestParameter('parent');
  		$parent = skyGetRecordById('category',$this->getRequestParameter('parent'));
  		$this->forward404Unless($parent);
  		$this->catName = $parent->category_name;
			//$this->catTitle = $parent->title;
  		$this->catDescription = $parent->description;

  		/*
  		* SKYiTech :: make category title and category path from parentsArray
  		*/
			$parentsArray = explode('||',$parent->parentsarray);
			array_shift($parentsArray);
			for($i = 0; $i<count($parentsArray); $i+=2){
				$this->categoryPath .= link_to($parentsArray[$i+1],'@categoryList?parent='.$parentsArray[$i].'&fname='.myUser::slugify($parentsArray[$i+1])) . ' &raquo; ';
				$this->catTitle .= $parentsArray[$i+1].' > ';
			}
	    $this->getResponse()->setTitle($this->catName .' :: '.$parent->title.' '.substr($this->catTitle,0,-2) . ' ' . SETTING_TITLE.' - '.sfConfig::get('app_sitename'));
//	    $this->getResponse()->setTitle('Free Download '.$parent->category_name.' '.myUser::parentsName($parent->parentsarray,', ',false).' - '.sfConfig::get('app_sitename'));
//	    $this->getResponse()->addMeta('description', str_replace(array('.','-','(',')',"'",),'', 'Free Download '.$parent->category_name.' '.myUser::parentsName($parent->parentsarray,' ',false).' from ').sfConfig::get('app_sitename'));
//	    $this->getResponse()->addMeta('keywords', str_replace(array('.','-','(',')',"'",),'', 'free, download, '.$parent->category_name.', '.myUser::parentsName($parent->parentsarray,', ',true).' from, ').sfConfig::get('app_sitename'));
	    unset($this->catTitle);

			$sql = 'select * from category where parents like "%|'.$this->getRequestParameter('parent').'|" and status="A"';
			$this->totalRecords = skyMysqlGetCount($sql);

			if($this->getRequestParameter('sort')=='default'){
				$sql .= ' order by ord asc, id desc';
		 	}
			elseif($this->getRequestParameter('sort')=='a2z')
				$sql .= ' order by category_name';
			else
				$sql .= ' order by id desc';

			$this->page = $this->getRequestParameter('page', 1);
			$startLimit = skyGetStartLimit($this->totalRecords, $this->page, SETTING_CATEGORY_PER_PAGE);
			$sql .= ' limit '.$startLimit.','.SETTING_CATEGORY_PER_PAGE;
	    $categorys = skyMysqlQuery($sql);
			
			$this->categories = array();
			while ($value = mysql_fetch_object($categorys)):
				$this->categories[]=$value;
			endwhile;

	  }
  	else{
  		$this->redirect();
  	}
		
  }
}
