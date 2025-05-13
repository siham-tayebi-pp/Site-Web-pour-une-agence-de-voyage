<?php
$host = 'localhost';
$user = 'root';           // ما كاين حتى باسوورد فـ XAMPP
$pass = '';
$db = 'agence_db';        // اسم قاعدة البيانات لي خلقناها

$conn = new mysqli($host, $user, $pass, $db);

// اختبار الاتصال
if ($conn->connect_error) {
    die("Échec de connexion : " . $conn->connect_error);
}

// echo "Connexion réussie"; // يمكن تستعملها للاختبار
?>
