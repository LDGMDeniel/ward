<?php
function validate_message_input_to_array($newowner, $text, $type, $ticket, $tstatus){
 //-m_id  +m_ticket  +m_type  -m_when -m_who  +m_text  +m_newowner  +m_newstatus
 $ticket=intval($ticket);
 if($ticket==0){$done=false;}
 $type_a=array('N', 'M', 'T', 'C', 'S');
 if(!in_array($type,$type_a)){$done=false;}
 //����� �������, ��������� �������� html ����, �� ������� ������� ����� ����������
 //$text ����� � ������. ���� ����-�� �� ���� ����� ��� ������ - ��� � ����.
 $text=str_replace('"','&quot;',$text);
 if(is_numeric($newowner)){
 $tstatus_a=array('N', 'W', 'C', 'U');
 if(in_array($tstatus,$tstatus_a)){$newstatus=$tstatus;}else{$done=false;}
 if($tstatus!='U'){
  switch ($type){
   case 'N':
    $newstatus='N';
    break;
   case 'T':
    if($tstatus!='C'){
    }
    break;
   case 'S':
    if(!in_array($tstatus,$tstatus_a)){$done=false;}
    break;
   case 'C':
    $newstatus='C';
    break;
  }
 }
?>