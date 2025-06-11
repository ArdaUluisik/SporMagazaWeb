<?php
session_start();
include("baglanti.php");

if (!isset($_SESSION["musteri_id"])) {
    header("Location: giris_yap.php");
    exit;
}

$mesaj = "";

if (isset($_GET["siparis_id"])) {
    $siparis_id = intval($_GET["siparis_id"]);

    try {
        $stmt = $baglanti->prepare("CALL SIPARIS_IPTAL(?)");
        $stmt->bind_param("i", $siparis_id);
        $stmt->execute();
        $stmt->close();

        $mesaj = "✅ Sipariş başarıyla iptal edildi ve stok güncellendi.";
    } catch (mysqli_sql_exception $e) {
        $mesaj = "❌ Sipariş iptal edilirken hata oluştu: " . $e->getMessage();
    }
} else {
    $mesaj = "❗ Sipariş ID belirtilmedi.";
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Sipariş İptal</title>
</head>
<body>
    <h2>Sipariş İptal</h2>
    <p><?= htmlspecialchars($mesaj) ?></p>
    <a href="siparislerim.php">Siparişlerime Dön</a> |
    <a href="anasayfa.php">Ana Sayfa</a>
</body>
</html>
