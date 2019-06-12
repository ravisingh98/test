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
		$sf_root_cache_dir = sfConfig::get('sf_root_cache_dir');
		$cache_dir = $sf_root_cache_dir.'/*/*/template/*/all';
	
		sfToolkit::clearGlob($cache_dir.'/sf_cache_partial');
		sfToolkit::clearGlob($cache_dir.'/global');
		sfToolkit::clearGlob($cache_dir.'/latest_updates');
		sfToolkit::clearGlob($cache_dir.'/newitems');

		echo 'cleared';
		return true;
	}

	public function handleError()
	{
 		clearcacheAction::execute();
 		return sfView::NONE;
	}

}
