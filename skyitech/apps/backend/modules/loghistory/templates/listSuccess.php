<?php
	use_helper('Javascript');
?>
<h1>loghistory</h1>
<?php
if($sf_params->get('site')):
	echo link_to('&raquo; clear filter','loghistory/list');
endif;
?>
<table>
<thead>
<tr>
  <th>Id</th>
  <th>Date</th>
  <th>Download</th>
</tr>
</thead>
<tbody>
<?php foreach ($log_historys as $log_history): ?>
	<tr>
    <td><?php echo $log_history->getId() ?></td>
    <td><?php echo $log_history->getDate() ?></td>
    <td><?php 
    	echo $log_history->getDownload();
    	//echo link_to($log_history->getDownload(), 'loghistory/files?day='.$log_history->getDate(), array('target'=>'_blank','title'=>'See Files Downloaded Chart'));
    	/*
				echo link_to_remote($log_history->getDownload(),array(
					'url' =>'loghistory/files?day='.$log_history->getDate(),
					'update' => 'l_'.$log_history->getId()
					));
				echo '<span id="l_'.$log_history->getId().'">';
				echo '</span>';
				*/
    	 ?></td>
	</tr>
<?php endforeach; ?>
</tbody>
</table>
<div class="pgn">
<?php
	$pageNum = myUser::pageNavigate($pager,sfConfig::get('app_webpath').'/loghistory/list/page/');
	echo $pageNum; 
?>
</div>