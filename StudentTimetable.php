<?php
    //check if the cookie is set
    if(isset($_COOKIE['grade'])){
        $grade = $_COOKIE['grade'];
    } else {
        //redirect to login page after displaying a message
        echo '<script>
                var confirmMsg = confirm("Your session has timed out. Please log in again.");
                if (confirmMsg) {
                    window.location.href = "StudentLogin.html";
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
    <title>Time Table | Student</title>

    <!-- stylesheet -->
    <link rel="stylesheet" href="CSS/Dashboard.css" />
    <link rel="stylesheet" href="CSS/style.css" />
    <!-- Bootstap link -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="wrapper">
        <?php include 'Includes/DashSideNav.php'; ?>
            
            <main class="content px-3 py-2">
                <div class="container-fluid">
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
                            if(isset($_COOKIE['grade'])){
                                $grade = $_COOKIE['grade'];
                            } else {
                                //if cookie is not set, redirect to login page
                                echo '<script>
                                        var confirmMsg = confirm("Your session has timed out. Please log in again.");
                                        if (confirmMsg) {
                                            window.location.href = "StudentLogin.html";
                                        }
                                    </script>';
                                exit();
                            }

                            //SQL query
                            $sql = "SELECT subjects.SubjectName, teaching.DayOfWeek
                                    FROM subjects 
                                    INNER JOIN teaching ON subjects.SubjectId = teaching.SubjectId 
                                    WHERE teaching.Grade = $grade AND teaching.TeachingYear = YEAR(CURDATE())";

                            $result = mysqli_query($connection,$sql);
                        
                            //output data
                            if (mysqli_num_rows($result) > 0) {
                                //initialize array to store subjects by day of week
                                $subjectsByDay = array(
                                    'Monday' => array(),
                                    'Tuesday' => array(),
                                    'Wednesday' => array(),
                                    'Thursday' => array(),
                                    'Friday' => array()
                                );
                            
                                //store subjects in the array by day of week
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $subjectsByDay[$row['DayOfWeek']][] = $row['SubjectName'];
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

                                //determine the maximum number of subjects for any day
                                $maxSubjects = max(array_map('count', $subjectsByDay));
                                $rowCount = 0; // Initialize row count

                                //output data for each row
                                for ($i = 0; $i < $maxSubjects; $i++) {
                                    echo "<tr>";

                                    //output subjects for each day
                                    foreach ($subjectsByDay as $day => $subjects) {
                                        echo "<td>";
                                        if (isset($subjects[$i])) {
                                            echo $subjects[$i];
                                        }
                                        echo "</td>";
                                    }

                                    echo "</tr>";

                                    //increment row count
                                    $rowCount++;

                                    //add an interval row after every 4 rows filled
                                    if ($rowCount % 4 == 0 && $i < $maxSubjects - 1) {
                                        echo "<tr><th colspan='5' class='table-success interval'>INTERVAL</th></tr>";
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
    </div>


    <script src="js/Dashboard.js"></script>
    <!-- link Bootstap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>