<?php

	ini_set('display_errors', 0);
	if(isset($_GET['facno'])){
		$facno = $_GET['facno'];
		$eno = $_GET['eno'];
		$prog = $_GET['prog'];
	}else{
		die();
	}
	$url = "http://ctengg.amu.ac.in/web/table_resultnew.php?fac=".$facno."&en=".$eno."&prog=".$prog;
	$result = file_get_contents($url);
	$dom = new DOMDocument();
	@$dom->loadHTML($result);
	//echo $dom->html;
	$data = $dom->getElementsByTagName('td');
	$size = 0;
	foreach ($data as $d) {
		$size++;
	}
	$infoAt = $size-7; # 6+1 student info + index

	$myObj->facno = $data[$infoAt]->textContent;
	$myObj->eno = $data[$infoAt+1]->textContent;
	$myObj->name = $data[$infoAt+2]->textContent;
	$myObj->spi = $data[$infoAt+4]->textContent;
	$myObj->cpi = $data[$infoAt+5]->textContent;
	$subjects = array();
	if($prog == 'btech'){
		for($i=0;$i<$infoAt;$i+=11){
			$subject = array('code' => $data[$i]->textContent, 'title'=> $data[$i+1]->textContent, 'sessional'=> $data[$i+2]->textContent, 'exam'=> $data[$i+3]->textContent,
				'total'=> $data[$i+4]->textContent,'highest'=> $data[$i+5]->textContent,'average'=> $data[$i+6]->textContent,'grace'=> $data[$i+7]->textContent,'grade'=> $data[$i+8]->textContent,
				'rank'=> $data[$i+9]->textContent,'range'=> $data[$i+10]->textContent);
			array_push($subjects,$subject);
		}
	}else{
		for($i=0;$i<$infoAt;$i+=6){
			$subject = array('code' => $data[$i]->textContent, 'title'=> '', 'sessional'=> $data[$i+1]->textContent, 'exam'=> $data[$i+2]->textContent,
				'total'=> $data[$i+3]->textContent,'highest'=> '','average'=> '','grace'=> $data[$i+4]->textContent,'grade'=> $data[$i+5]->textContent,
				'rank'=> '','range'=> '');
			array_push($subjects,$subject);
		}
	}

	$myObj->subs = $subjects;
	$myJSON = json_encode($myObj);
	echo $myJSON;
?>