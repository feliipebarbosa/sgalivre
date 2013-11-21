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
 * Session Wrapper
 * 
 * Para abstrair o vetor de sessão padrão do PHP
 *
 */
class Session {
    /** Sessão encerrada: Usuário deslogado. */
    const SESSION_ENCERRADA = 0;

    /** Sessão ativa: Usuário logado. */
    const SESSION_ATIVA = 1;

    /**
     * Sessão com dados fora de sincronia com o Banco de Dados,
     * o próximo acesso a qualquer página do sistema irá efetuar
     * uma recarga transparente da Sessão e retornar ao status SESSION_ATIVA.
     */
    const SESSION_DESATUALIZADA = 2;

	public static $session;
	
	
	public function __construct() {
		session_start();
	}
	
	
	/**
	 * @return Session session
	 */
	public static function getInstance() {
		if (Session::$session === null)			
			Session::$session = new Session();
		return Session::$session;
	}
	
	/**
	 * Define ou adicionar um valor na sessao
	 *
	 * @param String $chave
	 * @param mixed $valor
	 */
	public function set($chave, $valor) {
		$_SESSION[MODULO][$chave] = $valor;
	}
	
	/**
	 * Define ou adicionar um valor na sessao Global
	 *
	 * @param String $chave
	 * @param mixed $valor
	 */
	public function setGlobal($chave, $valor) {
		$_SESSION['GLOBAL'][$chave] = $valor;
	}
	
	/**
	 * Retorna o valor da chave guardada na sessao
	 *
	 * @param String $chave
	 * @return mixed
	 */
	public function get($chave) {
		if (isset($_SESSION[MODULO][$chave]))
			return $_SESSION[MODULO][$chave];
		if (isset($_SESSION['GLOBAL'][$chave]))
			return $_SESSION['GLOBAL'][$chave];
		return false;
	}
	
	/**
	 * Retorna se a chave informada ja esta
	 * guardada na sessao
	 *
	 * @param String $chave
	 * @return bool
	 */
	public function exists($chave) {
		return (isset($_SESSION[MODULO][$chave])) || (isset($_SESSION['GLOBAL'][$chave]));
	}

	/**
	 * Remove da sessao a chave informada.
	 * Retorna true se a chave existe e false caso nao exista
	 *
	 * @param String $chave
	 * @return bool
	 */
	public function del($chave) {
		if (isset($_SESSION[MODULO][$chave])) {
			unset($_SESSION[MODULO][$chave]);
			return true;
		}
		if (isset($_SESSION['GLOBAL'][$chave])) {
			unset($_SESSION['GLOBAL'][$chave]);
			return true;
		}
		return false;
	}

    /**
     * Marca os dados da Session do usuário especificado como desatualizados.<br>
     * Os dados da Session serão recarregados de forma transparente ao usuário.<br>
     * Este método deve ser invocado quando houver alguma alteração no usuário que faça os dados
     * armazenados na session saírem de sincronia com os dados do Banco de Dados.<br>
     * <br>
     * Caso a session não exista(o usuário especificado não esteja logado) esse método não tem efeito.<br>
     *
     * @param int $id_usu O ID do usuario da Session a ser invalidada.
     */
    public static function invalidate($id_usu) {
        DB::getInstance()->set_session_status($id_usu, Session::SESSION_DESATUALIZADA);
    }

	/**
	 * Remove todos valores armazenados, mas mantem a session viva
	 */
	public function reset() {
		$_SESSION = array();
	}
	
	/**
	 * Destroi (encerra) a sessao, removendo 
	 * todos os valores guardados
	 *
	 */
	public function destroy() {
		$_SESSION = array();
		session_destroy();
	}
	
	
}

?>