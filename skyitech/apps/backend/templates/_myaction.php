<?php
if($sf_context->getModuleName()=='category'){
	if($child=='D'):
		echo link_to(image_tag('addCategory.png'),'category/create?parent='.$id,array('title'=>'add Category'));
	elseif($child=='F'):
		echo link_to(image_tag('addFile.png'),'files/create?cid='.$id,array('title'=>'add File'));
		echo link_to(image_tag('addByDir.png'),'files/dirCatch?category_id='.$id,array('title'=>'add File By Directory'));
	elseif($child=='N'):
		echo link_to(image_tag('addCategory.png'),'category/create?parent='.$id,array('title'=>'add Category'));
		echo link_to(image_tag('addFile.png'),'files/create?cid='.$id,array('title'=>'add File'));
		echo link_to(image_tag('addByDir.png'),'files/dirCatch?category_id='.$id,array('title'=>'add File By Directory'));
	endif;
	if($child!='F' && $_SERVER['REMOTE_ADDR']=='115.240.79.79'){
		echo link_to(image_tag('addDir.png'),'category/dirCatch?parent='.$id,array('title'=>'add Category By Directory'));
	}
}
if($sf_context->getModuleName()=='movies')
	echo link_to(image_tag('copy.png'),$sf_context->getModuleName().'/copy?id='.$id, array('title'=>'copy movie to other site') );

echo link_to(image_tag('edit.png'),$sf_context->getModuleName().'/edit?id='.$id,array('title'=>'edit this item'));

if($sf_context->getModuleName()=='files' && $sf_context->getActionName()=='list')
	echo link_to(image_tag('delete.png'),$sf_context->getModuleName().'/delete?id='.$id.($sf_params->get('cid') ? '&cid='.$sf_params->get('cid') : ''), array('onclick'=>'return delCat();'));
elseif( ($sf_context->getModuleName()=='updates' || $sf_context->getModuleName()=='filterwords' || $sf_context->getModuleName()=='bannedip')  && $sf_context->getActionName()=='list')
	echo link_to(image_tag('delete.png'),$sf_context->getModuleName().'/delete?id='.$id, array());
elseif($sf_context->getModuleName()!='guestbook')
	echo link_to(image_tag('delete.png'),$sf_context->getModuleName().'/delete?id='.$id, array('onclick'=>'return delCat();'));

?>
