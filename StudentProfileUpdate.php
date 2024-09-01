<?php
//get database connection
include 'DBConnection/DBConnection.php';

//check connection
if (!$connection) {
    echo "<script>alert('Connection failed.');</script>";
}
//initialize error message variable
$errorMessage = "";

//initialize variables
$firstName = $lastName = $surName = $dob = $address = $email = $guardianName = $guardianContact = $emergencyContact = $currentPassword = $newPassword = $confirmPassword = "";

//check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //retrieve form data
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $surName = $_POST['surName'];
    $dob = $_POST['dob'];
    $address = $_POST['address'];
    $email = $_POST['email'];
    $guardianName = $_POST['guardianName'];
    $guardianContact = $_POST['guardianContact'];
    $emergencyContact = $_POST['emergencyContact'];
    $currentPassword = $_POST['currentPassword'];
    $newPassword = $_POST['newPassword'];
    $confirmPassword = $_POST['confirmPassword'];

    $errorMessage = "";
    
    //check if the cookie is set
    if(isset($_COOKIE['studentId']) && isset($_COOKIE['studentEmail'])){
        $studentId = $_COOKIE['studentId'];
        $studentEmail = $_COOKIE['studentEmail'];
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

    //check if newPassword or confirmPassword is empty and currentPassword is not empty
    if (!empty($newPassword) || !empty($confirmPassword)) {
        if (empty($currentPassword)) {
            $errorMessage = "Current Password is required.";
        } else {
            //verify current password
            $sqlVerifyPassword = "SELECT StudentPassword FROM student WHERE StudentId='$studentId'";
            $resultVerifyPassword = mysqli_query($connection, $sqlVerifyPassword);

            if (mysqli_num_rows($resultVerifyPassword) > 0) {
                $rowPassword = mysqli_fetch_assoc($resultVerifyPassword);
                $storedPassword = $rowPassword['StudentPassword'];

                if ($currentPassword != $storedPassword) {
                    $errorMessage = "Incorrect current password.";
                } else {
                    //check if newPassword and confirmPassword match
                    if ($newPassword != $confirmPassword) {
                        $errorMessage = "Password and Confirm Password do not match. Please enter again.";
                    } else {
                        //validate input data
                        $firstName = trim($firstName);
                        $lastName = trim($lastName);
                        $surName = trim($surName);
                        $dob = trim($dob);
                        $address = trim($address);
                        $email = trim($email);
                        $guardianName = trim($guardianName);
                        $guardianContact = trim($guardianContact);
                        $emergencyContact = trim($emergencyContact);

                        //validate password characters minimum 8
                        if (strlen($newPassword) < 8) {
                            $errorMessage = "Password must be at least 8 characters long.";
                        } else {
                            //validate at least one uppercase letter, one lowercase letter, one number and one special character
                            if (!preg_match("/[A-Z]/", $newPassword) || !preg_match("/[a-z]/", $newPassword) || !preg_match("/[0-9]/", $newPassword) || !preg_match("/[!@#\$%\^&\*]/", $newPassword)) {
                                $errorMessage = "Password must contain at least one uppercase letter, one lowercase letter, one number and one special character.";
                            }

                            // Validate email last @gmail.com
                            if (!filter_var($email, FILTER_VALIDATE_EMAIL) || substr($email, -10) !== "@gmail.com") {
                                $errorMessage = "Invalid email format or not a Gmail address.";
                            }

                            //validate email already exists
                            $sqlEmail = "SELECT StudentEmail FROM student WHERE StudentEmail='$email' AND StudentId!='$studentId'";
                            $resultEmail = mysqli_query($connection, $sqlEmail);

                            if (mysqli_num_rows($resultEmail) > 0) {
                                $errorMessage = "Email already exists.";
                            }

                            //validate guardian contact number
                            if (!preg_match("/^[0-9]{10}$/", $guardianContact)) {
                                $errorMessage = "Invalid guardian contact number.";
                            }

                            //validate emergency contact number
                            if (!preg_match("/^[0-9]{10}$/", $emergencyContact)) {
                                $errorMessage = "Invalid emergency contact number.";
                            }

                            //if no error message
                            if ($errorMessage == "") {
                                //update student table
                                $sqlStudent = "UPDATE student SET FirstName='$firstName', LastName='$lastName', SurName='$surName', DOB='$dob', StudentAddress='$address', StudentEmail='$email', StudentPassword='$newPassword' WHERE StudentId='$studentId'";

                                if (mysqli_query($connection, $sqlStudent)) {
                                    echo "<script>alert('Student record updated successfully');</script>";
                                } else {
                                    echo "<script>alert('Error updating student record: " . mysqli_error($connection) . "');</script>";
                                }

                                //update currentStudent table
                                $sqlCurrentStudent = "UPDATE currentStudent SET GuardianName='$guardianName', GuardianContactNo='$guardianContact', EmergencyContactNo='$emergencyContact' WHERE StudentId='$studentId'";

                                if (mysqli_query($connection, $sqlCurrentStudent)) {
                                    echo "<script>alert('Current student record updated successfully');</script>";
                                } else {
                                    echo "<script>alert('Error updating current student record: " . mysqli_error($connection) . "');</script>";
                                }
                            }
                        }
                        
                    }
                }
            } else {
                die("Student record not found.");
            }
        }
    } else {

        //validate input data
        $firstName = trim($firstName);
        $lastName = trim($lastName);
        $surName = trim($surName);
        $dob = trim($dob);
        $address = trim($address);
        $email = trim($email);
        $guardianName = trim($guardianName);
        $guardianContact = trim($guardianContact);
        $emergencyContact = trim($emergencyContact);

        $errorMessage = "";

        // Validate email last @gmail.com
        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || substr($email, -10) !== "@gmail.com") {
            $errorMessage = "Invalid email format or not a Gmail address.";
        }

        //validate email already exists
        $sqlEmail = "SELECT StudentEmail FROM student WHERE StudentEmail='$email' AND StudentId!='$studentId'";
        $resultEmail = mysqli_query($connection, $sqlEmail);

        if (mysqli_num_rows($resultEmail) > 0) {
            $errorMessage = "Email already exists.";
        }

        //validate guardian contact number
        if (!preg_match("/^[0-9]{10}$/", $guardianContact)) {
            $errorMessage = "Invalid guardian contact number.";
        }

        //validate emergency contact number
        if (!preg_match("/^[0-9]{10}$/", $emergencyContact)) {
            $errorMessage = "Invalid emergency contact number.";
        }

        //if no error message
        if ($errorMessage == "") {
            //update student table without password change
            $sqlStudent = "UPDATE student SET FirstName='$firstName', LastName='$lastName', SurName='$surName', DOB='$dob', StudentAddress='$address', StudentEmail='$email' WHERE StudentId='$studentId'";

            if (mysqli_query($connection, $sqlStudent)) {
                echo "<script>alert('Student record updated successfully');</script>";
            } else {
                echo "<script>alert('Error updating student record: " . mysqli_error($connection) . "');</script>";
            }

            //update currentStudent table
            $sqlCurrentStudent = "UPDATE currentStudent SET GuardianName='$guardianName', GuardianContactNo='$guardianContact', EmergencyContactNo='$emergencyContact' WHERE StudentId='$studentId'";

            if (mysqli_query($connection, $sqlCurrentStudent)) {
                echo "<script>alert('Current student record updated successfully');</script>";
            } else {
                echo "<script>alert('Error updating current student record: " . mysqli_error($connection) . "');</script>";
            }
        }
    }
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

