<?php use_helper('Javascript') ?>
<h1>Move Category or Files...</h1>
<div class="msg info" id="result" style="display:none;"></div>
<?php echo form_tag('category/move') ?>
<table>
<tr>
<th width="200">Source Category id:</th>
<td>
	<?php echo input_tag('id', $sf_params->get('id'), 'id=sourceid') ?>
	<b>Path: </b>
	<span id="sourceCategoryPath"></span>
	<?php echo observe_field('sourceid', array(
	    'update'   => 'sourceCategoryPath',
	    'url'      => 'category/getpath',
	    'with'     => "'id=' + value",
	    'loading'  => visual_effect('highlight', 'sourceCategoryPath')
	)) ?>
</td>
</tr><tr>
<th>Target Category Id:</th>
<td>
	<?php echo input_tag('movetoid', $sf_params->get('movetoid')) ?>
	<b>Path: </b><span id="targetCategoryPath"></span>
	<?php echo observe_field('movetoid', array(
	    'update'   => 'targetCategoryPath',
	    'url'      => 'category/getpath',
	    'with'     => "'id=' + value",
	    'loading'  => visual_effect('highlight', 'targetCategoryPath')
	)) ?>
</td>
</tr><tr>
<th></th>
<td>
	<?php echo submit_to_remote('ajax_submit', 'Move Category', array(
	    'update'   => 'result',
	    'url'      => 'category/moveCategory',
	    'loading'  => visual_effect('appear', 'result')
	)) ?>
	<?php echo submit_to_remote('ajax_submit', 'Move All Files', array(
	    'update'   => 'result',
	    'url'      => 'category/moveFile',
	    'loading'  => visual_effect('appear', 'result')
	)) ?>
	&nbsp;<?php echo link_to('cancel', 'category/list') ?>
</td>
</tr>
</table>
</form>
