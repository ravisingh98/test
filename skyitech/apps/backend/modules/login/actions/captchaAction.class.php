<?php

/**
 * sites actions.
 *
 * @package    
 * @subpackage sites
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 3335 2007-01-23 16:19:56Z fabien $
 */
class captchaAction extends sfAction
{
  public function execute()
  {
		$filename = md5($_SERVER['REMOTE_ADDR'].$_SERVER['HTTP_USER_AGENT']);
		$filename = sfConfig::get('sf_root_dir')."/cache/gb/$filename.txt";
		if(is_file($filename)){
			$handle = fopen($filename, "rb");
			$contents = stream_get_contents($handle);
			fclose($handle);
		}
		else
		{
			return $this->redirect('login/index');
		}

		$image_file = ImageCreate(50,20);
		$gray = imagecolorallocate($image_file, 240, 240, 240);
		$border = imagecolorallocate($image_file, 100, 100, 100);
		$font_color=ImageColorAllocate($image_file,0,0,0);
		imagefilledrectangle($image_file, 0, 0, 50, 20, $border);
		imagefilledrectangle($image_file, 1, 1, 48, 18, $gray);

		$font_file="images/visitor2.ttf";
		ImageTtfText($image_file,18,0,7,15,$font_color,$font_file, $contents);
		header("Content-Type: Image/GIF");
		ImageGIF($image_file);
		ImageDestroy($image_file);
		exit;
	 }

	public function handleError()
	{
 		captchaAction::execute();
 		return sfView::NONE;
	}

}
