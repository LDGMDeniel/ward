<?php
function html_head_print($title,$inc_css,$css,$inhead=''){
  echo('<html><head><title>'.$title.'</title>'."\r\n".
  '<meta http-equiv="Content-Type" Content="text/html; Charset=Windows-1251">'."\r\n".
  '<link rel="stylesheet" href="'.$inc_css.'/'.$css.'.css">'."\r\n".
  $inhead.
  '</head><body>'."\r\n");
}

//������� ��� ����� ����� ������
function f_howlong($when){ $start=intval($when);
 $long=time()-$start;
 if($long>=0){  if($long/86400>1){   $days=floor($long/86400);
   if($days>4 and $days<21){
    $result='������ '.$days.' ����';
   }else{
    if ($days%10==1) {     $result='������ '.$days.' ���';
    }else{     $result='������ '.$days.' ����';    }
   }  }elseif($long/3600>1){  $hours=floor($long/3600);
   if($hours>4 and $hours<21){    $result=$hours.' �����';
   }else{    switch ($hours%10) {
     case 1: $result=$hours.' ���'; break;
     case 2: case 3: case 4: $result=$hours.' ����'; break;
     default: $result=$hours.' �����';
    }
   }
  }elseif($long<60){   $result='������ ������';  }else{   $minutes=floor($long/60);
   if($minutes>4 and $minutes<21){
    $result=$minutes.' �����';
   }else{
    switch ($minutes%10) {
     case 1: $result=$minutes.' ������'; break;
     case 2: case 3: case 4: $result=$minutes.' ������'; break;
     default: $result=$minutes.' �����';
    }
   }  } }else{  $result='� �������'; } return($result);}

//������� �������� � ����������
function get_data_about_clients_and_staff($dbc_sqlink){ global $staff, $clients; $sqlresult=mysql_query('SELECT `s_id`,`s_login` FROM `staff` WHERE `s_going`!="D"',$dbc_sqlink);
 if($sqlresult!=false){
  if(mysql_num_rows($sqlresult)>0){
   while($sqldata=mysql_fetch_array($sqlresult,MYSQL_ASSOC)){
    $staff[$sqldata['s_id']]=$sqldata['s_login'];
   }
  }else{
   echo('������ ������ �� ���������');
  }
 }else{
  echo('������ � ��������� - ��������');
 }
 $sqlresult=mysql_query('SELECT `c_id`,`c_name` FROM `clients` WHERE `c_going`!="D"',$dbc_sqlink);
 if($sqlresult!=false){
  if(mysql_num_rows($sqlresult)>0){
   while($sqldata=mysql_fetch_array($sqlresult,MYSQL_ASSOC)){
    $clients[$sqldata['c_id']]=$sqldata['c_name'];
   }
  }else{
   echo('������ ������ �� ��������');
  }
 }else{
  echo('������ � �������� - ��������');
 }}

//��� ���-��
?>