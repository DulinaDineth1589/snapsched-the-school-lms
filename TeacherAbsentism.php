<?php
if(isset($_POST["date"])) {
    //accept HTML Form data
    $Date = $_POST["date"];
    $Reason = $_POST["reason"];

    //validate fields
    if (empty($Date) || empty($Reason)) {
        echo "<script>alert('All fields are required.');</script>";
    } else {
        //include database connection
        include 'DBConnection/DBConnection.php';

        //check if the connection to the database failed
        if (!$connection) {
            echo "Database connection failed. Please try again later.";
        }

        //check if the cookie is set
        if(isset($_COOKIE['teacherId'])){
            $TeacherId = $_COOKIE['teacherId'];
        } else {
            //redirect to login page after displaying a message
            echo '<script>
                    var confirmMsg = confirm("Your session has timed out. Please log in again.");
                    if (confirmMsg) {
                        window.location.href = "TeacherLogin.html";
                    }
                  </script>';
            exit();
        }

        //check if the teacher is already absent on the given date
        $checkAbsentQuery = "SELECT AbsentId FROM absenteeism WHERE TeacherId = '$TeacherId' AND AbsentDate = '$Date'";
        $result = mysqli_query($connection, $checkAbsentQuery);

        if (mysqli_num_rows($result) > 0) {
            echo "<script>alert('You are already absent on this date.');</script>";
            echo "<script>window.location.href = 'TeacherDashboard.php';</script>";
        }

        //get the latest AbsentId from the database to determine the next ID
        $latestAbsentIdQuery = "SELECT AbsentId FROM absenteeism ORDER BY AbsentId DESC LIMIT 1";
        $result = mysqli_query($connection, $latestAbsentIdQuery);

        if ($row = mysqli_fetch_assoc($result)) {
            $lastAbsentId = $row['AbsentId'];
            $lastNumber = intval(substr($lastAbsentId, 1)); //extracting the number part and converting it to an integer
            $nextNumber = $lastNumber + 1;
            $nextAbsentId = 'A' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT); //padding the number with leading zeros
        } else {
            //if no records exist in the table, start from A001
            $nextAbsentId = 'A001';
        }

        //SQL query to insert data into the absenteeism table
        $sql = "INSERT INTO absenteeism (AbsentId, TeacherId, Reson, AbsentDate) VALUES ('$nextAbsentId', '$TeacherId', '$Reason', '$Date')";

        $result = mysqli_query($connection, $sql);

        if ($result) {
            echo "<script>alert('Successful.');</script>";
        } else {
            echo "<script>alert('Error: " . $sql . "<br>" . mysqli_error($connection) . "');</script>";
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
    <title>Absentism | Teacher</title>

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
            <div class="container">
                <!-- Table Element -->
                <div class="card border-0">
                    <div class="card-header">
                        <h4 class="card-title">
                            Absentism
                        </h4>
                    </div>
                    <div class="card-body table-responsive">
                        <form name="teacherProUp" action="#" method="post">
                            <table class="profileStyle">
                                <tbody>
                                    <tr>
                                        <td>
                                            Date : </br>
                                            <input type="date" class="form-control" name="date" required>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Reason :
                                            <textarea class="form-control" rows="10" name="reason" required></textarea>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2"><button type="submit" class="btnStyle1 mx-2">submit</button></td>
                                    </tr>
                                </tbody>
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