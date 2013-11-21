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
 * Classe Cargo
 * 
 * Um cargo define permissões de acesso a módulos do sistema
 * 
 */
class Cargo {

	private $id = 0;
	private $nome = '';
	private $descricao = '';
	private $permissoes = array();
	private $pai = null;
	private $is_raiz;
    private $filhos = array();
	
	public function __construct($id, $nome, $descricao, $is_raiz = false) {
		$this->set_id($id);
		$this->set_nome($nome);
		$this->set_descricao($descricao);
		$this->set_raiz($is_raiz);
	}

	/**
	 * Define o id do Cargo
	 *
	 * @param int $id
	 */
	public function set_id($id) {
		$this->id = $id;
	}

	/**
	 * Retorna o id do Cargo
	 *
	 * @return int
	 */
	public function get_id() {
		return $this->id;
	}

	/**
	 * Define o nome do Cargo
	 *
	 * @param String $nome
	 */
	public function set_nome($nome) {
		$this->nome = $nome;
	}
	
	/**
	 * Retorna a descrição do Cargo
	 *
	 * @return int
	 */
	public function get_descricao() {
		return $this->descricao;
	}

	/**
	 * Define a descrição do Cargo
	 *
	 * @param String $nome
	 */
	public function set_descricao($descricao) {
		$this->descricao = $descricao;
	}

	/**
	 * Retorna o nome do Cargo
	 *
	 * @return String
	 */
	public function get_nome() {
		return $this->nome;
	}
	
	/**
	 * Define se o Cargo eh o cargo raiz(mais alto na hierarquia)
	 *
	 * @param bool $is_raiz
	 */
	public function set_raiz($is_raiz) {
		$this->is_raiz = $is_raiz;
	}

	/**
	 * Retorna se o Cargo eh o cargo raiz(mais alto na hierarquia)
	 *
	 * @return bool $is_raiz
	 */
	public function is_raiz() {
		return $this->is_raiz;
	}
	
	
	/**
	 * Define o superior deste Cargo
	 *
	 * @param Cargo $cargo
	 */
	public function set_pai(Cargo $cargo = null) {
		$this->pai = $cargo;
	}
	
	/**
	 * Retorna o superior deste cargo ou null se este cargo é o raiz
	 *
	 * @return String
	 */
	public function get_pai() {
		// Instancia o pai sob demanda
		if ($this->pai == null && !$this->is_raiz()) {
			$this->set_pai(DB::getInstance()->get_cargo_pai_by_id($this->get_id()));
		}
		return $this->pai;
	}

	/**
	 * Adiciona filho
     * @param $cargo
     * @return none
	 */
    public function add_filho(Cargo $cargo) {
        $this->filhos[] = $cargo;
    }

    /**
     * Retorna array de filhos
     * @return $filhos
     */
    public function get_filhos() {
        return $this->filhos;
    }

    /**
     * Adicinoa permissão para acessar módulo
	 * @param $pm
	 * @return none
     */
	public function add_permissao(PermissaoModulo $pm) {
		$this->permissoes[] = $pm;	
	}
	
	/**
	 * Modifica permissões
	 * @return $permissoes array
	 */
	public function get_permissoes() {
		// lazy loading (carrega sob demanda)
		if (!$this->permissoes) {
			$this->permissoes = DB::getInstance()->get_permissoes_cargo($this->get_id());
		}
		return $this->permissoes;	
	}
	
	/**
	 * Verfica se tem permissão para acessar módulo
	 * @param $modulo
	 * @return bool
	 */
	public function has_permissao($modulo) {
		if ($modulo instanceof Modulo) {
			$id_mod = $modulo->get_id();
		}
		else {
			$id_mod = (int) $modulo;
		}
		
		foreach ($this->get_permissoes() as $pc) {
			if ($pc->get_modulo()->get_id() == $id_mod) {
				return true;
			}
		}
		return false;
	}
}
?>