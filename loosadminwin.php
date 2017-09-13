<?
include 'inc.php';
include 'lang/lang.php';
include 'menu.php';
$valid=new valid;
mysqldb ();

$count=mysql_fetch_row(mysql_query("select count(*) from user where status='1'"));

//print_r($_POST);

if (isset($_POST['login']) && $_POST['pass']!='') {
	$admin=mysql_fetch_assoc(mysql_query("select * from admin where login='".check($_POST['login'])."'"));
	if ($admin['login']==check($_POST['login']) && $admin['pass']==md5($_POST['pass'])) {
		setcookie('login', $admin['login'], time()+60*60*60);
		setcookie('pass', $admin['pass'], time()+60*60*60);
		mysql_query("update admin set ip='". $_SERVER['REMOTE_ADDR'] ."' where login='".$admin['login']."'");
		header('location: ./loosadminwin.php');
		exit();
	}
}

if ($_COOKIE['login']!='' && $_COOKIE['pass']!=''){
	$admin=mysql_fetch_assoc(mysql_query("select * from admin where login='".check($_COOKIE['login'])."'"));
	if ($admin['ip']!=$_SERVER['REMOTE_ADDR']){
		setcookie('login');
		setcookie('pass');
		header('location: ./loosadminwin.php');
		exit();
	}
} 

