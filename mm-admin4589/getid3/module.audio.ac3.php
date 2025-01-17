<?php
/////////////////////////////////////////////////////////////////
/// getID3() by SkyDesignWorld <akashtilva@gmail.com>          //
/////////////////////////////////////////////////////////////////
// See readme.txt for more details                             //
/////////////////////////////////////////////////////////////////
//                                                             //
// module.audio.ac3.php                                        //
// module for analyzing AC-3 (aka Dolby Digital) audio files   //
// dependencies: NONE                                          //
//                                                            ///
/////////////////////////////////////////////////////////////////


class getid3_ac3
{

	function getid3_ac3(&$fd, &$ThisFileInfo) {

		///AH
		$ThisFileInfo['ac3']['raw']['bsi'] = array();
		$thisfile_ac3                      = &$ThisFileInfo['ac3'];
		$thisfile_ac3_raw                  = &$thisfile_ac3['raw'];
		$thisfile_ac3_raw_bsi              = &$thisfile_ac3_raw['bsi'];


		// http://www.atsc.org/standards/a_52a.pdf

		$ThisFileInfo['fileformat']            = 'ac3';
		$ThisFileInfo['audio']['dataformat']   = 'ac3';
		$ThisFileInfo['audio']['bitrate_mode'] = 'cbr';
		$ThisFileInfo['audio']['lossless']     = false;

		// An AC-3 serial coded audio bit stream is made up of a sequence of synchronization frames
		// Each synchronization frame contains 6 coded audio blocks (AB), each of which represent 256
		// new audio samples per channel. A synchronization information (SI) header at the beginning
		// of each frame contains information needed to acquire and maintain synchronization. A
		// bit stream information (BSI) header follows SI, and contains parameters describing the coded
		// audio service. The coded audio blocks may be followed by an auxiliary data (Aux) field. At the
		// end of each frame is an error check field that includes a CRC word for error detection. An
		// additional CRC word is located in the SI header, the use of which, by a decoder, is optional.
		//
		// syncinfo() | bsi() | AB0 | AB1 | AB2 | AB3 | AB4 | AB5 | Aux | CRC

		fseek($fd, $ThisFileInfo['avdataoffset'], SEEK_SET);
		$AC3header['syncinfo'] = fread($fd, 5);
		$thisfile_ac3_raw['synchinfo']['synchword'] = substr($AC3header['syncinfo'], 0, 2);

		if ($thisfile_ac3_raw['synchinfo']['synchword'] != "\x0B\x77") {

			$ThisFileInfo['error'][] = 'Expecting "\x0B\x77" at offset '.$ThisFileInfo['avdataoffset'].', found \x'.strtoupper(dechex($AC3header['syncinfo']{0})).'\x'.strtoupper(dechex($AC3header['syncinfo']{1})).' instead';
			unset($thisfile_ac3);
			return false;

		} else {

			// syncinfo() {
			// 	 syncword    16
			// 	 crc1        16
			// 	 fscod        2
			// 	 frmsizecod   6
			// } /* end of syncinfo */

			$thisfile_ac3_raw['synchinfo']['crc1']       = getid3_lib::LittleEndian2Int(substr($AC3header['syncinfo'], 2, 2));
			$ac3_synchinfo_fscod_frmsizecod                        = getid3_lib::LittleEndian2Int(substr($AC3header['syncinfo'], 4, 1));
			$thisfile_ac3_raw['synchinfo']['fscod']      = ($ac3_synchinfo_fscod_frmsizecod & 0xC0) >> 6;
			$thisfile_ac3_raw['synchinfo']['frmsizecod'] = ($ac3_synchinfo_fscod_frmsizecod & 0x3F);

			$thisfile_ac3['sample_rate'] = $this->AC3sampleRateCodeLookup($thisfile_ac3_raw['synchinfo']['fscod']);
			if ($thisfile_ac3_raw['synchinfo']['fscod'] <= 3) {
				$ThisFileInfo['audio']['sample_rate'] = $thisfile_ac3['sample_rate'];
			}

			$thisfile_ac3['frame_length'] = $this->AC3frameSizeLookup($thisfile_ac3_raw['synchinfo']['frmsizecod'], $thisfile_ac3_raw['synchinfo']['fscod']);
			$thisfile_ac3['bitrate']      = $this->AC3bitrateLookup($thisfile_ac3_raw['synchinfo']['frmsizecod']);
			$ThisFileInfo['audio']['bitrate'] = $thisfile_ac3['bitrate'];

			$AC3header['bsi'] = getid3_lib::BigEndian2Bin(fread($fd, 15));
			$ac3_bsi_offset = 0;

			$thisfile_ac3_raw_bsi['bsid'] = bindec(substr($AC3header['bsi'], $ac3_bsi_offset, 5));
			$ac3_bsi_offset += 5;
			if ($thisfile_ac3_raw_bsi['bsid'] > 8) {
				// Decoders which can decode version 8 will thus be able to decode version numbers less than 8.
				// If this standard is extended by the addition of additional elements or features, a value of bsid greater than 8 will be used.
				// Decoders built to this version of the standard will not be able to decode versions with bsid greater than 8.
				$ThisFileInfo['error'][] = 'Bit stream identification is version '.$thisfile_ac3_raw_bsi['bsid'].', but getID3() only understands up to version 8';
				unset($thisfile_ac3);
				return false;
			}

			$thisfile_ac3_raw_bsi['bsmod'] = bindec(substr($AC3header['bsi'], $ac3_bsi_offset, 3));
			$ac3_bsi_offset += 3;
			$thisfile_ac3_raw_bsi['acmod'] = bindec(substr($AC3header['bsi'], $ac3_bsi_offset, 3));
			$ac3_bsi_offset += 3;

			$thisfile_ac3['service_type'] = $this->AC3serviceTypeLookup($thisfile_ac3_raw_bsi['bsmod'], $thisfile_ac3_raw_bsi['acmod']);
			$ac3_coding_mode = $this->AC3audioCodingModeLookup($thisfile_ac3_raw_bsi['acmod']);
			foreach($ac3_coding_mode as $key => $value) {
				$thisfile_ac3[$key] = $value;
			}
			switch ($thisfile_ac3_raw_bsi['acmod']) {
				case 0:
				case 1:
					$ThisFileInfo['audio']['channelmode'] = 'mono';
					break;
				case 3:
				case 4:
					$ThisFileInfo['audio']['channelmode'] = 'stereo';
					break;
				default:
					$ThisFileInfo['audio']['channelmode'] = 'surround';
					break;
			}
			$ThisFileInfo['audio']['channels'] = $thisfile_ac3['num_channels'];

			if ($thisfile_ac3_raw_bsi['acmod'] & 0x01) {
				// If the lsb of acmod is a 1, center channel is in use and cmixlev follows in the bit stream.
				$thisfile_ac3_raw_bsi['cmixlev'] = bindec(substr($AC3header['bsi'], $ac3_bsi_offset, 2));
				$ac3_bsi_offset += 2;
				$thisfile_ac3['center_mix_level'] = $this->AC3centerMixLevelLookup($thisfile_ac3_raw_bsi['cmixlev']);
			}

			if ($thisfile_ac3_raw_bsi['acmod'] & 0x04) {
				// If the msb of acmod is a 1, surround channels are in use and surmixlev follows in the bit stream.
				$thisfile_ac3_raw_bsi['surmixlev'] = bindec(substr($AC3header['bsi'], $ac3_bsi_offset, 2));
				$ac3_bsi_offset += 2;
				$thisfile_ac3['surround_mix_level'] = $this->AC3surroundMixLevelLookup($thisfile_ac3_raw_bsi['surmixlev']);
			}

			if ($thisfile_ac3_raw_bsi['acmod'] == 0x02) {
				// When operating in the two channel mode, this 2-bit code indicates whether or not the program has been encoded in Dolby Surround.
				$thisfile_ac3_raw_bsi['dsurmod'] = bindec(substr($AC3header['bsi'], $ac3_bsi_offset, 2));
				$ac3_bsi_offset += 2;
				$thisfile_ac3['dolby_surround_mode'] = $this->AC3dolbySurroundModeLookup($thisfile_ac3_raw_bsi['dsurmod']);
			}

			$thisfile_ac3_raw_bsi['lfeon'] = (bool) bindec(substr($AC3header['bsi'], $ac3_bsi_offset, 1));
			$ac3_bsi_offset += 1;
			$thisfile_ac3['lfe_enabled'] = $thisfile_ac3_raw_bsi['lfeon'];
			if ($thisfile_ac3_raw_bsi['lfeon']) {
				//$ThisFileInfo['audio']['channels']++;
				$ThisFileInfo['audio']['channels'] .= '.1';
			}

			$thisfile_ac3['channels_enabled'] = $this->AC3channelsEnabledLookup($thisfile_ac3_raw_bsi['acmod'], $thisfile_ac3_raw_bsi['lfeon']);

			// This indicates how far the average dialogue level is below digital 100 percent. Valid values are 1�31.
			// The value of 0 is reserved. The values of 1 to 31 are interpreted as -1 dB to -31 dB with respect to digital 100 percent.
			$thisfile_ac3_raw_bsi['dialnorm'] = bindec(substr($AC3header['bsi'], $ac3_bsi_offset, 5));
			$ac3_bsi_offset += 5;
			$thisfile_ac3['dialogue_normalization'] = '-'.$thisfile_ac3_raw_bsi['dialnorm'].'dB';

			$thisfile_ac3_raw_bsi['compre_flag'] = (bool) bindec(substr($AC3header['bsi'], $ac3_bsi_offset, 1));
			$ac3_bsi_offset += 1;
			if ($thisfile_ac3_raw_bsi['compre_flag']) {
				$thisfile_ac3_raw_bsi['compr'] = bindec(substr($AC3header['bsi'], $ac3_bsi_offset, 8));
				$ac3_bsi_offset += 8;
				$thisfile_ac3['heavy_compression'] = $this->AC3heavyCompression($thisfile_ac3_raw_bsi['compr']);
			}

			$thisfile_ac3_raw_bsi['langcode_flag'] = (bool) bindec(substr($AC3header['bsi'], $ac3_bsi_offset, 1));
			$ac3_bsi_offset += 1;
			if ($thisfile_ac3_raw_bsi['langcode_flag']) {
				$thisfile_ac3_raw_bsi['langcod'] = bindec(substr($AC3header['bsi'], $ac3_bsi_offset, 8));
				$ac3_bsi_offset += 8;
			}

			$thisfile_ac3_raw_bsi['audprodie'] = (bool) bindec(substr($AC3header['bsi'], $ac3_bsi_offset, 1));
			$ac3_bsi_offset += 1;
			if ($thisfile_ac3_raw_bsi['audprodie']) {
				$thisfile_ac3_raw_bsi['mixlevel'] = bindec(substr($AC3header['bsi'], $ac3_bsi_offset, 5));
				$ac3_bsi_offset += 5;
				$thisfile_ac3_raw_bsi['roomtyp']  = bindec(substr($AC3header['bsi'], $ac3_bsi_offset, 2));
				$ac3_bsi_offset += 2;

				$thisfile_ac3['mixing_level'] = (80 + $thisfile_ac3_raw_bsi['mixlevel']).'dB';
				$thisfile_ac3['room_type']    = $this->AC3roomTypeLookup($thisfile_ac3_raw_bsi['roomtyp']);
			}

			if ($thisfile_ac3_raw_bsi['acmod'] == 0x00) {
				// If acmod is 0, then two completely independent program channels (dual mono)
				// are encoded into the bit stream, and are referenced as Ch1, Ch2. In this case,
				// a number of additional items are present in BSI or audblk to fully describe Ch2.


				// This indicates how far the average dialogue level is below digital 100 percent. Valid values are 1�31.
				// The value of 0 is reserved. The values of 1 to 31 are interpreted as -1 dB to -31 dB with respect to digital 100 percent.
				$thisfile_ac3_raw_bsi['dialnorm2'] = bindec(substr($AC3header['bsi'], $ac3_bsi_offset, 5));
				$ac3_bsi_offset += 5;
				$thisfile_ac3['dialogue_normalization2'] = '-'.$thisfile_ac3_raw_bsi['dialnorm2'].'dB';

				$thisfile_ac3_raw_bsi['compre_flag2'] = (bool) bindec(substr($AC3header['bsi'], $ac3_bsi_offset, 1));
				$ac3_bsi_offset += 1;
				if ($thisfile_ac3_raw_bsi['compre_flag2']) {
					$thisfile_ac3_raw_bsi['compr2'] = bindec(substr($AC3header['bsi'], $ac3_bsi_offset, 8));
					$ac3_bsi_offset += 8;
					$thisfile_ac3['heavy_compression2'] = $this->AC3heavyCompression($thisfile_ac3_raw_bsi['compr2']);
				}

				$thisfile_ac3_raw_bsi['langcode_flag2'] = (bool) bindec(substr($AC3header['bsi'], $ac3_bsi_offset, 1));
				$ac3_bsi_offset += 1;
				if ($thisfile_ac3_raw_bsi['langcode_flag2']) {
					$thisfile_ac3_raw_bsi['langcod2'] = bindec(substr($AC3header['bsi'], $ac3_bsi_offset, 8));
					$ac3_bsi_offset += 8;
				}

				$thisfile_ac3_raw_bsi['audprodie2'] = (bool) bindec(substr($AC3header['bsi'], $ac3_bsi_offset, 1));
				$ac3_bsi_offset += 1;
				if ($thisfile_ac3_raw_bsi['audprodie2']) {
					$thisfile_ac3_raw_bsi['mixlevel2'] = bindec(substr($AC3header['bsi'], $ac3_bsi_offset, 5));
					$ac3_bsi_offset += 5;
					$thisfile_ac3_raw_bsi['roomtyp2']  = bindec(substr($AC3header['bsi'], $ac3_bsi_offset, 2));
					$ac3_bsi_offset += 2;

					$thisfile_ac3['mixing_level2'] = (80 + $thisfile_ac3_raw_bsi['mixlevel2']).'dB';
					$thisfile_ac3['room_type2']    = $this->AC3roomTypeLookup($thisfile_ac3_raw_bsi['roomtyp2']);
				}

			}

			$thisfile_ac3_raw_bsi['copyright'] = (bool) bindec(substr($AC3header['bsi'], $ac3_bsi_offset, 1));
			$ac3_bsi_offset += 1;

			$thisfile_ac3_raw_bsi['original']  = (bool) bindec(substr($AC3header['bsi'], $ac3_bsi_offset, 1));
			$ac3_bsi_offset += 1;

			$thisfile_ac3_raw_bsi['timecode1_flag'] = (bool) bindec(substr($AC3header['bsi'], $ac3_bsi_offset, 1));
			$ac3_bsi_offset += 1;
			if ($thisfile_ac3_raw_bsi['timecode1_flag']) {
				$thisfile_ac3_raw_bsi['timecode1'] = bindec(substr($AC3header['bsi'], $ac3_bsi_offset, 14));
				$ac3_bsi_offset += 14;
			}

			$thisfile_ac3_raw_bsi['timecode2_flag'] = (bool) bindec(substr($AC3header['bsi'], $ac3_bsi_offset, 1));
			$ac3_bsi_offset += 1;
			if ($thisfile_ac3_raw_bsi['timecode2_flag']) {
				$thisfile_ac3_raw_bsi['timecode2'] = bindec(substr($AC3header['bsi'], $ac3_bsi_offset, 14));
				$ac3_bsi_offset += 14;
			}

			$thisfile_ac3_raw_bsi['addbsi_flag'] = (bool) bindec(substr($AC3header['bsi'], $ac3_bsi_offset, 1));
			$ac3_bsi_offset += 1;
			if ($thisfile_ac3_raw_bsi['addbsi_flag']) {
				$thisfile_ac3_raw_bsi['addbsi_length'] = bindec(substr($AC3header['bsi'], $ac3_bsi_offset, 6));
				$ac3_bsi_offset += 6;

				$AC3header['bsi'] .= getid3_lib::BigEndian2Bin(fread($fd, $thisfile_ac3_raw_bsi['addbsi_length']));

				$thisfile_ac3_raw_bsi['addbsi_data'] = substr($AC3header['bsi'], $ac3_bsi_offset, $thisfile_ac3_raw_bsi['addbsi_length'] * 8);
				$ac3_bsi_offset += $thisfile_ac3_raw_bsi['addbsi_length'] * 8;
			}

		}

		return true;
	}


