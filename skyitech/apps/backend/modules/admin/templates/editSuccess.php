<?php use_helper('Object') ?>

<?php echo form_tag('admin/update') ?>

<?php echo object_input_hidden_tag($admin, 'getId') ?>

<table>
<tbody>
<tr>
  <th>Username*:</th>
  <td><?php echo object_input_tag($admin, 'getUsername', array (
  'size' => 50,
)) ?></td>
</tr>
<tr>
  <th>Password*:</th>
  <td><?php echo input_tag('password', '', array (
  'size' => 50,
)) ?> Enter Password to change current password</td>
</tr>
<tr>
  <th>Level*:</th>
  <td><?php echo object_input_tag($admin, 'getLevel', array (
  'size' => 20,
)) ?><br/>Value: <br/>
1 - full rights,<br/>
2 - permission to ADD, EDIT, DELETE<br/>
3 - permission to ADD, EDIT</td>
</tr>
<tr>
  <th>Is superadmin*:</th>
  <td><?php echo object_input_tag($admin, 'getIsSuperadmin', array (
  'size' => 20,
)) ?> Value: Y/N</td>
</tr>
</tbody>
</table>
<hr />
<?php echo submit_tag('save') ?>
<?php if ($admin->getId()): ?>
  &nbsp;<?php echo link_to('delete', 'admin/delete?id='.$admin->getId(), 'post=true&confirm=Are you sure?') ?>
<?php else: ?>
  &nbsp;<?php echo link_to('cancel', 'admin/list') ?>
<?php endif; ?>
</form>
