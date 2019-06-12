<?php

class downloadAction extends sfAction
{
  public function execute()
  {
  	if($this->getRequestParameter('id')){
			if(!is_numeric($this->getRequestParameter('id')))
				$this->forward404();
	    $files = skyGetRecordById('files',$this->getRequestParameter('id'));
			$this->forward404Unless($files);

			$dataServer = 'sfd'.ceil($files->id/500);

			/*
			* SKYiTech :: get wallpaper in different size
			*							if image in requested size is not present on server generate it now and redirect to current page
			*/
	    $size = '';
	    $allowedsize = explode(",",SETTING_WALLPAPER_SIZE);
	    if($this->getRequestParameter('size') && $this->getRequestParameter('size')!=''){
			if(in_array($this->getRequestParameter('size'), $allowedsize))
	    		$size = $this->getRequestParameter('size');
	    	else
	    		$size = '640x480';

			if(!is_file(sfConfig::get('sf_upload_dir').'/ifiles/'.$size.'/'.$files->id.'/'.$files->file_name)){
				if(!is_dir(sfConfig::get('sf_upload_dir').'/ifiles/'.$size)){
					mkdir(sfConfig::get('sf_upload_dir').'/ifiles/'.$size);
					//chmod(sfConfig::get('sf_upload_dir').'/ifiles/'.$size,777);
				}
				if(!is_dir(sfConfig::get('sf_upload_dir').'/ifiles/'.$size.'/'.$files->id)){
					mkdir(sfConfig::get('sf_upload_dir').'/ifiles/'.$size.'/'.$files->id);
					//chmod(sfConfig::get('sf_upload_dir').'/ifiles/'.$size.'/'.$files->id,777);
				}
				$sizes = explode('x',$size);
			   $orgFilePath = sfConfig::get('sf_upload_dir').'/files/'.$dataServer.'/'.$files->id.'/org_'.$files->id.'_'.$files->file_name;
			   list($width1, $height1) = getimagesize($orgFilePath);
			   if($sizes[0]<360)
		    		$wImg = 'w1.png';
		    	else
			    	$wImg = 'w2.png';
		    	
		    	ini_set( memory_limit, '64M');
		    
		    if(SETTING_WALLPAPER_WATERMARK=='OFF')
					myClass::getImageRatio($orgFilePath,'ifiles/'.$size.'/'.$files->id.'/',$sizes[0], $sizes[1], $width1, $height1,false, $files->file_name,false,100,$wImg);
				else
					myClass::getImageRatio($orgFilePath,'ifiles/'.$size.'/'.$files->id.'/',$sizes[0], $sizes[1], $width1, $height1,false, $files->file_name,true,100,$wImg);

				if($sizes[0] > 900){
					//sleep(5);
				}
			}
		  }

			$rn = rand(1,4);
		  $sql = "update files set today=today+$rn, download=download+$rn where id=".$this->getRequestParameter('id');
			skyMysqlQuery($sql);

			if($files->extension=='URL'){
				$this->redirect($files->url);
				exit;
			}

//				$mobDomain = explode( ',' , SETTING_MOB_DATA_DOMAIN);
//				$pcDomain = explode( ',' , SETTING_PC_DATA_DOMAIN);
//				$mobDomain = trim( $mobDomain[array_rand($mobDomain)] );
//				$pcDomain = trim( $pcDomain[array_rand($pcDomain)] );

			$mobDomain = SETTING_MOB_DATA_DOMAIN;
			$pcDomain = SETTING_PC_DATA_DOMAIN;

			$filename = $files->file_name;
			$type = $this->getRequestParameter('type');
			if($type=='64')
				$filename = myClass::MultiFileName($files->file_name,'64');
			if($type=='192')
				$filename = myClass::MultiFileName($files->file_name,'192');
			if($type=='320')
				$filename = myClass::MultiFileName($files->file_name,'320');

			/*
			* SKYiTech :: check for mobile & pc user and redirect as per that
			*/
			if(USERDEVICE=='m')
			{
				
				if($size!=''){	/* if wallpaper */
				  if(preg_match('/samsung|android|opera/i',$_SERVER['HTTP_USER_AGENT']))
				    $this->redirect($mobDomain.'/ifiles/'.$size.'/'.$files->id.'/'.$files->file_name);
				  else
				    $this->redirect($pcDomain.'/ifiles/'.$size.'/'.$files->id.'/'.$files->file_name);
				}
			  else{	/* if other file */
		    		$this->redirect($mobDomain.'/files/'.$dataServer.'/'.$this->getRequestParameter('id').'/'.$filename);
			    }
			    exit;
			}
			else{
				if($size!=''){	/* if wallpaper */
					$this->redirect($pcDomain.'/ifiles/'.$size.'/'.$files->id.'/'.$files->file_name);
					exit;
				}
				else
		    	$this->redirect($pcDomain.'/files/'.$dataServer.'/'.$this->getRequestParameter('id').'/'.$filename);
		    	exit;
			}
		}
	    return sfView::NONE;
  }

	public function handleError()
	{
 		downloadAction::execute();
 		return sfView::NONE;
	}

}