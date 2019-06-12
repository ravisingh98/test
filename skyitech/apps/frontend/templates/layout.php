<?php
if(USERDEVICE=='p')
echo '<!DOCTYPE html>';
else
echo '<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN" "http://www.wapforum.org/DTD/xhtml-mobile10.dtd">';
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include_http_metas() ?>
<?php include_metas() ?>
<?php include_title() ?>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<link href="/css/<?php echo sfConfig::get('app_smallname')?>.css?<?php echo date('dm')?>" type="text/css" rel="stylesheet"/>
<?php 
if($sf_params->get('module').$sf_params->get('action')=='defaultindex')
	include(sfConfig::get('sf_upload_dir').'/advt/'.sfConfig::get('app_smallname').'/meta_homepage.php');
else
	include(sfConfig::get('sf_upload_dir').'/advt/'.sfConfig::get('app_smallname').'/meta_allpage.php');
?>
</head>
<body>
<div class="logo">
	<?php echo link_to(image_tag(sfConfig::get('app_sitename').'_'.USERDEVICE.'.png',array('alt'=>sfConfig::get('app_sitename'))),sfConfig::get('app_webpath')); ?>
</div>
<div id="mainDiv">
	<?php
		if($sf_params->get('module')=='default'){
			include(sfConfig::get('sf_upload_dir').'/advt/'.sfConfig::get('app_smallname').'/'.USERDEVICE.'_homeTop.php');
		}
		elseif($sf_params->get('module').$sf_params->get('action')!='filessearch')
		{
			include(sfConfig::get('sf_upload_dir').'/advt/'.sfConfig::get('app_smallname').'/'.USERDEVICE.'_allPageTop.php');
		}
	?>
<div id="mainDiv">
	<center> <font color="#FF0000"><b>No 1 Dj Remixer song uploading sites</b></font></center></div>
<div class="ad1 tCenter pgn2" align="center"> 
			<img src="/images/facebook.png" alt="."> <a href=" http://www.facebook.com/myhindipoint " target="_blank"><b>Like myhindipoint Facebook Fan Page</b></a></div>
	<?php
		//if($sf_params->get('module').$sf_params->get('action')=='defaultindex' || $sf_params->get('module').$sf_params->get('action')=='filessearch')
		{
		echo '<div class="search">';
		echo form_tag('files/search','method=get');
		echo 'Search Files: '.input_tag('find',($sf_params->get('find') ? $sf_params->get('find'):''),'size=20');
	      
		echo submit_tag('Search','');
		echo submit_tag('Album','');
		echo submit_tag('Singer','');
		echo '</form>';
		echo '</div>';
		}
	?>
	<?php echo $sf_data->getRaw('sf_content') ?>
	<?php
	if($sf_params->get('module').$sf_params->get('action')=='categorylist')
		include(success_dir.'categoryList.php');
	if($sf_params->get('module').$sf_params->get('action')=='fileslist')
		include(success_dir.'filesList.php');
	if($sf_params->get('module').$sf_params->get('action')=='filesshow')
		include(success_dir.'fileShow.php');
	if($sf_params->get('module').$sf_params->get('action')=='fileslastadded')
		include(success_dir.'lastAdded.php');
	if($sf_params->get('module').$sf_params->get('action')=='filesfeatured')
		include(success_dir.'featured.php');
	if($sf_params->get('module').$sf_params->get('action')=='filestop')
		include(success_dir.'topDownload.php');
	if($sf_params->get('module').$sf_params->get('action')=='filessinger')
		include(success_dir.'singerByList.php');
	if($sf_params->get('module').$sf_params->get('action')=='artistlist')
		include(success_dir.'artistList.php');
	if($sf_params->get('module').$sf_params->get('action')=='categorysearch')
		include(success_dir.'categorySearch.php');
	?>

	<?php
	if($sf_params->get('module')=='default'){
           include_partial('global/featuredsinger');
		if(SETTING_LATEST_FILES !='OFF')
			include_partial('global/featured');
		if(SETTING_UPDATES=='ON')
			include_partial('global/updates');
		include(sfConfig::get('sf_upload_dir').'/advt/'.sfConfig::get('app_smallname').'/'.USERDEVICE.'_homeMiddle.php');
		echo '<div class="top21" align="center">';
		echo '<b>&nbsp;Top 21 Downloads:</b><br/>'.link_to('Today','@topFiles?type=today');
		echo ' | '.link_to('Yesterday','@topFiles?type=yesterday');
		echo ' | '.link_to('Week','@topFiles?type=week');
		echo ' | '.link_to('Month','@topFiles?type=month');
		echo ' | '.link_to('All Time','@topFiles?type=all');
		echo '</div>';
		include_partial('global/category');
		include(sfConfig::get('sf_upload_dir').'/advt/'.sfConfig::get('app_smallname').'/'.USERDEVICE.'_homeCategoryEnd.php');
	}
	/*
	* Close Mysql Connection
	*/
	closeDB();

	if(strtoupper(@SETTING_ONLINE_COUNTER)=='ON'){
		if(strtoupper(@SETTING_ONLINE_SHOW_COUNT)=='ON')
			echo "<div id='online'>Online User: ".myClass::getOnlineVisitors(1).'</div>';
		else
			myClass::getOnlineVisitors(0);
	}
	?>
<?php if($sf_params->get('module')=='default'): ?>


	<div class="catRow"><?php echo link_to('<div>A-Z Singer Collection</div>','@artistList'); ?></div>
	<div class="catRow"><?php echo link_to('<div>Last Added Files</div>','@lastAddedFiles'); ?></div>
	<div class="catRow"><?php echo link_to('<div>Top 21 Files</div>','@topFiles?type=today'); ?></div>
	<div class="catRow"><?php echo link_to('<div>Disclaimer</div>','/info/disclaimer'); ?></div>
<?php endif; ?>

<?php if($sf_params->get('module')=='default'): ?>
	<?php include(sfConfig::get('sf_upload_dir').'/advt/'.sfConfig::get('app_smallname').'/'.USERDEVICE.'_homeBottom.php'); ?>
<?php elseif($sf_params->get('module').$sf_params->get('action')=='infolatestupdates'): ?>
<?php include(sfConfig::get('sf_upload_dir').'/advt/'.sfConfig::get('app_smallname').'/'.USERDEVICE.'_allPageBottom.php'); ?>
<?php endif; ?>

<div class="f1">
	<a href="/" class="siteLink"><?=sfConfig::get('app_sitename')?></a>
</div>
</div>
</body>
</html>