<?php
session_start();
include "db.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    $stmt = mysqli_prepare($conn,
        "SELECT user_id, name, password FROM users WHERE email = ?");

    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);

    if ($user && password_verify($password, $user["password"])) {

        session_regenerate_id(true);

        $_SESSION["user_id"] = $user["user_id"];
        $_SESSION["user_name"] = $user["name"];

        header("Location: dashboard.php");
        exit;

    } else {
        $error = "Invalid email or password.";
    }

    mysqli_stmt_close($stmt);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login - Child Vaccination System</title>
    <style>
        body {
            font-family: Arial;
            background: #eaf3ff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-box {
            background: white;
            padding: 30px;
            width: 300px;
            border-radius: 10px;
            box-shadow: 0 0 10px #ccc;
        }

        h2 {
            text-align: center;
            color: #1769aa;
        }

        input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            box-sizing: border-box;
        }

        button {
            width: 100%;
            padding: 10px;
            background: #1769aa;
            color: white;
            border: none;
            cursor: pointer;
        }

        .error {
            color: red;
            text-align: center;
        }
    </style>
</head>

<body>

<div class="login-box">

    <h2>Login</h2>

    <p class="error"><?php echo htmlspecialchars($error); ?></p>

    <form method="POST">

        <input type="email" name="email"
               placeholder="Enter Email" required>

        <input type="password" name="password"
               placeholder="Enter Password" required>

        <button type="submit">Login</button>

    </form>

</div>

</body>
</html>