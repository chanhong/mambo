<?php
/**
* Captcha audio handling for Mambo
* @package Mambo
* @author Mambo Foundation Inc see README.php
* @copyright Mambo Foundation Inc.
* See COPYRIGHT.php for copyright notices and details.
* @license GNU/GPL Version 2, see LICENSE.php
* Mambo is free software; you can redistribute it and/or
* modify it under the terms of the GNU General Public License
* as published by the Free Software Foundation; version 2 of the License.
*/

defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

global $mosConfig_lang, $mosConfig_absolute_path;

session_name('mos_captcha');
session_start();
$code = $_SESSION['code'];

// language select (for future addition of own wav files)
$lang = $mosConfig_absolute_path.'/includes/captchaAudio/'.$mosConfig_lang.'/';
if (!is_dir($lang)) {
	$lang = $mosConfig_absolute_path.'/includes/captchaAudio/en/';
}

$wavs = array();

for($i=0;$i<5;$i++){
	$file = $lang.$code{$i}.'.wav';
    $wavs[] = $file;
}

//$totalsize = filesize($filename);
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header('Content-type: audio/x-wav');
header("Content-Transfer-Encoding: binary");
//header("Content-Length: ".$totalsize);
header('Content-Disposition: attachment;filename=captcha.wav');

echo joinwavs($wavs);

/**
 * CAPTCHA antispam plugin - sound generator
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author     Andreas Gohr <gohr@cosmocode.de>
 */

function joinwavs($wavs){
    $fields = join('/',array( 'H8ChunkID', 'VChunkSize', 'H8Format',
                              'H8Subchunk1ID', 'VSubchunk1Size',
                              'vAudioFormat', 'vNumChannels', 'VSampleRate',
                              'VByteRate', 'vBlockAlign', 'vBitsPerSample' ));

    $data = '';

    foreach($wavs as $wav){
        $fp     = fopen($wav,'rb');
        $header = fread($fp,36);
        $info   = unpack($fields,$header);
        if($info['Subchunk1Size'] > 16){
            $header .= fread($fp,($info['Subchunk1Size']-16));
        }
        $header .= fread($fp,4);
        $size  = unpack('vsize',fread($fp, 4));
        $size  = $size['size'];
        $data .= fread($fp,$size);
    }

    return $header.pack('V',strlen($data)).$data;
}

?>