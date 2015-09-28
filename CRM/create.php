<?php
session_start();
if(isset($_SESSION['uid'])){
 require_once('dir.inc');
 require_once($inc_dbc.'/dbc.inc');
 require_once($inc_path.'/std.inc');
 html_head_print('CRM',$inc_css,$_SESSION['css']);
 include_once('menu.php');

 echo('<table cellspacing="0" rules="rows" border="1" id="table1" align="center" width="800">'."\r\n".
 '<tr><td>');
 if(isset($_POST['type'])){ 	echo('<form action="cpost.php" method="post">'."\r\n");  switch ($_POST['type']){  	case 'man'://создать контакт
  	 echo('<input name="type" type="hidden" value="man">');
  	 //срань господня этож SQL, хорошоб убрать этот запрос, да маловато шансов
  	 $sqlresult=mysql_query('SELECT `c_id`, `c_name` FROM `clients`;',$dbc_sqlink);
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
			 }
  	 echo('<h3>Контактное лицо '.$_POST['name'].' со следующими данными:</h3>'."\r\n".
  	 '<input name="name" type="hidden" value="'.$_POST['name'].'">'."\r\n");
  	 echo('Основное средство связи: <select size="1" name="ltype">'."\r\n".
				' <option value="P">телефон</option>'."\r\n".
				' <option value="E">e-mail</option>'."\r\n".
				'</select> <input name="lvalue" type="text" size="30"><br>');
  	 echo('Должность/описание: <input name="Name" type="text" value="" size="26"><br>');
  	 echo('Организация: <select size="1" name="org">'."\r\n");
  	 $i=2;
 			echo(' <option value="0">- выбрать -</option>'."\r\n");
  	 while(isset($clients[$i])){
 				echo(' <option value="'.$i.'">'.$clients[$i].'</option>'."\r\n");
 				$i+=1;
				}
				echo('</select> или <input name="orgn" type="text" size="3"> её номер(но не оба)<br>'."\r\n");
    echo('Примечания:<br><textarea name="info" rows=5 cols=50 wrap="off"></textarea>');
  	 break;

  	case 'org'://создать клиента
  	 echo('<input name="type" type="hidden" value="org">');
  	 echo('<h3>Организация '.$_POST['name'].' со следующими данными:</h3>'."\r\n".
  	 '<input name="name" type="hidden" value="'.$_POST['name'].'">'."\r\n");
  	 echo('Примечания:<br><textarea name="info" rows=5 cols=50 wrap="off"></textarea><br>');
  	 echo('<label>[<input name="addman" type="checkbox" value="yes">'."\r\n".
  	 'и создать основной контакт]</label>'."\r\n");
  	 break;  }
  echo('<br><br><input type="submit" value="Создать"></form>');
 }else{ 	echo('<form action="create.php" method="post">'."\r\n".
	 '<select size="1" name="type">'."\r\n".
	 ' <option value="man">Имя контакта:</option>'."\r\n".
	 ' <option value="org">Название организации:</option>'."\r\n".
	 '</select>'."\r\n".
	 '<input name="name" type="text" value="" size="50">'."\r\n".
	 '<input type="submit" value="Дальше">'."\r\n".
	 '</form>'); }
 echo('</td></tr></table');

 mysql_close($dbc_sqlink);
 echo('</body></html>');
}else{
 header('Location: login.php');
}
?>