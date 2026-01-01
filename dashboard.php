<?php
session_start(); 
include('db.php');
include('header.php'); // Inclure l'en-tête

// Vérification si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    // Si la session n'est pas définie, rediriger vers la page de connexion
    header('Location: login.php');
    exit(); // Toujours mettre un exit() après header pour éviter un affichage supplémentaire
}

// Récupérer l'ID de l'utilisateur connecté
$user_id = $_SESSION['user_id'];

// Récupération des tâches de l'utilisateur dans la table `tache`
$sql = "SELECT * FROM tache WHERE user_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id]);
$taches = $stmt->fetchAll(); // Récupérer toutes les tâches de cet utilisateur

// Ajouter une tâche si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['tache_description'])) {
    $tache_description = $_POST['tache_description'];
    
    // Insérer la nouvelle tâche dans la table `tache`
    $sql = "INSERT INTO tache (user_id, tache_description) VALUES (?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$user_id, $tache_description]);
    
    // Rediriger pour éviter que le formulaire soit soumis à nouveau lors du rafraîchissement de la page
    header('Location: dashboard.php');
    exit(); 
}
?>

<div class="container">
    <h2>Tableau de bord</h2>
    <h4>Bienvenue, <?php echo htmlspecialchars($_SESSION['username']); ?></h4> <!-- Afficher le nom d'utilisateur -->

    <!-- Formulaire pour ajouter une tâche -->
    <form action="dashboard.php" method="POST">
        <div class="mb-3">
            <label for="tache_description" class="form-label">Ajouter une nouvelle tâche</label>
            <input type="text" name="tache_description" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Ajouter la tâche</button>
    </form>

    <!-- Liste des tâches existantes -->
    <h4 class="mt-4">Mes Tâches :</h4>
    <ul class="list-group">
        <?php foreach ($taches as $tache): ?>
            <li class="list-group-item">
                <?php echo htmlspecialchars($tache['tache_description']); ?> <!-- Afficher la description de la tâche -->
            </li>
        <?php endforeach; ?>
    </ul>
</div>

<?php include('footer.php'); ?>