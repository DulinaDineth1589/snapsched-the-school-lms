<!DOCTYPE html>
<html lang="en">
<head>
  <title>INDASARA MAHA VIDYALAYA | HOME</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstap link -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Lightbox CSS -->
  <link rel="stylesheet" href="lightbox2-dev/dist/css/lightbox.min.css" />
  <!-- stylesheet -->
  <link rel="stylesheet" href="CSS/style.css" />
  
</head>

<body>

  <script>
    function studentLogin(){
      window.location.href = "StudentLogin.html";
    }
    function teacherLogin(){
      window.location.href = "TeacherLogin.html";
    }
  </script>

    <!-- nav bar -->
  <nav class="subNavBar navbar navbar-expand-sm fixed-top justify-content-center">
    <div class="container">
        <p class="title1">Proudly Celebrating It's 83th Anniversary...</p>
        <div class="ml-auto">
          <button onclick="studentLogin()" class="btnStyle mx-2" type="button">Student</button>
          <button onclick="teacherLogin()" class="btnStyle mx-2" type="button">Teacher</button>
        </div>
    </div>
  </nav>

  <nav class="navBar navbar navbar-expand-sm fixed-top justify-content-center">
    <div class="container">
        <a class="navbar-brand" href="#">
            <img src="Images/SnapSchetLogo.png" alt="Logo" style="width:170px; height: auto;">
        </a>
        <div class="collapse navbar-collapse justify-content-center" id="homeNavBar">
            <ul class="navLink">
                <li class="nav-item">
                    <a class="nav-link" href="HomePage.php"><p class="fontWhite" style="font-size: 13px; font-weight: bold;">Home</p></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="HomeAboutSchool.php"><p class="fontWhite" style="font-size: 13px; font-weight: bold;">About school</p></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="HomeAdministration.php"><p class="fontWhite" style="font-size: 13px; font-weight: bold;">Administration</p></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="HomeGallery.php"><p class="fontWhite" style="font-size: 13px; font-weight: bold;">Gallery</p></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="HomeContactUS.php"><p class="fontWhite" style="font-size: 13px; font-weight: bold;">Contact US</p></a>
                </li>
            </ul>
        </div>
        <form class="d-flex">
            <input class="form-control me-2" id="searchBar" type="text" style="border-color: rgb(0,35,135);;" placeholder="Search">
            <a class="navbar-brand" href="#">
                <img id="btnsearch" src="Images/search-icon.png" alt="Search" style="width:15px;">
            </a>
        </form>
    </div>
   </nav>

    <!-- carousel -->
    <div id="demo" class="carousel slide" data-bs-ride="carousel">
      <div class="carousel-inner">
        <div class="carousel-item active">
          <img src="Images/School16.jpg" alt="Los Angeles" class="d-block w-100" style="height: 700px;">
          <div class="carousel-caption">
            <h1 class="fontMontserratHead">THALARAMBA SRI INDASARA MAHA VIDYALAYA.</h1>
            <p class="fontMontserrat">නැණ ගුණ වඩා ඉදිරියටම යව්!</p>
          </div>
        </div>
        <div class="carousel-item active">
          <img src="Images/School2.jpg" alt="Los Angeles" class="d-block w-100" style="height: 700px;">
          <div class="carousel-caption">
            <h1 class="fontMontserratHead">THALARAMBA SRI INDASARA MAHA VIDYALAYA.</h1>
            <p class="fontMontserrat">නැණ ගුණ වඩා ඉදිරියටම යව්!</p>
          </div>
        </div>
        <div class="carousel-item active">
          <img src="Images/OATH1.jpg" alt="Los Angeles" class="d-block w-100" style="height: 700px;">
          <div class="carousel-caption">
            <h1 class="fontMontserratHead">THALARAMBA SRI INDASARA MAHA VIDYALAYA.</h1>
            <p class="fontMontserrat">නැණ ගුණ වඩා ඉදිරියටම යව්!</p>
          </div>
        </div>
        <div class="carousel-item active">
          <img src="Images/OATH6.jpg" alt="Los Angeles" class="d-block w-100" style="height: 700px;">
          <div class="carousel-caption">
            <h1 class="fontMontserratHead">THALARAMBA SRI INDASARA MAHA VIDYALAYA.</h1>
            <p class="fontMontserrat">නැණ ගුණ වඩා ඉදිරියටම යව්!</p>
          </div>
        </div>
      </div>
      
      <!-- Left and right controls/icons -->
      <button class="carousel-control-prev" type="button" data-bs-target="#demo" data-bs-slide="prev">
        <span class="carousel-control-prev-icon"></span>
      </button>
      <button class="carousel-control-next" type="button" data-bs-target="#demo" data-bs-slide="next">
        <span class="carousel-control-next-icon"></span>
      </button>
    </div>
  
    <!-- school intro -->
    <div class="container mt-5 detailsBox">
      <div class="row">
        <div class="col">
          <h1 class="header01">Welcome to Thalaramba Sri Indasara Maha Vidyalaya</h1>
          <p class="paraFont">Thalaramba Sri Indasara Maha Vidyalaya is a mix school located in Thalaramba, Matara, Sri Lanka. Started by Rev Thalaramba Indasara in 20th January of 1941. After that in 1942 the school was taken over by the government. At that time the school area was 117 perches. Although the school was named as Thalaramba Indasara vidyalaya in 1966, minister by Thudawas intervened and made it as Thalaramba Indasara Maha Vidyalaya in 1996.<br><br> Back in 2012, this school was converted in to a primary school and grade 6-11 were started in this school with the dedication of the then principal Mr. Laganal Hettiarachchi and the vice principal Rev. Kamburagamuwa. <br><br>Thalaramba Indasara Vidyalaya developed in this way has now managed to produce students who have achieved many achievements as well as in external fields such as sports. The dedication of the teaching staff to raise the school like this is satisfactory.</p>
        </div>
        <div class="col text-center">
          <img src="Images/School1.jpg" alt="School" style="width:60%; height: auto;">
        </div>
      </div>
    </div>

    <!-- school intro -->
    <div class="container mt-5 detailsBox">
      <div class="row">
        <div class="col-md-6">
          <h1 class="header01">Our Vision</h1>
          <p class="paraFont">To inherit a generation of intelligent students full of values and virtues.</p>
        </div>
        <div class="col-md-6">
          <h1 class="header01">Our Mission</h1>
          <p class="paraFont">It is our mission to present a generation full of skills and good life habits to be useful to themselves and the society while staying in a disciplined enviornment and respecting other parties in the society.</p>
        </div>
      </div>
    </div>

    <!-- about principal -->
    <div class="container mt-5 detailsBox">
      <div class="row">
        <div class="col-md-6 text-center">
          <img src="Images/Principle.jpg" alt="Principal" style="width:60%; height: auto;">
        </div>
        <div class="col-md-6">
          <h1 class="header01">About Principal</h1>
          <p class="paraFont">Mr. K. A. D. S. K. Kamburagamuwa is the current principal of Thalaramba Sri Indasara Maha Vidyalaya. He has been serving as the principal of the school since 2018. He has been a teacher for more than 20 years and has been serving as the principal of the school for the past 3 years. He has been able to bring the school to a high level of success with the dedication of the teachers and the students.</p>
        </div>
      </div>
    </div>


    <!-- news and events -->
    <div class="container mt-5">
      <div class="project-title pb-5">
        <h1 class="header01">News and Events</h1>
      </div>
      <div class="row">
        <div class="col-md-4">
          <div class="card" style="width: 100%;">
            <img src="Images/OATH6.jpg" class="card-img-top" alt="News1">
            <div class="card-body">
              <h5 class="card-title">OAth Taking Ceremony</h5>
              <p class="card-text">Oath taking ceremony of the new student council was held at the school auditorium.</p>
              <a href="#" class="btnStyle1">Read More</a>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card" style="width: 100%;">
            <img src="Images/Vesak8.jpg" class="card-img-top" alt="News2">
            <div class="card-body">
              <h5 class="card-title">Veask Celebrations</h5>
              <p class="card-text">Vesak celebrations were held at the school premises with the participation of students and teachers.</p>
              <a href="#" class="btnStyle1">Read More</a>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card" style="width: 100%;">
            <img src="Images/ChildrenDay1.jpg" class="card-img-top" alt="News3">
            <div class="card-body">
              <h5 class="card-title">Children's Day</h5>
              <p class="card-text">Children's day celebrations were held at the school with the participation of students and teachers.</p>
              <a href="#" class="btnStyle1">Read More</a>
            </div>
          </div>
        </div>
      </div>
    </div>

  <!-- sport phpto gallery-->
  <div class="container mt-5">
    <div class="project-title pb-5">
      <h1 class="header01">Photo Gallery</h1>
    </div>
  </div>
  <div class="grid-gallery">
    <div class="grid-item">
      <a href="Images/School16.jpg" data-lightbox="gridImage">
        <img src="Images/School16.jpg">
      </a>
    </div>
    <div class="grid-item">
      <a href="Images/ChildrenDay1.jpg" data-lightbox="gridImage">
        <img src="Images/ChildrenDay1.jpg">
      </a>
    </div>
    <div class="grid-item">
      <a href="Images/Logo.jpg" data-lightbox="gridImage">
        <img src="Images/Logo.jpg">
      </a>
    </div>
    <div class="grid-item">
      <a href="Images/OATH1.jpg" data-lightbox="gridImage">
        <img src="Images/OATH1.jpg">
      </a>
    </div>
    <div class="grid-item">
      <a href="Images/School1.jpg" data-lightbox="gridImage">
        <img src="Images/School1.jpg">
      </a>
    </div>
    <div class="grid-item">
      <a href="Images/Sportmeet2.jpg" data-lightbox="gridImage">
        <img src="Images/Sportmeet2.jpg">
      </a>
    </div>
    <div class="grid-item">
      <a href="Images/WestonBand.jpg" data-lightbox="gridImage">
        <img src="Images/WestonBand.jpg">
      </a>
    </div>
  </div>

  <!-- footer -->
  <?php include 'Includes/Footer.php'; ?>

  
    <!-- link Bootstap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- link Jquery -->
    <script src="JQuery/jquery-3.7.1.js"></script>
    <!-- jQuery CDN Link -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <!-- Lightbox JS -->
    <script src="lightbox2-dev/dist/js/lightbox.min.js"></script>
    <!-- Custom js -->
    <script src="js/main.js"></script>

    </body>
</html>
