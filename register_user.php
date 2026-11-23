<?php
include "db.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    if (strlen($password) < 8) {
        $message = "Password must contain at least 8 characters.";
    } else {

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = mysqli_prepare($conn,
            "INSERT INTO users (name, email, password)
             VALUES (?, ?, ?)");

        mysqli_stmt_bind_param(
            $stmt, "sss",
            $name, $email, $hashedPassword
        );

        try {
            if (mysqli_stmt_execute($stmt)) {
                $message = "Account created successfully!";
            }
        } catch (mysqli_sql_exception $e) {
            $message = "This email may already be registered.";
        }

        mysqli_stmt_close($stmt);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create Account</title>
</head>
<body>

<h2>Create Login Account</h2>

<p><?php echo htmlspecialchars($message); ?></p>

<form method="POST">

    <label>Name</label><br>
    <input type="text" name="name" required><br><br>

    <label>Email</label><br>
    <input type="email" name="email" required><br><br>

    <label>Password (minimum 8 characters)</label><br>
    <input type="password" name="password" required minlength="8"><br><br>

    <button type="submit">Create Account</button>

</form>

</body>
</html>