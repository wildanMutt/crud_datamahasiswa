<?php 

function koneksi() 
{
    return mysqli_connect('localhost', 'root', '', 'crud_datamhs');
}

function query ($query) 
{
    $conn = koneksi();
    $result = mysqli_query($conn, $query);
    
    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) {
    $rows[] = $row;
}

    return $rows;

}

function upload()
{
    $nama_file = $_FILES['gambar']['name'];
    $tipe_file = $_FILES['gambar']['type'];
    $ukuran_file = $_FILES['gambar']['size'];
    $error = $_FILES['gambar']['error'];
    $tmp_file = $_FILES['gambar']['tmp_name'];

    //ketika tidak ada gambar yang dipilih
    if($error == 4){
        echo "<script>
                alert('Pilih gambar terlebih dahulu!');
            </script>";
        return false;
    }

    //cek ekstensi file
    $daftar_gambar = ['jpg', 'jpeg', 'png'];
    $ekstensi_file = explode('.', $nama_file);
    $ekstensi_file = strtolower(end($ekstensi_file));
    if (!in_array($ekstensi_file, $daftar_gambar)){
        echo "<script>
                alert('Yang Anda pilih bukan gambar!');
            </script>";
        return false;
    }

    //cek type file
    if ($tipe_file != 'image/jpeg'&& $tipe_file !='image/png') {
        echo "<script>
                alert('Yang Anda pilih bukan gambar!');
            </script>";
        return false;
    }

    //cek ukuran file
    //maksimal 5 mb
    if($ukuran_file > 5000000) {
        echo "<script>
                alert('Ukuran terlalu besar!');
            </script>";
        return false;
    }

    //lolos pengecekan
    //siap upload file
    //generate nama file baru
    $nama_file_baru = uniqid();
    $nama_file_baru .= '.';
    $nama_file_baru .= $ekstensi_file;
     
    move_uploaded_file($tmp_file, 'img/' . $nama_file_baru);

    return $nama_file_baru;
}

function tambah($data)
{
    $conn = koneksi();

    $nama = htmlspecialchars($data['nama']);
    $nrp = htmlspecialchars ($data['nrp']);
    $email = htmlspecialchars ($data['email']);
    $jurusan = htmlspecialchars ($data['jurusan']);
    //$gambar = htmlspecialchars ($data['gambar']);
    
    //upload gambar
    $gambar = upload();

    if(!$gambar) {
        return false;
    }

    $query = "INSERT INTO
                mahasiswa
                VALUES 
                (null, '$nama', '$nrp', '$email', '$jurusan', '$gambar' );
            ";
    mysqli_query($conn, $query) or die(mysqli_error($conn));

    echo mysqli_error($conn);


    return mysqli_affected_rows($conn);
}

function hapus($id)
{
    $conn = koneksi();
    mysqli_query($conn, "DELETE FROM mahasiswa WHERE id = $id") or die(mysqli_error($conn));
    return mysqli_affected_rows($conn);
}

function ubah($data)
{
    $conn = koneksi();

    $id = ($data['id']);
    $nama = htmlspecialchars($data['nama']);
    $nrp = htmlspecialchars ($data['nrp']);
    $email = htmlspecialchars ($data['email']);
    $jurusan = htmlspecialchars ($data['jurusan']);
    $gambar = htmlspecialchars ($data['gambar']);

    $query = "UPDATE mahasiswa SET
                nama = '$nama', 
                nrp = '$nrp', 
                email = '$email', 
                jurusan = '$jurusan', 
                gambar = '$gambar'
            WHERE id = $id";
            
    mysqli_query($conn, $query) or die(mysqli_error($conn));

    echo mysqli_error($conn);


    return mysqli_affected_rows($conn);
}

function cari($keyword) {
    $conn = koneksi();

    $query = "SELECT * FROM mahasiswa
                WHERE 
                nama LIKE '%$keyword%' OR
                nrp LIKE '%$keyword%' OR
                jurusan LIKE '%$keyword%'  ";

    $result = mysqli_query($conn, $query);

    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }

    return $rows;
}

function login ($data)
{
    $conn = koneksi ();

    $username = htmlspecialchars($data['username']);
    $password = htmlspecialchars($data['password']);

    if(query("SELECT * FROM user WHERE username='$username' && password='$password' ")) {

        //set session
        $_SESSION['login'] = true;

        header("Location: index.php");
        exit;
    } else {
        return [
            'error' => true,
            'pesan' => 'Username / password salah'
        ];
    }

}

function registrasi ($data)
{
    $conn = koneksi();

    $username = htmlspecialchars(strtolower($data['username']));
    $password1 = mysqli_real_escape_string($conn, $data['password1']);
    $password2 = mysqli_real_escape_string($conn, $data['password2']);

    //jika username atau password kosong
    if (empty($username) || empty($password1) || empty($password2)) {
        echo "<script>
                alert('Username / password tidak boleh kosong!');
                document.location.href = 'registrasi.php';
            </script>";
        return false;
    }

    //jika username sudah ada
    if(query("SELECT * FROM user WHERE username = '$username' ")) {
        echo "<script>
                alert('Username sudah terdaftar');
                document.location.href = 'registrasi.php';
            </script>";
        return false;
    }

    //jika konfirmasi password tidak sesuai
    if ($password1 !== $password2) {
        echo "<script>
                alert('Konfirmasi password tidak sesuai');
                document.location.href = 'registrasi.php';
            </script>";
        return false;
    }
    
    //jika password < 5 digit
    if (strlen($password1) < 5) {
        echo "<script>
                alert('Password terlalu pendek');
                document.location.href = 'registrasi.php';
            </script>";
        return false;
    }

    //jika username dan password nya sudah sesuai
    //enkripsi password

    //insert ke tabel user
    $query = "INSERT INTO user
                VALUES
                (null, '$username', '$password1')";

    mysqli_query($conn, $query) or die(mysqli_error($conn));
    return mysqli_affected_rows($conn);
}


?>