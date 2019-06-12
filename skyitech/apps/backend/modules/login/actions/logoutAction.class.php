<?php
class logoutAction extends sfAction
{
	public function execute()
	{
		$this->getUser()->getAttributeHolder()->removeNamespace('admin');
		$this->getUser()->getAttributeHolder()->clear();
		$this->getUser()->clearCredentials('admin');
		$this->getUser()->setAuthenticated(false);
		return $this->redirect('@homepage');
	}
}
?>