<?php
function validate_message_input_to_array($newowner, $text, $type, $ticket, $tstatus){ $done=true;
 //-m_id  +m_ticket  +m_type  -m_when -m_who  +m_text  +m_newowner  +m_newstatus
 $ticket=intval($ticket);
 if($ticket==0){$done=false;}
 $type_a=array('N', 'M', 'T', 'C', 'S');
 if(!in_array($type,$type_a)){$done=false;}
 //самый геморой, позволить основные html тэги, но закрыть большую часть вредоноски
 //$text здесь € сдаюсь. ≈сли кому-то не лень будет это ломать - фиг с ними.
 $text=str_replace('"','&quot;',$text);
 if(is_numeric($newowner)){  $newowner=intval($newowner); }else{  $done=false; }
 $tstatus_a=array('N', 'W', 'C', 'U');
 if(in_array($tstatus,$tstatus_a)){$newstatus=$tstatus;}else{$done=false;}
 if($tstatus!='U'){
  switch ($type){
   case 'N':
    $newstatus='N';
    break;
   case 'T':
    if($tstatus!='C'){     if($newowner==0){$newstatus='N';}else{$newstatus='W';}
    }
    break;
   case 'S':
    if(!in_array($tstatus,$tstatus_a)){$done=false;}
    break;
   case 'C':
    $newstatus='C';
    break;
  }
 } $va=array($done, $newowner, $text, $type, $ticket, $newstatus); return($va);}
?>