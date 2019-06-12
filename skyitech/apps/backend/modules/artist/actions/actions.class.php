<?php

class artistActions extends sfActions
{
  public function executeIndex()
  {
    return $this->forward('artist', 'list');
  }

 public function executeList()
  {
		$c = new Criteria();

		if($this->getRequestParameter('searchText')){
			if($this->getRequestParameter('search')=='Id')
				$c->add(ArtistPeer::ID, $this->getRequestParameter('searchText'));
			if($this->getRequestParameter('search')=='Name')
				$c->add(ArtistPeer::NAME , '%'.$this->getRequestParameter('searchText').'%', Criteria::LIKE);
                      }
		if($this->getRequestParameter('sort')=='a2z')
			$c->addAscendingOrderByColumn(ArtistPeer::NAME);
		elseif($this->getRequestParameter('sort')=='z2a')
			$c->addDescendingOrderByColumn(ArtistPeer::NAME);
		else
			$c->addDescendingOrderByColumn(ArtistPeer::ID);
            $pager = new sfPropelPager('Artist', 10);
	    $pager->setCriteria($c);
	    $pager->setPage($this->getRequestParameter('page', 1));
	    $pager->init();
	    $this->pager = $pager;
	    $this->artists = $pager->getResults();

  }

	public function executeEditInLine()
	{
	    //load the proper page to work with
	    $page = ArtistPeer::retrieveByPk($this->getRequestParameter('id'));
	
	    //determine the proper get and set methods to be used
	    $set_func = 'set'.$this->getRequestParameter('attribute');
	    $get_func = 'get'.$this->getRequestParameter('attribute');
	
	    //same the current version of your page before performing the update
	    //$page->saveCurrentVersion();
	
	    //set the new value, save it and return it back for page display
	    $page->$set_func($this->getRequestParameter('value'));
	    $page->save();
	    die($page->$get_func());
	}

  public function executeShow()
  {
    $this->artist = ArtistPeer::retrieveByPk($this->getRequestParameter('id'));
    $this->forward404Unless($this->artist);
  }

  public function executeCreate()
  {
            $this->artist = new Artist();

	    $this->setTemplate('edit');
  }

  public function executeEdit()
  {
    $this->artist = ArtistPeer::retrieveByPk($this->getRequestParameter('id'));
    $this->forward404Unless($this->artist);
  }

  public function executeUpdate()
  {
    if (!$this->getRequestParameter('id'))
    {
      $artist = new Artist();
    }
    else
    {
      $artist = ArtistPeer::retrieveByPk($this->getRequestParameter('id'));
      $this->forward404Unless($artist);
    }

    $artist->setId($this->getRequestParameter('id'));
    $artist->setName($this->getRequestParameter('name'));
	    $artist->setStatus($this->getRequestParameter('status')=='A' ? ($this->getRequestParameter('featured') ? 'F' : 'A') : 'B');
    $artist->save();


		/*
		* SKYiTech :: create thumb images if file type is not wallpaper and thumb image uploaded
		*/
		$position = "bottom-right";
		if($this->getRequestParameter('position')=='T')
			$position = "top-right";
		if($this->getRequestParameter('position')=='M')
			$position = "middle-right";

		$thumbSave = sfConfig::get('sf_upload_dir').'/thumb/singer/'.myUser::convert_name($artist->getName()).'_rb.jpg';

		if($this->getRequest()->getFileSize('thumb_name') > 0)
		{
			if(!is_dir(sfConfig::get('sf_upload_dir').'/thumb/singer'))
				mkdir(sfConfig::get('sf_upload_dir').'/thumb/singer');

			{
				list($width1, $height1, $type1, $attr1) = getimagesize($this->getRequest()->getFilePath('thumb_name'));
				myClass::getImageRatio($this->getRequest()->getFilePath('thumb_name'),'thumb/singer/',80, 80, $width1, $height1,false, myUser::convert_name($artist->getName()).'_1.jpg',true,90,'logo.png');
				myClass::getImageRatio($this->getRequest()->getFilePath('thumb_name'),'thumb/singer/',100, 100, $width1, $height1,false, myUser::convert_name($artist->getName()).'_2.jpg',true,90,'logo.png');
				myClass::getImageRatio($this->getRequest()->getFilePath('thumb_name'),'thumb/singer/',150, 150, $width1, $height1,false, myUser::convert_name($artist->getName()).'_3.jpg',true,90,'logo.png',$position);
				myClass::getImageRatio($this->getRequest()->getFilePath('thumb_name'),'thumb/singer/',200, 200, $width1, $height1,false, myUser::convert_name($artist->getName()).'_4.jpg',true,90,'logo.png',$position);
			}
			$this->getRequest()->moveFile('thumb_name', $thumbSave);
		}
		elseif($this->getRequestParameter('thumb_url_path'))
		{
			if(!is_dir(sfConfig::get('sf_upload_dir').'/thumb/singer'))
				mkdir(sfConfig::get('sf_upload_dir').'/thumb/singer');

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL,  trim($this->getRequestParameter('thumb_url_path')));
			curl_setopt($ch, CURLOPT_HEADER, false);
			curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLINFO_HEADER_OUT, true);
			set_time_limit(300); # 5 minutes for PHP
			curl_setopt($ch, CURLOPT_TIMEOUT, 300) or error('time limit exceed... '); # and also for CURL

