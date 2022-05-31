<?php
require('config.php');
if (isset($_POST['register'])) {
    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $email = $_POST['email'];
    $email = filter_var($email, FILTER_SANITIZE_STRING);
    $password =md5($_POST['password']);
    $password = filter_var($password, FILTER_SANITIZE_STRING);
    $conf_password =md5($_POST['conf_password']);
    $conf_password = filter_var($conf_password, FILTER_SANITIZE_STRING);
    $image = $_FILES['image']['name'];
    $temp_image = $_FILES['image']['tmp_name'];

    $select_all_query = "SELECT * FROM `users` WHERE email=?";
    $select = $connection->prepare($select_all_query);
    $select->execute([$email]);
    if ($select->rowCount() > 0) {
        $message[] = "email is already exist!";
    } else {
        if ($password != $conf_password) {
            $message[] = "confirm password does not match!";
        } else {
            if (!empty($_POST['name']) && !empty($_POST['email']) && !empty($_POST['password'])) {
                $insert_data_query = "INSERT INTO `users`(`name`, `email`, `password`, `user_img`) VALUES (?,?,?,?)";
                $insert = $connection->prepare($insert_data_query);
                $insert->execute([$name, $email, $password, $image]);
                if ($insert) {
                    move_uploaded_file($temp_image, "uploaded_imgs/$image");
                    $message[] = "registeration successfully!";
                    header('location:login.php');
                }
            }else{
                $message[] = "all inputs must be filled!";

            }
        }
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

        <form action="" method="POST" enctype="multipart/form-data">
            <h3>register now</h3>
            <input type="text" name="name" id="name" placeholder="enter your name" required class="box">
            <input type="email" name="email" id="email" placeholder="enter your email" required class="box" autocomplete="off">
            <input type="password" name="password" id="password" placeholder="enter your password" required class="box" autocomplete="off">
            <input type="password" name="conf_password" id="conf-password" placeholder="re-write password" required class="box">
            <input type="file" name="image" id="image" required class="box">
            <p>already have an account <a href="login.php">login</a></p>
            <input type="submit" name="register" id="register" value="submit" class="btn">


        </form>
    </section>
</body>

</html>