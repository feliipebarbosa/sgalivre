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

/**
 * Classe Agenda
 *
 * Contem as informacoes da Agenda do Sistema
 *
 */
class Agenda {
    private $id;	
	private $day;
	private $hour_start_morning;
	private $hour_end_morning;
	private $hour_start_afternoon;
	private $hour_end_afternoon;
	private $atendente;
	private $unidade;

	public function __construct($id, $day = '', $hour_start_morning = '', $hour_end_morning='', $hour_start_afternoon='',$hour_end_afternoon) {
	    $this->set_id($id);
		$this->set_day($day);
		$this->set_hour_start_morning($hour_start_morning);
		$this->set_hour_end_morning($hour_end_morning);
		$this->set_hour_start_afternoon($hour_start_afternoon);
		$this->set_hour_end_afternoon($hour_end_afternoon);
	}
	
	/**
	 * Define o id da Agenda
	 *
	 */
	public function set_id($id) {
		if (is_int($id) && $id > 0)
			$this->id = $id;
		else
			throw new Exception("Erro ao definir id da Agenda. Deve ser um n&uacute;mero maior que zero.");
	}

	/**
	 * Retorna o id da Agenda
	 *
	 */
	public function get_id() {
	    return $this->id;
	}
	
	/**
	 * Define o dia da Agenda
	 * 
	 */
	public function set_day($day) {
			$this->day = $day;
	}

	/**
	 * Retorna o dia da Agenda
	 * 
	 */
	public function get_day() {
		return $this->day;
	}

	/**
	 * Define a hora início manhã da Agenda
	 * 
	 */
	public function set_hour_start_morning($hour_start_morning) {
		$this->hour_start_morning = $hour_start_morning;
	}

	/**
	 * Retorna a hora início manhã da Agenda
	 * 
	 */
	public function get_hour_start_morning() {
		return $this->hour_start_morning;
	}

	/**
	 * Define a hora fim manhã da Agenda
	 * 
	 */
	public function set_hour_end_morning($hour_end_morning) {
		$this->hour_end_morning = $hour_end_morning;
	}

	/**
	 * Retorna a hora fim manhã da Agenda
	 *
	 */
	public function get_hour_end_morning() {
		return $this->hour_end_morning;
	}	

	/**
	 * Define a hora início tarde da Agenda
	 * 
	 */
	public function set_hour_start_afternoon($hour_start_afternoon) {
		$this->hour_start_afternoon = $hour_start_afternoon;
	}

	/**
	 * Retorna a hora início tarde da Agenda
	 * 
	 */
	public function get_hour_start_afternoon() {
		return $this->hour_start_afternoon;
	}
	
	/**
	 * Define a hora fim tarde da Agenda
	 * 
	 */
	public function set_hour_end_afternoon($hour_end_afternoon) {
		$this->hour_end_afternoon = $hour_end_afternoon;
		
	}

	/**
	 * Retorna a hora fim tarde da Agenda
	 * 
	 */
	public function get_hour_end_afternoon() {
		return $this->hour_end_afternoon;
	}
	
	/**
	 * 
	 */
	public function set_atendente($atendente) {
		$this->atendente = $atendente;
	}
	
	/**
	 * 
	 */
	public function get_atendente() {
		return $this->atendente;
	}

	/**
	 * Define a unidade da Agenda
	 * 
	 */
	public function set_unidade($unidade) {
		$this->unidade = $unidade;
	}

	/**
	 * Retorna a unidade da Agenda
	 * 
	 */
	public function get_unidade() {
		return $this->unidade;
	}
	
	

}

?>
