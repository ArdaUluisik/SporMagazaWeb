<?php
session_start();
include("baglanti.php");

$hata = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $eposta = $_POST["eposta"];
    $sifre = $_POST["sifre"];

    
    $baglanti->query("SET @mesaj = ''");
    $baglanti->query("SET @musteri_id = NULL");

    
    $stmt = $baglanti->prepare("CALL GIRIS_YAP(?, ?, @mesaj, @musteri_id)");
    $stmt->bind_param("ss", $eposta, $sifre);
    $stmt->execute();
    $stmt->close();

    
    $sonuc = $baglanti->query("SELECT @mesaj AS mesaj, @musteri_id AS musteri_id");
    $row = $sonuc->fetch_assoc();

    if ($row["mesaj"] == "Giriş Başarılı" && !is_null($row["musteri_id"])) {
        
        $_SESSION["eposta"] = $eposta;
        $_SESSION["musteri_id"] = $row["musteri_id"];
    
        
        header("Location: anasayfa.php");
        exit;
    } else {
        $hata = "Hatalı e-posta veya şifre!";
    }
}

?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Giriş Yap</title>
</head>
<body>
    <h2>Müşteri Giriş</h2>

    <?php if ($hata): ?>
        <p style="color:red;"><?= htmlspecialchars($hata) ?></p>
    <?php endif; ?>

    <form action="giris_yap.php" method="post">
        <label>Eposta:</label>
        <input type="text" name="eposta" required><br><br>

        <label>Şifre:</label>
        <input type="password" name="sifre" required><br><br>

        <input type="submit" value="Giriş Yap">
    </form>
</body>
</html>
