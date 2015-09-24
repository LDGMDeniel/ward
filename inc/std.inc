<?php
function html_head_print($title,$inc_css,$css,$inhead=''){
  echo('<html><head><title>'.$title.'</title>'."\r\n".
  '<meta http-equiv="Content-Type" Content="text/html; Charset=Windows-1251">'."\r\n".
  '<link rel="stylesheet" href="'.$inc_css.'/'.$css.'.css">'."\r\n".
  $inhead.
  '</head><body>'."\r\n");
}

//выводит как давно висит заявка
function f_howlong($when){ $start=intval($when);
 $long=time()-$start;
 if($long>=0){  if($long/86400>1){   $days=floor($long/86400);
   if($days>4 and $days<21){
    $result='больше '.$days.' дней';
   }else{
    if ($days%10==1) {     $result='больше '.$days.' дня';
    }else{     $result='больше '.$days.' дней';    }
   }  }elseif($long/3600>1){  $hours=floor($long/3600);
   if($hours>4 and $hours<21){    $result=$hours.' часов';
   }else{    switch ($hours%10) {
     case 1: $result=$hours.' час'; break;
     case 2: case 3: case 4: $result=$hours.' часа'; break;
     default: $result=$hours.' часов';
    }
   }
  }elseif($long<60){   $result='меньше минуты';  }else{   $minutes=floor($long/60);
   if($minutes>4 and $minutes<21){
    $result=$minutes.' минут';
   }else{
    switch ($minutes%10) {
     case 1: $result=$minutes.' минута'; break;
     case 2: case 3: case 4: $result=$minutes.' минуты'; break;
     default: $result=$minutes.' минут';
    }
   }  } }else{  $result='в будущем'; } return($result);}

//массивы клиентов и работников
function get_data_about_clients_and_staff($dbc_sqlink){ global $staff, $clients; $sqlresult=mysql_query('SELECT `s_id`,`s_login` FROM `staff` WHERE `s_going`!="D"',$dbc_sqlink);
 if($sqlresult!=false){
  if(mysql_num_rows($sqlresult)>0){
   while($sqldata=mysql_fetch_array($sqlresult,MYSQL_ASSOC)){
    $staff[$sqldata['s_id']]=$sqldata['s_login'];
   }
  }else{
   echo('пустая выдача по персоналу');
  }
 }else{
  echo('запрос к персоналу - провален');
 }
 $sqlresult=mysql_query('SELECT `c_id`,`c_name` FROM `clients` WHERE `c_going`!="D"',$dbc_sqlink);
 if($sqlresult!=false){
  if(mysql_num_rows($sqlresult)>0){
   while($sqldata=mysql_fetch_array($sqlresult,MYSQL_ASSOC)){
    $clients[$sqldata['c_id']]=$sqldata['c_name'];
   }
  }else{
   echo('пустая выдача по клиентам');
  }
 }else{
  echo('запрос к клиентам - провален');
 }}

//ещё что-то
?>