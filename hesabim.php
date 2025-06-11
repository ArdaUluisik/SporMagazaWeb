<?php
session_start();
include("baglanti.php");

if (!isset($_SESSION["musteri_id"]) || !isset($_SESSION["eposta"])) {
    header("Location: giris_yap.php");
    exit;
}

$mesaj = "";
$eposta = $_SESSION["eposta"];


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["sil_sifre"])) {
    $sifre = $_POST["sil_sifre"];

    try {
        $stmt = $baglanti->prepare("CALL MUSTERI_SIL(?, ?)");
        $stmt->bind_param("ss", $eposta, $sifre);
        $stmt->execute();

        session_destroy();
        header("Location: kayit.php");
        exit;
    } catch (mysqli_sql_exception $e) {
        $mesaj = "Silme Hatası: " . $e->getMessage();
    }
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["eski_sifre"], $_POST["yeni_sifre"])) {
    $eski = $_POST["eski_sifre"];
    $yeni = $_POST["yeni_sifre"];

    $baglanti->query("SET @sonuc = ''");

    $stmt = $baglanti->prepare("CALL SIFRE_DEGISTIR(?, ?, ?, @sonuc)");
    $stmt->bind_param("sss", $eposta, $eski, $yeni);
    $stmt->execute();
    $stmt->close();

    $res = $baglanti->query("SELECT @sonuc AS sonuc");
    $row = $res->fetch_assoc();
    $mesaj = $row["sonuc"];
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Hesabım</title>
    <style>
        body { font-family: Arial; padding: 20px; text-align: center; }
        form { margin: 20px auto; width: 300px; }
        input[type="password"] {
            padding: 8px;
            width: 100%;
            margin-bottom: 10px;
        }
        button {
            padding: 8px 16px;
            width: 100%;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
        }
        button:hover { background-color: #0056b3; }
        .danger { background-color: red; }
        .danger:hover { background-color: darkred; }
        .message { color: green; margin-top: 15px; }
    </style>
</head>
<body>

<h2>Hesabım</h2>

<?php if ($mesaj): ?>
    <p class="message"><?= htmlspecialchars($mesaj) ?></p>
<?php endif; ?>


<form method="post">
    <h3>Şifre Değiştir</h3>
    <input type="password" name="eski_sifre" placeholder="Eski Şifre" required>
    <input type="password" name="yeni_sifre" placeholder="Yeni Şifre" required>
    <button type="submit">Şifreyi Değiştir</button>
</form>


<form method="post" onsubmit="return confirm('Hesabınızı silmek istediğinize emin misiniz?');">
    <h3>Hesabı Sil</h3>
    <input type="password" name="sil_sifre" placeholder="Şifrenizi Girin" required>
    <button class="danger" type="submit">Hesabımı Sil</button>
</form>

</body>
</html>
