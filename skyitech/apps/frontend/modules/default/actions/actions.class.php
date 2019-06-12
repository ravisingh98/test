<?php

/**
 * default actions.
 *
 * @package    
 * @subpackage default
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 2692 2006-11-15 21:03:55Z fabien $
 */
class defaultActions extends sfActions
{
  /**
   * Executes index action
   *
   */
  public function executeIndex()
  {
		if(!stristr($_SERVER['HTTP_HOST'],'frashmusic.club'))
			if(!stristr($_SERVER['HTTP_HOST'],'frashmusic'))
				exit;

		$this->getResponse()->setTitle(sfConfig::get('app_sitename').' '.SETTING_TITLE_HOME);

  }
	public function executeError404()
	{
		$this->redirect('/');
	}

	public function executeSecure()
	{
	}

	public function executeDisclaimer()
	{
	}

	public function executeUnavailable()
	{
	}

}