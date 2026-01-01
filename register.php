<?php
include('db.php');
include('header.php'); // Inclure l'en-tête

// Message d'erreur pour l'inscription
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Récupérer les données du formulaire
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validation du nom d'utilisateur : uniquement des lettres, chiffres et underscores
    if (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
        $error = "Le nom d'utilisateur ne doit contenir que des lettres, des chiffres et des underscores (_).";
    }

    // Vérification si le mot de passe et la confirmation du mot de passe sont identiques
    elseif ($password !== $confirm_password) {
        $error = "Les mots de passe ne correspondent pas.";
    }

    // Si le nom d'utilisateur et le mot de passe sont valides, on vérifie si l'utilisateur existe déjà
    else {
        // Vérifier si l'utilisateur existe déjà dans la base de données
        $sql = "SELECT * FROM users WHERE username = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user) {
            $error = "L'utilisateur existe déjà. Veuillez choisir un autre nom d'utilisateur.";
        } else {
            // Si l'utilisateur n'existe pas, on peut procéder à l'inscription
            $password_hashed = password_hash($password, PASSWORD_DEFAULT);

            // Insérer le nouvel utilisateur dans la base de données
            $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$username, $password_hashed]);

            // Rediriger vers la page de connexion après l'inscription
            header('Location: login.php');
            exit(); // Toujours ajouter exit() après header pour éviter que du code soit exécuté après
        }
    }
}
?>

<div class="container">
    <h2>Inscription</h2>

    <!-- Affichage du message d'erreur s'il y en a un -->
    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <!-- Formulaire d'inscription -->
    <form action="register.php" method="POST">
        <div class="mb-3">
            <label for="username" class="form-label">Nom d'utilisateur</label>
            <input type="text" name="username" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Mot de passe</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="confirm_password" class="form-label">Confirmer le mot de passe</label>
            <input type="password" name="confirm_password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">S'inscrire</button>
    </form>
</div>

<?php include('footer.php'); ?>