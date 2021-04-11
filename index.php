<?php
include "db_connect.php";
session_start();
$id_user = 0;
$is_login = false;
$role = "user";

$name_category = "";

if (isset($_SESSION['user_id'])) {
    $is_login = true;
    $id_user = $_SESSION['user_id'];
    $data = $DBH->prepare("SELECT `role` FROM `user` WHERE `key` = ?");
    $data->execute([$id_user]);
    $result = $data->fetchAll();
    $role = $result[0]["role"];
}

if (isset($_SESSION['msg_error'])) {
    $msg_error = $_SESSION['msg_error'];
    unset($_SESSION['msg_error']);
}

if (isset($_GET['logout'])) {
    session_unset();
    header("location: http://cmit-asino.ml/index.php");
}

try {
$category = "";
    if (isset($_GET["category"])) {
        $data = $DBH->query("SELECT `key` FROM `categories` WHERE `category`='".$_GET["category"]."'");
        $data->setFetchMode(PDO::FETCH_ASSOC);

        while ($row = $data->fetch()) {
            $category = $row['key'];
        }
        $name_category = "category=".$_GET["category"];
    } else {
        $category = "1";
    }
} catch (PDOException $e) {
    echo $e->getMessage();
}

if (isset($_GET["u"]) && isset($_GET["i"]) && (isset($_GET["l"]) || isset($_GET["d"]))) {
    $id_photo = $_GET["i"];
    if (isset($_GET["l"])) {
        $l = 1;
    } else {
        $l = -1;
    }
    $data = $DBH->prepare("SELECT l FROM `like` WHERE (`id_user` = ?) AND (`id_photo` = ?)");
    $data->execute([$id_user, $id_photo]);
    $result = $data->fetchAll();
    $c = $data->rowCount();
    if ($c == 0) {
        $data = $DBH->prepare("INSERT INTO `like` (`key`, `id_user`, `id_photo`, `l`) VALUES (NULL, ?, ?, ?)");
        $data->execute([$id_user, $id_photo, $l]);
    } else {
        $l_db = $result[0]["l"];
        if ($l==$l_db) {
            //Удаление
            $data = $DBH->prepare("DELETE FROM `like` WHERE (`id_user` = ?) AND (`id_photo` = ?)");
            $data->execute([$id_user, $id_photo]);
        } else {
            //Смена
            $data = $DBH->prepare("UPDATE `like` SET `l`=? WHERE (`id_user` = ?) AND (`id_photo` = ?)");
            $data->execute([$l, $id_user, $id_photo]);
        }
    }
    if (isset($_GET["category"])) {
        header("location: http://cmit-asino.ml/index.php?".$name_category."#f".$id_photo);
    } else {
        header("location: http://cmit-asino.ml/index.php#f".$id_photo);
    }
}

if (isset($_POST["check-theme"])) {
    $data = $DBH->prepare("SELECT COUNT(`theme`) AS `c` FROM `colors` WHERE (`id_user` = ?) AND (`id_page` = ?)");
    $data->execute([$id_user, $category]);
    $result = $data->fetchAll();
    $c = $result[0]["c"];
    if ($c == 0) {
        //insert
    } else {
        //update
    }
}

if (isset($_POST["id_photo"]) && isset($_POST["status"])) {
    var_dump($_POST);
}

?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/all.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ekko-lightbox/5.3.0/ekko-lightbox.css" integrity="sha512-Velp0ebMKjcd9RiCoaHhLXkR1sFoCCWXNp6w4zj1hfMifYB5441C+sKeBl/T/Ka6NjBiRfBBQRaQq65ekYz3UQ==" crossorigin="anonymous" />
    <link rel="stylesheet" href="css/style.css">
    <title>
        <?php
        if ($category == 1) {
            echo "Котики";
        } elseif ($category == 3) {
            echo "Хомяки";
        } elseif ($category == 5) {
            echo "Дракоши";
        }
        ?>
    </title>
