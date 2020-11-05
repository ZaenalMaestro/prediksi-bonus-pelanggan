<?php

require 'koneksi.php';

// hitung semua jumlah total data mahasiswa
function totalDataTraining()
{
   global $con;
   $result = mysqli_query($con, "SELECT count(*) FROM mahasiswa");
   $totalDataTraining =  mysqli_fetch_row($result);
   return (int) $totalDataTraining[0];
}

// hitung  jumlah total data mahasiswa dengan status kelulusan = tepat
function totalStatusKelulusanTepat()
{
   global $con;
   $result = mysqli_query($con, "SELECT count(*) FROM mahasiswa WHERE status_kelulusan = 'tepat'");
   $totalData =  mysqli_fetch_row($result);
   return (int) $totalData[0];
}

// hitung  jumlah total data mahasiswa dengan status kelulusan = terlambat
function totalStatusKelulusanTerlambat()
{
   global $con;
   $result = mysqli_query($con, "SELECT count(*) FROM mahasiswa WHERE status_kelulusan = 'terlambat'");
   $totalData =  mysqli_fetch_row($result);
   return (int) $totalData[0];
}

// hitung prior probability
