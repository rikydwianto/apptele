<?php
 //ambil data json dari file
 $url = 'http://'.$_SERVER['HTTP_HOST'];
  $content=file_get_contents($url."/apptele/absen.php");

  //mengubah standar encoding
//echo count($content);
  //mengubah data json menjadi data array asosiatif
  $result=json_decode($content,true);
  $no = 1;
	foreach(($result['data']) as $key)
	{
		if($key['cabang']=="PAGADEN")
		{
			$hitung[]=$key['no'];
			echo $no++.". ".$key['nik']." <br/> " ;
			echo $key['no'].'<br>';
		}
	}
	//print_r($hitung);
	foreach($hitung as $val){
		
		echo $result['data'][$val-1]['nama']."<br/>";
	}