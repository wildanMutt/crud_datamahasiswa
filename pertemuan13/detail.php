<?php

session_start();

if(!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

require 'functions.php';

//ambil id dari url
$id = $_GET['id'];

//query mahasiswa berdasarkan id
$mahasiswa = query("SELECT * FROM mahasiswa WHERE id = $id")[0];


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Mahasiswa</title>
</head>
<body>
    <h3>Detail Mahasiswa</h3>
    <ul>
        <li><img src="img/<?php echo $mahasiswa['gambar']; ?>" alt="" width="250"></li>
        <li>NRP : <?php echo $mahasiswa['nrp']; ?></li>
        <li>Nama : <?php echo $mahasiswa['nama']; ?></li>
        <li>Email : <?php echo $mahasiswa['email']; ?></li>
        <li>Jurusan : <?php echo $mahasiswa['jurusan']; ?></li>

        <li><a href="ubah.php?id=<?php echo $mahasiswa['id']; ?>">Ubah</a> | <a href="hapus.php?id=<?php echo $mahasiswa['id']; ?>" onclick="return confirm('Apakah Anda yakin?');">Hapus</a></li>
        
        <li><a href="index.php">Kembali Ke Daftar Mahasiswa</a></li>
    </ul>
</body>
</html>