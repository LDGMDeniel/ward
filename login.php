<?php
session_start();
require_once('dir.inc');
require_once($inc_dbc.'/dbc.inc');
if(isset($_POST['login']) && isset($_POST['pass'])){
 //����
 if(!isset($_SESSION['uid'])){
  $sqlresult=mysql_query('SELECT `s_id`,`s_login`,`s_pass` FROM `staff` WHERE `s_going`="Y"',$dbc_sqlink);
  if($sqlresult!=false){
   if(mysql_num_rows($sqlresult)>0){
    while($sqldata=mysql_fetch_array($sqlresult,MYSQL_ASSOC)){
     $staff[$sqldata['s_id']]['login']=$sqldata['s_login'];
     $staff[$sqldata['s_id']]['password']=$sqldata['s_pass'];
    }
   }else{
    echo('������ ������ �� ���������');
   }
  }else{
   echo('������ � ��������� - ��������');
  }
  $sent_pass=md5($_POST['pass'].'+fish');
  $et='empty'; //��������� ������� �����
  foreach($staff as $key => $record){
   if($record['login']==$_POST['login']){
    //login ������� � ����
    if($record['password']==$sent_pass){
     //������ ������ - ���� �������
     $et='ok';
     $_SESSION['uid']=$key;
     $_SESSION['name']=$record['login'];
     $_SESSION['css']='main';
    }else{
     //������ �� ������
     $et='bad password';
    }
   }else{
    //����� �� ������
    if($et=='empty'){$et='bad login';}
   }
  }
  switch($et){
   case 'bad login':
    echo('<center>����� �������� - ��� ������ ������. <a href="login.php">��� ���?</a></center>');
    break;
   case 'bad password':
    header('Location: login.php?a=p&n='.$_POST['login']);
    break;
   case 'ok':
    if($_POST['mark']=="yes"){
     $time_mark=md5(time().'mark');
     mysql_query('UPDATE `staff` SET `s_key` = "'.$time_mark.'" WHERE `s_id` = '.$_SESSION['uid'].' LIMIT 1;');
     setcookie("time_mark", $time_mark, time()+360000);
    }
    header('Location: index.php');
    break;
  }
 }else{
  echo('<center>������� ����� <a href="login.php?a=q">�����</a>.</center>');
 }
}elseif(isset($_GET['a'])){
 //��� ����� ������ ��������
 switch($_GET['a']){
  case 'q': //�����
   session_unset();
   session_destroy();
   setcookie("time_mark", "", time()-1);
   header('Location: login.php');
   break;
  case 'p':
   echo('<html><head><title>TRS login</title>'."\r\n".
   '<meta http-equiv="Content-Type" Content="text/html; Charset=Windows-1251">'."\r\n".
   '<link rel="stylesheet" href="'.$inc_css.'/main.css">'."\r\n".
   '</head><body>'."\r\n".'<form action="login.php" method="post"><table align="center">'."\r\n".
   '<tr colspan="2" align="center"><td id="red1">������ �� ��������.</td></tr>'."\r\n".
   '<tr><td align="right">�����: <input name="login" type="text" value="'.$_GET['n'].
   '" tabindex="3"></td>'."\r\n".'<td rowspan="2"><input type="submit" value="����" tabindex="3"><br>'."\r\n".
   '<label>[<input name="mark" type="checkbox" value="yes" tabindex="4"> ���������]</label></td></tr>'."\r\n".
   '<tr><td align="right">������: <input name="pass" type="password" value="" tabindex="1" autofocus></td></tr></form>'."\r\n".
   '</body></html>');
   break;
 }
}else{ //���� �� ��������� �������
 if(isset($_COOKIE['time_mark'])){  $sqlresult=false;  if(strlen($_COOKIE['time_mark'])==32){   $sqlresult=mysql_query('SELECT `s_id`,`s_login` FROM `staff` WHERE `s_key`="'.$_COOKIE['time_mark'].'"',$dbc_sqlink);
  }
  if($sqlresult!=false){
   if(mysql_num_rows($sqlresult)>0){
    $sqldata=mysql_fetch_array($sqlresult,MYSQL_ASSOC);
    $_SESSION['uid']=$sqldata['s_id'];
    $_SESSION['name']=$sqldata['s_login'];
    $_SESSION['css']='main';
    $time_mark=md5(time().'mark');
    mysql_query('UPDATE `staff` SET `s_key` = "'.$time_mark.'" WHERE `s_id` = '.$_SESSION['uid'].' LIMIT 1;');
    setcookie("time_mark", $time_mark, time()+360000);
    header('Location: index.php');
   }
  } }
 //�����������
 echo('<html><head><title>TRS login</title>'."\r\n".
 '<meta http-equiv="Content-Type" Content="text/html; Charset=Windows-1251">'."\r\n".
 '<link rel="stylesheet" href="'.$inc_css.'/main.css">'."\r\n".
 '</head><body>'."\r\n".'<form action="login.php" method="post"><table align="center">'."\r\n".
 '<tr><td align="right">�����: <input name="login" type="text" value="" tabindex="1" autofocus></td>'."\r\n".
 '<td rowspan="2"><input type="submit" value="����" tabindex="3"><br>'."\r\n".
 '<label>[<input name="mark" type="checkbox" value="yes" tabindex="4"> ���������]</label></td></tr>'."\r\n".
 '<tr><td align="right">������: <input name="pass" type="password" value="" tabindex="2"></td></tr></form>'."\r\n".
 '</body></html>');
}
?>
