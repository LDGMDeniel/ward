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
 //reworking
 if(isset($_POST['todo'])){
  switch($_POST['todo']){
   case 'message':
    break;
   case 'ticket':
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