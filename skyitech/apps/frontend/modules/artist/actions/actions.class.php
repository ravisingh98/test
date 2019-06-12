<?php

/**
 * category actions.
 *
 * @package    
 * @subpackage category
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 2692 2006-11-15 21:03:55Z fabien $
 */
class artistActions extends sfActions
{
  /**
   * Executes index action
   *
   */
  public function executeIndex()
  {
    $this->forward('default', 'module');
  }

  public function executeList()
  {
    $this->getResponse()->setTitle('Artist List '.SETTING_TITLE.' - '.sfConfig::get('app_sitename'));

		$sql = 'select * from artist';
		if($this->getRequestParameter('type')=='find')
			$sql = 'select * from artist where name like "%'.$this->getRequestParameter('find').'%"';
		if($this->getRequestParameter('type')=='chr')
			$sql = 'select * from artist where name like "'.$this->getRequestParameter('find').'%"';
		$this->totalRecords = skyMysqlGetCount($sql);

		$sql .= ' order by name asc';

		$this->page = $this->getRequestParameter('page', 1);
		$startLimit = skyGetStartLimit($this->totalRecords, $this->page, SETTING_ARTIST_PER_PAGE);
		$sql .= ' limit '.$startLimit.','.SETTING_ARTIST_PER_PAGE;
    $artists = skyMysqlQuery($sql);
		
		$this->artists = array();
		while ($value = mysql_fetch_object($artists)):
			$this->artists[]=$value;
		endwhile;

  }
}
