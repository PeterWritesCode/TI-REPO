<?php
//Conexão ao portal do PGAdmin porta 5433
$str = "dbname=spada user=postgres password=postgres host=localhost port=5432";
$connection = pg_connect($str);
if (!$connection) {
    die("Erro na ligação");
}
//Destruir a sessão e passa para a página (../index.html)
session_destroy();
header("location: ../index.html");
exit();
?>