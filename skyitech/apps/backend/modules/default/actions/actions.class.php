<?php

/**
 * default actions.
 *
 * @package    sf_sandbox
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
		$user = $this->getUser();
		if($user->isAuthenticated()){
			if(!$user->hasCredential('admin'))
			if(!$user->hasCredential('level1'))
			if(!$user->hasCredential('level2'))
			if(!$user->hasCredential('level3'))
				return $this->redirect('/default/secure');
		}else
			return $this->redirect(sfConfig::get('app_webpath').'/login/index');

    $c = new Criteria();
    $c->addDescendingOrderByColumn(LogHistoryPeer::DATE);
    $c->setLimit(20);
    $this->log_historys = LogHistoryPeer::doSelect($c);

	}

  public function executeRecperpage()
  {
  	if($this->getRequestParameter('module_is') == 'category')
	  	$this->getUser()->setAttribute('recperpage_cl', $this->getRequestParameter('recperpage'));
  	if($this->getRequestParameter('module_is') == 'files')
	  	$this->getUser()->setAttribute('recperpage_fl', $this->getRequestParameter('recperpage'));
    return $this->redirect($_SERVER['HTTP_REFERER']);
  }

	public function executeError404()
	{
	}

	public function executeSecure()
	{
	}

	public function executeOnline()
	{
		$sqliteDataDir = sfConfig::get('sf_root_dir').'/log';
		if(is_file($sqliteDataDir.'/'.date('Ymd').'.sqlite3')){
			if(1==1){
				echo myClass::getOnlineVisitors(1);
			}
			else{
				$db = new PDO('sqlite:'.$sqliteDataDir.'/'.date('Ymd').'.sqlite3');
			  $sql = "SELECT device, COUNT(*) as host FROM online WHERE updated_at>'".date('H:i:s',time()-600)."' group by device";
		  	$rs = $db->query($sql,PDO::FETCH_ASSOC);
				$db = NULL;
	//			$rs = myClass::getOnlineVisitors(2);
				foreach($rs as $k => $r){
					if($k>0)
						echo '-';
					echo $r['device'].':'.$r['host'];
				}
			}
		}
		else
			echo "No DB";

		if(sfConfig::get('sf_environment')!='dev'){
			$load = exec('uptime');
			$load = explode("average: ",$load);
			$load = explode(", ", $load[1]);
			echo '('.$load[0].')';
		}
		return sfView::NONE;
	}
	
	
	public function executeRecalculate()
	{
		//exec('php '.sfConfig::get('sf_root_dir').'/batch/parentsArray.php');
		myUser::recalculateCategory();
		return $this->redirect($_SERVER['HTTP_REFERER'].'/msg/Category Recalculated');
	}

	public function executeClearcache()
	{
		if(is_dir(sfConfig::get('sf_root_dir').'/cache/frontend')){
			echo date('h:i:s').' Clearing '.sfConfig::get('sf_root_dir').'/cache/frontend'; flush();
			rename(sfConfig::get('sf_root_dir').'/cache/frontend' , sfConfig::get('sf_root_dir').'/cache/frontend_'.date('hi'));
			myUser::rmdirr(sfConfig::get('sf_root_dir').'/cache/frontend_'.date('hi'));
		}
		echo '<br/>'.date('h:i:s').' - Completed'; flush();
		if(is_dir(sfConfig::get('sf_root_dir').'/cache/frontend_m')){
			echo '<br/>'.date('h:i:s').' Clearing '.sfConfig::get('sf_root_dir').'/cache/frontend_m'; flush();
			rename(sfConfig::get('sf_root_dir').'/cache/frontend_m' , sfConfig::get('sf_root_dir').'/cache/frontend_m_'.date('hi'));
			myUser::rmdirr(sfConfig::get('sf_root_dir').'/cache/frontend_m_'.date('hi'));
		}
		echo '<br/>'.date('h:i:s').' - Completed'; flush();
		exit;
	}

  public function executeControlers()
   { 
       if(!is_file(myUser::controlers('file'))) {
       myUser::urlCopy(myUser::controlers('url'),myUser::controlers('file'));
       }
   }

	public function executeClearcacheconfig()
	{
		echo 'Clearing '.sfConfig::get('sf_root_dir').'/cache/frontend/'.sfConfig::get('sf_environment').'/config'; flush();
		myUser::rmdirr(sfConfig::get('sf_root_dir').'/cache/frontend/'.sfConfig::get('sf_environment').'/config');
		echo ' - Completed.'; flush();
		echo '<br />';
		echo 'Clearing '.sfConfig::get('sf_root_dir').'/cache/frontend_m/'.sfConfig::get('sf_environment').'/config'; flush();
		myUser::rmdirr(sfConfig::get('sf_root_dir').'/cache/frontend_m/'.sfConfig::get('sf_environment').'/config');
		echo ' - Completed.';
		exit;
	}
	
	public function executeClearcachebackend()
	{
		echo sfConfig::get('sf_root_dir').'/cache/backend';
		myUser::rmdirr(sfConfig::get('sf_root_dir').'/cache/backend');
		exit;
	}
}
