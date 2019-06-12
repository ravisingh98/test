<?php
session_name('SKYiTech');
@session_start();

if($_SESSION['symfony/user/sfUser/authenticated']!=1 && !$_REQUEST['multi']){
	header('Location: /');
	exit;
}

$TaggingFormat = 'UTF-8';
header('Content-Type: text/html; charset='.$TaggingFormat);

require_once('getid3/getid3.php');
//include('config.php');
// Initialize getID3 engine
$getID3 = new getID3;
$getID3->setOption(array('encoding'=>$TaggingFormat));

getid3_lib::IncludeDependency(GETID3_INCLUDEPATH.'write.php', __FILE__, true);

$browsescriptfilename = 'demo.browse.php';

function FixTextFields($text) {
	return htmlentities(getid3_lib::SafeStripSlashes($text), ENT_QUOTES);
}

$Filename = '../siteuploads/files/'.(isset($_REQUEST['Filename']) ? getid3_lib::SafeStripSlashes($_REQUEST['Filename']) : '');

{
	//$TagFormatsToWrite = (isset($_POST['TagFormatsToWrite']) ? $_POST['TagFormatsToWrite'] : array());
	$TagFormatsToWrite = Array('id3v1','id3v2.3','ape');
	if (!empty($TagFormatsToWrite)) {
		//echo 'starting to write tag(s)<BR>';

		$tagwriter = new getid3_writetags;
		$tagwriter->filename       = $Filename;
		$tagwriter->tagformats     = $TagFormatsToWrite;
		$tagwriter->overwrite_tags = true;
		$tagwriter->tag_encoding   = $TaggingFormat;
		//if (!empty($_POST['remove_other_tags']))
		{
			$tagwriter->remove_other_tags = true;
		}
		$commonkeysarray = array(
									'Title',
									'Artist',
									'Album',
									'Year',
									'Comment',
									'Composer',
		 							'Copyright',
		 							'Band',
									'original_artist',
									'Publisher',
									);
									
		require_once('SkyWriteMp3Info.php');
		foreach ($commonkeysarray as $key) {
			//echo $key.'-';
			//if (!empty($_POST[$key]))
			{
				$TagData[strtolower($key)][] = getid3_lib::SafeStripSlashes($commonkeysarrayWrite[$key]);
				//echo $commonkeysarrayWrite[$key].$key;
			}
		}
		//if (!empty($_POST['Genre']))
		{
			//$TagData['genre'][] = getid3_lib::SafeStripSlashes($_POST['Genre']);
			$TagData['genre'][] = getid3_lib::SafeStripSlashes('Club');
		}
		if (!empty($_POST['GenreOther'])) {
			$TagData['genre'][] = getid3_lib::SafeStripSlashes($_POST['GenreOther']);
		}
		//if (!empty($_POST['Track']))
		{
			//$TagData['track'][] = getid3_lib::SafeStripSlashes($_POST['Track'].(!empty($_POST['TracksTotal']) ? '/'.$_POST['TracksTotal'] : ''));
			// add track number
			//$TagData['track'][] = getid3_lib::SafeStripSlashes('01/07');
		}
		
		//if (!empty($_FILES['userfile']['tmp_name'])) {

		if($_REQUEST['cid'] && is_file('../siteuploads/thumb/c/'.$_REQUEST['cid'].'_5.jpg')){
			$thumbImg = '../siteuploads/thumb/c/'.$_REQUEST['cid'].'_5.jpg';
		}
		else
		$thumbImg = 'skyTagImage.jpg';
		if (is_file($thumbImg)) {
			if (in_array('id3v2.4', $tagwriter->tagformats) || in_array('id3v2.3', $tagwriter->tagformats) || in_array('id3v2.2', $tagwriter->tagformats)) {
				//if (is_uploaded_file($_FILES['userfile']['tmp_name'])) {
				//if (is_uploaded_file($thumbImg))
				{
					//if ($fd = @fopen($_FILES['userfile']['tmp_name'], 'rb')) {
					if ($fd = @fopen($thumbImg, 'rb')) {
						//$APICdata = fread($fd, filesize($_FILES['userfile']['tmp_name']));
						$APICdata = fread($fd, filesize($thumbImg));
						fclose ($fd);

						//list($APIC_width, $APIC_height, $APIC_imageTypeID) = GetImageSize($_FILES['userfile']['tmp_name']);
						list($APIC_width, $APIC_height, $APIC_imageTypeID) = GetImageSize($thumbImg);
						$imagetypes = array(1=>'gif', 2=>'jpeg', 3=>'png');
						if (isset($imagetypes[$APIC_imageTypeID])) {

							//$TagData['attached_picture'][0]['data']          = $APICdata;
							//$TagData['attached_picture'][0]['picturetypeid'] = $_POST['APICpictureType'];
							//$TagData['attached_picture'][0]['description']   = $_FILES['userfile']['name'];
							//$TagData['attached_picture'][0]['mime']          = 'image/'.$imagetypes[$APIC_imageTypeID];
							$TagData['attached_picture'][0]['data']          = $APICdata;
							$TagData['attached_picture'][0]['picturetypeid'] = 3;
							$TagData['attached_picture'][0]['description']   = $thumbImg;
							$TagData['attached_picture'][0]['mime']          = 'image/'.$imagetypes[$APIC_imageTypeID];
						} else {
							//echo '<B>invalid image format (only GIF, JPEG, PNG)</B><BR>';
						}
					} else {
						//echo '<B>cannot open '.$_FILES['userfile']['tmp_name'].'</B><BR>';
						//echo '<B>cannot open skyTagImage.jpg</B><BR>';
					}
				}
			} else {
				//echo '<B>WARNING:</B> Can only embed images for ID3v2<BR>';
			}
		}

		$tagwriter->tag_data = $TagData;
		if ($tagwriter->WriteTags()) {
			/*
			* SKYiTech :: if file uploaded via multiple upload or dir upload just reply identification code
			*/
			if(@$_REQUEST['dirCatch']){
				echo $_REQUEST['dirCatch'];
				exit;
			}
			//echo 'Successfully wrote tags<BR>';
			
			/*
			* SKYiTech :: if single file uploaded redirect to upload page again.
			*/
			$goto = '/mm-admin4589/files/create/cid/'.$_REQUEST['cid'].'/lastFile/'.$_REQUEST['lastFile'];
			if($_REQUEST['cid'] && !$_REQUEST['multi'])
				header('Location: '.$goto);

			// print warning
			//if (!empty($tagwriter->warnings))
			{
				//echo 'There were some warnings:<BLOCKQUOTE STYLE="background-color:#FFCC33; padding: 10px;">'.implode('<BR><BR>', $tagwriter->warnings).'</BLOCKQUOTE>';
			}
		} else {
			echo 'Failed to write tags!<BLOCKQUOTE STYLE="background-color:#FF9999; padding: 10px;">'.implode('<BR><BR>', $tagwriter->errors).'</BLOCKQUOTE>';
		}
	} else {
		echo 'WARNING: no tag formats selected for writing - nothing written';
	}
	echo '<script type="text/javascript"> document.title="Mp3 Tag Writer Script By SKYiTech.com"; </script>';
//	echo '<HR>';
}
?>