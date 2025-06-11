<?php
session_start();
include("baglanti.php");

if (!isset($_SESSION["musteri_id"])) {
    header("Location: giris_yap.php");
    exit;
}

$musteri_id = $_SESSION["musteri_id"];
$mesaj = "";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['odeme_turu'])) {
    $odeme_turu = $_POST['odeme_turu'];

    try {
        $baglanti->set_charset("utf8mb4");

        // ODEME_ID'yi al
        $sorgu = $baglanti->prepare("SELECT ODEME_ID FROM ODEME WHERE ODEME_TURU = ?");
        $sorgu->bind_param("s", $odeme_turu);
        $sorgu->execute();
        $sonuc = $sorgu->get_result();

        if ($satir = $sonuc->fetch_assoc()) {
            $odeme_id = $satir["ODEME_ID"];
            $sorgu->close();

            // Sipariş oluştur
            $stmt = $baglanti->prepare("CALL SIPARIS_OLUSTUR(?, ?)");
            $stmt->bind_param("ii", $musteri_id, $odeme_id);
            $stmt->execute();
            $stmt->close();

            $mesaj = "✅ Sipariş başarıyla oluşturuldu!";
        } else {
            $mesaj = "❌ Seçilen ödeme türü bulunamadı!";
        }

    } catch (mysqli_sql_exception $e) {
        $mesaj = "❌ Sipariş oluşturulurken hata: " . $e->getMessage();
    }

} else {
    $mesaj = "❌ Ödeme türü seçilmedi.";
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
