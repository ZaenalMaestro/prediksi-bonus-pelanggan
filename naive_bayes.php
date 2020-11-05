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

// priorProbability status kelulusan = tepat
function priorStatusKelulusanTepat()
{
   $hasil = totalStatusKelulusanTepat() / totalDataTraining();
   return $hasil;
}

// priorProbability status kelulusan = terlambat
function priorStatusKelulusanTerlambat()
{
   $hasil = totalStatusKelulusanTerlambat() / totalDataTraining();
   return $hasil;
}

// tahap 2
function hitungConditional($key, $value)
{
   $hasil = [];
   global $con;
   $query = "SELECT COUNT($key) FROM mahasiswa WHERE $key = '$value' AND status_kelulusan";
   for ($i = 0; $i < 2; $i++) {
      if ($i == 0) {
         $data = mysqli_query($con, $query .= "='tepat'");
         $jumlahData = mysqli_fetch_row($data)[0];         
         $hasil['tepat'] = (int)$jumlahData / totalStatusKelulusanTepat();
      }else {
         $data = mysqli_query($con, $query .= "='terlambat'");
         $jumlahData = mysqli_fetch_row($data)[0];
         $hasil['terlambat'] = (int) $jumlahData / totalStatusKelulusanTerlambat();;
      }
   }
   return $hasil;
}


// Tahap 3
function prediksiNaiveBayes($data){
   $hasil['jenis_kelamin'] = hitungConditional('jenis_kelamin', $data['jenis_kelamin']);
   $hasil['status_mahasiswa'] = hitungConditional('status_mahasiswa', $data['status_mahasiswa']);
   $hasil['status_pernikahan'] = hitungConditional('status_pernikahan', $data['status_pernikahan']);
   $hasil['ipk_semester'] = hitungConditional('ipk_semester', $data['ipk_semester']);
   $nilaiTepat = $hasil['jenis_kelamin']['tepat'] * $hasil['status_mahasiswa']['tepat'] * $hasil['status_pernikahan']['tepat'] * $hasil['ipk_semester']['tepat'] * priorStatusKelulusanTepat();
   $nilaiTerlambat = $hasil['jenis_kelamin']['terlambat'] * $hasil['status_mahasiswa']['terlambat'] * $hasil['status_pernikahan']['terlambat'] * $hasil['ipk_semester']['terlambat'] * priorStatusKelulusanTerlambat();

   if ($nilaiTepat > $nilaiTerlambat) {
      return 'TEPAT';
   } else if ($nilaiTepat < $nilaiTerlambat) {
      return 'TERLAMBAT';
   }
}
