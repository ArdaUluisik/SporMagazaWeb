<?php
session_start();
include("baglanti.php");

if (!isset($_SESSION["eposta"])) {
    header("Location: giris.php");
    exit;
}

$mesaj = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $eposta = $_SESSION["eposta"];
    $eski_sifre = $_POST["eski_sifre"];
    $yeni_sifre = $_POST["yeni_sifre"];

    // OUT parametresi için SQL değişkeni tanımla
    $baglanti->query("SET @sonuc = ''");

    $stmt = $baglanti->prepare("CALL SIFRE_DEGISTIR(?, ?, ?, @sonuc)");
    $stmt->bind_param("sss", $eposta, $eski_sifre, $yeni_sifre);
    $stmt->execute();
    $stmt->close();

    $sonuc = $baglanti->query("SELECT @sonuc AS sonuc")->fetch_assoc();
    $mesaj = $sonuc["sonuc"];
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Şifre Değiştir</title>
</head>
<body>
    <h2>Şifre Değiştir</h2>

    <?php if (!empty($mesaj)): ?>
        <p style="color: <?= $mesaj === 'Şifre güncellendi' ? 'green' : 'red' ?>;">
            <?= htmlspecialchars($mesaj) ?>
        </p>
    <?php endif; ?>

    <form method="post" action="sifre_degistir.php">
        <label>Eski Şifre:</label><br>
        <input type="password" name="eski_sifre" required><br><br>

        <label>Yeni Şifre:</label><br>
        <input type="password" name="yeni_sifre" required><br><br>

        <input type="submit" value="Şifreyi Güncelle">
    </form>
</body>
</html>
