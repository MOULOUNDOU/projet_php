<?php
session_start();
include('db.php');
include('header.php'); // Inclure l'en-tête

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Recherche de l'utilisateur dans la base de données
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        // Si l'utilisateur existe et le mot de passe est correct, on démarre la session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        // Rediriger vers le tableau de bord approprié en fonction du rôle
        if ($user['role'] == 'admin') {
            header('Location: admin_dashboard.php');
            exit();
        } else {
            header('Location: dashboard.php');
            exit();
        }
    } else {
        echo "Nom d'utilisateur ou mot de passe incorrect.";
    }
}
?>

<div class="container">
    <h2>Connexion</h2>
    <form action="login.php" method="POST">
        <div class="mb-3">
            <label for="username" class="form-label">Nom d'utilisateur</label>
            <input type="text" name="username" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Mot de passe</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Se connecter</button>
    </form>
</div>

<?php include('footer.php'); ?>