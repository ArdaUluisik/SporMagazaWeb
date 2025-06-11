<?php
session_start();
include("baglanti.php");

if (!isset($_SESSION["musteri_id"])) {
    header("Location: giris.php");
    exit;
}

$musteri_id = $_SESSION["musteri_id"];
$mesaj = "";

try {
   
    $stmt = $baglanti->prepare("SELECT SEPET_ID FROM SEPET WHERE MUSTERI_ID = ?");
    $stmt->bind_param("i", $musteri_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        $sepet_id = $row["SEPET_ID"];

       
        $stmt_sil = $baglanti->prepare("CALL SEPET_SIL(?)");
        $stmt_sil->bind_param("i", $sepet_id);
        $stmt_sil->execute();

    
        header("Location: sepet.php");
        exit;
    } else {
        $mesaj = "Sepet bulunamadı.";
    }

} catch (mysqli_sql_exception $e) {
    $mesaj = "Hata: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Sepet Sil</title>
</head>
<body>
    <p style="color:red; text-align:center;"><?= htmlspecialchars($mesaj) ?></p>
</body>
</html>
