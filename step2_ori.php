<?php

//print_r($_POST);
$state = '';
if($_POST['id_type']=='AU1'||$_POST['id_type']=='AU2'){
	$state = 'CA';
}else{
	$state = $_POST['id_state'];
}

$proxy = new SoapClient('https://exchangeweb.lacuracao.com:2007/ws1/lacauth/service.asmx?WSDL');

$result = $proxy->Auth_Init(array('Params' => 
								array('Firstname' =>$_POST['fname'],
									'Middlename'=>'',
									'Lastname'=>$_POST['lname'],
									'HomePhone'=>$_POST['area'].$_POST['local1'].$_POST['local2'],
									'Ssn'=>$_POST['ssn1'].$_POST['ssn2'].$_POST['ssn3'],
									'ID_no'=>$_POST['idnumber'],
									'ID_State'=>$state,
									'DOB_Month'=>$_POST['dobM'],
									'DOB_Day'=>$_POST['dobD'],
									'DOB_Year'=>$_POST['dobY'],
									'Street'=>$_POST['add1'].$_POST['add2'],
									'City'=>$_POST['city'],
									'State'=>$_POST['state'],
									'Zip'=>$_POST['zip'],
									'AcctTransId'=>'156',
									'Language'=>'English',
                                    'TimeOut' => 0
									)
                          ),
                    "http://www.LaCuracao.net/LACmis/webservice",
                    "http://www.LaCuracao.net/LACmis/webservice/Auth_Init", 
                    false, null, 'rpc', 'literal');

        


if($re->Result=='QUESTIONS'){
	?>
    <form action="lexusstep1.php" method="post">
    
    	<input type="hidden" value="<?php echo $re->QuestionSetId?>" name="qsetid" />
        <input type="hidden" value="<?php echo $re->QuestionId?>" name="qid" />
        <input type="hidden" value="<?php echo $re->TransactionNumber?>" name="tnum" />
        <input type="hidden" value="<?php echo $re->TransactionId?>" name="tid" />
        
        <div>
        Help Text : <?php echo $re->HelpText?>
        </div>
        
        <p>
        	Question: <?php echo $re->Question?>
        </p>
    	<div>
        	<?php 
			$choice = $re->Choice->aChoice;
				for($i=0;$i<sizeof($choice);$i++){
					?>
					<p><input type="radio" value="<?php $choice[$i]->ChoiceId?>" name="choiceid" /><?php echo $choice[$i]->Choice?></p>
					<?php
				}
			?>
        
        </div>
        
        <input type="image" src="next.jpg" />
        
    </form>
    <?
}

/*echo '<pre>';
	print_r($re);
echo '</pre>';*/