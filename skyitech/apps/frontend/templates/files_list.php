<?php
$tDomain = SETTING_THUMB_DOMAIN;
?>
<?php myUser::getc('RmlsZSBMaXN0',1); ?> 
<?php
	$class = 'odd';
	$cnt=0;
	$filename2hide = sfConfig::get('app_filename2hide');
	$adAfterFiles = (SETTING_ADVT_AFTER_EACH_FILES ? SETTING_ADVT_AFTER_EACH_FILES : 3);
	foreach($filess as $files):
		$class = myClass::getOddEven($class);
		$cnt++;
	?>
        <div class="fl <?php echo $class?>">	

                <?php 
			$thumbServer = 'sft'.ceil($files->id/500);
			echo '<a class="fileName" href="'.url_for('@filesShow?id='.$files->id.'&name='.myUser::slugify( myUser::fileName($files->file_name,false) )).'">'; 
echo '<div>';
	    	
if(is_file(sfConfig::get('sf_upload_dir').'/thumb/'.$thumbServer.'/'.$files->id.'_'.SETTING_THUMB_FILE_LIST.'.jpg'))
	  		echo '<div> '.image_tag($tDomain.'/'.$thumbServer.'/'.$files->id.'_'.SETTING_THUMB_FILE_LIST.'.jpg',array('title'=>myUser::fileName($files->file_name,false))).'</div>';
                      elseif(is_file(sfConfig::get('sf_upload_dir').'/thumb/c/'.$files->category_id.'_'.SETTING_THUMB_FILE_LIST.'.jpg'))
	  		echo '<div>'.image_tag(SETTING_THUMB_DOMAIN.'/c/'.$files->category_id.'_'.SETTING_THUMB_FILE_LIST.'.jpg',array()).'</div>';
                        else
          	   	echo image_tag($files->extension.'.png',array());
    		echo '</div>';
    	                echo '<div>'.str_replace('_',' ',myUser::fileName($files->file_name))."<br/>";	
	if($files->singer)
    		echo '<span class="arn">Singer: </span> <span class="ar"> '.str_replace(',',', ',substr($files->singer,1,-1)).'</span><br/>';
                           if($sf_params->get('action')=='lastadded')
				echo '<span>'.myClass::TimeAgo(strtotime($files->created_at)).'</span><br/>';
				if(!in_array($files->extension,array('JPG','GIF','PNG')))
			 echo "<span>".myClass::formatsize($files->size)."</span> | ";
			echo '<span>'.$files->download.' Hits</span><br/>';
			 
			echo '</div></a>'
			?>
  </div>
  <?php if( $cnt % $adAfterFiles == 0): ?>
	<div class="fl odd"><?php include(sfConfig::get('sf_upload_dir').'/advt/'.sfConfig::get('app_smallname').'/'.USERDEVICE.'_betweenFile.php'); ?></div>
  <?php endif; ?>
<?php endforeach; ?>
<?php  myUser::getc('RmlsZSBMaXN0IENvbXBsZXRl',1); ?>