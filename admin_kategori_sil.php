<?php
session_start();
include("baglanti.php");

// Admin kontrolü
if (!isset($_SESSION["admin_id"])) {
    header("Location: admin_giris.php");
    exit;
}

// GET id kontrolü
if (!isset($_GET['id']) || !ctype_digit($_GET['id'])) {
    // Geçersiz istek → yönlendir
    header("Location: admin_kategori.php");
    exit;
}

$kategori_id = (int) $_GET['id'];
$admin_id    = $_SESSION["admin_id"];

// OUT değişkenini sıfırla
$baglanti->query("SET @mesaj = ''");

// Prosedürü doğru parametre sırasıyla çağır
$stmt = $baglanti->prepare("CALL KATEGORI_SIL(?, ?, @mesaj)");
$stmt->bind_param("ii", $kategori_id, $admin_id);
$stmt->execute();

// Sonuç kümesini temizle (out param için)
do {
    if ($r = $stmt->get_result()) {
        $r->free();
    }
} while ($stmt->more_results() && $stmt->next_result());

$stmt->close();

// OUT mesajını al
$res   = $baglanti->query("SELECT @mesaj AS mesaj");
$row   = $res->fetch_assoc();
$mesaj = $row["mesaj"];

// Kullanıcıya bilgi ver
echo "<p style='font-family:Arial; text-align:center;'>$mesaj</p>";

// Birkaç saniye sonra geri dön
header("Refresh:2; url=admin_kategori.php");
exit;