	function AC3sampleRateCodeLookup($fscod) {
		static $AC3sampleRateCodeLookup = array(
			0 => 48000,
			1 => 44100,
			2 => 32000,
			3 => 'reserved' // If the reserved code is indicated, the decoder should not attempt to decode audio and should mute.
		);
		return (isset($AC3sampleRateCodeLookup[$fscod]) ? $AC3sampleRateCodeLookup[$fscod] : false);
	}

	function AC3serviceTypeLookup($bsmod, $acmod) {
		static $AC3serviceTypeLookup = array();
		if (empty($AC3serviceTypeLookup)) {
			for ($i = 0; $i <= 7; $i++) {
				$AC3serviceTypeLookup[0][$i] = 'main audio service: complete main (CM)';
				$AC3serviceTypeLookup[1][$i] = 'main audio service: music and effects (ME)';
				$AC3serviceTypeLookup[2][$i] = 'associated service: visually impaired (VI)';
				$AC3serviceTypeLookup[3][$i] = 'associated service: hearing impaired (HI)';
				$AC3serviceTypeLookup[4][$i] = 'associated service: dialogue (D)';
				$AC3serviceTypeLookup[5][$i] = 'associated service: commentary (C)';
				$AC3serviceTypeLookup[6][$i] = 'associated service: emergency (E)';
			}

			$AC3serviceTypeLookup[7][1]      = 'associated service: voice over (VO)';
			for ($i = 2; $i <= 7; $i++) {
				$AC3serviceTypeLookup[7][$i] = 'main audio service: karaoke';
			}
		}
		return (isset($AC3serviceTypeLookup[$bsmod][$acmod]) ? $AC3serviceTypeLookup[$bsmod][$acmod] : false);
	}

