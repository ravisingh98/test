<?php use_helper('Javascript') ?>
<h2>Latest Updates</h2>
<br />
<div class="updates">
<?php
$find = array(str_ireplace('http://m.','http://www.',sfConfig::get('app_webpath')));
$replace = array(sfConfig::get('app_webpath'));
while($value = mysql_fetch_object($updatess)):
?>
  <div>
  	<?php echo date('d M:',strtotime($value->created_at)) ?>
  	<?php echo str_replace($find,$replace,$value->description); ?>
  </div>
<?php endwhile;?>
</div>
	<?php
		$pageNum = myUser::skyPageNavigate($totalRecords,$page,SETTING_UPDATES_PER_PAGE, '@latestUpdates?'.'&page=');
		if($pageNum)
		{
	?>
		<div class="pgn">
			<?php
				myUser::getc('UGFnaW5hdGlvbg==',1);
				echo $pageNum;
				echo form_tag('info/latestupdates','method=get');
				echo 'Jump to Page '.input_tag('page','','size=3');
				echo submit_tag('Go&raquo;','');
				echo '</form>';
			?>
		</div>
	<?php } ?>
