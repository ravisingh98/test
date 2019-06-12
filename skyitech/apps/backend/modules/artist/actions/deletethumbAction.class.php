<?php

/**
 * sites actions.
 *
 * @package    
 * @subpackage sites
 * @author     SKYiTech.com
 * @version    SVN: $Id: actions.class.php 3335 2007-01-23 16:19:56Z fabien $
 */
class deletethumbAction extends sfAction
{
	public function execute()
	{
		$artist = ArtistPeer::retrieveByPk($this->getRequestParameter('id'));
		$this->forward404Unless($artist);
		$thumbServer = 'singer';
		sfToolkit::clearGlob(sfConfig::get('sf_upload_dir').'/thumb/'.$thumbServer.'/'.myUser::convert_name($artist->getName()).'_*.jpg');
		return true;
	}

	public function handleError()
	{
 		deleteAction::execute();
 		return sfView::NONE;
	}

}

