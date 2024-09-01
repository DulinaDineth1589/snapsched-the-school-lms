<?php
//get database connection
include 'DBConnection/DBConnection.php';

//check connection
if (!$connection) {
    echo "Connection failed";
}

//error message variable
$errorMessage = "";

//check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //retrieve form data
    $currentPassword = $_POST['currentPassword'];
    $newPassword = $_POST['newPassword'];
    $confirmPassword = $_POST['confirmPassword'];
    
    $errorMessage = "";

    //check if newPassword or confirmPassword is empty and currentPassword is not empty
    if (!empty($newPassword) || !empty($confirmPassword)) {
        if (empty($currentPassword)) {
            $errorMessage = "Current Password is required.";
        } else {
            
            //check if the cookie is set
            if(isset($_COOKIE['studentId'])){
                $studentId = $_COOKIE['studentId']; // Retrieving teacherEmail from cookie
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
                        if (strlen($newPassword) < 8) {
                            $errorMessage = "Password must be at least 8 characters long.";
                        } else {
                            //validate password at least one uppercase letter, one lowercase letter, one number and one special character
                            if (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*(_|[^\w])).+$/", $newPassword)) {
                                $errorMessage = "Password must contain at least one uppercase letter, one lowercase letter, one number and one special character.";
                            } else {
                                //update student table with new password
                                $sqlUpdatePassword = "UPDATE student SET StudentPassword='$newPassword' WHERE StudentId='$studentId'";
                        
                                $result = mysqli_query($connection, $sqlUpdatePassword);

                                if ($result) {
                                    echo "<script>alert('Student password updated successfully');</script>";
                                } else {
                                    echo "<script>alert('Error updating student password: " . mysqli_error($connection) . "');</script>";
                                }
                            }
                        }
                    }
                }
            } else {
                echo "<script>alert('Error verifying current password.');</script>";
            }
        }
    } else {
        //no password update requested
        echo "<script>alert('No changes made to password.');</script>";
    }
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
                            Account
                        </h4>
                    </div>
                    <div class="card-body">
                        <form name="studentProUp" action="#" method="post">
                            <table class="cpTable">
                                <tr>
                                    <th colspan="2">Password</th>
                                </tr>
                                <?php
                                    if ($errorMessage != "") {
                                        echo "<tr>";
                                        echo "<td colspan='2'>";
                                        echo "<div class='alert alert-danger' role='alert'>";
                                        echo $errorMessage;
                                        echo "</div>";
                                        echo "</td>";
                                        echo "</tr>";
                                    }
                                ?>
                                <tr>
                                    <td>
                                        Current Password :<br>
                                        <input type="password" name="currentPassword">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        New Password :<br>
                                        <input type="password" name="newPassword">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Confirm Password :<br>
                                        <input type="password" name="confirmPassword">
                                    </td>
                                </tr>
                                <tr>
                                    <td><button class="btnStyle1 mx-2" type="submit">Save</button></td>
                                </tr>
                            </table>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="js/Dashboard.js"></script>
    <!-- link Bootstap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
