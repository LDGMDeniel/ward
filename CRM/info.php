<?php
session_start();
if(isset($_SESSION['uid'])){
 require_once('dir.inc');
 require_once($inc_dbc.'/dbc.inc');
 require_once($inc_path.'/std.inc');
 html_head_print('CRM',$inc_css,$_SESSION['css']);
 include_once('menu.php');

 mysql_close($dbc_sqlink);
 echo('</body></html>');
}else{
 header('Location: login.php');
}
?>