	function AC3audioCodingModeLookup($acmod) {
		static $AC3audioCodingModeLookup = array();
		if (empty($AC3audioCodingModeLookup)) {
			// array(channel configuration, # channels (not incl LFE), channel order)
			$AC3audioCodingModeLookup = array (
				0 => array('channel_config'=>'1+1', 'num_channels'=>2, 'channel_order'=>'Ch1,Ch2'),
				1 => array('channel_config'=>'1/0', 'num_channels'=>1, 'channel_order'=>'C'),
				2 => array('channel_config'=>'2/0', 'num_channels'=>2, 'channel_order'=>'L,R'),
				3 => array('channel_config'=>'3/0', 'num_channels'=>3, 'channel_order'=>'L,C,R'),
				4 => array('channel_config'=>'2/1', 'num_channels'=>3, 'channel_order'=>'L,R,S'),
				5 => array('channel_config'=>'3/1', 'num_channels'=>4, 'channel_order'=>'L,C,R,S'),
				6 => array('channel_config'=>'2/2', 'num_channels'=>4, 'channel_order'=>'L,R,SL,SR'),
				7 => array('channel_config'=>'3/2', 'num_channels'=>5, 'channel_order'=>'L,C,R,SL,SR')
			);
		}
		return (isset($AC3audioCodingModeLookup[$acmod]) ? $AC3audioCodingModeLookup[$acmod] : false);
	}

