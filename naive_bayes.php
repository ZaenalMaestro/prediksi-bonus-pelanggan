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
   $kelas['ya'] = jumlahDataKelas()['ya'] / totalDataTraining();
   $kelas['tidak'] = jumlahDataKelas()['tidak'] / totalDataTraining();
   return $kelas;
}

// 2. Hitung conditional probablity
function conditionalProbability($nama_kolom, $nilai)
{
   global $con;
   $query = "SELECT COUNT($nama_kolom) FROM pelanggan WHERE $nama_kolom = '$nilai' AND bonus=";

   $conditionalProbability['ya'] = (int) mysqli_fetch_row(mysqli_query($con, $query . "'ya'"))[0] / jumlahDataKelas()['ya'];
   $conditionalProbability['tidak'] = (int) mysqli_fetch_row(mysqli_query($con, $query . "'tidak'"))[0] / jumlahDataKelas()['tidak'];

   return $conditionalProbability;
}

// hitung poseterior probability
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
