<?php
session_start();
include("baglanti.php");

if (!isset($_SESSION["admin_id"])) {
    header("Location: admin_giris.php");
    exit;
}

$admin_id = $_SESSION["admin_id"];
$mesaj = "";

// Kategori listesini çek
$kategori_sorgu = $baglanti->query("SELECT KATEGORI_ID, KATEGORI_ADI FROM KATEGORI");

// Ürün silme işlemi
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["sil_id"])) {
    $sil_id = (int)$_POST["sil_id"];
    $stmt_sil = $baglanti->prepare("CALL URUN_SIL(?, ?)");
    $stmt_sil->bind_param("ii", $sil_id, $admin_id);
    $stmt_sil->execute();
    $mesaj = "Ürün silindi.";
}

// Ürün ekleme işlemi
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["urun_adi"])) {
    $urun_adi = trim($_POST["urun_adi"]);
    $fiyat = (int)$_POST["fiyat"];
    $kategori_id = (int)$_POST["kategori_id"];

    if ($urun_adi != "" && $fiyat > 0 && $kategori_id > 0) {
        $stmt = $baglanti->prepare("CALL URUN_EKLE(?, ?, ?, ?)");
        $stmt->bind_param("siii", $urun_adi, $kategori_id, $fiyat, $admin_id);
        $stmt->execute();
        $mesaj = "Ürün başarıyla eklendi!";
    } else {
        $mesaj = "Lütfen tüm alanları doğru doldurun.";
    }
}

// Tüm ürünleri çek
$urunler = $baglanti->query("
    SELECT U.URUN_ID, U.URUN_ADI, U.FIYAT, K.KATEGORI_ADI
    FROM URUN U
    JOIN KATEGORI K ON U.KATEGORI_ID = K.KATEGORI_ID
    ORDER BY U.URUN_ID DESC
");
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Ürün Ekle</title>
    <style>
        body { font-family: Arial; margin: 30px; }
        input, select, button { margin-top: 5px; padding: 5px; }
        table { width: 80%; border-collapse: collapse; margin-top: 30px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: center; }
        th { background-color: #f4f4f4; }
    </style>
</head>
<body>

    <h2>Ürün Ekle</h2>

    <?php if ($mesaj != "") echo "<p style='color:green;'>$mesaj</p>"; ?>

    <form method="post">
        <label>Ürün Adı:</label><br>
        <input type="text" name="urun_adi" required><br><br>

        <label>Fiyat:</label><br>
        <input type="number" name="fiyat" required><br><br>

        <label>Kategori:</label><br>
        <select name="kategori_id" required>
            <option value="">Kategori Seç</option>
            <?php while($row = $kategori_sorgu->fetch_assoc()): ?>
                <option value="<?= $row["KATEGORI_ID"] ?>"><?= $row["KATEGORI_ADI"] ?></option>
            <?php endwhile; ?>
        </select><br><br>

        <button type="submit">Ürün Ekle</button>
    </form>

    <hr>

    <h2>Mevcut Ürünler</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Adı</th>
            <th>Fiyat</th>
            <th>Kategori</th>
            <th>Sil</th>
        </tr>
        <?php while($urun = $urunler->fetch_assoc()): ?>
        <tr>
            <td><?= $urun["URUN_ID"] ?></td>
            <td><?= htmlspecialchars($urun["URUN_ADI"]) ?></td>
            <td><?= $urun["FIYAT"] ?>₺</td>
            <td><?= htmlspecialchars($urun["KATEGORI_ADI"]) ?></td>
            <td>
                <form method="post" onsubmit="return confirm('Bu ürünü silmek istediğinize emin misiniz?');">
                    <input type="hidden" name="sil_id" value="<?= $urun["URUN_ID"] ?>">
                    <button type="submit">Sil</button>
                </form>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>

    <br><a href="admin_anasayfa.php">← Geri dön</a>

</body>
</html>
