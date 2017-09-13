<?
include 'inc.php';
include 'lang/lang.php';
include 'menu.php';
$valid=new valid;
mysqldb ();

$count=mysql_fetch_row(mysql_query("select count(*) from user where status='1'"));

if (isset($_POST['text'])){
	if ($_POST['text']!='' && $_POST['code']==substr(md5(md5(check($_SESSION['code']))),0,4)){
		$echo.='<h1>'.OBQ.'</h1>';
		
		if ($_POST['who']=='1'){
			$mheaders='MIME-Version: 1.0' . "\r\n"; 
			$mheaders.='Content-type: text/html; charset=utf-8' . "\r\n"; 
			$mheaders.='To: svenltd@mail.ru' . "\r\n"; 
			$mheaders.='From: loosandwin.ru <reg@loosandwin.ru>' . "\r\n"; 
			$subject=m_encode('Сообщение с сайта loosandwin.ru');
			$message=nl2br(check($_POST['text']));
		mail('svenltd@mail.ru', $subject, $message, $mheaders);
		}else{
			$mheaders='MIME-Version: 1.0' . "\r\n"; 
			$mheaders.='Content-type: text/html; charset=utf-8' . "\r\n"; 
			$mheaders.='To: i@slavabars.ru' . "\r\n"; 
			$mheaders.='From: loosandwin.ru <reg@loosandwin.ru>' . "\r\n"; 
			$subject=m_encode('Сообщение с сайта loosandwin.ru');
			$message=nl2br(check($_POST['text']));
		mail('i@slavabars.ru', $subject, $message, $mheaders);
		}
		
		unset($_SESSION['code']);
		session_unregister($_SESSION['code']);
	} else {
		$echo.='<h1>'.OBW.'</h1>';
	}
}

$_SESSION['code']=uniqid("");

$echo.='<code>Барсуков Вячеслав ('.OBE.')<br />icq: 703-913<br /><br />Серов Иван ('.OBR.')<br />icq: 400-091-436</code>';
$echo.='<br /><form action="./obsv.php" method="post"><br />
<div style="width:90px; float: left;">'.OBE.': </div><select name="who" size="1"><option value="0">Барсуков Вячеслав ('.OBE.')</option><option value="1">Серов Иван ('.OBR.')</option></select><br />
<div style="width:90px; float: left;">'.OBT.': </div><textarea name="text"></textarea><br />
<div style="width:90px; float: left;">'.OBI.': </div><input type="text" name="code" /><br />
<div style="width:90px; float: left;">&nbsp;</div><img src="capcha.php"><br /><br />
<input type="submit" class="button" value="'.OBY.'" /><br /><br />
</form>
<code>'.OBU.'</code>';

mysql_close();

include 'pageloos.php';
?>