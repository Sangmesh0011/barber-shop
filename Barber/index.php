<?php
// Start the session (if not already started)
session_start();


?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <title>Barber Styles</title>
</head>
<style>
  body {
    margin: 0;
    padding: 0;
  }


  .banner {
    width: 100%;
    height: 70vh;
  }

  .banner img {
    margin-left: 0px;
    margin-top: 0px;
    height: 74vh;
    width: 100%;
  }

  .banner h1 {
    margin-top: -110px;
    color: white;
    margin-left: 40px;
    font-family: Impact, Haettenschweiler, 'Arial Narrow Bold', sans-serif;
  }

  .services {
    background-color: antiquewhite;
    margin-top: 10px;
    display: flex;
    justify-content: space-evenly;
    align-items: center;
  }

  .services .ser1 {
    padding: 30px 30px;
    font-family: fantasy;

  }

  body {
    margin: 0;
    padding: 0;
  }

  footer {
    background-color: #333;
    color: #fff;
    padding: 20px;
    text-align: center;
  }

  .footer-content {
    display: flex;
    flex-direction: column;
    align-items: center;
  }

  h4 {
    margin-bottom: 10px;
  }

  .social-links {
    display: flex;
    gap: 10px;
  }

  .social-links img {
    width: 30px;
    height: 30px;
  }

  
</style>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
  integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
<?php include "nav.php"?>
<body>
  
  </div>
  </div>
  </nav>
  <div class="banner">
    <img src="res/1.jpg" alt="" srcset="">
    <h1>Profesionality Delivered!</h1>
  </div>
  <br>
  <br>
  <br>
  <h3 style="margin-left: 45%;">Our Services</h3>
  <br>
  <div class="services">


    <div class="ser1">
      <img src="res/trimmer.png" height="80px" width="80px" alt="">
      <br>
      <br>
      <h4>Hair Cut</h4>
    </div>

    <div class="ser1">
      <img src="res/hair-color.png" height="80px" width="80px" alt="">
      <br>
      <br>
      <h4>Hair Color</h4>
    </div>

    <div class="ser1">
      <img src="res/makeup-kit.png" height="80px" width="80px" alt="">
      <br>
      <br>
      <h4>Make Up</h4>
    </div>

  </div>
</body>
<!DOCTYPE html>

<!-- Website content goes here -->

<!-- Footer -->
<footer>
  <div class="footer-content">
    <h4>Barber Styles</h4>
    <div class="social-links">
      <a href="#" target="_blank" rel="noopener noreferrer"><img src="res/facebook.png" alt="Facebook"></a>
      <a href="#" target="_blank" rel="noopener noreferrer"><img src="res/instagram.png" alt="Instagram"></a>
      <a href="#" target="_blank" rel="noopener noreferrer"><img src="res/twitter.png" alt="Twitter"></a>
    </div>
  </div>
</footer>




</html>