	function AC3centerMixLevelLookup($cmixlev) {
		static $AC3centerMixLevelLookup;
		if (empty($AC3centerMixLevelLookup)) {
			$AC3centerMixLevelLookup = array(
				0 => pow(2, -3.0 / 6), // 0.707 (�3.0 dB)
				1 => pow(2, -4.5 / 6), // 0.595 (�4.5 dB)
				2 => pow(2, -6.0 / 6), // 0.500 (�6.0 dB)
				3 => 'reserved'
			);
		}
		return (isset($AC3centerMixLevelLookup[$cmixlev]) ? $AC3centerMixLevelLookup[$cmixlev] : false);
	}

	function AC3surroundMixLevelLookup($surmixlev) {
		static $AC3surroundMixLevelLookup;
		if (empty($AC3surroundMixLevelLookup)) {
			$AC3surroundMixLevelLookup = array(
				0 => pow(2, -3.0 / 6),
				1 => pow(2, -6.0 / 6),
				2 => 0,
				3 => 'reserved'
			);
		}
		return (isset($AC3surroundMixLevelLookup[$surmixlev]) ? $AC3surroundMixLevelLookup[$surmixlev] : false);
	}

	function AC3dolbySurroundModeLookup($dsurmod) {
		static $AC3dolbySurroundModeLookup = array(
			0 => 'not indicated',
			1 => 'Not Dolby Surround encoded',
			2 => 'Dolby Surround encoded',
			3 => 'reserved'
		);
		return (isset($AC3dolbySurroundModeLookup[$dsurmod]) ? $AC3dolbySurroundModeLookup[$dsurmod] : false);
	}

