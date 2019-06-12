<?php

/**
 * Subclass for performing query and update operations on the 'updates' table.
 *
 * 
 *
 * @package lib.model
 */ 
class UpdatesPeer extends BaseUpdatesPeer
{
	/*
	* SKYiTech :: Get latest updates
	*/
	public static function getLatestUpdates(){
		$c = new Criteria();
		$c->add(UpdatesPeer::STATUS,'A');
		$c->add(UpdatesPeer::SITE,sfConfig::get('sf_environment'));
		$c->addDescendingOrderByColumn(UpdatesPeer::CREATED_AT);
		return UpdatesPeer::doSelect($c);
	}

}
