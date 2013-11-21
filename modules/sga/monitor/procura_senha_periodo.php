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

SGA::check_login('sga.monitor');
/**
 * Monta a interface do atendimento por periodo 
 */
try {
	
	if(!isset($_POST["num_senha"]) ){
		throw new Exception("Senha não especificada");
	}
	
	$num_senha = $_POST["num_senha"];
	$data_inicio = $_POST["i"];
	$data_fim = $_POST["j"];
		
	list($dia, $mes, $ano) = explode("/", $data_inicio);

	Template::display_popup_header("Consultar Senhas");
	//Verifica se a primeira data é válida
	if(!checkdate($mes, $dia, $ano)){
		echo "Primeira data é inválida";
	}
	else{
		$data_inicio = $ano.'-'.$mes.'-'.$dia.' 00:00:00';
		$inicial = $mes.'/'.$dia.'/'.$ano;

		list($dia,$mes,$ano) = explode("/", $data_fim);
		
		//Verifica se a segunda data é válida	
		if(!checkdate($mes, $dia, $ano)){
			echo "Segunda data é inválida";
		}
		else{
			$data_fim = $ano.'-'.$mes.'-'.$dia.' 23:59:59';
			$final = $mes.'/'.$dia.'/'.$ano;
			
			$id_uni = SGA::get_current_user()->get_unidade()->get_id();
		
			$atendimentos = DB::getInstance()->get_atendimento_senha_periodo($num_senha, $id_uni, $data_inicio, $data_fim);
		
			if($atendimentos == false){
				echo "Senha não encontrada.";
			}else{
				echo TMonitor::exibir_atendimento_periodo($atendimentos);
			}
		}
	}
	
	Template::display_popup_footer();
}
catch (Exception $e) {
	Template::display_exception($e);
}


function diferenca_dias($inicial,$final){
  $inicial = strtotime($inicial);
  $final = strtotime($final);    
  return ($final-$inicial)/86400; //transformação do timestamp em dias
}

?>
