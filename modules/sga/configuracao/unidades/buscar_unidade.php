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

SGA::check_login("sga.configuracao");

try {
	if (empty($_POST['search_type'])) {
		throw new Exception("Selecione o modo da busca.");
	}
//	if (sizeof($_POST['search_input'])<1) {
//		throw new Exception("Especifique o termo de busca");
//	}
	
	$modo = $_POST['search_type'];
	$termo = $_POST['search_input'];
	if($termo == ""){
		$result = DB::getInstance()->get_unidades();
	}//else if(ctype_alnum($termo)){
		$termo = '%'.$termo.'%';
		if ($modo == "codigo") {
			$unidade = DB::getInstance()->get_unidade_by_codigo($termo);
			$result = array();
			if ($unidade!= null) {
				$result = $unidade;
			}
		}else {
		//	modo = nome
			$unidade = DB::getInstance()->get_unidade_by_name($termo);
			
			$result = array();
			if ($unidade != null) {
				$result = $unidade;
			}
		}
//	}else{
//		throw new Exception("Digite apenas letras ou números.");
//	}
	TConfiguracao::display_resultado_unidades_interno($result);
}
catch (Exception $e) {
	TConfiguracao::display_exception($e);
}

?>