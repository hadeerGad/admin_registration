<?php
require('config.php');
session_start();
if (isset($_POST['login'])) {

    $email = $_POST['email'];
    $email = filter_var($email, FILTER_SANITIZE_STRING);
    $password = md5($_POST['password']);
    $password = filter_var($password, FILTER_SANITIZE_STRING);
    $select_all_query = "SELECT * FROM `users` WHERE email=? AND  password =?";
    $select = $connection->prepare($select_all_query);
    $select->execute([$email, $password]);
    $row = $select->fetch(PDO::FETCH_ASSOC);
    if ($select->rowCount() > 0) {
        if ($row['user_type'] == "admin") {
            $_SESSION["admin_id"] = $row['id'];
            header("location:admin_page.php");
        } else if ($row['user_type'] == "user") {
            $_SESSION["user_id"] = $row['id'];
            header("location:user_page.php");
        }else{
        $message[] = "no user found!";

        }
    } else {
        $message[] = "incorrect email or password!";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>registeration form</title>
    <!-- font awesome cdn -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <!-- custome css file -->
    <!-- <link rel='stylesheet' type='text/css' href='style.php' /> -->
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <!-- <div class='message'>
            <span>$msg</span>
            <i class='fas fa-times' onclick='this.parentElement.remove();'></i>
        </div> -->
    <?php
    if (isset($message)) {
        foreach ($message as $msg) {
            echo "
            <div class='message'>
            <span>$msg</span>
            <i class='fas fa-times' onclick='this.parentElement.remove();'></i>
        </div>
            ";
        }
    }
    ?>
    <section class="form-container">

        <form action="" method="POST">
            <h3>login now</h3>
            <input type="email" name="email" id="email" placeholder="enter your email" required class="box" autocomplete="off">
            <input type="password" name="password" id="password" placeholder="enter your password" required class="box" autocomplete="off">
            <p>don't have an account <a href="register.php">register</a></p>
            <input type="submit" name="login" id="login" value="login" class="btn">


        </form>
    </section>
</body>

</html>