<?php
session_start();
if(!isset($_SESSION['user_id'])){ header("Location: login.php"); exit; }
include("../config/database.php");

if(isset($_POST['submit'])){
    $title = $_POST['title'];
    $desc = $_POST['description'];
    $loc = $_POST['location'];
    $date = $_POST['date'];

    $img = $_FILES['image']['name'];
    move_uploaded_file($_FILES['image']['tmp_name'], "../assets/uploads/".$img);

    $sql = "INSERT INTO activities(title,description,location,activity_date,image) VALUES(?,?,?,?,?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$title,$desc,$loc,$date,$img]);

    $success = "Activité ajoutée !";
}
?>

<div class="container mt-5">
<h2>Ajouter une activité</h2>
<?php if(isset($success)) echo "<p class='text-success'>$success</p>"; ?>
<form method="POST" enctype="multipart/form-data">
    <input class="form-control mb-2" type="text" name="title" placeholder="Titre" required>
    <textarea class="form-control mb-2" name="description" placeholder="Description" required></textarea>
    <input class="form-control mb-2" type="text" name="location" placeholder="Lieu" required>
    <input class="form-control mb-2" type="date" name="date" required>
    <input class="form-control mb-2" type="file" name="image">
    <button class="btn btn-primary" type="submit" name="submit">Ajouter</button>
</form>
</div>