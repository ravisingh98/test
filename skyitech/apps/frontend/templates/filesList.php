<h1><?php echo $catName; ?></h1>
 
<?php
	if(is_file(sfConfig::get('sf_upload_dir').'/thumb/c/'.$parent.'_'.SETTING_THUMB_CAT_TOP.'.jpg'))
		echo '<center><p class="showimage">'.image_tag(SETTING_THUMB_DOMAIN.'/c/'.$parent.'_'.SETTING_THUMB_CAT_TOP.'.jpg',('class=absmiddle')).'</p></center>';
?>

<?php
/* if files more then 0 */
if(count($filess)>0)
{
?>

<?php if($category->description): ?>
	<div class="description"><?php echo str_replace(chr(13),'<br />',$category->description); ?></div>
<?php endif; ?>



<div class="pgn" align="center">
	<?php
		$parent = $name = $type = $sort = '';
		$fname = '&fname='.myUser::slugify($catName);
		if($sf_params->get('parent'))	$parent = '&parent='.$sf_params->get('parent');
		if($sf_params->get('type'))	$type = '&type='.$sf_params->get('type');
		if($sf_params->get('sort')!='new2old')
			echo link_to('New 2 Old','@filesList?sort=new2old'.$parent.$fname.$type);
		else
			echo '<span>New 2 Old</span>';
		if($sf_params->get('sort')!='download')
			echo '&nbsp;|&nbsp;'.link_to('Popular','@filesList?sort=download'.$parent.$fname.$type);
		else
			echo '&nbsp;|&nbsp;'.'<span>Popular</span>';
		if($sf_params->get('sort')!='a2z')
			echo '&nbsp;|&nbsp;'.link_to('A to Z','@filesList?sort=a2z'.$parent.$fname.$type);
		else
			echo '&nbsp;|&nbsp;'.'<span>A to Z</span>';
		if($sf_params->get('sort')!='z2a')
			echo '&nbsp;|&nbsp;'.link_to('Z to A','@filesList?sort=z2a'.$parent.$fname.$type);
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
		$sort = '';
		if($sf_params->get('sort'))	$sort = '&sort='.$sf_params->get('sort');
		$pageNum = myUser::skyPageNavigate($totalRecords,$page,SETTING_FILES_PER_PAGE, '@filesList?'.$parent.$fname.$sort.'&page=');
		if($pageNum)
		{
	?>
		<div class="pgn" align="center">
			<?php
				myUser::getc('UGFnaW5hdGlvbg==',1);
				echo $pageNum;
				echo form_tag('files/list','method=get');
				echo input_hidden_tag('parent',$sf_params->get('parent'));
				echo input_hidden_tag('fname',myUser::slugify($catName));
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
	echo $categoryPath;
	echo link_to($catName,'@filesList?parent='.$sf_params->get('parent').'&fname='.myUser::slugify($category->category_name).'&sort='.myUser::getListOrd($category->list_ord));
?>
</div>