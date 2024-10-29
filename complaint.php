<?php
// Include database configuration
include 'config.php';

// Fetch filter values if set
$barangay_filter = isset($_GET['barangay']) ? $_GET['barangay'] : '';
$type_filter = isset($_GET['type']) ? $_GET['type'] : '';
$date_filter = isset($_GET['date']) ? $_GET['date'] : '';
$status_filter = isset($_GET['status']) ? $_GET['status'] : '';

// Prepare SQL query with filters
$query = "SELECT * FROM complaints WHERE 1=1";
if ($barangay_filter) {
    $query .= " AND barangay = '" . $conn->real_escape_string($barangay_filter) . "'";
}
if ($type_filter) {
    $query .= " AND type = '" . $conn->real_escape_string($type_filter) . "'";
}
if ($date_filter) {
    $query .= " AND date = '" . $conn->real_escape_string($date_filter) . "'";
}
if ($status_filter) {
    $query .= " AND status = '" . $conn->real_escape_string($status_filter) . "'";
}
$query .= " ORDER BY date DESC";

$result = $conn->query($query);

// Fetch distinct barangay and type values for filter dropdowns
$barangay_options = $conn->query("SELECT DISTINCT barangay FROM complaints");
$type_options = $conn->query("SELECT DISTINCT type FROM complaints");
$status_options = ['', 'Under Observation', 'Actioned', 'Unverified']; // Status options including empty value

// Handle status update
if (isset($_POST['update_status'])) {
    $status = $_POST['status'];
    $complaint_id = $_POST['complaint_id'];

    // Debugging output
    error_log("Updating status to: $status for complaint ID: $complaint_id");

    $stmt = $conn->prepare("UPDATE complaints SET status = ? WHERE id = ?");
    $stmt->bind_param('si', $status, $complaint_id);

    if ($stmt->execute()) {
        echo '<script>alert("Status updated successfully.");</script>';
        // Refresh the page to reflect the updated status
        echo '<script>window.location.href = window.location.href;</script>';
    } else {
        echo "Error updating status: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complaint Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Poppins', sans-serif;
        }
        .card {
            margin-bottom: 1rem;
        }
        .card-title {
            font-size: 1rem;
        }
        .card-text {
            font-size: 0.875rem;
        }
        .badge {
            font-size: 0.75rem;
        }
        
    </style>
</head>
<body>
<div class="container mt-5">
    <h1 class="mb-4">Complaint Dashboard</h1>
    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Filters</h5>
                    <form method="GET">
                        <div class="form-group">
                            <label for="barangay">Barangay</label>
                            <select class="form-control" id="barangay" name="barangay">
                                <option value="">Any</option>
                                <?php while ($row = $barangay_options->fetch_assoc()) { ?>
                                    <option value="<?php echo htmlspecialchars($row['barangay']); ?>" <?php echo ($row['barangay'] == $barangay_filter) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($row['barangay']); ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="type">Type</label>
                            <select class="form-control" id="type" name="type">
                                <option value="">Any</option>
                                <?php while ($row = $type_options->fetch_assoc()) { ?>
                                    <option value="<?php echo htmlspecialchars($row['type']); ?>" <?php echo ($row['type'] == $type_filter) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($row['type']); ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="date">Date</label>
                            <input type="date" class="form-control" id="date" name="date" value="<?php echo htmlspecialchars($date_filter); ?>">
                        </div>
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select class="form-control" id="status" name="status">
                                <option value="">Any</option>
                                <?php foreach ($status_options as $status_option) { ?>
                                    <option value="<?php echo htmlspecialchars($status_option); ?>" <?php echo ($status_option == $status_filter) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($status_option); ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Apply Filters</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="row">
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($row['title']); ?></h5>
                                <p class="card-text"><?php echo htmlspecialchars($row['complaint']); ?></p>
                                <span class="badge badge-info"><?php echo htmlspecialchars($row['type']); ?></span>
                                <span class="badge badge-secondary"><?php echo htmlspecialchars($row['date']); ?></span>
                                <span class="badge badge-success"><?php echo htmlspecialchars($row['barangay']); ?></span>
                                <span class="badge badge-warning"><?php echo htmlspecialchars($row['status']); ?></span>
                                <a href="<?php echo htmlspecialchars($row['file']); ?>" class="badge badge-primary">File</a>
                                <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#statusModal<?php echo $row['id']; ?>" style="font-size: 0.75rem; padding: 0.15rem 0.3rem;">Change Status</a>

                                <!-- Status Update Form -->
                                <div class="modal fade" id="statusModal<?php echo $row['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="statusModalLabel<?php echo $row['id']; ?>" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="statusModalLabel<?php echo $row['id']; ?>">Update Status</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form method="POST">
                                                    <div class="form-group">
                                                        <label for="status">Status</label>
                                                        <select class="form-control" id="status" name="status">
                                                            <?php foreach ($status_options as $status_option) { ?>
                                                                <option value="<?php echo $status_option; ?>" <?php echo ($status_option == $row['status']) ? 'selected' : ''; ?>>
                                                                    <?php echo $status_option; ?>
                                                                </option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                    <input type="hidden" name="complaint_id" value="<?php echo $row['id']; ?>">
                                                    <button type="submit" class="btn btn-success" name="update_status">Update Status</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
