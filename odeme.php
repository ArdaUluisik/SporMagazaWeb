<?php



$mesaj = "";


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['odeme_turu'])) {
    $odeme_turu = $_POST['odeme_turu'];

    
    $gecerli_turler = ['KREDI KARTI', 'KAPIDA ODEME'];
    if (!in_array($odeme_turu, $gecerli_turler)) {
        $mesaj = "❌ Geçersiz ödeme türü.";
    } else {
       
        $sql = "CALL ODEME_EKLE(?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $odeme_turu);

        if ($stmt->execute()) {
            $mesaj = "✅ Ödeme başarıyla eklendi.";
        } else {
            $mesaj = "❌ Hata: " . $stmt->error;
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Ödeme Türü Ekle</title>
</head>
<body>
    <h2>Ödeme Türü Ekle</h2>

    <?php if (!empty($mesaj)) echo "<p><strong>$mesaj</strong></p>"; ?>

    <form action="" method="POST">
        <label for="odeme_turu">Ödeme Türü:</label>
        <select name="odeme_turu" id="odeme_turu" required>
            <option value="">Seçiniz</option>
            <option value="KREDI KARTI">Kredi Kartı</option>
            <option value="KAPIDA ODEME">Kapıda Ödeme</option>
        </select>
        <br><br>
        <input type="submit" value="Ekle">
    </form>
</body>
</html>
