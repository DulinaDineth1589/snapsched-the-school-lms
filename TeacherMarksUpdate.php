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

//check if the formMarks is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['saveMarks'])) {
    //retrieve form data
    $marks = $_POST['marks'];
    $studentIds = $_POST['studentId']; //array of student IDs
    $selectedSubject = $_POST['subjectSelect'];
    $selectedTerm = $_POST['termSelect'];

    //check if marks is empty
    if (empty($marks) || empty($studentIds)) {
        echo "<script>alert('Marks and Student IDs are required.');</script>";
    } else {
        //database connection
        include 'DBConnection/DBConnection.php';

        //check the connection
        if (!$connection) {
            echo "Connection failed";
        }

        //get subject id from the database
        $sqlSubject = "SELECT SubjectId FROM subjects WHERE SubjectName = '$selectedSubject'";
        $resultSubject = mysqli_query($connection, $sqlSubject);

        if ($resultSubject) {
            $rowSubject = mysqli_fetch_assoc($resultSubject);
            $subjectId = $rowSubject['SubjectId'];

            //current year
            $currentYear = date("Y");

            //array to store whether each update was successful
            $updateSuccess = array();

            //iterate over each student ID and insert marks
            foreach ($studentIds as $key => $studentId) {
                //get the mark
                $mark = $marks[$key];
            
                //update marks table
                $sql = "UPDATE marks SET Mark = ? WHERE StudentId = ? AND SubjectId = ? AND Term = ? AND MarkOfYear = ?";
                $stmt = mysqli_prepare($connection, $sql);
                mysqli_stmt_bind_param($stmt, "sssss", $mark, $studentId, $subjectId, $selectedTerm, $currentYear);
            
                //execute the update statement
                $updateSuccess[$key] = mysqli_stmt_execute($stmt);
            
                //close the prepared statement
                mysqli_stmt_close($stmt);
            }

            //check if all updates were successful
            if (array_search(false, $updateSuccess) === false) {
                echo "<script>alert('Marks updated successfully');</script>";
            } else {
                // At least one update failed
                echo "<script>alert('Error updating marks');</script>";
            }
            
        } else {
            echo "Error: " . mysqli_error($connection);
        }

        //close the database connection
        mysqli_close($connection);
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marks | Teacher</title>

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
                                        <h4>Student marks</h4>
                                        <form name="formYearSearch" method="post" action="#">
                                        <div class="form-group">
                                                <label for="gradeSelect">Grade</label>
                                                <select class="form-select" name="gradeSelect" id="gradeSelect" required>
                                                <?php
                                                        //database connection
                                                        include 'DBConnection/DBConnection.php';
    
                                                        if (!$connection) {
                                                            echo "Connection failed";
                                                        }
    
                                                        $sql = "SELECT * FROM grade";
                                                        $result = mysqli_query($connection, $sql);
    
                                                        if ($result) {
                                                            //fetch data from the database
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
                                            </div>
                                            <div class="form-group mt-2">
                                                <label for="subjectSelect">Subject</label>
                                                <select class="form-select" name="subjectSelect" id="subjectSelect" required>
                                                    <?php
                                                    //database connection
                                                    include 'DBConnection/DBConnection.php';

                                                    if (!$connection) {
                                                        echo "Connection failed";
                                                    }

                                                    //fetch data from the database
                                                    $sql = "SELECT * FROM subjects";
                                                    $result = mysqli_query($connection, $sql);

                                                    if ($result) {
                                                        //fetch data from the database
                                                        while ($row = mysqli_fetch_assoc($result)) {
                                                            echo '<option value="' . $row['SubjectName'] . '">' . $row['SubjectName'] . '</option>';
                                                        }
                                                        
                                                        mysqli_free_result($result);
                                                    } else {
                                                        echo "Error: " . mysqli_error($connection);
                                                    }

                                                    //close the database connection
                                                    mysqli_close($connection);
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="form-group mt-2">
                                                <label for="termSelect">Term</label>
                                                <select class="form-select" name="termSelect" id="termSelect" required>
                                                    <option value="1">1st Term</option>
                                                    <option value="2">2nd Term</option>
                                                    <option value="3">3rd Term</option>
                                                </select>
                                            </div>
                                            <button class="btnStyle1 mx-2" type="submit">Search</button>
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
                        //handle form submission
                        if ($_SERVER["REQUEST_METHOD"] == "POST") {
                            //retrieve selected values from the form
                            if(isset($_POST['gradeSelect'])){
                                $selectedGrade = $_POST['gradeSelect'];
                                $selectedSubject = $_POST['subjectSelect'];
                                $selectedTerm = $_POST['termSelect'];
                                
                                //display student details
                                echo '<table class="tableHead">';
                                echo '<tr><th colspan="2"><img src="Images/Logo.jpg"></th></tr>';
                                echo '<tr><th colspan="2">Sri Indasara Vidyalaya</th></tr>';
                                echo '<tr><td>Grade : ' . $selectedGrade . '</td><td colspan="2">Subject : ' . $selectedSubject . '</td></tr>';
                                echo '<tr><td>School year : ' . date("Y") . '</td><td>Term : ' . $selectedTerm . '</td></tr>';
                                echo '</table>';

                                //database connection
                                include 'DBConnection/DBConnection.php';

                                //check the connection
                                if (!$connection) {
                                    echo "Connection failed";
                                }

                                //fetch student data based on selected criteria
                                $sql = "SELECT student.*, marks.Mark 
                                FROM student 
                                LEFT JOIN marks ON student.StudentId = marks.StudentId
                                LEFT JOIN subjects ON marks.SubjectId = subjects.SubjectId
                                WHERE student.Grade = '$selectedGrade' AND 
                                      subjects.SubjectName = '$selectedSubject' AND 
                                      marks.Term = '$selectedTerm' AND marks.MarkOfYear = '" . date("Y") . "'";
                                $result = mysqli_query($connection, $sql);

                                //display student data
                                if ($result) {
                                    echo '<form name="formMarks" method="post" action="#">';
                                    echo '<input type="hidden" name="subjectSelect" value="' . $selectedSubject . '">';
                                    echo '<input type="hidden" name="termSelect" value="' . $selectedTerm . '">';
                                    echo '<table class="table">';
                                    echo '<thead class="table-dark">';
                                    echo '<tr>';
                                    echo '<th style="width: 20%;">Student ID</th>';
                                    echo '<th style="width: 40%;">Student Name</th>';
                                    echo '<th style="width: 10%;">Term</th>';
                                    echo '<th style="width: 30%;">Mark</th>';
                                    echo '</tr>';
                                    echo '</thead>';
                                    echo '<tbody>';

                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo '<tr>';
                                        echo '<td><input type="hidden" name="studentId[]" value="' . $row['StudentId'] . '">' . $row['StudentId'] . '</td>';
                                        echo '<td>' . $row['FirstName'] . ' ' . $row['LastName'] . '</td>'; //concatenating FirstName and LastName
                                        echo '<td>' . $selectedTerm . '</td>';
                                        echo '<td><input type="text" name="marks[]" value="' . ($row['Mark'] ?? '') . '"></td>';
                                        echo '</tr>';
                                    }
                                    echo '<tr>';
                                    echo '<td colspan="2"><button type="submit" name="saveMarks" class="btnStyle1 mx-2">Save</button></td>';
                                    echo '</tr>';
                                    echo '</tbody>';
                                    echo '</table>';
                                    echo '</form>';
                                    mysqli_free_result($result);
                                } else {
                                    echo "Error: " . mysqli_error($connection);
                                }

                                //close the database connection
                                mysqli_close($connection);
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="js/Dashboard.js"></script>
    <!-- Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