$sql = "SELECT * FROM student INNER JOIN currentStudent ON student.StudentId = currentStudent.StudentId WHERE student.StudentId='$studentId'";

$result = mysqli_query($connection, $sql);

if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);

    //assign values to variables
    $firstName = $row['FirstName'];
    $lastName = $row['LastName'];
    $surName = $row['SurName'];
    $dob = $row['DOB'];
    $address = $row['StudentAddress'];
    $email = $row['StudentEmail'];
    $guardianName = $row['GuardianName'];
    $guardianContact = $row['GuardianContactNo'];
    $emergencyContact = $row['EmergencyContactNo'];
} else {
    echo "0 results";
}

//close the database connection
mysqli_close($connection);
?> 

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Update | Student</title>

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
                <!-- Table Element -->
                <div class="card border-0">
                    <div class="card-header">
                        <h4 class="card-title">
                            Account Update
                        </h4>
                    </div>
                    <?php if ($errorMessage != "") { ?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo $errorMessage; ?>
                        </div>
                    <?php } ?>
                    <div class="card-body">
                        <form name="studentProUp" action="#" method="post">
                            <table class="puTable">
                                <tr>
                                    <th colspan="2">Personal Details</th>
                                </tr>
                                <tr>
                                    <td>
                                        First Name :<br>
                                        <input type="text" name="firstName" value="<?php echo $firstName; ?>">
                                    </td>
                                    <td>
                                        Last Name :<br>
                                        <input type="text" name="lastName" value="<?php echo $lastName; ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Sur Name :<br>
                                        <input type="text" name="surName" value="<?php echo $surName; ?>">
                                    </td>
                                    <td>
                                        DOB :<br>
                                        <input type="date" name="dob" value="<?php echo $dob; ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Address :<br>
                                        <input type="text" name="address" value="<?php echo $address; ?>">
                                    </td>
                                    <td>
                                        Email :<br>
                                        <input type="email" name="email" value="<?php echo $email; ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <th colspan="2">Guardian Details</th>
                                </tr>
                                <tr>
                                    <td>
                                        Guardian Name :<br>
                                        <input type="text" name="guardianName" value="<?php echo $guardianName; ?>">
                                    </td>
                                    <td>
                                        Guardian contact no :<br>
                                        <input type="text" name="guardianContact" value="<?php echo $guardianContact; ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Emergency contact no :<br>
                                        <input type="text" name="emergencyContact" value="<?php echo $emergencyContact; ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <th colspan="2">Password</th>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        Current Password :<br>
                                        <input type="password" name="currentPassword">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        New Password :<br>
                                        <input type="password" name="newPassword">
                                    </td>
                                    <td>
                                        Confirm Password :<br>
                                        <input type="password" name="confirmPassword">
                                    </td>
                                </tr>
                                <tr>
                                    <td><button class="btnStyle1 mx-2">Save</button></td>
                                </tr>
                            </table>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>
    
    <script src="js/Dashboard.js"></script>
    <!-- Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
