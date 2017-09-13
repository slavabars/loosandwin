<?
include 'inc.php';
include 'lang/lang.php';
$menu_st='site';
include 'menu.php';
$valid=new valid;
mysqldb ();

$count=mysql_fetch_row(mysql_query("select count(*) from user where status='1'"));

$echo.='<code>'.nl2br(OSITESS).'</code>';

mysql_close();

include 'pageloos.php';
?>