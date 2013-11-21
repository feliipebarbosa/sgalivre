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

class Estatistica {
	
	/**
	 * 
	 * @return Tabela Tabela contendo os tempos médio de atendimento
	 */
	public static function get_tempos_medios($title, $ids_uni, $dt_min, $dt_max) {
		$tabela = new Tabela($title, 2);
				
		$estat_tempos_medios = DB::getInstance()->get_estat_tempos_medios($ids_uni, $dt_min, $dt_max);
		
		$tempos = array("avg_espera" => "TME - Tempo Médio de Espera para início do atendimento", 
						"avg_desloc" => "TMD - Tempo Médio de Deslocamento para mesa de atendimento", 
						"avg_atend" => "TMA - Tempo Médio de Atendimento ao cliente", 
						"avg_total" => "TMP - Tempo Médio de Permanência na unidade");
		
		foreach ($tempos as $key => $nome) {
			$tabela->addRow(array($nome, $estat_tempos_medios[$key]));
		}
		
		return $tabela;
	}
	
	/**
	 * 
	 * @return Tabela Tabela contendo a quantidade de senha em cada status
	 */
	public static function get_qtde_senhas_por_status($title, $ids_uni, $dt_min, $dt_max) {
		$tabela = new Tabela($title, 2);
		
		$qtde_senhas_status = DB::getInstance()->get_qtde_senhas_por_status($ids_uni, $dt_min, $dt_max);
		//exit('<pre>'.print_r($qtde_senhas_status, true));
		$status = array(Atendimento::SENHA_EMITIDA => "Senhas aguardando chamada", 
						Atendimento::CHAMADO_PELA_MESA => "Senhas chamadas", 
						Atendimento::ATENDIMENTO_INICIADO => "Senhas em atendimento", 
						Atendimento::ATENDIMENTO_ENCERRADO => "Senhas encerradas",
						Atendimento::ATENDIMENTO_ENCERRADO_CODIFICADO => "Senhas codificadas",
						Atendimento::NAO_COMPARECEU => "Senhas que não compareceram",
						Atendimento::SENHA_CANCELADA => "Senhas canceladas",
						Atendimento::ERRO_TRIAGEM => "Erros de triagem");
		
		$atend_status = array();
		foreach ($qtde_senhas_status as $row) {
			$atend_status[$row['id_stat']] = $row['count'];
		}
		
		$total = 0;
		foreach ($status as $id_stat => $nome) {
			$valor = (int) $atend_status[$id_stat];
			$tabela->addRow(array($nome, $valor));
			$total += $valor;
		}
		
		$tabela->addRow(array("Total de senhas", $total));
		
		return $tabela;
	}
	/**
	 * 
	 * @return Tabela Tabela contendo os serviços mestres
	 */
	public static function get_estatisticas_servicos_mestres($title, $ids_uni, $dt_min, $dt_max) {
        $cabecalho = array("Serviço", "Qtde", "TME", "TMD", "TMA", "TMP");
		$tabela = new Tabela($title, 6, $cabecalho);
		
		$estat_serv_mestre = DB::getInstance()->get_estatistica_servico_mestres($ids_uni, $dt_min, $dt_max);
		
		//$tabela->addRow($cabecalho);
		//$tabela->setRowBgColor(0, array(0xE5, 0xE5, 0xE5));
		
		foreach ($estat_serv_mestre as $linha) {
			$desc_serv 	= $linha['nm_serv'];
			$count_serv = $linha['count'];
			$avg_espera = $linha['avg_espera'];
			$avg_desloc = $linha['avg_desloc'];
			$avg_atend	= $linha['avg_atend'];
			$avg_total	= $linha['avg_total'];
			
			$tabela->addRow(array($desc_serv, $count_serv, $avg_espera, $avg_desloc, $avg_atend, $avg_total));
		}
		
		return $tabela;
	}
	/**
	 * 
	 * @return Tabela Tabela contendo a quantidade de senha que tenham o status "codificado"
	 */
	public static function get_estatisticas_servicos_codificados($title, $ids_uni, $dt_min, $dt_max) {
        $cabecalho = array("Serviço", "Qtde", "TME", "TMD", "TMA", "TMP");
		$tabela = new Tabela($title, 6, $cabecalho);
		
		$estat_serv_mestre = DB::getInstance()->get_estatisticas_servicos_codificados($ids_uni, $dt_min, $dt_max);
		
		//$tabela->addRow(array("SERVIÇO", "QTDE", "TME", "TMD", "TMA", "TMP"));
		//$tabela->setRowBgColor(0, array(0xE5, 0xE5, 0xE5));
		
		foreach ($estat_serv_mestre as $linha) {
			$desc_serv 	= $linha['nm_serv'];
			$count_serv = $linha['count'];
			$avg_espera = $linha['avg_espera'];
			$avg_desloc = $linha['avg_desloc'];
			$avg_atend	= $linha['avg_atend'];
			$avg_total	= $linha['avg_total'];
			
			$tabela->addRow(array($desc_serv, $count_serv, $avg_espera, $avg_desloc, $avg_atend, $avg_total));
		}
		
		return $tabela;
	}
	/**
	 * 
	 * @return Tabela Tabela contendo os tempos médios de atendimento de cada atendente
	 */
	public static function get_tempos_atend_por_usu($ids_uni, $dt_min, $dt_max) {
        $cabecalho = array("Atendente", "Qtde de Senhas", "Qtde de Codificados", "TMD", "TMA");
		$tabela = new Tabela("Tempos Médios por Atendente", 5, $cabecalho);
		
		$estat_serv_mestre = DB::getInstance()->get_tempos_atend_por_usu($ids_uni, $dt_min, $dt_max);
		
		//$tabela->addRow(array("ATENDENTE", "QTDE", "TMD", "TMA"));
		//$tabela->setRowBgColor(0, array(0xE5, 0xE5, 0xE5));
		
		foreach ($estat_serv_mestre as $linha) {
			
			$nm_usu 		= $linha['nome'];
			$count_atend	= $linha['count_atend'];
			$qtde_senhas    = $linha['qtde_senhas'];
                        $avg_desloc 	= $linha['avg_desloc'];
			$avg_atend		= $linha['avg_atend'];
			
			$tabela->addRow(array($nm_usu, $qtde_senhas,$count_atend, $avg_desloc, $avg_atend));
		}
		
		return $tabela;
	}
	
