<?php
$tDomain = SETTING_THUMB_DOMAIN;
?>
<h2><?php echo $catName; ?></h2>
<div class="dtype">
	<small>
	<?php
		$find = $ext = $sort = $cat = '';
		if($sf_params->get('find'))	$find = '&find='.$sf_params->get('find');
		if($sf_params->get('ext'))	$ext = '&ext='.$sf_params->get('ext');
		if($sf_params->get('cat'))	$cat = '&cat='.$sf_params->get('cat');
		if($sf_params->get('sort')!='new2old' && $sf_params->get('sort'))
			echo link_to('New 2 Old','files/search?sort=new2old'.$find.$ext.$cat);
		else
			echo '<span>New 2 Old</span>';

		if($sf_params->get('sort')!='download')
			echo '&nbsp;|&nbsp;'.link_to('Popular','files/search?sort=download'.$find.$ext.$cat);
		else
			echo '&nbsp;|&nbsp;'.'<span>Popular</span>';
		if($sf_params->get('sort')!='a2z')
			echo '&nbsp;|&nbsp;'.link_to('A to Z','files/search?sort=a2z'.$find.$ext.$cat);
		else
			echo '&nbsp;|&nbsp;'.'<span>A to Z</span>';
		if($sf_params->get('sort')!='z2a')
			echo '&nbsp;|&nbsp;'.link_to('Z to A','files/search?sort=z2a'.$find.$ext.$cat);
		else
			echo '&nbsp;|&nbsp;'.'<span>Z to A</span>';
	?>
	</small>
</div>

<?php myUser::getc('RmlsZSBMaXN0',1); ?>
<?php
	$class = 'even';
	$cnt=0;
	$filename2hide = sfConfig::get('app_filename2hide');
	$adAfterFiles = (SETTING_ADVT_AFTER_EACH_FILES ? SETTING_ADVT_AFTER_EACH_FILES : 3);
	while($files = mysql_fetch_object($filess)):
		$class = myClass::getOddEven($class);
		$cnt++;
	?>
	<div class="fl <?php echo $class?>">
		<?php 
    	echo '<a href="'.url_for('@filesShow?id='.$files->id.'&name='.myUser::slugify(substr($files->file_name,0,strpos($files->file_name,sfConfig::get('app_filename2hide'))))).'" class="fileName">';
			$thumbServer = 'sft'.ceil($files->id/500);
			echo '<div>';
	  	if(is_file(sfConfig::get('sf_upload_dir').'/thumb/'.$thumbServer.'/'.$files->id.'_'.SETTING_THUMB_FILE_LIST.'.jpg'))
	  		echo '<div>'.image_tag($tDomain.'/'.$thumbServer.'/'.$files->id.'_'.SETTING_THUMB_FILE_LIST.'.jpg',array()).'</div>';
//	  	else
//    		echo '<div>'.image_tag($files->extension.'.png',array()).'</div>';
    	$parentsArray = explode('||',$files->parentsarray);
    	echo '<div>'.str_replace('_',' ',myUser::fileName($files->file_name)).' <span class="mc">('.@$parentsArray[2].')</span>'.'<br/>';
    	echo '<span class="alb">'.$files->category_name.'</span>'.'<br/>';
    	if($files->singer)
    		echo '<span class="ar">'.str_replace(',',', ',substr($files->singer,1,-1)).'</span><br/>';
			if(!in_array($files->extension,array('JPG','GIF','PNG')))
				echo '<span>'.myClass::formatsize($files->size).'</span> | ';
				echo '<span>'.$files->download.' Hits</span>';
			echo '</div></div></a>';
		?>
  </div>
<?php endwhile; ?>
<?php  myUser::getc('RmlsZSBMaXN0IENvbXBsZXRl',1); ?>

	<?php
		if($sf_params->get('sort'))	$sort = '&sort='.$sf_params->get('sort');
		$pageNum = myUser::skyPageNavigate($totalRecords,$page,SETTING_FILES_PER_PAGE,'files/search?'.$find.$sort.$ext.$cat.'&page=');
		if($pageNum){
	?>
		<div class="pgn">
			<?php
				myUser::getc('UGFnaW5hdGlvbg==',1);
				echo $pageNum;
				echo form_tag('files/search','method=get');
				echo input_hidden_tag('find',($sf_params->get('find')));
				echo input_hidden_tag('ext',$sf_params->get('ext'));
				echo input_hidden_tag('cat',$sf_params->get('cat'));
				echo input_hidden_tag('sort',$sf_params->get('sort'));
				echo 'Jump to Page '.input_tag('page','','size=3');
				echo submit_tag('Go&raquo;','');
				echo '</form>';
			?>
		</div>
	<?php } ?>
<div class="path"><b><?php 
	echo link_to('Home',sfConfig::get('app_webpath')).' &raquo; ';
?></b>
</div>