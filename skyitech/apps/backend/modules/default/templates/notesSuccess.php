<?php
	if($sf_params->get('save')){
		if($sf_params->get('save') == 'Save New'){
			if(trim($sf_params->get('new_name')) != '')
				$filenm = sfConfig::get('sf_upload_dir')."/notes/".$sf_params->get('new_name').'.txt';
			else
				echo 'Specify Name of File';
		}
		else
			$filenm = sfConfig::get('sf_upload_dir')."/notes/".$sf_params->get('mailName');
			
	
		if($sf_params->get('chmod')!='')
			chmod($filenm, $sf_params->get('chmod'));
		if(file_exists($filenm))
			unlink($filenm);
		$handle = fopen($filenm, 'w');
		fwrite($handle, $sf_params->get('email_file'));
		fclose($handle);
		//return $this->redirect('language/list');
	}

	if($sf_params->get('delete')=='delete'){
		$filenm = sfConfig::get('sf_upload_dir')."/notes/".$sf_params->get('mailName');
		if(file_exists($filenm))
			unlink($filenm);
	}


	$fileName = array();
	// Note that !== did not exist until 4.0.0-RC2
	if ($handle = opendir(sfConfig::get('sf_upload_dir').'/notes')) {
	  //echo "Directory handle: $handle\n";
	  $allowExt = array('.txt');
		while (false !== ($file = readdir($handle))) {
			if(in_array(substr($file, -4), $allowExt)) {
				$fileName[$file] = $file;
			}
		}
		sort($fileName);
		closedir($handle);
	}

	$content='';
	if($sf_params->get('mailName') != '')
		$selectFile = $sf_params->get('mailName');
	else
		$selectFile = 'general.txt';
	$filenm = sfConfig::get('sf_upload_dir')."/notes/".$selectFile;
	if(file_exists($filenm)) {
		$lines = file($filenm);
		foreach ($lines as $line_num => $line) {
			$content .= $line;
		}
	}
?>
<form action="" method="post" name="mail_format">
<div class="form-container">
		<br />
		<div class="pad5">
			<?php
				echo '<h1>Notes ';
				echo '<input name="chmod" type="hidden" value="'.$sf_params->get('chmod').'">';
				echo '<select name="mailName" onchange="document.mail_format.submit();">';
					echo '<option disabled selected>Select Note</option>';
				foreach($fileName as $key => $value){
					echo '<option value="'.$value.'" '.($value==$selectFile ? 'selected=""':'').'>'.$value.'</option>';
				}
				echo '</select> Notes / To Do List</h1>';
			?>
		</div>
		<div class="pad5">
			<?php echo '<textarea name="email_file" style="width:98%;" rows=15>'.$content.'</textarea>'; ?>
		</div>
		<div class="pad5">
			<label class="col" for=""> </label> 
			<input type="submit" class="but_bg" value="Save" name="save"/> 
			<?php if($sf_user->getAttribute('ADMINSUPERADMIN','','admin')=='Y'): ?>
			<input type="text" class="" value="" name="new_name"/> 
			<input type="submit" class="but_bg" value="Save New" name="save"/>  (Name Without Extension)
			<input type="submit" class="but_bg" value="delete" onclick="return confirm('Are you sure??');" name="delete"/>  (Selected File will be removed)
			<?php endif; ?>
		</div>
</div>
</form>