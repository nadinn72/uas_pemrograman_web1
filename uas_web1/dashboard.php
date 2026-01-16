<?php
session_start();

// Redirect ke halaman login jika belum login
if(!isset($_SESSION['user_id'])) {
    header('Location: /auth/login');
    exit();
}

// Redirect ke halaman yang sesuai berdasarkan role
if($_SESSION['role'] == 'admin') {
    header('Location: /films');
} else {
    header('Location: /films');
}
exit();
?>