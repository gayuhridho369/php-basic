<?php
session_start();

if (!isset($_SESSION["login"])) {
    header("Location: login.php");
    exit;
}

require 'function.php';

// Ambil data di URL
$id = $_GET["id"];
// Query data mahasiswa berdasarkan id
$mhs = query("SELECT * FROM mahasiswa WHERE id = $id")[0]; // [0] karena yang di panggil array numerik

// Cek tombol submit
if (isset($_POST["submit"])) {
    // Cek data berhasil diubah atau tidak
    if (ubah($_POST) > 0) {
        echo "
                <script>
                    alert('Data berhasil diubah!');
                    document.location.href = 'index.php';
                </script>
           ";
    } else {
        echo "
                <script>
                    alert('Data gagal diubah!');
                    document.location.href = 'index.php';
                </script>
           ";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>
    <h1>Ubah Data</h1>

    <form action="" method="post" enctype="multipart/form-data">
        <ul>
            <!-- cara untuk membawa nilai id tapi tidak terlihat di form, karena id unique tidak boleh diubah -->
            <input type="hidden" name="id" value="<?= $mhs["id"]; ?>">
            <input type="hidden" name="gambarLama" value="<?= $mhs["gambar"]; ?>">

            <li>
                <label for="nim">NIM : </label>
                <input type="text" name="nim" id="nim" required value="<?= $mhs["nim"]; ?>">
            </li>

            <li>
                <label for="nama">Nama : </label>
                <input type="text" name="nama" id="nama" required value="<?= $mhs["nama"]; ?>">
            </li>

            <li>
                <label for="email">Email : </label>
                <input type="text" name="email" id="email" required value="<?= $mhs["email"]; ?>">
            </li>

            <li>
                <label for="jurusan">Jurusan : </label>
                <input type="text" name="jurusan" id="jurusan" required value="<?= $mhs["jurusan"]; ?>">
            </li>

            <li>
                <label for="gambar">Gambar : </label> <br>
                <img src="img/<?= $mhs["gambar"]; ?>" width="70" ; height="70"> <br>
                <input type="file" name="gambar" id="gambar">
            </li>

            <li>
                <button type="submit" name="submit">Ubah Data</button>
            </li>
        </ul>

    </form>
</body>

</html>