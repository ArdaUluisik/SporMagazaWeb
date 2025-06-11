<a href="admin_anasayfa.php">
    <button>Ana Sayfa</button>
</a>
<?php
session_start();
include("baglanti.php");


if (!isset($_SESSION["admin_id"])) {
    echo "Bu sayfayı görüntülemek için giriş yapmalısınız.";
    exit;
}

$admin_id = $_SESSION["admin_id"];


$stmt = $baglanti->prepare("CALL BUTUN_MUSTERILER(?)");
$stmt->bind_param("i", $admin_id);

try {
    if ($stmt->execute()) {
        $sonuc = $stmt->get_result();

        echo "<h2>Bütün Müşteriler</h2>";
        echo "<table border='1' cellpadding='5' cellspacing='0'>";
        echo "<tr><th>ID</th><th>Adı</th><th>Soyadı</th><th>Telefon</th><th>Mail</th><th>Adres</th></tr>";

        while ($row = $sonuc->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["ID"] . "</td>";
            echo "<td>" . $row["Adı"] . "</td>";
            echo "<td>" . $row["Soyadı"] . "</td>";
            echo "<td>" . $row["Telefon"] . "</td>";
            echo "<td>" . $row["Mail"] . "</td>";
            echo "<td>" . $row["Adres"] . "</td>";
            echo "</tr>";
        }
        

        echo "</table>";
    } else {
        echo "Hata oluştu: " . $baglanti->error;
    }
} catch (mysqli_sql_exception $e) {
    echo "Hata: " . $e->getMessage();
}

$stmt->close();
$baglanti->close();
?>
