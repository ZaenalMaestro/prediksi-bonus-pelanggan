<?php
require 'naive_bayes.php';
$hasilPrediksi = '';
if (isset($_POST['submit'])) {
   $data = [
      "jenis_kelamin" => $_POST['jenis_kelamin'],
      "status_mahasiswa" => $_POST['status_mahasiswa'],
      "status_pernikahan" => $_POST['status_pernikahan'],
      "ipk_semester" => $_POST['ipk_semester'],
   ];
   $hasilPrediksi = prediksiNaiveBayes($data);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <meta http-equiv="X-UA-Compatible" content="ie=edge">
   <title>Prediksi Status Kelulusan</title>
</head>

<body>
   <!-- judul -->
   <h1>Prediksi Status Kelulusan</h1>
   <!-- form input -->
   <form action="" method="post">
      <!-- input jenis kelamin -->
      <label for="jenis-kelamin">jenis kelamin</label>
      <select name="jenis_kelamin" id="jenis-kelamin">
         <option></option>
         <option value="laki-laki">laki-laki</option>
         <option value="perempuan">perempuan</option>
      </select><br>

      <!-- input status mahasiswa-->
      <label for="status-mahasiswa">status mahasiswa</label>
      <select name="status_mahasiswa" id="status-mahasiswa">
         <option></option>
         <option value="bekerja">bekerja</option>
         <option value="mahasiswa">mahasiswa</option>
      </select><br>

      <!-- input status pernikahan-->
      <label for="status-pernikahan">status pernikahan</label>
      <select name="status_pernikahan" id="status-pernikahan">
         <option></option>
         <option value="menikah">menikah</option>
         <option value="belum">belum</option>
      </select><br>

      <!-- input ipk semester -->
      <label for="ipk-semester">IPK semester</label>
      <input type="text" name="ipk_semester" required><br>

      <!-- tombol submit -->
      <button type="submit" name="submit">PREDIKSI</button>
   </form>

   <!-- hasil -->
   <h5>Hasil Prediksi : <?= $hasilPrediksi ?></h5>
</body>

</html>