	function AC3channelsEnabledLookup($acmod, $lfeon) {
		$AC3channelsEnabledLookup = array(
			'ch1'=>(bool) ($acmod == 0),
			'ch2'=>(bool) ($acmod == 0),
			'left'=>(bool) ($acmod > 1),
			'right'=>(bool) ($acmod > 1),
			'center'=>(bool) ($acmod & 0x01),
			'surround_mono'=>false,
			'surround_left'=>false,
			'surround_right'=>false,
			'lfe'=>$lfeon);
		switch ($acmod) {
			case 4:
			case 5:
				$AC3channelsEnabledLookup['surround_mono']  = true;
				break;
			case 6:
			case 7:
				$AC3channelsEnabledLookup['surround_left']  = true;
				$AC3channelsEnabledLookup['surround_right'] = true;
				break;
		}
		return $AC3channelsEnabledLookup;
	}

	function AC3heavyCompression($compre) {
		// The first four bits indicate gain changes in 6.02dB increments which can be
		// implemented with an arithmetic shift operation. The following four bits
		// indicate linear gain changes, and require a 5-bit multiply.
		// We will represent the two 4-bit fields of compr as follows:
		//   X0 X1 X2 X3 . Y4 Y5 Y6 Y7
		// The meaning of the X values is most simply described by considering X to represent a 4-bit
		// signed integer with values from �8 to +7. The gain indicated by X is then (X + 1) * 6.02 dB. The
		// following table shows this in detail.

		// Meaning of 4 msb of compr
		//  7    +48.16 dB
		//  6    +42.14 dB
		//  5    +36.12 dB
		//  4    +30.10 dB
		//  3    +24.08 dB
		//  2    +18.06 dB
		//  1    +12.04 dB
		//  0     +6.02 dB
		// -1         0 dB
		// -2     �6.02 dB
		// -3    �12.04 dB
		// -4    �18.06 dB
		// -5    �24.08 dB
		// -6    �30.10 dB
		// -7    �36.12 dB
		// -8    �42.14 dB

		$fourbit = str_pad(decbin(($compre & 0xF0) >> 4), 4, '0', STR_PAD_LEFT);
		if ($fourbit{0} == '1') {
			$log_gain = -8 + bindec(substr($fourbit, 1));
		} else {
			$log_gain = bindec(substr($fourbit, 1));
		}
		$log_gain = ($log_gain + 1) * getid3_lib::RGADamplitude2dB(2);

		// The value of Y is a linear representation of a gain change of up to �6 dB. Y is considered to
		// be an unsigned fractional integer, with a leading value of 1, or: 0.1 Y4 Y5 Y6 Y7 (base 2). Y can
		// represent values between 0.111112 (or 31/32) and 0.100002 (or 1/2). Thus, Y can represent gain
		// changes from �0.28 dB to �6.02 dB.

		$lin_gain = (16 + ($compre & 0x0F)) / 32;

		// The combination of X and Y values allows compr to indicate gain changes from
		//  48.16 � 0.28 = +47.89 dB, to
		// �42.14 � 6.02 = �48.16 dB.

		return $log_gain - $lin_gain;
	}

