<b><?php 
echo myUser::getCategoryPath($parents->getParentsarray());
if($self)
	if($sf_context->getModuleName()=='files')
		echo link_to($parents->getCategoryName(),'/files/list?cid='.$parents->getId());
	else
		echo link_to($parents->getCategoryName(),'/category/list?parent='.$parents->getId());
?></b>
