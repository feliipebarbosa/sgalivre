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

SGA::check_login('sga.usuarios');
/**
 * Remove lotacoes passadas por POST 
 */
try {
//    if (empty($_POST['id_usu']) || empty($_POST['id_grupo'])) {
//	    throw new Exception("Nenhuma lotação selecionada.");
//    }
	$id_usu = $_POST["id_usu"];
	if(empty($id_usu) ){
		throw new Exception("Usuário não especificado.");
	}
	
	$id_grupo = $_POST['id_grupo'];
	if(empty($id_grupo) ){
		throw new Exception("Grupo não especificado.");
	}
	
	$id_lotacoes = $_POST["id_lotacoes"];
	
    // TODO verificações de segurança sobre: id_grupo, id_cargo
    // id_grupo: não permitir ao usuario lotar/editar um funcionario em um grupo onde ele não tem acesso
    // id_cargo: não permitir ao funcionario atribuir um cargo acima do seu.
    
	foreach($id_lotacoes as $id)
	{
		DB::getInstance()->remover_lotacao($id_usu, $id);
	}
	TUsuarios::display_confirm_dialog('Lotação removida com sucesso','Remover Lotação');
    
}
catch(Exception $e) {
	TUsuarios::display_exception($e);
}
?>