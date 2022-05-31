<?php
include "config.php";
session_start();
$user_id = $_SESSION["user_id"];
if (!isset($user_id)) {
    header("location:login.php");
};

if (isset($_POST['update'])) {
    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $email = $_POST['email'];
    $email = filter_var($email, FILTER_SANITIZE_STRING);
    $update_query = "UPDATE `users` SET `name`=?,`email`=? WHERE `id`=?";
    $update = $connection->prepare($update_query);
    $update->execute([$name, $email, $user_id]);
    $image = $_FILES['image']['name'];
    if (!empty($image)) {
        $old_img = $_POST['old_image'];
        $temp_image = $_FILES['image']['tmp_name'];
        $update_img_query = "UPDATE `users` SET `user_img`=? WHERE `id`=?";
        $update_img = $connection->prepare($update_img_query);
        $execute = $update_img->execute([$image, $user_id]);
        if ($execute) {
            move_uploaded_file($temp_image, "uploaded_imgs/$image");
            unlink("uploaded_imgs/$old_img");
            $message[] = "image has been updated";
        }
    }

if(!empty($old_password)){
    $old_password = $_POST["old_password"];
    $prev_password = md5($_POST['previous_password']);
    $new_password = md5($_POST["new_password"]);
    $confirm_password = md5($_POST['confirm_password']);

    if (!empty($prev_password) || !empty($new_password) || !empty($confirm_password)) {
        if ($old_password == $prev_password) {
            if ($new_password == $confirm_password) {
                $update_password_query = "UPDATE `users` SET `password`=? WHERE `id`=?";
                $update_password = $connection->prepare($update_password_query);
                $update = $update_password->execute([$new_password, $user_id]);
                if ($update) {
                    $message[] = "password is successfully updated !";
                }
            } else {
                $message[] = "confirm password don't match !";
            }
        } else {
            $message[] = "old password don't match !";
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
    <title>user profile page</title>
    <!-- font awesome cdn -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <!-- custome css file -->
    <!-- <link rel='stylesheet' type='text/css' href='style.php' /> -->
    <link rel="stylesheet" href="style.css">

    <!-- inline style -->
    <style>
        .btn,
        .delete-btn,
        .option-btn {
            margin: 0.5rem;
            padding: 1rem 3rem;
        }

        .title {
            text-align: center;
            padding: 1.5rem 1rem;
            background-color: var(--black);
            font-size: 3rem;
            text-transform: uppercase;
            color: var(--white);
        }

        .title span {
            color: var(--red);
        }

        .update_profile_container {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 2rem;
            min-height: 100vh;
        }

        .update_profile_container form {
            width: 70rem;
            border-radius: 0.5rem;
            background-color: var(--black);
            padding: 2rem;
            text-align: center;
        }

        .update_profile_container .flex {
            text-align: left;
            display: flex;
            justify-content: space-between;

        }

        .update_profile_container form .flex .input_box {
            width: 49%;

        }

        .update_profile_container form .flex .input_box span {
            font-size: 2rem;
            color: var(--light_color);
            display: block;
            padding-top: 1rem;
        }

        .update_profile_container form .flex .input_box .box {
            width: 100%;
            padding: 1.2rem 1.4rem;
            border-radius: 0.5rem;
            font-size: 1.8rem;
            color: var(--white);
            margin: 1rem 0rem;
            background-color: var(--light_bg);
        }

        .update_profile_container form img {
            height: 20rem;
            width: 20rem;
            margin-bottom: 0.5rem;
            border-radius: 50%;
            object-fit: cover;
        }

        @media (max-width:768px) {
            .update_profile_container form .flex .input_box {
                width: 100%;

            }
        }
    </style>
</head>

<body>
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
    <h1 class="title">update <span>user</span> profile page</h1>
    <section class="update_profile_container">
        <?php
        $select_data_query = 'SELECT * FROM `users` WHERE id=?';
        $select = $connection->prepare($select_data_query);
        $select->execute([$user_id]);
        $fetch_row = $select->fetch(PDO::FETCH_ASSOC);
        ?>
        <form action="" method="POST" enctype="multipart/form-data">
            <img src="uploaded_imgs/<?= $fetch_row['user_img']; ?>" alt="">
            <div class="flex">
                <div class="input_box">
                    <span>username:</span>
                    <input type="text" required class="box" name="name" placeholder="enter your name" value="<?= $fetch_row['name']; ?>">

                    <span>email:</span>
                    <input type="text" required class="box" name="email" placeholder="enter your email" value="<?= $fetch_row['email']; ?>">

                    <span>profile picture:</span>
                    <input type="hidden" name="old_image" value="<?= $fetch_row['user_img']; ?>">
                    <input type="file" class="box" name="image" accept="">
                </div>
                <div class="input_box">
                    <input type="hidden" name="old_password" value="<?= $fetch_row['password']; ?>">
                    <span>old password : </span>
                    <input type="password" class="box" name="previous_password" placeholder="enter your old password">

                    <span>new password : </span>
                    <input type="password" class="box" name="new_password" placeholder="enter your new password">

                    <span>confirm password : </span>
                    <input type="password" class="box" name="confirm_password" placeholder="confirm password">
                </div>


            </div>
            <div class="flex-btn">
                <input type="submit" value="update profile" name="update" class="btn">
                <a href="user_page.php" class="option-btn">go back</a>
            </div>
        </form>

    </section>
</body>

</html>