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
 * Adiciona ou atualiza as informacoes de um usuario
 */
try {
	if (empty($_POST['login_usu']) || empty($_POST['nm_usu']) || empty($_POST['ult_nm_usu']) || sizeof($_POST['grupos'])<1) {
		throw new Exception('Preencha os campos corretamente.');
	}
	$login_usu = $_POST['login_usu'];
    $nm_usu = $_POST['nm_usu'];
    $ult_nm_usu = $_POST['ult_nm_usu'];
	$grupos = $_POST['grupos'];
	$servicos = $_POST['servicos'];
    
    if (empty($_POST['id_usu']))
    {
    	// criando
    	if (!empty($_POST['senha_usu'])) {
    		$senha_usu = (string) $_POST['senha_usu'];
    	}
    	else {
    		throw new Exception("Preencha a senha corretamente.");
    	}
    	if ($senha_usu != $_POST['senha_usu2'])
    	{
    		throw new Exception("A confirmação de senha não confere com a senha.");
    	}
	    else if (!ctype_alnum($senha_usu)) {
	    	throw new Exception("A senha deve possuir somente letras e números.");
	    }
	    else if (strlen($senha_usu) < 6) {
	    	throw new Exception("A senha deve possuir no mínimo 6 caracteres.");
	    }

	    $novo_usuario = DB::getInstance()->inserir_usuario($login_usu, $nm_usu, $ult_nm_usu, $senha_usu); 
	    if($novo_usuario!=null){
	    	$permissoes = array();
		    foreach($grupos as $g){
		    	$aux = explode('@',$g);
//	    		formato: id_grupo@id_cargo
				DB::getInstance()->inserir_lotacao($novo_usuario->get_id(),$aux[0],$aux[1]);
		    }
		    if(sizeof($servicos)>0){
		    	$id_uni = SGA::get_current_user()->get_unidade()->get_id();
		    	foreach($servicos as $s){
		    		DB::getInstance()->adicionar_servico_usu($id_uni,$s,$novo_usuario->get_id());
		    	}
		    }
		    Template::display_confirm_dialog_refresh('Usuário criado com sucesso','Criar Usuário',true);
		    
	    }
    }
	else {
		// editando
		$id_usu = $_POST['id_usu'];
		
		DB::getInstance()->atualizar_usuario($id_usu, $login_usu, $nm_usu, $ult_nm_usu);
	}
}
catch (PDOException $e) {
	// erros de Violação de Restrição
	if ($e->getCode() >= 23000 && $e->getCode() <= 23999) {
		TUsuarios::display_error('O login informado já está cadastrado para outro usuário.');
	}
	else {
		TUsuarios::display_exception($e);
	}
}
//catch(Exception $e) {
//	
//	TUsuarios::display_exception($e);
//}

?>
