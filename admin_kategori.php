<?php
session_start();
include("baglanti.php");

if (!isset($_SESSION["admin_id"])) {
    header("Location: admin_giris.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Kategori Yönetimi</title>
    <style>
        body { font-family: Arial; margin: 20px; }
        table { border-collapse: collapse; width: 60%; margin-bottom: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: center; }
        form { margin-bottom: 20px; }
        .mesaj { color: green; font-weight: bold; }
    </style>
</head>
<body>

<h2>Kategoriler</h2>


<?php
if (isset($_SESSION["flash"])) {
    echo "<p class='mesaj'>" . $_SESSION["flash"] . "</p>";
    unset($_SESSION["flash"]);
}
?>


<form method="POST" action="admin_kategori.php">
    <input type="text" name="kategori_adi" placeholder="Kategori Adı" required>
    <button type="submit">Ekle</button>
</form>
<a href="admin_anasayfa.php">
    <button>Ana Sayfa</button>
</a>



<?php
$kategoriler = $baglanti->query("SELECT * FROM KATEGORI");
if ($kategoriler && $kategoriler->num_rows > 0) {
    echo "<table>";
    echo "<tr><th>ID</th><th>Kategori Adı</th><th>İşlem</th></tr>";
    while ($k = $kategoriler->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $k["KATEGORI_ID"] . "</td>";
        echo "<td>" . $k["KATEGORI_ADI"] . "</td>";
        echo "<td><a href='admin_kategori_sil.php?id=" . $k["KATEGORI_ID"] . "' onclick=\"return confirm('Silmek istediğine emin misin?');\">Sil</a></td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p>Hiç kategori yok.</p>";
}

?>

</body>
</html>

<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $kategori_adi = trim($_POST["kategori_adi"]);
    $admin_id     = $_SESSION["admin_id"];

    if ($kategori_adi !== "") {
        $baglanti->query("SET @mesaj = ''");
        $stmt = $baglanti->prepare("CALL KATEGORI_EKLE(?, ?, @mesaj)");
        $stmt->bind_param("is", $admin_id, $kategori_adi);
        $stmt->execute();

    
        $result = $baglanti->query("SELECT @mesaj AS mesaj");
        $row = $result->fetch_assoc();
        $_SESSION["flash"] = $row["mesaj"];
    } else {
        $_SESSION["flash"] = "Kategori adı boş olamaz.";
    }


    header("Location: admin_kategori.php");
    exit;
}
?>
