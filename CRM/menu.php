<?php
$page=$_SERVER['REQUEST_URI'];
$links=array('<a href="../index.php">TRS</a>',//0
'<a href="index.php">CRM</a>',//1
'<a href="list.php">�������</a>',//2
'<a href="list.php?t=man">��������</a>',//3
'<a href="taskman.php">������</a>',//4
'<a href="info.php"><span id="t_gray">��������</span></a>',//5
'<a href="../login.php?a=q">�����</a>');//6
switch($page){
 case '/index.php':$links[1]='CRM'; break;
 case '/CRM/list.php':$links[2]='�������'; break;
 case '/CRM/list.php?t=man':$links[3]='�������'; break;
 case '/CRM/taskman.php':
  case '/CRM/task-new.php':
   case '/CRM/task-arch.php':
    case '/CRM/task-zoom.php': $links[4]='������'; break;
 case '/CRM/list.php?q=find':$links[5]='<span id="t_gray">�����</span>'; break;
 case '/CRM/create.php':$links[5]='<span id="t_gray">�������</span>'; break;
 case '/CRM/info.php':$links[5]='<span id="t_gray">��������</span>'; break;
}
//������ ��� ������������ � ��� �����
//if(!isset($_SESSION['view'])){$links[5]='&nbsp;';}
if(!isset($_SESSION['name'])){$user='&nbsp;';}else{$user=$_SESSION['name'];}
echo('<table cellspacing="0" rules="none" border="0" align="center"><tr>'."\r\n");
echo('<td>'.$links[0]."</td>\r\n<td>".$links[1]."</td>\r\n<td>".$links[2].
"</td>\r\n<td>".$links[3]."</td>\r\n<td>".$links[4]."</td>\r\n<td>".$links[5].
"</td>\r\n<td>".$user."</td>\r\n<td>".$links[6]."</td>\r\n</tr></table>\r\n");
?>