</head>
<body class="bg-dark" id="cat-page">
<!-- Body-->
<!-- Шарики-->
<!-- новогодняя мотня newyear.html -->
<!--
<div class="fixed-top b-page_newyear bg-primary">
    <div class="b-page__content">
        <i class="b-head-decor">
            <i class="b-head-decor__inner b-head-decor__inner_n1">
                <div class="b-ball b-ball_n1 b-ball_bounce" data-note="0"><div class="b-ball__right"></div><div class="b-ball__i"></div></div>
                <div class="b-ball b-ball_n2 b-ball_bounce" data-note="1"><div class="b-ball__right"></div><div class="b-ball__i"></div></div>
                <div class="b-ball b-ball_n3 b-ball_bounce" data-note="2"><div class="b-ball__right"></div><div class="b-ball__i"></div></div>
                <div class="b-ball b-ball_n4 b-ball_bounce" data-note="3"><div class="b-ball__right"></div><div class="b-ball__i"></div></div>
                <div class="b-ball b-ball_n5 b-ball_bounce" data-note="4"><div class="b-ball__right"></div><div class="b-ball__i"></div></div>
                <div class="b-ball b-ball_n6 b-ball_bounce" data-note="5"><div class="b-ball__right"></div><div class="b-ball__i"></div></div>
                <div class="b-ball b-ball_n7 b-ball_bounce" data-note="6"><div class="b-ball__right"></div><div class="b-ball__i"></div></div>
                <div class="b-ball b-ball_n8 b-ball_bounce" data-note="7"><div class="b-ball__right"></div><div class="b-ball__i"></div></div>
                <div class="b-ball b-ball_n9 b-ball_bounce" data-note="8"><div class="b-ball__right"></div><div class="b-ball__i"></div></div>
                <div class="b-ball b-ball_i1"><div class="b-ball__right"></div><div class="b-ball__i"></div></div>
                <div class="b-ball b-ball_i2"><div class="b-ball__right"></div><div class="b-ball__i"></div></div>
                <div class="b-ball b-ball_i3"><div class="b-ball__right"></div><div class="b-ball__i"></div></div>
                <div class="b-ball b-ball_i4"><div class="b-ball__right"></div><div class="b-ball__i"></div></div>
                <div class="b-ball b-ball_i5"><div class="b-ball__right"></div><div class="b-ball__i"></div></div>
                <div class="b-ball b-ball_i6"><div class="b-ball__right"></div><div class="b-ball__i"></div></div>
            </i>
            <i class="b-head-decor__inner b-head-decor__inner_n2">
                <div class="b-ball b-ball_n1 b-ball_bounce" data-note="9"><div class="b-ball__right"></div><div class="b-ball__i"></div></div>
                <div class="b-ball b-ball_n2 b-ball_bounce" data-note="10"><div class="b-ball__right"></div><div class="b-ball__i"></div></div>
                <div class="b-ball b-ball_n3 b-ball_bounce" data-note="11"><div class="b-ball__right"></div><div class="b-ball__i"></div></div>
                <div class="b-ball b-ball_n4 b-ball_bounce" data-note="12"><div class="b-ball__right"></div><div class="b-ball__i"></div></div>
                <div class="b-ball b-ball_n5 b-ball_bounce" data-note="13"><div class="b-ball__right"></div><div class="b-ball__i"></div></div>
                <div class="b-ball b-ball_n6 b-ball_bounce" data-note="14"><div class="b-ball__right"></div><div class="b-ball__i"></div></div>
                <div class="b-ball b-ball_n7 b-ball_bounce" data-note="15"><div class="b-ball__right"></div><div class="b-ball__i"></div></div>
                <div class="b-ball b-ball_n8 b-ball_bounce" data-note="16"><div class="b-ball__right"></div><div class="b-ball__i"></div></div>
                <div class="b-ball b-ball_n9 b-ball_bounce" data-note="17"><div class="b-ball__right"></div><div class="b-ball__i"></div></div>
                <div class="b-ball b-ball_i1"><div class="b-ball__right"></div><div class="b-ball__i"></div></div>
                <div class="b-ball b-ball_i2"><div class="b-ball__right"></div><div class="b-ball__i"></div></div>
                <div class="b-ball b-ball_i3"><div class="b-ball__right"></div><div class="b-ball__i"></div></div>
                <div class="b-ball b-ball_i4"><div class="b-ball__right"></div><div class="b-ball__i"></div></div>
                <div class="b-ball b-ball_i5"><div class="b-ball__right"></div><div class="b-ball__i"></div></div>
                <div class="b-ball b-ball_i6"><div class="b-ball__right"></div><div class="b-ball__i"></div></div>
            </i>
            <i class="b-head-decor__inner b-head-decor__inner_n3">
                <div class="b-ball b-ball_n1 b-ball_bounce" data-note="18"><div class="b-ball__right"></div><div class="b-ball__i"></div></div>
                <div class="b-ball b-ball_n2 b-ball_bounce" data-note="19"><div class="b-ball__right"></div><div class="b-ball__i"></div></div>
                <div class="b-ball b-ball_n3 b-ball_bounce" data-note="20"><div class="b-ball__right"></div><div class="b-ball__i"></div></div>
                <div class="b-ball b-ball_n4 b-ball_bounce" data-note="21"><div class="b-ball__right"></div><div class="b-ball__i"></div></div>
                <div class="b-ball b-ball_n5 b-ball_bounce" data-note="22"><div class="b-ball__right"></div><div class="b-ball__i"></div></div>
                <div class="b-ball b-ball_n6 b-ball_bounce" data-note="23"><div class="b-ball__right"></div><div class="b-ball__i"></div></div>
                <div class="b-ball b-ball_n7 b-ball_bounce" data-note="24"><div class="b-ball__right"></div><div class="b-ball__i"></div></div>
                <div class="b-ball b-ball_n8 b-ball_bounce" data-note="25"><div class="b-ball__right"></div><div class="b-ball__i"></div></div>
                <div class="b-ball b-ball_n9 b-ball_bounce" data-note="26"><div class="b-ball__right"></div><div class="b-ball__i"></div></div>
                <div class="b-ball b-ball_i1"><div class="b-ball__right"></div><div class="b-ball__i"></div></div>
                <div class="b-ball b-ball_i2"><div class="b-ball__right"></div><div class="b-ball__i"></div></div>
                <div class="b-ball b-ball_i3"><div class="b-ball__right"></div><div class="b-ball__i"></div></div>
                <div class="b-ball b-ball_i4"><div class="b-ball__right"></div><div class="b-ball__i"></div></div>
                <div class="b-ball b-ball_i5"><div class="b-ball__right"></div><div class="b-ball__i"></div></div>
                <div class="b-ball b-ball_i6"><div class="b-ball__right"></div><div class="b-ball__i"></div></div>
            </i>
            <i class="b-head-decor__inner b-head-decor__inner_n4">
                <div class="b-ball b-ball_n1 b-ball_bounce" data-note="27"><div class="b-ball__right"></div><div class="b-ball__i"></div></div>
                <div class="b-ball b-ball_n2 b-ball_bounce" data-note="28"><div class="b-ball__right"></div><div class="b-ball__i"></div></div>
                <div class="b-ball b-ball_n3 b-ball_bounce" data-note="29"><div class="b-ball__right"></div><div class="b-ball__i"></div></div>
                <div class="b-ball b-ball_n4 b-ball_bounce" data-note="30"><div class="b-ball__right"></div><div class="b-ball__i"></div></div>
                <div class="b-ball b-ball_n5 b-ball_bounce" data-note="31"><div class="b-ball__right"></div><div class="b-ball__i"></div></div>
                <div class="b-ball b-ball_n6 b-ball_bounce" data-note="32"><div class="b-ball__right"></div><div class="b-ball__i"></div></div>
                <div class="b-ball b-ball_n7 b-ball_bounce" data-note="33"><div class="b-ball__right"></div><div class="b-ball__i"></div></div>
                <div class="b-ball b-ball_n8 b-ball_bounce" data-note="34"><div class="b-ball__right"></div><div class="b-ball__i"></div></div>
                <div class="b-ball b-ball_n9 b-ball_bounce" data-note="35"><div class="b-ball__right"></div><div class="b-ball__i"></div></div>
                <div class="b-ball b-ball_i1"><div class="b-ball__right"></div><div class="b-ball__i"></div></div>
                <div class="b-ball b-ball_i2"><div class="b-ball__right"></div><div class="b-ball__i"></div></div>
                <div class="b-ball b-ball_i3"><div class="b-ball__right"></div><div class="b-ball__i"></div></div>
                <div class="b-ball b-ball_i4"><div class="b-ball__right"></div><div class="b-ball__i"></div></div>
                <div class="b-ball b-ball_i5"><div class="b-ball__right"></div><div class="b-ball__i"></div></div>
                <div class="b-ball b-ball_i6"><div class="b-ball__right"></div><div class="b-ball__i"></div></div>
            </i>
            <i class="b-head-decor__inner b-head-decor__inner_n5">
                <div class="b-ball b-ball_n1 b-ball_bounce" data-note="0"><div class="b-ball__right"></div><div class="b-ball__i"></div></div>
                <div class="b-ball b-ball_n2 b-ball_bounce" data-note="1"><div class="b-ball__right"></div><div class="b-ball__i"></div></div>
                <div class="b-ball b-ball_n3 b-ball_bounce" data-note="2"><div class="b-ball__right"></div><div class="b-ball__i"></div></div>
                <div class="b-ball b-ball_n4 b-ball_bounce" data-note="3"><div class="b-ball__right"></div><div class="b-ball__i"></div></div>
                <div class="b-ball b-ball_n5 b-ball_bounce" data-note="4"><div class="b-ball__right"></div><div class="b-ball__i"></div></div>
                <div class="b-ball b-ball_n6 b-ball_bounce" data-note="5"><div class="b-ball__right"></div><div class="b-ball__i"></div></div>
                <div class="b-ball b-ball_n7 b-ball_bounce" data-note="6"><div class="b-ball__right"></div><div class="b-ball__i"></div></div>
                <div class="b-ball b-ball_n8 b-ball_bounce" data-note="7"><div class="b-ball__right"></div><div class="b-ball__i"></div></div>
                <div class="b-ball b-ball_n9 b-ball_bounce" data-note="8"><div class="b-ball__right"></div><div class="b-ball__i"></div></div>
                <div class="b-ball b-ball_i1"><div class="b-ball__right"></div><div class="b-ball__i"></div></div>
                <div class="b-ball b-ball_i2"><div class="b-ball__right"></div><div class="b-ball__i"></div></div>
                <div class="b-ball b-ball_i3"><div class="b-ball__right"></div><div class="b-ball__i"></div></div>
                <div class="b-ball b-ball_i4"><div class="b-ball__right"></div><div class="b-ball__i"></div></div>
                <div class="b-ball b-ball_i5"><div class="b-ball__right"></div><div class="b-ball__i"></div></div>
                <div class="b-ball b-ball_i6"><div class="b-ball__right"></div><div class="b-ball__i"></div></div>
            </i>
            <i class="b-head-decor__inner b-head-decor__inner_n6">
                <div class="b-ball b-ball_n1 b-ball_bounce" data-note="9"><div class="b-ball__right"></div><div class="b-ball__i"></div></div>
                <div class="b-ball b-ball_n2 b-ball_bounce" data-note="10"><div class="b-ball__right"></div><div class="b-ball__i"></div></div>
                <div class="b-ball b-ball_n3 b-ball_bounce" data-note="11"><div class="b-ball__right"></div><div class="b-ball__i"></div></div>
                <div class="b-ball b-ball_n4 b-ball_bounce" data-note="12"><div class="b-ball__right"></div><div class="b-ball__i"></div></div>
                <div class="b-ball b-ball_n5 b-ball_bounce" data-note="13"><div class="b-ball__right"></div><div class="b-ball__i"></div></div>
                <div class="b-ball b-ball_n6 b-ball_bounce" data-note="14"><div class="b-ball__right"></div><div class="b-ball__i"></div></div>
                <div class="b-ball b-ball_n7 b-ball_bounce" data-note="15"><div class="b-ball__right"></div><div class="b-ball__i"></div></div>
                <div class="b-ball b-ball_n8 b-ball_bounce" data-note="16"><div class="b-ball__right"></div><div class="b-ball__i"></div></div>
                <div class="b-ball b-ball_n9 b-ball_bounce" data-note="17"><div class="b-ball__right"></div><div class="b-ball__i"></div></div>
                <div class="b-ball b-ball_i1"><div class="b-ball__right"></div><div class="b-ball__i"></div></div>
                <div class="b-ball b-ball_i2"><div class="b-ball__right"></div><div class="b-ball__i"></div></div>
                <div class="b-ball b-ball_i3"><div class="b-ball__right"></div><div class="b-ball__i"></div></div>
                <div class="b-ball b-ball_i4"><div class="b-ball__right"></div><div class="b-ball__i"></div></div>
                <div class="b-ball b-ball_i5"><div class="b-ball__right"></div><div class="b-ball__i"></div></div>
                <div class="b-ball b-ball_i6"><div class="b-ball__right"></div><div class="b-ball__i"></div></div>
            </i>
            <i class="b-head-decor__inner b-head-decor__inner_n7">
                <div class="b-ball b-ball_n1 b-ball_bounce" data-note="18"><div class="b-ball__right"></div><div class="b-ball__i"></div></div>
                <div class="b-ball b-ball_n2 b-ball_bounce" data-note="19"><div class="b-ball__right"></div><div class="b-ball__i"></div></div>
                <div class="b-ball b-ball_n3 b-ball_bounce" data-note="20"><div class="b-ball__right"></div><div class="b-ball__i"></div></div>
                <div class="b-ball b-ball_n4 b-ball_bounce" data-note="21"><div class="b-ball__right"></div><div class="b-ball__i"></div></div>
                <div class="b-ball b-ball_n5 b-ball_bounce" data-note="22"><div class="b-ball__right"></div><div class="b-ball__i"></div></div>
                <div class="b-ball b-ball_n6 b-ball_bounce" data-note="23"><div class="b-ball__right"></div><div class="b-ball__i"></div></div>
                <div class="b-ball b-ball_n7 b-ball_bounce" data-note="24"><div class="b-ball__right"></div><div class="b-ball__i"></div></div>
                <div class="b-ball b-ball_n8 b-ball_bounce" data-note="25"><div class="b-ball__right"></div><div class="b-ball__i"></div></div>
                <div class="b-ball b-ball_n9 b-ball_bounce" data-note="26"><div class="b-ball__right"></div><div class="b-ball__i"></div></div>
                <div class="b-ball b-ball_i1"><div class="b-ball__right"></div><div class="b-ball__i"></div></div>
                <div class="b-ball b-ball_i2"><div class="b-ball__right"></div><div class="b-ball__i"></div></div>
                <div class="b-ball b-ball_i3"><div class="b-ball__right"></div><div class="b-ball__i"></div></div>
                <div class="b-ball b-ball_i4"><div class="b-ball__right"></div><div class="b-ball__i"></div></div>
                <div class="b-ball b-ball_i5"><div class="b-ball__right"></div><div class="b-ball__i"></div></div>
                <div class="b-ball b-ball_i6"><div class="b-ball__right"></div><div class="b-ball__i"></div></div>
            </i>
        </i>
    </div>
