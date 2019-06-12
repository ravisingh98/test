<?
  if($_REQUEST['singer'])
          $artist = base64_decode($_REQUEST['singer']);
        else
          $artist = 'frashmusic.club';
$commonkeysarrayWrite = array(
	'Title'			=>	substr(base64_decode($_REQUEST['lastFile']),0,-4),
	'Artist'		=>	$artist,
	'Album'			=>	base64_decode($_REQUEST['catName']).' - frashmusic.club',
	'Year'			=>	date("Y"),
	'Comment'		=>	'Downloaded from http://www.frashmusic.club',
	'Composer'	        =>	'www.frashsound.com',
	'Band'		=>	$artist,
	'original_artist'       =>	'Downloaded from www.frashsound.com',
	'Publisher'	        =>	'www.frashmusic.club',
);
?>
