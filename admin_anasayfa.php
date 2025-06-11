<?php
session_start();


if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_giris.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Admin Paneli</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .ust-menu {
            background-color: #333;
            overflow: hidden;
        }
        .ust-menu a {
            float: left;
            display: block;
            color: #f2f2f2;
            text-align: center;
            padding: 14px 20px;
            text-decoration: none;
        }
        .ust-menu a:hover {
            background-color: #575757;
        }
        .ust-menu a.sag {
            float: right;
        }
        .icerik {
            padding: 20px;
        }
    </style>
</head>
<body>

<div class="ust-menu">
    <a href="admin_anasayfa.php">Admin Ana Sayfa</a>
    <a href="admin_kategori.php">Kategoriler</a>
    <a href="admin_musteriler.php">Müşteriler</a>
    <a href="admin_siparis.php">Siparişler</a>
    <a href="admin_stok.php">Stoklar</a> <!-- ✅ Yeni eklenen menü -->
    <a href="admin_urun_ekle.php">Ürün Ekle</a> <!-- 👈 BUNU EKLE -->
    <a href="cikis_yap.php" class="sag">Çıkış</a>
</div>

<div class="icerik">
    <h2>Hoşgeldiniz, <?php echo $_SESSION['admin']; ?>!</h2>
    <p>Buradan siteyi yönetebilirsiniz.</p>
</div>

</body>
</html>
