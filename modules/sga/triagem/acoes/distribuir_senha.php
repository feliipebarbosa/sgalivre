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

SGA::check_login('sga.triagem');

/**
 * distribui senhas da triagem
 */
 
try {
	if (empty($_POST['id_servico']) || empty($_POST['id_prio'])) {
		throw new Exception("Serviço ou prioridade não especificado.");
	}
	
	if (Session::getInstance()->exists('TRIAGEM')) {
		
		$usuario = Session::getInstance()->get(SGA::K_CURRENT_USER);
		$id_usuario = $usuario->get_id();
		$id_unidade = $usuario->get_unidade()->get_id();
			
		$id_servico = $_POST['id_servico'];
		$id_prio = $_POST['id_prio'];
		$nm_cliente = $_POST['client_name'];
		$ident_cliente = $_POST['client_ident'];

        $senha_distribuida = false;
        $falhas_counter = 0;
        for ($i = 0; $i < 5 && !$senha_distribuida; $i++) {
            try {
                $senha = DB::getInstance()->distribui_senha($id_unidade, $id_servico, $id_prio, 0, Atendimento::SENHA_EMITIDA, $nm_cliente, $ident_cliente, SGA::get_date("Y-m-d H:i:s"));
                $senha_distribuida = true;
            }
            catch (PDOException $e) {
                // Essa exception pode ocorrer tanto por um erro no SQL
                // como pela proteção de concorrencia.

                // incrementa
                $falhas_counter++;

                if ($falhas_counter >= 5) {
                    // limite de erros atingido, joga a exceção pra frente.
                    throw $e;
                }
                
                // aguarda 1 segundo após falha
                sleep(1);
            }
        }
		
		Session::getInstance()->set("ultima_senha", $senha);
	}
	else {
	    throw new Exception("Erro de sessão na triagem.");
	}
}
catch(Exception $e) {
	TTriagem::display_exception($e);
}

?>