<?php
$tDomain = SETTING_THUMB_DOMAIN;
?><h1><?php echo 'Free Download '. str_replace('_',' ',myUser::fileName($files->file_name)); ?></h1>
<div class="fshow">
<?php if($files->description): ?>
	<div class="filedescription"><?php echo str_replace(chr(13),'<br />',$files->description); ?></div>
	<div class="devider">&nbsp;</div>
<?php endif; ?>
<?php
		$thumbServer = 'sft'.ceil($files->id/500);

		if(is_file(sfConfig::get('sf_upload_dir').'/thumb/'.$thumbServer.'/'.$files->id.'_'.SETTING_THUMB_FILE_TOP.'.jpg')){
				echo '<p class="showimage">';
				echo image_tag($tDomain.'/'.$thumbServer.'/'.$files->id.'_'.SETTING_THUMB_FILE_TOP.'.jpg',array('title'=>myUser::fileName($files->file_name,false), 'class'=>'absmiddle'));
				echo '</p>';
                                    }
	  		   elseif(is_file(sfConfig::get('sf_upload_dir').'/thumb/c/'.$files->category_id.'_'.SETTING_THUMB_FILE_TOP.'.jpg'))
	  		echo '<div>'.image_tag(SETTING_THUMB_DOMAIN.'/c/'.$files->category_id.'_'.SETTING_THUMB_FILE_TOP.'.jpg',array()).'</div>';
	  	

		
?>
<?php myUser::getc('RG93bmxvYWQgTGluaw==',1);?>
<div class="fInfo">
<?php $dataServer = 'sfd'.ceil($files->id/500); ?>
<?php if($files->extension=='MP3' &&  $files->size < SETTING_MP3_PLAY_LIMIT*1024*1024 ): ?>
<div>
<object type="application/x-shockwave-flash" data="/player.swf" width="200" height="20">
    <param name="movie" value="/player.swf" />
    <param name="bgcolor" value="#ffffff" />
    <param name="FlashVars" value="mp3=<?php echo url_for('files/download?id='.$files->id) ?>&amp;volume=75&amp;showstop=1&amp;showvolume=1" />
</object>
</div>	
<?php endif; ?>
	<?php if($files->extension=='JPG' || $files->extension=='PNG' || $files->extension=='JPEG'): ?>
		<?php include(sfConfig::get('sf_upload_dir').'/advt/'.sfConfig::get('app_smallname').'/'.USERDEVICE.'_beforeDownload.php'); ?>
		<div class="fhd">Select Your Screen Size</div><div class="fi">
			<?php
			$sizes = explode(",",SETTING_WALLPAPER_SIZE);
			foreach($sizes as $k => $s){
				if($s=="")
					echo "<br/>";
				else{
					echo link_to($s,'files/download?id='.$files->id.'&size='.$s,array('class'=>'dwnLink','rel'=>'nofollow'));
					if($k!=count($sizes)-1)
						echo ", ";
				}
			}
			?>
		</div>
		<?php include(sfConfig::get('sf_upload_dir').'/advt/'.sfConfig::get('app_smallname').'/'.USERDEVICE.'_afterDownload.php'); ?>
	<?php else: ?>
		<?php 
		include(sfConfig::get('sf_upload_dir').'/advt/'.sfConfig::get('app_smallname').'/'.USERDEVICE.'_beforeDownload.php');
		$multi = array();
		if($files->extension=='MP3'){
		if(is_file(sfConfig::get('sf_upload_dir').'/files/'.$dataServer.'/'.$files->id.'/'.myClass::MultiFileName($files->file_name,'64')))
			$multi[]=64;
		if(is_file(sfConfig::get('sf_upload_dir').'/files/'.$dataServer.'/'.$files->id.'/'.myClass::MultiFileName($files->file_name,'192')))
			$multi[]=192;
		if(is_file(sfConfig::get('sf_upload_dir').'/files/'.$dataServer.'/'.$files->id.'/'.myClass::MultiFileName($files->file_name,'320')))
			$multi[]=320;
		}
		if(count($multi)==0)
			echo '<div class="fhd">Download</div><div class="fi">'.link_to( str_replace('_',' ',myUser::fileName($files->file_name)) ,'files/download?id='.$files->id,array('class'=>'dwnLink','rel'=>'nofollow')).'</div>';
		else{
			echo '<div class="fhd">Download</div><div class="fi"><b>'.str_replace('_',' ',myUser::fileName($files->file_name)).'</b>';
			if(in_array(64,$multi)){
				echo '<br/>'.
					link_to( '64 KBPS'.' - '.myClass::formatSize(filesize(sfConfig::get('sf_upload_dir').'/files/'.$dataServer.'/'.$files->id.'/'.myClass::MultiFileName($files->file_name,'64')))
					 ,'files/download?type=64&id='.$files->id,array('class'=>'dwnLink1','rel'=>'nofollow'));
			}
			echo '<br/>'.link_to( '128 KBPS'.' - '.myClass::formatSize($files->size) ,'files/download?type=128&id='.$files->id,array('class'=>'dwnLink2','rel'=>'nofollow'));
			
			if(in_array(192,$multi)){
				echo '<br/>'.link_to( '192 KBPS'.' - '.myClass::formatSize(filesize(sfConfig::get('sf_upload_dir').'/files/'.$dataServer.'/'.$files->id.'/'.myClass::MultiFileName($files->file_name,'192'))) ,'files/download?type=192&id='.$files->id,array('class'=>'dwnLink3','rel'=>'nofollow'));
			}
			if(in_array(320,$multi)){
				echo '<br/>'.link_to( '320 KBPS'.' - '.myClass::formatSize(filesize(sfConfig::get('sf_upload_dir').'/files/'.$dataServer.'/'.$files->id.'/'.myClass::MultiFileName($files->file_name,'320'))) ,'files/download?type=320&id='.$files->id,array('class'=>'dwnLink4','rel'=>'nofollow'));
			}
			echo '</div>';
		}

		include(sfConfig::get('sf_upload_dir').'/advt/'.sfConfig::get('app_smallname').'/'.USERDEVICE.'_afterDownload.php');
		if(count($multi)==0)
			echo '<div class="fhd">Size of file</div><div class="fi">'.myClass::formatSize($files->size).'</div>';
		echo '<div class="fhd">Hits</div><div class="fi">'.($files->download).'</div>';
		?>
	<?php endif; ?>
	<?php
	if($files->singer){
		echo '<div class="fhd">Singer</div>';
		echo '<div class="fi">';
		foreach (explode(',',substr($files->singer,1,-1)) as $singer)
			if($singer)
				echo link_to($singer,'files/singer?singer='.$singer).'<br/>';
		echo '</div>';
	}
		echo '<div class="fhd">Category</div>';
		echo '<div class="fi">'.link_to($catName,'@filesList?parent='.$files->category_id.'&fname='.myUser::slugify($catName).'&sort='.myUser::getListOrd($list_ord)).'</div>';
	?>
