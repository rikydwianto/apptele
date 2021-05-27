<?php
	
//error_reporting(0);

// ==== BEGIN / variabel must be adjusted ====

$token = "bot"."1848914315:AAHECZ7cmUAFgsNGvU72spTw9-jv5ubUHWE";
$proxy = "";
$mysql_host = "localhost";
$mysql_user = "root";
$mysql_pass = "";
$mysql_dbname = "komida";
session_start();
// ==== END / variabel must be adjusted ====


$conn = mysqli_connect($mysql_host, $mysql_user, $mysql_pass);
if(! $conn ) {
  die('Could not connect: ' . mysql_error());
}

$db_selected = mysqli_select_db($conn,$mysql_dbname);
if (!$db_selected) {
  die ('Can\'t use foo : ' . mysql_error() .'<br>');
}


$updates = file_get_contents("php://input");

$updates = json_decode($updates,true);
$lokasi = $updates['message']['location'];
$pesan = $updates['message']['text'];
$chat_id = $updates['message']['chat']['id'];
$nama-"";
$nik="";
$id=$chat_id;

if (strpos($pesan, "/start") === 0) {
	$location = substr($pesan, 5);
	$pesan_balik[]= "Hallo Selamat datang di Bot Komida Pagaden, Bot ini khusus untuk update lokasi center agar lebih mudah";
	
}
else if (strpos($pesan, "/login") === 0) {
	$data=explode(" ",$pesan)[1];
	$user = explode("_",$data)[0];
	$password = (explode("_",$data)[1]);
	$pesan_balik[]="Sedang dicek.... harap tunggu";
	$cek  =mysqli_query($conn,"select * from karyawan where nik_karyawan='$user' and password=md5('$password')");
	if(!mysqli_num_rows($cek))
	{
		$pesan_balik[]="Data Tidak Ditemukan, Silahkan cek dan Ulangi lagi ...";
	}
	else{
		$data_login = mysqli_fetch_array($cek);
		$nama = $data_login['nama_karyawan'];
		$nik = $data_login['nik_karyawan'];
		$id = $data_login['id_karyawan'];
		$pesan_balik[]= " Login Berhasil ID anda :  $id, Nama : $nama, NIK : $nik"; 
		mysqli_query($conn,"UPDATE `karyawan` SET `id_telegram` = '$chat_id' WHERE `karyawan`.`id_karyawan` = $id;");
	}
}


$ceklogin = mysqli_query($conn,"select * from karyawan where id_telegram='$chat_id'");
	if(mysqli_num_rows($ceklogin)){
		$data= mysqli_fetch_array($ceklogin);
		$nama = $data['nama_karyawan'];
		$nik = $data['nik_karyawan'];
		$id = $data['id_karyawan'];
				
		if($pesan!=null){
			if($pesan=='help' || $pesan =="bantu"){
				$pesan_balik[]="Sharelock Lokasi center, lalu masukan nomor center /center no_center";
			}
			else if(strpos($pesan, "/center") === 0){
				$center=explode(" ",$pesan)[1];
				if(!empty($center))
				{
					$center = sprintf("%03d",$center);
					$pesan_balik[]="prosesss ... ";
					$cek_lok = mysqli_query($conn,"select * from data_telegram where id_telegram='$chat_id' order by id desc limit 0,1");
					$cek_lok = mysqli_fetch_array($cek_lok);
					if($cek_lok['id']!=null){
						mysqli_query($conn,"UPDATE `center` SET `latitude` = '$cek_lok[latitude]',`longitude` = '$cek_lok[longitude]' WHERE `no_center` = $center and id_karyawan='$id';");
						mysqli_query($conn," delete from data_telegram where id='$cek_lok[id]';");
						$pesan_balik[]="CENTER $center berhasil memperbarui lokasi";					
					}
					else $pesan_balik[]='Data tidak ditemukan silahkan Sharelock Terlebih dahulu';
				}
			}
			else
			$pesan_balik[]="Perintah tidak ditemukan ketik help / bantu untuk bantuan";
		}
		else if($lokasi!=null){
			mysqli_query($conn,"INSERT INTO `data_telegram` (`id`, `longitude`, `latitude`, `id_telegram`) VALUES (NULL, '$lokasi[longitude]', '$lokasi[latitude]', '$chat_id');
");
			$pesan_balik[]="Silahkan ketikan nomor center nya format : /center no_center";
		}
	}
	else{
		$pesan_balik[]="ID anda belum terdaftar di database, Silahkan Login terlebih dahulu";
		$pesan_balik[]="Untuk login ketik /login NIK_PASSWORD contoh 1234/2018_sandi123";
		
		
	}

	for ($i=0; $i <= count($pesan_balik); $i++) { 
	$url = "https://api.telegram.org/$token/sendMessage?parse_mode=html&chat_id=$chat_id&text=$pesan_balik[$i]";
	file_get_contents($url);
	
}

?>