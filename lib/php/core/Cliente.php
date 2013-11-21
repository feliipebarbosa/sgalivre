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
 * 
 * Classe Cliente
 *
 * Contem informacoes sobre o Cliente a ser atendido
 *
 * @author dataprev
 *
 */

class Cliente {

	private $nome;
	private $ident;
	private $senha;

	public function __construct($nome='', $senha = '', $ident = '') {
		$this->set_nome($nome);
		$this->set_senha($senha);
		$this->set_ident($ident);
	}

	/**
	* Define o nome do Cliente
	* @param String $nome
	*/
	public function set_nome($nome) {
		$this->nome = $nome;
	}

	/**
	* Retorna o nome do Cliente
	* @return String
	*/
	public function get_nome() {
		return $this->nome;
	}
	
	/**
	* Define a identidade do Cliente
	* @param String $ident
	*/
	public function set_ident($ident) {
		$this->ident = $ident;
	}

	/**
	* Retorna a identidade do Cliente
	* @return String
	*/
	public function get_ident() {
		return $this->ident;
	}

	/**
	* Define a senha do Cliente
	* @param Senha $senha
	*/
	public function set_senha($senha) {
		$this->senha = $senha;
	}

	/**
	* Retorna a senha do Cliente
	* @return Senha
	*/
	public function get_senha() {
		return $this->senha;
	}

	/**
	 * Retorna String com senha, prioridade e nome do cliente
	 * @return String
	 */
	public function tostring() {
		$pri = ($this->senha->get_prioridade())?"***":"";
		return "{$this->get_senha()->tostring()} - $pri {$this->get_nome()}";
	}
	
	/**
	 * Retorna resultado do método tostring
	 * @return String
	 */
	public function __tostring() {
		return $this->tostring();
	}

}



?>
