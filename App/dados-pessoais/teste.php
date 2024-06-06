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
<!---------Dados pessoais----------->
<div class="grid">
    <h1>Preencher</h1>
    <div class="scroll">
        <form action="../dados-pessoais/processar.php" method="post">
            <div class="form-group">
                <label for="nomepaciente" style="font-weight: 200;">Nome Completo</label>
                <input type="text" id="nomepaciente" name="nomepaciente" required>
            </div>
            <div class="form-group">
                <label for="data_nascimento" style="font-weight: 200;">Data de nascimento</label>
                <input type="date" id="data_nascimento" name="data_nascimento" required>
            </div>
            <div class="form-group">
                <label for="morada" style="font-weight: 200;">Morada</label>
                <input type="text" id="morada" name="morada" required>
            </div>
            <div class="form-group">
                <label for="contacto_ref" style="font-weight: 200;">Contacto de emergências</label>
                <input type="tel" id="contacto_ref" placeholder="(+351) xxx-xxx-xxx" name="contacto_ref" required>
            </div>
            <div class="form-group">
                <label for="numero_utente" style="font-weight: 200;">Número de utente</label>
                <input type="text" id="numero_utente" placeholder="xxxxxxxxx" name="numero_utente">
            </div>
            <div class="form-group">
                <label for="medico_familia" style="font-weight: 200;">Médico de família</label>
                <input type="text" id="medico_familia" name="medico_familia" required>
            </div>
            <div class="form-group">
                <label for="medicacao" style="font-weight: 200;">Medicação</label>
                <input type="text" id="medicacao" name="medicacao" required>
            </div>
            <div class="form-group">
                <label for="alergias" style="font-weight: 200;">Alergias</label>
                <input type="text" id="alergias" name="alergias" required>
            </div>
            <button type="submit">Salvar</button>
        </form>
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