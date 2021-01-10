<?php
// Koneksikan ke database
$conn = mysqli_connect("localhost", "root", "root", "php_basic");

function query($query)
{
    global $conn;

    $result = mysqli_query($conn, $query);
    $rows = []; // Membuat wadah
    while ($row = mysqli_fetch_assoc($result)) { // Memanggil data satu persatu dengan pengulangan
        $rows[] = $row; // Memasukan data ke dalam wadah
    }
    return $rows;
}

function tambah($data)
{
    global $conn;

    // Htmlspecialchars() cara sederhana mencegah terjadinya scripting dalam form
    $nim = htmlspecialchars($data["nim"]);
    $nama = htmlspecialchars($data["nama"]);
    $email = htmlspecialchars($data["email"]);
    $jurusan = htmlspecialchars($data["jurusan"]);

    // Upload gambar
    $gambar = upload();
    if (!$gambar) {
        return false;
    }

    // Query insert data
    $query = "INSERT INTO mahasiswa VALUES
                    ( default, '$nim', '$nama', '$email', '$jurusan', '$gambar')";
    mysqli_query($conn, $query);

    return mysqli_affected_rows($conn); // Menghasilkan -1 jika error atau 1 jika berhasil
}

function upload()
{
    // Mengambil data dari file, masukkan ke dalam variable
    $namaFile = $_FILES['gambar']['name'];
    $ukuranFile = $_FILES['gambar']['size'];
    $error = $_FILES['gambar']['error'];
    $tmpName = $_FILES['gambar']['tmp_name'];

    // Cek apakah user upload gambar atau tidak
    if ($error === 4) { // 4 adalah nilai kembali untuk tidak ada gambar
        echo "<script>
                alert ('Pilih gambar terlebih dahulu!');        
            </script>";
        return false;
    }

    // Cek yang di upload adalah gambar
    $ekstensiGambarValid = ['jpg', 'jpeg', 'png'];
    $ekstensiGambar = explode('.', $namaFile); // Explode adalah memecahkan string ke dalam array dengan . sebagai pemisah
    $ekstensiGambar = strtolower(end($ekstensiGambar)); // Strtolower mengubah semua string menjadi huruf kecil

    if (!in_array($ekstensiGambar, $ekstensiGambarValid)) { // In_array mencari string dalam array
        echo "<script>
                alert ('File yang Anda upload bukan gambar!');        
            </script>";
        return false;
    }

    // Cek ukuran gambar, maksimal 2 mb
    if ($ukuranFile > 2000000) {
        echo "<script>
                alert ('Ukuran gambar terlalu besar!');        
            </script>";
        return false;
    }

    // Lolos pengecekan, gambar siap di upload
    // Generate nama baru agar nama file tidak ter replace pada folder penyimpanan
    $namaFileBaru = uniqid();
    $namaFileBaru .= '.';
    $namaFileBaru .= $ekstensiGambar;


    move_uploaded_file($tmpName, 'img/' . $namaFileBaru);
    return $namaFileBaru;
}

function hapus($id)
{
    global $conn;
    mysqli_query($conn, "DELETE FROM mahasiswa WHERE id = $id");

    return mysqli_affected_rows($conn);
}

function ubah($data)
{
    global $conn;

    // Htmlspecialchars() cara sederhana mencegah terjadinya scripting dalam form
    $id = $data["id"];
    $nim = htmlspecialchars($data["nim"]);
    $nama = htmlspecialchars($data["nama"]);
    $email = htmlspecialchars($data["email"]);
    $jurusan = htmlspecialchars($data["jurusan"]);

    $gambarLama = $data["gambarLama"];

    // Cek apakah user pilih gambar baru atau tidak
    if ($_FILES['gambar']['error']) {
        $gambar = $gambarLama;
    } else {
        $gambar = upload();
    }

    // Query insert data
    $query = "UPDATE mahasiswa SET
                    nim = '$nim',
                    nama = '$nama',
                    email = '$email',
                    jurusan = '$jurusan',
                    gambar = '$gambar'
                    WHERE id = $id
                    ";

    mysqli_query($conn, $query);

    return mysqli_affected_rows($conn); // Menghasilkan -1 jika error atau 1 jika berhasil
}

function cari($keyword)
{
    $query = "SELECT * FROM mahasiswa WHERE 
                    nama LIKE '%$keyword%' OR
                    nim LIKE '%$keyword%' OR
                    email LIKE '%$keyword%' OR
                    jurusan LIKE '%$keyword%'
                    ";

    return query($query);
}

function registration($data)
{
    global $conn;

    $username = strtolower(stripslashes($data["username"]));
    $password = mysqli_real_escape_string($conn, $data["password"]);
    $password2 = mysqli_real_escape_string($conn, $data["password2"]);

    // Cek user sudah ada atau belum
    $result = mysqli_query($conn, "SELECT username FROM user WHERE username = '$username'");

    if (mysqli_fetch_assoc($result)) {
        echo "<script>
                alert('Username sudah terdaftar!');
            </script>";
        return false;
    }

    // Cek konfirmasi password
    if ($password !== $password2) {
        echo "<script>
                alert('Konfirmasi password tidak sesuai!');
            </script>";
        return false;
    }

    // Enkripsi password supaya aman di database
    $password = password_hash($password, PASSWORD_DEFAULT);

    // Tambahkan user ke data base
    mysqli_query($conn, "INSERT INTO user VALUES
                    (default, '$username', '$password')");

    return mysqli_affected_rows($conn);
}
