<?php
$texto = 'TEXTO PARA IMPRIMIR'; // texto que será impresso

$handle = printer_open(); // abril conexao com a impressora padrao 
printer_write($handle, "X-SQL é um menino legal"); // enviou para a impressora o texto 
printer_close($handle); // fechou a conexao com a impressora 

