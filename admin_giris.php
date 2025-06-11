<?php
session_start();
include("baglanti.php");

$hata = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $eposta = $_POST["eposta"];
    $sifre = $_POST["sifre"];

    $baglanti->query("SET @mesaj = ''");

    $stmt = $baglanti->prepare("CALL ADMIN_GIRIS(?, ?, @mesaj)");
    $stmt->bind_param("ss", $eposta, $sifre);
    $stmt->execute();

    // Prosedür sonucu tüketilmeli
    do {
        if ($sonuc = $stmt->get_result()) {
            while ($satir = $sonuc->fetch_assoc()) {
                // varsa işlenir
            }
            $sonuc->free();
        }
    } while ($stmt->more_results() && $stmt->next_result());

    $stmt->close();

    // OUT parametreyi oku
    $sonuc = $baglanti->query("SELECT @mesaj AS mesaj");
    $row = $sonuc->fetch_assoc();
    $mesaj = $row["mesaj"];

    if ($mesaj === "ADMIN GIRISI BASARILI") {
        $_SESSION["admin"] = $eposta;

        $id_sorgu = $baglanti->prepare("SELECT ADMIN_ID FROM ADMINLER WHERE EPOSTA = ?");
        $id_sorgu->bind_param("s", $eposta);
        $id_sorgu->execute();
        $id_sonuc = $id_sorgu->get_result();
        $id_row = $id_sonuc->fetch_assoc();

        $_SESSION["admin_id"] = $id_row["ADMIN_ID"];

        header("Location: admin_anasayfa.php");
        exit;
    } else {
        $hata = "Giriş başarısız! Bilgilerinizi kontrol edin.";
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Admin Giriş</title>
</head>
<body>
    <h2>Admin Giriş</h2>

    <?php if (!empty($hata)) { echo "<p style='color: red;'>$hata</p>"; } ?>

    <form method="POST" action="">
        <label for="eposta">E-posta:</label><br>
        <input type="email" id="eposta" name="eposta" required><br><br>

        <label for="sifre">Şifre:</label><br>
        <input type="password" id="sifre" name="sifre" required><br><br>

        <input type="submit" value="Giriş Yap">
    </form>
</body>
</html>
