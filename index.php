<?
include 'inc.php';
include 'lang/lang.php';
$menu_st='home';
include 'menu.php';
$valid=new valid;
mysqldb ();

$count=mysql_fetch_row(mysql_query("select count(*) from user where status='1'"));

if (check($_GET['m'])=='reg') {
	if (check($_POST['fio'])!='' && $_POST['my_mail']!='' && (int)$_POST['pol']!='' && (int)$_POST['vozr']!='' && $_FILES['foto']['name']!='' && $_POST['mail'][0]!='' && $_POST['mail'][1]!='' && $_POST['mail'][2]!='' && $_POST['mail'][3]!='' && $_POST['mail'][4]!='' && $_POST['code']!=''){
		
		if ($_POST['pol']<1 || $_POST['pol']>2) { 
			$_POST['pol']=1;
			$err[]=NO_POL;
		}
		
		if ($_POST['vozr']<14 || $_POST['vozr']>65) { 
			$_POST['vozr']=14;
			$err[]=NO_VOZ;
		}
		
		if (strlen(check($_POST['fio']))<4) { 
			$err[]=FIOMIN; $_POST['fio']='';
		}
		
		if (strlen(check($_POST['fio']))>100) { 
			$err[]=FIOMAX; $_POST['fio']='';
		}
		
		if ($valid->mail($_POST['my_mail'])==false) { 
			$err[]=YOUR.' E-mail '. check($_POST['my_mail']) .' '.NO_VID; $_POST['my_mail']='';
		}else{
			if (mysql_fetch_row(mysql_query("select mail from user where mail='". $_POST['my_mail'] ."'"))>1) {
				$err[]=YOUR.' E-mail '. check($_POST['my_mail']) .' '.E_PR_Q.' E-mail'; $_POST['my_mail']='';
			}
		}
		
		for ($i=0; $i<5; $i++){
			mysql_query("insert into tmp_mail (mail) value ('".check($_POST['mail'][$i])."')");
			$m_del[$i]=check($_POST['mail'][$i]);
		}
		
		for ($i=0; $i<5; $i++){
			if ($valid->mail($_POST['mail'][$i])==false) { 
				$err[]='E-mail '. check($_POST['mail'][$i]) .' '.NO_VID; $_POST['mail'][$i]='';
			}else{
				$mail_count=mysql_fetch_row(mysql_query("select count(*) from tmp_mail where mail='". $_POST['mail'][$i] ."'"));
				if ($mail_count[0]>=2){
					$err[]='E-mail '. check($_POST['mail'][$i]) .' '.E_PR_P; $_POST['mail'][$i]='';
				}else{
					if (mysql_fetch_row(mysql_query("select mail from mail where mail='". $_POST['mail'][$i] ."'"))>1 || mysql_fetch_row(mysql_query("select mail from user where mail='". $_POST['my_mail'] ."'"))>0) {
						$err[]='E-mail '. check($_POST['mail'][$i]) .' '.E_PR_W; $_POST['mail'][$i]='';
					} 
				}
			}
		}
		
		for ($i=0; $i<5; $i++){
			mysql_query("delete from tmp_mail where mail='".$m_del[$i]."'");
		}
		
		if ($_POST['code']!=substr(md5(md5($_SESSION['code'])),0,4)){
			$err[]=SCSCSCS;
		}
		
		if ($_FILES['foto']['size'] / 1024 / 1024 > 5) {
			$err[]=FOTOBIG;
		}
		
		$foto=$_FILES['foto']['name'];
		$ext=strrchr($foto, '.');
		if ($err==''){
			if ($ext == '.jpg' || $ext == '.JPG' || $ext == '.jpeg' || $ext == '.JPEG' || $ext == '.gif' || $ext == '.GIF'){
				$p_foto='./img/'.time().$ext;
				if (!move_uploaded_file(resize($_FILES['foto']['tmp_name'], 330, $_FILES['foto']['tmp_name']),$p_foto)) { 
					$err[]=FOTO_ERROR;
				} else {
					$im1='loosandwin.png';
					$img1=imageCreateFromPNG($im1);
					$img2=imageCreateFromJPEG($p_foto);
					$size_x1=imageSX($img1);
					$size_y1=imageSY($img1);
					$size_x2=imageSX($img2);
					$size_y2=imageSY($img2);
					$sy=$size_y2-$size_y1;
					$sx=$size_x2-$size_x1;
					imageCopy($img2,$img1,$sx,$sy,0,0,$size_x1,$size_y1);
					imageJPEG($img2,$p_foto,100);
					imagedestroy ($img1);
					imagedestroy ($img2);
				}
				
			} else {
				$err[]=NO_FOTO_FORMAT;
			}
		}
		
		if ($err==''){
			mysql_query("insert into user (fio,pol,vozr,mail,foto) value ('".check($_POST['fio'])."','".(int)$_POST['pol']."','".(int)$_POST['vozr']."','".check($_POST['my_mail'])."','$p_foto')");
			for ($i=0; $i<5; $i++){
				mysql_query("insert into mail (mail) value ('".check($_POST['mail'][$i])."')");
						// $mheaders='MIME-Version: 1.0' . "\r\n"; 
						// $mheaders.='Content-type: text/html; charset=utf-8' . "\r\n"; 
						// $mheaders.='To: '.$_POST['mail'][$i].'' . "\r\n"; 
						// $mheaders.='From: loosandwin.ru <reg@loosandwin.ru>' . "\r\n"; 
						// $subject=m_encode(''.PRIGOTVD.' - loosandwin.ru');
						// $message=nl2br(OIUYT);
						// mail($_POST['mail'][$i], $subject, $message, $mheaders);
						unset($_SESSION['code']);
						session_unregister($_SESSION['code']);
						$v=1;
			}
		}
		
	} else {
		$err[]=NOPOLE;
	}
}

