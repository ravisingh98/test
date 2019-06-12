<?php
/////////////////////////////////////////////////////////////////
/// getID3() by SkyDesignWorld <akashtilva@gmail.com>          //
/////////////////////////////////////////////////////////////////
// See readme.txt for more details                             //
/////////////////////////////////////////////////////////////////
//                                                             //
// module.archive.doc.php                                      //
// module for analyzing MS Office (.doc, .xls, etc) files      //
// dependencies: NONE                                          //
//                                                            ///
/////////////////////////////////////////////////////////////////


class getid3_doc
{

	function getid3_doc(&$fd, &$ThisFileInfo) {

		$ThisFileInfo['fileformat'] = 'doc';

		$ThisFileInfo['error'][] = 'MS Office (.doc, .xls, etc) parsing not enabled in this version of getID3()';
		return false;

	}

}


?>