<tr>
	<td>Yesterday</td>
	<td><?php echo DownloadHistoryPeer::getTotalDownloads(1); ?></td>
</tr>
<tr>
	<td>Week</td>
	<td><?php echo DownloadHistoryPeer::getTotalDownloads(date('N')); ?></td>
</tr>
<tr>
	<td>Month</td>
	<td><?php echo DownloadHistoryPeer::getTotalDownloads(date('j')-1); ?></td>
</tr>
