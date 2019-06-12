<h1>updates</h1>
<?php use_helper('Object') ?>
<?php include_partial('global/topAction'); ?>

<?php echo form_tag('updates/update') ?>

<?php echo object_input_hidden_tag($updates, 'getId') ?>

<table>
<tbody>
<tr>
  <th>Description:</th>
  <td><?php echo object_textarea_tag($updates, 'getDescription',array(
  	'class'=>'editor','size'=>'60x10')) ?>
  </td>
</tr>
<tr>
  <th>Status:</th>
  <td><span class="activeDeactiveButton"><?php echo checkbox_tag('status', 'A', ($updates->getStatus()=='A'?true:false), array ('')) ?><span></span></span> Active / Block Update</td>
</tr>
</tbody>
</table>
<hr />
<?php echo submit_tag('save') ?>
<?php if ($updates->getId()): ?>
  &nbsp;<?php echo link_to('delete', 'updates/delete?id='.$updates->getId(), 'post=true&confirm=Are you sure?') ?>
  &nbsp;<?php echo link_to('cancel', 'updates/list') ?>
<?php else: ?>
  &nbsp;<?php echo link_to('cancel', 'updates/list') ?>
<?php endif; ?>
</form>
