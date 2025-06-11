<?php
$host = 'localhost';
$user = 'root';
$pass = 'a04u2004';
$db = 'MAGAZA';

$baglanti = mysqli_connect($host, $user, $pass, $db);

if (!$baglanti) {
    die("Bağlantı başarısız: " . mysqli_connect_error());
} else {
  //  echo "Bağlantı başarılı";
}
?>