</div>
</div>

<?php include(sfConfig::get('sf_upload_dir').'/advt/'.sfConfig::get('app_smallname').'/'.USERDEVICE.'_RelatedItemsBefore.php'); ?>
<div class="randomFile">
<h3>Related Files</h3>
<?php
$sql = "select * from files where category_id=".$files->category_id.' order by rand() limit 3';
$randomfiles = skyMysqlQuery($sql);
while($file = mysql_fetch_object($randomfiles))
{
	?>
	<div class="fl odd">
		<?php 
			$thumbServer = 'sft'.ceil($file->id/500);
			echo '<a class="fileName" href="'.url_for('@filesShow?id='.$file->id.'&name='.myUser::slugify( myUser::fileName($file->file_name,false) )).'">';
			echo '<div>';

	if(is_file(sfConfig::get('sf_upload_dir').'/thumb/'.$thumbServer.'/'.$file->id.'_'.SETTING_THUMB_FILE_LIST.'.jpg'))
		  		echo '<div>'.image_tag($tDomain.'/'.$thumbServer.'/'.$file->id.'_'.SETTING_THUMB_FILE_LIST.'.jpg',array('title'=>myUser::fileName($file->file_name,false))).'</div>';

                      elseif(is_file(sfConfig::get('sf_upload_dir').'/thumb/c/'.$files->category_id.'_2.jpg'))
	  		echo '<div>'.image_tag(SETTING_THUMB_DOMAIN.'/c/'.$files->category_id.'_2.jpg',array()).'</div>';
	  	

	    	echo '<div>'.str_replace('_',' ',myUser::fileName($file->file_name))."<br />";
    	if($file->singer)
    		echo '<span class="ar">'.str_replace(',',', ',substr($file->singer,1,-1)).'</span><br/>';
			if(!in_array($file->extension,array('JPG','GIF','PNG')))
			 echo "<span>[".myClass::formatsize($file->size)."]</span> | ";
			echo '<span>'.$file->download.' Hits</span>';
			echo '</div>';
			echo '</div></a>';
			?>
  </div>
<?php
}
?>
</div>

<?php include(sfConfig::get('sf_upload_dir').'/advt/'.sfConfig::get('app_smallname').'/'.USERDEVICE.'_allPageBottom.php'); ?>
<div class="path"><?php 
	if(isset($_SERVER['HTTP_REFERER']) && strstr($_SERVER['HTTP_REFERER'],strtolower(sfConfig::get('app_sitename'))))
		echo '&laquo; '.link_to('Go Back',$_SERVER['HTTP_REFERER']).'<br />';
	echo link_to('Home',sfConfig::get('app_webpath')).' &raquo; ';
	echo $categoryPath;
	echo link_to($catName,'@filesList?parent='.$files->category_id.'&fname='.myUser::slugify($catName).'&sort='.myUser::getListOrd($list_ord));
?>
</div>
<div class="devider">&nbsp;</div>
<span style="background:blue; color:white; border-radius:5px;"><b> google Tags:  </b></span>
<?php echo str_replace("(SKYMp3.In).mp3" , "" , "$files->file_name");?> Mp3 Songs Download, iTunes Rip Mp3 Songs Download, <?php echo str_replace("(SKYMp3.In).mp3" , "" , "$files->file_name");?> 128 Kbps Mp3 Songs Free Download, All Mp3 Songs Download, Full Songs Download