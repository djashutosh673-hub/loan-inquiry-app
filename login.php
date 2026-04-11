<?php
session_start();

if ($_POST) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($username === "cosmos" && $password === "1234") {
        $_SESSION['admin_logged_in'] = true;
        header("Location: admin.php");
        exit();
    } else {
        echo "<h3 style='color:red;text-align:center;'>Invalid Login</h3>";
    }
}
?>

<form method="POST" style="width:300px;margin:100px auto;">
    <h2>Admin Login</h2>
    <input name="username" placeholder="Username" required><br><br>
    <input name="password" type="password" placeholder="Password" required><br><br>
    <button type="submit">Login</button>
</form>