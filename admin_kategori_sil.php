<?php
session_start();
include("baglanti.php");


if (!isset($_SESSION["admin_id"])) {
    header("Location: admin_giris.php");
    exit;
}


if (!isset($_GET['id']) || !ctype_digit($_GET['id'])) {
    
    header("Location: admin_kategori.php");
    exit;
}

$kategori_id = (int) $_GET['id'];
$admin_id    = $_SESSION["admin_id"];


$baglanti->query("SET @mesaj = ''");


$stmt = $baglanti->prepare("CALL KATEGORI_SIL(?, ?, @mesaj)");
$stmt->bind_param("ii", $kategori_id, $admin_id);
$stmt->execute();


do {
    if ($r = $stmt->get_result()) {
        $r->free();
    }
} while ($stmt->more_results() && $stmt->next_result());

$stmt->close();


$res   = $baglanti->query("SELECT @mesaj AS mesaj");
$row   = $res->fetch_assoc();
$mesaj = $row["mesaj"];


echo "<p style='font-family:Arial; text-align:center;'>$mesaj</p>";


header("Refresh:2; url=admin_kategori.php");
exit;
