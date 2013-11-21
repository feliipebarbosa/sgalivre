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
 * Classe Modulo
 * 
 * Para controle dos modulos do sistema
 * 
 */
 
 class Modulo {

    // status
 	const MODULO_INATIVO = 0;
 	const MODULO_ATIVO = 1;

    // TIPO
    const MODULO_UNIDADE = 0;
    const MODULO_GLOBAL = 1;

 	private $id;
	private $chave = '';
	private $nome = '';
	private $autor = '';
	private $descricao = '';
	private $dir = '';
	private $img = '';
    private $tipo;

	public function __construct($id, $chave, $nome, $autor, $img, $tipo) {
		$this->set_id($id);
		$this->set_chave($chave);
		$this->set_nome($nome);
		$this->set_autor($autor);
		$this->set_img($img);
		$this->set_dir("modules/".str_replace('.', '/', $chave));
        $this->set_tipo($tipo);
	}

 /**
	 * Define o id do Grupo
	 *
	 * @param int $id
	 */
	public function set_id($id) {
		$this->id = $id;
	}

	/**
	 * Retorna o id do Grupo
	 *
	 * @return int
	 */
	public function get_id() {
		return $this->id;
	}
	
	/**
	 * Define a chave do Modulo
	 *
	 * @param String $chave
	 */
	public function set_chave($chave) {
		$this->chave = $chave;
	}

	/**
	 * Retorna a chave do Modulo
	 *
	 * @return String
	 */
	public function get_chave() {
		return $this->chave;
	}

	/**
	 * Define o nome do Modulo
	 *
	 * @param String $nome
	 */
	public function set_nome($nome) {
		$this->nome = $nome;
	}

	/**
	 * Retorna o nome do Modulo
	 *
	 * @return String
	 */
	public function get_nome() {
		return $this->nome;
	}

	/**
	 * Define o autor do Modulo
	 *
	 * @param String $autor
	 */
	public function set_autor($autor) {
		$this->autor = $autor;
	}

	/**
	 * Retorna o autor do Modulo
	 *
	 * @return String
	 */
	public function get_autor() {
		return $this->autor;
	}

	/**
	 * Define a descricao do Modulo
	 *
	 * @param String $descricao
	 */
	public function set_descricao($descricao) {
		$this->descricao = $descricao;
	}

	/**
	 * Retorna a descricao do Modulo
	 *
	 * @return String
	 */
	public function get_descricao() {
		return $this->descricao;
	}

	/**
	 * Define o diretorio do Modulo
	 *
	 * @param String $dir
	 */
	public function set_dir($dir) {
		$this->dir = $dir;
	}

	/**
	 * Retorna o diretorio do Modulo
	 *
	 * @return String
	 */
	public function get_dir() {
		return $this->dir;
	}
	
	/**
	 * Define a imagem do Modulo
	 *
	 * @param String $img
	 */
	public function set_img($img) {
		$this->img = $img;
	}

    /**
	 * Retorna o endereço da imagem
	 *
	 * @return String
	 */
	public function get_img() {
		return $this->img;
	}

	/**
	 * Define o tipo do Modulo
	 *
	 * @param int $tipo
	 */
	public function set_tipo($tipo) {
		$this->tipo = $tipo;
	}

    public function is_global() {
        return $this->tipo == Modulo::MODULO_GLOBAL;
    }

	/**
	 * Retorna o Modulo esta instalado no Sistema
	 *
	 * @return bool
	 */
	public function is_instalado() {
		return DB::is_instalado($this->get_chave());
	}

	/**
	 * Verifica a integridade do Modulo e retorna se 
	 * esta corretamente instalado
	 *
	 * @return bool
	 */
	public function verifica() {
		/**
		 * TODO: verificacao do modulo
		 */
	    return true;
		if ($this->is_instalado()) {
			$tema = DB::getInstance()->get_tema_atual();
			$dir = $tema->get_dir();
			$css = $this->get_css();
			$js = $this->get_js();
			$erro = '';
			if (!empty($css) && !file_exists("../temas/$dir/css/".$css))
				$erro .= 'Folha de Estilos (CSS) do M&oacute;dulo n&atilde;o encontrada.<br />';
			if (!empty($js) && !file_exists("../temas/$dir/js/".$js))
				$erro .= 'Javascript do M&oacute;dulo n&atilde;o encontrado.<br />';
			if (!empty($erro))
				throw new Exception($erro);
		} else {
			throw new Exception('M&oacute;dulo n&atilde;o instalado');
		}
		return true;
	}
	
	/**
	 * Retorna String com Chave do módulo
	 * @return String
	 */
	public function toString() {
	    return "Modulo_Object_(". $this->get_chave() .")";
	}
	
	/**
	 * Retorna o resultado do método toString
	 * @return String
	 */
	public function __tostring() { 
		return $this->toString(); 
	}

}

?>
