<?
if (check($_GET['lang'])!=''){
	setcookie('lang', check($_GET['lang']), time()+60*600*600);
	header('location: ./');
	exit();
}

switch (check($_COOKIE['lang'])) {
	case 'russia':
		include 'russian.php';
	break;
	
	case 'english':
		include 'english.php';
	break;
	
	case 'unkrainsky':
		include 'unkrainsky.php';
	break;
	
	case 'deutsch':
		include 'deutsch.php';
	break;
	
	default:
		include 'russian.php';
	break;
}

$lang_menu='<small><a href="./?lang=russia">Русский</a> <a href="./?lang=english">English</a> <a href="./?lang=unkrainsky">Українська</a><!--<a href="./?lang=deutsch">Deutsch</a>--></small>';
?>