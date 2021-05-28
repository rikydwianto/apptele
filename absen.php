
<?php
 //ambil data json dari file
  $content=file_get_contents("https://komida.co.id/hris/load_belumabsen2.php");

  //mengubah standar encoding
//echo count($content);
  //mengubah data json menjadi data array asosiatif
  echo($content);