	/**
	 * 
	 * @return Tabela Tabela contendo a quantidade de atendimentos de cada serviço por atendente
	 */
	
	public static function get_estat_atend_por_usu($title_suffix, $ids_uni, $dt_min, $dt_max) {
		
		$estat_serv_mestre = DB::getInstance()->get_estat_atend_por_usu($ids_uni, $dt_min, $dt_max);
		
		$tabelas = array();
		$tabela = null;
		$nome_ant = false;
		foreach ($estat_serv_mestre as $linha) {
			
			$nm_usu 		= $linha['nome'];
			$desc_serv		= $linha['nm_serv'];
			$count_atend	= $linha['count_atend'];
			
			if ($nome_ant !== $nm_usu) {
				
				if ($tabela != null) {
					// salva tabela do atendente anterior
					$tabelas[] = $tabela;
				}

                $cabecalho = array("Serviço", "Qtde de Atendimentos");
				$tabela = new Tabela($nm_usu." - ".$title_suffix, 2, $cabecalho);
				//$tabela->addRow(array("SERVIÇO", "QTDE DE ATENDIMENTOS"));
				//$tabela->setRowBgColor(0, array(0xE5, 0xE5, 0xE5));
				$nome_ant = $nm_usu;
			}
			
			$tabela->addRow(array($desc_serv, $count_atend));
		}
		
		// salva tabela do ultimo atendente
		if ($tabela != null) {
			$tabelas[] = $tabela;
		}
		
		return $tabelas;
	}
	
	/**
	 * 
	 * @return Tabela Tabela contendo as estatisticas dos atendimentos encerrados (status = codificado)
	 */
	
