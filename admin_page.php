<?php
include "config.php";
session_start();
$admin_id = $_SESSION["admin_id"];
if (!isset($admin_id)) {
    header("location:login.php");
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>admin profile page</title>
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
            margin:0.5rem;
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

        .profile_container {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 2rem;
            min-height: 100vh;
        }

        .profile_container .profile {
            width: 40rem;
            border-radius: 0.5rem;
            background-color: var(--black);
            padding: 2rem;
            text-align: center;
        }

        .profile_container .profile img {
            height: 20rem;
            width: 20rem;
            margin-bottom: 0.5rem;
            border-radius: 50%;
            object-fit: cover;
        }
        .profile_container .profile h3{
            font-size: 2.5rem;
            padding: 0.5rem 0rem;
            color: var(--white);
        }
    </style>
</head>

<body>

    <h1 class="title"><span>admin</span> profile page</h1>
    <section class="profile_container">
        <?php
        $select_data_query = 'SELECT * FROM `users` WHERE id=?';
        $select = $connection->prepare($select_data_query);
        $select->execute([$admin_id]);
        $fetch_row = $select->fetch(PDO::FETCH_ASSOC);
        ?>
        <div class="profile">
            <img src="uploaded_imgs/<?= $fetch_row['user_img'] ?>" alt="">
            <h3><?= $fetch_row['name'] ?></h3>
            <a href="admin_profile_update.php" class="btn">update your profile</a>
            <a href="logout.php" class="delete-btn">logout</a>
            <div class="flex-btn">
                <a href="login.php" class="option-btn">login</a>
                <a href="register.php" class="option-btn">register</a>
            </div>
        </div>

    </section>
</body>

</html>