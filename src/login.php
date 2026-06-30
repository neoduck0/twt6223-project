<?php

declare(strict_types=1);

require_once "inc/db.php";
require_once "inc/helpers.php";

session_start();

if ($_SERVER["REQUEST_METHOD"] === "GET") {
    goto render_page;
}

$error = null;

$email = $_POST["email"];
$password = $_POST["password"];
$csrf_token = $_POST["csrf_token"];

if (empty($email) || empty($password) || empty($csrf_token)) {
    $error = "Email and password are required";
    goto render_page;
}

if (!verify_csrf_token($csrf_token)) {
    $error = "Invalid form submission";
    goto render_page;
}

try {
    $user = get_user($email);
} catch (RuntimeException $e) {
    $error = "Internal server error";
    error_log($e->getMessage());
    goto render_page;
}

if ($user === null) {
    $error = "Invalid email or password";
    goto render_page;
}

if (password_verify($password, $user["password_hash"]) === false) {
    $error = "Invalid email or password";
    goto render_page;
}

session_regenerate_id();
$_SESSION["user_id"] = $user["id"];
$_SESSION["email"] = $user["email"];
$_SESSION["first_name"] = $user["first_name"];
$_SESSION["last_name"] = $user["last_name"];
$_SESSION["is_admin"] = $user["is_admin"];

header("Location: index.php");
exit();

render_page:
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Etagere: Login</title>
        <link rel="stylesheet" href="css/common.css">
        <link rel="stylesheet" href="css/login.css">
    </head>
    <body class="login-page">
        <main class="login-shell">
            <section class="login-card">
                <h1 id="login-heading">Welcome back</h1>
                <p class="login-subtitle">Log in to continue to Etagere.</p>

                <?php if (isset($error) && $error !== null): ?>
                <p class="login-error" role="alert">
                    <?= htmlspecialchars($error) ?>
                </p>
                <?php endif; ?>

                <form method="post" class="login-form">
                    <input type="hidden" name="csrf_token" value="<?= generate_csrf_token() ?>">

                    <label class="login-label" for="email">Email</label>
                    <input id="email" placeholder="Enter your email" type="email" name="email" required>

                    <label class="login-label" for="password">Password</label>
                    <input id="password" placeholder="Enter your password" type="password" name="password" required>

                    <button type="submit">Log in</button>
                </form>

                <p class="login-footer">
                    New here? <a href="register.php">Create an account</a>
                </p>
            </section>
        </main>
        <script src="https://cdn.jsdelivr.net/npm/jquery@4.0.0/dist/jquery.min.js"></script>
        <script src="js/login.js"></script>
    </body>
</html>
