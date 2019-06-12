<div class="create">
<?php
	if($sf_context->getModuleName()=='category'){
		if($sf_context->getActionName()=='list'){
			echo link_to ('[New-Updated-Hot]', $sf_context->getModuleName().'/list?newupdatedhot=show',array('title'=>'show or hide full details'));
			echo link_to ('[set Order]', $sf_context->getModuleName().'/setOrder',array('title'=>'show or hide set order option'));
			echo link_to ('[Move Category/Files]', $sf_context->getModuleName().'/move',array('title'=>'move category or files'));
			echo link_to ('[Full Detail]', $sf_context->getModuleName().'/fullinfo',array('title'=>'show or hide full details'));
			echo link_to (image_tag('create.png',array('class'=>'absmiddle')).' Add New Category', $sf_context->getModuleName().'/create?parent='.$parent,array('title'=>'create'));
		}

	}
	elseif($sf_context->getModuleName()=='files'){
		if($sf_context->getActionName()=='list'){
			echo link_to (image_tag('addFile.png',array('class'=>'absmiddle')).' Add New File', $sf_context->getModuleName().'/create?cid='.$cid,array('title'=>'create'));
			echo link_to (image_tag('addByDir',array('class'=>'absmiddle')).' Add Files from Dir', $sf_context->getModuleName().'/dirCatch?category_id='.$cid,array('title'=>'create'));
		}
	}
	else{
		if($sf_context->getActionName()=='list'){
			echo link_to (image_tag('create.png',array('class'=>'absmiddle')).' Add New', $sf_context->getModuleName().'/create',array('title'=>'create'));
		}
		elseif($sf_context->getActionName()=='create' || $sf_context->getActionName()=='edit')
			echo link_to (image_tag('list.png',array('class'=>'absmiddle')).' List', $sf_context->getModuleName().'/list',array('title'=>'Go to List'));
		elseif($sf_context->getActionName()=='show'){
			echo link_to (image_tag('create.png',array('class'=>'absmiddle')).' Add New', $sf_context->getModuleName().'/create?cid='.$pid,array('title'=>'create'));
			echo link_to(image_tag('edit.png',array('class'=>'absmiddle')).'edit', $sf_context->getModuleName().'/edit?id='.$id,array('title'=>'Edit this Entry'));
			echo link_to(image_tag('list.png',array('class'=>'absmiddle')).'list', $sf_context->getModuleName().'/list',array('title'=>'Go to List'));
		}
	}
?>
</div>