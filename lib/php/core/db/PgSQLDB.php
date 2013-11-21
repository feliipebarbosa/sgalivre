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
 * Classe DB
 *
 * Responsavel pela abstracao do Banco de Dados
 *
 */
class PgSQLDB extends DB {

	// static
	/** Mantem um instancia unica dessa classe */
    private static $instance;
    
    // fields
    /** Objeto que retorna as queries apropriadas a um banco específico(PostgreSQL, MySQL, Oracle...) */
    private $m_queries;
	
    /**
     *  Construtor private, evita que essa classe seja instanciada de fora formalizando seu uso atraves do singleton.
     *  
	 * @return DB db
     */
	protected function __construct() {
        $this->connect(Config::DB_HOST, Config::DB_PORT, Config::DB_USER, Config::DB_PASS, Config::DB_NAME);
		// TODO instanciar a classe a partir do banco especificado
		$queries = "PgSQLQueries";
    	$this->set_queries(new $queries);
    }
	
	/**
	 * Conecta ao banco
	 */
	public function connect($host, $port, $usuario, $senha, $banco) {
		try {
            $pdo = new PDO('pgsql:host='.$host.';port='.$port.';dbname='.$banco, $usuario, $senha);
			$this->set_connection($pdo/*new ProfilePDO($pdo)*/);
			$this->get_connection()->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}
		catch (PDOException $e) {
			// O trace da exception exibe a senha do banco(entre outros)
			// nao dar re-thrown!
			exit("<pre><h1>ERRO FATAL</h1>\n\nErro conectando ao Banco de Dados\nMotivo: ".$e->getMessage()."\n\nPor motivos de segurança o Trace da exception não pode ser exibido.</pre>");
		}
	}
	
	/**
	 * Insere um novo cargo no sistema e suas permissoes de acesso aos modulos
	 * 
	 * @param $nm_cargo
	 * @param $desc_cargo
	 * @param $permissoes
	 * @return Cargo O cargo que foi criado
	 */
	public function inserir_cargo($id_cargo_pai, $nm_cargo, $desc_cargo) {
		
		$sql = $this->get_queries()->inserir_cargo();
		$statement = $this->get_connection()->prepare($sql);
		
		$statement->bindValue(':id_cargo_pai', $id_cargo_pai, PDO::PARAM_INT);
		$statement->bindValue(':nm_cargo', $nm_cargo, PDO::PARAM_STR);
		$statement->bindValue(':desc_cargo', $desc_cargo, PDO::PARAM_STR);
		
		$statement->execute();
		
		$id_cargo = $this->get_connection()->lastInsertId('cargos_aninhados_id_cargo_seq');
		
		return new Cargo($id_cargo, $nm_cargo, $desc_cargo);
	}
	
	public function criar_grupo($id_grupo_pai, $nm_grupo, $desc_grupo) {
		$sql = $this->get_queries()->criar_grupo();
		
		$statement = $this->get_connection()->prepare($sql);
		$statement->bindValue(':id_grupo_pai', $id_grupo_pai, PDO::PARAM_INT);
		$statement->bindValue(':nm_grupo', $nm_grupo, PDO::PARAM_INT);
		$statement->bindValue(':desc_grupo', $desc_grupo, PDO::PARAM_INT);
		$statement->execute();
		
		$id_grupo = $this->get_connection()->lastInsertId('grupos_aninhados_id_grupo_seq');
		
		return new Grupo($id_grupo, $nm_grupo, $desc_grupo);
	}
	
	public function get_lotacao_valida($id_usu, $id_grupo) {
        $sql = $this->get_queries()->get_lotacao_valida();
		
		$statement = $this->get_connection()->prepare($sql);
		$statement->bindValue(':id_usu', $id_usu, PDO::PARAM_INT);
		$statement->bindValue(':id_grupo', $id_grupo, PDO::PARAM_INT);
		$statement->execute();
		
		$ret = $this->to_array($statement);
		
		if (sizeof($ret) > 0)
		{
			$id_grupo  = $ret[0]['p_id_grupo'];
			$id_cargo = $ret[0]['p_id_cargo'];
            
			$usuario = DB::getInstance()->get_usuario_by_id($id_usu);
			$grupo = DB::getInstance()->get_grupo_by_id($id_grupo);
			$cargo = DB::getInstance()->get_cargo($id_cargo);
			
			return new Lotacao($usuario, $grupo, $cargo);
		}
		return null;
	}
	
	public function inserir_usuario($login_usu, $nm_usu, $ult_nm_usu, $senha_usu) {
		$sql = $this->get_queries()->inserir_usuario();
		$statement = $this->get_connection()->prepare($sql);
		
		$statement->bindValue(':login_usu', $login_usu, PDO::PARAM_STR);
		$statement->bindValue(':nm_usu', $nm_usu, PDO::PARAM_STR);
		$statement->bindValue(':ult_nm_usu', $ult_nm_usu, PDO::PARAM_STR);
		$statement->bindValue(':senha_usu', $senha_usu, PDO::PARAM_STR);
		$statement->execute();
		
		$id = $this->get_connection()->lastInsertId('usuarios_id_usu_seq');
		if($statement->rowCount() === 1){
			$usuario =  DB::getInstance()->get_usuario_by_id($id);
			return $usuario;
		}else{
			return null;
		}
	}
    
}

?>
