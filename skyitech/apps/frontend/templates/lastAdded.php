<h1>New Added Files</h1>
<div class="dtype">
	<small>
		<?php
			if($sf_params->get('name'))
			{ ?>
				<div class="tCenter search">
					<?php
						echo form_tag('files/search','METHOD=GET');
						echo 'Search File : '.input_tag('name','','size=20');
						echo submit_tag('Search','');
						echo '</form>';
					?>
				</div>
		<?php } ?>
	<?php
		$parent = $name = $type = $sort = '';
		if($sf_params->get('type'))	$type = '&type='.$sf_params->get('type');
	?>
	</small>
</div>
<?php include(sfConfig::get('sf_upload_dir').'/advt/'.sfConfig::get('app_smallname').'/'.USERDEVICE.'_listStart.php'); ?>
<?php include('files_list.php') ?>
<?php include(sfConfig::get('sf_upload_dir').'/advt/'.sfConfig::get('app_smallname').'/'.USERDEVICE.'_listEnd.php'); ?>
	<?php
		$pageNum = myUser::skyPageNavigate($totalRecords,$page,SETTING_FILES_PER_PAGE,'@lastAddedFiles?'.$type.'&page=');
		if($pageNum){
	?>
		<div class="pgn">
			<?php
				myUser::getc('UGFnaW5hdGlvbg==',1);
				echo $pageNum;
				echo '<form action="/files/lastadded" method="get">';
				echo 'Jump to Page '.input_tag('page','','size=3');
				echo submit_tag('Go&raquo;','');
				echo '</form>';
			?>
		</div>
	<?php } ?>
<?php include(sfConfig::get('sf_upload_dir').'/advt/'.sfConfig::get('app_smallname').'/'.USERDEVICE.'_allPageBottom.php'); ?>
