<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

</head>
<style>
.nav{
    color: white;
    width: 100%;
    height: 70px;
    background-color: black;
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 200px;
}
.nav .siglog{
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 10px;
}
.nav .siglog button{
    padding: 10px 20px;
}
.nav logo{
    font-size: 1.4em;
    font-size: bolder;
}
.nav .links{
    color: white;
    margin-left: -40px;
    display: flex;
    gap: 30px;
}
.nav .links a{
    color: white;
    text-decoration: none;
    font-size: 20px;
    margin: 20px;
    cursor: pointer;
   font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
}
.nam{
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 20px;
}
.nam->form.f1{
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 20px;
    margin-top: 20px;
}
.sl{
    display: flex;
    justify-content: center;
    align-items: center;
}
</style>

<body>
    <div class="nav">

        <logo>BARBER STYLES</logo>
        <div class="links">
            <div><a href="index.php">Home</a></div>
            <div> <a href="stores.php">Book</a></div>
            <div> <a href="about.php">About</a></div>
            <?php
            if (isset($_SESSION['user_id']) && isset($_SESSION['username'])) {
               echo '<div><a href="user_dash.php">Dashboard</a></div>';
            }
            ?>
            
        </div>
        <div class="siglog">

            <?php
            // Check if the user is logged in
            if (isset($_SESSION['user_id']) && isset($_SESSION['username'])) {
                // You can customize this part to display the user's symbol or name
                echo '<div class="nam">';
                echo '<span style="color: white;" class="me-2""><img src="res/prof_icon.png" width="28px" height="28px"> -- ' . $_SESSION['username'] . '</span>';
                echo '<form id="f1" action="logout.php" method="post">
                    <button type="submit">Log Out</button>
                    </form>';
                echo '</div>';
            } else {
                echo '<div class="sl">';
                echo '<form id="f2" action="signup.html" method="post">
                    <button type="submit">SIGN UP</button>
                    </form>';
                echo '<form id="f3" action="login.html" method="post">
                    <button type="submit">LOG IN</button>
                    </form>';
                echo '</div>';
            }
            ?>
        </div>
    </div>
</body>

</html>