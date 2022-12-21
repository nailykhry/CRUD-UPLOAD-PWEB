<?php
// Load file koneksi.php
include "koneksi.php";
// Ambil data ID yang dikirim oleh form_ubah.php melalui URL
$id = $_GET['id'];
// Ambil Data yang Dikirim dari Form
$nis = $_POST['nis'];
$nama = $_POST['nama'];
$jenis_kelamin = $_POST['jenis_kelamin'];
$telp = $_POST['telp'];
$alamat = $_POST['alamat'];
// Ambil data foto yang dipilih dari form
$foto = $_FILES['foto']['name'];
$tmp = $_FILES['foto']['tmp_name'];

// Cek apakah user ingin mengubah fotonya atau tidak
if(empty($foto)){ // Jika user tidak memilih file foto pada form

  // Lakukan proses update tanpa mengubah fotonya
  // Proses ubah data ke Database
  $query = "UPDATE siswa SET nis='$nis', nama='$nama', jenis_kelamin='$jenis_kelamin', telp='$telp', alamat='$alamat' WHERE id='$id'";

  if(mysqli_query($conn, $query)){ // Cek jika proses simpan ke database sukses atau tidak
    // Jika Sukses, Lakukan :
    header("location: index.php"); // Redirect ke halaman index.php
  }else{
    // Jika Gagal, Lakukan :
    echo "Maaf, Terjadi kesalahan saat mencoba untuk menyimpan data ke database.";
    echo "<br><a href='form_ubah.php'>Kembali Ke Form</a>";
  }
}else{ // Jika user memilih foto / mengisi input file foto pada form
  // Lakukan proses update termasuk mengganti foto sebelumnya
  // Rename nama fotonya dengan menambahkan tanggal dan jam upload
  $fotobaru = date('dmYHis').$foto;
  // Set path folder tempat menyimpan fotonya
  $path = "images/".$fotobaru;
  // Proses upload
  if(move_uploaded_file($tmp, $path)){ // Cek apakah gambar berhasil diupload atau tidak
    // Query untuk menampilkan data siswa berdasarkan ID yang dikirim
    $sql = "SELECT foto FROM siswa WHERE id='$id'";
    $data = $conn->query($sql); // Ambil semua data dari hasil eksekusi $sql
    $data = $data->fetch_assoc();
    // Cek apakah file foto sebelumnya ada di folder images
    if(is_file("images/".$data['foto'])) // Jika foto ada
      unlink("images/".$data['foto']); // Hapus file foto sebelumnya yang ada di folder images
    // Proses ubah data ke Database
    $query = "UPDATE siswa SET nis='$nis', nama='$nama', jenis_kelamin='$jenis_kelamin', telp='$telp', alamat='$alamat', foto='$fotobaru' WHERE id='$id'";
    if(mysqli_query($conn, $query)){ // Cek jika proses simpan ke database sukses atau tidak
      // Jika Sukses, Lakukan :
      header("location: index.php"); // Redirect ke halaman index.php
    }else{
      // Jika Gagal, Lakukan :
      echo "Maaf, Terjadi kesalahan saat mencoba untuk menyimpan data ke database.";
      echo "<br><a href='form_ubah.php'>Kembali Ke Form</a>";
    }
  }else{
    // Jika gambar gagal diupload, Lakukan :
    echo "Maaf, Gambar gagal untuk diupload.";
    echo "<br><a href='form_ubah.php'>Kembali Ke Form</a>";
  }
}
?>