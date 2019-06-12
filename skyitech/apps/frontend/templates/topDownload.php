<?php
$tDomain = SETTING_THUMB_DOMAIN;
?>
<?php if($sf_params->get('type')): ?>
<h1><?php
		if($sf_params->get('type')=='today' ||  $sf_params->get('type')=='yesterday')
			echo $sf_params->get('type').'\'s Most Popular Downloads';
		if($sf_params->get('type')=='week' ||  $sf_params->get('type')=='month')
			echo 'This '. $sf_params->get('type') . '\'s Most Popular Downloads';
		if($sf_params->get('type')=='all')
			echo 'All Time\'s Most Popular Downloads';
	?></h1>
<div class="dtype">
<?php
if($sf_params->get('type')=='today')
	echo '<span>Today</span>';
else
	echo link_to('Today','@topFiles?type=today');
echo ' | ';
if($sf_params->get('type')=='yesterday')
	echo '<span>Yesterday</span>';
else
	echo link_to('Yesterday','@topFiles?type=yesterday');
echo ' | ';
if($sf_params->get('type')=='week')
	echo '<span>Week</span>';
else
	echo link_to('This Week','@topFiles?type=week');
echo ' | ';
if($sf_params->get('type')=='month')
	echo '<span>Month</span>';
else
	echo link_to('This Month','@topFiles?type=month');
echo ' | ';
if($sf_params->get('type')=='all')
	echo '<span>All Time</span>';
else
	echo link_to('All Time','@topFiles?type=all');
?>
</div>
<?php include(sfConfig::get('sf_upload_dir').'/advt/'.sfConfig::get('app_smallname').'/'.USERDEVICE.'_listStart.php'); ?>
<div class="devider">&nbsp;</div>
	<?php
	$filename2hide = sfConfig::get('app_filename2hide');
	$class = 'even';
	foreach($filess as $files):
		$class = myClass::getOddEven($class);
	?>
		<div class="fl <?php echo $class?>">
	    <?php 
				$thumbServer = 'sft'.ceil($files->id/500);
				echo '<a class="fileName" href="'.url_for('@filesShow?id='.$files->id.'&name='.substr($files->file_name,0,strpos($files->file_name,sfConfig::get('app_filename2hide')))).'">';
				echo '<div>';
	    	
if(is_file(sfConfig::get('sf_upload_dir').'/thumb/'.$thumbServer.'/'.$files->id.'_'.SETTING_THUMB_FILE_LIST.'.jpg'))
	  		echo '<div> '.image_tag($tDomain.'/'.$thumbServer.'/'.$files->id.'_'.SETTING_THUMB_FILE_LIST.'.jpg',array('title'=>myUser::fileName($files->file_name,false))).'</div>';
                      elseif(is_file(sfConfig::get('sf_upload_dir').'/thumb/c/'.$files->category_id.'_'.SETTING_THUMB_FILE_LIST.'.jpg'))
	  		echo '<div>'.image_tag(SETTING_THUMB_DOMAIN.'/c/'.$files->category_id.'_'.SETTING_THUMB_FILE_LIST.'.jpg',array()).'</div>';
                        else
          	   	echo image_tag($files->extension.'.png',array());
    		echo '</div>';


    	$parentsArray = explode('||',$files->parentsarray);
    	echo '<div>'.str_replace('_',' ',myUser::fileName($files->file_name)).' <span class="aln">('.@$parentsArray[2].')</span>'.'<br/>';
    	echo '<span class="mc">Album: </span><span class="alb"> '.$files->category_name.'</span>'.'<br/>';
    	if($files->singer)
    		echo '<span class="mc">Artist: </span><span class="alb"> '.str_replace(',',', ',substr($files->singer,1,-1)).'</span><br/>';
			if(!in_array($files->extension,array('JPG','PNG')))
			 echo "<span>".myClass::formatsize($files->size)."</span> | ";
				echo '<span>'.$files->download.' Hits</span>';
    		echo '</div></a>';
	    	?>
	  </div>
<?php
	endforeach;
?>
<?php endif; ?>
<?php include(sfConfig::get('sf_upload_dir').'/advt/'.sfConfig::get('app_smallname').'/'.USERDEVICE.'_allPageBottom.php'); ?>

