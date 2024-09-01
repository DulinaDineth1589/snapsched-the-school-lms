<?php
// Check if the teacherId cookie is set
if (isset($_COOKIE['teacherId'])) {
    $TeacherId = $_COOKIE['teacherId']; // Retrieving teacherEmail from cookie
} else {
    // If cookie is not set, redirect to login page
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
    <title>Progress Report | Teacher</title>

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
                                        <h4>School Progress Report</h4>
                                        <form id="formYearSearch" method="post" action="#">
                                            <div class="form-group">
                                                <label for="gradeSelect">Grade</label>
                                                <select id="gradeSelect" class="form-select" name="gradeSelect">
                                                    <?php
                                                        //database connection
                                                        include 'DBConnection/DBConnection.php';

                                                        //check connection
                                                        if (!$connection) {
                                                            echo "Connection failed";
                                                        }

                                                        //get grede from the database
                                                        $sql = "SELECT * FROM grade";
                                                        $result = mysqli_query($connection, $sql);

                                                        if ($result) {

                                                            //display grade options
                                                            while ($row = mysqli_fetch_assoc($result)) {
                                                                echo '<option value="' . $row['Grade'] . '">' . $row['Grade'] . '</option>';
                                                            }
                                                            
                                                            mysqli_free_result($result);
                                                        } else {
                                                            echo "Error: " . mysqli_error($connection);
                                                        }

                                                        //close the database connection
                                                        mysqli_close($connection);
                                                    ?>

                                                </select>
                                            <button id="firstSearchBtn" class="btnStyle1 mx-2" type="submit" name="submit">Search</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="secondFilterBar" class="row">
                    <div class="card flex-fill border-0 illustration">
                        <div class="card-body p-0 d-flex flex-fill">
                            <div class="row g-0 w-100">
                                <div class="col">
                                    <div class="p-3 m-1">
                                        <form id="formStudentSearch" method="post" action="#">
                                            <div class="form-group">
                                                <label for="studentId">Student</label>
                                                <select id="studentId" class="form-select" name="studentId">
                                                    <?php
                                                        //check if form is submitted
                                                        if (isset($_POST['gradeSelect'])) {
                                                            //database connection
                                                            include 'DBConnection/DBConnection.php';

                                                            //check connection
                                                            if (!$connection) {
                                                                echo "Connection failed";
                                                            }

                                                            //initialize variables
                                                            $selectedGrade = $_POST['gradeSelect'];

                                                            //query to retrieve student details based on the selected grade
                                                            $studentSql = "SELECT StudentId, CONCAT(FirstName, ' ', LastName) AS FullName
                                                                           FROM student
                                                                           WHERE Grade = '$selectedGrade'";
                                                            $studentResult = mysqli_query($connection, $studentSql);

                                                            if ($studentResult) {
                                                                //display student options
                                                                while ($row = mysqli_fetch_assoc($studentResult)) {
                                                                    echo '<option value="' . $row['StudentId'] . '">' . $row['FullName'] . '</option>';
                                                                }
                                                                
                                                                mysqli_free_result($studentResult);
                                                            } else {
                                                                echo "Error: " . mysqli_error($connection);
                                                            }

                                                            //close the database connection
                                                            mysqli_close($connection);
                                                        }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="form-group mx-2">
                                                <label for="yearSelect">Year</label>
                                                <select id="yearSelect" class="form-select" name="yearSelect">
                                                    <?php
                                                        //check if form is submitted
                                                        if (isset($_POST['gradeSelect'])) {
                                                            //database connection
                                                            include 'DBConnection/DBConnection.php';

                                                            //check connection
                                                            if (!$connection) {
                                                                echo "Connection failed";
                                                            }

                                                            //initialize variables
                                                            $selectedGrade = $_POST['gradeSelect'];

                                                            //query to retrieve school years based on the selected grade
                                                            $yearSql = "SELECT DISTINCT MarkOfYear
                                                                        FROM marks
                                                                        WHERE StudentId IN (SELECT StudentId FROM student WHERE Grade = '$selectedGrade')";
                                                            $yearResult = mysqli_query($connection, $yearSql);

                                                            if ($yearResult) {
                                                                //display year options
                                                                while ($row = mysqli_fetch_assoc($yearResult)) {
                                                                    echo '<option value="' . $row['MarkOfYear'] . '">' . $row['MarkOfYear'] . '</option>';
                                                                }
                                                                
                                                                mysqli_free_result($yearResult);
                                                            } else {
                                                                echo "Error: " . mysqli_error($connection);
                                                            }

                                                            //close the database connection
                                                            mysqli_close($connection);
                                                        }
                                                    ?>
                                                </select>
                                            </div>
                                            <button id="secondSearchBtn" class="btnStyle1 mx-2" type="submit">Search</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Table Element -->
                <div id="progressReport" class="card border-0">
                <div class="card border-0">
                    <div class="card-body">
                        <?php
                        //check if form is submitted
                        if (isset($_POST['studentId'])) {
                            //database connection
                            include 'DBConnection/DBConnection.php';

                            //check connection
                            if (!$connection) {
                                echo "Connection failed";
                            }
                            
                            //initialize variables
                            $fullName = "";
                            $selectedYear = "";
                            $grade = "";

                            //retrieve selected year from form
                            $selectedStudent = $_POST['studentId'];
                            $selectedYear = $_POST['yearSelect']; 

                            //fetch student information
                            $studentSql = "SELECT CONCAT(FirstName, ' ', LastName) AS FullName, Grade 
                                           FROM student 
                                           WHERE StudentId = '$selectedStudent'";
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
                                             WHERE m.MarkOfYear = '$selectedYear' AND m.StudentId = '$selectedStudent'
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
            </div>
        </main>
    </div>
</body>
</html>
