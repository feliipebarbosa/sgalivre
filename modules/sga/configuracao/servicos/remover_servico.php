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

SGA::check_login('sga.configuracao');

try {
    if (empty($_POST['id_serv'])) {
	    throw new Exception("Selecione o serviço.");
    }
    $id_serv = $_POST['id_serv'];
    
    DB::getInstance()->remover_servico($id_serv);
    TConfiguracao::display_confirm_dialog("Serviço removido com sucesso.");
}
catch (PDOException $e) {
	// erros de Violação de Restrição
	if ($e->getCode() >= 23000 && $e->getCode() <= 23999) {
		TConfiguracao::display_error("Este serviço não pode ser removido pois existem registros no banco de dados que fazem referência ao mesmo.");
	}
	else {
		TConfiguracao::display_exception($e);
	}
}
catch (Exception $e) {
	TConfiguracao::display_exception($e);
}
?>