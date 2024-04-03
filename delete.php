<?php
require_once "config.php";

// Check if id parameter exists
if (isset($_GET["id"]) && !empty(trim($_GET["id"]))) {
    // Prepare a delete statement
    $sql = "DELETE FROM services WHERE id = ?";

    if ($stmt = mysqli_prepare($link, $sql)) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "i", $param_id);

        // Set parameters
        $param_id = trim($_GET["id"]);

        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {
            // Redirect to read.php after deletion
            header("location: read.php");
            exit();
        } else {
            echo "Something went wrong. Please try again later.";
        }
    }

    // Close statement
    mysqli_stmt_close($stmt);
}

// Close connection
mysqli_close($link);
?>
