<?php
session_start();

if (!isset($_SESSION["login"])) {
    header("Location: login.php");
    exit;
}

// Ambil data mahasiswa
require 'function.php';
$mahasiswa = query("SELECT * FROM mahasiswa");

// Ketika melakukan pencarian data
if (isset($_POST["cari"])) {
    $mahasiswa = cari($_POST["keyword"]);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Halaman Admin</title>

    <style>
        .loader {
            width: 130px;
            position: absolute;
            top: 85px;
            left: 320px;
            z-index: -1;
            display: none;
        }

        @media print {

            .logout,
            .tambah,
            .form-cari,
            .aksi {
                display: none;
            }
        }
    </style>

    <script src="js/jquery-3.4.1.min.js"></script>
    <script src="js/script.js"></script>

</head>

<body>

    <a href="logout.php" style="float:right;" class="logout">Logout</a>

    <h1>Daftar Mahasiswa</h1>

    <a href="cetak.php" target="_blank">Cetak</a> ||

    <a href="tambah.php" class="tambah">Tambah Data</a>
    <br><br>

    <form action="" method="post" class="form-cari">
        <input type="text" name="keyword" size="40" autofocus placeholder="Masukan keyword pencarian....." autocomplete="off" id="keyword">
        <button type="submit" name="cari" id="tombol-cari">Cari</button>
        <img src="img/loader.gif" class="loader">
    </form>
    <br>

    <div id="container">
        <table border="1" cellpadding="10" cellspacing="0">
            <tr>
                <th>No.</th>
                <th class="aksi">Aksi</th>
                <th>Gambar</th>
                <th>NIM</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Jurusan</th>
            </tr>

            <?php $i = 1; ?>
            <?php foreach ($mahasiswa as $mhs) : ?>
                <tr>
                    <td><?= $i; ?></td>
                    <td class="aksi">
                        <a href="ubah.php?id=<?= $mhs["id"]; ?>">Ubah</a> |
                        <a href="hapus.php?id=<?= $mhs["id"]; ?>" onclick="return confirm('Yakin untuk menghapus?'); ">Hapus</a>
                    </td>
                    <td><img src="img/<?= $mhs["gambar"]; ?>" alt="" style="width:70px;height:70px;"></td>
                    <td><?= $mhs["nim"]; ?></td>
                    <td><?= $mhs["nama"]; ?></td>
                    <td><?= $mhs["email"]; ?></td>
                    <td><?= $mhs["jurusan"]; ?></td>
                </tr>
                <?php $i++; ?>
            <?php endforeach; ?>
        </table>
    </div>

</body>

</html>