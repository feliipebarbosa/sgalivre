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
/**
 * Remove um cargo e exibe a confirmação de remoção.
 */
try {
    if (empty($_POST['id_cargo'])) {
	    throw new Exception("O cargo a ser removido deve estar selecionado.");
    }
    
    $id_cargo = $_POST['id_cargo'];
    DB::getInstance()->remover_cargo($id_cargo);
    TConfiguracao::display_confirm_dialog("Cargo removido com sucesso.");
}
catch (PDOException $e) {
	if ($e->getCode() >= 23000 && $e->getCode() < 24000) {
		TConfiguracao::display_error('Cargo não removido, pois existem grupos utilizando o cargo especificado para remoção.');
	}
	else {
		TConfiguracao::display_exception($e);
	}
}
catch(Exception $e) {
	TConfiguracao::display_exception($e);
}
?>