	public static function get_estat_atendimentos_encerradas($ids_uni, $dt_min, $dt_max){
		
		$estat_senhas_encerradas = DB::getInstance()->get_estat_atendimentos_encerradas($ids_uni, $dt_min, $dt_max);
		$resul = count($estat_senhas_encerradas);
		$tabelas = array();
		
		if ($resul > 0){
			$tabela;
//			$tabela->addRow(array("","->Senhas com mais de um serviço codificado","","","","","","","","",""));
//			$tabela->setCellBgColor(0,0,array(29,109,6));
//			$tabela->addRow(array("Senha","Cliente","Data","Hr.Chamada","Hr.Inicio","Fim Atend.","Duração Atend.","Serviço","Guichê","Func.","Unidade"));
			
			for ($cont= 0; $cont< $resul+1; $cont++){
				$id_uni = $estat_senhas_encerradas[$cont]['id_uni'];
				$nm_uni = $estat_senhas_encerradas[$cont]['nm_uni'];
				if($id_uni != $old_id || $cont == 0){
					$linha = 1;
					$tabelas[$cont] = $tabela;
					$old_id = $estat_senhas_encerradas[$cont]['id_uni'];
                    $cabecalho = array("Senha","Cliente","Data","Hr.Chamada","Hr.Inicio","Fim Atend.","Duração Atend.","Serviço","Guichê","Func.");
                    $tabela = new Tabela($nm_uni, 11, $cabecalho);
					//echo($id_uni.' -if- '.$old_id.' --- '.$cont.'<br/>');
					//exit();
				}
				$id_serv = $estat_senhas_encerradas[$cont]['id_serv'];
				$nm_serv = $estat_senhas_encerradas[$cont]['nm_serv'];
				$senha = $estat_senhas_encerradas[$cont]['num_senha'];
				$nm_cli = $estat_senhas_encerradas[$cont]['nm_cli'];
				$dt_atend = $estat_senhas_encerradas[$cont]['dt_cheg'];
				$hr_cham = $estat_senhas_encerradas[$cont]['dt_cha'];
				$hr_ini = $estat_senhas_encerradas[$cont]['dt_ini'];
				$hr_fim = $estat_senhas_encerradas[$cont]['dt_fim'];
				$login_usu = $estat_senhas_encerradas[$cont]['login_usu'];
				$num_guiche = $estat_senhas_encerradas[$cont]['num_guiche'];
				$tempo = $estat_senhas_encerradas[$cont]['tempo'];
				$qtd_servicos = $estat_senhas_encerradas[$cont]['count'];
			
				if ($qtd_servicos>1){
					$tabela->setRowFontColor($linha,array(250,0,0));
				}
				$tabela->addRow(array($senha,$nm_cli,$dt_atend,$hr_cham,$hr_ini,$hr_fim,$tempo,$nm_serv,$num_guiche,$login_usu));
				$linha++;
			}
			return $tabelas;
		}
		return null;	
	}
	
	/**
	 * 
	 * @param $serv_mestre
	 * @return Tabela contendo serviços disponíveis global
	 */
	public static function get_serv_disponiveis(Servico $serv_mestre){
        $cabecalho = array("Subserviço", "Código", "Ativo");
		$tabela = new Tabela($serv_mestre->get_nome(), 3, $cabecalho);
		
		$sub_serv = DB::getInstance()->get_servicos_sub($serv_mestre->get_id());

		//$tabela->addRow(array("Subserviço", "Código", "Ativo"));
		//$tabela->setRowBgColor(0, array(0xE5, 0xE5, 0xE5));
		
		foreach ($sub_serv as $sub) {
			$stat_serv = ($sub->get_status() == 1)? 'Sim': 'Não';		
			$tabela->addRow(array($sub->get_nome(), $sub->get_id(), $stat_serv));
		}
		
		return $tabela;
	}
	
	/**
	 * 
	 * @param $unidade
	 * @return Tabela contendo serviços disponiveis por unidade
	 */
	public static function get_serv_disponiveis_uni(Unidade $unidade){
        $cabecalho = array("Código","Nome destacado", "Nome Original", "Ativo");
		$tabela = new Tabela($unidade->get_nome(), 4, $cabecalho);
		
		$serv = DB::getInstance()->get_serv_disponiveis_uni($unidade->get_id());
		$result = count($serv);
		
		
		//$tabela->addRow(array("Código","Nome destacado", "Nome Original", "Ativo"));
		//$tabela->setRowBgColor(0, array(0xE5, 0xE5, 0xE5));
		
		foreach ($serv as $s) {	
			$id_serv = $s['id_serv'];
			$nm_serv = $s['nm_serv'];
			$stat_serv = ($s['stat_serv'] == 1)? 'Sim': 'Não';
			$serv_mestre = $s['nm_serv_mestre'];
			
			$tabela->addRow(array($id_serv, $nm_serv,$serv_mestre,$stat_serv));
		}
		
		return $tabela;
		
	}
	
