<?php
// auto-generated by sfPropelCrud
// date: 2009/07/22 19:29:36
?>
<?php

/**
 * sites actions.
 *
 * @package    
 * @subpackage sites
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 3335 2007-01-23 16:19:56Z fabien $
 */
class deleteAction extends sfAction
{
  public function execute()
  {
    $files = FilesPeer::retrieveByPk($this->getRequestParameter('id'));
    $this->forward404Unless($files);
    /*
    * SKYiTech :: Remove folder & all files of this file
    */
		$dataServer = 'sfd'.ceil($files->getId()/500);
		$thumbServer = 'sft'.ceil($files->getId()/500);
    myUser::rmdirr(sfConfig::get('sf_upload_dir').'/files/'.$dataServer.'/'.$files->getId().'/');

		sfToolkit::clearGlob(sfConfig::get('sf_upload_dir').'/thumb/'.$thumbServer.'/'.$files->getId().'_*.jpg');

    $parentCategory = CategoryPeer::retrieveByPk($files->getCategoryId());
    if(CategoryPeer::hasFiles($files->getCategoryId(),1)==1)
    {
	    $parentCategory->setChild('N');
	    $parentCategory->save();
		}
		/*
		* SKYiTech :: updating file's count for this files parent categories
		*/
		myUser::updateFilesTotal($files->getCategoryId(),$parentCategory->getParents(),'remove',1);

    $files->delete();
    return $this->redirect($_SERVER['HTTP_REFERER']);
    //return $this->redirect('files/list'.($this->getRequestParameter('cid') ? '?cid='.$this->getRequestParameter('cid') : ''));
  }

	public function handleError()
	{
 		deleteAction::execute();
 		return sfView::NONE;
	}

}
