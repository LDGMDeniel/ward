<?php
session_start();
require_once('dir.inc');
require_once($inc_dbc.'/dbc.inc');
if(isset($_POST['login']) && isset($_POST['pass'])){
 //вход
 if(!isset($_SESSION['uid'])){
  $sqlresult=mysql_query('SELECT `s_id`,`s_login`,`s_pass` FROM `staff` WHERE `s_going`="Y"',$dbc_sqlink);
  if($sqlresult!=false){
   if(mysql_num_rows($sqlresult)>0){
    while($sqldata=mysql_fetch_array($sqlresult,MYSQL_ASSOC)){
     $staff[$sqldata['s_id']]['login']=$sqldata['s_login'];
     $staff[$sqldata['s_id']]['password']=$sqldata['s_pass'];
    }
   }else{
    echo('пустая выдача по персоналу');
   }
  }else{
   echo('запрос к персоналу - провален');
  }
  $sent_pass=md5($_POST['pass'].'+fish');
  $et='empty'; //результат попытки войти
  foreach($staff as $key => $record){
   if($record['login']==$_POST['login']){
    //login нашелся в базе
    if($record['password']==$sent_pass){
     //пароль совпал - вход успешен
     $et='ok';
     $_SESSION['uid']=$key;
     $_SESSION['name']=$record['login'];
     $_SESSION['css']='main';
    }else{
     //пароль не канает
     $et='bad password';
    }
   }else{
    //логин не канает
    if($et=='empty'){$et='bad login';}
   }
  }
  switch($et){
   case 'bad login':
    echo('<center>Среди активных - нет такого логина. <a href="login.php">Ещё раз?</a></center>');
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
  echo('<center>Сначала нужно <a href="login.php?a=q">выйти</a>.</center>');
 }
}elseif(isset($_GET['a'])){
 //тут будут всякие действия
 switch($_GET['a']){
  case 'q': //выход
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
   '<tr colspan="2" align="center"><td id="red1">Пароль не подходит.</td></tr>'."\r\n".
   '<tr><td align="right">Логин: <input name="login" type="text" value="'.$_GET['n'].
   '" tabindex="3"></td>'."\r\n".'<td rowspan="2"><input type="submit" value="Вход" tabindex="3"><br>'."\r\n".
   '<label>[<input name="mark" type="checkbox" value="yes" tabindex="4"> запомнить]</label></td></tr>'."\r\n".
   '<tr><td align="right">Пароль: <input name="pass" type="password" value="" tabindex="1" autofocus></td></tr></form>'."\r\n".
   '</body></html>');
   break;
 }
}else{ //вход по тайммарку печенек
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
 //приглашение
 echo('<html><head><title>TRS login</title>'."\r\n".
 '<meta http-equiv="Content-Type" Content="text/html; Charset=Windows-1251">'."\r\n".
 '<link rel="stylesheet" href="'.$inc_css.'/main.css">'."\r\n".
 '</head><body>'."\r\n".'<form action="login.php" method="post"><table align="center">'."\r\n".
 '<tr><td align="right">Логин: <input name="login" type="text" value="" tabindex="1" autofocus></td>'."\r\n".
 '<td rowspan="2"><input type="submit" value="Вход" tabindex="3"><br>'."\r\n".
 '<label>[<input name="mark" type="checkbox" value="yes" tabindex="4"> запомнить]</label></td></tr>'."\r\n".
 '<tr><td align="right">Пароль: <input name="pass" type="password" value="" tabindex="2"></td></tr></form>'."\r\n".
 '</body></html>');
}
?>
