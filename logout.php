<?php
session_start();         // ضروري باش نحذف session
session_unset();         // حذف كل بيانات session
session_destroy();       // إغلاق الجلسة كلياً
header("Location: login.php"); // توجيه للمستخدم
exit;
