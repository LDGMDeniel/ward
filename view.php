<?php
session_start();
if(isset($_SESSION['uid'])){
 if(isset($_GET['n'])){
  $_SESSION['view']=intval($_GET['n']);
  if($_SESSION['view']==0){
   unset($_SESSION['view']);
   //намекаем что юзеру тут не рады без кошерного номера заявки
   header('Location: index.php');
  }
 }

 if(isset($_SESSION['view'])){
  require_once('dir.inc');
  require_once($inc_dbc.'/dbc.inc');
  require_once($inc_path.'/std.inc');
  html_head_print('TRS',$inc_css,$_SESSION['css']);
  include_once('menu.php');
  //пробуем откруть заявку
  $sqlresult=mysql_query('SELECT * FROM `ticket` WHERE `t_id`="'.$_SESSION['view'].'"',$dbc_sqlink);
  if($sqlresult!=false){
   if(mysql_num_rows($sqlresult)>0){
    get_data_about_clients_and_staff($dbc_sqlink);
    echo('<table cellspacing="0" rules="rows" border="1" id="table1" align="center" width="800">'."\r\n");
    echo('<tr align="center" id="gray0"><td>&nbsp;№&nbsp;</td><td>клиент</td><td>заголовок</td>'.
    '<td>висит</td><td>исполнитель</td></tr>'."\r\n");
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
    '<tr align="center" id="gray0"><td>&nbsp;№&nbsp;</td><td>клиент</td><td>заголовок</td>'.
    '<td>висит</td><td>исполнитель</td></tr>'."\r\n".
    '<tr align="center"><td colspan="5">Не нашлось заявок по такому запросу.</td></tr>'."\r\n".
    '</table>'."\r\n");
    $et='skip';
   }
  }else{
   echo('запрос к заявкам - провален');
    $et='skip';
  }
  //cобщения если заявка открылась
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
       case 'N': $newstatus='свободна'; break;
       default:  $newstatus='непонятно';
      }
      switch ($sqldata['m_type']){
       case 'N': $acted=' создаёт эту заявку для ['.$staff[$sqldata['m_newowner']].'] с сообщением:<br>'."\r\n"; break;
       case 'M': $acted=' пишет:<br>'."\r\n"; break;
       case 'T':
        if($sqldata['m_newowner']==0){         $acted=' освобожает эту заявку с сообщением:<br>'."\r\n"; break;        }else{         $acted=' передаёт эту заявку пользователю ['.$staff[$sqldata['m_newowner']].']:<br>'."\r\n"; break;
        }
       case 'C': $acted=' закрывает эту заявку с сообщением:<br>'."\r\n"; break;
       case 'S': $acted=' меняет статус этой заявки на ['.$newstatus.'] с сообщением:<br>'."\r\n"; break;
       default:  $acted=' делает непонятное:<br>'."\r\n";
      }
      echo('<tr id="'.$rowcolor.$rowstripe.'"><td colspan="5">'."\r\n".
      $staff[$sqldata['m_who']].' '.date('d.m.Y \в H:i',$sqldata['m_when']+18000)."\r\n".
      $acted.$sqldata['m_text']."\r\n".
      "</td></tr>\r\n");
     }
    }else{
     //echo('нет сообщений');
    }
   }else{
    echo('запрос к сообщениям - провален');
   }
   //блок для постинга сообщений
   echo('<tr><td colspan="5">'."\r\n".
   '<form action="post.php" method="post">'."\r\n".
   '<input name="todo" type="hidden" value="message">'."\r\n".
   '<input name="ticket" type="hidden" value="'.$_SESSION['view'].'">'."\r\n".
   'Это будет <select size="1" name="type">'."\r\n".
   '<option value="M" selected>сообщение</option>'."\r\n".
   '<option value="T">передача заявки</option>'."\r\n".
   '<option value="C">закрытие заявки</option>'."\r\n".
   '</select><select size="1" name="newowner">'."\r\n");
   echo('<option value="0">освободить</option>'."\r\n");
   foreach($staff as $key => $value){
    if($key==$_SESSION['uid']){
     echo('<option value="'.$key.'" selected>'.$value.'</option>'."\r\n");
    }else{
     echo('<option value="'.$key.'">'.$value.'</option>'."\r\n");
    }
   }
   echo('</select><br><textarea name="text" rows=4 cols=80 wrap="soft" required></textarea>'."\r\n");
   echo('<input name="tstatus" type="hidden" value="'.$tstatus.'"><input type="submit" value="Отправить">'."\r\n");
   echo("</form></td></tr>\r\n");
  }
  mysql_close($dbc_sqlink);
  echo('</table></body></html>');
 }else{
  //намекаем что юзеру тут вообще не рады без номера заявки
  header('Location: index.php');
 }
}else{
 header('Location: login.php');
}
?>