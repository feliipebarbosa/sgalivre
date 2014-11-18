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
	private $dia;
	private $dia_semana;
	private $hour;
	private $id_usu;
	private $id_uni;
	private $id_cliente;

	public function __construct($id, $dia = '', $hour = '', $id_usu='', $id_uni='',$id_cliente='') {
	    $this->set_id($id);
		$this->set_dia($dia);
		$this->set_hour_start_morning($hour);
		$this->set_hour_end_morning($id_usu);
		$this->set_hour_start_afternoon($id_uni);
		$this->set_hour_end_afternoon($id_cliente);
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
	public function set_dia($dia) {
			$this->dia = $dia;
	}

	/**
	 * Retorna o dia da Agenda
	 * 
	 */
	public function get_dia() {
		return $this->dia;
	}

	public function set_dia_semana($dia_semana) {
			$this->dia_semana = $dia_semana;
	}

	/**
	 * Retorna o dia da Agenda
	 * 
	 */
	public function get_dia_semana() {
		return $this->dia_semana;
	}

	/**
	 * Define a hora início manhã da Agenda
	 * 
	 */
	public function set_hour($hour) {
		$this->hour = $hour;
	}

	/**
	 * Retorna a hora início manhã da Agenda
	 * 
	 */
	public function get_hour() {
		return $this->hour;
	}

	/**
	 * Define a hora fim manhã da Agenda
	 * 
	 */
	public function set_id_usu($id_usu) {
		$this->id_usu = $id_usu;
	}

	/**
	 * Retorna a hora fim manhã da Agenda
	 *
	 */
	public function get_id_usu() {
		return $this->id_usu;
	}	

	/**
	 * Define a hora início tarde da Agenda
	 * 
	 */
	public function set_id_uni($id_uni) {
		$this->id_uni = $id_uni;
	}

	/**
	 * Retorna a hora início tarde da Agenda
	 * 
	 */
	public function get_id_uni() {
		return $this->id_uni;
	}
	
	/**
	 * Define a hora fim tarde da Agenda
	 * 
	 */
	public function set_id_cliente($id_cliente) {
		$this->id_cliente = $id_cliente;
		
	}

	/**
	 * Retorna a hora fim tarde da Agenda
	 * 
	 */
	public function get_id_cliente() {
		return $this->id_cliente;
	}
	

}

?>
