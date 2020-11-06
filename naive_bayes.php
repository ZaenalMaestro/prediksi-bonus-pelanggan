<?php

require 'koneksi.php';

// hitung semua jumlah total data mahasiswa
function totalDataTraining()
{
   global $con;
   return (int) mysqli_fetch_row(mysqli_query($con, "SELECT count(*) FROM pelanggan"))[0];
}

// hitung  jumlah total data pelanggan dengan bonus ya dan bonus tidak
function jumlahDataKelas()
{
   global $con;
   $query = "SELECT count(*) FROM pelanggan WHERE bonus=";

   $jumlahDataBonus['ya']       = (int) mysqli_fetch_row(mysqli_query($con, $query . "'ya'"))[0];
   $jumlahDataBonus['tidak']   = (int) mysqli_fetch_row(mysqli_query($con, $query . "'tidak'"))[0];
   return $jumlahDataBonus;
}


function priorProbability()
{
   $kelas['ya'] = jumlahDataKelas()['ya'] / totalDataTraining();
   $kelas['tidak'] = jumlahDataKelas()['tidak'] / totalDataTraining();
   return $kelas;
}

// tahap 2
function conditionalProbability($nama_kolom, $nilai)
{
   global $con;
   $query = "SELECT COUNT($nama_kolom) FROM pelanggan WHERE $nama_kolom = '$nilai' AND bonus=";

   $conditionalProbability['ya'] = (int) mysqli_fetch_row(mysqli_query($con, $query . "'ya'"))[0] / jumlahDataKelas()['ya'];
   $conditionalProbability['tidak'] = (int) mysqli_fetch_row(mysqli_query($con, $query . "'tidak'"))[0] / jumlahDataKelas()['tidak'];

   return $conditionalProbability;
}

// Tahap 3
function posteriorProbability($data)
{
   $atribut['kartu'] = conditionalProbability('kartu', $data['kartu']);
   $atribut['panggilan'] = conditionalProbability('panggilan', $data['panggilan']);
   $atribut['blok'] = conditionalProbability('blok', $data['blok']);

   $probabilitas['ya'] = $atribut['kartu']['ya'] * $atribut['panggilan']['ya'] * $atribut['blok']['ya'] * priorProbability()['ya'];
   $probabilitas['tidak'] = $atribut['kartu']['tidak'] * $atribut['panggilan']['tidak'] * $atribut['blok']['tidak'] * priorProbability()['tidak'];

   if ($probabilitas['ya'] > $probabilitas['tidak']) {
      return 'YA';
   } else if ($probabilitas['ya'] < $probabilitas['tidak']) {
      return 'TIDAK';
   }
}