	function AC3roomTypeLookup($roomtyp) {
		static $AC3roomTypeLookup = array(
			0 => 'not indicated',
			1 => 'large room, X curve monitor',
			2 => 'small room, flat monitor',
			3 => 'reserved'
		);
		return (isset($AC3roomTypeLookup[$roomtyp]) ? $AC3roomTypeLookup[$roomtyp] : false);
	}

	function AC3frameSizeLookup($frmsizecod, $fscod) {
		$padding     = (bool) ($frmsizecod % 2);
		$framesizeid =   floor($frmsizecod / 2);

		static $AC3frameSizeLookup = array();
		if (empty($AC3frameSizeLookup)) {
			$AC3frameSizeLookup = array (
				0  => array(128, 138, 192),
				1  => array(40, 160, 174, 240),
				2  => array(48, 192, 208, 288),
				3  => array(56, 224, 242, 336),
				4  => array(64, 256, 278, 384),
				5  => array(80, 320, 348, 480),
				6  => array(96, 384, 416, 576),
				7  => array(112, 448, 486, 672),
				8  => array(128, 512, 556, 768),
				9  => array(160, 640, 696, 960),
				10 => array(192, 768, 834, 1152),
				11 => array(224, 896, 974, 1344),
				12 => array(256, 1024, 1114, 1536),
				13 => array(320, 1280, 1392, 1920),
				14 => array(384, 1536, 1670, 2304),
				15 => array(448, 1792, 1950, 2688),
				16 => array(512, 2048, 2228, 3072),
				17 => array(576, 2304, 2506, 3456),
				18 => array(640, 2560, 2786, 3840)
			);
		}
		if (($fscod == 1) && $padding) {
			// frame lengths are padded by 1 word (16 bits) at 44100
			$AC3frameSizeLookup[$frmsizecod] += 2;
		}
		return (isset($AC3frameSizeLookup[$framesizeid][$fscod]) ? $AC3frameSizeLookup[$framesizeid][$fscod] : false);
	}

	function AC3bitrateLookup($frmsizecod) {
		$framesizeid =   floor($frmsizecod / 2);

		static $AC3bitrateLookup = array(
			0  => 32000,
			1  => 40000,
			2  => 48000,
			3  => 56000,
			4  => 64000,
			5  => 80000,
			6  => 96000,
			7  => 112000,
			8  => 128000,
			9  => 160000,
			10 => 192000,
			11 => 224000,
			12 => 256000,
			13 => 320000,
			14 => 384000,
			15 => 448000,
			16 => 512000,
			17 => 576000,
			18 => 640000
		);
		return (isset($AC3bitrateLookup[$framesizeid]) ? $AC3bitrateLookup[$framesizeid] : false);
	}


}

?>