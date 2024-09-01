<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Progress Report | Student</title>

    <!-- stylesheet -->
    <link rel="stylesheet" href="CSS/Dashboard.css" />
    <link rel="stylesheet" href="CSS/style.css" />
    <!-- Bootstrap link -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="wrapper">
        <?php include 'Includes/DashSideNav.php'; ?>
            
        <main class="content px-3 py-2">
            <div class="container-fluid">
                <div class="row">
                    <div class="card flex-fill border-0 illustration">
                        <div class="card-body p-0 d-flex flex-fill">
                            <div class="row g-0 w-100">
                                <div class="col">
                                    <div class="p-3 m-1">
                                        <h4>School Progress Report</h4>
                                        <form name="formYearSearch" method="post" action="#">
                                            <div class="form-group">
                                                <label for="year">Select year</label>
                                                <select class="form-select" name="year" id="year">
                                                    <?php
                                                        //database connection
                                                        include 'DBConnection/DBConnection.php';

                                                        if (!$connection) {
                                                            echo "Connection failed";
                                                        }

                                                        //SQL query to retrieve distinct years from marks table
                                                        $sql = "SELECT DISTINCT MarkOfYear FROM marks ORDER BY MarkOfYear DESC";
                                                        $result = mysqli_query($connection, $sql);

                                                        if ($result) {
                                                            
                                                            //display year options in a dropdown
                                                            while ($row = mysqli_fetch_assoc($result)) {
                                                                echo '<option value="' . $row['MarkOfYear'] . '">' . $row['MarkOfYear'] . '</option>';
                                                            }
                                                            
                                                            mysqli_free_result($result);
                                                        } else {
                                                            echo "Error: " . mysqli_error($connection);
                                                        }

                                                        //close the database connection
                                                        mysqli_close($connection);
                                                    ?>
                                                </select>
                                            <button class="btnStyle1 mx-2" type="submit" name="submit">Search</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Table Element -->
                <div class="card border-0">
                    <div class="card-body">
                        <?php
                        //check if form is submitted
                        if (isset($_POST['submit'])) {
                            //initialize variables
                            $fullName = "";
                            $selectedYear = "";
                            $grade = "";

                            //retrieve selected year from form
                            $selectedYear = $_POST['year'];

                            //database connection
                            include 'DBConnection/DBConnection.php';

                            //check connection
                            if (!$connection) {
                                echo "<script>alert('Connection failed.');</script>";
                            }

                            //check if the cookie is set
                            if(isset($_COOKIE['studentId'])){
                                $studentId = $_COOKIE['studentId'];
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

                            //fetch student information
                            $studentSql = "SELECT CONCAT(FirstName, ' ', LastName) AS FullName, Grade 
                                           FROM student 
                                           WHERE StudentId = '$studentId'";
                            $studentResult = mysqli_query($connection, $studentSql);

                            if ($studentResult) {
                                $studentRow = mysqli_fetch_assoc($studentResult);
                                $fullName = $studentRow['FullName'];
                                $grade = $studentRow['Grade'];

                                //display student details
                                echo '<table class="tableHead">';
                                echo '<tr><th colspan="2"><img src="Images/Logo.jpg"></th></tr>';
                                echo '<tr><th colspan="2">Sri Indasara Vidyalaya</th></tr>';
                                echo '<tr><td colspan="2">Name : ' . $fullName . '</td></tr>';
                                echo '<tr><td>School year : ' . $selectedYear . '</td><td>Grade : ' . $grade . '</td></tr>';
                                echo '</table>';

                                //query to retrieve subject marks based on the selected year
                                $marksSql = "SELECT s.SubjectName, m.Term, m.Mark
                                             FROM marks m
                                             JOIN subjects s ON m.SubjectId = s.SubjectId
                                             WHERE m.MarkOfYear = '$selectedYear' AND m.StudentId = '$studentId'
                                             ORDER BY s.SubjectName, m.Term";

                                $result = mysqli_query($connection, $marksSql);

                                //display progress report table
                                if ($result) {
                                    echo '<table class="table" id="progressTable">';
                                    echo '<thead class="table-dark">';
                                    echo '<tr>';
                                    echo '<th style="width: 25%;">Subject Name</th>';
                                    echo '<th style="width: 15%;">1st term Mark</th>';
                                    echo '<th style="width: 15%;">2nd term Mark</th>';
                                    echo '<th style="width: 15%;">3rd term Mark</th>';
                                    echo '</tr>';
                                    echo '</thead>';
                                    echo '<tbody>';

                                    $currentSubject = null;
                                    $marks = ['1st term Mark' => '', '2nd term Mark' => '', '3rd term Mark' => ''];
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        if ($row['SubjectName'] !== $currentSubject) {
                                            if (!is_null($currentSubject)) {
                                                //print marks for the previous subject from the 4th column onwards
                                                echo '<tr>';
                                                $i = 0;
                                                foreach ($marks as $key => $mark) {
                                                    $i++;
                                                    if ($i > 2) {
                                                        echo '<td style="width: 15%;">' . ($mark === '' ? '' : $mark) . '</td>';
                                                    }
                                                }
                                                echo '</tr>';
                                            }
                                            //reset marks array for the new subject
                                            $currentSubject = $row['SubjectName'];
                                            $marks = ['1st term Mark' => '', '2nd term Mark' => '', '3rd term Mark' => ''];
                                            echo '<tr><td>' . $currentSubject . '</td>';
                                        }
                                        //store mark in corresponding term slot
                                        $marks[$row['Term']] = $row['Mark'];
                                    }
                                    //print marks for the last subject from the 4th column onwards
                                    echo '<tr>';
                                    $i = 0;
                                    foreach ($marks as $key => $mark) {
                                        $i++;
                                       
                                        if ($i > 2) {
                                            echo '<td style="width: 15%;">' . ($mark === '' ? '' : $mark) . '</td>';
                                        }
                                    }
                                    echo '</tr>';
                                    echo '</tbody>';
                                    echo '</table>';
                                } else {
                                    echo 'Error fetching subject marks: ' . mysqli_error($connection);
                                }
                            } else {
                                echo 'Error fetching student information: ' . mysqli_error($connection);
                            }

                            //close the database connection
                            mysqli_close($connection);
                        }
                        ?>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="js/Dashboard.js"></script>
    <!-- Bootstrap script -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>