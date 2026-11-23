<?php
include "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $child_id = $_POST["child_id"];
    $vaccine_id = $_POST["vaccine_id"];
    $centre_id = $_POST["centre_id"];
    $vaccination_date = $_POST["vaccination_date"];
    $next_due_date = $_POST["next_due_date"];
    $status = $_POST["status"];

    $stmt = mysqli_prepare($conn,
        "INSERT INTO vaccination_records
        (child_id, vaccine_id, centre_id,
         vaccination_date, next_due_date, status)
        VALUES (?, ?, ?, ?, ?, ?)");

    mysqli_stmt_bind_param(
        $stmt, "iiisss",
        $child_id, $vaccine_id, $centre_id,
        $vaccination_date, $next_due_date, $status
    );

    if (mysqli_stmt_execute($stmt)) {
        echo "Vaccination record added successfully!";
    } else {
        echo "Unable to add vaccination record.";
    }

    mysqli_stmt_close($stmt);
}
?>