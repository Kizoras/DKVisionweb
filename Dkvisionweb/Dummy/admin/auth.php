<?php
session_start();
include "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Gunakan prepared statement untuk keamanan
    $stmt = mysqli_prepare($conn, "SELECT * FROM admin WHERE username=?");
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $data = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    if ($data) {

        $valid = false;

        // cek bcrypt
        if(password_verify($password, $data['password'])){
            $valid = true;
        }

        // fallback md5 (password lama)
        if(!$valid && md5($password) === $data['password']){
            // upgrade otomatis ke bcrypt
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $update = mysqli_prepare($conn, "UPDATE admin SET password=? WHERE username=?");
            mysqli_stmt_bind_param($update, "ss", $hash, $username);
            mysqli_stmt_execute($update);
            $valid = true;
        }

        if($valid){
            $_SESSION['admin'] = $username;
            
            // Tutup session sebelum redirect
            session_write_close();
            
            // redirect dengan PHP header 
            header("Location: dashboard.php");
            exit; // HARUS ADA
        }
    }
    
    // Kalau gagal, redirect balik ke login
    session_write_close();
    header("Location: login.php?error=1");
    exit;
}
?>