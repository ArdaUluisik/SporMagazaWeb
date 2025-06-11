<?php
session_start();
include("baglanti.php");

if (!isset($_SESSION["musteri_id"])) {
    header("Location: giris.php");
    exit;
}

$musteri_id = $_SESSION["musteri_id"];
$mesaj = "";

try {
    $stmt = $baglanti->prepare("CALL SEPET_GORUNTULE(?)");
    $stmt->bind_param("i", $musteri_id);
    $stmt->execute();
    $sonuc = $stmt->get_result();
} catch (mysqli_sql_exception $e) {
    $mesaj = "Hata: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Sepetim</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { border-collapse: collapse; width: 80%; margin: auto; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: center; }
        th { background-color: #f2f2f2; }
        h2 { text-align: center; }
        .temizle-btn {
            text-align: center;
            margin-top: 20px;
        }
        .temizle-btn button {
            padding: 8px 16px;
            background-color: red;
            color: white;
            border: none;
            border-radius: 5px;
        }
        .temizle-btn button:hover {
            background-color: darkred;
        }
    </style>
</head>
<body>

<h2>Sepetim</h2>

<?php if ($mesaj): ?>
    <p style="color: red; text-align: center;"><?= htmlspecialchars($mesaj) ?></p>
<?php else: ?>
    <table>
        <tr>
            <th>Ürün Adı</th>
            <th>Adet</th>
            <th>Birim Fiyat</th>
            <th>Toplam Tutar</th>
        </tr>
        <?php 
        $toplam = 0;
        while ($row = $sonuc->fetch_assoc()):
            $toplam += $row["TOPLAM_TUTAR"];
        ?>
        <tr>
            <td><?= htmlspecialchars($row["URUN_ADI"]) ?></td>
            <td><?= htmlspecialchars($row["ADET"]) ?></td>
            <td><?= htmlspecialchars($row["FIYAT"]) ?> ₺</td>
            <td><?= htmlspecialchars($row["TOPLAM_TUTAR"]) ?> ₺</td>
        </tr>
        <?php endwhile; ?>
        <tr>
            <td colspan="3"><strong>Genel Toplam</strong></td>
            <td><strong><?= $toplam ?> ₺</strong></td>
        </tr>
    </table>

    <div class="temizle-btn">
        <form action="sepet_sil.php" method="post" onsubmit="return confirm('Sepeti silmek istediğinize emin misiniz?');">
            <button type="submit">Sepeti Temizle</button>
        </form>
    </div>
    <form action="sepet_onayla.php" method="post">
    <button type="submit">Sepeti Onayla</button>
</form>

<?php endif; ?>

</body>
</html>
