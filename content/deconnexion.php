<?php
session_destroy(); // On détruit la session
header('Location: index.php?page=accueil'); // Retour à l'accueil
exit();