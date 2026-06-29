<?php

declare(strict_types=1);

$db_server = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "etagere";
$conn = "";

try {
    $conn = mysqli_connect($db_server, $db_user, $db_pass, $db_name);

    if ($conn === false) {
        $conn = null;
    }
} catch (mysqli_sql_exception) {
    $conn = null;
}

/*
 * This function retrieves a user from the database by their email.
 * It returns an associative array representing the user if found, or null if not found.
 * Throws a RuntimeException if the query fails.
 */
function get_user(mysqli $conn, string $email): ?array
{
    $query = "SELECT * FROM users WHERE email = ? LIMIT 1";

    $stmt = mysqli_prepare($conn, $query);
    if ($stmt === false) {
        throw new RuntimeException("Failed to prepare statement");
    }

    try {
        if (mysqli_stmt_bind_param($stmt, "s", $email) === false) {
            throw new RuntimeException("Failed to bind parameters");
        }

        if (mysqli_stmt_execute($stmt) === false) {
            throw new RuntimeException("Failed to execute statement");
        }

        $result = mysqli_stmt_get_result($stmt);
        if ($result === false) {
            throw new RuntimeException("Failed to get result");
        }

        $row = mysqli_fetch_assoc($result);
        if ($row === false) {
            return null;
        }
        return $row;
    } finally {
        mysqli_stmt_close($stmt);
    }
}
