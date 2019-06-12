<?php
class fmActions extends sfActions
{
  public function executeIndex()
  {
  	$dirs = array();
  	$dir_name = array();
  	$files = array();
  	$rename_file_name = array();
 		$this->filesSelectedNow = false;

  	if(!$this->getRequestParameter('dir_path') || !preg_match('/^\/fm/i',$this->getRequestParameter('dir_path')))
 			$this->dir_path = '/fm';
  	else
	 		$this->dir_path = $this->getRequestParameter('dir_path');

  	if($this->getRequestParameter('navigation'))
	 		$this->dir_path = base64_decode($this->getRequestParameter('navigation'));

	  	$dir = $_SERVER['DOCUMENT_ROOT'].$this->dir_path;
	  	//$dir = $_SERVER['DOCUMENT_ROOT'].'/fm'.$this->getRequestParameter('dir_path');

  	if($this->getRequestParameter('checkedFiles')){
  		foreach($this->getRequestParameter('checkedFiles') as $key => $value){
  			$files[] = $value;
  			$rename_file_name[] = $this->getRequestParameter('rename_'.md5($value));
  		}
  		$this->filesSelectedNow = true;
  	//print_r($files); exit;
  	}
  	elseif($dir){
	    if( $dh = opendir($dir))
	    {
	        while( false !== ($file = readdir($dh)))
	        {
	            if( $file == '.' || $file == '..' || substr($file,0,9) == '.htaccess' || $file==".ftpquota")
	                continue;
	            $path = $dir . '/' . $file;
	            if( is_dir($path)){
	            	$dirs[] =  base64_encode(str_replace($_SERVER['DOCUMENT_ROOT'],sfConfig::get('app_frontsiteurl'), str_replace(' ','%20',$path)));
	            	$dir_name[] = base64_encode($file);
	            }
	            elseif(is_file($path)){
	                $files[$file] = base64_encode(str_replace($_SERVER['DOCUMENT_ROOT'],sfConfig::get('app_frontsiteurl'), str_replace(' ','%20',$path)));
	                $rename_file_name[$file] = base64_encode($file);
	            }
	        }
	        closedir($dh);
	        ksort($files);
	        ksort($rename_file_name);
	    }
  	}
	 	$this->dirs = $dirs;
	 	$this->dir_name = $dir_name;
	 	$this->files = $files;
	 	$this->rename_file_name = $rename_file_name;
  }

