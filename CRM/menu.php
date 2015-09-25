<?php
$page=$_SERVER['REQUEST_URI'];
$links=array('<a href="../index.php">TRS</a>',
'<a href="index.php">CRM</a>',
'<a href="list.php">Клиенты</a>',
'<a href="list.php?t=man">Контакты</a>',
'<a href="taskman.php">Задачи</a>',
'<a href="info.php"><span id="t_gray">Карточка</span></a>',
'<a href="../login.php?a=q">Выход</a>');
switch($page){
 case '/index.php':$links[1]='CRM'; break;
 case '/CRM/list.php':$links[2]='Клиенты'; break;
 case '/CRM/list.php?t=man':$links[3]='Контаты'; break;
 case '/CRM/taskman.php':
  case '/CRM/task-new.php':
   case '/CRM/task-arch.php':
    case '/CRM/task-zoom.php': $links[4]='Задачи'; break;
 case '/CRM/list.php?q=find':$links[5]='<span id="t_gray">Поиск</span>'; break;
 case '/CRM/create.php':$links[5]='<span id="t_gray">Создать</span>'; break;
 case '/info.php':$links[5]='<span id="t_gray">Карточка</span>'; break;
}
//каждый раз переписывать и эту хрень
if(!isset($_SESSION['view'])){$links[6]='&nbsp;';}
if(!isset($_SESSION['name'])){$user='&nbsp;';}else{$user=$_SESSION['name'];}
echo('<table cellspacing="0" rules="none" border="0" align="center"><tr>'."\r\n");
echo('<td>'.$links[0]."</td>\r\n<td>".$links[1]."</td>\r\n<td>".$links[2].
"</td>\r\n<td>".$links[3]."</td>\r\n<td>".$links[4]."</td>\r\n<td>".$links[5].
"</td>\r\n<td>".$user."</td>\r\n<td>".$links[6]."</td>\r\n</tr></table>\r\n");
?>