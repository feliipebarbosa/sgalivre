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
 * Este programa é distribuído na esperança que possa ser útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer
 * MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU para maiores detalhes.
 *
 * Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "LICENCA.txt", junto com este programa, se não, escreva para a 
 * Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA.
 *
**/

@SGA::check_login('sga.atendimento');

/**
 * Encerra e redireciona o atendimento
 */

try {
    $atendimento = Session::getInstance()->get('ATENDIMENTO');
    
    if (!empty($_GET['redirecionar'])) {
        $redirecionar = $_GET['redirecionar'];
        if ($redirecionar == "true") {
            Session::getInstance()->set("redirecionar", true);
        }
        else {
            Session::getInstance()->del("redirecionar");
        }
    }
    else {
        throw new Exception("Váriavel 'redirecionar' era esperada nos parametros GET");
    }

    if ($atendimento->get_status() == Atendimento::ATENDIMENTO_INICIADO) {
        $atendimento->set_status(Atendimento::ATENDIMENTO_ENCERRADO);
        DB::getInstance()->set_atendimento_status($atendimento->get_id(), Atendimento::ATENDIMENTO_ENCERRADO);
    }
    
    SGA::_include("modules/sga/atendimento/atender/index.php");
}
catch(Exception $e) {
	TAtendimento::display_exception($e);
}

?>