			$outfile = fopen($thumbSave, 'wb');
			curl_setopt($ch, CURLOPT_FILE, $outfile) or error('can not write destination file');
			curl_exec($ch) or error('error3');
			//$info = curl_getinfo($ch);
			fclose($outfile);
			{
				list($width1, $height1, $type1, $attr1) = getimagesize($thumbSave);
				myClass::getImageRatio($thumbSave,'thumb/singer/',80, 80, $width1, $height1,false, myUser::convert_name($artist->getName()).'_1.jpg',true,90,'logo.png');
				myClass::getImageRatio($thumbSave,'thumb/singer/',100, 100, $width1, $height1,false, myUser::convert_name($artist->getName()).'_2.jpg',true,90,'logo.png');
				myClass::getImageRatio($thumbSave,'thumb/singer/',150, 150, $width1, $height1,false, myUser::convert_name($artist->getName()).'_3.jpg',true,90,'logo.png',$position);
				myClass::getImageRatio($thumbSave,'thumb/singer/',200, 200, $width1, $height1,false, myUser::convert_name($artist->getName()).'_4.jpg',true,90,'logo.png',$position);
			}
		}
		elseif($this->getRequestParameter('position_force')=='yes'){
				myClass::getImageRatio($thumbSave,'thumb/singer/',100, 100, $width1, $height1,false, myUser::convert_name($artist->getName()).'_2.jpg',true,90,'logo.png',$position);
				myClass::getImageRatio($thumbSave,'thumb/singer/',150, 150, $width1, $height1,false, myUser::convert_name($artist->getName()).'_3.jpg',true,90,'logo.png',$position);
		}
	 	   myUser::writeArtistList();
    return $this->redirect('artist/list');
  }


  public function executeDelete()
  {
  	$this->artist = ArtistPeer::retrieveByPk($this->getRequestParameter('id'));

        $this->forward404Unless($this->artist);

        $this->artist->delete();

        return $this->redirect('artist/list');
  }

  public function executeActivation()
  {
  	if($this->getRequestParameter('status') == 'B' || $this->getRequestParameter('status') == 'F')
  		$status = 'A';
  	else
  		$status = 'B';
    $this->artist = ArtistPeer::retrieveByPk($this->getRequestParameter('id'));
    $this->forward404Unless($this->artist);
    $this->artist->setStatus($status);
    $this->artist->save();
  	if($this->getRequestParameter('status') == 'B' || $this->getRequestParameter('status') == 'F')
  		echo '<a onclick="new Ajax.Updater(\'active_'.$this->getRequestParameter('id').'\', \'/'.sfConfig::get('app_webpath').'/artist/activation/id/'.$this->getRequestParameter('id').'/status/A\', {asynchronous:true, evalScripts:false});; return false;" href="#">Active</a>';
  	else
  		echo '<a onclick="new Ajax.Updater(\'active_'.$this->getRequestParameter('id').'\', \'/'.sfConfig::get('app_webpath').'/artist/activation/id/'.$this->getRequestParameter('id').'/status/B\', {asynchronous:true, evalScripts:false});; return false;" href="#"><span class="red">Block</span></a>';
		return sfView::NONE;
  }
  
  
}
