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
 * Classe Menu
 * 
 * Para controlar o menu dos modulos do sistema
 * 
 */ 
 class Menu {
 	
	private $id_menu;
	private $nome;
	private $link;
	private $descricao;
	private $ordem;
	
	# construtor
	public function __construct($id, $nome, $link, $descricao, $ordem = 0) {
		$this->set_id_menu($id);
		$this->set_nome($nome);
		$this->set_link($link);
		$this->set_descricao($descricao);
		$this->set_ordem($ordem);
	}
	
	/**
	 * Retorna id do Menu
	 * @return int
	 */
	public function get_id_menu() {
		return $this->id_menu;
	}
	
	/**
	 * Define id do Menu
	 * @param int $id
	 */
	public function set_id_menu($id) {
		if(is_int($id))
			$this->id_menu = $id;
		else
			throw new Exception("O id do Menu deve ser inteiro");
	}
	
	/**
	 * Retorna nome do Menu
	 * @return String
	 */
	public function get_nome() {
		return $this->nome;
	}
	
	/**
	 * Define nome do menu
	 * @param String $nome
	 */
	public function set_nome($nome) {
		$this->nome = $nome;
	}
	
	/**
	 * Retorna o link do Menu
	 * @return String
	 */
	public function get_link() {
		return $this->link;
	}
	
 	/**
	 * Define o link do Menu (pagina a ser aberta)
	 * @param String $link
	 */
	public function set_link($link) {
		$this->link = $link;
	}
	
 
	/**
	 * Retorna a descricao do Menu
	 * @return String
	 */
	public function get_descricao() {
		return $this->descricao;
	}
	
	/**
	 * Define a descricao do Menu
	 * @param String $descricao
	 */
	public function set_descricao($descricao) {
		$this->descricao = $descricao;
	}

	
	/**
	 * Retorna ordem
	 * @return int
	 */
	public function get_ordem() {
		return $this->ordem;
	}
	
	/**
	 * Define prioridade de exibicao
	 * @param int prioridade
	 */
	public function set_ordem($ordem) {
		if(is_int($ordem))
			$this->ordem = $ordem;
		else
			throw new Exception("A ordem deve ser inteiro");
	}
}
 
?>