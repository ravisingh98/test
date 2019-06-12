<?php include_partial('global/showError')?>
<style type="text/css">
.mainDiv{ border:none; }
</style>
<?php echo form_tag('/login/index',array('name'=>'loginForm', 'method'=>'post')) ?>
<h2 style="padding: 5px 5px 5px 8px; background-color: #0682c7; font-weight: bold; color: #fff; margin-top: 0px; 
text-align:center; color:#fff; font-size:100%;">..:: Admin Panel Login ::.. </h2>
<h2 style="padding: 5px 5px 5px 8px; background-color: #009933; font-weight: bold; color: #fff; margin-top: 0px; 
text-align:center; color:#fff; font-size:100%;">Welcome to the admin of <?php echo sfConfig::get('app_sitename') ?></h2>


<table class="login">
<tbody>
	<tr>
		<th>User Name:</th>
		<td>
			<?php echo input_tag('username', '', array ('size' => 20,)) ?>
		</td>
	</tr>
	<tr>
		<th>Password:</th>
		<td>
			<?php echo input_password_tag('password', '', array ( 'size' => 20,)) ?>
		</td>
	</tr>
	<tr>
		<th><?php echo label_for('captchaCode','Enter Code:'); ?></th>
		<td>
			<?php echo @$msg; ?><?php 
				echo input_tag('cptc', '',array('size'=>5)); 
				echo '&nbsp;'.image_tag(sfConfig::get('app_webpath').'/login/captcha?'.rand(1000,2000));
			?>
			<?php echo input_hidden_tag('cKey', md5('sqlite'.$captchaCode)) ?>
		</td>
	</tr>
	<tr>
		<th></th>
		<td>
			<?php
				echo submit_tag('Login',  array ('name' => 'login',));
			?>
		</td>
	</tr>
</tbody>
</table>

<div style="padding:2px; margin:1px 0; border-top:1px solid #ddd; border-bottom:1px solid #ddd; background:#f4f0e9;font-weight:bold;"><a href="http://frashsound.com"><font color="#f33">Homepage</font></a> &#187 <b>Admin Mode </b></div>
<h2 style="padding: 5px 5px 5px 8px; background-color: #0682c7; font-weight: bold; color: #fff; margin-top: 0px; 
text-align:center; color:#fff; font-size:100%;"><a href="http://frashsound.com" style="text-decoration:none;"> <font color="white"> &copy; Frashsound.com 2017 - 2018 </font> </a></h2>
 <div style="background-color:black;padding:8px;border-radius:2px solid black; color:#a9a9a9;" align="Center"><b>Powered By :- <a href="http://www.Getindiahost.com" target="_blank" style="text-decoration:none;"><font color="#F33"> Getindiahost.com </font> </a><a href="wtai://wp/mc;+919012887761" style="text-decoration:none;"><font color="skyblue">♥ Frashsound.com ♥ </font> <font color="orange"> +91 9012887761 </font> <font color="deeppink"> [ New Delhi-India ] </font> </a></b></div>
	</div>
	
	</div>
	</div>