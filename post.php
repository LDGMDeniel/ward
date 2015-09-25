<?php
session_start();
if(isset($_SESSION['uid'])){
 require_once('dir.inc');
 require_once($inc_dbc.'/dbc.inc');
 require_once($inc_path.'/std.inc');
 require_once($inc_path.'/post.inc');
//проверить все $_POST на наличие и пригодность
/*обязательные
 todo - [ticket/message] выбирает что надо постить
 -message
   newowner - [may be 0, intval()] кому передать, если 0 или свободно, или не передавать, в зависимости от типа
   text - [safe string] текст сообщения
   type - [N/M/T/C/S] N - создание заявки, M - просто сообщение, T - смена владельца, C - закрыть, S - смена статуса
   ticket - [intval(), not 0] к какой заявке применять, для новой заявки это будет mysql_insert_id()
 -ticket
   client - [intval(), not 0] id клиента
   caption - [varchar(50)] заголовок заявки
   U - [OFF/ON] устанавливает срочность, допустимо только если newowner!=0*/
/*дополнительные
 -message
  newstatus* - при смене статуса заявки(*не реализовано)*/
 $post_result='';
 //reworking
 if(isset($_POST['todo'])){
  switch($_POST['todo']){
   case 'message':
    break;
   case 'ticket':
    break;
   default: $post_result='Форма прислала что-то не то. Ничего не сделано.';
  }
 }
 if($post_result==''){$post_result='Успешно добавлено. Или выглядит так.';}
 html_head_print('TRS',$inc_css,$_SESSION['css'],'<meta http-equiv="refresh" content="2; url=index.php">'."\r\n");
 echo('<center>'.$post_result.
 '<br>Подождите пару секунд, или перейдите <a href="index.php">на главную.</a></center>'."\r\n");
 mysql_close($dbc_sqlink);
 echo('</body></html>');
}else{
 header('Location: login.php');
}
/*вычислимые
  t_id - не нужен
  t_who - m_type==N{передаётся сессией}else{не нужен}
  t_when - m_type==N{time()}else{не нужен}
  t_client - m_type==N{ticket.client}else{не нужен}
  t_owner - m_type==N/T{message.newowner}else{не нужен}
  t_caption - m_type==N{ticket.caption}else{не нужен}
  t_status - m_newstatus==-{не нужен}else{m_newstatus}
  (возможные статусы: N - бесхозная, W - в работе, U - срочно, C - закрыта)
  m_id - не нужен
  m_ticket - message.ticket/mysql_insert_id()
  m_type - message.type
  m_when - time()
  m_who - передаётся сессией
  m_text - message.text
  m_newowner - message.newowner
  m_newstatus -
   message.type==N/T{newowner==0{N}else{W}}
   message.type==S{message.newstatus}
   message.type==C{C}
   ticket.U==ON{U}
   message.type==M{-}*/
?>