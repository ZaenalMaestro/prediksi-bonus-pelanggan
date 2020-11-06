<?php

require 'koneksi.php';

// hitung semua jumlah total data mahasiswa
function totalDataTraining()
{
   global $con;
   return (int) mysqli_fetch_row(mysqli_query($con, "SELECT count(*) FROM mahasiswa"))[0];
}

// hitung  jumlah total data mahasiswa dengan status kelulusan = tepat
function totalStatusKelulusan()
{
   global $con;
   $query = "SELECT count(*) FROM mahasiswa WHERE status_kelulusan=";

   $data['tepat']       = (int) mysqli_fetch_row(mysqli_query($con, $query . "'tepat'"))[0];
   $data['terlambat']   = (int) mysqli_fetch_row(mysqli_query($con, $query . "'terlambat'"))[0];
   return $data;
}


function priorStatusKelulusan()
{
   $hasil['tepat'] = totalStatusKelulusan()['tepat'] / totalDataTraining();
   $hasil['terlambat'] = totalStatusKelulusan()['terlambat'] / totalDataTraining();
   return $hasil;
}


// priorProbability status kelulusan = terlambat


// tahap 2
function hitungConditionalProbability($key, $value)
{
   global $con;
   $query = "SELECT COUNT($key) FROM mahasiswa WHERE $key = '$value' AND status_kelulusan";

   $hasil['tepat'] = (int) mysqli_fetch_row(mysqli_query($con, $query . "='tepat'"))[0] / totalStatusKelulusan()['tepat'];
   $hasil['terlambat'] = (int) mysqli_fetch_row(mysqli_query($con, $query . "='terlambat'"))[0] / totalStatusKelulusan()['terlambat'];

   return $hasil;
}


// Tahap 3
function prediksiNaiveBayes($data)
{
   $hasil['jenis_kelamin'] = hitungConditionalProbability('jenis_kelamin', $data['jenis_kelamin']);
   $hasil['status_mahasiswa'] = hitungConditionalProbability('status_mahasiswa', $data['status_mahasiswa']);
   $hasil['status_pernikahan'] = hitungConditionalProbability('status_pernikahan', $data['status_pernikahan']);
   $hasil['ipk_semester'] = hitungConditionalProbability('ipk_semester', $data['ipk_semester']);
   $nilaiTepat = $hasil['jenis_kelamin']['tepat'] * $hasil['status_mahasiswa']['tepat'] * $hasil['status_pernikahan']['tepat'] * $hasil['ipk_semester']['tepat'] * priorStatusKelulusan()['tepat'];
   $nilaiTerlambat = $hasil['jenis_kelamin']['terlambat'] * $hasil['status_mahasiswa']['terlambat'] * $hasil['status_pernikahan']['terlambat'] * $hasil['ipk_semester']['terlambat'] * priorStatusKelulusan()['terlambat'];

   if ($nilaiTepat > $nilaiTerlambat) {
      return 'TEPAT';
   } else if ($nilaiTepat < $nilaiTerlambat) {
      return 'TERLAMBAT';
   }
}
