<?php use_helper('Javascript') ?>
<?php
// auto-generated by sfPropelCrud
// date: 2009/08/28 12:12:29
if($sf_params->get('msg'))
	echo '<h2 style="color:red; text-align:center;">'.$sf_params->get('msg').'</b></h2>';
?>
<?php use_helper('Object') ?>
<h1>Category Editor</h1>
<?php if(isset($parent) && $parent)
	include_partial('global/catpath',array('parents' => $category,'self'=>false));
?>

<?php echo form_tag('category/update',array('name'=>'Category', 'enctype'=>'multipart/form-data')) ?>

<?php echo object_input_hidden_tag($category, 'getId') ?>
<?php echo input_hidden_tag('parent', $parent) ?>
<?php echo input_hidden_tag('parents', $category->getParents()) ?>
<?php echo input_hidden_tag('parentsarray', $category->getParentsarray()) ?>

<table>
<tbody>
<tr>
  <th>Category name:</th>
  <td><?php echo object_input_tag($category, 'getCategoryName', array ('size' => 60,)) ?></td>
</tr>
<tr>
  <th>Target URL:</th>
  <td><?php echo object_input_tag($category, 'getUrl', array ('size' => 60,)) ?></td>
</tr>
<tr>
	<th>Thumb display:</th>
	<td><?php
		if(is_file(sfConfig::get('sf_upload_dir').'/thumb/c/'.$category->getId().'_0.jpg')){
			echo '<div id="thumbUpdate">';
			for($i=1;$i<=8;$i++)
				echo image_tag(sfConfig::get('app_upload_dir').'/thumb/c/'.$category->getId().'_'.$i.'.jpg',array('alt'=>'')).'&nbsp;';
			echo link_to('original',sfConfig::get('app_upload_dir').'/thumb/c/'.$category->getId().'_0.jpg',array('target'=>'_blank'));
			echo '</div><br/>';
		}
			echo 'url:&nbsp;'.input_tag('thumb_url_path', '', array ('size'=>60));
			echo '&nbsp;'.input_file_tag('thumb_name',  array ('size' => 20,));
			echo '&nbsp;&nbsp;&nbsp;'.link_to_remote('Remove Thumb', array('update' => 'thumbUpdate',
																			    'url'    => 'category/deletethumb?id='.$category->getId(),));
//			echo '<br/>'.checkbox_tag('thumb_square','yes').label_for('thumb_square',' Square Thumb');

			echo '<hr/>Logo: ';
			echo '<br/>'.radiobutton_tag('logo','logo_c1.png',true).image_tag( sfConfig::get('app_upload_dir').'/logo_c1.png','style=background:red');
			echo '<br/>'.radiobutton_tag('logo','logo_c2.png').image_tag(sfConfig::get('app_upload_dir').'/logo_c2.png','style=background:red');
			echo '<br/>'.radiobutton_tag('logo','logo_c3.png').image_tag(sfConfig::get('app_upload_dir').'/logo_c3.png','style=background:red');
			echo '<br/>'.radiobutton_tag('logo','logo_c4.png').image_tag(sfConfig::get('app_upload_dir').'/logo_c4.png','style=background:red');
			echo '<br/>'.radiobutton_tag('logo','logo_c5.png').image_tag(sfConfig::get('app_upload_dir').'/logo_c5.png','style=background:red');
			echo '<br/>'.radiobutton_tag('logo','logo_c6.png').image_tag(sfConfig::get('app_upload_dir').'/logo_c6.png','style=background:red');
			echo '<br/>'.radiobutton_tag('logo','logo_c7.png').image_tag(sfConfig::get('app_upload_dir').'/logo_c7.png','style=background:red');
			echo '<br/>'.radiobutton_tag('logo','logo_c8.png').image_tag(sfConfig::get('app_upload_dir').'/logo_c8.png','style=background:red');
			echo '<br/>'.radiobutton_tag('logo','logo_c9.png').image_tag(sfConfig::get('app_upload_dir').'/logo_c9.png','style=background:red');

			echo '<hr/>Watermark Position: ';
			echo '<br/>';
			echo radiobutton_tag('position','TL').'&nbsp;';
			echo radiobutton_tag('position','TC').'&nbsp;';
			echo radiobutton_tag('position','TR').'&nbsp;';
			echo '<br/>';
			echo radiobutton_tag('position','ML').'&nbsp;';
			echo radiobutton_tag('position','M').'&nbsp;';
			echo radiobutton_tag('position','MR').'&nbsp;';
			echo '<br/>';
			echo radiobutton_tag('position','BL').'&nbsp;';
			echo radiobutton_tag('position','B',true).'&nbsp;';
			echo radiobutton_tag('position','BR').'&nbsp;';
			echo checkbox_tag('position_force','yes').label_for('position_force',' Change Position Only');

		?></td>
