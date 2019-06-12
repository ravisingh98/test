<?php
if(count($log_history))
{
$file = $count = $tempPage = array();
$key =0;
$files = explode('||', $log_history->getFiles());
array_shift($files);
array_pop($files);
foreach ($files as $filehistory):
	$filetype = explode('-',$filehistory);
  $file[] = $filetype[0];
  $count[] = $filetype[1];
	$tempPage[] = "t".$filetype[0]."(".$filetype[1]."),000000,$key,1,11";
	$key++;
endforeach;
echo '<img src="http://chart.apis.google.com/chart?chbh=a,5,20&chds=0,'.(max($count)+100).'&chs=400x400&cht=bhg&chd=t:'.implode('|',$count).'&chm='.implode('|',$tempPage).'&chtt='.array_sum($count).'+files+Downloaded+'.sfConfig::get('sf_environment').'+-+'.date('d M,Y',strtotime($day)).'" alt="" />';
}
?>
