<?php
$mode = isset($_GET["mode"]) ? $_GET["mode"] : "register";
$success = "";
$error = "";

$file = "users.json";
if (!file_exists($file)) {
    file_put_contents($file, "[]");
}

$users = json_decode(file_get_contents($file), true);
if (!is_array($users)) {
    $users = [];
}
if ($mode == "register" && $_SERVER["REQUEST_METHOD"] == "POST") {

    $name = $_POST["name"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $hashed = password_hash($password, PASSWORD_DEFAULT);
    $users[] = [
        "name" => $name,
        "email" => $email,
        "password" => $hashed
    ];

    file_put_contents($file, json_encode($users, JSON_PRETTY_PRINT));
    $success = "Registration successful!";
}
if ($mode == "login" && $_SERVER["REQUEST_METHOD"] == "POST") {

    $email = $_POST["email"];
    $password = $_POST["password"];

    $found = false;

    foreach ($users as $user) {
        if ($user["email"] === $email && password_verify($password, $user["password"])) {
            $found = true;
            break;
        }
    }

    if ($found) {
        $success = "Login successful!";
    } else {
        $error = "Incorrect email or password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= ucfirst($mode) ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
    <form class="glass-box" method="POST">

        <h2><?= ucfirst($mode) ?></h2>

        <?php if (!empty($success)) { ?>
            <p class="success"><?= $success ?></p>
        <?php } ?>

        <?php if (!empty($error)) { ?>
            <p class="error"><?= $error ?></p>
        <?php } ?>

        <?php if ($mode == "register") { ?>

            <input type="text" name="name" placeholder="Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>

            <label class="terms">
                <input type="checkbox" required>
                I agree to the Terms & Conditions
            </label>

            <button class="btn">Register</button>

            <p class="login-text">
                Already have an account? <a href="user.php?mode=login">Login</a>
            </p>

        <?php } else { ?>

            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>

            <button class="btn">Login</button>

            <p class="login-text">
                Don’t have an account? <a href="user.php?mode=register">Register</a>
            </p>

        <?php } ?>
    </form>
</div>

</body>
</html>
