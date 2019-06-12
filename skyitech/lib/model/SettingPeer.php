<?php

/**
 * Subclass for performing query and update operations on the 'setting' table.
 *
 * 
 *
 * @package lib.model
 */ 
class SettingPeer extends BaseSettingPeer
{

	public static function getSetting($meta,$def=''){
		if($meta){
	  	$c = new Criteria();
			$c->add(SettingPeer::META,$meta);
			$cnt = SettingPeer::doSelectOne($c);
			if($cnt)
				return $cnt->getValue();
			else
				return $def;
  	}
  	else
  		return false;
	}

}
