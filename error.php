<?
include 'inc.php';
include 'lang/lang.php';
include 'menu.php';
$valid=new valid;
mysqldb ();

$count=mysql_fetch_row(mysql_query("select count(*) from user where status='1'"));

if (isset($_POST['text'])){
	if (isset($_POST['text']) && $_POST['code']==substr(md5(md5(check($_SESSION['code']))),0,4)){
		$echo.='<h1>'.OBQ.'</h1>';
		mysql_query("insert into error (error) value ('".check($_POST['text'])."')");
		unset($_SESSION['code']);
		session_unregister($_SESSION['code']);
	} else {
		$echo.='<h1>'.OBW.'</h1>';
	}
}

$_SESSION['code']=uniqid("");

$echo.='<br /><form action="./error.php" method="post"><br />
<textarea name="text"></textarea><br />
<div style="width:90px; float: left;">'.OBI.': </div><input type="text" name="code" /><br />
<div style="width:90px; float: left;">&nbsp;</div><img src="capcha.php"><br /><br />
<input type="submit" class="button" value="'.OBY.'" /><br /><br />
</form>
';

mysql_close();

include 'pageloos.php';
?>