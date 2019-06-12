<div id="cateogry">
	<h1><?php echo $catName;?></h1>
	<?php if($catDescription): ?>
		<div class="description"><?php echo str_replace(chr(13),'<br />',$catDescription); ?></div>
	<?php endif; ?>
<?php
	if(is_file(sfConfig::get('sf_upload_dir').'/thumb/c/'.$parent.'_'.SETTING_THUMB_CAT_TOP.'.jpg'))
		echo '<p class="showimage">'.image_tag(SETTING_THUMB_DOMAIN.'/c/'.$parent.'_'.SETTING_THUMB_CAT_TOP.'.jpg',('class=absmiddle')).'</p>';
?>
	<div class="dtype">
	<?php
		$parent = $name = $type = $sort = '';
		$fname = '&fname='.myUser::slugify($catName);
		if($sf_params->get('parent'))	$parent = '&parent='.$sf_params->get('parent');
		if($sf_params->get('sort')=='default' || $sf_params->get('sort')=='new2old')
			echo link_to('sort by A to Z','@categoryList?sort=a2z'.$parent.$fname);
		else
			echo link_to('sort by New 2 Old','@categoryList?sort=new2old'.$parent.$fname);
	?></div>
	<?php include(sfConfig::get('sf_upload_dir').'/advt/'.sfConfig::get('app_smallname').'/'.USERDEVICE.'_listStart.php'); ?>

<?php  myUser::getc('RmlsZSBMaXN0',1); ?>
	<div class="catList">
		<?php foreach($categories as $value):?>
		<div class="catRow">
			<?php

				if($value->child=='D'):
					echo '<a href="'.url_for('@categoryList?parent='.$value->id.'&fname='.myUser::slugify($value->category_name)).'"><div>';
					if(is_file(sfConfig::get('sf_upload_dir').'/thumb/c/'.$value->id.'_'.SETTING_THUMB_CAT_LIST.'.jpg'))
						echo image_tag(SETTING_THUMB_DOMAIN.'/c/'.$value->id.'_'.SETTING_THUMB_CAT_LIST.'.jpg',('class=absmiddle')).'</div><div>';
					echo $value->category_name.' '.($value->files?'['.$value->files.']':'');
				elseif($value->child=='U'):
					echo '<a href="'.$value->url.'"><div>';
					echo $value->category_name;
				else:
					echo '<a href="'.url_for('@filesList?parent='.$value->id.'&fname='.myUser::slugify($value->category_name).'&sort='.myUser::getListOrd($value->list_ord)).'"><div>';
					if(is_file(sfConfig::get('sf_upload_dir').'/thumb/c/'.$value->id.'_'.SETTING_THUMB_CAT_LIST.'.jpg'))
						echo image_tag(SETTING_THUMB_DOMAIN.'/c/'.$value->id.'_'.SETTING_THUMB_CAT_LIST.'.jpg',('class=absmiddle')).'</div><div>';
					echo $value->category_name.' '.($value->files?'['.$value->files.']':'');
				endif;
			?>
			<?php
				if($value->flag_new)
					echo image_tag('new.gif');
				if($value->flag_updated)
					echo image_tag('updated.gif');
				if($value->flag_hot)
					echo image_tag('hot.gif');
			?></div></a>
		</div>
		<?php endforeach;?>
	</div>
<?php  myUser::getc('RmlsZSBMaXN0IENvbXBsZXRl',1); ?>

<?php include(sfConfig::get('sf_upload_dir').'/advt/'.sfConfig::get('app_smallname').'/'.USERDEVICE.'_listEnd.php'); ?>
</div>
	<?php
		$sort = '';
		if($sf_params->get('sort'))	$sort = '&sort='.$sf_params->get('sort');
//		include('display_no.php');
		$pageNum = myUser::skyPageNavigate($totalRecords,$page,SETTING_CATEGORY_PER_PAGE,'@categoryList?'.$parent.$fname.$sort.'&page=');
		{
	?>
		<div class="pgn">
			<?php
				myUser::getc('UGFnaW5hdGlvbg==',1);
				echo $pageNum;
				echo form_tag('category/list','method=get');
				echo input_hidden_tag('parent',$sf_params->get('parent'));
				echo input_hidden_tag('fname',myUser::slugify($catName));
				echo input_hidden_tag('sort',$sf_params->get('sort'));
				echo 'Jump to Page '.input_tag('page','','size=3');
				echo submit_tag('Go&raquo;','');
				echo '</form>';
			?>
		</div>
	<?php } ?>
<?php include(sfConfig::get('sf_upload_dir').'/advt/'.sfConfig::get('app_smallname').'/'.USERDEVICE.'_allPageBottom.php'); ?>
<div class="path"><?php 
	echo link_to('Home',sfConfig::get('app_webpath')).' &raquo; ';
	echo $categoryPath;
	echo link_to($catName,'/category/list?parent='.$sf_params->get('parent').'&fname='.myUser::slugify($catName));
?>
</div>