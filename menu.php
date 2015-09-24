<?php
$page=$_SERVER['REQUEST_URI'];
$links=array('<a href="index.php">Открытые</a>',
'<a href="index.php?s=my">Личные</a>',
'<a href="index.php?s=old">Архив</a>',
'<a href="new.php">Создать</a>',
'<a href="view.php"><span id="t_gray">Просмотр</span></a>',
'<a href="login.php?a=q">Выход</a>');
switch($page){
 case '/index.php':$links[0]='Открытые'; break;
 case '/index.php?s=my':$links[1]='Личные'; break;
 case '/index.php?s=old':$links[2]='Архив'; break;
 case '/new.php':$links[3]='Создать'; break;
 case '/view.php':$links[4]='<span id="t_gray">Просмотр</span>'; break;
}
if(!isset($_SESSION['view'])){$links[4]='&nbsp;';}
if(!isset($_SESSION['name'])){$user='&nbsp;';}else{$user=$_SESSION['name'];}
echo('<table cellspacing="0" rules="none" border="0" align="center"><tr>'."\r\n");
echo('<td>'.$links[0]."</td>\r\n<td>".$links[1]."</td>\r\n<td>".$links[2].
"</td>\r\n<td>".$links[3]."</td>\r\n<td>".$links[4]."</td>\r\n<td>".$user."</td>\r\n<td>".
$links[5]."</td>\r\n</tr></table>\r\n");
?>