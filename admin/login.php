<?php
session_start();
include("../config/database.php");

if(isset($_POST['login'])){
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username=?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if($user && password_verify($password, $user['password'])){
        $_SESSION['user_id'] = $user['id'];
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Identifiants invalides";
    }
}
?>
<div class="container mt-5">
    <h2>Admin Login</h2>
    <?php if(isset($error)) echo "<p class='text-danger'>$error</p>"; ?>
    <form method="POST">
        <input class="form-control mb-2" type="text" name="username" placeholder="Nom utilisateur" required>
        <input class="form-control mb-2" type="password" name="password" placeholder="Mot de passe" required>
        <button class="btn btn-primary" type="submit" name="login">Connexion</button>
    </form>
</div>