</div>
-->
<!-- Шарики кончились-->
<div class="container">
    <h1 class="text-center text-light">
        <?php
            if ($category == 1) {
                echo "Котики";
            } elseif ($category == 3) {
                echo "Хомяки";
            } elseif ($category == 5) {
                echo "Дракоши";
            }
        ?>
    </h1>
    <div class="row">
    <!-- <div class="row row-cols-3"> -->
<?php
    try {
        $data = "";
        if ($role == "admin") {
            $data = $DBH->query("SELECT `key`, `name`, `disc` FROM photos WHERE id_cat_name=".$category);
            $data->setFetchMode(PDO::FETCH_ASSOC);
        } elseif ($role == "user") {
            $data = $DBH->query("SELECT `key`, `name`, `disc` FROM photos WHERE (id_cat_name=".$category.") AND (`public`=1)");
            $data->setFetchMode(PDO::FETCH_ASSOC);
        }

        $i = 1;
        while ($row = $data->fetch()) {
            if (($i % 3) == 1) {
                echo '<div class="card-deck">';
            }

            $d = $DBH->prepare("SELECT l FROM `like` WHERE (`id_user` = ?) AND (`id_photo` = ?)");
            $d->execute([$id_user, $row['key']]);
            $r = $d->fetchAll();
            $c = $d->rowCount();
            if ($c == 0) {
                $icon_l = '<i class="far fa-thumbs-up"></i>';
                $icon_d = '<i class="far fa-thumbs-down"></i>';
            } else {
                if ($r[0]["l"] == 1) {
                    $icon_l = '<i class="fas fa-thumbs-up"></i>';
                    $icon_d = '<i class="far fa-thumbs-down"></i>';
                } else {
                    $icon_l = '<i class="far fa-thumbs-up"></i>';
                    $icon_d = '<i class="fas fa-thumbs-down"></i>';
                }
            }

            //echo '<div class="col mb-4">';
            echo '<div class="card border-primary text-center h-100 shadow-lg-cat bg-white rounded" id="f'.$row['key'].'">';
            echo '<div class="card-body">';
            if ($role == "admin") {
                echo '<div class="box-img">';
                echo '<div class="check-public">';
                echo '<form action="/" name="f'.$row['key'].'">';
                echo '<input type="checkbox" id="c'.$row['key'].'" name="n'.$row['key'].'">';
                echo '</form>';
                echo '</div>';
            }
            echo '<a href="img/'.$row['name'].'" data-toggle="lightbox" data-title="Котейки" data-footer="'.$row['disc'].'">';
            echo '<img src="img/'.$row['name'].'" class="card-img-top" alt="...">';
            echo '<p class="card-text">'.$row['disc'].'</p>';
            echo '</a>';
            if ($role == "admin") {
                echo '</div>';
            }
            echo '</div>';
            echo '<div class="card-footer">';

            //demo like
            if ($is_login) {
                echo '<a class="text-primary like" href="?u='.$id_user.'&i='.$row['key'].'&l&'.$name_category.'".>';
				echo $icon_l;
				echo '	</a>';
            } else {
                echo '<i class="far fa-thumbs-up text-primary "></i>';
            }

            $sum_likes = $DBH->prepare("SELECT SUM(l) AS `sum_likes` FROM `like` WHERE `id_photo` = ?");
            $sum_likes->execute([$row['key']]);
            $sum_like = $sum_likes->fetchAll();

            if (isset($sum_like[0]["sum_likes"])) {
                echo " ".$sum_like[0]["sum_likes"]." ";
            } else {
                echo " 0 ";
            }

            //demo dislike
            if ($is_login) {
                echo '<a class="text-danger dislike" href="?u='.$id_user.'&i='.$row['key'].'&d&'.$name_category.'".>';
				echo $icon_d;
				echo '	</a>';
            } else {
                echo '<i class="far fa-thumbs-down text-danger disabled"></i>';
            }
            echo '</div>';
            echo '</div>';
            //echo '</div>';

            if (($i % 3) == 0) {
                echo '</div>';
            }
            $i++;
        }
        if (($i % 3) != 1) {
            echo '</div>';
        }

    } catch (PDOException $e) {
        echo $e->getMessage();
    }
