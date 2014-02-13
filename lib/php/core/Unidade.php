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
 * Classe Unidade
 * 
 * Unidade do Sistema
 * 
 */
class Unidade {

	private $id;
	private $grupo;
	private $codigo;
	private $nome;
	private $tema;
	private $stat_uni;
	

	public function __construct($id, $grupo,$codigo, $nome, $stat_uni) {
		$this->set_id($id);
		$this->set_codigo($codigo);
		$this->set_nome($nome);
		$this->set_stat_uni($stat_uni);
	}

	/**
	 * Define o id da Unidade
	 *
	 * @param int $id
	 */
	public function set_id($id) {
		$this->id = $id;
	}

	/**
	 * Retorna o id da Unidade
	 *
	 * @return int
	 */
	public function get_id() {
		return $this->id;
	}
	
/**
	 * Define o Grupo ao qual a Unidade pertence
	 *
	 * @param Grupo $grupo
	 */
	public function set_grupo(Grupo $grupo) {
		$this->grupo = $grupo;
	}

	/**
	 * Retorna o Grupo ao qual a Unidade pertence
	 *
	 * @return Grupo
	 */
	public function get_grupo() {
		return $this->grupo;
	}
	
	/**
	 * Define o codigo da Unidade
	 *
	 * @param int $codigo
	 */
	public function set_codigo($codigo) {
		$this->codigo = $codigo;
	}

	/**
	 * Retorna o codigo da Unidade
	 *
	 * @return int
	 */
	public function get_codigo() {
		return $this->codigo;
	}

	/**
	 * Define o nome da Unidade
	 *
	 * @param String $nome
	 */
	public function set_nome($nome) {
		$this->nome = $nome;
	}

	/**
	 * Retorna o nome da Unidade
	 *
	 * @return String
	 */
	public function get_nome() {
		return $this->nome;
	}

	/**
	 * Define o Tema da Unidade
	 *
	 * @param Tema $tema
	 */
	public function set_tema($tema) {
		$this->tema = $tema;
	}

	/**
	 * Retorna o Tema da Unidade
	 *
	 * @return Tema
	 */
	public function get_tema() {
		return $this->tema;
	}
	
	/**
	 * Retorna o status da unidade
	 * @return stat_uni
	 */
	public function get_stat_uni(){
		return $this->stat_uni;
	}
	
	/**
	 * Modifica o status da unidade
	 * @param $stat_uni
	 * @return none
	 */
	public function set_stat_uni($stat_uni){
		$this->stat_uni = $stat_uni;
	}
	
	/**
	 * Retorna String com id e nome da unidade
	 * @return String
	 */
	public function toString() {
		return "Unidade_Object_id:" . $this->get_id() . "_name:" . $this->get_nome();
	}
	
	/**
	 * Retorna resultado do método toString
	 * @return String
	 */
	public function __tostring(){ 
		return $this->toString(); 
	}

}

?>
