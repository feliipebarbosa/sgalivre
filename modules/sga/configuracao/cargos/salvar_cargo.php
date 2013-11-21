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
 * 
 */
try {
    if (empty($_POST['nm_cargo'])) {
	    throw new Exception("Preencha os campos corretamente.");
    }
    
	$id_cargo_pai = $_POST['id_cargo_pai'];
    $nm_cargo 	= $_POST['nm_cargo'];
    $desc_cargo	= $_POST['desc_cargo'];
    $cargo_modulos = $_POST['cargo_modulos'];
    
    if (!empty($_POST['id_cargo'])) {
    	$id_cargo = $_POST['id_cargo'];
    	
    	// atualizar nome e descrição
    	DB::getInstance()->atualizar_cargo($id_cargo, $id_cargo_pai, $nm_cargo, $desc_cargo);
    	
    	$pcs = DB::getInstance()->get_permissoes_cargo($id_cargo);
		if (sizeof($cargo_modulos) > 0) {
	    	$modulos_atuais = array();
	    	foreach ($pcs as $pc) {
	    		$modulos_atuais[] = (string) $pc->get_modulo()->get_id();
	    	}
	    	
	    	// retorna os elementos presentes em $cargo_modulos que nao estao presentes em $modulos_atuais
	    	$novos = array_diff($cargo_modulos, $modulos_atuais);
	    	
	    	// retorna os elementos presentes em $modulos_atuais que nao estao presentes em $cargo_modulos
	    	$removidos = array_diff($modulos_atuais, $cargo_modulos);
	    	
	    	// inserir novos
	    	foreach ($novos as $id_mod) {
	    		DB::getInstance()->inserir_permissao_modulo_cargo($id_cargo, $id_mod, 3);
	    	}
	    	
	    	// remover "removidos"
	   		foreach ($removidos as $id_mod) {
	    		DB::getInstance()->remover_permissao_modulo_cargo($id_cargo, $id_mod, 3);
	    	}
		}
    }
    else {
    	// insere o cargo e obtem o objeto que o representa
    	$cargo = DB::getInstance()->inserir_cargo($id_cargo_pai, $nm_cargo, $desc_cargo);
    	
    	// insere as permissões do cargo
    	if (sizeof($cargo_modulos) > 0) {
	    	foreach ($cargo_modulos as $id_mod) {
	    		DB::getInstance()->inserir_permissao_modulo_cargo($cargo->get_id(), $id_mod, 3);
	    	}
    	}
    }
}
catch(Exception $e) {
	TConfiguracao::display_exception($e);
}
?>