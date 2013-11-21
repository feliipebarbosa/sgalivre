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

class Lotacao {
	
	private $usuario;
	private $grupo;
	private $cargo;
	
	public function __construct(Usuario $usuario, Grupo $grupo, Cargo $cargo) {
		$this->set_usuario($usuario);
		$this->set_grupo($grupo);
		$this->set_cargo($cargo);
	}
	
	/**
	 * Modifica usuario
	 * @param $usuario
	 * @return none
	 */
	public function set_usuario(Usuario $usuario) {
		$this->usuario = $usuario;
	}
	
	/**
	 * Retorna objeto usuario
	 * @return Usuario $usuario
	 */
	public function get_usuario() {
		return $this->usuario;
	}
	
	/**
	 * Modifica grupo
	 * @param $grupo
	 * @return none
	 */
	public function set_grupo(Grupo $grupo) {
		$this->grupo = $grupo;
	}
	
	/**
	 * Retorna objeto Grupo
	 * @return Grupo $grupo
	 */
	public function get_grupo() {
		return $this->grupo;
	}
	
	/**
	 * Modifica cargo
	 * @param $cargo
	 * @return none
	 */
	public function set_cargo(Cargo $cargo) {
		$this->cargo = $cargo;
	}
	
	/**
	 * Retorna objeto Cargo
	 * @return Cargo $cargo
	 */
	public function get_cargo() {
		return $this->cargo;
	}
}
?>