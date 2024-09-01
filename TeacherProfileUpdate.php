<?php
include 'DBConnection/DBConnection.php';

// Check database connection
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

// Error message variable
$errorMessage = "";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $surName = $_POST['sur_name'];
    $address = $_POST['address'];
    $email = $_POST['email'];
    $contact_no = $_POST['contact_no'];
    $currentPassword = $_POST['current_password'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];
    $nic = $_POST['nic'];
    
    // Check if the cookie is set
    if(isset($_COOKIE['teacherEmail'])){
        $teacherEmail = $_COOKIE['teacherEmail'];
    } else {
        // If cookie is not set, redirect to login page
        header("Location: TeacherLogin.html");
        exit();
    }

    // Check if newPassword or confirmPassword is empty and currentPassword is not empty
    if (!empty($newPassword) || !empty($confirmPassword)) {
        if (empty($currentPassword)) {
            $errorMessage = "Current Password is required.";
        } else {
            // Verify current password
            $sqlVerifyPassword = "SELECT TeacherPassword FROM teacher WHERE TeacherEmail='$teacherEmail'";
            $resultVerifyPassword = mysqli_query($connection, $sqlVerifyPassword);

            if ($resultVerifyPassword) {
                $rowPassword = mysqli_fetch_assoc($resultVerifyPassword);
                $storedPassword = $rowPassword['TeacherPassword'];

                if ($currentPassword != $storedPassword) {
                    $errorMessage = "Incorrect current password.";
                } else {
                    // Validate new password
                    $passwordPattern = '/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[^A-Za-z0-9])[A-Za-z\d^A-Za-z0-9]{8,}$/';
                    if (!preg_match($passwordPattern, $newPassword)) {
                        $errorMessage = "Password must be at least 8 characters long and contain at least one uppercase letter, one lowercase letter, one number, and one special character.";
                    }

                    //validate nic 12 or 10 characters
                    if ((strlen($nic) != 12 && strlen($nic) != 10) || (strlen($nic) == 12 && !preg_match("/^[0-9]+$/", $nic)) || (strlen($nic) == 10 && !preg_match("/^[0-9]{9}[Vv]$/", $nic))) {
                        $errorMessage = "Invalid NIC.";
                    }

                    // Validate NIC uniqueness
                    $sqlNic = "SELECT NIC FROM teacher WHERE NIC='$nic'";
                    $resultNic = mysqli_query($connection, $sqlNic);
                    if (mysqli_num_rows($resultNic) > 1) {
                        $errorMessage = "NIC already exists.";
                    }

                    // Validate email last @gmail.com
                    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || substr($email, -10) !== "@gmail.com") {
                        $errorMessage = "Invalid email format or not a Gmail address.";
                    }

                    // Validate email uniqueness
                    if ($email != $teacherEmail) {
                        $sqlEmail = "SELECT TeacherEmail FROM teacher WHERE TeacherEmail='$email'";
                        $resultEmail = mysqli_query($connection, $sqlEmail);
                        if (mysqli_num_rows($resultEmail) > 0) {
                            $errorMessage = "Email already exists.";
                        }
                    }

                    // Validate contact number (10 digits)
                    if (!preg_match('/^[0-9]{10}$/', $contact_no)) {
                        $errorMessage = "Contact number must be 10 digits.";
                    }

                    if (empty($errorMessage)) {
                        // Update teacher table with password change
                        $sqlTeacher = "UPDATE teacher SET FirstName='$firstName', LastName='$lastName', SurName='$surName', TeacherAddress='$address', TeacherEmail='$email', TeacherContactNo='$contact_no', NIC='$nic', TeacherPassword='$newPassword' WHERE TeacherEmail='$teacherEmail'";

                        // Execute the query
                        $result = mysqli_query($connection, $sqlTeacher);
                        if ($result) {
                            echo "<script>alert('Teacher details updated successfully');</script>";
                        } else {
                            echo "<script>alert('Error updating teacher details. Please try again later.');</script>";
                        }
                    }
                }
            } else {
                die("Error: " . mysqli_error($connection));
            }
        }
    } else {
        //validate nic 12 or 10 characters
        if ((strlen($nic) != 12 && strlen($nic) != 10) || (strlen($nic) == 12 && !preg_match("/^[0-9]+$/", $nic)) || (strlen($nic) == 10 && !preg_match("/^[0-9]{9}[Vv]$/", $nic))) {
            $errorMessage = "Invalid NIC.";
        }

        // Validate NIC uniqueness
        $sqlNic = "SELECT NIC FROM teacher WHERE NIC='$nic'";
        $resultNic = mysqli_query($connection, $sqlNic);
        if (mysqli_num_rows($resultNic) > 1) {
            $errorMessage = "NIC already exists.";
        }

        // Validate email last @gmail.com
        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || substr($email, -10) !== "@gmail.com") {
            $errorMessage = "Invalid email format or not a Gmail address.";
        }

        // Validate email uniqueness
        if ($email != $teacherEmail) {
            $sqlEmail = "SELECT TeacherEmail FROM teacher WHERE TeacherEmail='$email'";
            $resultEmail = mysqli_query($connection, $sqlEmail);

            if (mysqli_num_rows($resultEmail) > 0) {
                $errorMessage = "Email already exists.";
            }
        }

        // Validate contact number (10 digits)
        if (!preg_match('/^[0-9]{10}$/', $contact_no)) {
            $errorMessage .= "Contact number must be 10 digits.";
        }

        // If no errors, update teacher details
        if (empty($errorMessage)) {
            // Update teacher table
            $sqlTeacher = "UPDATE teacher SET FirstName='$firstName', LastName='$lastName', SurName='$surName', TeacherAddress='$address', TeacherEmail='$email', TeacherContactNo='$contact_no', NIC='$nic' WHERE TeacherEmail='$teacherEmail'";
            if (mysqli_query($connection, $sqlTeacher)) {
                echo "<script>alert('Teacher details updated successfully');</script>";
            } else {
                echo "<script>alert('Error updating teacher details. Please try again later.');</script>";
            }
        }
    }
}

