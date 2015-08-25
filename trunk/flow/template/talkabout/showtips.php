<LINK media=screen,projection 
href="screen.css" type=text/css rel=stylesheet><LINK 
href="comment.css" type=text/css rel=stylesheet> 
<?php
require('index.inc.php');
checkget(); 	
    $str="select * from comm ";
	$db->query($str);
	if($db->num_rows())
			while($db->next_record()){
		echo("
<DIV class=nesting>
  <DIV class=endReview>
<DIV class=titleTip>
<DIV class=name> ".$db->value('username')." <SPAN class=cGray 
id=ip_3OBELUN90001121M_3OBFKN3S>ip£º".$db->value('ip')."£º</SPAN> </DIV>
<DIV class=title>".$db->value('time')." ·¢±í</DIV></DIV>
<DIV class=review 
id=3OBELUN90001121M_3OBFKN3S>".$db->value('comm')."</DIV>
	
		
		");
				
				
    }
    
 ?>
 
