<?php if ($sf_request->hasErrors()): ?>
<div class="msg error">
		<div id="errors" style="padding:10px;">
			Please correct the following errors and resubmit:
			<ul>
			<?php
				$i=0;
				foreach ($sf_request->getErrors() as $error){
					if($i==0)
						echo '<li>'.$error.'</li>';
					$i++;
				}
			?>
			</ul>
		</div>
</div>
<?php endif; ?>
