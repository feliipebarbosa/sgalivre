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
 * Classe Usuario
 *
 * Contem as informacoes do usuario do Sistema
 *
 */
class Usuario {
	const USUARIO_ATIVO = 1;
    private $id;	
	private $login;
	private $nome;
	private $sobrenome;
	private $grupo = array();
	private $servicos = array();
	private $num_guiche;
	private $status;
	private $unidade;
	private $lotacao;
	//private $permissoes = array();

	public function __construct($id, $mat = '', $nome = '', $sobrenome='', $status=1) {
	    $this->set_id($id);
		$this->set_login($mat);
		$this->set_nome($nome);
		$this->set_sobrenome($sobrenome);
		$this->set_status($status);
	}
	
	/**
	 * Define o id do Usuario
	 *
	 * @param int $id
	 */
	public function set_id($id) {
		if (is_int($id) && $id > 0)
			$this->id = $id;
		else
			throw new Exception("Erro ao definir id do Usuario. Deve ser um n&uacute;mero maior que zero.");
	}

	/**
	 * Retorna o id do Usuario
	 *
	 * @return int
	 */
	public function get_id() {
	    return $this->id;
	}
	
	/**
	 * Define o login do Usuario
	 * 
	 * @param String $mat
	 */
	public function set_login($mat) {
			$this->login = $mat;
	}

	/**
	 * Retorna o login do Usuario
	 * 
	 * @return String
	 */
	public function get_login() {
		return $this->login;
	}

	/**
	 * Define o nome do Usuario
	 * 
	 * @param String $nome
	 */
	public function set_nome($nome) {
		$this->nome = $nome;
	}

	/**
	 * Retorna o nome do Usuario
	 * 
	 * @return String
	 */
	public function get_nome() {
		return $this->nome;
	}

	/**
	 * Define o sobrenome do Usuario
	 * 
	 * @param String $sobrenome
	 */
	public function set_sobrenome($sobrenome) {
		$this->sobrenome = $sobrenome;
	}

	/**
	 * Retorna o sobrenome do Usuario
	 * 
	 * @return String
	 */
	public function get_sobrenome() {
		return $this->sobrenome;
	}	

	/**
	 * Define o grupo do Usuario
	 * 
	 * @param int $grupo
	 */
	public function set_grupo($grupo) {
		if (is_array($grupo))
			$this->grupo = $grupo;
		else
			throw new Exception("Erro ao definir grupo do Usuario, deve ser um array.");
	}

	/**
	 * Retorna o grupo do Usuario
	 * 
	 * @return int
	 */
	public function get_grupo() {
		return $this->grupo;
	}
	
	/**
	 * Define a unidade(Unidade) atual do Usuario
	 * 
	 * @param Unidade $unidade
	 */
	public function set_unidade(Unidade $unidade = null) {
		$this->unidade = $unidade;
		
		if ($unidade != null) {
			$this->set_lotacao(DB::getInstance()->get_lotacao_valida($this->get_id(), $unidade->get_grupo()->get_id()));
			$this->set_servicos(DB::getInstance()->get_usuario_servicos_unidade($this->get_id(), $unidade->get_id()));
		}
		else {
			$this->set_lotacao(null);
			$this->set_servicos(array());
		}
	}

	/**
	 * Retorna a unidade atual do Usuario
	 * 
	 * @return Unidade A unidade atual do usuario
	 */
	public function get_unidade() {
		return $this->unidade;
	}
	
	/**
	 * 
	 * @param $lotacao Lotacao
	 */
	public function set_lotacao(Lotacao $lotacao = null) {
		$this->lotacao = $lotacao;
	}
	
	/**
	 * 
	 * @return Lotacao 
	 */
	public function get_lotacao() {
		return $this->lotacao;
	}

	/**
	 * Define os servicos do Usuario
	 * 
	 * @param array $servicos
	 */
	public function set_servicos($servicos) {
		if (is_array($servicos))
			$this->servicos = $servicos;
		else
			throw new Exception("Erro ao definir servicos do Usuario, deve ser um array.");
	}

	/**
	 * Retorna os servicos do Usuario
	 * 
	 * @return array
	 */
	public function get_servicos() {
		return $this->servicos;
	}
	
	/**
	 * Define o numero do guiche do Usuario
	 *
	 * @param int $num_guiche
	 */
	public function set_num_guiche($num_guiche) {
		if (is_int($num_guiche) && $num_guiche > 0)
			$this->num_guiche = $num_guiche;
		else
			throw new Exception("Erro ao definir guiche do Usuario. Deve ser um n&uacute;mero maior que zero.");
	}
	
	/**
	 * Retorna o numero do guiche do Usuario
	 *
	 * @return int
	 */
	public function get_num_guiche() {
	    return $this->num_guiche;
	}

	/**
	 * Define o status do Usuario
	 * 
	 * @param int $status
	 */
	public function set_status($status) {
		if (is_int($status))
			$this->status = $status;
		else
			throw new Exception("Erro ao definir status do Atendente, deve ser um inteiro.");
	}

	/**
	 * Retorna o status do Usuario
	 * 
	 * @return int
	 */
	public function get_status() {
		return $this->status;
	}

	/**
	 * Retorna String com login, nome e grupo do usuario
	 * @return String
	 */
	public function tostring() {
		return "{$this->get_login()} - {$this->get_nome()}, {$this->get_grupo()}";
	}
	
	/**
	 * Retorna resultado do método toString
	 * @return String
	 */
	public function __tostring() {
		return $this->tostring();
	}


}

?>
