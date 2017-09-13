<?
include 'inc.php';
include 'lang/lang.php';
$menu_st='faq';
include 'menu.php';
$valid=new valid;
mysqldb ();

$count=mysql_fetch_row(mysql_query("select count(*) from user where status='1'"));

if (isset($_POST['text'])){
	if (isset($_POST['text']) && $_POST['code']==substr(md5(md5(check($_SESSION['code']))),0,4)){
		$echo.='<h1>'.OBQ.'</h1>';
		mysql_query("insert into faq (vopros,status) value ('".check($_POST['text'])."','0')");
		unset($_SESSION['code']);
		session_unregister($_SESSION['code']);
	} else {
		$echo.='<h1>'.OBW.'</h1>';
	}
}

$_SESSION['code']=uniqid("");

$faq = mysql_query("select * from faq where status='1' order by id desc");
for ($c=0; $c<mysql_num_rows($faq); $c++){
	$faq_result = mysql_fetch_array($faq);
	$echo.='<code><b>'.VORR.'</b>: '.$faq_result['vopros'].'<br />
	<b>'.OTVET.'</b>: '.$faq_result['otvet'].'</code>';
}

$echo.='<br /><form action="./faq.php" method="post"><br />
<textarea name="text"></textarea><br />
<div style="width:90px; float: left;">'.OBI.': </div><input type="text" name="code" /><br />
<div style="width:90px; float: left;">&nbsp;</div><img src="capcha.php"><br /><br />
<input type="submit" class="button" value="'.ZADDV.'" /><br /><br />
</form>
<code>'.ADMINMNO.'</code>';

mysql_close();

include 'pageloos.php';
?>