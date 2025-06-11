<?php
session_start();
include("baglanti.php");

if (!isset($_SESSION["musteri_id"])) {
    header("Location: giris.php");
    exit;
}


if (!isset($_GET["kategori_id"])) {
    echo "Kategori seçilmedi.";
    exit;
}

$kategori_id = (int)$_GET["kategori_id"];
$mesaj = "";

try {
    $stmt = $baglanti->prepare("SELECT URUN_ID, URUN_ADI, FIYAT FROM URUN WHERE KATEGORI_ID = ?");
    $stmt->bind_param("i", $kategori_id);
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
    <title>Kategori Ürünleri</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h2 { text-align: center; }
        .urun { border: 1px solid #ccc; padding: 15px; margin: 10px auto; max-width: 500px; border-radius: 5px; }
        .urun h3 { margin: 0; }
        form { display: flex; justify-content: space-between; align-items: center; margin-top: 10px; }
        input[type="number"] { width: 60px; }
        button { padding: 5px 10px; background-color: #28a745; color: white; border: none; border-radius: 4px; }
        button:hover { background-color: #218838; }
    </style>
</head>
<body>

<h2>Ürünler</h2>

<?php if ($mesaj != ""): ?>
    <p style="color:red; text-align:center;"><?= htmlspecialchars($mesaj) ?></p>
<?php endif; ?>

<?php if ($sonuc->num_rows > 0): ?>
    <?php while ($urun = $sonuc->fetch_assoc()): ?>
        <div class="urun">
            <h3><?= htmlspecialchars($urun["URUN_ADI"]) ?></h3>
            <p>Fiyat: <?= $urun["FIYAT"] ?>₺</p>
            <form action="sepet_ekle.php" method="post">
                <input type="hidden" name="urun_id" value="<?= $urun["URUN_ID"] ?>">
                <label>Adet:</label>
                <input type="number" name="adet" min="1" value="1">
                <button type="submit">Sepete Ekle</button>
            </form>
        </div>
    <?php endwhile; ?>
<?php else: ?>
    <p style="text-align:center;">Bu kategoriye ait ürün bulunamadı.</p>
<?php endif; ?>

</body>
</html>
