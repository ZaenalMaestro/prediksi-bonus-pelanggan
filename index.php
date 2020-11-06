<?php
require 'naive_bayes.php';
$hasilPrediksi = '';
if (isset($_POST['submit'])) {
   $data = [
      "kartu" => $_POST['kartu'],
      "panggilan" => $_POST['panggilan'],
      "blok" => $_POST['blok'],
   ];
   $hasilPrediksi = posteriorProbability($data);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <meta http-equiv="X-UA-Compatible" content="ie=edge">
   <style>
   input, select{
      display: block;
   }
   </style>
   <title>Penentuan Bonus Pelanggan Dengan Naive Bayes</title>
</head>

<body>
   <!-- judul -->
   <h1>Penentuan Bonus Pelanggan</h1>
   <!-- form input -->
   <form action="" method="post">
      <!-- input pelanggan -->
      <label for="pelanggan">nama pelanggan</label>
      <input type="text" name="pelanggan" id="pelanggan">

      <!-- pilih kartu-->
      <label for="kartu">Kartu</label>
      <select name="kartu" id="kartu">
         <option></option>
         <option value="prabayar">prabayar</option>
         <option value="pascabayar">pascabayar</option>
      </select>

      <!-- pilih panggilan-->
      <label for="panggilan">panggilan</label>
      <select name="panggilan" id="panggilan">
         <option></option>
         <option value="sedikit">sedikit</option>
         <option value="cukup">cukup</option>
         <option value="banyak">banyak</option>
      </select>

      <!-- pilih blok-->
      <label for="blok">blok</label>
      <select name="blok" id="blok">
         <option></option>
         <option value="rendah">rendah</option>
         <option value="sedang">sedang</option>
         <option value="tinggi">tinggi</option>
      </select>

      <!-- tombol submit -->
      <button type="submit" name="submit">PREDIKSI</button>
   </form>

   <!-- hasil -->
   <h5>BONUS : <?= $hasilPrediksi ?></h5>
</body>

</html>