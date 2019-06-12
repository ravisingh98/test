<?php
$tDomain = SETTING_THUMB_DOMAIN;
?>
<?php
	$featured = skyMysqlQuery('select id,file_name,category_id,size,download,extension,created_at from files where status="F" order by rand() limit '.SETTING_FEATURED_FILES);

?>
<div class="featured">
  <h2>Featured Songs</h2>
<?php
	$filename2hide = sfConfig::get('app_filename2hide');
	while($files = mysql_fetch_object($featured)):
	?>
	<div class="fl">
		<?php 
			$thumbServer = 'sft'.ceil($files->id/500);
			echo '<a class="fileName" href="'.url_for('@filesShow?id='.$files->id.'&name='.myUser::slugify( myUser::fileName($files->file_name,false) )).'">';
                        echo '<div>';
	  	if(is_file(sfConfig::get('sf_upload_dir').'/thumb/'.$thumbServer.'/'.$files->id.'_'.SETTING_THUMB_FILE_LIST.'.jpg'))
	  		echo '<div> '.image_tag($tDomain.'/'.$thumbServer.'/'.$files->id.'_'.SETTING_THUMB_FILE_LIST.'.jpg',array('title'=>myUser::fileName($files->file_name,false))).'</div>';
                      elseif(is_file(sfConfig::get('sf_upload_dir').'/thumb/c/'.$files->category_id.'_2.jpg'))
	  		echo '<div>'.image_tag(SETTING_THUMB_DOMAIN.'/c/'.$files->category_id.'_2.jpg',array()).'</div>';
    	echo '<div>'.str_replace('_',' ',myUser::fileName($files->file_name))."<br/>";	      
             echo "<span>".myClass::formatsize($files->size)."</span> | ";
        echo '<span>'.$files->download.' Hits</span>';
			 			
			echo '</div>';
			echo '</div></a>'
			?>
  </div>
<?php endwhile; ?>
  <div>
  	<?php echo link_to('[More...]','@featured', array('class'=>'')) ?>
  </div>
</div>
<div class="devider">&nbsp;</div>