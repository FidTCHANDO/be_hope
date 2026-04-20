<?php
session_start();
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit;
}
include("../includes/header.php");
?>

<div class="container mt-5">
<h2>Dashboard Admin</h2>
<a href="add_activity.php" class="btn btn-success">Ajouter activité</a>
<a href="logout.php" class="btn btn-danger">Déconnexion</a>
</div>

<?php include("../includes/footer.php"); ?>