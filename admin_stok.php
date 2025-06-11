<?php
session_start();
include("baglanti.php");


if (!isset($_SESSION["admin_id"])) {
    header("Location: admin_giris.php");
    exit;
}

$admin_id = $_SESSION["admin_id"];


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["urun_id"], $_POST["stok_adeti"], $_POST["fiyat"])) {
    $urun_id = (int)$_POST["urun_id"];
    $stok_adeti = (int)$_POST["stok_adeti"];
    $fiyat = (int)$_POST["fiyat"];

    try {
       
        $stmt_stok = $baglanti->prepare("CALL STOK_GUNCELLE(?, ?, ?)");
        $stmt_stok->bind_param("iii", $urun_id, $stok_adeti, $admin_id);
        $stmt_stok->execute();
        do {
            if ($r = $stmt_stok->get_result()) $r->free();
        } while ($stmt_stok->more_results() && $stmt_stok->next_result());
        $stmt_stok->close();

        
        $stmt_fiyat = $baglanti->prepare("CALL URUN_GUNCELLE(?, ?, ?)");
        $stmt_fiyat->bind_param("iii", $urun_id, $fiyat, $admin_id);
        $stmt_fiyat->execute();
        do {
            if ($r = $stmt_fiyat->get_result()) $r->free();
        } while ($stmt_fiyat->more_results() && $stmt_fiyat->next_result());
        $stmt_fiyat->close();

    } catch (mysqli_sql_exception $e) {
        echo "<p style='color:red; text-align:center;'>Hata: " . $e->getMessage() . "</p>";
    }

    header("Location: admin_stok.php");
    exit;
}


try {
    $stmt = $baglanti->prepare("CALL STOK_BILGISI(?)");
    $stmt->bind_param("i", $admin_id);
    $stmt->execute();
    $sonuc = $stmt->get_result();
} catch (mysqli_sql_exception $e) {
    echo "<p style='color:red; text-align:center;'>Hata: " . $e->getMessage() . "</p>";
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Stok ve Fiyat Bilgisi</title>
    <style>
        body { font-family: Arial; margin: 20px; }
        table { border-collapse: collapse; width: 90%; margin: auto; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: center; }
        th { background-color: #f4f4f4; }
        input[type="number"] { width: 80px; }
        h2 { text-align: center; }
        .nav-button {
            margin: 10px auto;
            display: block;
            padding: 8px 16px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            text-decoration: none;
            text-align: center;
            width: 120px;
        }
    </style>
</head>
<body>

<a href="admin_anasayfa.php" class="nav-button">Ana Sayfa</a>

<h2>Stok ve Fiyat Güncelleme</h2>

<table>
    <tr>
        <th>Ürün ID</th>
        <th>Ürün Adı</th>
        <th>Stok</th>
        <th>Yeni Stok</th>
        <th>Fiyat (₺)</th>
        <th>Yeni Fiyat</th>
        <th>Güncelle</th>
    </tr>

    <?php while ($satir = $sonuc->fetch_assoc()): ?>
        <tr>
            <form method="POST" action="admin_stok.php">
                <td><?= htmlspecialchars($satir["URUN_ID"]) ?></td>
                <td><?= htmlspecialchars($satir["URUN_ADI"]) ?></td>
                <td><?= htmlspecialchars($satir["STOK_ADETI"]) ?></td>
                <td>
                    <input type="number" name="stok_adeti" min="0" value="<?= htmlspecialchars($satir["STOK_ADETI"]) ?>">
                </td>
                <td><?= htmlspecialchars($satir["FIYAT"]) ?>₺</td>
                <td>
                    <input type="number" name="fiyat" min="0" value="<?= htmlspecialchars($satir["FIYAT"]) ?>">
                    <input type="hidden" name="urun_id" value="<?= $satir["URUN_ID"] ?>">
                </td>
                <td>
                    <button type="submit">Güncelle</button>
                </td>
            </form>
        </tr>
    <?php endwhile; ?>

</table>

</body>
</html>
