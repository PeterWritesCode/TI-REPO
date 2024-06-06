<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/geral.css">
    <link rel="stylesheet" href="../CSS/definicoes.css">
    <link rel="stylesheet" href="../CSS/login-sign-up.css">
    <title>SPADA</title>
</head>
<body>
<!---------Editar perfil----------->
<div class="grid">
    <h1>Dados pessoais</h1>
    <div class="center-image">
        <img class="image" src="../imgs/editar-perfil.svg" alt="imagem">
    </div>
    <div>
        <?php
        session_start();
        // Conexão com o banco de dados
        $pgAdmin = "dbname=spada user=postgres password=postgres host=localhost port=5432";
        $connection = pg_connect($pgAdmin);

        if (!$connection) {
            die("Erro na ligação");
        }

        // Consulta SQL para obter os dados do cuidador
        $query = "SELECT nomecuidador, email, password FROM cuidador WHERE utilizador_id = $1";
        $result = pg_query_params($connection, $query, array($_SESSION['cuidador_id']));

        if ($result) {
            // Se a consulta for bem-sucedida, mostra os dados do cuidador
            $cuidador = pg_fetch_assoc($result);
        } /*else {
            echo "Erro ao recuperar os dados do cuidador.";
        }*/
        pg_close($connection);
        ?>
        <form>
            <div class="form-group">
                <label for="name" style="font-weight: 200;">Nome Completo</label>
                <!-- Preencher com o nome do cuidador -->
                <input type="text" id="name" name="name" value="<?php echo isset($cuidador['nomecuidador']) ? $cuidador['nomecuidador'] : ''; ?>">
            </div>
            <div class="form-group">
                <label for="email" style="font-weight: 200;">Email</label>
                <!-- Preencher com o email do cuidador -->
                <input type="email" id="email" name="email" value="<?php echo isset($cuidador['email']) ? $cuidador['email'] : ''; ?>">
            </div>
            <div class="form-group">
                <label for="password" style="font-weight: 200;">Password</label>
                <div style="position: relative;">
                    <!-- Como não é seguro exibir a senha, não preenchemos este campo -->
                    <input type="password" id="password" name="password" value="<?php echo isset($cuidador['password']) ? $cuidador['password'] : ''; ?>"  required>
                    <div class="password-toggle">
                        <img src="../imgs/olho-fechado.svg" alt="olho fechado" id="eyeicon">
                    </div>
                </div>
            </div>
        </form>

    </div>
</div>

<!---------Navegação----------->
<nav class="nav horizontal">
    <ul>
        <li>
            <a href="../dados-pessoais/dados-pessoais-iniciar.html">
                <img class="material-icons" src="../imgs/icone1_not_selected.svg" alt="dados paciente">
            </a>
        </li>
        <li>
            <a href="../alertas/alertas.html">
                <img class="material-icons" src="../imgs/icone2-not_selected.svg" alt="alertas">
            </a>
        </li>
        <li>
            <a href="../definicoes/definicoes.html">
                <img class="material-icons" src="../imgs/icone3_selected.svg" alt="definições">
            </a>
        </li>
    </ul>
</nav>
</body>
<script src="../password.js"></script>
</html>
