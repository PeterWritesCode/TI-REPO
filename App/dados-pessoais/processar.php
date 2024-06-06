<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/geral.css">
    <link rel="stylesheet" href="../CSS/dados-paciente.css">
    <title>SPADA</title>
</head>
<body>
<div class="grid">
    <h1>Dados Paciente</h1>
    <div class="scroll">
        <div class="center-image">
            <img src="../imgs/paciente.svg" alt="paciente">
        </div>

<?php
session_start();
// Conexão com o banco de dados
$pgAdmin = "dbname=spada user=postgres password=postgres host=localhost port=5432";
$connection = pg_connect($pgAdmin);

// Verifica se a conexão foi estabelecida corretamente
if (!$connection) {
    die("Erro na ligação com o banco de dados");
}

// Verifica se os campos foram preenchidos
if(isset($_POST['nomepaciente']) && isset($_POST['data_nascimento'])&& isset($_POST['morada']) && isset($_POST['contacto_ref']) && isset($_POST['numero_utente']) && isset($_POST['medico_familia']) && isset($_POST['medicacao']) && isset($_POST['alergias'])) {
    // Obtém os dados do formulário
    $nomepaciente = $_POST['nomepaciente'];
    $data_nascimento = $_POST['data_nascimento'];
    $morada = $_POST['morada'];
    $contacto_ref = $_POST['contacto_ref'];
    $numero_utente = $_POST['numero_utente'];
    $medico_familia = $_POST['medico_familia'];
    $medicacao = $_POST['medicacao'];
    $alergias = $_POST['alergias'];
?>
        <form action="processar.php" method="post">
            <div class="form-group">
                <label for="nomepaciente" style="font-weight: 200;">Nome Completo</label>
                <input type="text" id="nomepaciente" name="nomepaciente" required value="<?php echo $nomepaciente; ?>">
            </div>
            <div class="form-group">
                <label for="data_nascimento" style="font-weight: 200;">Data de nascimento</label>
                <input type="date" id="data_nascimento" name="data_nascimento" required value="<?php echo $data_nascimento; ?>">
            </div>
            <div class="form-group">
                <label for="morada" style="font-weight: 200;">Morada</label>
                <input type="text" id="morada" name="morada" required value="<?php echo $morada; ?>">
            </div>
            <div class="form-group">
                <label for="contacto_ref" style="font-weight: 200;">Contacto de emergências</label>
                <input type="tel" id="contacto_ref" placeholder="(+351) xxx-xxx-xxx" name="contacto_ref" required value="<?php echo $contacto_ref; ?>">
            </div>
            <div class="form-group">
                <label for="numero_utente" style="font-weight: 200;">Número de utente</label>
                <input type="text" id="numero_utente" placeholder="xxxxxxxxx" name="numero_utente" value="<?php echo $numero_utente; ?>">
            </div>
            <div class="form-group">
                <label for="medico_familia" style="font-weight: 200;">Médico de família</label>
                <input type="text" id="medico_familia" name="medico_familia" required value="<?php echo $medico_familia; ?>">
            </div>
            <div class="form-group">
                <label for="medicacao" style="font-weight: 200;">Medicação</label>
                <input type="text" id="medicacao" name="medicacao" required value="<?php echo $medicacao; ?>">
            </div>
            <div class="form-group">
                <label for="alergias" style="font-weight: 200;">Alergias</label>
                <input type="text" id="alergias" name="alergias" required value="<?php echo $alergias; ?>">
            </div>
        </form>
        <?php
} else {
    echo "<p>Preencha todos os campos do formulário.</p>";
}
?>
    </div>
</div>

<!---------Navegação----------->
<nav class="nav horizontal">
    <ul>
        <li>
            <a href="dados-pessoais-iniciar.html">
                <img class="material-icons" src="../imgs/icone1_selected.svg" alt="dados paciente">
            </a>
        </li>
        <li>
            <a href="../alertas/alertas.html">
                <img class="material-icons" src="../imgs/icone2-not_selected.svg" alt="alertas">
            </a>
        </li>
        <li>
            <a href="../definicoes/definicoes.html">
                <img class="material-icons" src="../imgs/icone3_not_selected.svg" alt="definições">
            </a>
        </li>
    </ul>
</nav>
</body>
</html>