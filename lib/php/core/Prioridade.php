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
 * Classe Prioridade
 * 
 * Usada para personalizar as Senhas
 */
class Prioridade {

	private $id = 0;
	private $nome = '';
	private $descricao = '';
	private $peso;

	public function __construct($id, $nome, $descricao, $peso) {
		$this->set_id($id);
		$this->set_nome($nome);
		$this->set_descricao($descricao);
		$this->set_peso($peso);
	}

	/**
	 * Define o id da Prioridade
	 *
	 * @param int $id
	 */
	public function set_id($id) {
		$this->id = $id;
	}

	/**
	 * Retorna o id da Prioridade
	 *
	 * @return int
	 */
	public function get_id() {
		return $this->id;
	}

	/**
	 * Define o nome da Prioridade
	 *
	 * @param String $nome
	 */
	public function set_nome($nome) {
		$this->nome = $nome;
	}

	/**
	 * Retorna o nome da Prioridade
	 *
	 * @return String
	 */
	public function get_nome() {
		return $this->nome;
	}

	/**
	 * Define a descricao da Prioridade
	 *
	 * @param String $desc
	 */
	public function set_descricao($desc) {
		$this->descricao = $desc;
	}

	/**
	 * Retorna a descricao da Prioridade
	 *
	 * @return String
	 */
	public function get_descricao() {
		return $this->descricao;
	}

	/**
	 * Define o peso da Prioridade
	 * Serve para ordenar as prioridades, do maior peso
	 * para o menor.
	 *
	 * @param int $peso
	 */
	public function set_peso($peso) {
    	if (is_int($peso) && $peso >= 0)
    		$this->peso = $peso;
        else
 			throw new Exception("O peso da prioridade deve ser um inteiro positivo");
	}

	/**
	 * Retorna o peso da Prioridade
	 *
	 * @return int
	 */
	public function get_peso() {
		return $this->peso;
	}
	
	/**
	 * Retorna String com o nome
	 * @return String
	 */
	public function toString() {
	    return $this->get_nome();
	}
	
	/*
	 * Retorna resultado do método toString
	 * @return String
	 */
	public function __tostring() { 
		return $this->toString(); 
	}
	

}

?>
