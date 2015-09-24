<?php
session_start();
if(isset($_SESSION['uid'])){
 require_once('dir.inc');
 require_once($inc_dbc.'/dbc.inc');
 require_once($inc_path.'/std.inc');
 html_head_print('TRS',$inc_css,$_SESSION['css']);
 include_once('menu.php');
 get_data_about_clients_and_staff($dbc_sqlink);
 if(isset($_GET['s'])){
  switch($_GET['s']){
   case 'my':
    $q_string='SELECT * FROM `ticket` WHERE `t_owner`="'.$_SESSION['uid'].'" AND `t_status`!="C"';
    break;
   case 'old':
    $q_string='SELECT * FROM `ticket` WHERE `t_status`="C"';
    break;
   default:
    $q_string='SELECT * FROM `ticket` WHERE `t_status`!="C"';
  }
 }else{
  $q_string='SELECT * FROM `ticket` WHERE `t_status`!="C"';
 }
 $sqlresult=mysql_query($q_string,$dbc_sqlink);
 if($sqlresult!=false){
  if(mysql_num_rows($sqlresult)>0){
   $row_count=0;
   echo('<table cellspacing="0" rules="rows" border="1" id="table1" align="center" width="800">'."\r\n");
   echo('<tr align="center" id="gray0"><td>&nbsp;№&nbsp;</td><td>клиент</td><td>заголовок</td>'.
   '<td>висит</td><td>исполнитель</td></tr>'."\r\n");
    /*//demo colors
    echo('<tr><td colspan="5" id="gray0">Lorem ipsum dolor sit amet, consectetur adipiscing elit</td></tr>');
    echo('<tr><td colspan="5" id="gray1"> sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</td></tr>');
    echo('<tr><td colspan="5" id="green0">Ut enim ad minim veniam, quis nostrud exercitation ullamco</td></tr>');
    echo('<tr><td colspan="5" id="green1">laboris nisi ut aliquip ex ea commodo consequat.</td></tr>');
    echo('<tr><td colspan="5" id="red0">Duis aute irure dolor in reprehenderit in voluptate velit </td></tr>');
    echo('<tr><td colspan="5" id="red1">esse cillum dolore eu fugiat nulla pariatur. Excepteur sint</td></tr>');
    echo('<tr><td colspan="5" id="blue0">occaecat cupidatat non proident, sunt in culpa qui officia</td></tr>');
    echo('<tr><td colspan="5" id="blue1">deserunt mollit anim id est laborum.</td></tr>');*/
   while($sqldata=mysql_fetch_array($sqlresult,MYSQL_ASSOC)){
    $row_count++;
    if($row_count%2==0){$rowstripe='0';}else{$rowstripe='1';}
    switch($sqldata['t_status']){
     case 'N': $rowcolor='green'; break;
     case 'U': $rowcolor='red'; break;
     default: $rowcolor='gray';
    }
    if(($sqldata['t_owner']==$_SESSION['uid']) && $rowcolor!='red'){$rowcolor='blue';}
    echo('<tr id="'.$rowcolor.$rowstripe.'"><td align="center">'.$sqldata['t_id'].'</td><td>'.
    $clients[$sqldata['t_client']].'</td><td><a href="view.php?n='.$sqldata['t_id'].'">'.$sqldata['t_caption'].
    '</a></td><td>'.f_howlong($sqldata['t_when']).'</td><td>'.$staff[$sqldata['t_owner']]."</td></tr>\r\n");
   }
   echo('</table>');
  }else{
   echo('<table cellspacing="0" rules="rows" border="1" id="table1" align="center" width="800">'."\r\n".
   '<tr align="center" id="gray0"><td>&nbsp;№&nbsp;</td><td>клиент</td><td>заголовок</td>'.
   '<td>висит</td><td>исполнитель</td></tr>'."\r\n".
   '<tr align="center"><td colspan="5">Не нашлось заявок по такому запросу.</td></tr>'."\r\n".
   '</table>'."\r\n");
  }
 }else{
  echo('запрос к заявкам - провален');
 }
 mysql_close($dbc_sqlink);
 echo('</body></html>');
}else{
 header('Location: login.php');
}
?>