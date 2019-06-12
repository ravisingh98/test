<?php use_helper('Javascript') ?>
<table>
	<tr>
		<td><h1>Category List<br/><u><i><?php echo (@$pager ? @$pager->getNbResults().' Results' : ''); ?></i> </u></h1></td>
		<td><?php include_partial('global/topAction',array('parent' => $parent)); ?></td>
	</tr>
</table>

<?php if(isset($parent) && $parent)
	include_partial('global/catpath',array('parents' => $parentCategory,'self'=>true));
?>
<table>
<thead>
<tr>
	<?php if($setOrderOnOff == 'show'): ?>
	<th width=10>Order</th>
	<?php endif; ?>
	<th width=50>Id</th>
	<th width=50>thumb</th>
	<th>Category name</th>
	<?php if($fullInfoOnOff=='show'): ?>
	<th>Title</th>
	<th>Description</th>
	<th>Child</th>
	<th>Order</th>
	<?php endif; ?>
	<th width=50>Status</th>
	<th width=240>Action</th>
</tr>
</thead>
<tbody id="order">
<?php foreach ($categorys as $category): ?>
<tr class="sortable <?php echo $category->getChild(); ?>" id="item_<?php echo $category->getId();?>">
	<?php if($setOrderOnOff == 'show'): ?>
	<td class="imgHandler"><?php echo image_tag('updown.png'); ?></td>
	<?php endif; ?>
	<td><?php echo link_to($category->getId(), 'category/show?id='.$category->getId()) ?></td>
	<td><?php 
			if(is_file(sfConfig::get('sf_upload_dir').'/thumb/c/'.$category->getId().'_1.jpg'))
					echo image_tag(sfConfig::get('app_upload_dir').'/thumb/c/'.$category->getId().'_1.jpg');
		?></td>
	<td>
		<?php
		if($category->getChild()=='D')
			echo link_to($category->getCategoryName(),'category/list?parent='.$category->getId());
		if($category->getChild()=='F')
			echo link_to($category->getCategoryName(),'files/list?cid='.$category->getId());
		if($category->getChild()=='U')
			echo '&nbsp;'.link_to($category->getCategoryName().image_tag('url.jpg'),$category->getUrl(),array('target'=>'_blank'));
		if($category->getChild()=='N'){
			echo $category->getCategoryName();
			//echo '&nbsp;-&nbsp;'.link_to('List','files/list?cid='.$category->getId());
		}
	 	?>
		<?php
			if($category->getFlagNew() > 0)
				echo image_tag('new.gif');
			if($category->getFlagUpdated() > 0)
				echo image_tag('updated.gif');
			if($category->getFlagHot() > 0)
				echo image_tag('hot.gif');
		?>
		<?php 
		/*
		echo '<br /><span id="edit_me_name_'.$category->getId().'">'.$category->getCategoryName().'</span>';
		echo input_in_place_editor_tag('edit_me_name_'.$category->getId(), 'category/editInLine?attribute=CategoryName&id='.$category->getId(), array(
		  'cols'        => 40,
		  'rows'        => 1,
		)) */
		?>
	</td>
	<?php if($fullInfoOnOff=='show'): ?>
		<td>
			<?php echo $category->getTitle() ?>
			<?php /*
			<span id="edit_me_title_<?php echo $category->getId() ?>"><?php echo $category->getTitle() ?></span>
			<?php echo input_in_place_editor_tag('edit_me_title_'.$category->getId(), 'category/editInLine?attribute=Title&id='.$category->getId(), array(
			  'cols'        => 40,
			  'rows'        => 5,
			)) */?>
		</td>
	  <td><?php echo $category->getDescription() ?></td>
		<td id="showChild_<?php echo $category->getId() ?>">
		<?php
			if($category->getChild()!='N'){
				$fileInfo = CategoryPeer::totalFileAndSize($category->getId());
			}
			if($category->getChild()=='D'){
				echo 'Category:<b>'.CategoryPeer::hasChild($category->getId()).'</b><br />';
				echo 'Files:<b>'.$category->getFiles().'</b>';
				if($fileInfo[1] != $category->getFiles())
					echo '<br />ActualFiles:<b>'.$fileInfo[1].'</b>';
				echo '<br />Size: <b>'.$fileInfo[0].'</b>';
				echo '<br /><b>Downloads</b><br />Today:<b>'.number_format($fileInfo[2]).'</b>';
				echo '<br/> Total:<b>'.number_format($fileInfo[3]).'</b>';
			}
			if($category->getChild()=='F'){
				//echo 'Files: '.CategoryPeer::hasFiles($category->getId(),$category->getChild());
				echo 'Files:<b>'.$category->getFiles().'</b>';
				if($fileInfo[1] != $category->getFiles())
					echo '<br />ActualFiles:<b>'.$fileInfo[1].'</b>';
				echo '<br />Size: <b>'.$fileInfo[0].'</b>';
				echo '<br /><b>Downloads</b><br />Today:<b>'.number_format($fileInfo[2]).'</b>';
				echo '<br/> Total:<b>'.number_format($fileInfo[3]).'</b>';
			}
			if($category->getChild()=='N'){
				echo 'Empty Directory';
			}
		?>
		</td>
		<td><?php echo $category->getOrd() ?></td>
	<?php endif; ?>
  <td id="active_<?php echo $category->getId(); ?>">
  	<?php
			if($category->getStatus() == 'B'){
				$lnkName = '<span class="red">Block</span>';
				$status = 'B';
			}else{
				$lnkName = 'Active';
				$status = 'A';
			}
			echo link_to_remote($lnkName,	array('update' => 'active_'.$category->getId(),'url' => '/category/activation?id='.$category->getId().'&status='.$status));
			//echo ($files->getStatus()=='A' ? 'Active' : 'Deactive');
  	?>
  </td>
	<td class="action">
		<?php include_partial('global/myaction',array('id' => $category->getId(),'child'=>$category->getChild())); ?>
	</td>
   </tr>
