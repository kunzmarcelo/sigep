<?php

/* Inclusão da classe mPDF */
include('../mpdf60/mpdf.php');
$external = '../startbootstrap/vendor/bootstrap/css/bootstrap.min.css';


//$css = include_once "./pdf.css";
// Extrai os dados do HTML gerado pelo programa PHP
$filename = "code.html";
$html = file_get_contents($filename);
$mpdf = new mPDF('', 'A4-L', 10, 'Times New Roman'); // Página, fonte;
$stylesheet = file_get_contents("$external"); // external css
//include '../startbootstrap/vendor/bootstrap-social/bootstrap-social.css';
/*
 * A conversão de caracteres foi necessária aqui, mas pode não ser no seu servidor.
 * Certifique-se disso nas configurações globais do PHP.
 * Usar codificação errada resulta em travamento.
 */
$mpdf->allow_charset_conversion = true; //Ativa a conversão de caracteres;
$mpdf->charset_in = 'UTF-8'; //Codificação do arquivo '$filename';
//$mpdf->SetFooter('{DATE j/m/Y}|{PAGENO}/{nb} | LOJAS LÜGER');

/* Propriedades do documento PDF */
$mpdf->SetAuthor('SIGEP'); // Autor
$mpdf->SetSubject("Relatório"); //Assunto
$mpdf->SetTitle('Relátorio'); //Titulo
//$mpdf->SetKeywords('palavras, chave, aqui'); //Palavras chave
$mpdf->SetCreator('Sigep'); //Criador

/* A proteção para o PDF é opcional */
$mpdf->SetProtection(array('copy', 'print'), '', '#minhasenha'); // Permite apenas copiar e imprimir

/* Geração do PDF */
$mpdf->WriteHTML($html, 0); // Carrega o conteudo do HTML criado;
$data_hora = date('Y-m-d');
//$hora = date('H:i:s');


$data = explode("-", $data_hora);
$data2 = $data[2] . '-' . $data[1] . '-' . $data[0];
$mpdf->Output("relatorio_$data2" . ".pdf", 'D'); // Cria PDF usando 'D' para forçar o download;
unlink($filename); // Apaga o HTML
ob_clean(); // Descarta o buffer;
exit();
?>



