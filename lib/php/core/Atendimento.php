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
 * Classe Atendimento
 * 
 * contem o Cliente, o Servico e o Status do atendimento
 */

class Atendimento {

    private $id;
	private $cliente;
	private $num_guiche;
	private $servico;
	private $status = 0;
	private $dt_cheg;
	private $dt_cha;
	private $dt_ini;
	private $dt_fim;
	private $usuario;
    
	// estados do atendimento
	const SENHA_EMITIDA = 1;
	const CHAMADO_PELA_MESA = 2;
	const ATENDIMENTO_INICIADO = 3;
	const ATENDIMENTO_ENCERRADO = 4;
	const NAO_COMPARECEU = 5;
	const SENHA_CANCELADA = 6;
	const ERRO_TRIAGEM = 7;
	const ATENDIMENTO_ENCERRADO_CODIFICADO = 8;

    public static $ARRAY_TODOS_STATUS = array(Atendimento::SENHA_EMITIDA,Atendimento::CHAMADO_PELA_MESA, Atendimento::ATENDIMENTO_INICIADO, Atendimento::ATENDIMENTO_ENCERRADO, Atendimento::NAO_COMPARECEU, Atendimento::ERRO_TRIAGEM, Atendimento::ATENDIMENTO_ENCERRADO_CODIFICADO);
	
	# construtor
	public function __construct($id, $cliente, $servico, $status, $dt_cheg, $dt_cha , $dt_ini , $dt_fim, $num_guiche=0, $usuario=null) {
	    $this->set_id($id);
		$this->set_cliente($cliente);
		$this->set_servico($servico);
		$this->set_status($status);
		$this->set_dt_cheg($dt_cheg);
		$this->set_dt_cha($dt_cha);
		$this->set_dt_ini($dt_ini);
		$this->set_dt_fim($dt_fim);
		$this->set_num_guiche($num_guiche);
		$this->set_usuario($usuario);
		
	}
	
	/**
	 * Retorna a data fim do Atendimento
	 * @return date
	 */
	public function get_dt_fim() {
		return $this->dt_fim;
	}
	
	/**
	 * Modifica data final
	 * @param $dt_fim
	 * @return none
	 */
	public function set_dt_fim($dt_fim) {
		$this->dt_fim = $dt_fim;
	}
	
	/**
	 * Retorna data de chamada
	 * @return $dt_cha
	 */
	public function get_dt_cha() {
		return $this->dt_cha;
	}
	
	/**
	 * Modifica data de chamada
	 * @param $dt_cha
	 * @return none
	 */
	public function set_dt_cha($dt_cha) {
		$this->dt_cha = $dt_cha;
	}
	
	/**
	 * Retorna data de inicio
	 * @return $dt_ini
	 */
	public function get_dt_ini() {
		return $this->dt_ini;
	}
	
	/**
	 * Modifica data de inicio
	 * @param $dt_ini
	 * @return none
	 */
	public function set_dt_ini($dt_ini) {
		$this->dt_ini = $dt_ini;
	}
	
	/**
	 * Retorna data de chegada
	 * @return $dt_cheg
	 */
	public function get_dt_cheg() {
		return $this->dt_cheg;
	}
	
	/**
	 * Modifica data de chegada
	 * @param $dt_cheg
	 * @return none
	 */
	public function set_dt_cheg($dt_cheg) {
		$this->dt_cheg = $dt_cheg;
	}
	
	
	/**
	 * Retorna o id do Atendimento
	 *
	 * @return int
	 */
	public function get_id() {
		return $this->id;
	}
	
	
	/**
	 * Define o id do Atenidmento
	 *
	 * @param int $id
	 */
	public function set_id($id) {
		if (is_int($id) && $id > 0)
			$this->id = $id;
		else
			throw new Exception("Erro ao definir id do atendimento. Deve ser maior que zero.");
	}

	/**
	 * Retorna o Cliente do Atendimento
	 *
	 * @return Cliente
	 */
	public function get_cliente() {
		return $this->cliente;
	}
	
	/**
	 * Define o Cliente do Atendimento
	 *
	 * @param Cliente $cliente
	 */
	public function set_cliente($cliente) {
		$this->cliente = $cliente;
	}
	
	/**
	 * Retorna o numero do guiche
	 * @return $nm_guiche
	 */
	public function get_num_guiche() {
		return $this->num_guiche;
	}
	
	/**
	 * Modifica o guiche
	 * @param $nm_guiche
	 * @return none
	 */
	public function set_num_guiche($nm_guiche) {
		$this->num_guiche = $nm_guiche;
	}
	
	/**
	 * Retorna o Servico do Atendimento
	 *
	 * @return Servico
	 */
	public function get_servico() {
		return $this->servico;
	}

	/**
	 * Define o Servico do Atendimento
	 *
	 * @param Servico $servico
	 */
	public function set_servico($servico) {
		$this->servico = $servico;
	}
	
	/**
	 * Retorna o Status do Atendimento
	 *
	 * @return int
	 */
	public function get_status() {
		return $this->status;
	}
	
	/**
	 * Define o Status do Atenidmento
	 *
	 * @param int $status
	 */
	public function set_status($status) {
		if (is_int($status) && $status > 0)
			$this->status = $status;
		else
			throw new Exception("Erro ao definir status do atendimento. Deve ser maior que zero.");
	}
	
	
	/**
	 * Define o usuario do Atenidmento
	 *
	 * @param obj $usuario
	 */
	public function set_usuario($usuario){
		$this->usuario = $usuario;
	}
	
	
	/**
	 * Retorna o Usuario do Atendimento
	 *
	 * @return obj usuario
	 */
	public function get_usuario(){
		return $this->usuario;
	}
	
	/**
	 * Retorna String com Id, senha e status
	 * @return String
	 */
	public function toString() {
	    return "[ Id: {$this->get_id()} Senha: {$this->get_cliente()->get_senha()} Status: {$this->get_status()} ]";
	}
	
	/**
	* Retorna resultado do método tostring
	 * @return String
	 */
	public function __tostring() {
		return $this->toString(); 
	}
	
	
}

?>