?>
    </div>
</div>

<!-- footer -->
<footer>
    <div class="text-left fixed-bottom text-light"><h5>Ондрей Шошкав</h5></div>
</footer>

<!-- Navigation-->
<nav class="navbar navbar-dark fixed-top bg-primary"> <!--bg-primary-->
    <a class="navbar-brand" href="index.php">
        <i class="fas fa-images"></i> Фотогалерея
    </a>
    <div class="flex-row d-flex">
        <a class="nav-item nav-link text-light" href="index.php">Котейки</a>
        <a class="nav-item nav-link text-light" href="index.php?category=homka">Хомяки</a>
        <a class="nav-item nav-link text-light" href="index.php?category=dragons">Дракоши</a>
        <!--<a class="nav-item nav-link text-light" href="#" data-toggle="modal" data-target="#setting"><i class="fas fa-cogs"></i></a> -->
        <?php
            if ($is_login) {
                echo '<div class="btn-group">';
                echo '<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                echo '<i class="fas fa-user"></i>';
                echo '</button>';
                echo '<div class="dropdown-menu dropdown-menu-right">';
                echo '<a class="dropdown-item" href="#" data-toggle="modal" data-target="#setting"><i class="fas fa-cogs"></i> Настройки</a>';
                echo '<a class="dropdown-item" href="#" data-toggle="modal" data-target="#upload_img"><i class="fas fa-cogs"></i> Загрзить Фотачку</a>';
                echo '<a class="dropdown-item" href="?logout"><i class="fas fa-sign-out-alt"></i> Выход</a>';
                echo '</div>';
                echo '</div>';
            } else {
                echo '<a class="nav-item nav-link text-light" href="#" data-toggle="modal" data-target="#login"><i class="fas fa-sign-in-alt"></i></a>';
            }
        ?>
    </div>
