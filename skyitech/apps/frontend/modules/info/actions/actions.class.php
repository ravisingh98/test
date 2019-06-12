<?php

/**
 * disclaimer actions.
 *
 * @package    
 * @subpackage disclaimer
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 2692 2006-11-15 21:03:55Z fabien $
 */
class infoActions extends sfActions
{
  /**
   * Executes index action
   *
   */
  public function executeDisclaimer()
  {
  }

	public function executeMore()
	{
	}

	public function executeLatestupdates()
	{
		$sql = 'select * from updates where status="D"';
		$this->totalRecords = skyMysqlGetCount($sql);
		$sql .= ' order by created_at desc';

		$this->page = $this->getRequestParameter('page', 1);
		$startLimit = skyGetStartLimit($this->totalRecords, $this->page, SETTING_UPDATES_PER_PAGE);
		$sql .= ' limit '.$startLimit.','.SETTING_UPDATES_PER_PAGE;
    $this->updatess = skyMysqlQuery($sql);

	}

}
