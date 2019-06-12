<?php
	$featured = skyMysqlQuery('select * from artist where status="F" order by rand() limit 3');
?>
<div class="featured">
  <h2>Featured Singer</h2>
<?php
	$class = 'even';
	$cnt=0;
	while($files = mysql_fetch_object($featured)):
		$class = myClass::getOddEven($class);
		$cnt++;
	?>
	<div class="fl <?php echo $class?>">
		<?php 
			echo '<a class="fileName" href="'.url_for('/files/singer?singer='.$files->name).'"><div>';
					if(is_file(sfConfig::get('sf_upload_dir').'/thumb/singer/'.myUser::convert_name($files->name).'_'.SETTING_THUMB_FILE_LIST.'.jpg'))
		echo '<div>'.image_tag(SETTING_THUMB_DOMAIN.'/singer/'.myUser::convert_name($files->name).'_'.SETTING_THUMB_FILE_LIST.'.jpg',('class=absmiddle')).'</div>';
			echo '<div>'.str_replace('_',' ',myUser::fileName($files->name));
			echo '</div>';
			echo '</div></a>';
			?>
        </div>
<?php endwhile; ?>
  <div></div></div>
