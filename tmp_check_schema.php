<?php
try {
    $pdo = new PDO('mysql:host=localhost;dbname=parcial_3;charset=utf8mb4', 'root', '', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);

    $tables = ['colaboradores', 'perfiles_laborales', 'cat_tipos_planilla'];
    foreach ($tables as $table) {
        echo "--- $table ---\n";
        $q = $pdo->query("SHOW CREATE TABLE $table");
        $r = $q->fetch();
        if (!$r) {
            echo "TABLE NOT FOUND\n\n";
            continue;
        }
        echo $r['Create Table'] . "\n\n";
    }
} catch (Exception $e) {
    echo 'ERROR: ' . $e->getMessage() . "\n";
}
