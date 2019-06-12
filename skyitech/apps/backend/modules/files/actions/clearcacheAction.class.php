<?php

/**
 * sites actions.
 *
 * @package    
 * @subpackage sites
 * @author     SKYiTech.com
 * @version    SVN: $Id: actions.class.php 3335 2007-01-23 16:19:56Z fabien $
 */
class clearcacheAction extends sfAction
{
  public function execute()
  {
		myUser::clearFileCache($this->getRequestParameter('id'));
		return true;
	}

	public function handleError()
	{
 		clearcacheAction::execute();
 		return sfView::NONE;
	}

}
