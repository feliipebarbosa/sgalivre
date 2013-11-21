<?php

/**
 * 
 * Copyright (C) 2009 DATAPREV - Empresa de Tecnologia e Informações da Previdência Social - Brasil
 *
 * Este arquivo é parte do programa SGA Livre - Sistema de Gerenciamento do Atendimento - Versão Livre
 *
 * O SGA é um software livre; você pode redistribuí­-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como 
 * publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença, ou (na sua opnião) qualquer versão.
 *
 * Este programa é distribuído na esperança que possa ser útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita
 * de ADEQUAÇÃO a qualquer
 * MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU para maiores detalhes.
 *
 * Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "LICENCA.txt", junto com este programa, se
 * não, escreva para a 
 * Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA.
 *
**/

/**
 * Como as imagens tem que ser carregadas externamente, o RelatorioHTML guarda
 * o objeto Graidle(grafico) na SESSION com um ID e cria uma tag <IMG> apontando para
 * este script que recupera o objeto Graide da SESSION e gera a imagem.
 *
 * Caso algum erro ocorra, uma imagem contendo o erro é gerada.
 */
SGA::check_access('sga.relatorios');

if (!isset($_GET['id_graidle'])) {
    fatal_error("Erro interno: id_graidle não especificado.");
}
else {
    $id_graidle = $_GET['id_graidle'];
    if (!isset($_SESSION['graidle'][$id_graidle])) {
        fatal_error("Erro interno: id_graidle não encontrado na session.");
    }

    // recuperao o bjeto da session
    $graidle = $_SESSION['graidle'][$id_graidle];

    // remove objeto graidle da session
    unset($_SESSION['graidle'][$id_graidle]);
    
    $graidle->create(); // gera imagem
    $graidle->carry(); // envia
}


function fatal_error($msg) {
    $im = imagecreatetruecolor(600,  50);
    
    $white = imagecolorallocate($im, 255, 255, 255);
    imagefill($im, 0, 0, $white);

    $text_color = imagecolorallocate($im, 233, 14, 91);
    $msgs = explode("\n", $msg);
    for ($i = 0; $i < count($msgs); $i++) {
        imagestring($im, 12, 5, 5 + $i*15,  $msgs[$i], $text_color);
    }

    header("Content-type: image/png");
    header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
    header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");

    imagepng($im);
    imagedestroy($im);
    exit();
}
?>
