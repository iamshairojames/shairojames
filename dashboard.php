<?php

session_start();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" href="style.css">
    <title>JASAANKNOWN</title>

</head>

<style>
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
            <li class=""><a href="users.php" onclick="redirectTo('users.php')"><i class='bx bx-group'></i>Users</a></li>
            <li class=""><a href="archv.php" onclick="redirectTo('archive.php')"><i class='bx bx-cog'></i>Archive</a></li>
        </ul>
        <ul class="side-menu">
            <li>
                <a href="login.php" class="logout">
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
                    <h1>Dashboard</h1>
                    <ul class="breadcrumb">
                        <li><a href="#">
                            
                            </a></li>
                    </ul>
                </div>
              
            </div>

            <!-- Insights -->
            <ul class="insights">
                <li>
                    <a href="residents.php">
                        <i class='bx bx-group'></i>
                        <span style="color:#fbc02d; font-weight:bold ;">Residents</span>
                    </a>
                </li>
                <li>
                    <a href="voter.php">
                        <i class='bx bxs-registered'></i>
                        <span style="color:#fbc02d; font-weight:bold ;">Registered Voters</span>
                    </a>
                </li>
                <li>
                    <a href="household.php">
                        <i class='bx bxs-building-house'></i>
                        <span style="color:#fbc02d; font-weight:bold ;">Households</span>
                    </a>
                </li>
                <li>
                    <a href="activitydash.php">
                        <i class='bx bxs-hand'></i>
                        <span style="color:#fbc02d; font-weight:bold ;">Activities</span>
                    </a>
                </li>
                <li>
                    <a href="graph.php">
                        <i class='bx bxs-bar-chart-alt-2'></i>
                        <span style="color:#fbc02d; font-weight:bold ;">Graphs</span>
                    </a>
                </li>
                <li>
                    <a href="official.php">
                        <i class='bx bxs-user-rectangle'></i>
                        <span style="color:#fbc02d; font-weight:bold ;">Officials</span>
                    </a>
                </li>
                <li>
                    <a href="complaint.php">
                        <i class='bx bx-comment-dots'></i>
                        <span style="color:#fbc02d; font-weight:bold ;">Complaints</span>
                    </a>
                </li>
                <li>
                    <a href="about.php">
                        <i class='bx bxs-info-square'></i>
                        <span style="color:#fbc02d; font-weight:bold ;">About</span>
                    </a>
                </li>
            </ul>
            <!-- End of Insights -->

            <div class="bottom-data">
                <div class="orders">
                    <div class="header">
                        <i class='bx bx-receipt'></i>
                        <h3>Recent Users</h3>
                        <i class='bx bx-filter'></i>
                        <i class='bx bx-search'></i>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>Recent User</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <img src="images/lovely.jpg">
                                    <p>Lovely Vanessa</p>
                                </td>
                                <td><span class="status completed">15 mins ago</span></td>
                            </tr>
                            <tr>
                                <td>
                                    <img src="images/che.jpg">
                                    <p>Cherlyn Marfe</p>
                                </td>
                                <td><span class="status pending">Active 1 hour ago</span></td>
                            </tr>
                            <tr>
                                <td>
                                    <img src="images/shaiwo.jpg">
                                    <p>Shairo James</p>
                                </td>
                                <td><span class="status process">Active</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Reminders -->
                <div class="reminders">
                    <div class="header">
                        <i class='bx bx-note'></i>
                        <h3>Reminders</h3>
                        <i class='bx bx-filter'></i>
                        <i class='bx bx-plus'></i>
                    </div>
                    <ul class="task-list">
                        <li class="completed">
                            <div class="task-title">
                                <i class='bx bx-check-circle'></i>
                                <p>Start Our Meeting</p>
                            </div>
                            <i class='bx bx-dots-vertical-rounded'></i>
                        </li>
                        <li class="completed">
                            <div class="task-title">
                                <i class='bx bx-check-circle'></i>
                                <p>record submission</p>
                            </div>
                            <i class='bx bx-dots-vertical-rounded'></i>
                        </li>
                        <li class="not-completed">
                            <div class="task-title">
                                <i class='bx bx-x-circle'></i>
                                <p>thesis proposal</p>
                            </div>
                            <i class='bx bx-dots-vertical-rounded'></i>
                        </li>
                    </ul>
                </div>

                <!-- End of Reminders-->

            </div>

        </main>

    </div>

    <script src="index.js"></script>
</body>

</html>