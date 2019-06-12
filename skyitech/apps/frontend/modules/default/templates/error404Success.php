<?php 
if(is_file('404.html')){
	include('404.html');
}
else
	echo "<h1>Error 400</h1>Oops.. The File/Page you'r looking for, is currently unavailable or removed. We regret the inconvienence. Please hit back or visit home page.<br/><br/>";
	?>