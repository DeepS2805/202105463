<?php
// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$flight_number = $departure = $destination = $departure_time = "";
$flight_number_err = $departure_err = $destination_err = $departure_time_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate flight number
    $input_flight_number = trim($_POST["flight_number"]);
    if (empty($input_flight_number)) {
        $flight_number_err = "Please enter a flight number.";
    } else {
        $flight_number = $input_flight_number;
    }

    // Validate departure
    $input_departure = trim($_POST["departure"]);
    if (empty($input_departure)) {
        $departure_err = "Please enter a departure.";
    } else {
        $departure = $input_departure;
    }

    // Validate destination
    $input_destination = trim($_POST["destination"]);
    if (empty($input_destination)) {
        $destination_err = "Please enter a destination.";
    } else {
        $destination = $input_destination;
    }

    // Validate departure time
    $input_departure_time = trim($_POST["departure_time"]);
    if (empty($input_departure_time)) {
        $departure_time_err = "Please enter a departure time.";
    } else {
        $departure_time = $input_departure_time;
    }

    // Check input errors before updating the database
    if (empty($flight_number_err) && empty($departure_err) && empty($destination_err) && empty($departure_time_err)) {
        // Prepare an update statement
        $sql = "UPDATE flights SET flight_number=?, departure=?, destination=?, departure_time=? WHERE id=?";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssssi", $param_flight_number, $param_departure, $param_destination, $param_departure_time, $param_id);

            // Set parameters
            $param_flight_number = $flight_number;
            $param_departure = $departure;
            $param_destination = $destination;
            $param_departure_time = $departure_time;
            $param_id = $_POST["id"];

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Records updated successfully. Redirect to landing page
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
} else {
    // Check existence of id parameter before processing further
    if (isset($_GET["id"]) && !empty(trim($_GET["id"]))) {
        // Get URL parameter
        $id =  trim($_GET["id"]);

        // Prepare a select statement
        $sql = "SELECT * FROM flights WHERE id = ?";
        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $param_id);

            // Set parameters
            $param_id = $id;

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                $result = mysqli_stmt_get_result($stmt);

                if (mysqli_num_rows($result) == 1) {
                    /* Fetch result row as an associative array. Since the result set
                    contains only one row, we don't need to use while loop */
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

                    // Retrieve individual field value
                    $flight_number = $row["flight_number"];
                    $departure = $row["departure"];
                    $destination = $row["destination"];
                    $departure_time = $row["departure_time"];
                } else {
                    // URL doesn't contain valid id. Redirect to error page
                    header("location: error.php");
                    exit();
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
        }

        // Close statement
        mysqli_stmt_close($stmt);

        // Close connection
        mysqli_close($link);
    } else {
        // URL doesn't contain id parameter. Redirect to error page
        header("location: error.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Update Record</title>
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
                    <h2 class="mt-5">Update Record</h2>
                    <p>Please edit the input values and submit to update the flight record.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group">
                            <label>Flight Number</label>
                            <input type="text" name="flight_number" class="form-control <?php echo (!empty($flight_number_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $flight_number; ?>">
                            <span class="invalid-feedback"><?php echo $flight_number_err; ?></span>
                        </div>
                        <div class="form-group">
                            <label>Departure</label>
                            <input type="text" name="departure" class="form-control <?php echo (!empty($departure_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $departure; ?>">
                            <span class="invalid-feedback"><?php echo $departure_err; ?></span>
                        </div>
                        <div class="form-group">
                            <label>Destination</label>
                            <input type="text" name="destination" class="form-control <?php echo (!empty($destination_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $destination; ?>">
                            <span class="invalid-feedback"><?php echo $destination_err; ?></span>
                        </div>
                        <div class="form-group">
                            <label>Departure Time</label>
                            <input type="text" name="departure_time" class="form-control <?php echo (!empty($departure_time_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $departure_time; ?>">
                            <span class="invalid-feedback"><?php echo $departure_time_err; ?></span>
                        </div>
                        <input type="hidden" name="id" value="<?php echo $id; ?>" />
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>