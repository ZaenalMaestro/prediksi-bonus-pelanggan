<?php

require 'koneksi.php';

// hitung semua jumlah total data training
function totalDataTraining()
{
   global $con;
   return (int) mysqli_fetch_row(mysqli_query($con, "SELECT count(*) FROM pelanggan"))[0];
}

// hitung  jumlah data kelas bonus=ya dan kelas benus=tidak
function jumlahDataKelas()
{
   global $con;
   $query = "SELECT count(*) FROM pelanggan WHERE bonus=";

   $jumlahDataBonus['ya']       = (int) mysqli_fetch_row(mysqli_query($con, $query . "'ya'"))[0];
   $jumlahDataBonus['tidak']   = (int) mysqli_fetch_row(mysqli_query($con, $query . "'tidak'"))[0];
   return $jumlahDataBonus;
}

// 1. hitung nilai prior probability
function priorProbability()
{
   /* prior probability = jumlah data kelas(ya|tidak) / total data training */
   // A. untuk prior probability kelas bonus = ya
   $kelas['ya'] = jumlahDataKelas()['ya'] / totalDataTraining();
   // B. untuk prior probability kelas bonus = tidak
   $kelas['tidak'] = jumlahDataKelas()['tidak'] / totalDataTraining();
   return $kelas;
}

// 2. Hitung conditional probablity
function conditionalProbability($nama_kolom, $nilai)
{
   global $con;
   $query = "SELECT COUNT($nama_kolom) FROM pelanggan WHERE $nama_kolom = '$nilai' AND bonus=";

   /* conditional probability = jumlah data atribut(ya|tidak) / jumlah data kelas(ya|tidak)*/
   // => conditional probability kelas bonus = ya
   $conditionalProbability['ya'] = (int) mysqli_fetch_row(mysqli_query($con, $query . "'ya'"))[0] / jumlahDataKelas()['ya'];
   // => conditional probability kelas bonus = tidak
   $conditionalProbability['tidak'] = (int) mysqli_fetch_row(mysqli_query($con, $query . "'tidak'"))[0] / jumlahDataKelas()['tidak'];

   return $conditionalProbability;
}

// hitung poseterior probability
function posteriorProbability($data)
{
   // menghitung nilai conditional probability setiap atribut
   $atribut['kartu'] = conditionalProbability('kartu', $data['kartu']);
   $atribut['panggilan'] = conditionalProbability('panggilan', $data['panggilan']);
   $atribut['blok'] = conditionalProbability('blok', $data['blok']);

   /* posterior probability = conditional probability ke-1 *...* conditional probability ke-n * prior probability */
   // => posterior probability kelas bonus = ya
   $probabilitas['ya'] = $atribut['kartu']['ya'] * $atribut['panggilan']['ya'] * $atribut['blok']['ya'] * priorProbability()['ya'];
   // => posterior probability kelas bonus = tidak
   $probabilitas['tidak'] = $atribut['kartu']['tidak'] * $atribut['panggilan']['tidak'] * $atribut['blok']['tidak'] * priorProbability()['tidak'];

   // membandingkan nilai posterior probability kelas bonus = ya dengan posterior probability kelas bonus = tidak
   if ($probabilitas['ya'] > $probabilitas['tidak']) {
      return 'YA';
   } else if ($probabilitas['ya'] < $probabilitas['tidak']) {
      return 'TIDAK';
   }
}
