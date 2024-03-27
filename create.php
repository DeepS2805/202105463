<?php
// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$flight_number = $departure = $destination = $departure_time = "";
$flight_number_err = $departure_err = $destination_err = $departure_time_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate flight number
    if (empty(trim($_POST["flight_number"]))) {
        $flight_number_err = "Please enter a flight number.";
    } else {
        $flight_number = trim($_POST["flight_number"]);
    }

    // Validate departure
    if (empty(trim($_POST["departure"]))) {
        $departure_err = "Please enter a departure location.";
    } else {
        $departure = trim($_POST["departure"]);
    }

    // Validate destination
    if (empty(trim($_POST["destination"]))) {
        $destination_err = "Please enter a destination.";
    } else {
        $destination = trim($_POST["destination"]);
    }

    // Validate departure time
    if (empty(trim($_POST["departure_time"]))) {
        $departure_time_err = "Please enter the departure time.";
    } else {
        $departure_time = trim($_POST["departure_time"]);
    }

    // Check input errors before inserting in database
    if (empty($flight_number_err) && empty($departure_err) && empty($destination_err) && empty($departure_time_err)) {
        // Prepare an insert statement
        $sql = "INSERT INTO flights (flight_number, departure, destination, departure_time) VALUES (?, ?, ?, ?)";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssss", $param_flight_number, $param_departure, $param_destination, $param_departure_time);

            // Set parameters
            $param_flight_number = $flight_number;
            $param_departure = $departure;
            $param_destination = $destination;
            $param_departure_time = $departure_time;

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Records created successfully. Redirect to landing page
                header("location: index.php");
                exit();
            } else {
                echo "Oops! Something went wrong. Please try again later.";
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
    <title>Create Flight Record</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .wrapper {
            width: 600px;
            margin: 0 auto;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="mt-5">Create Flight Record</h2>
                    <p>Please fill this form and submit to add flight record to the database.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group">
                            <label>Flight Number</label>
                            <input type="text" name="flight_number" class="form-control <?php echo (!empty($flight_number_err)) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($flight_number); ?>">
                            <span class="invalid-feedback"><?php echo $flight_number_err; ?></span>
                        </div>
                        <div class="form-group">
                            <label>Departure</label>
                            <input type="text" name="departure" class="form-control <?php echo (!empty($departure_err)) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($departure); ?>">
                            <span class="invalid-feedback"><?php echo $departure_err; ?></span>
                        </div>
                        <div class="form-group">
                            <label>Destination</label>
                            <input type="text" name="destination" class="form-control <?php echo (!empty($destination_err)) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($destination); ?>">
                            <span class="invalid-feedback"><?php echo $destination_err; ?></span>
                        </div>
                        <div class="form-group">
                            <label>Departure Time</label>
                            <input type="text" name="departure_time" class="form-control <?php echo (!empty($departure_time_err)) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($departure_time); ?>">
                            <span class="invalid-feedback"><?php echo $departure_time_err; ?></span>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