	public function executeUrl(){
		if($this->getRequestParameter('url_path')){
			ini_set("memory_limit","500M"); 
			set_time_limit(30);
			if($this->getRequestParameter('rename_file_name')!=''){
				$saveAs = $this->getRequestParameter('rename_file_name');
				$saveAs = trim(str_replace('%20',' ',$saveAs));	// join remaining array
			}
			$filePathToSave = $_SERVER['DOCUMENT_ROOT'].$this->getRequestParameter('dir_path').'/'.$saveAs;

			$way = 'wget';

			if($way=='fread'){
				/* run with fread commnd */
				$readHandle = fopen($this->getRequestParameter('url_path'), "rb");
				$writeHandle = fopen($filePathToSave, "a");
				while (!feof($readHandle)) {
				 fwrite($writeHandle, fread($readHandle, 8192));	/* read small part of file and save */
				 //fwrite($writeHandle, fread($readHandle, 512000));	/* read small part of file and save */
				}
				fclose($readHandle);
				fclose($writeHandle);
			}
			elseif($way=='file_get_contents'){
				/* run with file_get_cntents commnd */
				$outfile = fopen($filePathToSave, 'wb');
				$content = file_get_contents($this->getRequestParameter('url_path'));
				fwrite($outfile,$content) or die('can not write');
				fclose($outfile);
			}
			elseif($way=='wget'){
				/* run with wget command */
				exec('wget --header="Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8" -U "Mozilla/5.0 (Windows NT 5.1; rv:12.0) Gecko/20100101 Firefox/12.0" -O '.myUser::linuxConvert($filePathToSave).' '.myUser::linuxConvert($this->getRequestParameter('url_path')));
			}
			elseif($way=='curl'){
				$ch = curl_init($this->getRequestParameter('url_path'));
				curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
				curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 7.0b; Windows NT 6.0)');
				curl_setopt($ch, CURLOPT_AUTOREFERER, true);
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);	// required to follow redirected urls
				curl_setopt($ch, CURLOPT_MAXREDIRS, 4);	// set max redirect limit
				curl_setopt($ch, CURLOPT_FAILONERROR, 1); // dont hang
				$outfile = fopen($filePathToSave, 'wb');
				curl_setopt($ch, CURLOPT_FILE, $outfile) or error('can not write destination file');
				$c = curl_exec($ch);
				curl_close($ch);
			}
		}
 		$this->redirect('fm/index?navigation='.base64_encode($this->getRequestParameter('dir_path')));
	}

 	public function executeUnzip(){
	 	if($this->getRequestParameter('file'))
  	{
  		//echo $_SERVER['DOCUMENT_ROOT'].base64_decode($this->getRequestParameter('file')); exit;
  		echo exec('unzip -q '. $_SERVER['DOCUMENT_ROOT'].myUser::linuxConvert(base64_decode($this->getRequestParameter('file'))).' -d '.$_SERVER['DOCUMENT_ROOT'].myUser::linuxConvert(base64_decode($this->getRequestParameter('dir_path'))) );
  	}
 		$this->redirect('fm/index?navigation='.$this->getRequestParameter('dir_path'));
 		exit;
 	}

 	public function executeZip(){
		if($this->getRequestParameter('dir'))
		{
			//echo $_SERVER['DOCUMENT_ROOT'].base64_decode($this->getRequestParameter('file')); exit;
			exec('cd '.$_SERVER['DOCUMENT_ROOT'].myUser::linuxConvert(base64_decode($this->getRequestParameter('dir_path'))).'
			zip -rq '. myUser::linuxConvert(base64_decode($this->getRequestParameter('dirName'))).'.zip '.myUser::linuxConvert(base64_decode($this->getRequestParameter('dirName'))) );
			//  		echo exec('zip -rq '. $_SERVER['DOCUMENT_ROOT'].myUser::linuxConvert(base64_decode($this->getRequestParameter('dir'))).' '.$_SERVER['DOCUMENT_ROOT'].myUser::linuxConvert(base64_decode($this->getRequestParameter('dir'))) );
		}
		$this->redirect('fm/index?navigation='.$this->getRequestParameter('dir_path'));
		exit;
	}

 	public function executeDelete(){
		if($this->getRequestParameter('file'))
  		{
	  		$pathToDelete = $_SERVER['DOCUMENT_ROOT'].myUser::linuxConvert(base64_decode($this->getRequestParameter('file')));
		  	if(preg_match('/\/fm\//i',$pathToDelete))
  				exec('rm -rf '. $pathToDelete);
  		}
 		$this->redirect('fm/index?navigation='.$this->getRequestParameter('dir_path'));
 		exit;
	}

 	public function executeRename(){
		if($this->getRequestParameter('file'))
  		{
	  		$pathToRename = $_SERVER['DOCUMENT_ROOT'].base64_decode($this->getRequestParameter('dir_path')).'/'.base64_decode($this->getRequestParameter('file'));
	  		$renameTo = $_SERVER['DOCUMENT_ROOT'].base64_decode($this->getRequestParameter('dir_path')).'/'.($this->getRequestParameter('value'));
		
		  	if(is_dir($pathToRename) || is_file($pathToRename))
			  	if(preg_match('/\/fm\//i',$pathToRename))
	  				exec('mv "'.$pathToRename.'" "'.$renameTo.'"');
  		}
  		echo $this->getRequestParameter('value');
 	//	$this->redirect('fm/index?navigation='.$this->getRequestParameter('dir_path'));
 		exit;
	}

 	public function executeMkdir(){
		if($this->getRequestParameter('dir_name'))
		{
		//echo $_SERVER['DOCUMENT_ROOT'].base64_decode($this->getRequestParameter('dir_name')); exit;
		mkdir( $_SERVER['DOCUMENT_ROOT'].$this->getRequestParameter('dir_path').'/'.$this->getRequestParameter('dir_name') );
		}
		$this->redirect('fm/index?navigation='.base64_encode($this->getRequestParameter('dir_path')));
		exit;
	}

}