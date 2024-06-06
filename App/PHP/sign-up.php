<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['email']) || !isset($_POST['nome_cuidador']) || !isset($_POST['password'])) {
        header("Location: ../PHP/error_campofalta.php");
        exit();
    }

    $email = strtolower($_POST['email']);
    $nome_cuidador = $_POST['nome_cuidador'];
    $password = $_POST['password'];

    $pgAdmin = "dbname=spada user=postgres password=postgres host=localhost port=5432";
    $connection = pg_connect($pgAdmin);

    if (!$connection) {
        die("Erro na ligação");
    }

    $query = "INSERT INTO cuidador (nomecuidador, email, password) VALUES ($1, $2, $3)";
    $result = pg_query_params($connection, $query, array($nome_cuidador, $email, $password));

    if ($result) {
        pg_close($connection);
        header("Location: ../log-in-sign-up/login.html");
        exit();
    } else {
        pg_close($connection);
        echo "Erro ao cadastrar. Tente novamente.";
    }
}
?>
