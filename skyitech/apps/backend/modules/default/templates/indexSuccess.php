<?php use_helper('Javascript') ?>
<?php echo '<h1>Welcome '.$sf_user->getAttribute('ADMINUSERNAME','','admin').'</h1>'; ?>
<table>
<tbody>
<tr>
	<td>Server Time</td>
	<td><?php echo date('d M, Y h:i:s',time()); ?></td>
</tr>
<tr>
	<td>Server IP</td>
	<td><?php echo $_SERVER['SERVER_ADDR']; ?></td>
</tr>
<tr>
	<td>Your PC IP</td>
	<td><?php echo $_SERVER['REMOTE_ADDR']; ?></td>
</tr>
<tr>
	<td>Downloads Today</td>
	<td><?php
	$con = Propel::getConnection();
  $sql = "SELECT SUM(today) FROM files";
  $rs = $con->executeQuery($sql, ResultSet::FETCHMODE_NUM);
  $rs->next();
  echo $rs->getString(1);
	?></td>
</tr>
</tbody>
</table>


<?php 
echo '<table><tr><td width=400 valign=top>';
if(count($log_historys)){
$date = $host = $download = $hostDownload = array();
echo '<table>';
echo '<tr><th>Date</th><th>Download</th></tr>';
foreach ($log_historys as $log_history):
	echo '<tr><td>';
	echo date('d.M.Y',strtotime($log_history->getDate()));
	echo '</td><td>';
	echo $log_history->getDownload();
	echo '</td></tr>';
endforeach;
	echo '</table>';
	echo link_to('More...','loghistory/list');
}
echo '</td><td>';
include('notesSuccess.php');
echo '</td></tr></table>';

?>