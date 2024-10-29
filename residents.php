<?php
// Include the config.php file for database connection
require_once('config.php');

function calculateAge($birthdate) {
    // Create a DateTime object for the birthdate
    $dob = new DateTime($birthdate);
    // Get the current date
    $now = new DateTime();
    // Calculate the difference between the current date and the birthdate
    $difference = $now->diff($dob);
    // Return the calculated age
    return $difference->y;
}


if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "DELETE FROM resident WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        header("Location: residents.php");
        exit();
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}

// Fetch data from the resident table
$sql = "SELECT COUNT(*) AS total FROM resident";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$total_records = $row['total'];

$records_per_page = 100; // Number of records to display per page
$total_pages = ceil($total_records / $records_per_page); // Calculate total pages

$current_page = isset($_GET['page']) ? $_GET['page'] : 1; // Get the current page number, default to 1 if not set

// Validate current page value
if ($current_page < 1) {
    $current_page = 1;
} elseif ($current_page > $total_pages && $total_pages > 0) {
    $current_page = $total_pages;
}

// Calculate the starting record for the current page
$start_from = ($current_page - 1) * $records_per_page;

// Fetch data from the resident table with pagination
$sql = "SELECT * FROM resident";
$result = $conn->query($sql);
$sql = "SELECT * FROM resident ORDER BY name";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" href="style.css">
    <title>Residents</title>

    <style>
        table {
            width: 100% !important;
            border-collapse: collapse !important;
            margin-bottom: 20px !important;
        }

        th,
        td {
            border: 1px solid #ddd !important;
            padding: 8px !important;
            text-align: left !important;
        }

        th {
            background-color: #f2f2f2 !important;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9 !important;
        }

        tr:nth-child(odd) {
            background-color: #f9f9f9 !important;
        }

        tr:hover {
            background-color: #f2f2f2 !important;
        }

        .pagination {
            margin-top: 20px;
            text-align: center;
        }

        .pagination a {
            color: #000;
            padding: 8px 16px;
            text-decoration: none;
            transition: background-color .3s;
            border: 1px solid #ddd;
            margin: 0 4px;
        }

        .pagination a.active {
            background-color: #4CAF50;
            color: white;
            border: 1px solid #4CAF50;
        }

        .pagination a:hover:not(.active) {
            background-color: #ddd;
        }

        #addResidentsBtn {
        background-color: #4CAF50;
        color: white;
        border: none;
        border-radius: 5px;
        padding: 10px 20px;
        margin-left: 10px;
        cursor: pointer;
        margin-bottom: 20px;
    }

    /* Hover effect */
    #addResidentsBtn:hover {
        background-color: #45a049;
    }
    
    .sidebar .logo .logo-name{
        margin-left: 10px; /* Add space below the logo */
}

    </style>
</head>

<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <a href="#" class="logo">
            <div class="logo-name"><span>Jasaan</span>Known</div>
        </a>
        <ul class="side-menu">
            <li class="active"><a href="dashboard.php" onclick="redirectTo('dashboard.php')"><i class='bx bxs-dashboard'></i>Dashboard</a></li>
            <li class=""><a href="barangay.php" onclick="redirectTo('barangay.php')"><i class='bx bxs-compass'></i>Barangays</a></li>
            <li class=""><a href="maps.php" onclick="redirectTo('maps.php')"><i class='bx bx-map-alt'></i>Maps</a></li>
            <li class=""><a href="users.php" onclick="redirectTo('users.php')"><i class='bx bx-group'></i>Users</a></li>
            <li class=""><a href="setting.php" onclick="redirectTo('settings.php')"><i class='bx bx-cog'></i>Settings</a></li>
        </ul>
        <ul class="side-menu">
            <li>
                <a href="#" class="logout">
                    <i class='bx bx-log-out-circle'></i>
                    Logout
                </a>
            </li>
        </ul>
    </div>
    <!-- End of Sidebar -->

    <!-- Main Content -->
    <div class="content">
        <!-- Navbar -->
        <nav>
            <i class='bx bx-menu'></i>
            <form action="#">
                <div class="form-input">
                    <input type="search" placeholder="Search...">
                    <button class="search-btn" type="submit"><i class='bx bx-search'></i></button>
                </div>
            </form>
            <input type="checkbox" id="theme-toggle" hidden>
            <label for="theme-toggle" class="theme-toggle"></label>
            <a href="#" class="notif">
                <i class='bx bx-bell'></i>
                <span class="count"></span>
            </a>
            <a href="#" class="profile">
                <img src="images/logo.png">
            </a>
        </nav>
        <!-- End of Navbar -->


        <main>
            <div class="header">
                <div class="left">
                    <h1>Residents</h1>
                    <button id="addResidentsBtn"><a href="registerR.php" style="text-decoration:none; color:inherit;">Add Resident</a></button>
                    <ul class="breadcrumb">
                        <li><a href="#">

                        </a></li>

                    </ul>
                </div>
            </div>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Age</th>
                            <th>Barangay</th>
                            <th>Gender</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                       if ($result->num_rows > 0) {
                        // Output data of each row
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row["name"] . "</td>";
                            echo "<td>" . calculateAge($row["birth_date"]) . "</td>"; // Calculate age using the birth_date column
                            echo "<td>" . $row["address"] . "</td>";
                            echo "<td>" . $row["gender"] . "</td>";
                            // Replace "status" with the correct column name
                            echo "<td>" . $row["status"] . "</td>"; // Assuming the column name is "resident_status"
                            echo "<td>";
                            echo "<a href='editR.php?id=" . $row["id"] . "' class='edit-btn'><i class='bx bx-edit'></i></a>";
                            echo "&nbsp;&nbsp;&nbsp;"; 
                            echo "<a href='?action=delete&id=" . $row["id"] . "' class='delete-btn' onclick='return confirm(\"Are you sure you want to delete this resident?\")'><i class='bx bx-trash'></i></a>";
                            echo "</td>";
                            echo "</tr>";
                            
                        }
                    } else {
                        echo "<tr><td colspan='6'>No residents found</td></tr>";
                    }
                        ?>
                    </tbody>
                </table>
                <div class="pagination">
                    <?php
                    // Pagination links
                    for ($i = 1; $i <= $total_pages; $i++) {
                        echo "<a href='?page=$i'";
                        if ($i == $current_page) echo " class='active'";
                        echo ">$i</a>";
                    }
                    ?>
                </div>
            </div>
        </main>
    </div>

    <script src="index.js"></script>
</body>

</html>