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
    if (empty($_POST['desc_serv']) || empty($_POST['nm_serv'])) {
	    throw new Exception("Preencha os campos corretamente.");
    }
    $nm_serv	= $_POST['nm_serv'];
    $desc_serv	= $_POST['desc_serv'];
    $stat_serv  = (int) $_POST['stat_serv'];
        
   // $id_macro = null;
    // existe ?
    if (isset($_POST['id_macro'])) {
    	// o macro veio vazio? (não selecionado)
    	if (empty($_POST['id_macro'])) {
    		//throw new Exception('O macrosserviço deve ser especificado.' . ' - ' . $_POST['id_macro']);
    		$id_macro = null;
    	}else{
    		$id_macro = $_POST['id_macro'];
    	}
    }
    
    if (!empty($_POST['id_serv'])) {
    	$id_serv = $_POST['id_serv'];
    	
    	DB::getInstance()->atualizar_servico($id_serv, $id_macro, $nm_serv, $desc_serv, $stat_serv);
    	DB::getInstance()->atualiza_stat_uni_serv($id_serv, $stat_serv);
    }
    else {
    	//insere o servico e obtem o objeto que o representa
    	DB::getInstance()->inserir_servico($id_macro, $nm_serv, $desc_serv, $stat_serv);
    }
    
//    TConfiguracao::display_confirm_dialog("Operação efetuada com sucesso ","Serviço");
}
catch (Exception $e) {
	TConfiguracao::display_exception($e);
}
?>