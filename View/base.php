<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body><?php
// Handling error messages.
if (isset($_SESSION['errors']) && count($_SESSION['errors']) > 0) {
    $errors = $_SESSION['errors'];
    unset($_SESSION['errors']);

    foreach ($errors as $error) { ?>
        <div class="alert alert-error"><?= $error ?></div> <?php
    }
}

// Handling sucecss messages.
if (isset($_SESSION['success'])) {
    $message = $_SESSION['success'];
    unset($_SESSION['success']);
    ?>
    <div class="alert alert-success"><?= $message ?></div> <?php
}
?>
<header>
    <nav>
        <ul>
            <li><a href="/">Chat</a></li>
            <?php
            if (UserController::isUserConnected()) {
                ?>
                <li><a href="/index.php?c=user&m=logout">Se déconnecter</a></li>
                <li><a href="/index.php?c=user&m=user-space">Votre espace</a></li>
            <?php
            }
            else { ?>
                <li><a href="/index.php?c=user&m=register">S'enregistrer</a></li>
                <li><a href="/index.php?c=user&m=login">Se Connecter</a></li>
                <?php
            }
            ?>

        </ul>
    </nav>
</header>
<main>
    <?= $html ?>
</main>
<script src="/assets/js/post-message.js"></script>
</body>
</html>