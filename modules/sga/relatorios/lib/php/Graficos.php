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

class Graficos {
    
    /**
     *
     * @param array Array contendo nm_uni e seus respectivo TMA
     * @param mixed Método de ordenação
     * @return Graidle O gráfico de barras horizontais com o ranking
     */
    public static function get_hb_ranking_unidades($array, $sort, $titulo) {
        $nms_uni = array();

        $tmes = array();
        $tmds = array();
        $tmas = array();
        $tmps = array();
        
        usort($array, $sort);
        
		foreach ($array as $linha) {
			$nms_uni[] = $linha['nm_uni'];
            $tmes[] = $linha['avg_espera'];
            $tmds[] = $linha['avg_desloc'];
            $tmas[] = $linha['avg_atend'];
            $tmps[] = $linha['avg_total'];
		}

        $g = new Graidle($titulo);
        $g->setDivision(1200);
        $g->setValue($tmas,'hb',"TMA","red");
        $g->setValue($tmds,'hb',"TMD","orange");
        $g->setValue($tmes,'hb',"TME","yellow");
        $g->setValue($tmps,'hb',"TMP","purple");
        $g->setSecondaryAxis(1,1);
        $g->setExtLegend(3);

        /*
         * A altura é definida por 160 pixels (parte fixa do gráfico)
         * mais 60 pixels pra cada unidade inclusa
         */
        $g->setHeight(160 + count($nms_uni) * 60);

        $g->setWidth(1000);
        $g->setFontSmall(7);
        $g->setXtitle("Tempo");
        $g->setXvalue($nms_uni);
        $g->setYValueFunc(array("Graficos", "get_seconds_as_time"));

		return $g;
	}
	/**
	 * 
     * @param $title
     * @param $ids_uni
     * @param $dt_min
     * @param $dt_max
     * @return Grafico dde estatisticas de macro serviços globais
	 */
    public static function get_pie_estat_macro_serv_global($title, $ids_uni, $dt_min, $dt_max) {
        $array = DB::getInstance()->get_estat_macro_serv_global($ids_uni, $dt_min, $dt_max);

        $counts_serv = array();
        $nms_serv = array();

        foreach ($array as $linha) {
			$nms_serv[] = $linha['nm_serv'];
            $counts_serv[] = $linha['count_serv'];
		}

        $g = new Graidle($title);
        $g->setAA(4);
        $g->setValue($counts_serv,'p',"");
        $g->setHeight(500);
        $g->setWidth(1000);
        $g->setLegend($nms_serv);
        $g->setExtLegend(2);

        return $g;
    }
	/**
	 * 
     * @param $ids_uni
     * @param $dt_min
     * @param $dt_max
     * @return Grafico 
	 */
    public static function get_pie_estat_atendimentos_uni_global($ids_uni, $dt_min, $dt_max) {
        $array = DB::getInstance()->get_estat_atendimentos_uni_global($ids_uni, $dt_min, $dt_max);

        $counts_serv = array();
        $nms_uni = array();

        foreach ($array as $linha) {
			$nms_uni[] = $linha['nm_uni'];
            $counts_serv[] = $linha['count_serv'];
		}

        $g = new Graidle("Atendimentos Efetuados");
        $g->setAA(4);
        $g->setValue($counts_serv,'p',"");
        $g->setHeight(500);
        $g->setWidth(1000);
        $g->setLegend($nms_uni);
        $g->setExtLegend(2);

        return $g;
    }

    public static function get_linha_tempos_medios_por_periodo($title, $ids_uni, $dt_min, $dt_max) {
        $array = DB::getInstance()->get_tempos_medios_por_periodo($ids_uni, $dt_min, $dt_max);

        $tmes = array();
        $tmas = array();
        $tmps = array();
        $labelsX = array();
		foreach ($array as $linha) {
            $tmes[] = $linha['avg_espera'];
            $tmas[] = $linha['avg_atend'];
            $tmps[] = $linha['avg_total'];
            $dtparts = explode("-", $linha['dt_atend']);
            $labelsX[] = $dtparts[1].'/'.$dtparts[0];
		}

        $g = new Graidle($title);
        $g->setValue($tmes,'l',"TME");
        $g->setValue($tmas,'l',"TMA");
        $g->setValue($tmps,'l',"TMP");
        $g->setHeight(500);
        $g->setWidth(1000);
        $g->setSecondaryAxis(1,1);
        $g->setDivision(900);
        $g->setYValueFunc(array("Graficos", "get_seconds_as_time"));
        $g->setXValue($labelsX);
        $g->setXtitle('Mês');
        $g->setExtLegend(1);
        return $g;
    }

    public static function compara_tme($elem1, $elem2) {
        return $elem1['avg_espera'] - $elem2['avg_espera'];
    }

    public static function compara_tmd($elem1, $elem2) {
        return $elem1['avg_desloc'] - $elem2['avg_desloc'];
    }

    public static function compara_tma($elem1, $elem2) {
        return $elem1['avg_atend'] - $elem2['avg_atend'];
    }

    public static function compara_tmp($elem1, $elem2) {
        return $elem1['avg_total'] - $elem2['avg_total'];
    }

    public static function get_seconds_as_time($secs) {
        $h = floor($secs / 3600);
        $i = floor(($secs % 3600) / 60);
        $s = $secs % 60;
        $h = str_pad($h, 2, '0', STR_PAD_LEFT);
        $i = str_pad($i, 2, '0', STR_PAD_LEFT);
        $s = str_pad($s, 2, '0', STR_PAD_LEFT);

        return "$h:$i:$s";
    }
}
?>
