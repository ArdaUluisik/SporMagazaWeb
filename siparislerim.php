<?php
session_start();
include("baglanti.php");

if (!isset($_SESSION["musteri_id"])) {
    header("Location: giris_yap.php");
    exit;
}

$musteri_id = $_SESSION["musteri_id"];

$siparisler = [];

try {
    $stmt = $baglanti->prepare("CALL kullanici_siparis_gecmisi(?)");
    $stmt->bind_param("i", $musteri_id);
    $stmt->execute();
    $sonuc = $stmt->get_result();

    while ($row = $sonuc->fetch_assoc()) {
        $siparisler[] = $row;
    }

    $stmt->close();
} catch (mysqli_sql_exception $e) {
    die("Sipariş geçmişi alınırken hata oluştu: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Siparişlerim</title>
</head>
<body>
    <h2>Siparişlerim</h2>

    <?php if (count($siparisler) === 0): ?>
        <p>Henüz siparişiniz bulunmamaktadır.</p>
    <?php else: ?>
        <table border="1" cellpadding="10">
            <tr>
                <th>Sipariş Tarihi</th>
                <th>Ürün Adı</th>
                <th>Adet</th>
                <th>Birim Fiyat</th>
                <th>Toplam Fiyat</th>
                <th>İşlem</th>
            </tr>
            <?php foreach ($siparisler as $siparis): ?>
                <tr>
                    <td><?= htmlspecialchars($siparis["SIPARIS_TARIHI"]) ?></td>
                    <td><?= htmlspecialchars($siparis["URUN_ADI"]) ?></td>
                    <td><?= $siparis["ADET"] ?></td>
                    <td><?= number_format($siparis["FIYAT"], 2) ?> ₺</td>
                    <td><?= number_format($siparis["toplam_fiyat"], 2) ?> ₺</td>
                    <td>
                        <a href="siparis_iptal.php?siparis_id=<?= $siparis["SIPARIS_ID"] ?>"
                           onclick="return confirm('Bu siparişi iptal etmek istediğinizden emin misiniz?');">
                            ❌ İptal Et
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>

    <br><a href="anasayfa.php">Ana Sayfaya Dön</a>
</body>
</html>
