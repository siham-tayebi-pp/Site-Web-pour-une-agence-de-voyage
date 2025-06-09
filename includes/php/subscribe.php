<?php
// Traitement de l'abonnement
$success = false;
$error = '';
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) {
    $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);

    if ($email) {
        $file = 'emails.txt';
        $email = strtolower($email); // normaliser

        // Éviter les doublons
        $emails = file_exists($file) ? file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) : [];
        if (in_array($email, $emails)) {
            $error = "Cet email est déjà inscrit.";
        } else {
            file_put_contents($file, $email . PHP_EOL, FILE_APPEND);
            $success = true;
            $message = "Merci pour votre abonnement !";
        }
    } else {
        $error = "Adresse email invalide.";
    }
} else {
    $error = "Aucune donnée reçue.";
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Résultat de l'abonnement</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light d-flex align-items-center justify-content-center vh-100">
    <div class="container text-center">
        <div class="p-5 bg-white shadow rounded">
            <?php if ($success): ?>
            <div class="alert alert-success">
                <?= htmlspecialchars($message) ?>
            </div>
            <?php else: ?>
            <div class="alert alert-danger">
                <?= htmlspecialchars($error) ?>
            </div>
            <?php endif; ?>
            <a href="../../index.php" class="btn btn-primary mt-3">Retour</a>
        </div>
    </div>
</body>

</html>