		<div class="navigation">
			<div class="logo"><?php echo sfConfig::get('app_sitename') ?></div>
			<div class="mainMenuUL">
			<?php
				if($sf_user->isAuthenticated()){
					echo link_to('Home', '/', array('class'=>'active'));
					echo link_to('Category', '/category/list', array());
					echo link_to('Files', '/files/list', array());
					echo link_to('Artist', '/artist/list', array());
					echo link_to('Updates', '/updates/list', array());
					echo '<span class="online" id="cc">'.link_to_remote('Clear', array('update'=>'cc','url'=>'/default/clearcache'), array()).'</span>';
					echo link_to('Logout', '/login/logout', array('style'=>'float:right;'));
					echo link_to('FrontSite', sfConfig::get('app_frontsiteurl'), array('target'=>'_blank','style'=>'float:right;'));
				}
			?>
			<div class="otherLinks">
				<?php 
						echo link_to('Other','#',array('id'=>'otherLink'));
					?>
				<ul id="otherLinks" style="">
					<?php 
					echo '<li>'.link_to('File Manager', '/fm/index', array()).'</li>';
					if($sf_user->hasCredential('level1'))
						echo '<li>'.link_to('Advertisement', '/advt', array()).'</li>';
					if($sf_user->hasCredential('level1'))
						echo '<li>'.link_to('Setting', '/setting/list', array()).'</li>';
					if($sf_user->hasCredential('level1'))
						echo '<li>'.link_to('Manage Admins', '/admin/list', array()).'</li>';
					echo '<li>'.link_to('Change Password', '/setting/changePassword', array()).'</li>';
					?>
				</ul>
			</div>
			<div class="online">
				<span id="online"></span>
				<?php echo periodically_call_remote(array(
			    'frequency' => 10,
			    'update'    => 'online',
			    'url'       => 'default/online',
				)) ?>
			</div>
			<div id="searchLnk">
				<?php
					//echo link_to('Search','#',array('onClick'=>'if(document.getElementById(\'searchDiv\').style.display=="none") document.getElementById(\'searchDiv\').style.display="block"; else document.getElementById(\'searchDiv\').style.display="none";return false;'));
					echo link_to('Search','#');
				?>
				<div id="searchDiv">
					<?php
						echo form_tag('category/list','method=get');
						echo input_tag('categorysearch','','size=10');
						echo submit_tag('Category By Name','');
						echo '</form>';
						echo form_tag('files/list','method=get');
						echo input_tag('name','','size=10');
						echo submit_tag('Files By Name','');
						echo '</form>';
						echo form_tag('category/show','method=get');
						echo input_tag('id','','size=10');
						echo submit_tag('Category By Id','');
						echo '</form>';
						echo form_tag('files/show','method=get');
						echo input_tag('id','','size=10');
						echo submit_tag('Files By Id','');
						echo '</form>';
					?><hr/>
				  <?php 
				  echo 'Find ID & Category';
				  echo input_auto_complete_tag('find_category', '',
												'category/findcategory',
												array('autocomplete' => 'on','size'=>35,'style'=>'margin-top:3px;'),
												array('use_style'    => true)
											);
					?>
				</div>
			</div>
			</div>
			<div style="clear:both;height:0px;">&nbsp;</div>
		</div>

<script type="text/javascript">
jQuery('#otherLink').click(function(){
jQuery('#otherLinks').slideToggle();
})
jQuery('#searchLnk a').click(function(){
jQuery('#searchDiv').slideToggle();
})
</script>