<?php

$host = "localhost";
$username = "root";
$password = "";
$dbase = "kopikenangansenja";

$con = mysqli_connect ( $host,$username,$password) OR die ( "koneksi gagal");
mysqli_select_db ($con,$dbase) or die ("gagal pilih db");

?>
