<h1><?php echo $sf_params->get('singer'); ?> All Mp3 Songs</h1>
<div class="list">
<? $tDomain = SETTING_THUMB_DOMAIN;

	if(is_file(sfConfig::get('sf_upload_dir').'/thumb/singer/'.myUser::convert_name($sf_params->get('singer')).'_'.SETTING_THUMB_FILE_TOP.'.jpg')){
				echo '<div class="fshow"><p class="showimage">';
				echo image_tag($tDomain.'/singer/'.myUser::convert_name($sf_params->get('singer')).'_'.SETTING_THUMB_FILE_TOP.'.jpg',array('title'=> $sf_params->get('singer'), 'class'=>'absmiddle'));
				echo '</p></div>';
		}

if(count($filess)>0)
{
?>
<div class="dtype">
	<?php
		$singer = $name = $type = $sort = '';
		if($sf_params->get('singer'))	$singer = '&singer='.$sf_params->get('singer');
		if($sf_params->get('name'))	$name = '&name='.$sf_params->get('name');
		if($sf_params->get('type'))	$type = '&type='.$sf_params->get('type');

		if($sf_params->get('sort')!='new2old')
			echo link_to('New 2 Old','@filesSingerList?sort=new2old'.$singer.$name.$type);
		else
			echo '<span>New 2 Old</span>';
		if($sf_params->get('sort')!='download')
			echo '&nbsp;|&nbsp;'.link_to('Popular','@filesSingerList?sort=download'.$singer.$name.$type);
		else
			echo '&nbsp;|&nbsp;'.'<span>Popular</span>';
		if($sf_params->get('sort')!='a2z')
			echo '&nbsp;|&nbsp;'.link_to('A to Z','@filesSingerList?sort=a2z'.$singer.$name.$type);
		else
			echo '&nbsp;|&nbsp;'.'<span>A to Z</span>';
		if($sf_params->get('sort')!='z2a')
			echo '&nbsp;|&nbsp;'.link_to('Z to A','@filesSingerList?sort=z2a'.$singer.$name.$type);
		else
			echo '&nbsp;|&nbsp;'.'<span>Z to A</span>';

	?>
</div>
<?php
//if(mysql_num_rows($filess)>3)
	include(sfConfig::get('sf_upload_dir').'/advt/'.sfConfig::get('app_smallname').'/'.USERDEVICE.'_listStart.php');
?>
<?php include('files_list.php') ?>
<?php include(sfConfig::get('sf_upload_dir').'/advt/'.sfConfig::get('app_smallname').'/'.USERDEVICE.'_listEnd.php'); ?>
	<?php
		if($sf_params->get('sort'))	$sort = '&sort='.$sf_params->get('sort');
		$pageNum = myUser::skyPageNavigate($totalRecords,$page,SETTING_FILES_PER_PAGE, '@filesSingerList?'.$singer.$name.$type.$sort.'&page=');
		if($pageNum)
		{
	?>
		<div class="pgn" align="center">
			<?php
				myUser::getc('UGFnaW5hdGlvbg==',1);
				echo $pageNum;
				echo form_tag('files/singer','method=get');
				echo input_hidden_tag('singer',$sf_params->get('singer'));
				echo input_hidden_tag('name',$sf_params->get('name'));
				echo input_hidden_tag('type',$sf_params->get('type'));
				echo input_hidden_tag('sort',$sf_params->get('sort'));
				echo 'Jump to Page '.input_tag('page','','size=3');
				echo submit_tag('Go&raquo;','');
				echo '</form>';
			?>
		</div>
	<?php } ?>
<?php
} /* if files more then 0 */
?>
</div>
<?php include(sfConfig::get('sf_upload_dir').'/advt/'.sfConfig::get('app_smallname').'/'.USERDEVICE.'_allPageBottom.php'); ?>
<div class="path"><?php 
	echo link_to('Home',sfConfig::get('app_webpath')).' &raquo; ';
?>
</div>