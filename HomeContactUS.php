<?php
    //get database connection
    include 'DBConnection/DBConnection.php';

    //check connection
    if (!$connection) {
        echo "Connection failed";
    }
    //error message
    $errorMessage = "";

    //initialize variables for error handling
    $name = $email = $contact = $message = $date = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $contact = $_POST['contact'];
        $date = $_POST['date'];
        $message = $_POST['message'];

        //validate email last @gmail.com
        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || substr($email, -10) !== "@gmail.com") {
            $errorMessage = "Invalid email format or not a Gmail address.";
        }

        //validate contact number
        if (!preg_match("/^[0-9]{10}$/", $contact)) {
            $errorMessage = "Invalid contact number";
        }

        //if there are no errors
        if ($errorMessage == "") {
            //generate AppointmentId for new user (AppointmentId = A0001 like that)
            $sql = "SELECT MAX(AppointmentId) AS max FROM appointment";
            $result = mysqli_query($connection, $sql);
            $row = mysqli_fetch_assoc($result);

            if ($row['max'] == NULL) {      
                $appointmentId = "A0001";
            } else {        
                $currentId = $row['max'];
                $idNumber = substr($currentId, 1);
                $newIdNumber = str_pad($idNumber + 1, strlen($idNumber), '0', STR_PAD_LEFT);
                $appointmentId = 'A' . $newIdNumber;
            }

            $sql = "INSERT INTO appointment (AppointmentId, SenderName, Message, SenderContactNo, SenderEmail, AppointmentDate) 
            VALUES ('$appointmentId', '$name', '$message', '$contact', '$email', '$date')";

            //execute the query
            $result = mysqli_query($connection, $sql);

            if ($result) {
                //message sent successfully
                echo "<script>alert('Message sent successfully!');</script>";
                echo "<script>window.location = 'HomePage.php';</script>";
                exit();
            } else {
                //message sending failed
                echo "<script>alert('Message sending failed!');</script>";
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>ContactUS | INDASARA MAHA VIDYALAYA</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap link -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Lightbox CSS -->
  <link rel="stylesheet" href="lightbox2-dev/dist/css/lightbox.min.css" />
  <!-- Stylesheet -->
  <link rel="stylesheet" href="CSS/style.css" />
  
</head>
<body>

<?php include 'Includes/NavBar.php'; ?>

<div class="container bannerImg mt-5 mb-5">
    <div class="row">
        <div class="col-md-12">
            <div class="center-content">
                <div class="center-content-inner">
                    <h1 class="heading">Contact Us</h1>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <h6 class="fontMontserrat">Get In Touch</h6>
                        <p>We are here to help you. If you have any questions or need any information, feel free to contact us.</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <!-- Form -->
                        <h6 class="fontMontserrat">Send Us A Message</h6>
                        <?php
                            if ($errorMessage != "") {
                                echo "<div class='alert alert-danger' role='alert'>";
                                echo $errorMessage;
                                echo "</div>";
                            }
                        ?>
                        <form action="#" method="post">
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="contact" class="form-label">Contact No</label>
                                <input type="text" class="form-control" id="contact" name="contact" required>
                            </div>
                            <div class="mb-3">
                                <label for="date" class="form-label">Date</label>
                                <input type="date" class="form-control" id="date" name="date" required>
                            </div>
                            <div class="mb-3">
                                <label for="message" class="form-label">Message</label>
                                <textarea class="form-control" id="message" name="message" rows="3" required></textarea>
                            </div>
                            <button type="submit" class="btnStyle1">Send</button>
                        </form>
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-12">
                                <h6 class="fontMontserrat">INDASARA MAHA Vidyalaya</h6>
                                <img src="Images/contact.jpg" style="width: 100%;" alt="Contact Image">
                                <h4 style="text-align: center;">Sujatha Vidyalaya</h4>
                                <p style="text-align: center;">Talabara, Matara, Sri Lanka.<br><a href="mailto:principal@sujathavidyalaya.org">principal@indasaramahavidyalaya.org</a> | <a href="mailto:it@sujathavidyalaya.org">it@indasaramahavidyalaya.org</a><br><a href="tel:+94412222313">+94 - 412 222 313</a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container mb-5">
    <div class="row">
        <div class="col-md-12">
                <div class="center-content-inner">
                    <h1 class="heading">Location</h1>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3959.073366073073!2d80.5600733147747!3d5.948366195800073!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3ae1b7b1b1b1b1b1%3A0x3b1b1b1b1b1b1b1b1!2sIndasara%20Maha%20Vidyalaya!5e0!3m2!1sen!2slk!4v1633663660001!5m2!1sen!2slk" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                    </div>
                </div>
        </div>
    </div>
</div>

<?php include 'Includes/Footer.php'; ?>