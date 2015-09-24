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
 if(isset($_POST['todo'])){
  switch($_POST['todo']){
   case 'message':
    if(isset($_POST['newowner']) && isset($_POST['text']) && isset($_POST['type']) && isset($_POST['ticket']) && isset($_POST['tstatus'])){
     $vi=array($_POST['newowner'], $_POST['text'], $_POST['type'], $_POST['ticket'], $_POST['tstatus']);
     $va=validate_message_input_to_array($_POST['newowner'], $_POST['text'], $_POST['type'], $_POST['ticket'], $_POST['tstatus']);
     if($va[0]==true){
      $sqlresult=mysql_query('INSERT INTO `message` (`m_ticket`, `m_type`, `m_when`, `m_who`, `m_text`, `m_newowner`, `m_newstatus`)'.
      ' VALUES ("'.$va[4].'", "'.$va[3].'", '.time().', "'.$_SESSION['uid'].'", "'.$va[2].'", "'.$va[1].'", "'.$va[5].'")',$dbc_sqlink);
      if($sqlresult==false){
       $post_result='Произошла ошибка MySQL. Ничего не сделано.';
      }else{
       switch ($va[3]){
        case 'T':
         if($va[1]==0){
          $sqlresult=mysql_query('UPDATE `ticket` SET `t_owner` = "0", `t_status`="N"  WHERE `t_id` = "'.$va[4].'"',$dbc_sqlink);
         }else{
          $sqlresult=mysql_query('UPDATE `ticket` SET `t_owner` = "'.$va[1].'", `t_status`="'.$va[5].'" WHERE `t_id` = "'.$va[4].'"',$dbc_sqlink);
         }
         if($sqlresult==false){
          $post_result='Произошла ошибка MySQL. Сообщение не повлияло на заявку.';
         }
         break;
        case 'C':
         $sqlresult=mysql_query('UPDATE `ticket` SET `t_status` = "C" WHERE `t_id` = "'.$va[4].'"',$dbc_sqlink);
         if($sqlresult==false){
          $post_result='Произошла ошибка MySQL. Сообщение не повлияло на заявку.';
         }
         break;
       }
      }
     }else{
      $post_result='Функция обработки сообщения вернула ошибку входных данных. Ничего не сделано.';
     }
    }else{
     $post_result='Ожидания переменных не оправданы. Ничего не сделано.';
    }
    break;
   case 'ticket':
    if(isset($_POST['newowner']) && isset($_POST['text']) && isset($_POST['client']) && isset($_POST['caption'])){
     if(isset($_POST['U'])){
      if($_POST['U']=='yes'){
       $newstatus='U';
      }else{
       $post_result='Обязательное значение вне ожиданий. Ничего не сделано.';
      }
     }else{
      $newstatus='N';
     }
     if($post_result==''){
      //ебаный костыль, я не мочь в логику, потому сначала готовим сообщение, потом постим тикет, потом меняем значение -1
      $vm=validate_message_input_to_array($_POST['newowner'], $_POST['text'], 'N', -1, $newstatus);
      $vt[0]=true;
      $vt[1]=intval($_POST['client']);
      if($vt[1]==0){$vt[0]=false;}
      if(strlen($_POST['caption'])>50){$vt[0]=false;}
      $vt[2]=str_replace('"','&quot;',$_POST['caption']);
      if($vt[0] && $vm[0]){
       $sqlresult=mysql_query('INSERT INTO `ticket` (`t_who`, `t_when`, `t_client`, `t_owner`, `t_caption`, `t_status`)
       VALUES ("'.$_SESSION['uid'].'", '.time().', "'.$vt[1].'", "'.$vm[1].'", "'.$vt[2].'", "'.$vm[5].'")');
       if($sqlresult==false){
        $post_result='Произошла ошибка MySQL. Ничего не сделано.';
       }else{
        $vm[4]=mysql_insert_id();
        $sqlresult=mysql_query('INSERT INTO `message` (`m_ticket`, `m_type`, `m_when`, `m_who`, `m_text`, `m_newowner`, `m_newstatus`)'.
        ' VALUES ("'.$vm[4].'", "'.$vm[3].'", '.time().', "'.$_SESSION['uid'].'", "'.$vm[2].'", "'.$vm[1].'", "'.$vm[5].'")',$dbc_sqlink);
        if($sqlresult==false){
        $post_result='Произошла ошибка MySQL. Заявка может быть запорота.';
        }
       }
      }else{
       $post_result='Один из массивов не готов. Ничего не сделано.';
      }
     }
    }
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