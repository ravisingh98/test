<?php use_helper('Javascript') ?>
<h1>Dir Uploader </h1>
<?php include_partial('global/topAction',array('id' => '')); ?>
<?php 
	include_partial('global/catpath',array('parents' => $parentCategory,'self'=>true));
?>
<?php 
	echo '<span id="cc2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.link_to_remote(image_tag('cc.png','title=clear cache').'Clear Cache',	array('update' => 'cc_'.$category_id,'url' => '/category/clearcache?id='.$category_id)).'</span>';
?>
<?php echo form_tag('files/dirCatch',array('name'=>'Files', 'enctype'=>'multipart/form-data')) ?>
<?php	echo input_hidden_tag('category_id', $category_id); ?>
<table>
<tbody>
<tr>
	<th><?php	echo 'Directory Path to upload:&nbsp;'; ?></th>
	<td><?php echo input_tag('dir_path', $dir_path, array('size'=>70)); ?></td>
</tr>
<tr>
	<th><?php	echo ' Remove Text '; ?></th>
	<td><?php	echo input_tag('replaceText', $replaceText); ?>Remove any text(s) from name. seperate multiple by comma(i.e. text1,text2,text3)</td>
</tr>
<tr>
	<th><?php	echo ' Show me files '; ?></th>
	<td><?php echo submit_tag('show files A-Z') ?>
	<?php echo submit_tag('show files Z-A') ?></td>
</tr>
</tbody>
</table>
</form>

