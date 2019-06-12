<?php use_helper('Javascript') ?>
<table>
	<tr>
		<td width="10%"><h2>Artist List: </h2></td>
	<td width="30%">
		<div>
		<?php
			$sort = $searchText = '';
			if($sf_params->get('searchText'))	$searchText = '&searchText='.$sf_params->get('searchText');
			if($sf_params->get('sort'))	$sort = '&sort='.$sf_params->get('sort');
	
			if($sf_params->get('sort') && $sf_params->get('sort')!='n')
				echo link_to('New 2 Old','artist/list?sort=n'.$searchText).' | ';
			else
				echo '<b>New 2 Old</b>'.' | ';
			if($sf_params->get('sort')!='a2z')
				echo link_to('A to Z','artist/list?sort=a2z'.$searchText).' | ';
			else
				echo '<b>A to Z</b>'.' | ';
			if($sf_params->get('sort')!='z2a')
				echo link_to('Z to A','artist/list?sort=z2a'.$searchText).' | ';
			else
				echo '<b>Z to A</b>'.' | <br />';
		?>
		</div>
	</td>
	<td width="30%">
		<?php 
			echo form_tag('artist/list','METHOD=GET');
		  echo 'Search: ';
		  echo input_tag('searchText', $sf_params->get('searchText'),'size=12');
			echo submit_tag('Id',array('name'=>'search'));
			echo submit_tag('Name',array('name'=>'search'));			echo '</form>';
			if($sf_params->get('search'))
				echo '<div class="msg info">Search <b>'.$sf_params->get('search').'</b> like "<b>'.$sf_params->get('searchText').'</b>" in <b>Artist</b></div>';
		?>
	</td>

		<td width="30%"><?php include_partial('global/topAction'); ?></td>
	</tr>
</table>
<table>
<thead>
<tr>
	<th width="5%">Id</th>
	<th width="5%">thumb</th>
	<th width="75%">Artist Name</th>
	<th width="5%">Status</th>
	<th width="10%">Action</th>
</tr>
</thead>
<tbody id="order">
<?php foreach ($artists as $artist): ?>
<tr class="sortable" id="item_<?php echo $artist->getId();?>">
	<td><?php echo $artist->getId() ?></td>
	<td><?php 
			if(is_file(sfConfig::get('sf_upload_dir').'/thumb/singer/'.myUser::convert_name($artist->getName()).'_1.jpg'))
					echo image_tag(sfConfig::get('app_upload_dir').'/thumb/singer/'.myUser::convert_name($artist->getName()).'_1.jpg');
		?></td>
	<td>
		<?php echo $artist->getName();?>
	</td>
    <td id="active_<?php echo $artist->getId(); ?>">
    	<?php
				if($artist->getStatus() == 'B'){
					$lnkName = '<span class="red">Block</span>';
					$status = 'B';
				}elseif($artist->getStatus() == 'F'){
					$lnkName = '<span class="bluebg">Featured</span>';
					$status = 'F';
				}else{
					$lnkName = 'Active';
					$status = 'A';
				}
				echo link_to_remote($lnkName,	array('update' => 'active_'.$artist->getId(),'url' => '/artist/activation?id='.$artist->getId().'&status='.$status));
				//echo ($artist->getStatus()=='A' ? 'Active' : 'Deactive');
    	?>
    </td>
	<td class="action">
		<?php include_partial('global/myaction',array('id' => $artist->getId())); ?>
	</td>
   </tr>
<?php endforeach; ?>
</tbody>
</table>
<?php $sort = $name = $searchText = $search = '';
		if($sf_params->get('sort'))	$sort = '/sort/'.$sf_params->get('sort');
		if($sf_params->get('searchText'))	$searchText = '/searchText/'.$sf_params->get('searchText');
		if($sf_params->get('search'))	$search = '/search/'.$sf_params->get('search');
		$pageNum = myUser::pageNavigate($pager,sfConfig::get('app_webpath').'/artist/list'.$sort.$searchText.$search.'/page/');?>
<?php
	if($pageNum){
?>
	<div class="pgn">
		<?php echo $pageNum; ?>
		<?php
			echo form_tag('artist/list','METHOD=GET');
				echo input_hidden_tag('sort',$sf_params->get('sort'));
				echo input_hidden_tag('searchText',$sf_params->get('searchText'));
				echo input_hidden_tag('search',$sf_params->get('search'));
			echo 'Jump to Page '.input_tag('page','','size=3');
			echo submit_tag('Go&raquo;','');
			echo '</form>';
		?>
	</div>
<?php } ?>
