<?php
// Include config.php for database connection
include 'config.php';

// Query to fetch activities data
$sql = "SELECT * FROM activities";
$result = $conn->query($sql);

// Function to sanitize user input
function sanitizeInput($input) {
    return htmlspecialchars(trim($input));
}

// Handle delete action
if(isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id = $_GET['id'];
    // Prepare and execute delete query
    $delete_sql = "DELETE FROM activities WHERE id=?";
    $delete_stmt = $conn->prepare($delete_sql);
    $delete_stmt->bind_param("i", $id);
    if($delete_stmt->execute()) {
        // Redirect back to this page after deletion
        header("Location: {$_SERVER['PHP_SELF']}");
        exit();
    } else {
        echo "<p style='color: red;'>Error deleting activity: " . $conn->error . "</p>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" href="style.css">
    <title>Registered Activities</title>
</head>
<style>
      .activity-container {
            display: flex;
            flex-direction: column; /* Align activities from top to bottom */
            overflow-y: auto; /* Enable vertical scrolling */
            padding: 20px;
            gap: 20px;
        }

        .activity {
            border-radius: 10px;
            overflow: hidden;
            min-width: 1200px;
            max-width: 1200px;
            display: flex;
            flex-direction: column;
            position: relative; /* Added for positioning comment form */
        }

        .activity img {
            width: 100%;
            height: 500px;
        }

        .activity-details {
            padding: 15px;
        }

        .activity-details h2 {
            margin: 0 0 10px;
        }

        .activity-description, .activity-date {
            margin: 5px 0;
        }

        .play-button {
            background-color: #6a0dad;
            color: #fff;
            border: none;
            padding: 10px;
            text-align: center;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
        }

        .play-button:hover {
            background-color: #8a2be2;
        }

        .comments-container {
            padding: 10px;
            border-radius: 0 0 10px 10px;
            margin-top: auto; /* Push comments to the bottom */
        }

        .comment {
            color: black;
            font-size: 14px;
            margin-bottom: 5px;
            display: flex;
            align-items: center;
        }

        .comment .profile-pic {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            margin-right: 10px;
        }

        .comment-text {
            flex: 1;
        }

        .comment-form {
            padding: 10px;
            border-radius: 0 0 10px 10px;
            margin-top: auto;
        }

        .comment-form textarea {
            width: calc(100% - 20px);
            padding: 8px;
            margin-bottom: 8px;
            border: none;
            border-radius: 5px;
            resize: none;
        }

        .comment-form button {
            background-color: #6a0dad;
            color: #fff;
            border: none;
            padding: 10px;
            text-align: center;
            border-radius: 5px;
            cursor: pointer;
        }

        .comment-form button:hover {
            background-color: #8a2be2;
        }

    .bottom-data {
        margin-top: 20px;
        padding: 20px;
        background-color: #f9f9f9;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        display: flex;
        flex-wrap: wrap;
    }

    #addActivityBtn {
        background-color: #4CAF50;
        color: white;
        border: none;
        border-radius: 5px;
        padding: 10px 20px;
        margin-left: 10px;
        cursor: pointer;
    }

    /* Hover effect */
    #addActivityBtn:hover {
        background-color: #45a049;
    }

/* Styling for the buttons container */
.action-buttons {
        margin-top: 10px; /* Adjust margin as needed */
        padding-top: 10px; /* Add some space between description and buttons */
        border-top: 1px solid #ccc; /* Add a border above buttons */
        display: flex;
        justify-content: flex-end; /* Align buttons to the right */
    }

    .action-buttons a {
        margin-left: 10px; /* Adjust margin between buttons as needed */
        text-decoration: none;
        color: inherit;
        font-size: 24px; /* Adjust font size as needed */
    }

    .action-buttons a:hover {
        color: #4CAF50; /* Change color on hover */
    }

    .sidebar .logo .logo-name{
        margin-left: 10px; /* Add space below the logo */
}
</style>

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
            <li class=""><a href="#" onclick="redirectTo('users.php')"><i class='bx bx-group'></i>Users</a></li>
            <li class=""><a href="#" onclick="redirectTo('settings.php')"><i class='bx bx-cog'></i>Settings</a></li>
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
                    <h1>Activities</h1>
                    <button id="addActivityBtn"><a href="activities.php" style="text-decoration:none; color:inherit;">Manage Activities</a></button>
                    <ul class="breadcrumb">
                        <li><a href="#"></a></li>
                    </ul>
                </div>
            </div>
        <div class="activity-container">
        <?php
        // Database connection
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "jk";

        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Fetch activities from database
        $sql = "SELECT id, actname, barangay, date, picture, description FROM activities";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Output data of each row
            while($row = $result->fetch_assoc()) {
                echo '<div class="activity">';
                echo '<img src="' . $row["picture"] . '" alt="Activity Image">';
                echo '<div class="activity-details">';
                echo '<h2>' . $row["actname"] . '</h2>';
                echo '<p class="activity-barangayname">' . $row["barangay"] . '</p>';
                echo '<p class="activity-description">' . $row["description"] . '</p>';
                echo '<p class="activity-date">' . $row["date"] . '</p>';
                echo '</div>';
                // Add comments container
                echo '<div class="comments-container">';
                // Fetch and display comments for each activity
                $activity_id = $row["id"]; // Assuming 'id' is the column name for the activity ID
                $comments_sql = "SELECT * FROM comments WHERE activity_id = $activity_id";
                $comments_result = $conn->query($comments_sql);
                if ($comments_result->num_rows > 0) {
                    while ($comment_row = $comments_result->fetch_assoc()) {
                        echo '<div class="comment">';
                        echo '<img class="profile-pic" src="images/default-avatar.png" alt="Profile Picture">';
                        echo '<div class="comment-text">' . $comment_row["comment"] . '</div>';
                        echo '</div>';
                    }
                } else {
                    echo "No comments yet.";
                }
                echo '</div>';
                // Comment form
                echo '<form class="comment-form" method="post" action="submit_comment.php">';
                echo '<input type="hidden" name="activity_id" value="' . $activity_id . '">';
                echo '<textarea name="comment" placeholder="Add your comment"></textarea>';
                echo '<button type="submit">Submit</button>';
                echo '</form>';
                echo '</div>';
            }
        } else {
            echo "0 results";
        }
        $conn->close();
        ?>
    </div>

</div>
<main>
    <!-- Your other HTML content here -->
</main>
<script src="index.js">

        const activities = [
            {
                name: 'Mission: Yozakura Family',
                description: 'High school student Taiyou Asano has been socially inept ever since his family died in a car crash. The only person he can properly interact with is his childhood friend, Mutsumi Yozakura—the head of...',
                date: 'Apr 07, 2024',
                img: /path/to/images/shaiwo.jpg
            },
            // Add more activities here
        ];

        const container = document.querySelector('.activity-container');

        activities.forEach(activity => {
            const activityDiv = document.createElement('div');
            activityDiv.classList.add('activity');

            activityDiv.innerHTML = `
                <img src="${activity.img}" alt="Activity Image">
                <div class="activity-details">
                    <h2>${activity.name}</h2>
                    <p class="activity-description">${activity.description}</p>
                    <p class="activity-date">${activity.date}</p>
                    <button class="play-button">Play Now</button>
                </div>
            `;

            container.appendChild(activityDiv);
        });




</script>
</body>
</html>
