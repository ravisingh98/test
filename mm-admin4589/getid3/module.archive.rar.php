<?php
/////////////////////////////////////////////////////////////////
/// getID3() by SkyDesignWorld <akashtilva@gmail.com>          //
/////////////////////////////////////////////////////////////////
// See readme.txt for more details                             //
/////////////////////////////////////////////////////////////////
//                                                             //
// module.archive.rar.php                                      //
// module for analyzing RAR files                              //
// dependencies: NONE                                          //
//                                                            ///
/////////////////////////////////////////////////////////////////


class getid3_rar
{

	function getid3_rar(&$fd, &$ThisFileInfo) {

		$ThisFileInfo['fileformat'] = 'rar';

		$ThisFileInfo['error'][] = 'RAR parsing not enabled in this version of getID3()';
		return false;

	}

}


?>