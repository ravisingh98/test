<?php
	$updates = skyMysqlQuery("select id, description from updates where status='A' order by created_at desc");
	
?>
<div class="updates">
  <h2>Latest Updates</h2>
<?php
$find = array(str_ireplace('http://m.','http://www.',sfConfig::get('app_webpath')));
$replace = array(sfConfig::get('app_webpath'));
	while($value = mysql_fetch_object($updates)): ?>
  <div>
  	<?php /*echo link_to($value->getDescription(),$value->getLink(), array('class'=>'')) */?>
  	<?php echo str_replace($find,$replace,$value->description) ?>
  </div>
<?php endwhile;?>
  <div>
  	<?php echo link_to('[More Updates...]','@latestUpdates', array('class'=>'')) ?>
  </div>
</div>
<div class="devider">&nbsp;</div>
