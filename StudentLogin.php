<?php
//start session
session_start();

//include database connection
include 'DBConnection/DBConnection.php';

//check if the connection to the database failed
if (!$connection) {
    echo "Database connection failed. Please try again later.";
}

//check if username and password are provided via POST request
if (isset($_POST['textUserName']) && isset($_POST['textPassword'])) {

    //function to validate input data
    function Validate($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    //validate username and password
    $userName = Validate($_POST['textUserName']);
    $password = Validate($_POST['textPassword']);

    //check if username or password is empty
    if (empty($userName) || empty($password)) {
        echo "Username and password are required fields.";
    } else {
        //construct SQL query to fetch user details
        $sql = "SELECT * FROM student WHERE (StudentId='$userName' OR StudentEmail='$userName') AND StudentPassword='$password'";

        //execute the query
        $result = mysqli_query($connection, $sql);

        //check if exactly one row is returned
        if (mysqli_num_rows($result) == 1) {
            //fetch the row
            $row = mysqli_fetch_assoc($result);
            
            //store user details in session variables
            $_SESSION['userName'] = $row['StudentEmail'];
            $_SESSION['password'] = $password;

            //fetch the student ID and grade from the row
            $studentId = $row['StudentId'];
            $grade = $row['Grade'];

            //set cookie for StudentId (expire in 30 minutes)
            setcookie('studentId', $studentId, time() + (30 * 60), "/"); 

            //set cookie for Grade (expire in 30 minutes)
            setcookie('grade', $grade, time() + (30 * 60), "/");

            //set cookie for StudentEmail (expire in 30 minutes)
            setcookie('studentEmail', $row['StudentEmail'], time() + (30 * 60), "/");

            //redirect to student dashboard
            header("Location: StudentDashboard.php");
            exit();
        } else {
            //if no or more than one row is returned, display error
            echo "Invalid username or password.";
        }
    }
} else {
    //if username or password is not provided via POST request, display error
    echo "Username and password are required fields.";
}

//close the database connection
mysqli_close($connection);
?>
