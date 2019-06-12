<?php
// auto-generated by sfPropelCrud
// date: 2009/08/10 18:07:49
?>
<?php

/**
 * setting actions.
 *
 * @package    
 * @subpackage setting
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 3335 2007-01-23 16:19:56Z fabien $
 */
class settingActions extends sfActions
{
  public function executeIndex()
  {
    return $this->forward('setting', 'list');
  }

  public function executeList()
  {
  	$c = new Criteria();
  	if($this->getRequestParameter('device')=='PC')
  		$c->add(SettingPeer::DEVICE, 'P');
  	if($this->getRequestParameter('device')=='MOB')
	  	$c->add(SettingPeer::DEVICE, 'M');
  	$c->addAscendingOrderByColumn(SettingPeer::DEVICE);
  	$c->addAscendingOrderByColumn(SettingPeer::META);
    $this->settings = SettingPeer::doSelect($c);
  }

  public function executeShow()
  {
    $this->setting = SettingPeer::retrieveByPk($this->getRequestParameter('id'));
    $this->forward404Unless($this->setting);
  }

  public function executeCreate()
  {
    $this->setting = new Setting();

    $this->setTemplate('edit');
  }

  public function executeEdit()
  {
    $this->setting = SettingPeer::retrieveByPk($this->getRequestParameter('id'));
    $this->forward404Unless($this->setting);
  }

  public function executeUpdate()
  {
    if (!$this->getRequestParameter('id'))
    {
      $setting = new Setting();
    }
    else
    {
      $setting = SettingPeer::retrieveByPk($this->getRequestParameter('id'));
      $this->forward404Unless($setting);
    }

    $setting->setId($this->getRequestParameter('id'));
    $setting->setMeta($this->getRequestParameter('meta'));
    $setting->setDescription($this->getRequestParameter('description'));
    $setting->setValue($this->getRequestParameter('value'));
    $setting->setDevice($this->getRequestParameter('device'));

    $setting->save();

		/*
		* SKYiTech :: Write setting.php file 
		*/
		{
			/* write mobile settings
			**/
			$writeFile = '<?php'.chr(13);
			$c = new Criteria();
			$c->add(SettingPeer::DEVICE, 'M', Criteria::NOT_EQUAL);
			$c->addAnd(SettingPeer::DEVICE, 'A', Criteria::NOT_EQUAL);
		//print_r(SettingPeer::doSelectRs($c)); exit;
			foreach(SettingPeer::doSelect($c) as $settingRow):
				$writeFile .= 'define(\'SETTING_'.strtoupper($settingRow->getMeta()).'\', \''.$settingRow->getValue().'\');'.chr(13);
			endforeach;
			$writeFile .= '?>';
			$selectFile = 'settings_p.php';
			$filenm = sfConfig::get('sf_upload_dir')."/".$selectFile;
			if(file_exists($filenm))
				unlink($filenm);
			$handle = fopen($filenm, 'w');
			fwrite($handle, $writeFile);
			fclose($handle);
			
			/* write pc settings
			**/
			$writeFile = '<?php'.chr(13);
			$c = new Criteria();
			$c->add(SettingPeer::DEVICE, 'P', Criteria::NOT_EQUAL);
			$c->addAnd(SettingPeer::DEVICE, 'A', Criteria::NOT_EQUAL);
			foreach(SettingPeer::doSelect($c) as $settingRow):
				$writeFile .= 'define(\'SETTING_'.strtoupper($settingRow->getMeta()).'\', \''.$settingRow->getValue().'\');'.chr(13);
			endforeach;
			$writeFile .= '?>';
	
			$selectFile = 'settings_m.php';
			$filenm = sfConfig::get('sf_upload_dir')."/".$selectFile;
			if(file_exists($filenm))
				unlink($filenm);
			$handle = fopen($filenm, 'w');
			fwrite($handle, $writeFile);
			fclose($handle);
			
			/* write admin settings
			**/

			$writeFile = '<?php'.chr(13);
			$c = new Criteria();
			$c->add(SettingPeer::DEVICE, 'a');
			foreach(SettingPeer::doSelect($c) as $settingRow):
				$writeFile .= 'define(\'SETTING_'.strtoupper($settingRow->getMeta()).'\', \''.$settingRow->getValue().'\');'.chr(13);
			endforeach;
			$writeFile .= '?>';

			$selectFile = 'settings_admin.php';
			$filenm = sfConfig::get('sf_upload_dir')."/".$selectFile;
			if(file_exists($filenm))
				unlink($filenm);
			$handle = fopen($filenm, 'w');
			fwrite($handle, $writeFile);
			fclose($handle);

		}

    return $this->redirect('setting/list');
  }

  public function executeDelete()
  {
    $setting = SettingPeer::retrieveByPk($this->getRequestParameter('id'));

    $this->forward404Unless($setting);

    $setting->delete();

    return $this->redirect('setting/list');
  }

  public function executeChangePassword()
  {
  	if($this->hasRequestParameter('password')){
	    $admin = AdminPeer::retrieveByPk($this->getUser()->getAttribute('ADMINUSERID','','admin'));
	    if($this->getRequestParameter('username'))
		    $admin->setUsername($this->getRequestParameter('username'));
		 if($this->getRequestParameter('password'))
	    	$admin->setPassword(md5($this->getRequestParameter('password')));
	    $admin->save();
	    $this->redirect('/');
  	}
  	else{
	    $this->admin = AdminPeer::retrieveByPk($this->getUser()->getAttribute('ADMINUSERID','','admin'));
	    $this->forward404Unless($this->admin);
	  }
  }

}
