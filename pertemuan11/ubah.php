<?php

require 'functions.php';

//jika tidak ada id di url
if(!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

//Ambil data dari url
$id = $_GET['id'];

//query mahasiswa berdasarkan id
$m = query("SELECT * FROM mahasiswa WHERE id = $id")[0];



//cek apakah tombol ubah sudah ditekan
if ( isset($_POST['ubah'])) {
    if ( ubah($_POST) > 0) {
        echo "<script>
                alert('Pengubahan data berhasil');
                document.location.href = 'index.php';
        </script>";
    } else {
        echo "Pengubahan data gagal";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ubah Data Mahasiswa</title>
</head>
<body>

    <h3>Form Ubah Data Mahasiswa</h3>

    <form action="" method="POST">
        <input type="hidden" name="id" value="<?php echo $m['id']; ?>">
        <ul>
            <li>
                <label>
                    Nama :
                    <input type="text" name="nama" autofocus required value="<?php echo $m['nama']; ?>">
                <label>
            </li> 
            <li>   
                <label>
                    NRP :  
                    <input type="text" name="nrp" required value="<?php echo $m['nrp']; ?>">
                <label>
            </li>
            <li>
                <label>
                    Email :
                    <input type="text" name="email" required value="<?php echo $m['email']; ?>">
                <label>
            </li>
            <li>
                <label>
                    Jurusan
                    <input type="text" name="jurusan" required value="<?php echo $m['jurusan']; ?>">
                <label>
            </li>
            <li>
                <label>
                    Gambar :
                    <input type="text" name="gambar" required value="<?php echo $m['gambar']; ?>">
                <label>
            </li>
            <li>
                <button type="submit" name="ubah">Ubah Data</button>
            </li>
        </ul>
    </form>
    
</body>
</html>