<?php
session_start();
if(isset($_SESSION['uid'])){
 if(isset($_GET['n'])){
  $_SESSION['view']=intval($_GET['n']);
  if($_SESSION['view']==0){
   unset($_SESSION['view']);
   //�������� ��� ����� ��� �� ���� ��� ��������� ������ ������
   header('Location: index.php');
  }
 }

 if(isset($_SESSION['view'])){
  require_once('dir.inc');
  require_once($inc_dbc.'/dbc.inc');
  require_once($inc_path.'/std.inc');
  html_head_print('TRS',$inc_css,$_SESSION['css']);
  include_once('menu.php');
  //������� ������� ������
  $sqlresult=mysql_query('SELECT * FROM `ticket` WHERE `t_id`="'.$_SESSION['view'].'"',$dbc_sqlink);
  if($sqlresult!=false){
   if(mysql_num_rows($sqlresult)>0){
    get_data_about_clients_and_staff($dbc_sqlink);
    echo('<table cellspacing="0" rules="rows" border="1" id="table1" align="center" width="800">'."\r\n");
    echo('<tr align="center" id="gray0"><td>&nbsp;�&nbsp;</td><td>������</td><td>���������</td>'.
    '<td>�����</td><td>�����������</td></tr>'."\r\n");
    $row_count=1;
    while($sqldata=mysql_fetch_array($sqlresult,MYSQL_ASSOC)){
     if($row_count%2==0){$rowstripe='0';}else{$rowstripe='1';}
     $tstatus=$sqldata['t_status'];
     switch($sqldata['t_status']){
      case 'N': $rowcolor='green'; break;
      case 'U': $rowcolor='red'; break;
      default: $rowcolor='gray';
     }
     if(($sqldata['t_owner']==$_SESSION['uid']) && $rowcolor!='red'){$rowcolor='blue';}
     echo('<tr id="'.$rowcolor.$rowstripe.'"><td align="center">'.$sqldata['t_id'].'</td><td>'.
     $clients[$sqldata['t_client']].'</td><td>'.$sqldata['t_caption'].
     '</td><td>'.f_howlong($sqldata['t_when']).'</td><td>'.$staff[$sqldata['t_owner']]."</td></tr>\r\n");
    }
   }else{
    unset($_SESSION['view']);
    echo('<table cellspacing="0" rules="rows" border="1" id="table1" align="center" width="800">'."\r\n".
    '<tr align="center" id="gray0"><td>&nbsp;�&nbsp;</td><td>������</td><td>���������</td>'.
    '<td>�����</td><td>�����������</td></tr>'."\r\n".
    '<tr align="center"><td colspan="5">�� ������� ������ �� ������ �������.</td></tr>'."\r\n".
    '</table>'."\r\n");
    $et='skip';
   }
  }else{
   echo('������ � ������� - ��������');
    $et='skip';
  }
  //c������� ���� ������ ���������
  if($et!='skip'){
   $sqlresult=mysql_query('SELECT * FROM `message` WHERE `m_ticket`="'.$_SESSION['view'].'"',$dbc_sqlink);
   if($sqlresult!=false){
    if(mysql_num_rows($sqlresult)>0){
     while($sqldata=mysql_fetch_array($sqlresult,MYSQL_ASSOC)){
      $row_count++;
      if($row_count%2==0){$rowstripe='0';}else{$rowstripe='1';}
      $rowcolor='gray';
      if($sqldata['m_who']==$_SESSION['uid']){$rowcolor='blue';}
      switch ($sqldata['m_newstatus']){
       case 'N': $newstatus='��������'; break;
       default:  $newstatus='���������';
      }
      switch ($sqldata['m_type']){
       case 'N': $acted=' ������ ��� ������ ��� ['.$staff[$sqldata['m_newowner']].'] � ����������:<br>'."\r\n"; break;
       case 'M': $acted=' �����:<br>'."\r\n"; break;
       case 'T':
        if($sqldata['m_newowner']==0){         $acted=' ���������� ��� ������ � ����������:<br>'."\r\n"; break;        }else{         $acted=' ������� ��� ������ ������������ ['.$staff[$sqldata['m_newowner']].']:<br>'."\r\n"; break;
        }
       case 'C': $acted=' ��������� ��� ������ � ����������:<br>'."\r\n"; break;
       case 'S': $acted=' ������ ������ ���� ������ �� ['.$newstatus.'] � ����������:<br>'."\r\n"; break;
       default:  $acted=' ������ ����������:<br>'."\r\n";
      }
      echo('<tr id="'.$rowcolor.$rowstripe.'"><td colspan="5">'."\r\n".
      $staff[$sqldata['m_who']].' '.date('d.m.Y \� H:i',$sqldata['m_when']+18000)."\r\n".
      $acted.$sqldata['m_text']."\r\n".
      "</td></tr>\r\n");
     }
    }else{
     //echo('��� ���������');
    }
   }else{
    echo('������ � ���������� - ��������');
   }
   //���� ��� �������� ���������
   echo('<tr><td colspan="5">'."\r\n".
   '<form action="post.php" method="post">'."\r\n".
   '<input name="todo" type="hidden" value="message">'."\r\n".
   '<input name="ticket" type="hidden" value="'.$_SESSION['view'].'">'."\r\n".
   '��� ����� <select size="1" name="type">'."\r\n".
   '<option value="M" selected>���������</option>'."\r\n".
   '<option value="T">�������� ������</option>'."\r\n".
   '<option value="C">�������� ������</option>'."\r\n".
   '</select><select size="1" name="newowner">'."\r\n");
   echo('<option value="0">����������</option>'."\r\n");
   foreach($staff as $key => $value){
    if($key==$_SESSION['uid']){
     echo('<option value="'.$key.'" selected>'.$value.'</option>'."\r\n");
    }else{
     echo('<option value="'.$key.'">'.$value.'</option>'."\r\n");
    }
   }
   echo('</select><br><textarea name="text" rows=4 cols=80 wrap="soft" required></textarea>'."\r\n");
   echo('<input name="tstatus" type="hidden" value="'.$tstatus.'"><input type="submit" value="���������">'."\r\n");
   echo("</form></td></tr>\r\n");
  }
  mysql_close($dbc_sqlink);
  echo('</table></body></html>');
 }else{
  //�������� ��� ����� ��� ������ �� ���� ��� ������ ������
  header('Location: index.php');
 }
}else{
 header('Location: login.php');
}
?>