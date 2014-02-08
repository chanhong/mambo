<?php

$c_name			= 'mos_captcha';
$c_width		= '250';
$c_height		= '70';
$c_imgtype	= 'png';
$c_codetype = 'true';

	/**
	* Spam Protection - Code Image Generator - 2006 Dominik Paulus
	* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
	* @author: Dominik Paulus, [email]mail@dpaulus.de[/email]
	* @date: 06/24/06
	* @version: 3.4c
	*
	*/

	// Imagecolors
	$colors = array(
	255, 244, 234, // Background
	255, 128, 0,   // Code
	255, 128, 0,   // Vertical Lines
	255, 128, 0    // Border (Last value without ',')
	);

	$x = $c_width;
	$y = $c_height;
	$y2 = $y/2; $x2 = $x/2;

	session_name($c_name);
	session_start();
	$_SESSION['img'] = "OK";  // debug
	
	mt_srand((double)microtime()*1000000);

	// Fontsetup
	$font = './captchaFonts/font1.ttf';

	// Numerical code
	if($c_codetype)
		$seccode = strval(mt_rand(10000, 99999));
	else {
		$string = "abcdefghjkmnpqrstuvwxyz0123456789";
		$stringlen = strlen($string);
		$seccode = "";
		for($i = 0; $i < 5; $i++)
		$seccode .= $string{mt_rand(0, $stringlen)};
	}

	$_SESSION['code'] = $seccode;
	$clen = strlen($seccode);

	// create image
	$im = ImageCreateTrueColor($x, $y) or die('ImageCreate error!');

	// Image colors
	$bgcolor     = ImageColorAllocate($im, $colors[0], $colors[1], $colors[2]);
	$fontcolor   = ImageColorAllocate($im, $colors[3], $colors[4], $colors[5]);
	$linecolor   = ImageColorAllocate($im, $colors[6], $colors[7], $colors[8]);
	$bordercolor = ImageColorAllocate($im, $colors[9], $colors[10], $colors[11]);
	$alphacolor  = ImageColorAllocate($im, 0, 255, 0);
	ImageFill($im, 0, 0, $bgcolor);
	
	// Code
	$xspace =70;
	$yspace = 60;
	$size = 25;
	$angle = 20;
	
	// Morph
	function morph($im, $sx, $sy, $w, $h) {

		$morphx = $h;
		$morphy = mt_rand(3.5,5.2);
		$mx = $sx;
		$my = $sy;
		$mvalues = array();

		for($i = 0; $i < $morphx/2; $i++) {
			$mvalues[] = $mx-(log($i+1)*$morphy);
			ImageCopyMerge($im, $im, $mvalues[$i], $my+$i, $mx, $my+$i, $w+20, 1, 0);
		}

		$mvalues = array_reverse($mvalues);
		$mvcount = count($mvalues);
		for($i = 0; $i < $mvcount; $i++) {
			ImageCopyMerge($im, $im, $mvalues[$i], $my+$i+$mvcount, $mx, $my+$i+$mvcount, $w+20, 1, 0);
		}

	}	
	
	$ttfborders = array();
	for($i = 0; $i < $clen; $i++) {
		$tmp = ImageCreateTrueColor($xspace,$yspace);
		ImageFill($tmp, 0, 0, $bgcolor);
		$ttfborders[] = ImageTTFText($tmp, $size+mt_rand(0, 8), mt_rand(-$angle, $angle), 20,
		$yspace-10, $fontcolor, $font, $seccode{$i}
		);
		morph($tmp, 0, 0, 20, 20);
		ImageColorTransparent($tmp, $bgcolor);
		ImageCopyMerge($im, $tmp, ($i)*50, 2, 0, 0, $xspace, $yspace, 100);
		ImageDestroy($tmp);
	}

	// Wave
	ImageSetThickness($im, 3);
	$ux = $uy = 0;
	$vx = 0; //mt_rand(10,15);
	$vy = mt_rand($y2-3, $y2+3);

	for($i = 0; $i < 10; $i++) {
		$ux = $vx + mt_rand(20,30);
		$uy = mt_rand($y2-8,$y2+8);
		ImageSetThickness($im, mt_rand(1,2));
		ImageLine($im, $vx, $vy, $ux, $uy, $linecolor);
		$vx = $ux;
		$vy = $uy;
	}
	ImageLine($im, $vx, $vy, $x, $y2, $linecolor);

	// Triangle
	ImageSetThickness($im, 3);
	$ux = mt_rand($x2-10, $x2+10);
	$uy = mt_rand($y2-10, $y2-30);
	ImageLine($im, mt_rand(10,$x2-20), $y, $ux, $uy, $linecolor);
	ImageSetThickness($im, 1);
	ImageLine($im,  mt_rand($x2+20,$x-10), $y, $ux, $uy, $linecolor);
	ImageSetThickness($im, 1);

	// Border
	ImageSetThickness($im, 1);
	ImageLine($im, 0, 0, 0, $y, $bordercolor); // left
	ImageLine($im, 0, 0, $x, 0, $bordercolor); // top
	ImageLine($im, 0, $y-1, $x, $y-1, $bordercolor); // bottom
	ImageLine($im, $x-1, 0, $x-1, $y-1, $bordercolor); // right

	for($i = $x/$clen; $i < $x; $i+=$x/$clen)
	ImageLine($im, $i, 0, $i, $y, $bordercolor);

	switch($c_imgtype) {
		case 'jpeg':
			Header("Content-Type: image/jpeg");
			ImageJPEG($im,"",75);	
			break;
		case 'png':
			Header("Content-Type: image/png");
			ImagePNG($im);
			break;
		case 'gif':
			Header("Content-Type: image/gif");
			ImageGIF($im);
			break;
		default:
			die("Wrong \$type in captcha.php (should be jpeg, png or gif)\n");
	}
	
	ImageDestroy($im);
?>
