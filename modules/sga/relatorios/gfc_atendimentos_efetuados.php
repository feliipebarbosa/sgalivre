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

SGA::check_access('sga.relatorios');
try {
	$formato = $_GET['formato'];
	if ($formato == "pdf") {
		$rel = new RelatorioPDF();
	}
	else {
		$rel = new RelatorioHTML();
	}

	$rel->setTitulo("Atendimentos Efetuados");

	$dt_min = explode('/', $_GET['dt_min']);
	$dt_max = explode('/', $_GET['dt_max']);

	$tm_min = mktime(0, 0, 0, $dt_min[1], $dt_min[0], $dt_min[2]);
	$tm_max = mktime(23, 59, 59, $dt_max[1], $dt_max[0], $dt_max[2]);

	$dt_min = date("Y-m-d H:i:s", $tm_min);
	$dt_max = date("Y-m-d H:i:s", $tm_max);

	$rel->setSubTitulo('Período: '.date("d/m/Y", $tm_min)." - ".date("d/m/Y", $tm_max));

    $gfc_agregado = $_GET['check_gfc_agregado'] == 1;
    $gfc_unidades = $_GET['check_gfc_unidades'] == 1;

    $grupos = $_GET['idGrupo'];

    $tmp = DB::getInstance()->get_unidades_by_grupos($grupos);
    $ids_uni = array();
    foreach ($tmp as $u) {
        $ids_uni[] = $u->get_id();
    }

    if ($gfc_agregado) {
        $graidle = Graficos::get_pie_estat_atendimentos_uni_global($ids_uni, $dt_min, $dt_max);
        $rel->addComponente($graidle);

        $rel->addComponente(Separador::getInstance());

        $graidle = Graficos::get_pie_estat_macro_serv_global("Macrosserviços Atendidos - Agregado", $ids_uni, $dt_min, $dt_max);
        $rel->addComponente($graidle);

        $rel->addComponente(Separador::getInstance());
    }

    if ($gfc_unidades) {
        foreach ($tmp as $u) {
            $graidle = Graficos::get_pie_estat_macro_serv_global("Macrosserviços Atendidos - ".$u->get_nome(), array($u->get_id()), $dt_min, $dt_max);
            $rel->addComponente($graidle);
            
            $rel->addComponente(Separador::getInstance());
        }
    }

    
    
    $rel->output();
}
catch (Exception $e) {
    Template::display_exception($e);
}
?>
