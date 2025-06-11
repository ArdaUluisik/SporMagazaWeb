<?php
session_start();
include("baglanti.php");

if (!isset($_SESSION["musteri_id"])) {
    header("Location: giris.php");
    exit;
}

$musteri_id = $_SESSION["musteri_id"];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["urun_id"], $_POST["adet"])) {
    $urun_id = (int)$_POST["urun_id"];
    $adet = (int)$_POST["adet"];

    // Müşteriye ait sepet var mı kontrol et, yoksa oluştur
    $stmt = $baglanti->prepare("SELECT SEPET_ID FROM SEPET WHERE MUSTERI_ID = ?");
    $stmt->bind_param("i", $musteri_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $sepet_id = $row["SEPET_ID"];
    } else {
        $stmt_insert = $baglanti->prepare("INSERT INTO SEPET (MUSTERI_ID) VALUES (?)");
        $stmt_insert->bind_param("i", $musteri_id);
        $stmt_insert->execute();
        $sepet_id = $stmt_insert->insert_id;
    }

    // Sepete ürün ekle
    try {
        $stmt = $baglanti->prepare("CALL SEPET_EKLE(?, ?, ?)");
        $stmt->bind_param("iii", $sepet_id, $urun_id, $adet);
        $stmt->execute();
        header("Location: sepet.php");
        exit;
    } catch (mysqli_sql_exception $e) {
        echo "<p style='color:red;'>Hata: " . $e->getMessage() . "</p>";
    }
} else {
    echo "<p style='color:red;'>Eksik veri gönderildi.</p>";
}
?>
