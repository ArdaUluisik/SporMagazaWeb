<?php
session_start();
include("baglanti.php");



if (!isset($_SESSION["musteri_id"])) {
    header("Location: giris_yap.php");
    exit;
}

$musteri_id = $_SESSION["musteri_id"];
echo "Musteri ID: " . $musteri_id . "<br>";
$odeme_id = 1; // Sabit örnek ödeme ID

$mesaj = "";

try {
    
    $baglanti->set_charset("utf8mb4");

    
    $stmt = $baglanti->prepare("CALL SIPARIS_OLUSTUR(?, ?)");
    $stmt->bind_param("ii", $musteri_id, $odeme_id);
    $stmt->execute();
    $stmt->close();

    $mesaj = "✅ Sipariş başarıyla oluşturuldu!";
} catch (mysqli_sql_exception $e) {
   
    $mesaj = "❌ Sipariş oluşturulurken hata: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Sepeti Onayla</title>
</head>
<body>
    <h2>Sepet Onay</h2>
    <p><?= htmlspecialchars($mesaj) ?></p>
    <a href="anasayfa.php">Ana Sayfaya Dön</a>
</body>
</html>
