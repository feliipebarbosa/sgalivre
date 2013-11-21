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
	if (empty($_POST['cod_uni']) || empty($_POST['nm_uni']) || empty($_POST['id_grupo'])) {
		throw new Exception('Preencha os campos corretamente.');
	}
	$cod_uni = $_POST['cod_uni'];
	$nm_uni = $_POST['nm_uni'];
	$id_grupo =(int) $_POST['id_grupo'];
	$id_usu = SGA::get_current_user()->get_id();
	$id_uni = $_POST['id_uni'];	
	
	$unidades = DB::getInstance()->get_unidades();
	$boll = true;
		
	if (empty($_POST['id_uni']))
	{
		foreach($unidades as $uni){
			if($cod_uni == $uni->get_codigo()){
				$boll = false;
			}
		}
		
		if($boll){
			//criando
			DB::getInstance()->inserir_unidade($id_grupo,$cod_uni,$nm_uni);
			$id_uni = DB::getInstance()->get_connection()->lastInsertId('unidades_id_uni_seq');
			DB::getInstance()->insere_mensagem($id_uni,$id_usu);
			Template::display_confirm_dialog('Unidade criada com sucesso.','Criar Unidade',true);
		}else{
			//se o código de unidade já existe
			Template::display_error('Código de Unidade já existe',"ERRO: UNIDADES");
		}
	}
	else {
		foreach($unidades as $uni){
			if($id_uni != $uni->get_id()){
				if($cod_uni == $uni->get_codigo()){
					$boll = false;
				}
			}
		}

		if($boll){
			// editando
			DB::getInstance()->atualizar_unidade($id_uni,$id_grupo,$cod_uni,$nm_uni);
			Template::display_confirm_dialog('Unidade atualizada com sucesso. ','Atualizar Unidade');
		}else{
			//se o código de unidade já existe
			Template::display_error('Código de Unidade já existe',"ERRO: UNIDADES");
		}
	}
}
catch(Exception $e) {
	TAdmin::display_exception($e);
}

?>