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
 * Classe Servico
 * 
 * Responsavel pelas informacoes do Servico
 * 
 */
class Servico {
	const SERVICO_ATIVO = 1;
	const SERVICO_INATIVO = 0;
	
	private $id;
	private $nome;
	private $descricao = '';
	private $mestre = 0;
	private $sigla = '';
	private $status = Servico::SERVICO_ATIVO;
	
	public function __construct($id, $nome, $sigla = ' ', $mestre=0) {
		$this->set_id($id);
		$this->set_nome($nome);
		$this->set_mestre($mestre);
		$this->set_sigla($sigla);
	}
	
	/**
	 * Define o status do serviço
	 * @param String $nome
	 */	
	public function set_status($status) {
		$this->status = $status;
	}
	
	/**
	 * Retorna o status do servico
	 * @return String
	 */
	public function get_status() {
		return $this->status;
	}
	
	/**
	 * Define o numero do servico
	 * @param int $id
	 */
	public function set_id($id) {
		if (is_int($id) && $id >= 0)
			$this->id = $id;
		else
			throw new Exception("Erro ao definir numero do Servico, tem que ser um inteiro maior que zero.");
	}
	
	/**
	 * Retorna o numero do servico
	 * @return int
	 */
	public function get_id() {
		return $this->id;
	}
	
	/**
	 * Define o nome do servico
	 * @param String $nome
	 */	
	public function set_nome($nome) {
		$this->nome = $nome;
	}
	
	/**
	 * Retorna o nome do servico
	 * @return String
	 */
	public function get_nome() {
		return $this->nome;
	}


	/**
	 * Define a decricao do Servico
	 * @param String $desc
	 */	
	public function set_descricao($desc) {
		$this->descricao = $desc;
	}
	
	/**
	 * Retorna a descricao do servico
	 * @return String
	 */
	public function get_descricao() {
		return $this->descricao;
	}

	/**
	 * Define a sigla do Servico
	 * @param char $sigla
	 */	
	public function set_sigla($sigla) {
		$this->sigla = $sigla;
	}
	
	/**
	 * Retorna a sigla do servico
	 * @return char
	 */
	public function get_sigla() {
		return $this->sigla;
	}

	
	/**
	 * Define o numero do servico mestre do servico
	 * @param int $mestre
	 */	
	public function set_mestre($mestre) {
		if (is_int($mestre) && $mestre >= 0)
			$this->mestre = $mestre;
		else
			throw new Exception("Erro ao definir Servico Mestre, tem que ser um inteiro positivo.");
	}
	
	/**
	 * Retorna o numero do servico mestre do servico
	 * @return int
	 */
	public function get_mestre() {
		return $this->mestre;
	}
	
	/**
	 * Retorna se o servico e ou nao mestre
	 * @return bool
	 */
	public function is_mestre() {
		return ($this->mestre == 0)?true:false;
	}
	
}


?>