if ($_COOKIE['login']!='' && $_COOKIE['pass']!='') {
	$admin=mysql_fetch_assoc(mysql_query("select * from admin where login='".check($_COOKIE['login'])."'"));
	if ($_COOKIE['login']==$admin['login'] && $_COOKIE['pass']==$admin['pass']){
		$echo='<code>
		<a href="./loosadminwin.php?mod=user">Проверка пользователей</a> | <a href="./loosadminwin.php?mod=faq_no">FAQ</a> | <a href="./loosadminwin.php?mod=error">Ошибки</a>
		</code>';
		switch (check($_GET['mod'])) {
			
			case 'error':
				
				$error=mysql_num_rows(mysql_query("select * from error"));
				$num = 100;  
				$page = $_GET['page'];  
				$total = (int)(($faq - 1) / $num) + 1;  
				$page = (int)$_GET['page'];  
				if(empty($page) or $page < 0) {
					$page = 1;  
				}
				if($page > $total) {
					$page = $total;  
				}
				$start = $page * $num - $num;  
				$error = mysql_query("select * from error order by id desc LIMIT $start, $num");
				for ($c=0; $c<mysql_num_rows($error); $c++){
					$error_result = mysql_fetch_array($error);
					$echo.='<code>'.$error_result['error'].'</code>';
				}
				if ($page != 1) $pervpage = '<a href="./loosadminwin.php?mod=error&amp;page=1"><<</a>  
				   <a href="./loosadminwin.php?mod=error&amp;page='. ($page - 1) .'"><</a> ';  
				if ($page != $total) $nextpage = ' <a href="./loosadminwin.php?mod=error&amp;page='. ($page + 1) .'">></a>  
				   <a href="./loosadminwin.php?mod=error&amp;page=' .$total. '">>></a>';  
				if($page - 2 > 0) $page2left = ' <a href="./loosadminwin.php?mod=error&amp;page='. ($page - 2) .'&amp;">'. ($page - 2) .'</a> | ';  
				if($page - 1 > 0) $page1left = '<a href="./loosadminwin.php?mod=error&amp;page='. ($page - 1) .'">'. ($page - 1) .'</a> | ';  
				if($page + 2 <= $total) $page2right = ' | <a href="./loosadminwin.php?mod=error&amp;page='. ($page + 2) .'">'. ($page + 2) .'</a>';  
				if($page + 1 <= $total) $page1right = ' | <a href="./loosadminwin.php?mod=error&amp;page='. ($page + 1) .'">'. ($page + 1) .'</a>'; 
				$echo.='<br /><center>'.$pervpage.$page2left.$page1left.'<b>'.$page.'</b>'.$page1right.$page2right.$nextpage.'</center>'; 
				
			break;
			
			case 'search':
				
				if ($_POST['search']!=''){
					$echo.='Ну чуток не доделал =)))';
				} else {
					header('location: ./loosadminwin.php');
					exit();
				}
				
			break;
			
			case 'user':
				
				if($_GET['st']=='no'){
					$us=mysql_query("select * from user where id='". $_GET['id'] ."'");
					$us_result=mysql_fetch_array($us);
					if ($us_result['status']==0){
						unlink($us_result['foto']);
						$mheaders='MIME-Version: 1.0' . "\r\n"; 
						$mheaders.='Content-type: text/html; charset=utf-8' . "\r\n"; 
						$mheaders.='To: '.$us_result['mail'].'' . "\r\n"; 
						$mheaders.='From: loosandwin.ru <reg@loosandwin.ru>' . "\r\n"; 
						$subject=m_encode(''.OBIGG.' - loosandwin.ru');
						$message=nl2br(SFGBDG);
						mail($us_result['mail'], $subject, $message, $mheaders);
						mysql_query("DELETE FROM user WHERE (id='".$_GET['id']."')");
						header('location: ./loosadminwin.php?mod=user');
						exit();
					}
				}
				
				if($_GET['st']=='yes'){
					$us=mysql_query("select * from user where id='". $_GET['id'] ."'");
					$us_result=mysql_fetch_array($us);
					if ($us_result['status']==0){
						mysql_query("update user set status='1' where (id='".$_GET['id']."')");
						if ($us_result['pol']==2){
							mysql_query("insert into female (uid,foto,ext,plus,minus) value ('".$_GET['id']."','".$us_result['foto']."','10000','0','0')");
						} else {
							mysql_query("insert into male (uid,foto,ext,plus,minus) value ('".$_GET['id']."','".$us_result['foto']."','10000','0','0')");
						}
						$mheaders='MIME-Version: 1.0' . "\r\n"; 
						$mheaders.='Content-type: text/html; charset=utf-8' . "\r\n"; 
						$mheaders.='To: '.$us_result['mail'].'' . "\r\n"; 
						$mheaders.='From: loosandwin.ru <reg@loosandwin.ru>' . "\r\n"; 
						$subject=m_encode(''.OBIGG.' - loosandwin.ru');
						$message=nl2br(GBDGBBD);
						mail($us_result['mail'], $subject, $message, $mheaders);
						header('location: ./loosadminwin.php?mod=user');
						exit();
					}
				}
				
				$user=mysql_num_rows(mysql_query("select * from user where status='0'"));
				$num = 10;  
				$page = $_GET['page'];  
				$total = (int)(($faq - 1) / $num) + 1;  
				$page = (int)$_GET['page'];  
				if(empty($page) or $page < 0) {
					$page = 1;  
				}
				if($page > $total) {
					$page = $total;  
				}
				$start = $page * $num - $num;  
				$user = mysql_query("select * from user where status='0' order by id desc LIMIT $start, $num");
				for ($c=0; $c<mysql_num_rows($user); $c++){
					$user_result = mysql_fetch_array($user);
					if ($user_result['pol']='1') {
						$user_result['pol']='муж';
					} else {
						$user_result['pol']='жен';
					}
					$echo.='<code>
						<img src="'.$user_result['foto'].'" alt="'.$user_result['id'].'" class="float-left" />
						Ф.И.О: <b>'.$user_result['fio'].'</b><br />
						Пол: <b>'.$user_result['pol'].'</b><br />
						Возраст: <b>'.$user_result['vozr'].'</b><br />
						E-mail: <b>'.$user_result['mail'].'</b><br /><br /><br />
						<a href="./loosadminwin.php?mod=user&amp;st=yes&amp;id='.$user_result['id'].'">все верно</a> | <a href="./loosadminwin.php?mod=user&amp;st=no&amp;id='.$user_result['id'].'">анкета неверна УДААЛИТЬ</a><br /><br />
						Внимание, - действие окончательное!
					</code>';
				}
				if ($page != 1) $pervpage = '<a href="./loosadminwin.php?mod=user&amp;page=1"><<</a>  
				   <a href="./loosadminwin.php?mod=user&amp;page='. ($page - 1) .'"><</a> ';  
				if ($page != $total) $nextpage = ' <a href="./loosadminwin.php?mod=user&amp;page='. ($page + 1) .'">></a>  
				   <a href="./loosadminwin.php?mod=user&amp;page=' .$total. '">>></a>';  
				if($page - 2 > 0) $page2left = ' <a href="./loosadminwin.php?mod=user&amp;page='. ($page - 2) .'&amp;">'. ($page - 2) .'</a> | ';  
				if($page - 1 > 0) $page1left = '<a href="./loosadminwin.php?mod=user&amp;page='. ($page - 1) .'">'. ($page - 1) .'</a> | ';  
				if($page + 2 <= $total) $page2right = ' | <a href="./loosadminwin.php?mod=user&amp;page='. ($page + 2) .'">'. ($page + 2) .'</a>';  
				if($page + 1 <= $total) $page1right = ' | <a href="./loosadminwin.php?mod=user&amp;page='. ($page + 1) .'">'. ($page + 1) .'</a>'; 
				$echo.='<br /><center>'.$pervpage.$page2left.$page1left.'<b>'.$page.'</b>'.$page1right.$page2right.$nextpage.'</center>'; 
				
			break;
			
			case 'faq_no';
				
				if ($_GET['s']=='del' && (int)$_GET['id']!=''){
					mysql_query("DELETE FROM faq WHERE (id='".(int)$_GET['id']."')");
				}
				
				if ($_GET['s']=='o' && check($_POST['text'])!='' && (int)$_GET['id']!=''){
					mysql_query("update faq set otvet='".check($_POST['text'])."',status='1' where id='".$_GET['id']."'");
					header('location: ./loosadminwin.php?mod=faq_no');
				}
				
				$echo.='<code><a href="./loosadminwin.php?mod=faq_no">FAQ без ответа</a> | <a href="./loosadminwin.php?mod=faq_yes">FAQ отвеченные</a></code>';
				
				$faq=mysql_num_rows(mysql_query("select * from faq where status='0'"));
				$num = 50;  
				$page = $_GET['page'];  
				$total = (int)(($faq - 1) / $num) + 1;  
				$page = (int)$_GET['page'];  
				if(empty($page) or $page < 0) {
					$page = 1;  
				}
				if($page > $total) {
					$page = $total;  
				}
				$start = $page * $num - $num;  
				$faq = mysql_query("select * from faq where status='0' order by id desc LIMIT $start, $num");
				for ($c=0; $c<mysql_num_rows($faq); $c++){
					$faq_result = mysql_fetch_array($faq);
					$echo.='<code>Вопрос: <a href="./loosadminwin.php?mod=faq_no&amp;s=del&amp;id='.$faq_result['id'].'">[удалить]</a><br />'.$faq_result['vopros'].'</code>
					<form action="./loosadminwin.php?mod=faq_no&amp;s=o&amp;id='.$faq_result['id'].'" method="post"><br />
					<textarea name="text"></textarea>
					<br /><br />
					<input type="submit" class="button" value="ответить" /><br /><br />
					</form>';
				}
				if ($page != 1) $pervpage = '<a href="./loosadminwin.php?mod=faq_no&amp;page=1"><<</a>  
				   <a href="./loosadminwin.php?mod=faq_no&amp;page='. ($page - 1) .'"><</a> ';  
				if ($page != $total) $nextpage = ' <a href="./loosadminwin.php?mod=faq_no&amp;page='. ($page + 1) .'">></a>  
				   <a href="./loosadminwin.php?mod=faq_no&amp;page=' .$total. '">>></a>';  
				if($page - 2 > 0) $page2left = ' <a href="./loosadminwin.php?mod=faq_no&amp;page='. ($page - 2) .'&amp;">'. ($page - 2) .'</a> | ';  
				if($page - 1 > 0) $page1left = '<a href="./loosadminwin.php?mod=faq_no&amp;page='. ($page - 1) .'">'. ($page - 1) .'</a> | ';  
				if($page + 2 <= $total) $page2right = ' | <a href="./loosadminwin.php?mod=faq_no&amp;page='. ($page + 2) .'">'. ($page + 2) .'</a>';  
				if($page + 1 <= $total) $page1right = ' | <a href="./loosadminwin.php?mod=faq_no&amp;page='. ($page + 1) .'">'. ($page + 1) .'</a>'; 
				$echo.='<br /><center>'.$pervpage.$page2left.$page1left.'<b>'.$page.'</b>'.$page1right.$page2right.$nextpage.'</center>'; 
				
			break;
			
			case 'faq_yes';
				
				if ($_GET['s']=='del' && (int)$_GET['id']!=''){
					mysql_query("DELETE FROM faq WHERE (id='".(int)$_GET['id']."')");
				}
				
				if ($_GET['s']=='o' && check($_POST['text'])!='' && (int)$_GET['id']!=''){
					mysql_query("update faq set otvet='".check($_POST['text'])."' where id='".$_GET['id']."'");
					header('location: ./loosadminwin.php?mod=faq_yes');
				}
				
				$echo.='<code><a href="./loosadminwin.php?mod=faq_no">FAQ без ответа</a> | <a href="./loosadminwin.php?mod=faq_yes">FAQ отвеченные</a></code>';
				
				$faq=mysql_num_rows(mysql_query("select * from faq where status='1'"));
				$num = 50;  
				$page = $_GET['page'];  
				$total = (int)(($faq - 1) / $num) + 1;  
				$page = (int)$_GET['page'];  
				if(empty($page) or $page < 0) {
					$page = 1;  
				}
				if($page > $total) {
					$page = $total;  
				}
				$start = $page * $num - $num;  
				$faq = mysql_query("select * from faq where status='1' order by id desc LIMIT $start, $num");
				for ($c=0; $c<mysql_num_rows($faq); $c++){
					$faq_result = mysql_fetch_array($faq);
					$echo.='<code>Вопрос: <a href="./loosadminwin.php?mod=faq_yes&amp;s=del&amp;id='.$faq_result['id'].'">[удалить]</a><br />'.$faq_result['vopros'].'</code>
					<form action="./loosadminwin.php?mod=faq_yes&amp;s=o&amp;id='.$faq_result['id'].'" method="post"><br />
					<textarea name="text">'.$faq_result['otvet'].'</textarea>
					<br /><br />
					<input type="submit" class="button" value="ответить" /><br /><br />
					</form>';
				}
				if ($page != 1) $pervpage = '<a href="./loosadminwin.php?mod=faq_yes&amp;page=1"><<</a>  
				   <a href="./loosadminwin.php?mod=faq_yes&amp;page='. ($page - 1) .'"><</a> ';  
				if ($page != $total) $nextpage = ' <a href="./loosadminwin.php?mod=faq_yes&amp;page='. ($page + 1) .'">></a>  
				   <a href="./loosadminwin.php?mod=faq_yes&amp;page=' .$total. '">>></a>';  
				if($page - 2 > 0) $page2left = ' <a href="./loosadminwin.php?mod=faq_yes&amp;page='. ($page - 2) .'&amp;">'. ($page - 2) .'</a> | ';  
				if($page - 1 > 0) $page1left = '<a href="./loosadminwin.php?mod=faq_yes&amp;page='. ($page - 1) .'">'. ($page - 1) .'</a> | ';  
				if($page + 2 <= $total) $page2right = ' | <a href="./loosadminwin.php?mod=faq_yes&amp;page='. ($page + 2) .'">'. ($page + 2) .'</a>';  
				if($page + 1 <= $total) $page1right = ' | <a href="./loosadminwin.php?mod=faq_yes&amp;page='. ($page + 1) .'">'. ($page + 1) .'</a>'; 
				$echo.='<br /><center>'.$pervpage.$page2left.$page1left.'<b>'.$page.'</b>'.$page1right.$page2right.$nextpage.'</center>'; 
				
			break;
			
			default:
			$echo.='<br /><br /><form action="./loosadminwin.php?mod=search" method="post"><br />
			<div style="width:90px; float: left;">E-mail: </div><input type="text" name="search" /><br /><br />
			<input type="submit" class="button" value="искать" /><br /><br />
			</form>
			';
			break;
		}
	}
} else {
	$echo='<form action="./loosadminwin.php" method="post"><br />
	<div style="width:90px; float: left;">Login: </div><input type="text" name="login" /><br />
	<div style="width:90px; float: left;">Pass: </div><input type="text" name="pass" /><br />
	<input type="submit" class="button" value="login" /><br /><br />
	</form>';
}

mysql_close();

include 'pageloos.php';
?>