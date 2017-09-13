<?
include 'inc.php';
$_SESSION['code']=time();
$im = @imagecreate (80, 20) or die ("Cannot initialize new GD image stream!"); 
$bg = imagecolorallocate ($im, 232, 238, 247); 
$char = substr(md5(md5($_SESSION['code'])),0,4); 
for ($i=0; $i<=128; $i++) { 
	$color = imagecolorallocate ($im, rand(0,255), rand(0,255), rand(0,255));
	imagesetpixel($im, rand(2,80), rand(2,20), $color);
} 
for ($i = 0; $i < strlen($char); $i++) { 
	$color = imagecolorallocate ($im, rand(0,255), rand(0,128), rand(0,255));
	$x = 5 + $i * 20; 
	$y = rand(1, 6); 
	imagechar ($im, 5, $x, $y, $char[$i], $color); 
} 
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); 
header("Cache-Control: no-store, no-cache, must-revalidate"); 
header("Cache-Control: post-check=0, pre-check=0", false); 
header("Pragma: no-cache"); 
header("Content-type: image/png"); 
imagepng($im); 
imagedestroy ($im);
?>
