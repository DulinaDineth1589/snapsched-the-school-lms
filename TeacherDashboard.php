<?php
//check if the cookie is set
if(isset($_COOKIE['teacherId'])){
    $TeacherId = $_COOKIE['teacherId']; // Retrieving teacherEmail from cookie
} else {
    //if cookie is not set, redirect to login page
    echo '<script>
            var confirmMsg = confirm("Your session has timed out. Please log in again.");
            if (confirmMsg) {
                window.location.href = "TeacherLogin.html";
            }
          </script>';
    exit();
}
?> 

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Teacher</title>

    <!-- stylesheet -->
    <link rel="stylesheet" href="CSS/Dashboard.css" />
    <link rel="stylesheet" href="CSS/style.css" />
    <!-- Bootstrap link -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="wrapper">
        <?php include 'Includes/TeacherSideNav.php'; ?>
            
        <main class="content px-3 py-2">
            <div class="container-fluid">
                <div class="row">
                    <div class="card flex-fill border-0 illustration">
                        <div class="card-body p-0 d-flex flex-fill">
                            <div class="row g-0 w-100">
                                <div class="col">
                                    <div class="p-3 m-1">
                                    <h4>Announcement</h4>
                                        <?php
                                            //get database connection
                                            include 'DBConnection/DBConnection.php';

                                            //check connection
                                            if (!$connection) {
                                                echo "Connection failed";
                                            }

                                            //SQL query
                                            $sql = "SELECT * FROM announcement WHERE AnnouncementDate >= CURDATE() AND (Recipient = 'both' OR Recipient = 'teacher')";

                                            $result = mysqli_query($connection,$sql);

                                            //output data
                                            if (mysqli_num_rows($result) > 0) {
                                                while ($row = mysqli_fetch_assoc($result)) {
                                                    echo "<div class='card border-0'>";
                                                    echo "<div class='card-body'>";
                                                    echo "<h5 class='card-title'>" . $row['Subject'] . "</h5>";
                                                    echo "<h5 class='card-title'>" . $row['AnnouncementDate'] . "</h5>";
                                                    echo "<p class='card-text'>" . $row['Announcement'] . "</p>";
                                                    echo "</div>";
                                                    echo "</div>";
                                                }
                                            } else {
                                                //if no results found
                                                echo "No announcements.";
                                            }

                                            //close the database connection
                                            mysqli_close($connection);
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- display relief classes -->
                <div class="card border-0">
                    <div class="card-header">
                        <h4 class="card-title">
                            Relief Classes
                        </h4>
                    </div>
                    <div class="card-body">
                        <?php
                            //get database connection
                            include 'DBConnection/DBConnection.php';
                                                        
                            //check connection
                            if (!$connection) {
                                echo "Connection failed";
                            }
                        
                            //SQL query to get relief classes for the current date and teacher
                            $sql = "SELECT DISTINCT teaching.Grade, teaching.DayOfWeek, teaching.Period
                                    FROM teaching
                                    INNER JOIN relief ON teaching.TeachingId = relief.TeachingId
                                    WHERE relief.TeacherId = '$TeacherId' AND DATE(relief.ReliefDate) = CURDATE()";
                
                            $result = mysqli_query($connection, $sql);
                        
                            //check for errors in the query
                            if (!$result) {
                                //print error message and exit
                                echo "<script>alert('Error: " . mysqli_error($connection) . "');</script>";
                                exit();
                            }
                        
                            //output data period, grade and day of week only
                            if (mysqli_num_rows($result) > 0) {
                                //display relief classes
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<div class='card border-0'>";
                                    echo "<div class='card-body'>";
                                    echo "<h5 class='card-title'>" . $row['DayOfWeek'] . " - Grade " . $row['Grade'] . " - Period " . $row['Period'] . "</h5>";
                                    echo "</div>";
                                    echo "</div>";
                                }
                            
                            } else {
                                //if no results found
                                echo "No relief classes for today.";
                            }
                        
                            //close the database connection
                            mysqli_close($connection);
                        ?>
                    </div>
                </div>


                <!-- Table Element -->
                <div class="card border-0">
                    <div class="card-header">
                        <h4 class="card-title">
                            Time Table
                        </h4>
                    </div>
                    <div class="card-body">
                        <?php
                            //get database connection
                            include 'DBConnection/DBConnection.php';

                            //check connection
                            if (!$connection) {
                                echo "Connection failed";
                            }

                            //check if the cookie is set
                            if(isset($_COOKIE['teacherId'])){
                                $TeacherId = $_COOKIE['teacherId'];
                            } else {
                                //if cookie is not set, redirect to login page
                                echo '<script>
                                        var confirmMsg = confirm("Your session has timed out. Please log in again.");
                                        if (confirmMsg) {
                                            window.location.href = "TeacherLogin.html";
                                        }
                                      </script>';
                                exit();
                            }

                            //SQL query
                            $sql = "SELECT Grade, DayOfWeek, Period
                                    FROM teaching
                                    WHERE TeacherId = '$TeacherId' AND TeachingYear = YEAR(CURDATE())";

                            $result = mysqli_query($connection,$sql);

                            //check for errors in the query
                            if (!$result) {
                                //print error message and exit
                                echo "<script>alert('Error: " . mysqli_error($connection) . "');</script>";
                                exit();
                            }
                        
                            //output data
                            if (mysqli_num_rows($result) > 0) {
                                //initialize array to store subjects by day of week and period
                                $classByDayPeriod = array(
                                    'Monday' => array_fill(1, 8, ''), //assuming 8 periods in a day
                                    'Tuesday' => array_fill(1, 8, ''),
                                    'Wednesday' => array_fill(1, 8, ''),
                                    'Thursday' => array_fill(1, 8, ''),
                                    'Friday' => array_fill(1, 8, '')
                                );

                                //store subjects in the array by day of week and period
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $classByDayPeriod[$row['DayOfWeek']][$row['Period']] = $row['Grade'];
                                }

                                //start the table
                                echo "<table class='table'>";
                                echo "<thead class='table-dark'>";
                                echo "<tr>";
                                echo "<th>Monday</th>";
                                echo "<th>Tuesday</th>";
                                echo "<th>Wednesday</th>";
                                echo "<th>Thursday</th>";
                                echo "<th>Friday</th>";
                                echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";

                                //determine the maximum number of periods for any day
                                //assuming 8 periods in a day
                                $maxPeriods = 8; 

                                //initialize interval added flag to false
                                $intervalAdded = false; 
                                
                                //output data for each period
                                for ($period = 1; $period <= $maxPeriods; $period++) {
                                    echo "<tr>";
                                    foreach ($classByDayPeriod as $day => $periods) {
                                        echo "<td>";
                                        if (isset($periods[$period]) && !empty($periods[$period])) {
                                            echo "Grade " . $periods[$period];
                                        } else {
                                            echo "Free";
                                        }
                                        echo "</td>";
                                    }
                                    echo "</tr>";

                                    //add an interval row after every 4 rows filled and only once
                                    if ($period % 4 == 0 && !$intervalAdded && $period < $maxPeriods) {
                                        echo "<tr><th colspan='5' class='table-success interval'>INTERVAL</th></tr>";
                                        //set interval added flag to true
                                        $intervalAdded = true;
                                    }
                                }
                            
                                //end the table
                                echo "</tbody>";
                                echo "</table>";
                            } else {
                                //if no results found
                                echo "No subjects found for this grade and year.";
                            }
                        
                            //close the database connection
                            mysqli_close($connection);
                        ?>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="JS/Dashboard.js"></script>
    <!-- Bootstrap script -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
