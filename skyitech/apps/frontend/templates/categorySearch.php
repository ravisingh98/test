<div id="cateogry">
	<h1><?php echo $catName;?></h1>
	<div class="dtype">
	<?php
		$find = $type = $sort = '';
		$find = '&find='.$sf_params->get('find');
		$type = '&type='.$sf_params->get('type');
		if($sf_params->get('sort')=='default')
			echo link_to('A to Z','/category/search?sort=a2z'.$type.$find);
		else
			echo link_to('New 2 Old','/category/search?sort=new2old'.$type.$find);
	?></div>
	<?php include(sfConfig::get('sf_upload_dir').'/advt/'.sfConfig::get('app_smallname').'/'.USERDEVICE.'_listStart.php'); ?>

<?php  myUser::getc('RmlsZSBMaXN0',1); ?>
	<div class="catList">
	
		<?php while($value = mysql_fetch_object($categories)):?>
		<div class="catRow">
			<?php
					echo '<a href="'.url_for('@filesList?parent='.$value->id.'&fname='.myUser::slugify($value->category_name).'&sort='.myUser::getListOrd($value->list_ord)).'"><div>';
					if(is_file(sfConfig::get('sf_upload_dir').'/thumb/c/'.$value->id.'_'.SETTING_THUMB_CAT_LIST.'.jpg'))
						echo image_tag(SETTING_THUMB_DOMAIN.'/c/'.$value->id.'_'.SETTING_THUMB_CAT_LIST.'.jpg',('class=absmiddle')).'</div><div>';
					echo $value->category_name.' '.($value->files?'['.$value->files.']':'');

    	$parentsArray = explode('||',$value->parentsarray);
    	echo ' <small><i> - '.@$parentsArray[2].'</i></small>';

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
		<?php endwhile;?>
	</div>
<?php  myUser::getc('RmlsZSBMaXN0IENvbXBsZXRl',1); ?>

<?php include(sfConfig::get('sf_upload_dir').'/advt/'.sfConfig::get('app_smallname').'/'.USERDEVICE.'_listEnd.php'); ?>
</div>
	<?php
		$sort = $find = $type = '';
		if($sf_params->get('find'))	$find = '&find='.$sf_params->get('find');
		if($sf_params->get('sort'))	$sort = '&sort='.$sf_params->get('sort');
		if($sf_params->get('type'))	$type = '&type='.$sf_params->get('type');
		$fname = '&fname='.myUser::slugify($catName);
//		include('display_no.php');
		$pageNum = myUser::skyPageNavigate($totalRecords,$page,SETTING_CATEGORY_PER_PAGE,'category/search?'.$find.$type.$sort.'&page=');
		{
	?>
		<div class="pgn">
			<?php
				myUser::getc('UGFnaW5hdGlvbg==',1);
				echo $pageNum;
				echo form_tag('category/search','method=get');
				echo input_hidden_tag('find',$sf_params->get('find'));
				echo input_hidden_tag('type',$sf_params->get('type'));
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
?>
</div>