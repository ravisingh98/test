<style>
label { font-weight:bold; padding-right:10px; } 
.siteList a { padding:5px; background:#eee; border: 1px solid #ddd; font-weight:bold; text-decoration:none; }
.siteList a:hover,
.siteList a.sel { background:#4F9D00; border: 1px solid #ddd; font-weight:bold; text-decoration:none; color:#fff; }
</style>
<h1>Advertisement Code Edit / Show</h1>
<?php
	$dir = $sf_params->get('dir') ? $sf_params->get('dir')."/" : "";

	$fileName = array();
	// Note that !== did not exist until 4.0.0-RC2

	if ($handle = opendir(sfConfig::get('sf_upload_dir').'/advt/'.$dir)) {
	   //echo "Directory handle: $handle\n";
	   $allowExt = array('.txt','.php','.css');
		while (false !== ($file = readdir($handle))) {
			if(in_array(substr($file, -4), $allowExt)) {
				$fileName[$file] = $file;
			}
		}
		sort($fileName);
		closedir($handle);
	}

	if($sf_params->get('save')){
		$selectFile = $_REQUEST['mailName'];
		$filenm = sfConfig::get('sf_upload_dir')."/advt/".$dir.$sf_params->get('mailName');
	
		if($sf_params->get('chmod')!='')
			chmod($filenm, $sf_params->get('chmod'));
		if(file_exists($filenm))
			unlink($filenm);
		$handle = fopen($filenm, 'w');
		fwrite($handle, $sf_params->get('email_file'));
		fclose($handle);
	}
	
	$content='';
	if($sf_params->get('mailName') != '') {
		$selectFile = $sf_params->get('mailName');
		$filenm = sfConfig::get('sf_upload_dir')."/advt/".$dir.$_REQUEST['mailName'];
	
		if(file_exists($filenm)) {
			$lines = file($filenm);
			foreach ($lines as $line_num => $line) {
				$content .= $line;
			}
		}
	}
?>
<form action="" method="post" name="mail_format">
<div class="form-container">
	<fieldset style="border:0px solid #d5d5d5;padding:0;margin:0;">
		<br />
		<div class="siteList">
			<?php
				echo '<label>Select Site</label>';
				$directories = array('frashmusic');
				asort($directories);
				foreach($directories as $value){
					echo link_to($value,'advt/index?dir='.$value, array('class'=>($dir==$value."/" ? "sel" : "")));
					echo '&nbsp;';
				}
				?>
		</div>
		<br/>
		<div class="pad5">
			<?php
			if(count($fileName)==0){
				echo "<br/>Files Not Found Here.. Plz Select Site";
			}
			else{
				echo '<label>Select File</label>';
				echo '<input name="chmod" type="hidden" value="'.$sf_params->get('chmod').'">';
				echo '<select name="mailName" onchange="document.mail_format.submit();">';
					echo '<option disabled selected>Select Ad File</option>';
				foreach($fileName as $key => $value){
					echo '<option value="'.$value.'" '.($value==$selectFile ? 'selected':'').'>'.$value.'</option>';
				}
				echo '</select>';
			}
			?>
		</div>
		<Br />
		<?php
		if($sf_params->get('mailName') != '') {
		?>
		<div class="pad5">
			<label>Advertisement File Content</label>
		</div>
		<div class="pad5">
			<?php echo '<textarea name="email_file" style="width:98%;" rows=25>'.$content.'</textarea>'; ?>
		</div>
		<div class="pad5">
			<label class="col" for=""> </label> 
			<input type="submit" class="but_bg" value="Save" name="save"/> 
			<input type="reset" class="but_bg" value="Cancel" id="cancel" name="cancel"/>
		</div>
		<?php
		}
		?>
	</fieldset>
</div>
</form>
