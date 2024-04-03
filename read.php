<?php
require_once "config.php";

// Define an empty array to store services
$services = [];

// Attempt select query execution
$sql = "SELECT * FROM services";
if ($result = mysqli_query($link, $sql)) {
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $services[] = $row;
        }
    }
    mysqli_free_result($result);
}

// Close connection
mysqli_close($link);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Services</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                <h2>Services</h2>
                <a href="create.php" class="btn btn-primary mb-3">Add New Service</a>
                <?php if (empty($services)): ?>
                            <div class="alert alert-info" role="alert">
                                No services found.
                            </div>
                <?php else: ?>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Service Name</th>
                                        <th>Description</th>
                                        <th>Price</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($services as $service): ?>
                                                <tr>
                                                    <td><?php echo $service['service_name']; ?></td>
                                                    <td><?php echo $service['description']; ?></td>
                                                    <td><?php echo $service['price']; ?></td>
                                                    <td>
                                                        <a href="update.php?id=<?php echo $service['id']; ?>" class="btn btn-info btn-sm">Update</a>
                                                        <a href="delete.php?id=<?php echo $service['id']; ?>" class="btn btn-danger btn-sm">Delete</a>
                                                    </td>
                                                </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
