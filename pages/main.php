<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="../images/logo.png">
    <title>Main Course</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="/cook/navbar2.css">
    <link rel="stylesheet" href="../navbar.css">
    <link rel="stylesheet" href="../styles/main.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poetsen+One&display=swap" rel="stylesheet">
    <style>
        .card-link {
            text-decoration: none;
            color: #000000;
        }
        .card-link:hover {
            text-decoration: none;
        }
        .card-img-top {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
    </style>
</head>
<body>
<?php
session_start();
include("../baglanti.php");
$user_logged_in = isset($_SESSION['user_id']);
$user_role = isset($_SESSION['role']) ? $_SESSION['role'] : '';
    if ($user_logged_in) {
        include ("../navbar2.php"); // Giriş yapılmışsa bu navbar
    } else {
        include ("../navbar.php");  // Giriş yapılmamışsa bu navbar
    }

// Kullanıcının favori tariflerini alıyrouz
$user_id = $user_logged_in ? $_SESSION['user_id'] : null;
$fav_sql = "SELECT recipe_id FROM user_favorites WHERE user_id = '$user_id'";
$fav_result = mysqli_query($conn, $fav_sql);
$favorites = [];
while ($fav_row = mysqli_fetch_assoc($fav_result)) {
    $favorites[] = $fav_row['recipe_id'];
}

$sql = "SELECT * FROM recipes WHERE category = 'Main Course'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    echo '<div class="container">';
    $counter = 0;
    while ($row = mysqli_fetch_assoc($result)) {
        if ($counter % 4 == 0) {
            echo '<div class="row justify-content-center">';
        }
        $imagePath = '../' . $row["image"];
        $is_favorite = in_array($row["id"], $favorites);
        
        echo '<div class="col-md-3 mb-4">';
        echo '<a href="recipe_details.php?recipe_id=' . $row["id"] . '" class="card-link">';
        echo '<div class="card">';
        echo '<img src="' . $imagePath . '" class="card-img-top" alt="' . $row["recipe_name"] . '">';
        echo '<div class="card-body">';
        echo '<h5 class="card-title">' . $row["recipe_name"] . '</h5>';
        if ($is_favorite) {
            echo '<a href="add_to_favorites.php?id=' . $row["id"] . '&action=remove" class="btn btn-danger" onclick="refreshPage()">Remove from favorites</a>';
        } else {
            echo '<a href="add_to_favorites.php?id=' . $row["id"] . '&action=add" class="btn btn-primary" onclick="refreshPage()">Add to favorites</a>';
        }
        if ($user_role === 'admin') {
            echo '<div class="button-group">';
            echo '<a href="edit_recipe.php?recipe_id=' . $row["id"] . '" class="btn btn-warning" style="display: inline-block; width: 100px; margin-right: 5px;">Edit</a>';
            echo '<a href="delete_recipe.php?recipe_id=' . $row["id"] . '" class="btn btn-danger" onclick="return confirm(\'Are you sure you want to delete this recipe?\')" style="display: inline-block; width: 100px;">Delete</a>';
            echo '</div>';
        }
        echo '</div>';
        echo '</div>';
        echo '</a>';
        echo '</div>';
        
        $counter++;
        if ($counter % 4 == 0) {/*dört sütunlu grid düzeninde görüntülemeyi sağlıyo*/
            echo '</div>';
        }
    }
    if ($counter % 4 != 0) {
        echo '</div>';
    }
    echo '</div>';
}

mysqli_close($conn);
?>
<?php 
     include('../footer.php');
      ?>

<script>
    function refreshPage() {
        location.reload();
    }
</script>

</body>
</html>
