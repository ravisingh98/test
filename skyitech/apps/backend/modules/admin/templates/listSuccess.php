<?php
// auto-generated by sfPropelCrud
// date: 2013/08/22 13:14:24
?>
<h1>admin</h1>

<table>
<thead>
<tr>
  <th>Id</th>
  <th>Username</th>
  <th>Level</th>
  <th>Super Admin</th>
</tr>
</thead>
<tbody>
<?php foreach ($admins as $admin): ?>
<tr>
    <td><?php echo $admin->getId(); ?></td>
      <td><?php echo link_to($admin->getUsername(), 'admin/edit?id='.$admin->getId()) ?></td>
      <td><?php echo $admin->getLevel() ?></td>
      <td><?php echo $admin->getIsSuperadmin() ?></td>
  </tr>
<?php endforeach; ?>
</tbody>
</table>

<?php echo link_to ('create', 'admin/create') ?>