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
 * Exibe a janela para salvar os dados de um grupo e exibir a confirmação 
 */
try {
    if (empty($_POST['nm_grupo']) || (empty($_POST['id_grupo_pai']) && $_POST['id_grupo'] != 1)) {
    	throw new Exception("Preencha os campos corretamente.");
    }

    $nm_grupo 		= $_POST['nm_grupo'];
    $id_grupo_pai	= $_POST['id_grupo_pai'];
    $desc_grupo 	= $_POST['desc_grupo'];
	$confirmado		= $_POST['confirmado'];
	$bool           = true;
	$id_popup       = "";
    $unidades = DB::getInstance()->get_unidade_by_grupo($id_grupo_pai);
    
    	// se editando
	    if (!empty($_POST['id_grupo'])) {
	    	$id_grupo = $_POST['id_grupo'];
	        $current_id_pai = DB::getInstance()->get_grupo_pai_by_id($id_grupo)->get_id();
      		//verifica se o usuario ao editar alterou o grupo superior
	        if ($current_id_pai != $id_grupo_pai){
      			//verifica se o grupo superior selecionado possui unidade lotada a ele
      			if (sizeof($unidades) > 0 ){
	      			$unidades_grupo = DB::getInstance()->get_unidade_by_grupo($id_grupo);
	      			//verifica se grupo selecionado possui unidade lotada a ele 
	      			if (sizeof($unidades_grupo)>0){
	      				$bool = false;
	      				Template::display_error("Nao é possível mover o grupo pois há unidade lotada à ele e o grupo destino também possui unidade lotada.","Mover Grupo");
	      				$id_popup = "config_edit_grupo";
	      			}else{
		      			// verifica se o usuario ainda não confirmou o deslocamento
	      				if (!$confirmado){
	      					$bool = false;
		    				Template::display_yes_no_dialog("A unidade (".$unidades[0]->get_nome().") será deslocada para o novo grupo (".$nm_grupo.") Deseja Confirmar?","Atenção", "Configuracao.salvarGrupo(1); window.closePopup(this);") ;
		    			}else{
		    				//verifica se o grupo superior é igual ao grupo selecionado
					    	if ($id_grupo_pai == $id_grupo) {
					    		$bool = false;
					    		throw new Exception("Um grupo não pode ser seu próprio pai."); 	
					    	}
					    	//atualiza o grupo
					    	DB::getInstance()->atualizar_grupo($id_grupo, $id_grupo_pai, $nm_grupo, $desc_grupo);
							if (sizeof($unidades) > 0) {
					    		$unidade = $unidades[0];
					    		//atualiza o id_grupo da unidade	
					    		DB::getInstance()->atualizar_unidade($unidade->get_id(), $id_grupo, $unidade->get_codigo(), $unidade->get_nome());
								$id_popup = "config_edit_grupo";					    	
							}
				      	}		
	      			}
      			
      			}else{//se o grupo pai nao possui unidade à ser deslocada somente atualizará seu filho
            		DB::getInstance()->atualizar_grupo($id_grupo, $id_grupo_pai, $nm_grupo, $desc_grupo);
            		$id_popup = "config_edit_grupo";
      			}
	      	}else{
	      		DB::getInstance()->atualizar_grupo($id_grupo, $id_grupo_pai, $nm_grupo, $desc_grupo);
	      		$id_popup = "config_edit_grupo";
	      	}
	    }
	    else { // criando
	    	if (sizeof($unidades)> 0 ){
	    		if (!$confirmado){
	    			$bool = false;
	    			Template::display_yes_no_dialog("A unidade (".$unidades[0]->get_nome().") será deslocada para o novo grupo (".$nm_grupo.") Deseja Confirmar?","Atenção", "Configuracao.salvarGrupo(1); window.closePopup(this);") ;
	    		}else{
	    			$grupo = DB::getInstance()->criar_grupo($id_grupo_pai, $nm_grupo, $desc_grupo);
					$unidade = $unidades[0];
		    		DB::getInstance()->atualizar_unidade($unidade->get_id(), $grupo->get_id(), $unidade->get_codigo(), $unidade->get_nome());
	    			$id_popup = "config_add_grupo";
	    		}
	    	}else{
	    		$grupo = DB::getInstance()->criar_grupo($id_grupo_pai, $nm_grupo, $desc_grupo);
	    		$id_popup = "config_add_grupo";
	    	}
	    	
    	}
    	if ($bool){
    		TConfiguracao::display_confirm_dialog("Grupo salvo com sucesso.","","window.closePopupById('$id_popup')");
    	}
		//utilizado para verificação do Ajax
		//echo "true";
}
catch(Exception $e) {
	TConfiguracao::display_exception($e);
}
?>