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
 if(isset($_POST['type'])){ 	echo('<form action="cpost.php" method="post">'."\r\n");  switch ($_POST['type']){  	case 'man'://������� �������
  	 //����� �������� ���� SQL
  	 $sqlresult=mysql_query('SELECT `c_id`, `c_name` FROM `clients`;',$dbc_sqlink);
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
			 }
  	 echo('<h3>������� ������� '.$_POST['name'].' �� ���������� �������:</h3>'."\r\n".
  	 '<input name="name" type="hidden" value="'.$_POST['name'].'">'."\r\n");
  	 echo('�������� �������� �����: <select size="1" name="ltype">'."\r\n".
				' <option value="P">�������</option>'."\r\n".
				' <option value="E">e-mail</option>'."\r\n".
				'</select> <input name="lvalue" type="text" size="30"><br>');
  	 echo('���������: <input name="Name" type="text" value="" size="26"><br>');
  	 echo('�����������: <select size="1" name="org">'."\r\n");
  	 $i=2;
  	 while(isset($clients[$i])){
 				echo(' <option value="'.$i.'">'.$clients[$i].'</option>'."\r\n");
 				$i+=1;
				}
				echo('</select> ��� <input name="orgn" type="text" size="3"> � �����(�� �� ���)<br>'."\r\n");
    echo('����������: <textarea name="info" rows=5 cols=50 wrap="off"></textarea>');
  	 break;
  	case 'org'://������� �������
  	 echo('<h3>������� ����������� '.$_POST['name'].' �� ���������� �������:</h3>'."\r\n".
  	 '<input name="name" type="hidden" value="'.$_POST['name'].'">'."\r\n");
  	 echo('<label>[<input name="addman" type="checkbox" value="yes">'."\r\n".
  	 '� ������� �������� �������]</label>'."\r\n".
  	 '<label>[<input name="addname" type="checkbox" value="yes">'."\r\n".
  	 '� �� ��������� ���]</label>'."\r\n");
  	 break;  }
  echo('</form>');
 }else{ 	echo('<form action="create.php" method="post">'."\r\n".
	 '<select size="1" name="type">'."\r\n".
	 ' <option value="man">��� ��������:</option>'."\r\n".
	 ' <option value="org">�������� �����������:</option>'."\r\n".
	 '</select>'."\r\n".
	 '<input name="name" type="text" value="" size="50">'."\r\n".
	 '<input type="submit" value="������">'."\r\n".
	 '</form>'); }
 echo('</td></tr></table');

 mysql_close($dbc_sqlink);
 echo('</body></html>');
}else{
 header('Location: login.php');
}
?>