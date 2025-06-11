<?php
session_start();
include("baglanti.php");

$mesaj = "";

try {
    // Prosedürü çağır
    $stmt = $baglanti->prepare("CALL MUSTERI_KATEGORILERI()");
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
    <title>Kategoriler</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h2 { text-align: center; }
        ul { list-style-type: none; padding: 0; max-width: 400px; margin: auto; }
        li { padding: 10px; border-bottom: 1px solid #ccc; }
        a { text-decoration: none; color: #333; }
        a:hover { text-decoration: underline; color: blue; }
    </style>
</head>
<body>

<h2>Kategoriler</h2>

<?php if ($mesaj != ""): ?>
    <p style="color:red; text-align:center;"><?= htmlspecialchars($mesaj) ?></p>
<?php endif; ?>

<ul>
    <?php while ($row = $sonuc->fetch_assoc()): ?>
        <li>
            <a href="kategori_urunleri.php?kategori_id=<?= $row["KATEGORI_ID"] ?>">
                <?= htmlspecialchars($row["KATEGORI_ADI"]) ?>
            </a>
        </li>
    <?php endwhile; ?>
</ul>

</body>
</html>
