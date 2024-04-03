<?php
require_once "config.php";

// Define variables and initialize with empty values
$service_name = $description = $price = "";
$service_name_err = $price_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate service name
    if (empty(trim($_POST["service_name"]))) {
        $service_name_err = "Please enter a service name.";
    } else {
        $service_name = trim($_POST["service_name"]);
    }

    // Validate price
    if (empty(trim($_POST["price"]))) {
        $price_err = "Please enter the price.";
    } elseif (!is_numeric(trim($_POST["price"]))) {
        $price_err = "Price must be a numeric value.";
    } else {
        $price = trim($_POST["price"]);
    }

    // Check input errors before inserting into database
    if (empty($service_name_err) && empty($price_err)) {
        // Prepare an insert statement
        $sql = "INSERT INTO services (service_name, description, price) VALUES (?, ?, ?)";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssd", $param_service_name, $param_description, $param_price);

            // Set parameters
            $param_service_name = $service_name;
            $param_description = $_POST["description"];
            $param_price = $price;

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Redirect to read.php after successful creation
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
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Service</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6">
                <h2>Add Service</h2>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <div class="form-group">
                        <label>Service Name</label>
                        <input type="text" name="service_name" class="form-control <?php echo (!empty($service_name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $service_name; ?>">
                        <span class="invalid-feedback"><?php echo $service_name_err; ?></span>
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="description" class="form-control"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Price</label>
                        <input type="text" name="price" class="form-control <?php echo (!empty($price_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $price; ?>">
                        <span class="invalid-feedback"><?php echo $price_err; ?></span>
                    </div>
                    <input type="submit" class="btn btn-primary" value="Submit">
                    <a href="read.php" class="btn btn-secondary ml-2">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
