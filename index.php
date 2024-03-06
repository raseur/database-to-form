<?php
require 'config.php';

try {
    // Connexion à la base de données avec PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

     

    $query = $pdo->query("SHOW TABLES");
    $tables = $query->fetchAll(PDO::FETCH_COLUMN);

    echo "<form method='post'>";
    foreach ($tables as $table) {
        echo "<input type='checkbox' name='tables[]' value='$table'> $table<br>";
    }
    echo "<input type='submit' value='Générer le(s) Formulaire(s)'>";
    echo "</form>";

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['tables'])) {
        foreach ($_POST['tables'] as $table) {
            $query = $pdo->prepare("DESCRIBE $table");
            $query->execute();
            $columns = $query->fetchAll(PDO::FETCH_COLUMN);

            echo "<textarea rows='10' cols='50'>";
            echo "<form method='post' action='traitement_du_formulaire.php'>\n";
            foreach ($columns as $column) {
                echo "<label for='$column'>$column</label>\n";
                echo "<input type='text' name='$column' id='$column' />\n";
            }
            echo "<input type='submit' value='Soumettre' />\n";
            echo "</form>\n";
            echo "</textarea><br><br>";
        }
    }
    } catch (PDOException $e) {
        die("Erreur de connexion à la base de données: " . $e->getMessage());
    }
?>