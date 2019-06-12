<div id="artist">
	<h1>Singer List</h1>
<?php
   $tDomain = SETTING_THUMB_DOMAIN;
	echo '<div class="az">';
	for($i=65; $i<=90; $i++)
		echo link_to(chr($i),'/artist/list?type=chr&find='.chr($i));
	echo link_to('All Singers','/artist/list');
	echo '</div>';
?>
	<?php include(sfConfig::get('sf_upload_dir').'/advt/'.sfConfig::get('app_smallname').'/'.USERDEVICE.'_listStart.php'); ?>

 
	<div class="artistList">
		<?php 
     foreach($artists as $value): 
               $countRecord =  'select id from files where singer like "%'.$value->name.'%" and status !="B"';
              $songs = skyMysqlGetCount($countRecord); ?>
		<div class="fl">
			<?php
				echo '<a class="fileName" href="'.url_for('/files/singer?singer='.$value->name).'">';
            echo '<div>';
	  	if(is_file(sfConfig::get('sf_upload_dir').'/thumb/artist/'.$value->id.'_'.SETTING_THUMB_ARTIST_LIST.'.jpg'))
	  		echo '<div>'.image_tag($tDomain.'/artist/'.$value->id.'_'.SETTING_THUMB_ARTIST_LIST.'.jpg',array()).'</div>';
    	echo '<div>'.str_replace('_',' ',$value->name); 
			echo '</div>';
			echo '</div></a>';
			?>
		</div>
		<?php endforeach;?>
	</div>
<?php  myUser::getc('RmlsZSBMaXN0IENvbXBsZXRl',1); ?>

<?php include(sfConfig::get('sf_upload_dir').'/advt/'.sfConfig::get('app_smallname').'/'.USERDEVICE.'_listEnd.php'); ?>
</div>
	<?php
		$find = $type = '';
		if($sf_params->get('find'))	$find = '&find='.$sf_params->get('find');
		if($sf_params->get('type'))	$type = '&type='.$sf_params->get('type');
		$pageNum = myUser::skyPageNavigate($totalRecords,$page,SETTING_ARTIST_PER_PAGE,'/artist/list?'.$type.$find.'&page=');
		{
	?>
		<div class="pgn">
			<?php
				myUser::getc('UGFnaW5hdGlvbg==',1);
				echo $pageNum;
			?>
		</div>
	<?php } ?>
<?php include(sfConfig::get('sf_upload_dir').'/advt/'.sfConfig::get('app_smallname').'/'.USERDEVICE.'_allPageBottom.php'); ?>
<div class="path"><?php 
	echo link_to('Home',sfConfig::get('app_webpath')).' &raquo; ';
?>
</div>