<?php
session_start();
include('db.php');
include('header.php'); // Inclure l'en-tête

// Vérification si l'utilisateur est connecté et a un rôle administrateur
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    // Si l'utilisateur n'est pas administrateur, rediriger vers la page de connexion
    header('Location: login.php');
    exit();
}

$sql = "SELECT * FROM users";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$users = $stmt->fetchAll();
?>

<div class="container">
    <h2>Tableau de bord de l'administrateur</h2>
    <h4>Liste des utilisateurs :</h4>
    <ul class="list-group">
        <?php foreach ($users as $user): ?>
            <li class="list-group-item">
                <?php echo htmlspecialchars($user['username']); ?>
            </li>
        <?php endforeach; ?>
    </ul>
</div>

<?php include('footer.php'); ?>