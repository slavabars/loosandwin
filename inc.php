<?

session_name('hash');
session_start();
error_reporting(0);

header("Content-type:text/html; charset=utf-8");

if (isset($_SESSION['code'])){
	$_SESSION['code']=check($_SESSION['code']);
}

class valid {
	function mail($mail){
		if (preg_match("|^[0-9a-z_]+@[0-9a-z_^\.]+\.[a-z]{2,4}$|i",$mail)){
			return true;
		}else{
			return false;
		}
	}
} 

function m_encode($txt, $encoding='UTF-8') { 
	return '=?'.$encoding.'?B?'.base64_encode($txt).'?='; 
}

function mysqldb () {
	@mysql_connect("localhost", "loosandwin_w", "kPGzIo1w") or (print(MYSQL_CON_ERROR) and exit());
	@mysql_select_db("loosandwin_q") or (print(MYSQL_DB_ERROR) and exit());
	@mysql_query("SET NAMES 'utf8′"); 
	@mysql_query("SET CHARACTER SET 'utf8′");
}

function check($message){ 
	$message=str_replace("|","I",$message); 
	$message=str_replace("||","I",$message);
	$message=htmlspecialchars($message);                  
	$message=str_replace("'","&#39;",$message);            
	$message=str_replace("\"","&#34;",$message);  
	$message=str_replace("\$","&#36;",$message);    
	$message=str_replace("$","&#36;",$message);          
	$message=str_replace("\\","&#92;", $message);
	$message=str_replace("`","", $message);  
	$message=str_replace("^","&#94;", $message);   
	$message=str_replace("%","&#37;", $message);  
	$message=str_replace(":","&#58;",$message);  
	$message=preg_replace("|&#58;|",":",$message,3); 
	$message=str_replace("&#92;&quot;","&quot;",$message);  
	$message=stripslashes(trim($message));               
	return $message;
}

function resize($img, $thumb_width, $newfilename)  
{  
	global $err;
  $max_width=$thumb_width; 
    if (!extension_loaded('gd') && !extension_loaded('gd2'))  
    { 
        trigger_error("GD is not loaded", E_USER_WARNING); 
        return false; 
    } 
    list($width_orig, $height_orig, $image_type) = getimagesize($img); 
    switch ($image_type)  
    { 
        case 1: $im = imagecreatefromgif($img); break; 
        case 2: $im = imagecreatefromjpeg($img);  break; 
        case 3: $im = imagecreatefrompong($img); break; 
        default:  $err[]=NO_FOTO_FORMAT; $newfilename=false; break; 
    } 
    $aspect_ratio = (float) $height_orig / $width_orig; 
    $thumb_height = round($thumb_width * $aspect_ratio); 
    while($thumb_height>$max_width) 
    { 
        $thumb_width-=1; 
        $thumb_height = round($thumb_width * $aspect_ratio); 
    } 
    $newImg = imagecreatetruecolor($thumb_width, $thumb_height); 
    if(($image_type == 1) OR ($image_type==3)) 
    { 
        imagealphablending($newImg, false); 
        imagesavealpha($newImg,true); 
        $transparent = imagecolorallocatealpha($newImg, 255, 255, 255, 127); 
        imagefilledrectangle($newImg, 0, 0, $thumb_width, $thumb_height, $transparent); 
    } 
    imagecopyresampled($newImg, $im, 0, 0, 0, 0, $thumb_width, $thumb_height, $width_orig, $height_orig); 
    switch ($image_type)  
    { 
        case 1: imagegif($newImg,$newfilename); break; 
        case 2: imagejpeg($newImg,$newfilename);  break; 
        case 3: imagepng($newImg,$newfilename); break; 
        default:  $err[]=FOTO_ERROR_HACK; $newfilename=false; break; 
		imagedestroy ($newImg);
    } 
    return $newfilename; 
}
?>