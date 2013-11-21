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

	$rel->setTitulo("Ranking das Unidades de Atendimento");

	$dt_min = explode('/', $_GET['dt_min']);
	$dt_max = explode('/', $_GET['dt_max']);

	$tm_min = mktime(0, 0, 0, $dt_min[1], $dt_min[0], $dt_min[2]);
	$tm_max = mktime(23, 59, 59, $dt_max[1], $dt_max[0], $dt_max[2]);

	$dt_min = date("Y-m-d H:i:s", $tm_min);
	$dt_max = date("Y-m-d H:i:s", $tm_max);

	$rel->setSubTitulo('Período: '.date("d/m/Y", $tm_min)." - ".date("d/m/Y", $tm_max));

    $grupos = $_GET['idGrupo'];

    $tmp = DB::getInstance()->get_unidades_by_grupos($grupos);
    $ids_uni = array();
    foreach ($tmp as $u) {
        $ids_uni[] = $u->get_id();
    }

    // Os mesmos dados são usados para os gráficos de Ranking, ordenados de forma distinta em cada gráfico
    $array = DB::getInstance()->get_ranking_unidades($ids_uni, $dt_min, $dt_max);

    $func = array("Graficos", "compara_tme"); // ordenação por TME -> Graficos::compara_tme()
    $graidle = Graficos::get_hb_ranking_unidades($array, $func, "Ranking das Unidades - TME");
    $rel->addComponente($graidle);

    $rel->addComponente(Separador::getInstance());

    $func = array("Graficos", "compara_tmd"); // ordenação por TMD -> Graficos::compara_tmd()
    $graidle = Graficos::get_hb_ranking_unidades($array, $func, "Ranking das Unidades - TMD");
    $rel->addComponente($graidle);

    $rel->addComponente(Separador::getInstance());

    $func = array("Graficos", "compara_tma"); // ordenação por TMA -> Graficos::compara_tma()
    $graidle = Graficos::get_hb_ranking_unidades($array, $func, "Ranking das Unidades - TMA");
    $rel->addComponente($graidle);

    $rel->addComponente(Separador::getInstance());

    $func = array("Graficos", "compara_tmp"); // ordenação por TMP -> Graficos::compara_tmp()
    $graidle = Graficos::get_hb_ranking_unidades($array, $func, "Ranking das Unidades - TMP");
    $rel->addComponente($graidle);
    
    $rel->output();
}
catch (Exception $e) {
    Template::display_exception($e);
}

?>