<?php echo form_tag('files/dirCatch','id=gForm') ?>
<?php	echo input_hidden_tag('dir_path', $dir_path); ?>
<?php	echo input_hidden_tag('category_id', $category_id); ?>
<?php	echo input_hidden_tag('replaceText', $replaceText); ?>
<?php
if($filesSelectedNow == true){
	$flist=$fileNewName='';
	echo '<table>';
	foreach($files as $key=>$value){
	  //echo $value;
	  $flist .= "'".$value."', ";
	  $fileNewName .= "'".$rename_file_name[$key]."', ";
	  echo '<tr id="'.$value.'"><td id="get_'.$value.'"><a href="" onclick="makeDir(\''.$value.'\','.$category_id.',\''.$rename_file_name[$key].'\', \''.$removeFile.'\', \''.$mp3tags.'\'); return false;">Get</a></td>';
	  echo '<td>'.base64_decode($value).'</td><td>'.$rename_file_name[$key].'</td></tr>';
	}
	echo '</table>';
	$flist = substr($flist,0,-2);
	$fileNewName = substr($fileNewName,0,-2);
	?>
	<script type="text/javascript">
	
		// write id to whom we have to invide
		var webpath=<?php echo "'".$_SERVER['HTTP_HOST']."'"; ?>;
		var flist=new Array(<?php echo $flist; ?>);
		var fileNewName=new Array(<?php echo $fileNewName; ?>);
		var cid=<?php echo $category_id; ?>;
		var removeFile=<?php echo "'".$removeFile."'"; ?>;
		var mp3tags=<?php echo "'".$mp3tags."'"; ?>;
		var c=1
		var t
	
		function makeDir(id,cid,newName,removeFile,mp3tags){
			//alert(category);
				var ajaxRequest1;  // The variable that makes Ajax possible!
				try{
					// Opera 8.0+, Firefox, Safari
					ajaxRequest1 = new XMLHttpRequest();
				} catch (e){
					// Internet Explorer Browsers
					try{
						ajaxRequest1 = new ActiveXObject("Msxml2.XMLHTTP");
					} catch (e) {
						try{
							ajaxRequest1 = new ActiveXObject("Microsoft.XMLHTTP");
						} catch (e){
							// Something went wrong
							alert("Your browser broke!");
							return false;
						}
					}
				}
			
				// Create a function that will receive data sent from the server
				ajaxRequest1.onreadystatechange = function(){
					if(ajaxRequest1.readyState == 4){
						document.getElementById(ajaxRequest1.responseText).style.background = '#fc0';
						document.getElementById('get_'+ajaxRequest1.responseText).innerHTML = 'Done';
					}
				}
				//alert("http://"+webpath+"/mm-admin4589/files/update?dirCatch=true&status=A&removeFile="+removeFile+"&mp3tags="+mp3tags+"&url_path="+id+"&category_id="+cid+"&rename_file_name"+newName);
				ajaxRequest1.open("GET", "http://"+webpath+"/mm-admin4589/files/update?dirCatch=true&urlType=CopyToServer&status=A&removeFile="+removeFile+"&mp3tags="+mp3tags+"&url_path="+id+"&category_id="+cid+"&rename_file_name="+newName, true);
				ajaxRequest1.send(null);
		}
	
		function clearcache(){
			//alert(category);
				var ajaxRequest1;  // The variable that makes Ajax possible!
				try{
					// Opera 8.0+, Firefox, Safari
					ajaxRequest1 = new XMLHttpRequest();
				} catch (e){
					// Internet Explorer Browsers
					try{
						ajaxRequest1 = new ActiveXObject("Msxml2.XMLHTTP");
					} catch (e) {
						try{
							ajaxRequest1 = new ActiveXObject("Microsoft.XMLHTTP");
						} catch (e){
							// Something went wrong
							alert("Your browser broke!");
							return false;
						}
					}
				}
			
				// Create a function that will receive data sent from the server
				ajaxRequest1.onreadystatechange = function(){
					if(ajaxRequest1.readyState == 4){
						//document.getElementById('cc2').style.background = '#fc0';
						//document.getElementById('cc2').innerHTML = 'Cache Cleared';
					}
				}
				ajaxRequest1.open("GET", "http://"+webpath+"/mm-admin4589/category/clearcache?id="+cid, true);
				ajaxRequest1.send(null);
		}
	
		function timedCount()
		{
			// get 1 value from array
			add = flist.shift();
			addNewName = fileNewName.shift();
			// open url to add friend
			makeDir( add, cid, addNewName, removeFile , mp3tags);
			
			// write current working log
			document.getElementById('addedTotal').innerHTML='Total Files Requested: '+c;
			
			// add link to div id="added"
			document.getElementById(add).style.background = '#ff0';

			// total counter
			c=c+1;
			
			// stop function if array is empty
			if(flist==''){
				document.getElementById('addedTotal').innerHTML = document.getElementById('addedTotal').innerHTML + ' <b>:: Complete All Files ::</b>';
				stopCount()
				clearcache();
				Exit
			}
			t=setTimeout("timedCount()",document.getElementById('delay').value);
		}
		
		function stopCount()
		{
			clearTimeout(t);
		}
	
	</script>
	
	Delay (Micro Second):	<input type="text" value="3000" id="delay">
	<input type="button" value="Start!"	onClick="timedCount();">
	<input type="button" value="Stop!" onClick="stopCount()">
	<div id="addedTotal">Files not copied yet:</div>
<?php
}
elseif($files || $dirs){
	echo '<table >';
	echo form_tag('files/dirCatch',array('name'=>'Dirs', 'enctype'=>'multipart/form-data'));
	foreach($dirs as $key=>$value){
	  echo '<tr>';
	  echo '<td>'.base64_decode($value).'</td><td>'.submit_tag(base64_decode($dir_name[$key]),array('value'=>$dir_path.'/'.base64_decode($dir_name[$key]),'name'=>'dir_path')).'</td></tr>';
	}
	echo '</form>';
	foreach($files as $key=>$value){
		echo '<tr>';
	  echo '<td>';
	  echo checkbox_tag('checkedFiles[]',$value);
	  echo label_for('checkedFiles_'.$value,'&nbsp;'.base64_decode($value)).'</td>';
	  echo '<td>'.input_tag('rename_'.md5($value),base64_decode($rename_file_name[$key]),array('id'=> $value,'size'=>60)).'</td>';
		//echo $key.' -> '.$value.' -> '.substr($value,strrpos($value, ".")+1);
		echo '</tr>';
	}
	echo '</table>';
	?>
	<?php echo link_to('Select All','#',array('onclick'=>'checkedAll(\'gForm\'); return false;')); ?>
	<br /><br />
	Remove Original File From Server After Uploading<br />
	<?php echo label_for('',radiobutton_tag('removeFile','yes').'yes').'&nbsp;&nbsp;'; ?>
	<?php echo label_for('',radiobutton_tag('removeFile','no',true).'no'); ?>
	<br />
	<br />
	Overwrite MP3 Tags?<br />
	<?php echo label_for('',radiobutton_tag('mp3tags','yes',(SETTING_MP3TAG_OVERWRITE=='ON' ? true : false)).'yes').'&nbsp;&nbsp;'; ?>
	<?php echo label_for('',radiobutton_tag('mp3tags','no',(SETTING_MP3TAG_OVERWRITE=='OFF' ? true : false)).'no'); ?>
	<br />
	<br />
	<?php echo submit_tag('Upload Selected',''); ?>

	<script type="text/javascript">
		checked=false;
		function checkedAll (frm1) {
			var aa= document.getElementById(frm1);
			if (checked == false)
			{
				checked = true
			}
			else
			{
				checked = false
			}
			for (var i =0; i < aa.elements.length; i++) 
			{
				if(aa.elements[i].name=="checkedFiles[]")
				aa.elements[i].checked = checked;
			}
		}
	</script>
<?php
}
?>
</form>
