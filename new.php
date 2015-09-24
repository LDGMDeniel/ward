<?php
 session_start();
if(isset($_SESSION['uid'])){
 require_once('dir.inc');
 require_once($inc_dbc.'/dbc.inc');
 require_once($inc_path.'/std.inc');
 html_head_print('TRS',$inc_css,$_SESSION['css']);
 include_once('menu.php');
 get_data_about_clients_and_staff($dbc_sqlink);
 //блок для определения заявки t_client t_caption
 echo('<form action="post.php" method="post"><input name="todo" type="hidden" value="ticket">'."\r\n".
 '<table cellspacing="0" rules="rows" border="1" id="table1" align="center" width="800">'."\r\n".
 '<tr><td>Для клиента <select size="1" name="client">');
 foreach($clients as $key => $value){
  echo('<option value="'.$key.'">'.$value.'</option>'."\r\n");
 }
 echo('</select><br>Заголовок: <input name="caption" type="text" value="" size="51" maxlength="50" required></td></tr>'."\r\n");
 //блок для постинга сообщений
 echo('<tr><td>Кому поручить: <select size="1" name="newowner"><option value="0" selected>никому</option>'."\r\n");
 foreach($staff as $key => $value){
  echo('<option value="'.$key.'">'.$value.'</option>'."\r\n");
 }
 echo('</select><label>[<input name="U" type="checkbox" value="yes"> срочно]</label>'.
 '<br><textarea name="text" rows=4 cols=80 wrap="soft" required></textarea>'."\r\n");
 echo('<input type="submit" value="Отправить">'."\r\n");
 echo("</form></td></tr>\r\n");
 mysql_close($dbc_sqlink);
 echo('</table></body></html>');
}else{
 header('Location: login.php');
}
?>