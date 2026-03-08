<?php
$pdo = new PDO('mysql:host=metro.proxy.rlwy.net;port=56141;dbname=railway', 'root', 'ABqaqoefHgHDNsQnvDfmiOefZYxDPFIR');
$pdo->exec('SET FOREIGN_KEY_CHECKS = 0');
$stmt = $pdo->query('SHOW TABLES');
$tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
foreach ($tables as $table) {
    $pdo->exec("DROP TABLE IF EXISTS `$table`");
    echo "Dropped: $table\n";
}
$pdo->exec('SET FOREIGN_KEY_CHECKS = 1');
echo "All tables dropped.\n";
