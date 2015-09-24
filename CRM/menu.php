<?php
$page=$_SERVER['REQUEST_URI'];
$links=array('<a href="../index.php">TRS</a>',
'<a href="index.php">Список клиентов</a>',
'<a href="staff.php">Сотрудники</a>',
'<a href="new.php">Создать контакт</a>',
'<a href="info.php"><span id="t_gray">Карточка</span></a>',
'<a href="../login.php?a=q">Выход</a>');
switch($page){
 case '/TRS/index.php':$links[1]='Список клиентов'; break;
 case '/staff.php':$links[2]='Сотрудники'; break;
 case '/new.php':$links[3]='Создать контакт'; break;
 case '/info.php':$links[4]='<span id="t_gray">Карточка</span>'; break;
}
if(!isset($_SESSION['view'])){$links[4]='&nbsp;';}
if(!isset($_SESSION['name'])){$user='&nbsp;';}else{$user=$_SESSION['name'];}
echo('<table cellspacing="0" rules="none" border="0" align="center"><tr>'."\r\n");
echo('<td>'.$links[0]."</td>\r\n<td>".$links[1]."</td>\r\n<td>".$links[2].
"</td>\r\n<td>".$links[3]."</td>\r\n<td>".$links[4]."</td>\r\n<td>".$user."</td>\r\n<td>".
$links[5]."</td>\r\n</tr></table>\r\n");
?>