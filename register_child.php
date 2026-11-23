
<?php
include "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $parent_name = $_POST["parent_name"];
    $phone = $_POST["phone"];
    $email = $_POST["email"];
    $parent_address = $_POST["parent_address"];

    $child_name = $_POST["child_name"];
    $dob = $_POST["date_of_birth"];
    $gender = $_POST["gender"];
    $blood_group = $_POST["blood_group"];
    $child_address = $_POST["child_address"];

    mysqli_begin_transaction($conn);

    try {
        // Save parent details
        $stmt = mysqli_prepare($conn,
            "INSERT INTO parents (name, phone, email, address)
             VALUES (?, ?, ?, ?)");

        mysqli_stmt_bind_param(
            $stmt, "ssss",
            $parent_name, $phone, $email, $parent_address
        );

        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        // Get the new parent ID
        $parent_id = mysqli_insert_id($conn);

        // Save child details
        $stmt = mysqli_prepare($conn,
            "INSERT INTO children
            (parent_id, name, date_of_birth, gender, blood_group, address)
            VALUES (?, ?, ?, ?, ?, ?)");

        mysqli_stmt_bind_param(
            $stmt, "isssss",
            $parent_id, $child_name, $dob,
            $gender, $blood_group, $child_address
        );

        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        mysqli_commit($conn);

        echo "Parent and child registered successfully!";

    } catch (mysqli_sql_exception $e) {
        mysqli_rollback($conn);
        echo "Registration failed. Please check the form details and database.";
    }
}
?>
