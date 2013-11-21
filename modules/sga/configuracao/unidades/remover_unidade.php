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

    if (empty($_POST['id_uni'])) {
	    throw new Exception("A unidade a ser deletada deve ser especificado.");
    }
   
    $id_uni = $_POST['id_uni'];
    $uni_atual = SGA::get_current_user()->get_unidade();
    $unidade = DB::getInstance()->get_unidade($id_uni);
    if ($unidade == null) {
    	throw new Exception("A unidade especificada para remoção não existe.");
    }else{
    	if($uni_atual != null && $uni_atual->get_id() == $unidade->get_id()){
    		throw new Exception("Não é possível remover a própria unidade.");
    	}
    }
    DB::getInstance()->remover_unidade($id_uni);
    TConfiguracao::display_confirm_dialog("Unidade removida com sucesso.","Remover Unidade","Configuracao.alterarConteudo('unidades/index.php');");
}

catch(Exception $e) {
	TConfiguracao::display_exception($e);
}
?>