</nav>

<!-- Modal Setting -->
<div class="modal fade" id="setting" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="index.php" method="post">
                <div class="modal-header">
                    <h5 class="modal-title">Заголовок</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>

                </div>
                <div class="modal-body">
                    <p>
                        
                        Укажите цвет элемента: <input type="color" name="bg" value="#ff0000" id="changeItemColor">
                    </p>
                    <a href="Тест.php">Открыть Профиль Пользователя</a>
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" id="check-theme">
                        <label class="custom-control-label" for="check-theme">Темная тема</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <footer>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                        <button type="submit" class="btn btn-primary">Сохранить изменения</button>
                    </footer>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Modal Login -->
<div class="modal fade" id="login" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Авторизация пользователя</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php
                if (isset($msg_error)) {
                  echo '<span class="text-danger error">';
                  echo $msg_error;
                  echo '</span>';
                }
                ?>
                <form action="login.php" method="post">
                    <div class="form-group">
                        <label for="InputLogin">Login<sup class="text-danger">*</sup></label>
                        <input type="text" class="form-control" id="InputLogin" required name="login">
                    </div>
                    <div class="form-group">
                        <label for="InputPassword">Password<sup class="text-danger">*</sup></label>
                        <input type="password" class="form-control" id="InputPassword" required name="password">
                    </div>
                    <button type="submit" class="btn btn-primary">Войти</button> или <a href="registration.php">Зарегистрироваться</a>
                </form>
            </div>
        </div>
    </div>
