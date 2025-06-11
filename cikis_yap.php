<?php
session_start(); // Oturumu başlat

// 1. Oturum verilerini temizle
$_SESSION = array();

// 2. Oturumu tamamen sonlandır
session_destroy();

// 3. İsteğe bağlı: Oturum çerezini de temizlemek istersen (genellikle gerekmez)
// if (ini_get("session.use_cookies")) {
//     $params = session_get_cookie_params();
//     setcookie(session_name(), '', time() - 42000,
//         $params["path"], $params["domain"],
//         $params["secure"], $params["httponly"]
//     );
// }

// 4. Kullanıcıyı "kayit_ol.php" sayfasına yönlendir
header("Location: kayit.php");
exit;
