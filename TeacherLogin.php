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
            $sql = "SELECT * FROM teacher WHERE TeacherEmail='$userName' AND TeacherPassword='$password'";

            //execute the query
            $result = mysqli_query($connection, $sql);

            //check if exactly one row is returned
            if (mysqli_num_rows($result) == 1) {

                //check aviable user details
                $sql = "SELECT * FROM teacher WHERE TeacherEmail='$userName' AND TeacherStatus='Available'";

                //execute the query
                $result = mysqli_query($connection, $sql);

                if (mysqli_num_rows($result) == 1) {
                    //fetch the row
                    $row = mysqli_fetch_assoc($result);

                    //store user details in session variables
                    $_SESSION['userName'] = $row['TeacherEmail'];
                    $_SESSION['password'] = $password;

                    //fetch the student ID from the row
                    $teacherEmail = $row['TeacherEmail'];
                    $teacherId = $row['TeacherId'];

                    //set cookie for StudentId (expire in 30 minutes)
                    setcookie('teacherEmail', $teacherEmail, time() + (30 * 60), "/");
                    setcookie('teacherId', $teacherId, time() + (30 * 60), "/");

                    //redirect to student dashboard
                    header("Location: TeacherDashboard.php");
                    exit();
                } else {
                    //if no or more than one row is returned, display error
                    echo "<script>alert('Your access is denied. Please contact the administrator.');</script>";

                    //redirect to login page
                    echo "<script>window.location = 'TeacherLogin.html';</script>";
                }
            } else {
                //if no or more than one row is returned, display error
                echo "<script>alert('Invalid username or password. Please try again.');</script>";

                //redirect to login page
                echo "<script>window.location = 'TeacherLogin.html';</script>";
            }
        }
    } else {
        //if username or password is not provided via POST request, display error
        echo "Username and password are required fields.";

        //redirect to login page
        echo "<script>window.location = 'TeacherLogin.html';</script>";
    }

    //close the database connection
    mysqli_close($connection);
?>
