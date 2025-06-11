<?php
include("baglanti.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ad = $_POST['ad'];
    $soyad = $_POST['soyad'];
    $telefon = $_POST['telefon'];
    $eposta = $_POST['eposta'];
    $sifre = $_POST['sifre'];

    
    mysqli_query($baglanti, "SET @sonuc = ''");

    
    $baglanti->query("SET @sonuc = ''");

$baglanti->query("SET @sonuc = ''");

$sql = "CALL MUSTERI_EKLE(?, ?, ?, ?, ?, @sonuc)";
$stmt = $baglanti->prepare($sql);
$stmt->bind_param("sssss", $ad, $soyad, $telefon, $eposta, $sifre);
$stmt->execute();
$stmt->close();

$result = $baglanti->query("SELECT @sonuc AS mesaj");
$row = $result->fetch_assoc();
echo "<p>" . $row['mesaj'] . "</p>";



   
    $sonuc = mysqli_query($baglanti, "SELECT @sonuc AS mesaj");
    $row = mysqli_fetch_assoc($sonuc);
    $mesaj = $row['mesaj'];

    echo "<p>$mesaj</p>";

    mysqli_close($baglanti);
}
?>