if ($err!=''){
	$errno='<h1>'.ERRORQ.'</h1>';
	foreach ($err as $error){
		$errno.=' - <b>'.$error.'</b><br>';
	}
}

switch ((int)$v){
	case 1:
		$echo='<h1>'.AVQ.'</h1><br />
		<code>
		<p>'.AVW.'</p>
		<p>'.AVE.' E-mail '.check($_POST['my_mail']).' '.AVR.'</p>
		<p>'.AVT.'</p>
		<p>'.AVY.'</p>
		</code>';
		
		$mheaders='MIME-Version: 1.0' . "\r\n"; 
		$mheaders.='Content-type: text/html; charset=utf-8' . "\r\n"; 
		$mheaders.='To: '.$_POST['my_mail'].'' . "\r\n"; 
		$mheaders.='From: loosandwin.ru <reg@loosandwin.ru>' . "\r\n"; 
		$subject=m_encode(''.REGSQW.' loosandwin.ru');
		$message=nl2br(SFGBDG);
		mail($_POST['my_mail'], $subject, $message, $mheaders);
		
	break;
	
	default:
		
		
		/* switch ((int)$_POST['pol']) {
		case "1":
			$polc1='selected';
		break;
		
		case "2":
			$polc2='selected';
		break;
		
		default:
			$polc1='selected';
		break;
		}  */
		
		if ((int)isset($_POST['pol'])) { 
			$polc[(int)$_POST['pol']]='selected';
		} else {
			$polc[1]='selected';
		}
		
		if ((int)isset($_POST['vozr'])) { 
			$vozrc[(int)$_POST['vozr']]='selected';
		} else {
			$vozrc[14]='selected';
		}
		
		for ($i=14; $i<65; $i++){
			$v.='<option value="'.$i.'" '.$vozrc[$i].'>'.$i.'</option>';
		}
		
		$_SESSION['code']=uniqid("");
		
		$echo='<form action="./?m=reg" method="post"  enctype="multipart/form-data"><br />
			<b>'.ANQ.': </b><br />
			<div style="width:90px; float: left;">'.ANW.': </div><input type="text" name="fio" value="'.$_POST['fio'].'" /><br />
			<div style="width:90px; float: left;">'.YOUR.' E-mail: </div><input type="text" name="my_mail" value="'.$_POST['my_mail'].'" /><br />
			<div style="width:90px; float: left;">'.ANE.': </div><select name="pol" size="1"><option value="1" '.$polc[1].'>'.ANR.'</option><option value="2" '.$polc[2].'>'.ANT.'</option></select><br />
			<div style="width:90px; float: left;">'.ANY.': </div><select name="vozr" size="1">'.$v.'</select><br />
			<div style="width:90px; float: left;">'.ANU.': </div><input type="file" name="foto" /><br /><br />
			<b>'.ANI.': </b><br />
			<div style="width:90px; float: left;">E-mail: </div><input type="text" name="mail[]" value="'. $_POST['mail'][0] .'" /><br />
			<div style="width:90px; float: left;">E-mail: </div><input type="text" name="mail[]" value="'. $_POST['mail'][1] .'" /><br />
			<div style="width:90px; float: left;">E-mail: </div><input type="text" name="mail[]" value="'. $_POST['mail'][2] .'" /><br />
			<div style="width:90px; float: left;">E-mail: </div><input type="text" name="mail[]" value="'. $_POST['mail'][3] .'" /><br />
			<div style="width:90px; float: left;">E-mail: </div><input type="text" name="mail[]" value="'. $_POST['mail'][4] .'" /><br />
			<div style="width:90px; float: left;">'.OBI.': </div><input type="text" name="code" /><br />
			<div style="width:90px; float: left;">&nbsp;</div><img src="capcha.php"><br /><br />
			<input type="submit" class="button" value="'.ANO.'" /> | <input type="reset" value="'.ANP.'" class="button" /><br /><br />
			</form>';
			$echo.='<code>'.nl2br(RYLLES).'</code>';
	break;
}

mysql_close();

include 'pageloos.php';
?>