</div>
<!--Загрузка приколов-->
        <div class="modal fade" id="upload_img" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Загрузить новую картинку с эротикой</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="/" method="post">
                            <div class="form-group">
                                <label for="selectFile">Если реально это сделаешь = пожизненный бан на сайте<sup class="text-danger"></sup></label>
                                <input type="file" class="form-control" id="selectFile" required name="selectFile" accept=".jpg,.jpeg,.png">
                            </div>
                            <div class="form-group">
                                <label for="textImg">Описание<sup class="text-danger">*</sup></label>
                                <input type="text" class="form-control" id="textImg" required name="textImg">
                            </div>
                            <div class="form-group">
                                <select class="form-select" aria-label="Категория изображения" id="categoryImg" name="categoryImg">
                                    <option selected>Выбирете категорию...</option>
                                    <option value="1">Котейки</option>
                                    <option value="3">Хомяки</option>
                                    <option value="5">Гачимучи</option>
                                </select>
                                <input type="hidden" class="form-control">
                            </div>
                            <button type="submit" class="btn btn-primary" id="upload_file">Загрузить</button>
                            <button type="button" class="btn btn-outline-danger" data-dismiss="modal" aria-label="Close">Отмена</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

<script src="js/jquery-3.5.1.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/ekko-lightbox/5.3.0/ekko-lightbox.min.js" integrity="sha512-Y2IiVZeaBwXG1wSV7f13plqlmFOx8MdjuHyYFVoYzhyRr3nH/NMDjTBSswijzADdNzMyWNetbLMfOpIPl6Cv9g==" crossorigin="anonymous"></script>
<script src="js/script.js"></script>

</body>
</html>