	/**
	 * 
	 * @return Tabela com legendas para a tabela  de estatisticas por atendimento
	 */
	public static function get_legendas_atendimento(){
		$tabela = new Tabela("Legenda", 2, null, 30, "ffcc33");
        $tabela->setColWidth(0, 0.1);
        $tabela->setAlign("left");
		$tabela->addRow(array("TE", "Tempo de Espera"));
		$tabela->addRow(array("TD", "Tempo de Deslocamento"));
		$tabela->addRow(array("TA", "Tempo de Atendimento"));
		$tabela->addRow(array("TP", "Tempo de Permanência na Agência"));
		
		return $tabela;
	}
	
	public static function get_legendas_tempos_medios(){
		$tabela = new Tabela("Legenda", 2, null, 30, "ffcc33");
        $tabela->setColWidth(0, 0.1);
		$tabela->setAlign("left");
		$tabela->addRow(array("TME", "Tempo Médio de Espera para início do atendimento"));
		$tabela->addRow(array("TMD", "Tempo Médio de Deslocamento para mesa de atendimento"));
		$tabela->addRow(array("TMA", "Tempo Médio de Atendimento ao cliente"));
		$tabela->addRow(array("TMP", "Tempo Médio de Permanência na Agência"));

		return $tabela;
	}
	
	public static function get_legendas_t_m_atend(){
		$tabela = new Tabela("Legenda", 2, null, 30, "ffcc33");
        $tabela->setColWidth(0, 0.1);
        $tabela->setAlign("left");
		$tabela->addRow(array("TMD", "Tempo Médio de Deslocamento para mesa de atendimento"));
		$tabela->addRow(array("TMA", "Tempo Médio de Permanência na Agência"));
		
		return $tabela;
	}
	/**
	 * 
	 * @param $ids_uni
	 * @param $dt_min
	 * @param $dt_max
	 * @return Tabela contendo as estatisticas por atendimento
	 */
	
	public static function get_estat_por_atendimento($ids_uni, $dt_min, $dt_max){
		$estat_senhas = DB::getInstance()->get_estat_atendimentos($ids_uni, $dt_min, $dt_max);
		$resul = count($estat_senhas);
		$tabelas = array();
		if ($resul > 0) {
			
			for ($cont= 0; $cont< $resul+1; $cont++) {
				$id_uni = $estat_senhas[$cont]['id_uni'];
				$nm_uni = $estat_senhas[$cont]['nm_uni'];
				if($id_uni != $old_id || $cont == 0){
					$tabelas[$cont] = $tabela;
					$old_id = $estat_senhas[$cont]['id_uni'];
                    $cabecalho = array("Senha","Data","Serv. Triado","Status","Func.","Chegada","Chamada","Inicio","Fim","TE","TD","TA","TP","Guichê");
					$tabela = new Tabela($nm_uni, 14, $cabecalho);
				}
				$id_serv = $estat_senhas[$cont]['id_serv'];
				$nm_serv = $estat_senhas[$cont]['nm_serv'];
				$senha = $estat_senhas[$cont]['num_senha'];
				$nm_cli = $estat_senhas[$cont]['nm_cli'];
				$dt_atend = $estat_senhas[$cont]['dt_cheg'];
				$hr_cheg = $estat_senhas[$cont]['hr_cheg'];
				$hr_cham = $estat_senhas[$cont]['hr_cha'];
				$hr_ini = $estat_senhas[$cont]['hr_ini'];
				$hr_fim = $estat_senhas[$cont]['hr_fim'];
				$login_usu = $estat_senhas[$cont]['login_usu'];
				$num_guiche = $estat_senhas[$cont]['num_guiche'];
				$tempo = $estat_senhas[$cont]['tempo'];
				$nm_stat = $estat_senhas[$cont]['nm_stat'];
				$tmp_fila = $estat_senhas[$cont]['tmp_fila'];
				$tmp_desl = $estat_senhas[$cont]['tmp_desl'];
				$tmp_atend = $estat_senhas[$cont]['tmp_atend'];
				$tmp_total = $estat_senhas[$cont]['tmp_total'];
				$qtd_servicos = $estat_senhas[$cont]['count'];
				
				$tabela->addRow(array($senha,$dt_atend,$nm_serv,$nm_stat,$login_usu,$hr_cheg,$hr_cham,$hr_ini,$hr_fim,$tmp_fila,$tmp_desl,$tmp_atend,$tmp_total,$num_guiche));
			}
			$tabelas[$resul+1] = Estatistica::get_legendas_atendimento();
			return $tabelas;
		}
		return null;
	}
}
?>