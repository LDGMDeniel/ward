<?php
session_start();
if(isset($_SESSION['uid'])){
 require_once('dir.inc');
 require_once($inc_dbc.'/dbc.inc');
 require_once($inc_path.'/std.inc');
 require_once($inc_path.'/post.inc');
//��������� ��� $_POST �� ������� � �����������
/*������������
 todo - [ticket/message] �������� ��� ���� �������
 -message
   newowner - [may be 0, intval()] ���� ��������, ���� 0 ��� ��������, ��� �� ����������, � ����������� �� ����
   text - [safe string] ����� ���������
   type - [N/M/T/C/S] N - �������� ������, M - ������ ���������, T - ����� ���������, C - �������, S - ����� �������
   ticket - [intval(), not 0] � ����� ������ ���������, ��� ����� ������ ��� ����� mysql_insert_id()
 -ticket
   client - [intval(), not 0] id �������
   caption - [varchar(50)] ��������� ������
   U - [OFF/ON] ������������� ���������, ��������� ������ ���� newowner!=0*/
/*��������������
 -message
  newstatus* - ��� ����� ������� ������(*�� �����������)*/
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
       $post_result='��������� ������ MySQL. ������ �� �������.';
      }else{
       switch ($va[3]){
        case 'T':
         if($va[1]==0){
          $sqlresult=mysql_query('UPDATE `ticket` SET `t_owner` = "0", `t_status`="N"  WHERE `t_id` = "'.$va[4].'"',$dbc_sqlink);
         }else{
          $sqlresult=mysql_query('UPDATE `ticket` SET `t_owner` = "'.$va[1].'", `t_status`="'.$va[5].'" WHERE `t_id` = "'.$va[4].'"',$dbc_sqlink);
         }
         if($sqlresult==false){
          $post_result='��������� ������ MySQL. ��������� �� �������� �� ������.';
         }
         break;
        case 'C':
         $sqlresult=mysql_query('UPDATE `ticket` SET `t_status` = "C" WHERE `t_id` = "'.$va[4].'"',$dbc_sqlink);
         if($sqlresult==false){
          $post_result='��������� ������ MySQL. ��������� �� �������� �� ������.';
         }
         break;
       }
      }
     }else{
      $post_result='������� ��������� ��������� ������� ������ ������� ������. ������ �� �������.';
     }
    }else{
     $post_result='�������� ���������� �� ���������. ������ �� �������.';
    }
    break;
   case 'ticket':
    if(isset($_POST['newowner']) && isset($_POST['text']) && isset($_POST['client']) && isset($_POST['caption'])){
     if(isset($_POST['U'])){
      if($_POST['U']=='yes'){
       $newstatus='U';
      }else{
       $post_result='������������ �������� ��� ��������. ������ �� �������.';
      }
     }else{
      $newstatus='N';
     }
     if($post_result==''){
      //������ �������, � �� ���� � ������, ������ ������� ������� ���������, ����� ������ �����, ����� ������ �������� -1
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
        $post_result='��������� ������ MySQL. ������ �� �������.';
       }else{
        $vm[4]=mysql_insert_id();
        $sqlresult=mysql_query('INSERT INTO `message` (`m_ticket`, `m_type`, `m_when`, `m_who`, `m_text`, `m_newowner`, `m_newstatus`)'.
        ' VALUES ("'.$vm[4].'", "'.$vm[3].'", '.time().', "'.$_SESSION['uid'].'", "'.$vm[2].'", "'.$vm[1].'", "'.$vm[5].'")',$dbc_sqlink);
        if($sqlresult==false){
        $post_result='��������� ������ MySQL. ������ ����� ���� ��������.';
        }
       }
      }else{
       $post_result='���� �� �������� �� �����. ������ �� �������.';
      }
     }
    }
    break;
   default: $post_result='����� �������� ���-�� �� ��. ������ �� �������.';
  }
 }
 if($post_result==''){$post_result='������� ���������. ��� �������� ���.';}
 html_head_print('TRS',$inc_css,$_SESSION['css'],'<meta http-equiv="refresh" content="2; url=index.php">'."\r\n");
 echo('<center>'.$post_result.
 '<br>��������� ���� ������, ��� ��������� <a href="index.php">�� �������.</a></center>'."\r\n");
 mysql_close($dbc_sqlink);
 echo('</body></html>');
}else{
 header('Location: login.php');
}
/*����������
  t_id - �� �����
  t_who - m_type==N{��������� �������}else{�� �����}
  t_when - m_type==N{time()}else{�� �����}
  t_client - m_type==N{ticket.client}else{�� �����}
  t_owner - m_type==N/T{message.newowner}else{�� �����}
  t_caption - m_type==N{ticket.caption}else{�� �����}
  t_status - m_newstatus==-{�� �����}else{m_newstatus}
  (��������� �������: N - ���������, W - � ������, U - ������, C - �������)
  m_id - �� �����
  m_ticket - message.ticket/mysql_insert_id()
  m_type - message.type
  m_when - time()
  m_who - ��������� �������
  m_text - message.text
  m_newowner - message.newowner
  m_newstatus -
   message.type==N/T{newowner==0{N}else{W}}
   message.type==S{message.newstatus}
   message.type==C{C}
   ticket.U==ON{U}
   message.type==M{-}*/
?>