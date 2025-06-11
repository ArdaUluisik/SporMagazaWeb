<?php
session_start();
include("baglanti.php");


if (!isset($_SESSION["admin_id"])) {
    header("Location: admin_giris.php");
    exit;
}

$sorgu = $baglanti->query("CALL ADMIN_SIPARISLER()");
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Tüm Siparişler - Admin Paneli</title>
    <style>
        table {
            width: 90%;
            margin: 20px auto;
            border-collapse: collapse;
        }
        th, td {
            padding: 8px;
            border: 1px solid #ccc;
        }
        th {
            background-color: #eee;
        }
    </style>
</head>
<body>
    <h2 style="text-align:center;">Tüm Siparişler</h2>

    <table>
        <tr>
            <th>Sipariş ID</th>
            <th>Müşteri Adı</th>
            <th>Ürün</th>
            <th>Adet</th>
            <th>Birim Fiyat</th>
            <th>Ürün Toplam</th>
           
            <th>Tarih</th>
        </tr>
        <?php while ($satir = $sorgu->fetch_assoc()): ?>
        <tr>
            <td><?= $satir['SIPARIS_ID'] ?></td>
            <td><?= htmlspecialchars($satir['MUSTERI_ADI'] . ' ' . $satir['MUSTERI_SOYAD']) ?></td>
            <td><?= htmlspecialchars($satir['URUN_ADI']) ?></td>
            <td><?= $satir['ADET'] ?></td>
            <td><?= $satir['FIYAT'] ?>₺</td>
            <td><?= $satir['TOPLAM_URUN_TUTAR'] ?>₺</td>
           
            <td><?= $satir['TARIH'] ?></td>
        </tr>
        <?php endwhile; ?>
    </table>

    <div style="text-align:center;">
        <a href="admin_anasayfa.php">← Admin Paneline Dön</a>
    </div>
</body>
</html>
