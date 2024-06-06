
<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verifica se o email e a senha foram enviados no formulário
    if (!isset($_POST['email']) || !isset($_POST['password'])) {
        header("Location: ../PHP/error_campofalta.php");
        exit();
    }

    $email = strtolower($_POST['email']);
    $password = $_POST['password'];

    $pgAdmin = "dbname=spada user=postgres password=postgres host=localhost port=5432";
    $connection = pg_connect($pgAdmin);

    if (!$connection) {
        die("Erro na ligação");
    }

    // Consulta SQL para buscar o cuidador pelo email
    $query = "SELECT * FROM cuidador WHERE email = $1";
    $result = pg_query_params($connection, $query, array($email));

    if ($result) {
        $cuidador = pg_fetch_assoc($result);

        // Verifica se o cuidador foi encontrado e se a senha está correta
        if ($cuidador && $password == $cuidador['password']) {
            // Se o login for bem-sucedido, armazena o ID do cuidador na sessão
            $_SESSION['cuidador_id'] = $cuidador['utilizador_id'];
            // Redireciona para a página inicial do cuidador
            header("Location: ../alertas/alertas.html");
            exit();
        } else {
            // Se o email ou a senha estiverem incorretos, exibe uma mensagem de erro
            echo "Email ou senha incorretos. Por favor, tente novamente.";
        }
    } else {
        echo "Erro ao conectar ao banco de dados.";
    }

    pg_close($connection);
}
?>
