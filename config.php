<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "hightsnoebity";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Koneksi Gagal: " . $conn->connect_error);
}
?>