// Check if the cookie is set
if(isset($_COOKIE['teacherEmail'])){
    $teacherEmail = $_COOKIE['teacherEmail'];
} else {
    // If cookie is not set, redirect to login page
    header("Location: TeacherLogin.html");
    exit();
}

// SQL query to retrieve teacher information
$sql = "SELECT * FROM teacher WHERE TeacherEmail='$teacherEmail'";
$result = mysqli_query($connection, $sql);

// Initialize variables to store retrieved values
if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $first_name = $row['FirstName'];
    $last_name = $row['LastName'];
    $sur_name = $row['SurName'];
    $address = $row['TeacherAddress'];
    $email = $row['TeacherEmail'];
    $contact_no = $row['TeacherContactNo'];
    $nic = $row['NIC'];
} else {
    echo "0 results";
}

// Close the database connection
mysqli_close($connection);
?> 

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Update | Teacher</title>

    <!-- Stylesheets -->
    <link rel="stylesheet" href="CSS/Dashboard.css" />
    <link rel="stylesheet" href="CSS/style.css" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="wrapper">
        <?php include 'Includes/TeacherSideNav.php'; ?>

        <main class="content px-3 py-2">
            <div class="container-fluid">
                <!-- Table Element -->
                <div class="card border-0">
                    <div class="card-header">
                        <h4 class="card-title">Account</h4>
                    </div>
                    <!-- Error Message -->
                        <?php if (!empty($errorMessage)) : ?>
                            <div class="alert alert-danger" role="alert">
                                <?php echo $errorMessage; ?>
                            </div>
                        <?php endif; ?>
                    <div class="card-body">
                        <form name="teacherProUp" action="#" method="post">
                            <table class="puTable">
                                <tr>
                                    <th colspan="2">Personal Details</th>
                                </tr>
                                <tr>
                                    <td>
                                        First Name:<br>
                                        <input type="text" name="first_name" value="<?php echo $first_name; ?>">
                                    </td>
                                    <td>
                                        Last Name:<br>
                                        <input type="text" name="last_name" value="<?php echo $last_name; ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Sure Name:<br>
                                        <input type="text" name="sur_name" value="<?php echo $sur_name; ?>">
                                    </td>
                                    <td>
                                        Address:<br>
                                        <input type="text" name="address" value="<?php echo $address; ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Contact no:<br>
                                        <input type="text" name="contact_no" value="<?php echo $contact_no; ?>">
                                    </td>
                                    <td>
                                        Email:<br>
                                        <input type="email" name="email" value="<?php echo $email; ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        NIC:<br>
                                        <input type="text" name="nic" value="<?php echo $nic; ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <th colspan="2">Password</th>
                                </tr>
                                <tr>
                                    <td>
                                        Current Password:<br>
                                        <input type="password" name="current_password">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        New Password:<br>
                                        <input type="password" name="new_password">
                                    </td>
                                    <td>
                                        Confirm Password:<br>
                                        <input type="password" name="confirm_password">
                                    </td>
                                </tr>
                                <tr>
                                    <td><button type="submit" class="btnStyle1 mx-2">Save</button></td>
                                </tr>
                            </table>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- JavaScript -->
    <script src="js/Dashboard.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
