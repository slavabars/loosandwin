<?
switch (check($menu_st)){
	case 'home':
	$m_menu='<li id="current"><a href="./"><span>'.AWW.'</span></a></li>
	<li><a href="./faq.php"><span>'.AWE.'</span></a></li>
	<li><a href="./site.php"><span>'.AWT.'</span></a></li>';
	break;
	
	case 'faq':
	$m_menu='<li><a href="./"><span>'.AWW.'</span></a></li>
	<li id="current"><a href="./faq.php"><span>'.AWE.'</span></a></li>
	<li><a href="./site.php"><span>'.AWT.'</span></a></li>';
	break;
	
	case 'site':
	$m_menu='<li><a href="./"><span>'.AWW.'</span></a></li>
	<li><a href="./faq.php"><span>'.AWE.'</span></a></li>
	<li id="current"><a href="./site.php"><span>'.AWT.'</span></a></li>';
	break;
	
	default:
	$m_menu='<li id="current"><a href="./"><span>'.AWW.'</span></a></li>
	<li><a href="./faq.php"><span>'.AWE.'</span></a></li>
	<li><a href="./site.php"><span>'.AWT.'</span></a></li>';
	break;
}
?>