<?php
/////////////////////////////////////////////////////////////////
/// getID3() by SkyDesignWorld <akashtilva@gmail.com>          //
/////////////////////////////////////////////////////////////////
// See readme.txt for more details                             //
/////////////////////////////////////////////////////////////////
//                                                             //
// module.audio.mod.php                                        //
// module for analyzing MOD Audio files                        //
// dependencies: NONE                                          //
//                                                            ///
/////////////////////////////////////////////////////////////////


class getid3_mod
{

	// new combined constructor
	function getid3_mod(&$fd, &$ThisFileInfo, $option) {

		if ($option === 'mod') {
			$this->getMODheaderFilepointer($fd, $ThisFileInfo);
		}
		elseif ($option === 'xm') {
			$this->getXMheaderFilepointer($fd, $ThisFileInfo);
		}
		elseif ($option === 'it') {
			$this->getITheaderFilepointer($fd, $ThisFileInfo);
		}
		elseif ($option === 's3m') {
			$this->getS3MheaderFilepointer($fd, $ThisFileInfo);
		}
	}


	function getMODheaderFilepointer(&$fd, &$ThisFileInfo) {

		fseek($fd, $ThisFileInfo['avdataoffset'] + 1080);
		$FormatID = fread($fd, 4);
		if (!preg_match('/^(M.K.|[5-9]CHN|[1-3][0-9]CH)$/', $FormatID)) {
			$ThisFileInfo['error'][] = 'This is not a known type of MOD file';
			return false;
		}

		$ThisFileInfo['fileformat'] = 'mod';

		$ThisFileInfo['error'][] = 'MOD parsing not enabled in this version of getID3()';
		return false;
	}

	function getXMheaderFilepointer(&$fd, &$ThisFileInfo) {

		fseek($fd, $ThisFileInfo['avdataoffset']);
		$FormatID = fread($fd, 15);
		if (!preg_match('/^Extended Module$/', $FormatID)) {
			$ThisFileInfo['error'][] = 'This is not a known type of XM-MOD file';
			return false;
		}

		$ThisFileInfo['fileformat'] = 'xm';

		$ThisFileInfo['error'][] = 'XM-MOD parsing not enabled in this version of getID3()';
		return false;
	}

	function getS3MheaderFilepointer(&$fd, &$ThisFileInfo) {

		fseek($fd, $ThisFileInfo['avdataoffset'] + 44);
		$FormatID = fread($fd, 4);
		if (!preg_match('/^SCRM$/', $FormatID)) {
			$ThisFileInfo['error'][] = 'This is not a ScreamTracker MOD file';
			return false;
		}

		$ThisFileInfo['fileformat'] = 's3m';

		$ThisFileInfo['error'][] = 'ScreamTracker parsing not enabled in this version of getID3()';
		return false;
	}

	function getITheaderFilepointer(&$fd, &$ThisFileInfo) {

		fseek($fd, $ThisFileInfo['avdataoffset']);
		$FormatID = fread($fd, 4);
		if (!preg_match('/^IMPM$/', $FormatID)) {
			$ThisFileInfo['error'][] = 'This is not an ImpulseTracker MOD file';
			return false;
		}

		$ThisFileInfo['fileformat'] = 'it';

		$ThisFileInfo['error'][] = 'ImpulseTracker parsing not enabled in this version of getID3()';
		return false;
	}

}


?>