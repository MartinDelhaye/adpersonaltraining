<?php
include("config/config.php");
include("scripts/fonction-admin.php");

startSession();
session_unset();  // Libère toutes les variables de session
session_destroy(); // Détruit la session

// Redirige vers la page d'identification
header("Location: adminIdentification.php");
exit();
?>