<?php endforeach; ?>
</tbody>
</table>

<?php if($setOrderOnOff == 'hide'): ?>
<?php
	$parent = $sort = $categorysearch = '';
	if($sf_params->get('categorysearch'))	$categorysearch = '/categorysearch/'.$sf_params->get('categorysearch');
	if($sf_params->get('parent'))	$parent = '/parent/'.$sf_params->get('parent');
	if($sf_params->get('sort'))	$sort = '/sort/'.$sf_params->get('sort');
	$pageNum = myUser::pageNavigate($pager,sfConfig::get('app_webpath').'/category/list'.$parent.$categorysearch.'/page/');
	if($pageNum){
?>
	<div class="pgn">
		<?php echo $pageNum; ?>
		<?php
			echo form_tag('category/list','METHOD=GET');
			echo input_hidden_tag('parent',$sf_params->get('parent'));
			echo 'Jump to Page '.input_tag('page','','size=3');
			echo submit_tag('Go&raquo;','');
			echo '</form>';

			echo form_tag('default/recperpage','METHOD=POST');
			echo " - Records ";
			echo input_hidden_tag('module_is',$sf_context->getModuleName());
			echo select_tag("recperpage", options_for_select(
				array(10=>10,20=>20,25=>25,50=>50,100=>100), $recperpage)
			);
			echo submit_tag('Change','');
			echo '</form>';
		?>
	</div>
<?php } ?>
<?php endif; ?>


<div id="feedback" style="display:none;"></div>
<?php if($setOrderOnOff == 'show'): ?>
<style>
  .imgHandler { cursor: move; }
</style>
<?php echo sortable_element('order', array(
  'url'    => 'category/list',
  'update' => 'feedback',
  'only'   => 'sortable',
  'tag'		=> 'tr',
  'handle' => 'imgHandler'
)) ?>

<?php
	$arr_ord = array();
	$arr_ord = $sf_params->get('order');
	$old_ord = 0;
	print_r($arr_ord);
	if(sizeof($arr_ord) > 0) {
		foreach($arr_ord as $key => $value) {
			$new_ord = $key + 1;
			$con = Propel::getConnection();
			$c1 = new Criteria();
			$c1->add(CategoryPeer::ID,$value);
			$c2 = new Criteria();
			$c2->add(CategoryPeer::ORD,$new_ord);
			BasePeer::doUpdate($c1, $c2, $con);
		}
	}
?>
<?php endif; ?>