</tr>
<tr>
  <th>Title:</th>
  <td><?php echo object_input_tag($category, 'getTitle', array ('size' => 60,)) ?></td>
</tr>
<tr>
  <th>Description:</th>
  <td><?php echo object_textarea_tag($category, 'getDescription',array('class'=>'editor', 'size'=>'60x4'));
  	//'rich'=>true, 'tinymce_options'=>'plugins:"",theme_advanced_buttons1:"bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,fontsizeselect,|,forecolor,backcolor",theme_advanced_buttons2:"undo,redo,|,link,unlink,image,cleanup,code",theme_advanced_buttons3:""',
  	 ?></td>
</tr>
<?php if ($category->getChild()=='F'): ?>
<tr>
  <th>List Order:</th>
  <td><?php echo select_tag('list_ord', options_for_select(array(
  '0'  => 'new2old',
  '1'    => 'a-z',
  '2' => 'z-a',
  '3'    => 'download',
), $category->getListOrd())) ?></td>
</tr>
<?php endif; ?>
<tr>
  <th>Admin Only:</th>
  <td>
  	Parent: <?php echo input_tag('parents', $category->getParents(), array ('readonly' => true)) ?>
  	Child: <?php echo object_input_tag($category, 'getChild', array ('size' => 1,)) ?>
  	Order: <?php echo object_input_tag($category, 'getOrd', array ('size' => 1,)) ?></td>
</tr>
<tr>
  <th>Status:</th>
  <td><span class="activeDeactiveButton"><?php echo checkbox_tag('status', 'A', ($category->getStatus()=='A'?true:false), array ('')) ?><span></span></span> Active / Block Category</td>
</tr>
<tr>
  <th>Flag:</th>
  <td>
  	New <?php echo input_tag('flag_new', $category->getFlagNew(), array ('size'=>1)) ?>
  	Update <?php echo input_tag('flag_updated', $category->getFlagUpdated(), array ('size'=>1)) ?>
  	Hot <?php echo input_tag('flag_hot', $category->getFlagHot(), array ('size'=>1)) ?>
  	[Write Number of days to display Flag - Maximum 99 days]
  </td>
</tr>
</tbody>
</table>
<hr />
<?php echo submit_tag('save') ?>
<?php if ($category->getId()): ?>
  &nbsp;<?php echo link_to('cancel', 'category/list?parent='.$parent) ?>
<?php else: ?>
  &nbsp;<?php echo link_to('cancel', 'category/list?parent='.$parent) ?>
<?php endif; ?>
</form>
<br />
<?php if($category->getParents()!='|'): ?>
<h1 onclick="document.getElementById('moveDiv').style.display='block'">Category Move</h1>
<div id="moveDiv" style="display:none;">
<?php echo form_tag('category/move') ?>
	<?php echo object_input_hidden_tag($category, 'getId') ?>
	<table>
	<tbody>
		<tr>
		  <th>Category name:</th>
		  <td><?php echo object_input_tag($category, 'getCategoryName', array ('size' => 60,)) ?></td>
		</tr>
		<tr>
		  <th>Move to Category Id:</th>
		  <td>
		  	<?php echo input_tag('movetoid', '', array('size' => 10)) ?><span id="item_suggestion"></span>
			  <?php echo observe_field('movetoid', array(
			      'update'   => 'item_suggestion',
			      'url'      => 'category/getpath',
			      'with'     => "'id=' + value",
			  )) ?>
		  </td>
		</tr>
		<tr>
		  <th></th>
		  <td>
				<?php echo submit_tag('Move') ?>
		  </td>
		</tr>
	</tbody>
	</table>
</form>
</div>
<?php endif; ?>

<script type="text/javascript">
jQuery('.editor').dblclick(function(){
	CKEDITOR.replace( this );
});
</script>