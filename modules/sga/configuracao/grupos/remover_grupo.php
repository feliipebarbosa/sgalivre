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
 * Exibe a jenala para a confirmação de remoção de grupos
 */
try {

    if (empty($_POST['id_grupo'])) {
	    throw new Exception("O grupo a ser deletado deve ser especificado.");
    }
   
    $id_grupo = $_POST['id_grupo'];
    
    $grupo = DB::getInstance()->get_grupo_by_id($id_grupo);
    if ($grupo == null) {
    	throw new Exception("O grupo especificado para remoção não existe.");
    }
    if ($grupo->is_raiz()) {
    	throw new Exception("Não é possível remover o grupo raiz.");
    }
    
    DB::getInstance()->remover_grupo($id_grupo);
    TConfiguracao::display_confirm_dialog("Grupo removido com sucesso.");
}
catch(PDOException $e){
	if($e->getCode() >= 23000 && $e->getCode() <24000){
		TConfiguracao::display_error('Não é possivel remover o grupo porque existem unidades ou usuários associados a ele.','Remover grupo');
	}else{
		TConfiguracao::display_exception($e);
	}	
}
catch(Exception $e) {
	TConfiguracao::display_exception($e);
}
?>