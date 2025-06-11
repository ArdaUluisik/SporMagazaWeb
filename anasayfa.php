<!-- ust_menu.php -->
<style>
    .navbar {
        background-color: #333;
        overflow: hidden;
        padding: 10px;
    }

    .navbar a {
        float: left;
        color: white;
        text-align: center;
        padding: 12px 16px;
        text-decoration: none;
        font-size: 16px;
    }

    .navbar a:hover {
        background-color: #ddd;
        color: black;
    }

    .navbar .cikis {
        float: right;
        background-color: crimson;
        border-radius: 4px;
        margin-left: 10px;
    }

    .navbar .hesap {
        float: right;
        background-color: teal;
        border-radius: 4px;
    }
</style>

<div class="navbar">
    <a href="anasayfa.php">Ana Sayfa</a>
    <a href="musteri_kategoriler.php">Kategoriler</a>
    <a href="sepet.php">Sepetim</a>
    <a href="siparislerim.php">Siparişlerim</a>

    <a href="hesabim.php" class="hesap">Hesabım</a>
    <a href="cikis_yap.php" class="cikis">Çıkış</a>
</div>
