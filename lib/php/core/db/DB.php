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
abstract class DB {

	// static
	/** Mantem um instancia unica dessa classe */
    private static $instance;
    
    // fields
    /** Objeto que retorna as queries apropriadas a um banco específico(MySQL, PostgreSQL, Oracle...) */
    private $m_queries;
    
    /** Conexão com o banco de dados */
	private $m_connection;
	
	/**
     * Singleton
     *
     * @return DB
     */
    public static function getInstance() {
    	if (DB::$instance === null) {
            $db_class = Config::DB_CLASS;
    		DB::$instance = new $db_class;
    	}  
    	return DB::$instance;
    }
	
	/**
	 * Conecta ao banco
	 */
	public abstract function connect($host, $port, $usuario, $senha, $banco);
	
	/**
	 * 
	 * @param DBQueries
	 */
	public function set_queries(DBQueries $queries) {
    	$this->m_queries = $queries;
    }
	
	/**
	 * 
	 * @return DBQueries
	 */
	public function get_queries() {
    	return $this->m_queries;
    }
    
	/**
	 * 
	 * @return PDO
	 */
	public function get_connection() {
    	return $this->m_connection;
    }
    
	/**
	 * 
	 * @return PDO
	 */
	public function set_connection(/*PDO*/ $pdo) {
    	$this->m_connection = $pdo;
    }
    
	/**
	 * Transforma um resultset em array
	 * @param PDOStatement statement
	 * @return array
	 */
	public function to_array($statement){
		return $statement->fetchAll();
	}
	
	/**
	 * Retorna se usuario tem ou nao acesso ao modulo
	 * Retorno:
	 * 		1 = Usuario Invalido
	 * 		2 = Modulo Invalido
	 * 		3 = Senha Invalida
	 * 	    4 = Acesso Negado
	 * 	   -1 = Acesso Liberado (Ok)
	 *
	 * @param String $user
	 * @param String $pass
	 * @param String $chave_mod
	 * @return int
	 */
	public function has_acesso($user, $pass, $chave_mod) {	        
	    $exceptionMessage = "Erro ao tentar efetuar login.";
	    
	    $sql = $this->get_queries()->exists_usuario();
	    $statement = $this->m_connection->prepare($sql);
	    $statement->bindValue(':login_usu', $user, PDO::PARAM_STR);
		$statement->execute();
		
        if (count(DB::to_array($statement)) == 0)
	        return 1; // unknown user
	        
        $sql = $this->get_queries()->exists_modulo();
        
        $statement = $this->m_connection->prepare($sql);
	    $statement->bindValue(':chave_mod', $chave_mod, PDO::PARAM_STR);
		$statement->execute();
		
		$tmp = DB::to_array($statement);
		$id_mod = $tmp[0][0];
		
		if (count($tmp) == 0) {
	        return 2; // unknown mod
		}
		
		$sql = $this->get_queries()->get_usuario_login();
        $statement = $this->m_connection->prepare($sql);
	    $statement->bindValue(':login_usu', $user, PDO::PARAM_STR);
	    $statement->bindValue(':pass', $pass, PDO::PARAM_STR);
		$statement->execute();
		
        if (count(DB::to_array($statement)) == 0) {
	        return 3; // invalid password
        }
        
        $sql = $this->get_queries()->get_status_usu();
        $statement = $this->m_connection->prepare($sql);
	    $statement->bindValue(':login_usu', $user, PDO::PARAM_STR);
	    $statement->bindValue(':pass', $pass, PDO::PARAM_STR);
		$statement->execute();
        
		$status = $this->to_array($statement);
		$status = $status[0][0];
		
		if($status == 0){
			return 5; //inativo
		}
		
		return -1;		
	}

    
    public function get_variaveis_globais() {
        $sql = $this->get_queries()->get_variaveis_globais();
		$statement = $this->get_connection()->prepare($sql);
		$statement->execute();

		$array = DB::to_array($statement);
		$vars = array();
        foreach ($array as $l) {
            $var_chave = $l['chave'];
            $var_valor = $l['valor'];

            $vars[$var_chave] = $var_valor;
        }

        return $vars;
    }

    public function salvar_variavel_global($chave, $valor) {
        $sql = $this->get_queries()->salvar_variavel_global();
		$statement = $this->get_connection()->prepare($sql);
        $statement->bindValue(":chave", $chave, PDO::PARAM_STR);
        $statement->bindValue(":valor", $valor, PDO::PARAM_STR);
		$statement->execute();
    }
	

	/**
	 * 
	 * @param $linha
	 * @return Tema
	 */
	public function criar_tema($linha){		
		$id_tema   = $linha['id_tema'];
		$nome_tema = $linha['nm_tema'];
		$descricao = $linha['desc_tema'];
		$autor     = $linha['autor_tema'];
		$dir       = $linha['dir_tema'];
        
		$tema     = new Tema($id_tema, $nome_tema, $descricao, $autor, $dir);
		return $tema;
	}
	
	/**
	 * 
	 * @param $linha
	 * @return unknown_type
	 */
	public function criar_unidade($linha){
		$id       = $linha['id_uni'];
		$codigo   = $linha['cod_uni'];
		$nome     = $linha['nm_uni'];
		$stat_uni = $linha['stat_uni'];
		$unidade  = new Unidade($id, null, $codigo, $nome, $stat_uni);
		return $unidade;
	}
	
	/**
	 * 
	 * @param $linha
	 * @return unknown_type
	 */
	public function criar_modulo($linha){
		$id 		= $linha['id_mod'];
		$chave 		= $linha['chave_mod'];
		$nome 		= $linha['nm_mod'];
		$descricao 	= $linha['desc_mod'];
		$autor 		= $linha['autor_mod'];
		$img 		= $linha['img_mod'];
        $tipo       = $linha['tipo_mod'];
		$modulo     = new Modulo($id, $chave, $nome, $autor, $img, $tipo);
		
		$modulo->set_descricao($descricao);
		$modulo->set_autor($autor);
		if (SGA::is_module_path($chave)){
			$modulo->set_dir(str_replace('.','/',$chave));
		}
		
		return $modulo;
	}
	
	/**
	 * 
	 * @param $linha
	 * @return Usuario $usuario
	 */
	public function criar_usuario($linha){
		$id 		= (int) $linha['id_usu'];
		$user 		= $linha['login_usu'];
		$nome 		= $linha['nm_usu'];
		$sobrenome 	= $linha['ult_nm_usu'];
		$status 	= (int) $linha['stat_usu'];
		$usuario = new Usuario($id, $user, $nome, $sobrenome, $status);
		return $usuario;
	}
	
	public function criar_servico($linha){
		$id_serv	= (int) $linha['id_serv'];
		$nm_serv    = $linha['nm_serv'];
		$sigla 		= $linha['sigla_serv'];
		$desc_serv 	= $linha['desc_serv'];
		$id_macro 	= (int) $linha['id_macro'];
        $stat_serv 	= $linha['stat_serv'];
        
        
        $servico 	= new Servico($id_serv, $nm_serv, $sigla, $id_macro);
        $servico->set_descricao($desc_serv);
        $servico->set_status($stat_serv);
		
		return $servico;
	}
	
	public function criar_senha($linha){
		$sigla 	= $linha['sigla_serv'];
		$numero	= (int) $linha['num_senha'];
		
		$senha 	= new Senha($sigla, $numero);
		return $senha;
	}
	
	public function criar_prioridade($linha){
		$id_pri   = (int) $linha['id_pri'];
		$nm_pri   = $linha['nm_pri'];
		$desc_pri = $linha['desc_pri'];
		$peso_pri = (int) $linha['peso_pri'];
            
        $pri    = new Prioridade($id_pri, $nm_pri, $desc_pri, $peso_pri);
        
        return $pri;
	}
	
	public function criar_cliente($linha, $senha){
		$nome  = $linha['nm_cli'];
		$ident = $linha['ident_cli'];
		
		$cli = new Cliente($nome, $senha, $ident);
		
		return $cli;
	}
	
	
	/**
	 * Retorna todas as unidades (Unidade) do Sistema
	 * 
	 * @return array
	 */
	public function get_unidades() {
		$sql = $this->get_queries()->get_unidades();
		$statement = $this->m_connection->prepare($sql);
		$statement->execute();
		
		$array = DB::to_array($statement);
		$unidades = array();
		foreach ($array as $t) {
			$tema     = DB::getInstance()->criar_tema($t);
			
			$grupo = DB::getInstance()->criarGrupo($t);
			
			$unidade   = DB::getInstance()->criar_unidade($t); 
			$unidade->set_tema($tema);
			$unidade->set_grupo($grupo);
			$unidades[] = $unidade;			
		}
		return $unidades;
	}
	
	/**
	 * Retorna a Unidade especificada pelo id
	 * 
	 * @param int $id_uni
	 * @return Unidade
	 */
	public function get_unidade($id_uni) {
		$sql = $this->get_queries()->get_unidade();
		$statement = $this->m_connection->prepare($sql);
		$statement->bindValue(':id_uni', $id_uni, PDO::PARAM_INT);
		$statement->execute();		
		$u = DB::to_array($statement);
		
		$tema = DB::getInstance()->criar_tema($u[0]);
		
		$grupo = DB::getInstance()->criarGrupo($u[0]);
		
		$unidade = DB::getInstance()->criar_unidade($u[0]); 
		$unidade->set_tema($tema);
		$unidade->set_grupo($grupo);
		return $unidade;
	}
	
	/**
	 * Retorna a Unidade especificada pelo codigo
	 * 
	 * @param int $cod_uni
	 * @return Unidade
	 */
	public function get_unidade_by_codigo($cod_uni) {
		$sql = $this->get_queries()->get_unidade_by_codigo();
		$statement = $this->m_connection->prepare($sql);
		$statement->bindValue(':cod_uni', $cod_uni, PDO::PARAM_STR);
		$statement->execute();		
		$unidades = $this->to_array($statement);
		
		if (count($unidades)){
			$aux = array();
			foreach ($unidades as $uni){
				$tema     = DB::getInstance()->criar_tema($uni);
							
				$grupo = DB::getInstance()->criarGrupo($uni);
				
				$unidade   = DB::getInstance()->criar_unidade($uni); 
				$unidade->set_tema($tema);
				$unidade->set_grupo($grupo);
				
				$aux[] = $unidade;
			}
			return $aux;
		}else{
			return null;
		}
	}
	
	/**
	 * Retorna a Unidade especificada pelo nome
	 * 
	 * @param string $nm_uni
	 * @return array Unidade
	 */
	public function get_unidade_by_name($nm_uni) {
		$sql = $this->get_queries()->get_unidade_by_name();
		$statement = $this->m_connection->prepare($sql);
		$statement->bindValue(':nm_uni', $nm_uni, PDO::PARAM_STR);
		$statement->execute();		
		$unidades = $this->to_array($statement);
		
		if (count($unidades) > 0) {
			$aux = array();
			foreach ($unidades as $uni){
				$tema     = DB::getInstance()->criar_tema($uni);
				
				$grupo = DB::getInstance()->criarGrupo($uni);
				$unidade = DB::getInstance()->criar_unidade($uni); 
				$unidade->set_tema($tema);
				$unidade->set_grupo($grupo);
				$aux[] = $unidade;
			}
			return $aux;
		}else{
			return null;
		}
	}

	public function get_unidades_by_usuario($id_usu) {
		$sql = $this->get_queries()->get_unidades_by_usuario();
		$statement = $this->m_connection->prepare($sql);
		$statement->bindValue(':id_usu', $id_usu, PDO::PARAM_INT);
		$statement->execute();
		
		$array = DB::to_array($statement);
		$unidades = array();
		foreach ($array as $t) {
			$tema     = DB::getInstance()->criar_tema($t);
			
			$grupo = DB::getInstance()->criarGrupo($t);
			
			$unidade   = DB::getInstance()->criar_unidade($t); 
			$unidade->set_tema($tema);
			$unidade->set_grupo($grupo);
			$unidades[] = $unidade;			
		}
		return $unidades;
	}

	public function get_unidades_by_grupos_mod_usu($ids_grupo) {
		$sql = $this->get_queries()->get_unidades_by_grupos_mod_usu();

        if ($ids_grupo == null) {
            $ids_grupo = array("null");

        }
        else {
            // verificação de segurança, garante que todos são numeros
            // ja que irão entrar direto na query (via concatenação)
            foreach ($ids_grupo as $key => $value) {
                $ids_grupo[$key] = (int) $value;
            }
        }
        $ids_grupo = implode(',', $ids_grupo);
        $sql = str_replace(':ids_grupo', $ids_grupo, $sql);
        
        $statement = $this->get_connection()->prepare($sql);
		$statement->execute();

		$array = DB::to_array($statement);
        
		$unidades = array();
		foreach ($array as $t) {

			$tema = DB::getInstance()->criar_tema($t);
			
			$grupo = DB::getInstance()->criarGrupo($t);

			$unidade   = DB::getInstance()->criar_unidade($t);
            
			$unidade->set_tema($tema);
			$unidade->set_grupo($grupo);
			$unidades[] = $unidade;
		}
		return $unidades;
		
	}
	
    public function get_unidades_by_grupos($id_grupo) {
    	$id_mod = Session::getInstance()->get(SGA::K_CURRENT_MODULE)->get_id();
    	$id_usu = SGA::get_current_user()->get_id();
		$sql = $this->get_queries()->get_unidades_by_grupos();

//        if ($id_grupo == null) {
//            $id_grupo = array("null");
//
//        }
//        else {
//            // verificação de segurança, garante que todos são numeros
//            // ja que irão entrar direto na query (via concatenação)
//            foreach ($id_grupo as $key => $value) {
//                $id_grupo[$key] = (int) $value;
//            }
//        }
//        $id_grupo = implode(',', $id_grupo);
//        $sql = str_replace(':id_grupo', $id_grupo, $sql);
        $statement = $this->get_connection()->prepare($sql);
		$statement->bindValue(':id_usu', $id_usu, PDO::PARAM_INT);
//        $statement->bindValue(':id_usu_2', $id_usu, PDO::PARAM_INT);
        $statement->bindValue(':id_mod', $id_mod, PDO::PARAM_INT);
		$statement->bindValue(':id_grupo_1', $id_grupo, PDO::PARAM_INT);
		$statement->bindValue(':id_grupo_2', $id_grupo, PDO::PARAM_INT);
		$statement->execute();

		$array = DB::to_array($statement);

		$unidades = array();
		foreach ($array as $t) {
			$tema = DB::getInstance()->criar_tema($t);
			
			$grupo = DB::getInstance()->criarGrupo($t);

			$unidade   = DB::getInstance()->criar_unidade($t);
            
			$unidade->set_tema($tema);
			$unidade->set_grupo($grupo);
			$unidades[] = $unidade;
		}
		return $unidades;
	}
	
	public function get_unidade_by_grupo($id_grupo){
		$sql = $this->get_queries()->get_unidade_by_grupo();
        $statement = $this->get_connection()->prepare($sql);
        $statement->bindValue(':id_grupo', $id_grupo, PDO::PARAM_INT);
		$statement->execute();

		$array = DB::to_array($statement);
		if(sizeof($array)>0){
			$unidades= array();
			
			$tema = DB::getInstance()->criar_tema($array[0]);
			
			$grupo = DB::getInstance()->criarGrupo($array[0]);
	
			$unidade   = DB::getInstance()->criar_unidade($array[0]);
	            
			$unidade->set_tema($tema);
			$unidade->set_grupo($grupo);
			$unidades[] = $unidade;
	
			return $unidades;
		}
		else{
			return null;		
		}
	}
	
	public function get_status_uni($id_uni){
		$sql = $this->get_queries()->get_status_uni();
    	$statement = $this->m_connection->prepare($sql);
    	$statement->bindValue(':id_uni',$id_uni, PDO::PARAM_INT);
    	$statement->execute();
    	
    	$status = $this->to_array($statement);
		$status = $status[0][0];
		return $status;
	}
	
	public function set_status_uni($id_uni,$stat_uni){
		$sql = $this->get_queries()->set_status_uni();
		$statement = $this->m_connection->prepare($sql);
    	$statement->bindValue(':stat_uni', $stat_uni, PDO::PARAM_INT);
    	$statement->bindValue(':id_uni', $id_uni, PDO::PARAM_INT);
    	$statement->execute();
    	
    	return $statement->rowCount() === 1;
	}
	
	/**
	 * Retorna todos os temas disponiveis
	 * 
	 * @return array
	 */
	public function get_temas() {
		$sql = $this->get_queries()->get_temas();
		$statement = $this->m_connection->prepare($sql);
		$statement->execute();
		$array = DB::to_array($statement);
		
		$temas = array();
		foreach ($array as $t) {
			$temas[] = DB::getInstance()->criar_tema($t);
		}
		return $temas;
	}

	/**
	 * Retorna os modulos do sistema, a partir do seu status
	 * 
	 * @param int $status
	 * @return array
	 */
	public function get_modulos($stats_mod = array(Modulo::MODULO_ATIVO, Modulo::MODULO_INATIVO), $tipos_mod = array(Modulo::MODULO_GLOBAL, Modulo::MODULO_UNIDADE)) {
		$sql = $this->get_queries()->get_modulos();
        
		// verifcação de segurança(SQL Injection), garante que todos valores são números
        // importante pois entrarão direto na consulta
     	foreach ($stats_mod as $key => $value) {
    		$stats_mod[$key] = (int) $value;
    	}
    	$stats_mod = implode(",", $stats_mod);
    	
    	$sql = str_replace(':stats_mod', $stats_mod, $sql);
    	
        foreach ($tipos_mod as $key => $value) {
    		$tipos_mod[$key] = (int) $value;
    	}
    	$tipos_mod = implode(",", $tipos_mod);
    	$sql = str_replace(':tipos_mod', $tipos_mod, $sql);
		
        $statement = $this->get_connection()->prepare($sql);
		$statement->execute();
		
		$array = DB::to_array($statement);
		
		$modulos = array();
		foreach ($array as $m) {
			$modulo 	= DB::getInstance()->criar_modulo($m);
			$modulos[] 	= $modulo;
		}
		return $modulos;
	}

	/**
	 * Retorna o modulo do sistema especificado pela chave
	 * e o status
	 * 
	 * @param String $chave
	 * @param int $status
	 * @return Modulo
	 */
	public function get_modulo($chave, $status) {
		$sql = $this->get_queries()->get_modulo();
		$statement = $this->m_connection->prepare($sql);
		$statement->bindValue(':chave', $chave, PDO::PARAM_STR);
		$statement->bindValue(':status', $status, PDO::PARAM_INT);
		$statement->execute();
		
		$m = DB::to_array($statement);
					
		$modulo = DB::getInstance()->criar_modulo($m[0]);
			
		return $modulo;
	}

	/**
	 * Retorna se o modulo esta ou nao instalado
	 * 
	 * @param String $chave
	 * @return bool
	 */
	public function is_instalado($chave) {
		$sql = $this->get_queries()->is_instalado();
		
		$statement = $this->m_connection->prepare($sql);
		$statement->bindValue(':chave', $chave, PDO::PARAM_STR);
		$statement->execute();
		
		if (count($this->to_array($statement)) > 0) {
			return true;
		}
		return false;
    }

    public function has_access_global($id_usu, $id_mod) {
        $sql = $this->get_queries()->has_mod_global_access();

        $statement = $this->get_connection()->prepare($sql);
		$statement->bindValue(':id_usu', $id_usu, PDO::PARAM_INT);
		$statement->bindValue(':id_mod', $id_mod, PDO::PARAM_INT);
		$statement->execute();

        $ret = $this->to_array($statement);

        return sizeof($ret) > 0;
    }
	
	/**
	 * Retorna a lotação válida para o usuario especificado em um determinado grupo.
	 * 
	 * A lotação mais póxima encontrada é retornada, ou seja se o usuário estiver lotado no 
	 * próprio grupo com qualquer cargo, esta lotação é retornada, caso contrário a primeira 
	 * lotacao encontrada de um grupo que seja pai direto ou indireto do grupo desejado é retornada.
	 * 
	 * 
	 * @param int $id_usu Id do usuario
	 * @param int $id_grupo Id do grupo
	 * @return Lotacao
	 */
	public abstract function get_lotacao_valida($id_usu, $id_grupo);
	
	/**
	 * Grava o ID da sessão atual, inserindo caso não exista, ou atualizando caso já exista.
	 * 
	 * @param $id_usu ID do usuario
	 * @param $session_id ID da sessão a ser gravado, se não for especificado o ID da sessão atual é utilizado
	 * @return void
	 */
	public function salvar_session_id($id_usu, $session_id = null) {
		if ($session_id == null) {
			$session_id = session_id();
		}
		
		$sql = $this->get_queries()->salvar_session_id();
		
        $statement = $this->get_connection()->prepare($sql);
		$statement->bindValue(':id_usu', $id_usu, PDO::PARAM_INT);
		$statement->bindValue(':session_id', $session_id, PDO::PARAM_STR);
		$statement->execute();
	}

	/**
	 * Verifica se a sessão do usuário é valida, comparando o ID da sessão passado com o ID armazenado no banco
	 * 
	 * @param $id_usu ID do usuario
	 * @param $session_id ID da sessão a ser comparado, se não especificado o ID da sessão atual é utilizado
	 * @return bool TRUE somente se o ID da sessão existe no banco e é igual ao parametro, false caso contrario
	 */
	public function verificar_session_id($id_usu, $session_id = null) {
		if ($session_id == null) {
			$session_id = session_id();
		}
		
		$sql = $this->get_queries()->verificar_session_id();
		
        $statement = $this->get_connection()->prepare($sql);
		$statement->bindValue(':id_usu', $id_usu, PDO::PARAM_INT);
		$statement->bindValue(':session_id', $session_id, PDO::PARAM_STR);
		$statement->execute();

        $ret = DB::getInstance()->to_array($statement);

        // se nao existe session ativa para este usuario
        if (sizeof($ret) == 0) {
            return Session::SESSION_ENCERRADA;
        }
        
        // retornar status da session
        return $ret[0]['stat_session'];
	}

    /**
     * Atualiza o status da Session de um determinado usuário.
     *
     * @param int $id_usu O ID do usuário cuja session deve ser atualizada.
     * @param int $stat_session O novo status da Session
     */
	public function set_session_status($id_usu, $stat_session) {
		if ($session_id == null) {
			$session_id = session_id();
		}

		$sql = $this->get_queries()->set_session_status();

        $statement = $this->get_connection()->prepare($sql);
		$statement->bindValue(':id_usu', $id_usu, PDO::PARAM_INT);
		$statement->bindValue(':stat_session', $stat_session, PDO::PARAM_INT);
		$statement->execute();
	}

	/**
	 * Retorna o Usuario a partir de seu login
	 * 
	 * @param String $login
	 * @return Usuario usuario
	 */
	public function get_usuario($login) {
		$sql = $this->get_queries()->get_usuario();
		
		$statement = $this->m_connection->prepare($sql);
		$statement->bindValue(':login', $login, PDO::PARAM_INT);
		$statement->execute();
		
		$u = $this->to_array($statement);
		
		if (count($u) > 0) {
			$u = $u[0];
			return DB::getInstance()->criar_usuario($u);
		}
		return null;
	}
	/**
	 * 
	 * @param $login
	 * @return array de usuarios
	 */
	public function get_usuario_by_mat($login,$id_cargo,$id_grupo){
		$sql = $this->get_queries()->get_usuario_by_mat();
		
		$statement = $this->m_connection->prepare($sql);
		$statement->bindValue(':login', $login, PDO::PARAM_INT);
		$statement->bindValue(':id_cargo_1', $id_cargo, PDO::PARAM_INT);
		$statement->bindValue(':id_cargo_2', $id_cargo, PDO::PARAM_INT);
		$statement->bindValue(':id_grupo_1', $id_grupo, PDO::PARAM_INT);
		$statement->bindValue(':id_grupo_2', $id_grupo, PDO::PARAM_INT);
		$statement->execute();
		
		
		$u = $this->to_array($statement);
		$usuarios = array();
		
		if (count($u) > 0) {
			foreach ($u as $usu){
				$usuarios[] = DB::getInstance()->criar_usuario($usu);
			}
			return $usuarios;
		}
		return null;
	}
	
	/**
	 * Retorna o Usuario a partir de seu ID
	 * 
	 * @param String $id_usu ID do usuario
	 * @return Usuario usuario
	 */
	public function get_usuario_by_id($id_usu) {
		$sql = $this->get_queries()->get_usuario_by_id();
		
		$statement = $this->m_connection->prepare($sql);
		$statement->bindValue(':id_usu', $id_usu, PDO::PARAM_INT);
		$statement->execute();
		
		$u = $this->to_array($statement);
		
		if (count($u) > 0) {
			$u = $u[0];
			return DB::getInstance()->criar_usuario($u);
		}
		return null;
	}
	
	/**
	 * Retorna uma lista de usuarios através do nome
	 * 
	 * @param String $nome
	 * @return Usuario usuario
	 */
	public function get_usuarios_by_name($nm_usu,$id_cargo,$id_grupo) {
		$sql = $this->get_queries()->get_usuarios_by_name();
		
		$statement = $this->m_connection->prepare($sql);
		$statement->bindValue(':nm_usu', $nm_usu, PDO::PARAM_STR);
		$statement->bindValue(':id_cargo_1', $id_cargo, PDO::PARAM_INT);
		$statement->bindValue(':id_cargo_2', $id_cargo, PDO::PARAM_INT);
		$statement->bindValue(':id_grupo_1', $id_grupo, PDO::PARAM_INT);
		$statement->bindValue(':id_grupo_2', $id_grupo, PDO::PARAM_INT);
		$statement->execute();
		
		$tmp = $this->to_array($statement);
		
		$ret = array();
		foreach ($tmp as $u)  {
			
			$ret[] = DB::getInstance()->criar_usuario($u);
		}
		return $ret;
	}
	
	/**
	 * Retorna um array contendo todos os usuarios que estão 
	 * dentro dos grupos e grupos filhos onde o usuário passado
	 * tem acesso ao módulo especificado, desde que estes grupos estejam
     * contidos dentro do grupo filtro específicado
	 * 
	 * @param int id do usuário que deve possuir a permissão sobre os usuários
     * @param int id do módulo sobre o qual o usuario deve possuir permissao
     * @param int id do grupo usado para filtrar o retorno
	 * @return array de usuários
	 */
	public function get_usuarios_grupos_by_usuario($id_usu, $id_mod, $id_grupo, $termo_login, $termo_nome) {
		$sql = $this->get_queries()->get_usuarios_grupos_by_usuario();
		
		$statement = $this->m_connection->prepare($sql);
        $statement->bindValue(':id_usu_1', $id_usu, PDO::PARAM_INT);
        $statement->bindValue(':id_usu_2', $id_usu, PDO::PARAM_INT);
        $statement->bindValue(':id_mod', $id_mod, PDO::PARAM_INT);
		$statement->bindValue(':id_grupo_1', $id_grupo, PDO::PARAM_INT);
		$statement->bindValue(':id_grupo_2', $id_grupo, PDO::PARAM_INT);
        $statement->bindValue(':termo_login', $termo_login, PDO::PARAM_STR);
        $statement->bindValue(':termo_nome', $termo_nome, PDO::PARAM_STR);
		$statement->execute();
		
		$users = array();
		
		$tmp = $this->to_array($statement);
		foreach ($tmp as $u) {
			$user = DB::getInstance()->criar_usuario($u);
			$users[] = $user;
		}
		return $users;
	}
	
	/**
	 * Retorna um array contendo todos os usuarios do sistema,
	 * de acordo com o status
	 * 
	 * @param int status
	 * @return array
	 */
	public function get_usuarios($status = array(1)) {
		$sql = $this->get_queries()->get_usuarios();
		
		foreach ($status as $key => $value) {
    		$status[$key] = (int) $value;
    	}
    	$status = implode(",", $status);
    	$sql = str_replace(':status', $status, $sql);
		
		$statement = $this->m_connection->prepare($sql);
		$statement->execute();
		
		$users = array();
		
		$tmp = $this->to_array($statement);
		foreach ($tmp as $u) {
			$user = DB::getInstance()->criar_usuario($u);
			$users[] = $user;
		}
		return $users;
	}

	public function get_todos_usuarios(){
		$sql = $this->get_queries()->get_todos_usuarios();
				
		$statement = $this->m_connection->prepare($sql);
		$statement->execute();
		
		$users = array();
		
		$tmp = $this->to_array($statement);
		foreach ($tmp as $u) {
			$user = DB::getInstance()->criar_usuario($u);
			$users[] = $user;
		}
		return $users;
	}
	
	
	/**
	 * Retorna um array contendo os servicos do usuario em uma determianda unidade
	 * de acordo com o ID de usuário especificado.
	 * 
	 * @param int ID do usuário
	 * @return array
	 */
	public function get_usuario_servicos_unidade($id_user, $id_uni, $status = array(Servico::SERVICO_ATIVO)) {
		$sql = $this->get_queries()->get_usuario_servicos_unidade();
		
		// verificação de segurança
    	// garante que todos os elementos de status sao numeros
    	foreach ($status as $key => $value) {
    		$status[$key] = (int) $value;
    	}
    	$status = implode(",", $status);
    	$sql = str_replace(':status', $status, $sql);
		
		$statement = $this->m_connection->prepare($sql);
		$statement->bindValue(':id_user', $id_user, PDO::PARAM_INT);
		$statement->bindValue(':id_uni', $id_uni, PDO::PARAM_INT);
		$statement->execute();
		
		$servicos = array();
		
		$tmp = DB::to_array($statement);
		foreach ($tmp as $s) {
			$servicos[] = $s['id_serv'];
		}
		return $servicos;
	}

    /**
     *
     * @param <type> $login_usu
     * @param <type> $nm_usu
     * @param <type> $ult_nm_usu
     * @param <type> $senha_usu
     * @return Usuario
     */
	public abstract function inserir_usuario($login_usu, $nm_usu, $ult_nm_usu, $senha_usu);
	
	public function atualizar_usuario($id_usu, $login_usu, $nm_usu, $ult_nm_usu) {
		$sql = $this->get_queries()->atualizar_usuario();
		
		$statement = $this->m_connection->prepare($sql);
		$statement->bindValue(':id_usu', $login_usu, PDO::PARAM_INT);
		$statement->bindValue(':login_usu', $login_usu, PDO::PARAM_INT);
		$statement->bindValue(':nm_usu', $nm_usu, PDO::PARAM_STR);
		$statement->bindValue(':ult_nm_usu', $ult_nm_usu, PDO::PARAM_STR);
		$statement->execute();
	}
	public function inserir_unidade($id_grupo,$cod_uni,$nm_uni) {
		$sql = $this->get_queries()->inserir_unidade();
		
		$statement = $this->m_connection->prepare($sql);
		$statement->bindValue(':id_grupo', $id_grupo, PDO::PARAM_INT);
		$statement->bindValue(':cod_uni', $cod_uni, PDO::PARAM_STR);
		$statement->bindValue(':nm_uni', $nm_uni, PDO::PARAM_STR);
		$statement->execute();
		//return $statement->rowCount() === 1;
	}
	public function atualizar_unidade($id_uni,$id_grupo,$cod_uni,$nm_uni) {
		$sql = $this->get_queries()->atualizar_unidade();
		$statement = $this->m_connection->prepare($sql);
		$statement->bindValue(':id_uni', $id_uni, PDO::PARAM_INT);
		$statement->bindValue(':id_grupo', $id_grupo, PDO::PARAM_INT);
		$statement->bindValue(':cod_uni', $cod_uni, PDO::PARAM_STR);
		$statement->bindValue(':nm_uni', $nm_uni, PDO::PARAM_STR);
		$statement->execute();
		//return $statement->rowCount() ===1;
	}
	
	/**
	 * Retorna um array contento todos os grupos (Grupo) 
	 * que o Usuario participa
	 * 
	 * @param int $id_user
	 * @return array
	 */
	/*
	 * DELETAR
	public function get_usuario_grupos($id_user) {
		$sql = $this->get_queries()->get_usuario_grupos();
		
		$statement = $this->m_connection->prepare($sql);
		$statement->bindValue(':id_user', $id_user, PDO::PARAM_INT);
		$statement->execute();
		
		$grupos = array();
		$tmp = $this->to_array($statement);
		foreach ($tmp as $s) {
			$id   = $s['id_grup'];
			$nome = $s['nm_grup'];
			$grupos[] = new Grupo($id, $nome);
		}
		return $grupos;
	}
	*/
	
	/**
	 * Retorna todos os grupos (Grupo) do Sistema
	 * 
	 * @return array
	 */
	public function get_grupos() {
		$sql = $this->get_queries()->get_grupos();
		
		$statement = $this->m_connection->prepare($sql);
		$statement->execute();
		
		$grupos = array();
		
		$tmp = $this->to_array($statement);
		return DB::getInstance()->criarArrayGrupos($tmp);
	}

    /**
	 * Retorna a arvore com todos os grupos (Grupo) do Sistema
	 *
	 * @return array
	 */
	public function get_arvore_grupos() {
		$sql = $this->get_queries()->get_arvore_grupos();

		$statement = $this->m_connection->prepare($sql);
		$statement->execute();

		$grupos = array();
        $ret = null;
		$tmp = $this->to_array($statement);
		foreach ($tmp as $s) {
            $id_grupo_pai   = $s['id_grupo_pai'];

            $grupo = DB::getInstance()->criarGrupo($s);
            $grupos[$grupo->get_id()] = $grupo;

            $pai = $grupos[$id_grupo_pai];
            if ($grupo->is_raiz()) {
                $ret = $grupo;
            }
            else {
                $pai->add_filho($grupo);
            }
		}
        
		return $ret;
	}
	
	/**
	 * Retorna todos os grupos que pdoem ser pai do grupo especificado por $id_grupo
	 * 
	 * Filhos diretos e indiretos de do grupo $id_grupo não podem ser seu pai.
	 * 
	 * @return array
	 */
	public function get_grupos_candidatos_pai($id_grupo) {
		$sql = $this->get_queries()->get_grupos_candidatos_pai();
		
		$statement = $this->m_connection->prepare($sql);
		$statement->bindValue(':id_grupo_1', $id_grupo, PDO::PARAM_INT);
		$statement->bindValue(':id_grupo_2', $id_grupo, PDO::PARAM_INT);
		$statement->execute();
		
		$grupos = array();
		$tmp = $this->to_array($statement);
		return DB::getInstance()->criarArrayGrupos($tmp);
	}
	
	/**
	 * Retorna todos os grupos (Grupo) folha do Sistema
	 * 
	 * @return array
	 */
	public function get_grupos_folha_disponiveis() {
		$sql = $this->get_queries()->get_grupos_folha_disponiveis();
		
		$statement = $this->m_connection->prepare($sql);
		$statement->execute();
		
		$grupos = array();
		$tmp = $this->to_array($statement);
		return DB::getInstance()->criarArrayGrupos($tmp);
	}
	
	public function get_lotacoes_editaveis($id_usu, $id_mod, $id_grupo = null, $filtrar_redundancia = false) {
		if ($id_grupo == null) {
            $id_grupo = DB::getInstance()->get_grupo_by_id(1); //Raiz
        }

        $sql = $this->get_queries()->get_lotacoes_editaveis();
		
		$statement = $this->m_connection->prepare($sql);
		$statement->bindValue(':id_usu', $id_usu, PDO::PARAM_INT);
		$statement->bindValue(':id_grupo_1', $id_grupo, PDO::PARAM_INT);
		$statement->bindValue(':id_grupo_2', $id_grupo, PDO::PARAM_INT);
        $statement->bindValue(':id_mod', $id_mod, PDO::PARAM_INT);
		$statement->execute();
		
		$lotacoes = array();
		$tmp = $this->to_array($statement);

        // Elimina grupos que sejam filhos(diretos/indiretos) ja que a permissao pode ser obtida pelo pai
        // sendo nesta caso a permissao obtida pelo grupo filho redundante
        // se baseia no fato de que a lista vem ordenada pela a altura do grupo na arvore,
        // ou seja, a raiz virá primeiro a seguir seus filhos e assim sucessivamente.
        if ($filtrar_redundancia) {
            $esq = $tmp[0]['esquerda'];
            $dir = $tmp[0]['direita'];
        }
		foreach ($tmp as $s) {
			$id_grupo   = $s['id_grupo'];
			$id_cargo	= $s['id_cargo'];

            if ($filtrar_redundancia) {
                if ($s['esquerda'] > $esq && $s['direita'] < $dir) {
                    continue; // filtrado, filho(direto/indireto) do anterior
                }
                else {
                    // nao foi filtrado, guarda a esquerda e direita, para prevenir possiveis filhos
                    // deste grupo
                    $esq = $s['esquerda'];
                    $dir = $s['direita'];
                }
            }
			$grupo = DB::getInstance()->get_grupo_by_id($id_grupo);
			$cargo = DB::getInstance()->get_cargo($id_cargo);
			$usuario = DB::getInstance()->get_usuario_by_id($id_usu);
			
			$lotacoes[] = new Lotacao($usuario, $grupo, $cargo);
		}
		return $lotacoes;
	}

    public function get_lotacoes_visiveis($id_usu, $id_usu_admin, $id_mod, $id_grupo) {
        $sql = $this->get_queries()->get_lotacoes_visiveis();

		$statement = $this->m_connection->prepare($sql);
		$statement->bindValue(':id_usu', $id_usu, PDO::PARAM_INT);
        $statement->bindValue(':id_usu_admin_1', $id_usu_admin, PDO::PARAM_INT);
        $statement->bindValue(':id_usu_admin_2', $id_usu_admin, PDO::PARAM_INT);
		$statement->bindValue(':id_grupo_1', $id_grupo, PDO::PARAM_INT);
		$statement->bindValue(':id_grupo_2', $id_grupo, PDO::PARAM_INT);
        $statement->bindValue(':id_mod', $id_mod, PDO::PARAM_INT);
		$statement->execute();

		$lotacoes = array();
		$tmp = $this->to_array($statement);

        $usuario = DB::getInstance()->get_usuario_by_id($id_usu);
		foreach ($tmp as $s) {
			$id_grupo   = $s['id_grupo'];
			$id_cargo	= $s['id_cargo'];

			$grupo = DB::getInstance()->get_grupo_by_id($id_grupo);
			$cargo = DB::getInstance()->get_cargo($id_cargo);
			

			$lotacoes[] = new Lotacao($usuario, $grupo, $cargo);
		}
		return $lotacoes;
	}

    public function get_unidades_visiveis($id_usu, $id_usu_admin, $id_mod, $id_grupo) {
        $sql = $this->get_queries()->get_unidades_visiveis();

		$statement = $this->m_connection->prepare($sql);
		$statement->bindValue(':id_usu', $id_usu, PDO::PARAM_INT);
        $statement->bindValue(':id_usu_admin', $id_usu_admin, PDO::PARAM_INT);
		$statement->bindValue(':id_grupo_1', $id_grupo, PDO::PARAM_INT);
		$statement->bindValue(':id_grupo_2', $id_grupo, PDO::PARAM_INT);
        $statement->bindValue(':id_mod', $id_mod, PDO::PARAM_INT);
		$statement->execute();

		$array = $this->to_array($statement);
        $unidades = array();
        foreach ($array as $t) {
			$tema     = DB::getInstance()->criar_tema($t);

			$grupo = DB::getInstance()->criarGrupo($t);

			$unidade   = DB::getInstance()->criar_unidade($t);
			$unidade->set_tema($tema);
			$unidade->set_grupo($grupo);
			$unidades[] = $unidade;
		}
		return $unidades;
	}
//	public function get_lotacoes_uni_by_usuario($id_usu, $id_uni) {
//		$sql = $this->get_queries()->get_lotacoes_by_usuario();
//		
//		$statement = $this->m_connection->prepare($sql);
//		$statement->bindValue(':id_usu', $id_usu, PDO::PARAM_INT);
//		$statement->bindValue(':id_uni', $id_uni, PDO::PARAM_INT);
//		$statement->execute();
//		
//		$lotacoes = array();
//		$tmp = $this->to_array($statement);
//		foreach ($tmp as $s) {
//			$id_grupo   = $s['id_grupo'];
//			$id_cargo	= $s['id_cargo'];
//			
//			$grupo = DB::getInstance()->get_grupo_by_id($id_grupo);
//			$cargo = DB::getInstance()->get_cargo($id_cargo);
//			$usuario = DB::getInstance()->get_usuario_by_id($id_usu);
//			
//			$lotacoes[] = new Lotacao($usuario, $grupo, $cargo);
//		}
//		return $lotacoes;
//	}
//	
	
	/**
	 * Retorna todos os grupos(Grupo) com seus respectivos sub-grupos de um usuario
	 * onde este usuario tem permissão de acesso em um determinado módulo.
	 * 
	 * @param int $id_usu ID do usuario 
	 * @param int $id_mod ID do módulo ao qual o usuario deve possuir permissão
	 * 
	 * @return array
	 */
	public function get_grupos_by_permissao_usuario($id_usu, $id_mod) {
		$sql = $this->get_queries()->get_grupos_by_permissao_usuario();
		
		$statement = $this->m_connection->prepare($sql);
		$statement->bindValue(':id_usu', $id_usu, PDO::PARAM_INT);
		$statement->bindValue(':id_mod', $id_mod, PDO::PARAM_INT);
		$statement->execute();
		
		$grupos = array();
		$tmp = $this->to_array($statement);
		return DB::getInstance()->criarArrayGrupos($tmp);
	}
	
	public function get_grupo_by_id($id_grupo) {
		$sql = $this->get_queries()->get_grupo_by_id();		
		$statement = $this->m_connection->prepare($sql);
		$statement->bindValue(':id_grupo', $id_grupo, PDO::PARAM_INT);
		$statement->execute();
		
		$tmp = $this->to_array($statement);
		$g = $tmp[0];
		
		return DB::getInstance()->criarGrupo($g);
	}
	
	public static function criarArrayGrupos($linhas) {
		$gs = array();
		foreach ($linhas as $linha) {
			$gs[] = DB::getInstance()->criarGrupo($linha);
		}
		return $gs;
	}
	
	public static function criarGrupo($linha) {
		$id_grupo = $linha['id_grupo'];
		$nm_grupo = $linha['nm_grupo'];
		$descricao = $linha['desc_grupo'];
		$is_raiz = $linha['esquerda'] == 1;
		
		
		return new Grupo($id_grupo, $nm_grupo, $descricao, $is_raiz);
	}
	
	/**
	 * Obtem os filhos diretos e indiretos do Grupo especificado por $id_grupo
	 * 
	 * @param $id_grupo O grupo cujos filhos devem ser retornados.
	 * @return array Um array de grupos (Grupo) com todos os filhos e seus respectivos filhos a partir do grupo especificado
	 */
	public function get_sub_grupos($id_grupo) {
		$sql = $this->get_queries()->get_sub_grupos();
		$statement = $this->m_connection->prepare($sql);
		$statement->bindValue(':id_grupo_1', $id_grupo, PDO::PARAM_INT);
		$statement->bindValue(':id_grupo_2', $id_grupo, PDO::PARAM_INT);
		$statement->execute();
		
		$tmp = $this->to_array($statement);
		
		return DB::getInstance()->criarArrayGrupos($tmp);
	}
	
	public function get_grupo_pai_by_id($id_grupo_filho) {
		$sql = $this->get_queries()->get_grupo_pai_by_id();		
		$statement = $this->m_connection->prepare($sql);
		$statement->bindValue(':id_grupo_filho', $id_grupo_filho, PDO::PARAM_INT);
		$statement->execute();
		
		$tmp = $this->to_array($statement);
		
		return DB::getInstance()->criarGrupo($tmp[0]);
	}
	
	public function criarArrayCargos($cargos){
		$c = array();
		foreach($cargos as $cargo){
			$c[] = DB::getInstance()->criarCargo($cargo);
		}
		return $c;
	}
	
	public function criarCargo($cargo){
		$id_cargo   = $cargo['id_cargo'];
		$nm_cargo	= $cargo['nm_cargo'];
		$desc_cargo = $cargo['desc_cargo'];
		$is_raiz    = $cargo['esquerda'] == 1;
		
		return  new Cargo($id_cargo, $nm_cargo, $desc_cargo, $is_raiz);
	}
	
	/**
	 * Retorna todos os cargos (Cargo) do Sistema
	 * 
	 * @return array
	 */
	public function get_cargos() {
		$sql = $this->get_queries()->get_cargos();
		
		$statement = $this->m_connection->prepare($sql);
		$statement->execute();
		
		$cargos = array();
		$tmp = $this->to_array($statement);

		return DB::getInstance()->criarArrayCargos($tmp);
	}

    /**
	 * Retorna a arvore com todos os cargos (Cargo) do Sistema
	 *
	 * @return array
	 */
	public function get_arvore_cargos() {
		$sql = $this->get_queries()->get_arvore_cargos();

		$statement = $this->m_connection->prepare($sql);
		$statement->execute();

		$cargos = array();
        $ret = null;
		$tmp = $this->to_array($statement);
		foreach ($tmp as $s) {

			$id_cargo_pai   = $s['id_cargo_pai'];
			
			$cargo = DB::getInstance()->criarCargo($s);
			
            $cargos[$cargo->get_id()] = $cargo;

            $pai = $cargos[$id_cargo_pai];
            if ($cargo->is_raiz()) {
                $ret = $cargo;
            }
            else {
                $pai->add_filho($cargo);
            }
		}

		return $ret;
	}

	/**
	 * Obtem os candidatos a pai de um determinado cargo, seus filhos diretos ou indiretos,
	 * são excluidos da lista.
	 * 
	 * @return array
	 */
	public function get_cargos_candidatos_pai($id_cargo) {
		$sql = $this->get_queries()->get_cargos_candidatos_pai();
		
		$statement = $this->m_connection->prepare($sql);
		$statement->bindValue(':id_cargo_1', $id_cargo, PDO::PARAM_INT);
		$statement->bindValue(':id_cargo_2', $id_cargo, PDO::PARAM_INT);
		$statement->execute();
		
		$cargos = array();
		$tmp = $this->to_array($statement);

		return DB::getInstance()->criarArrayCargos($tmp);
		
	}
	
	public function get_cargo_pai_by_id($id_cargo_filho) {
		$sql = $this->get_queries()->get_cargo_pai_by_id();		
		
		$statement = $this->m_connection->prepare($sql);
		$statement->bindValue(':id_cargo_filho', $id_cargo_filho, PDO::PARAM_INT);
		$statement->execute();
		
		$s = $this->to_array($statement);
		if (sizeof($s) > 0) {
			$s = $s[0];
			return DB::getInstance()->criarCargo($s);
		}
		exit('a');
		return null;
	}
	
	/**
	 * Retorna o cargo (Cargo) a partir do ID especificado
	 * 
	 * @param $id_cargo ID do cargo.
	 * @return Cargo
	 */
	public function get_cargo($id_cargo) {
		$sql = $this->get_queries()->get_cargo();
		
		$statement = $this->m_connection->prepare($sql);
		$statement->bindValue(':id_cargo', $id_cargo, PDO::PARAM_INT);
		$statement->execute();
		
		$s = $this->to_array($statement);
		if (sizeof($s) > 0) {
			$s = $s[0];
		
			return DB::getInstance()->criarCargo($s);
		}
		return null;
	}
	
	public function get_sub_cargos($id_cargo) {
		$sql = $this->get_queries()->get_sub_cargos();
		
		$statement = $this->m_connection->prepare($sql);
		$statement->bindValue(':id_cargo_1', $id_cargo, PDO::PARAM_INT);
		$statement->bindValue(':id_cargo_2', $id_cargo, PDO::PARAM_INT);
		$statement->execute();
		
		$tmp = $this->to_array($statement);
		$ret = array();

		return DB::getInstance()->criarArrayCargos($tmp); 
	}
	
	/**
	 * Retorna todas  as permissoes (PermissaoModulo) do cargo especificado
	 * 
	 * @return array
	 */
	public function get_permissoes_cargo($id_cargo) {
		$sql = $this->get_queries()->get_permissoes_cargo();
		
		$statement = $this->m_connection->prepare($sql);
		$statement->bindValue(':id_cargo', $id_cargo, PDO::PARAM_INT);
		$statement->execute();
		
		$pcs = array();
		$tmp = $this->to_array($statement);
		foreach ($tmp as $s) {
			$modulo = DB::getInstance()->criar_modulo($s);
			$pcs[] = new PermissaoModulo($modulo);
		}
		return $pcs;
	}
	
	/**
	 * Insere um novo cargo no sistema e suas permissoes de acesso aos modulos
	 * 
	 * @param $nm_cargo
	 * @param $desc_cargo
	 * @param $permissoes
	 * @return Cargo O cargo que foi criado
	 */
	public abstract function inserir_cargo($id_cargo_pai, $nm_cargo, $desc_cargo);
	
	/**
	 * Atualiza um cargo do sistema
	 * 
	 * @param $id_cargo
	 * @param $nm_cargo
	 * @param $desc_cargo
	 */
	public function atualizar_cargo($id_cargo, $id_cargo_pai, $nm_cargo, $desc_cargo) {
		
		$sql = $this->get_queries()->atualizar_cargo();
		$statement = $this->m_connection->prepare($sql);
		
		$statement->bindValue(':id_cargo', $id_cargo, PDO::PARAM_INT);
		$statement->bindValue(':id_cargo_pai', $id_cargo_pai, PDO::PARAM_INT);
		$statement->bindValue(':nm_cargo', $nm_cargo, PDO::PARAM_STR);
		$statement->bindValue(':desc_cargo', $desc_cargo, PDO::PARAM_STR);
		
		$statement->execute();
	}
	
	/**
	 * Remove um cargo do sistema e suas permissoes
	 * 
	 * @param $id_cargo
	 * @return Cargo O cargo que foi criado
	 */
	public function remover_cargo($id_cargo) {
		
		$statement = new PDOException();
		
		$this->get_connection()->beginTransaction();
		try {
			$sql = $this->get_queries()->remover_permissoes_cargo();
			$statement = $this->m_connection->prepare($sql);
			$statement->bindValue(':id_cargo', $id_cargo, PDO::PARAM_INT);
			
			$statement->execute();
			
			$sql = $this->get_queries()->remover_cargo();
			$statement = $this->m_connection->prepare($sql);
			$statement->bindValue(':id_cargo', $id_cargo, PDO::PARAM_INT);
			
			$statement->execute();
			
			$this->get_connection()->commit();
		}
		catch (PDOException $e) {
			$this->get_connection()->rollBack();
			throw $e;
		}
	}
	
	/**
	 * Insere uma permissão de acesso a um determinado modulo para o cargo especificado.
	 * 
	 * @param $id_cargo ID do cargo que terá a permissão.
	 * @param $id_modulo ID do módulo ao qual a permissão se refere.
	 * @param $permissao Valor da permissao
	 * @return void
	 */
	public function inserir_permissao_modulo_cargo($id_cargo, $id_mod, $permissao) {
		
		$sql = $this->get_queries()->inserir_permissao_modulo_cargo();
		$statement = $this->m_connection->prepare($sql);
		
		$statement->bindValue(':id_cargo', $id_cargo, PDO::PARAM_INT);
		$statement->bindValue(':id_mod', $id_mod, PDO::PARAM_INT);
		$statement->bindValue(':permissao', $permissao, PDO::PARAM_INT);
		
		$statement->execute();
	}
	
	/**
	 * Remove uma permissão de acesso a um determinado modulo para o cargo especificado.
	 * 
	 * @param $id_cargo ID do cargo que terá a permissão.
	 * @param $id_modulo ID do módulo ao qual a permissão se refere.
	 * @param $permissao Valor da permissao
	 * @return void
	 */
	public function remover_permissao_modulo_cargo($id_cargo, $id_mod) {
		
		$sql = $this->get_queries()->remover_permissao_modulo_cargo();
		$statement = $this->m_connection->prepare($sql);
		
		$statement->bindValue(':id_cargo', $id_cargo, PDO::PARAM_INT);
		$statement->bindValue(':id_mod', $id_mod, PDO::PARAM_INT);
		
		$statement->execute();
	}
	
	/**
	 * Retorna o cargo (Cargo) cujo ID é igual ao parametro especificado
	 * 
	 * @param int $id_cargo
	 * @return Lotacao
	 */
	public function get_lotacao($id_usu, $id_grupo) {
		$sql = $this->get_queries()->get_lotacao();
		
		$usuario = DB::getInstance()->get_usuario_by_id($id_usu);
		$grupo = DB::getInstance()->get_grupo_by_id($id_grupo);
		if ($usuario == null || $grupo == null) {
			return null;
		}
		
		$statement = $this->m_connection->prepare($sql);
		$statement->bindValue(':id_usu', $id_usu, PDO::PARAM_INT);
		$statement->bindValue(':id_grupo', $id_grupo, PDO::PARAM_INT);
		$statement->execute();
		
		$tmp = $this->to_array($statement);
		if (sizeof($tmp) > 0) {
			$s = $tmp[0];

			$cargo = DB::getInstance()->criarCargo($s);
			if ($cargo == null) {
				return null;
			}
			
			return new Lotacao($usuario, $grupo, $cargo);
		}
		return null;
	}
	
	public function inserir_lotacao($id_usu, $id_grupo, $id_cargo) {
		$sql = $this->get_queries()->inserir_lotacao();

		$statement = $this->m_connection->prepare($sql);
		$statement->bindValue(':id_usu', $id_usu, PDO::PARAM_INT);
		$statement->bindValue(':id_grupo', $id_grupo, PDO::PARAM_INT);
		$statement->bindValue(':id_cargo', $id_cargo, PDO::PARAM_INT);
		$statement->execute();
	}
	
	public function atualizar_lotacao($id_usu, $id_grupo, $id_cargo, $id_grupo_default) {
		$sql = $this->get_queries()->atualizar_lotacao();

		$statement = $this->m_connection->prepare($sql);
		$statement->bindValue(':id_usu', $id_usu, PDO::PARAM_INT);
		$statement->bindValue(':id_grupo', $id_grupo_default, PDO::PARAM_INT);
		$statement->bindValue(':id_grupo_novo', $id_grupo, PDO::PARAM_INT);
		$statement->bindValue(':id_cargo', $id_cargo, PDO::PARAM_INT);
		$statement->execute();
		
		return $statement->rowCount() === 1;
	}
	
	public function remover_lotacao($id_usu, $id_grupo) {
		$sql = $this->get_queries()->remover_lotacao();

		$statement = $this->m_connection->prepare($sql);
		$statement->bindValue(':id_usu', $id_usu, PDO::PARAM_INT);
		$statement->bindValue(':id_grupo', $id_grupo, PDO::PARAM_INT);
		$statement->execute();
		
		return $statement->rowCount() === 1;
	}

    /**
     * Remove todas as lotações do usuário especificado.
     *
     * @param <type> $id_usu ID do usuário
     */
    public function remover_lotacoes($id_usu) {
		$sql = $this->get_queries()->remover_lotacoes();

		$statement = $this->get_connection()->prepare($sql);
		$statement->bindValue(':id_usu', $id_usu, PDO::PARAM_INT);
		$statement->execute();
	}
	
	/**
	 * Retorna o cargo (Cargo) cujo ID é igual ao parametro especificado
	 * 
	 * @param int $id_cargo
	 * @return array
	 */
	public function get_cargo_by_usuario_grupo($id_usu, $id_grupo) {
		$sql = $this->get_queries()->get_cargo_by_id();
		
        $statement = $this->get_connection()->prepare($sql);
		$statement->executeValue(':id_cargo', $id_cargo, PDO::PARAM_INT);
		$statement->execute();
		
		$tmp = $this->to_array($statement);
		
		$s = $tmp[0];
		
		return DB::getInstance()->criarCargo($s);
		
	}

	/**
	 * Cria um grupo cujo pai é definido pelo parametro $pai
	 * 
	 * @param int $pai ID do grupo  que será pai do novo grupo
	 * @param int $nm_grupo Nome do grupo a ser criado
	 * @param int $desc_grupo Descrição do novo grupo
	 */
	public abstract function criar_grupo($id_grupo_pai, $nm_grupo, $desc_grupo);
	
	/**
	 * Atualiza um grupo
	 * 
	 * @param int $pai ID do grupo que será pai do grupo
	 * @param int $nm_grupo Nome do grupo a ser atualizado
	 * @param int $desc_grupo Descrição do grupo
	 */
	public function atualizar_grupo($id_grupo, $id_grupo_pai, $nm_grupo, $desc_grupo) {
		$sql = $this->get_queries()->atualizar_grupo();
		
		$statement = $this->m_connection->prepare($sql);
		$statement->bindValue(':id_grupo', $id_grupo, PDO::PARAM_INT);
		$statement->bindValue(':id_grupo_pai', $id_grupo_pai, PDO::PARAM_INT);
		$statement->bindValue(':nm_grupo', $nm_grupo, PDO::PARAM_STR);
		$statement->bindValue(':desc_grupo', $desc_grupo, PDO::PARAM_STR);
		$statement->execute();
	}
	
	/**
	 *  Remove um grupo em cascata, removendo todos seus filhos
	 *  
	 * @param $id_grupo ID do grupo a ser removido
	 * @return void
	 */
	public function remover_grupo($id_grupo) {
		$sql = $this->get_queries()->remover_grupo();
		
		$statement = $this->m_connection->prepare($sql);
		$statement->bindValue(':id_grupo', $id_grupo, PDO::PARAM_INT);
		$statement->execute();
	}
	
	public function remover_senha_uni_msg($id_uni){
		$sql = $this->get_queries()->remover_senha_uni_msg();
		
		$statement = $this->m_connection->prepare($sql);
		$statement->bindValue(':id_uni', $id_uni, PDO::PARAM_INT);
		$statement->execute();
	}
	
	public function remover_unidade($id_uni){
		try {
			$this->get_connection()->beginTransaction();
			
			$this->remover_senha_uni_msg($id_uni);
			$sql = $this->get_queries()->remover_unidade($id_uni);
			
			$statement = $this->m_connection->prepare($sql);
			$statement->bindValue(':id_uni', $id_uni, PDO::PARAM_INT);
			$statement->execute();
			
			$this->get_connection()->commit();
		}
		catch (PDOException $e) {
			if ($e->getCode() >= 23000 && $e->getCode() <= 23999) {
				$this->get_connection()->rollBack();
				throw new Exception("Não é possivel remover a unidade porque existem atendimentos, usuários ou serviços associados.");	
			}
			else {
				throw $e;
			}
		}
	}
	
	/**
	 *  Remove um serviço da unidade
	 *  
	 * @param $id_grupo ID do grupo a ser removido
	 * @return void
	 */
	public function remover_servico_uni($id_uni,$id_serv) {
		$sql = $this->get_queries()->remover_servico_uni();
		
		$statement = $this->m_connection->prepare($sql);
		$statement->bindValue(':id_uni', $id_uni, PDO::PARAM_INT);
		$statement->bindValue(':id_serv', $id_serv, PDO::PARAM_INT);
		$statement->execute();
	}
	
	
	/**
	 * Retorna a PermissaoModuloGrupo com base na unidade passada como referência,
	 * se existirem
	 * 
	 * 
	 * @param $id_usu ID do usuario
	 * @param $chave_mod Chave do modulo ao qual a permissao se refere
	 * @param $id_uni ID da unidade referencia
	 * @return PermissaoModuloGrupo Retorna PermissaoModuloGrupo referente ao grupo mais próximo 
	 * da unidade ou null caso o usuario não pertenca a nenhum grupo que contenha a unidade referencia
	 */
	public function get_permissao_modulo_grupo($id_usu, $chave_mod, $id_uni) {
		$sql = $this->get_queries()->get_permissao_modulo_grupo();
		
		$statement = $this->m_connection->prepare($sql);
		$statement->bindValue(':id_grupo_pai', $id_grupo_pai, PDO::PARAM_INT);
		$statement->bindValue(':nm_grupo', $nm_grupo, PDO::PARAM_INT);
		$statement->bindValue(':desc_grupo', $desc_grupo, PDO::PARAM_INT);
		$statement->execute();
	}
	
	/**
	 * Retorna um array contendo o menu do modulo
	 * 
	 * @param String $chave_mod
	 * @return array
	 */
	public function get_menu($chave_mod) {
		$sql = $this->get_queries()->get_menu();
		
		$statement = $this->m_connection->prepare($sql);
		$statement->bindValue(':chave_mod', $chave_mod, PDO::PARAM_STR);
		$statement->execute();
		
		$res = $this->to_array($statement);
		
		$menus = array();
		
		foreach ($res as $linha) {
			$id 		= (int) $linha['id_menu'];
			$nome 		= $linha['nm_menu'];
			$link 		= $linha['lnk_menu'];
			$desc 		= $linha['desc_menu'];
			$menus[$id] = new Menu($id, $nome, $link, $desc);
		}
		return $menus;
	}
	
	/**
	 * Retorna uma String contendo o link menu especificado
	 * @return String
	 */
	public function get_menu_link($id_menu) {
		$sql = $this->get_queries()->get_menu_link();
		
		$statement = $this->m_connection->prepare($sql);
		$statement->bindValue(':id_menu', $id_menu, PDO::PARAM_INT);
		$statement->execute();
		
		$s = $this->to_array($statement);
		
		if (sizeof($s) > 0) {
			return $s[0]['lnk_menu'];			
		}
		return null;
	}

	/**
	 * Retorna o numero total de senhas aguardando chamada em uma unidade
	 * 
	 * @return int
	 */
	public function get_total_fila($id_uni) {
		$sql = $this->get_queries()->get_total_fila();
		
		$statement = $this->get_connection()->prepare($sql);
        $statement->bindValue(':id_uni', $id_uni, PDO::PARAM_INT);
		$statement->execute();
		
		$ret = DB::to_array($statement);
		return $ret[0][0];
	}

	/**
	 * Retorna a ultima senha distribuida (gerada) em uma unidade
	 * 
	 * @return Senha senha
	 */
	public function get_ultima_senha($id_uni, $ids_stat = array(Atendimento::SENHA_EMITIDA)) {
		$sql = $this->get_queries()->get_ultima_senha();
	
		foreach ($ids_stat as $key => $value) {
    		$ids_stat[$key] = (int) $value;
    	}
    	$ids_stat = implode(",", $ids_stat);
    	$sql = str_replace(':ids_stat', $ids_stat, $sql);
    	
		$statement = $this->get_connection()->prepare($sql);
        $statement->bindValue(':id_uni', $id_uni, PDO::PARAM_INT);
		$statement->execute();
		
		$f = DB::to_array($statement);
		if (sizeof($f) > 0)
		{
//			$sigla 	= $f[0]['sigla_serv'];
//			$numero	= (int) $f[0]['num_senha'];

			$senha 	= DB::getInstance()->criar_senha($f[0]);
			//$senha = new Senha($sigla,$numero,$pri);
			return $senha;
		}
		return null;
	}
	
	/**
	 * Retorna o numero da proxima senha a ser gerada
	 * 
	 * @return int
	 */
	public function get_proxima_senha_numero() {
		$sql = $this->get_queries()->get_proxima_senha_numero();
		
		$statement = $this->m_connection->prepare($sql);
		$statement->execute();
		
		$f = $this->to_array($statement);
		return ((int) $f[0]['senha'])+1;
	}

    /**
     * Armazena todas as senhas de uma determinada unidade que são anteriores ao timestamp especificado no histórico.
     * Caso o timestamp não seja específicado o momento atual é considerado.
     *
     * @param int ID da unidade que deve ter suas senhas reiniciadas
     * @param int (Opcional) Timestamp usado para filtragem das senhas a serem reinicadas
     */
    public function reiniciar_senhas_unidade($id_uni, $dt_max = null) {
        if ($dt_max == null) {
            $dt_max = time();
        }

        $sql = $this->get_queries()->reiniciar_senhas_unidade();

		$statement = $this->get_connection()->prepare($sql);
        $statement->bindValue(':id_uni', $id_uni, PDO::PARAM_INT);
        $statement->bindValue(':dt_max', date("Y-m-d H:i:s", $dt_max), PDO::PARAM_STR);
		$statement->execute();
    }

    /**
     * Armazena todas as senhas de todas unidades que são anteriores ao timestamp especificado no histórico.
     * Caso o timestamp não seja específicado o momento atual é considerado.
     *
     * @param int (Opcional) Timestamp usado para filtragem das senhas a serem reinicadas
     */
    public function reiniciar_senhas_global($dt_max = null) {
        if ($dt_max == null) {
            $dt_max = time();
        }

        $sql = $this->get_queries()->reiniciar_senhas_global();

		$statement = $this->get_connection()->prepare($sql);
        $statement->bindValue(':dt_max', date("Y-m-d H:i:s", $dt_max), PDO::PARAM_STR);
		$statement->execute();
    }

	/**
	 * Retorna o Servico especificado pelo id do servico
	 * 
	 * @param int $id_serv Id do servico
	 * @return Servico
	 */
	public function get_servico($id_serv) {
		$sql = $this->get_queries()->get_servico();
		
		$statement = $this->get_connection()->prepare($sql);
		$statement->bindValue(':id_serv', $id_serv, PDO::PARAM_INT);		
		$statement->execute();
		
		$s = $this->to_array($statement);
        
		$servico 	= DB::getInstance()->criar_servico($s[0]);
		
		return $servico;
	}
	
	/**
	 * Retorna o Servico especificado pelo id do servico
	 * e pela unidade atual
	 * @param int $id_serv Id do servico
	 * @return Servico
	 */
	public function get_servico_unidade($id_serv, $id_uni) {
		$sql = $this->get_queries()->get_servico_current_uni();
		
		$statement = $this->get_connection()->prepare($sql);
		$statement->bindValue(':id_serv', $id_serv, PDO::PARAM_INT);		
		$statement->bindValue(':id_uni', $id_uni, PDO::PARAM_INT);
		
		$statement->execute();
		
		$s = $this->to_array($statement);
		
		$servico 	= DB::getInstance()->criar_servico($s[0]);		
		return $servico;
	}
	
	/**
	 * Insere um servico global no banco
	 * 
	 * @param int $id_macro ID do servico macro do qual o novo servico será filho, passe null para criar um macro
	 * @param string $desc_serv Descrição do serviço a ser inserido.
	 * @return Servico o servico inserido
	 */
	public function inserir_servico($id_macro, $nm_serv, $desc_serv, $stat_serv) {
		$sql = $this->get_queries()->inserir_servico();
		
		$statement = $this->m_connection->prepare($sql);
		$statement->bindValue(':id_macro', $id_macro, PDO::PARAM_INT);
		$statement->bindValue(':nm_serv', $nm_serv, PDO::PARAM_STR);
		$statement->bindValue(':desc_serv', $desc_serv, PDO::PARAM_STR);
		$statement->bindValue(':stat_serv', $stat_serv, PDO::PARAM_INT);
		$statement->execute();
	}

	/**
	 * Atualiza um servico global no banco
	 * 
	 * @param int $id_macro ID do servico macro do qual servico será filho, passe null para criar um macro
	 * @param string $desc_serv Descrição do serviço a ser atualizada.
	 * @return void
	 */
	public function atualizar_servico($id_serv, $id_macro, $nm_serv, $desc_serv, $stat_serv) {
		$sql = $this->get_queries()->atualizar_servico();
		
		$statement = $this->m_connection->prepare($sql);
		$statement->bindValue(':id_serv', $id_serv, PDO::PARAM_INT);
		$statement->bindValue(':id_macro', $id_macro, PDO::PARAM_INT);
		$statement->bindValue(':nm_serv', $nm_serv, PDO::PARAM_STR);
		$statement->bindValue(':desc_serv', $desc_serv, PDO::PARAM_STR);
        $statement->bindValue(':stat_serv', $stat_serv, PDO::PARAM_STR);
		
		$statement->execute();
		
		DB::getInstance()->atualizar_sub_servico($id_serv, $stat_serv);
	}
	
	public function atualizar_sub_servico($id_serv,$stat_serv) {
		$sql = $this->get_queries()->atualizar_sub_servico();
		
		$statement = $this->m_connection->prepare($sql);
		$statement->bindValue(':id_serv', $id_serv, PDO::PARAM_INT);
        $statement->bindValue(':stat_serv', $stat_serv, PDO::PARAM_STR);
		
		$statement->execute();
	}
	
	public function atualiza_stat_uni_serv($id_serv, $stat_serv){
		$sql = $this->get_queries()->atualiza_stat_uni_serv();
		
		$statement = $this->m_connection->prepare($sql);
		$statement->bindValue(':id_serv', $id_serv, PDO::PARAM_INT);
        $statement->bindValue(':stat_serv', $stat_serv, PDO::PARAM_STR);
		
		$statement->execute();
	}
	
	public function get_stat_serv($id_serv){
		$sql = $this->get_queries()->get_stat_serv();
		
		$statement = $this->m_connection->prepare($sql);
		$statement->bindValue(':id_serv', $id_serv, PDO::PARAM_INT);
		
		$statement->execute();
		$stat_serv = $this->to_array($statement);
		
		return $stat_serv[0][0];
	}
	
	/**
	 * Remover um servico global do banco
	 * 
	 * @param int $id_serv ID do servico global a ser removido
	 */
	public function remover_servico($id_serv) {
		$sql = $this->get_queries()->remover_servico();
		
		$statement = $this->m_connection->prepare($sql);
		$statement->bindValue(':id_serv', $id_serv, PDO::PARAM_INT);	
		
		$statement->execute();
	}
	
	/**
	 * Adiciona a uma unidade um serviço ja existente no banco
	 * @param $id_uni, id da unidade
	 * @param $id_serv, id do serviço ja existente
	 * @param $nome_serv, nome do serviço na unidade
	 * @return Servico o servico inserido
	 */
	public function inserir_servico_uni($id_uni,$id_serv,$id_loc,$nome_novo_serv,$sigla,$status_serv) {
		$sql = $this->get_queries()->inserir_servico_uni();
		
		$statement = $this->m_connection->prepare($sql);
		$statement->bindValue(':id_uni', $id_uni, PDO::PARAM_INT);
		$statement->bindValue(':id_serv', $id_serv, PDO::PARAM_INT);
		$statement->bindValue(':id_loc', $id_loc, PDO::PARAM_INT);
		$statement->bindValue(':nome_serv', $nome_novo_serv, PDO::PARAM_STR);
		$statement->bindValue(':sigla', $sigla, PDO::PARAM_STR);
		$statement->bindValue(':status_serv', $status_serv, PDO::PARAM_INT);	
		
		$statement->execute();
		
		$servico = new Servico((int)$id_serv, $nome_novo_serv,$sigla);
		return $servico;
	}
	
	/**
	 * Altera um serviço
	 * @param $id_uni, id da unidade
	 * @param $id_serv, id do serviço ja existente
	 * @param $nome_serv, novo nome do serviço na unidade
	 * @return Servico o servico inserido
	 */
	public function alterar_servico_uni($id_uni,$id_serv,$id_loc,$nome_novo_serv,$sigla,$status_serv) {
		$sql = $this->get_queries()->alterar_servico_uni();
		
		$statement = $this->m_connection->prepare($sql);
		$statement->bindValue(':id_uni', $id_uni, PDO::PARAM_INT);
		$statement->bindValue(':id_serv', $id_serv, PDO::PARAM_INT);
		$statement->bindValue(':nome_serv', $nome_novo_serv, PDO::PARAM_STR);
		$statement->bindValue(':sigla', $sigla, PDO::PARAM_STR);	
		$statement->bindValue(':status_serv', $status_serv, PDO::PARAM_INT);	
		
		$statement->execute();
		
		$servico = new Servico((int)$id_serv, $nome_novo_serv,$sigla);
		return $servico;
	}
	
	/**
	 * Retorna um array contendo todos servicos ativos do sistema
	 * 
	 * @return array
	 */
	public function get_servicos() {
		$sql = $this->get_queries()->get_servicos();
		
		$statement = $this->m_connection->prepare($sql);
		$statement->execute();
		
		$servicos 	= array();
		$tmp 		= $this->to_array($statement);
		foreach ($tmp as $s) {
			$id 			= (int) $s['id_serv'];
			$servico 		= DB::getInstance()->criar_servico($s);
			$servicos[$id] 	= $servico;
		}
		return $servicos;
	}
	
	/**
	 * Retorna um array contendo todos servicos de uma unidade
	 * @param id da unidade
	 * @author robson
	 * @return array
	 */
	public function get_servicos_unidade($id_uni, $stats_serv = array(0, 1)) {
		$sql = $this->get_queries()->get_servicos_unidade();
		
		// verificação de segurança
    	// garante que todos os elementos de id_uni sao numero
    	foreach ($stats_serv as $key => $value) {
    		$stats_serv[$key] = (int) $value;
    	}
    	$stats_serv = implode(",", $stats_serv);
    	$sql = str_replace(':stats_serv', $stats_serv, $sql);
		
		$statement = $this->m_connection->prepare($sql);
		$statement->bindValue(':id_uni', $id_uni);
		$statement->execute();
		
		$servicos 	= array();
		$tmp 		= $this->to_array($statement);
		
		foreach ($tmp as $s) {
			$id 			= (int) $s['id_serv'];
			$servico 		= DB::getInstance()->criar_servico($s);
			$servicos[$id] 	= $servico;
		}
		
		return $servicos;
	}

	/**
	 * TESTE PARA LISTA SERVICOS EM ERRO TRIAGEM (ATENDIMENTO)
	 */
	public function get_servicos_unidade_erro_triagem($id_uni, $id_usu, $stats_serv = array(0, 1)) {
		$sql = $this->get_queries()->get_servicos_unidade_erro_triagem();
		
		// verificação de segurança
    	// garante que todos os elementos de id_uni sao numero
    	foreach ($stats_serv as $key => $value) {
    		$stats_serv[$key] = (int) $value;
    	}
    	$stats_serv = implode(",", $stats_serv);;
    	$sql = str_replace(':stats_serv', $stats_serv, $sql);
		
		$statement = $this->m_connection->prepare($sql);
		$statement->bindValue(':id_uni', $id_uni);
		$statement->bindValue(':id_usu', $id_usu);
		$statement->execute();
		
		$servicos 	= array();
		$tmp 		= $this->to_array($statement);
		
		foreach ($tmp as $s) {
			$id 			= (int) $s['id_serv'];
			$servico 		= DB::getInstance()->criar_servico($s);
			$servicos[$id] 	= $servico;
		}
		
		return $servicos;
	}
	
	/**
	 * Retorna um array contendo os servicos mestres de uma unidade
	 * 
	 * @param int status
	 * @return array
	 */
	public function get_servicos_mestre_unidade($id_uni, $stats_serv = array(Servico::SERVICO_ATIVO, Servico::SERVICO_INATIVO)) {
		$sql = $this->get_queries()->get_servicos_mestre_unidade();
        
        // verificação de segurança
    	// garante que todos os elementos de id_uni sao numero
    	foreach ($stats_serv as $key => $value) {
    		$stats_serv[$key] = (int) $value;
    	}
    	$stats_serv = implode(",", $stats_serv);;
    	$sql = str_replace(':stats_serv', $stats_serv, $sql);

		$statement = $this->m_connection->prepare($sql);
		$statement->bindValue(':id_uni', $id_uni);
		$statement->execute();
		
		$servicos 	= array();
		$tmp 		= $this->to_array($statement);
		foreach ($tmp as $s) {
			$id 			= (int) $s['id_serv'];
//			$nome 			= $s['nm_serv'];
//			$sigla          = $s['sigla_serv'];
//			$descricao 		= $s['desc_serv'];
			$servico 		= new Servico($id, $nome, $sigla);
//			$servico->set_descricao($descricao);
			$servicos[$id] 	= $servico;
		}
		return $servicos;
	}

	public function get_serv_disponiveis_uni($id_uni, $stats_serv = array(Servico::SERVICO_ATIVO, Servico::SERVICO_INATIVO)){
		$sql = $this->get_queries()->get_serv_disponiveis_uni();
        
        // verificação de segurança
    	// garante que todos os elementos de id_uni sao numeros
    	foreach ($stats_serv as $key => $value) {
    		$stats_serv[$key] = (int) $value;
    	}
    	$stats_serv = implode(",", $stats_serv);
    	$sql = str_replace(':stats_serv', $stats_serv, $sql);

		$statement = $this->m_connection->prepare($sql);
		$statement->bindValue(':id_uni', $id_uni);
		$statement->execute();
		
		return $this->to_array($statement);
	}
	
	
	/**
	 * Retorna liste de serviços mestre da unidade que possuem senha cancelada
	 * @param $status
	 * @param $id_uni
	 * @return unknown_type
	 */
	public function get_servicos_unidade_reativar($status, $id_uni) {
		$sql = $this->get_queries()->get_servicos_unidade_reativar();		
		
		$ids_stat = array(Atendimento::SENHA_CANCELADA,Atendimento::NAO_COMPARECEU);
		
		foreach ($ids_stat as $key=> $value){
			$ids_stat[$key] = (int) $value;
		}
		
		$ids_stat = implode(",",$ids_stat);
		$sql = str_replace(":id_stat",$ids_stat,$sql);
		
		$statement = $this->m_connection->prepare($sql);
		$statement->bindValue(':stats_serv', $status, PDO::PARAM_INT);
		$statement->bindValue(':id_uni', $id_uni, PDO::PARAM_INT);
		
		$statement->execute();
		
		$servicos 	= array();
		$tmp 		= $this->to_array($statement);
		foreach ($tmp as $s) {
			$id 			= (int) $s['id_serv'];
			$servico 		= DB::getInstance()->criar_servico($s);
			$servicos[$id] 	= $servico;
		}
		return $servicos;
	}
	
	public function get_servicos_unidade_transfere_senha($status, $id_uni,$id_serv) {
		$sql = $this->get_queries()->get_servicos_unidade_transfere_senha();		
		$statement = $this->m_connection->prepare($sql);
		$statement->bindValue(':stats_serv', $status, PDO::PARAM_INT);
		$statement->bindValue(':id_uni', $id_uni, PDO::PARAM_INT);
		$statement->bindValue(':id_serv', $id_serv, PDO::PARAM_INT);		
		$statement->execute();
		
		$servicos 	= array();
		$tmp 		= $this->to_array($statement);
		foreach ($tmp as $s) {
			$id 			= (int) $s['id_serv'];
			$servico 		= DB::getInstance()->criar_servico($s);
			$servicos[$id] 	= $servico;
		}
		return $servicos;
	}
	
	/**
	 * Retorna um array contendo os servicos mestres do Sistema
	 * 
	 * @return array
	 */
	public function get_servicos_mestre($stats_serv = array(Servico::SERVICO_ATIVO, Servico::SERVICO_INATIVO)) {
		$sql = $this->get_queries()->get_servicos_mestre();

        // verificação de segurança
    	// garante que todos os elementos de id_uni sao numero
    	foreach ($stats_serv as $key => $value) {
    		$stats_serv[$key] = (int) $value;
    	}
    	$stats_serv = implode(",", $stats_serv);;
    	$sql = str_replace(':stats_serv', $stats_serv, $sql);

		$statement = $this->m_connection->prepare($sql);
		$statement->execute();
		
		$servicos 	= array();
		$tmp 		= $this->to_array($statement);
		foreach ($tmp as $s) {
			$id 			= (int) $s['id_serv'];
			$servico 		= DB::getInstance()->criar_servico($s);
			$servicos[$id] 	= $servico;
		}
		return $servicos;
	}
	
	/**
	 * Retorna um array contendo todos subservicos do servico mestre
	 * 
	 * @param int mestre (id do servico mestre)
	 * @param int status (id do status do subservico)
	 * @return array
	 */
	public function get_servicos_sub_unidade($mestre, $status, $id_uni) {
		$sql = $this->get_queries()->get_servicos_sub_unidade();	
		$statement = $this->m_connection->prepare($sql);
		$statement->bindValue(':mestre', $mestre, PDO::PARAM_INT);
		$statement->bindValue(':status', $status, PDO::PARAM_INT);		
		$statement->bindValue(':id_uni', $id_uni, PDO::PARAM_INT);
		$statement->execute();
		
		$servicos 	= array();
		$tmp 		= $this->to_array($statement);
		foreach ($tmp as $s) {
			$servico 		= DB::getInstance()->criar_servico($s);
			$servicos[] 	= $servico;
		}
		return $servicos;
	}
	
/**
	 * Retorna um array contendo todos subservicos do servico mestre
	 * 
	 * @param int mestre (id do servico mestre)
	 * @param int status (id do status do subservico)
	 * @return array
	 */
	public function get_servicos_sub($id_macro, $stats_serv = array(Servico::SERVICO_ATIVO, Servico::SERVICO_INATIVO)) {
		$sql = $this->get_queries()->get_servicos_sub();

        // verificação de segurança
    	// garante que todos os elementos de id_uni sao numero
    	foreach ($stats_serv as $key => $value) {
    		$stats_serv[$key] = (int) $value;
    	}
        $stats_serv = implode(",", $stats_serv);;
    	$sql = str_replace(':stats_serv', $stats_serv, $sql);

		$statement = $this->m_connection->prepare($sql);
		$statement->bindValue(':id_macro', $id_macro, PDO::PARAM_INT);
		$statement->execute();
		
		$servicos 	= array();
		$tmp 		= $this->to_array($statement);
		foreach ($tmp as $s) {
			$servico 		= DB::getInstance()->criar_servico($s);
			$servicos[] 	= $servico;
		}
		return $servicos;
	}
	
	public function get_servicos_sub_nao_cadastrados_uni($id_uni){
		$sql = $this->get_queries()->get_servicos_sub_nao_cadastrados_uni();
		$statement = $this->m_connection->prepare($sql);
		$statement->bindValue(':id_uni', $id_uni, PDO::PARAM_INT);
		$statement->execute();
		$servicos = $this->to_array($statement);
		return $servicos;
	}

	public function criarArrayAtendimentos($atendimentos){
		$atds = array();
		foreach ($atendimentos as $atendimento){
			$atds[] = DB::getInstance()->criarAtendimento($atendimento);
		}
		return $atds;
	}
	public function criarAtendimento($atendimento, $cli=null){
		$id_atend = (int) $atendimento['id_atend'];
		$id_stat  = (int) $atendimento['id_stat'];
		$num_guiche = (int)$atendimento['num_guiche'];
		
		$dt_cheg = $atendimento['dt_cheg'];
		$dt_cha = $atendimento['dt_cha'];
		$dt_ini = $atendimento['dt_ini'];
		$dt_fim = $atendimento['dt_fim'];
		
		$id_serv  = (int) $atendimento['id_serv'];
				
		return new Atendimento($id_atend, $cli, $id_serv, $id_stat, $dt_cheg, $dt_cha , $dt_ini , $dt_fim, $num_guiche);
	
	}
	
	/**
	 * Retorna o proximo atendimento já no estado Atendimento::CHAMADO_PELA_MESA 
	 * O Atendimento é retornado após ser atualizado no banco, já tendo o usuário e o status atualizados.
	 * 
	 * @param $id_usu O ID do usuario atendendo
	 * @param $id_uni A unidade em que o atendimento esta sendo feito
	 * @param $servicos Os serviços de interesse.
	 * @return Atendimento O próximo atendimento
	 */
	public function chama_proximo_atendimento($id_usu, $id_uni, $servicos, $num_guiche) {
		// força conversão de todos elementos para inteiros
		if ($servicos == array()) {
			$servicos[0] = 'null';
		}
		else {
			foreach ($servicos as $key => $value)
			{
				$servicos[$key] = (int) $value;
			}
		}
		
		$servicos = implode(",", $servicos);
		
		$sql = $this->get_queries()->get_proximo_atendimento();
		
		// substitui diretamente, mas continua protegida contra SQL injection pelo fato de termos 
		// garantido que todos são numeros, em conversão feita acima
		$sql = str_replace(':servicos', $servicos, $sql);
		try {
			$this->m_connection->beginTransaction();
			
			$statement = $this->m_connection->prepare($sql);
			$statement->bindValue(":id_uni", $id_uni);
			$statement->execute();		
		
			$array = $this->to_array($statement);
			if (count($array) == 0)
			{
				return false;
			}
			$f = $array[0];

			$numero   = (int) $f['num_senha'];	

	        $pri    = DB::getInstance()->criar_prioridade($f);
			$senha  = DB::getInstance()->criar_senha($f);
			//$senha = new Senha($sigla,$numero,$pri);
			$senha->set_prioridade($pri);
			
			$cli = DB::getInstance()->criar_cliente($f, $senha);
			
			$servico = DB::getInstance()->criar_servico($f);
			
//			$atendimento = new Atendimento($id_atend, $cli, $servico, $id_stat,  $dt_cheg, $dt_cha , $dt_ini , $dt_fim);
			$atendimento = DB::getInstance()->criarAtendimento($f,$cli);
			$atendimento->set_status(Atendimento::CHAMADO_PELA_MESA);
			$id_atend = $atendimento->get_id();
			
			// Atualiza status no banco
			DB::getInstance()->set_atendimento_status($id_atend, Atendimento::CHAMADO_PELA_MESA);
			
			// Atualiza e o usuario que esta efetuando o atendimento
			DB::getInstance()->set_atendimento_usuario($id_atend, $id_usu);
			
			// Atualiza o guiche onde o atendimento está sendo executado
			//$num_guiche = DB::getInstance()->get_usuario_by_id($id_usu)->get_num_guiche();
			DB::getInstance()->set_atendimento_guiche($id_atend,$num_guiche);
			
			$this->m_connection->commit();
			
			return $atendimento;
		}
		catch (PDOException $e) {
			try { 
				$this->m_connection->rollBack(); 
			}
			catch (Exception $e2) {
				// nao havia transação
			}
			throw $e;	
		}
	}
	
	/**
	 * Retorna a Fila de clientes de acordo com o(s) servico(s) especificado(s)
	 * 
	 * @param array servicos
	 * @return Fila
	 */
	public function get_fila($servicos, $id_uni, $ids_stat = array(Atendimento::SENHA_EMITIDA)) {
		if ($servicos == array()) {
			$servicos[0] = 'null';
		}
		else {
			foreach ($servicos as $key => $value)
			{
				$servicos[$key] = (int) $value;
			}
		}
		
		$servicos = implode(",", $servicos);
		
		$sql = $this->get_queries()->get_fila();
		$sql = str_replace(':servicos', $servicos, $sql);
		
		foreach ($ids_stat as $key=>$value){
			
			$ids_stat[$key] = (int) $value;
    	}
    	$ids_stat = implode(",", $ids_stat);;
    	$sql = str_replace(':id_stat', $ids_stat, $sql);
    			
		$statement = $this->m_connection->prepare($sql);
		$statement->bindValue(':id_uni', $id_uni, PDO::PARAM_INT);
		$statement->execute();		
		
		$aux  = $this->to_array($statement);
		$fila = array();
		foreach ($aux as $f) {
			$id_atend = (int) $f['id_atend'];
			$pri    = DB::getInstance()->criar_prioridade($f);
			$senha = DB::getInstance()->criar_senha($f);
			$senha->set_prioridade($pri);
			$cli = DB::getInstance()->criar_cliente($f,$senha);
			
			$fila[$id_atend] = DB::getInstance()->criarAtendimento($f,$cli);
		}
		return new Fila($fila);

	}
	
	
	/**
	 * Retorna um atendimento a partir de seu ID
	 * 
	 * @param id_atendimento
	 * @return Atendimento
	 */
	public function get_atendimento($id_atendimento){
		$sql = $this->get_queries()->get_atendimento();
		
		$statement = $this->m_connection->prepare($sql);
		$statement->bindValue(':id_atendimento', $id_atendimento, PDO::PARAM_INT);
		$statement->execute();

		$array = $this->to_array($statement);
		if (count($array) == 0)
		{
			return false;
		}
		
		$f = $array[0];
		
		$pri    = DB::getInstance()->criar_prioridade($f);
		$senha  = DB::getInstance()->criar_senha($f);
		//$senha = new Senha($sigla,$numero,$pri);
		$senha->set_prioridade($pri);
		$cli = DB::getInstance()->criar_cliente($f, $senha);
		
		$servico = DB::getInstance()->criar_servico($f);
		
//		return new Atendimento($id_atend, $cli, $id_serv, $id_stat,  $dt_cheg, $dt_cha , $dt_ini , $dt_fim);
		return DB::getInstance()->criarAtendimento($f,$cli);
	}
	
	/**
	 * Retorna um atendimento a partir de seu ID e que esteja com um status contido no parametro status
	 * 
	 * @param $id_usu ID do usuario
	 * @param $status Um array contendo os status
	 * @return array(Atendimento)
	 */
	public function get_atendimentos_by_usuario($id_usu, $id_uni, $status) {
		
		// verificação de seguranca contra SQL Injection, garante que só numeros irão para a Query
		foreach ($status as $key => $value) {
			// qualquer caractere não numerico será anulado aqui pela conversão
			$status[$key] = (int) $value;
		}
		
		$status = implode(",", $status);
		
		$sql = $this->get_queries()->get_atendimentos_by_usuario();
		$sql = str_replace(':status', $status, $sql);
		
		$statement = $this->m_connection->prepare($sql);
		$statement->bindValue(':id_usu', $id_usu, PDO::PARAM_INT);
		$statement->bindValue(':id_uni', $id_uni, PDO::PARAM_INT);
		$statement->execute();
		
		$array = $this->to_array($statement);
		$ret = array();
		foreach ($array as $f) {
		 
			$pri    = DB::getInstance()->criar_prioridade($f);
			$senha = DB::getInstance()->criar_senha($f);
			//$senha  = new Senha($sigla, $numero, $pri);
			$senha->set_prioridade($pri);
			$cli = DB::getInstance()->criar_cliente($f,$senha);
			$servico = DB::getInstance()->criar_servico($f);
			
//			$ret[] =  new Atendimento($id_atend, $cli, $servico, $id_stat,  $dt_cheg, $dt_cha , $dt_ini , $dt_fim);
			$ret[] = DB::getInstance()->criarAtendimento($f,$cli);
		}
		return $ret;
	}
	
	/**
	 * Retorna um atendimento a partir de sua senha
	 * 
	 * @param num_senha, id_uni
	 * @return Atendimento
	 */
	public function get_atendimento_por_senha($num_senha,$id_uni,$id_stat=Atendimento::SENHA_EMITIDA){
		$sql = $this->get_queries()->get_atendimento_por_senha();
		$statement = $this->m_connection->prepare($sql);
		$statement->bindValue(':num_senha', $num_senha, PDO::PARAM_INT);
		$statement->bindValue(':id_uni', $id_uni, PDO::PARAM_INT);
		$statement->bindValue(':id_stat', $id_stat, PDO::PARAM_INT);
		$statement->execute();

		$array = $this->to_array($statement);
		if (count($array) == 0)
		{
			return false;
		}
		
		$f = $array[0];
		$pri    = DB::getInstance()->criar_prioridade($f);
		$senha = DB::getInstance()->criar_senha($f);
		//$senha  = new Senha($sigla, $numero, $pri);
		$senha->set_prioridade($pri);
		$cli = DB::getInstance()->criar_cliente($f,$senha);

		return DB::getInstance()->criarAtendimento($f,$cli);
	}
	
	/**
	 * Define o status do Servico em Atendimento
	 * 
	 * @param int $id_atend
	 * @param int $status
	 */
	public function set_atendimento_status($id_atend, $status) {
	    $sql = $this->get_queries()->set_atendimento_status();
	    
	    switch ($status) {
		    case Atendimento::CHAMADO_PELA_MESA:
		    	$column = 'dt_cha';
		        break;
	        case Atendimento::ATENDIMENTO_INICIADO:
	            $column = 'dt_ini';
		        break;
	        case Atendimento::ATENDIMENTO_ENCERRADO:
	        case Atendimento::NAO_COMPARECEU:
	        case Atendimento::SENHA_CANCELADA:
	        case Atendimento::ERRO_TRIAGEM:
	        case Atendimento::ATENDIMENTO_ENCERRADO_CODIFICADO:
	            $column = 'dt_fim';
		        break;
		  	case Atendimento::SENHA_EMITIDA:
	            $column = 'dt_fim = null, dt_cheg';
		        break;
	        default:
	        	throw new Exception('O valor de status passado para o atendimento é inválido: '.$status);
	    }
	    
	    $sql = str_replace(':column', $column, $sql);
	    
	    $statement = $this->m_connection->prepare($sql);
		$statement->bindValue(':status', $status, PDO::PARAM_INT);
	    $statement->bindValue(':id_atend', $id_atend, PDO::PARAM_INT);
        $statement->bindValue(':dt_time', SGA::get_date("Y-m-d H:i:s"), PDO::PARAM_STR);
		$statement->execute();
	}
	
	/**
	 * Define a prioridade do Atendimento
	 * 
	 * @param int $id_atend
	 * @param int $status
	 */
	public function set_atendimento_prioridade($id_atend, $id_pri) {
	    $sql = $this->get_queries()->set_atendimento_prioridade();
	    
	    $statement = $this->m_connection->prepare($sql);
	    $statement->bindValue(':id_atend', $id_atend, PDO::PARAM_INT);
	    $statement->bindValue(':id_pri',$id_pri, PDO::PARAM_INT);
		$statement->execute();
	}
	
	/**
	 * Define o usuario que etsá efetuando o Atendimento
	 * 
	 * @param $id_atend ID do atendimento
	 * @param $id_usu ID do usuario
	 * @return void
	 */
	public function set_atendimento_usuario($id_atend, $id_usu) {
	    $sql = $this->get_queries()->set_atendimento_usuario();
	    
	    $statement = $this->m_connection->prepare($sql);
	    $statement->bindValue(':id_atend', $id_atend, PDO::PARAM_INT);
		$statement->bindValue(':id_usu', $id_usu, PDO::PARAM_INT);
	    
		$statement->execute();
	}
	/**
	 * Define o guiche onde o atendimento foi efetuado
	 * @param $id_atend
	 * @param $num_guiche
	 * @return void
	 */
	public function set_atendimento_guiche($id_atend,$num_guiche){
		$sql = $this->get_queries()->set_atendimento_guiche();
		
		$statement = $this->m_connection->prepare($sql);
		$statement->bindValue(':id_atend', $id_atend, PDO::PARAM_INT);
		$statement->bindValue(':num_guiche', $num_guiche, PDO::PARAM_INT);
		
		$statement->execute();
	}
	/**
	 * Coloca uma nova senha para ser chamada pelo painel
	 * 
	 * @param int $id_uni
	 * @param int $id_serv
	 * @param int $num_senha
	 * @param char $sig_senha
	 * @param String $msg_senha (ex.: "Senha")
	 * @param String $nm_local (ex.: "Mesa", "Sala", etc.)
	 * @param int $num_guiche
	 */
	public function chama_proximo($id_uni, $id_serv, $num_senha, $sig_senha, $msg_senha, $nm_local, $num_guiche) {
	    $sql = $this->get_queries()->chama_proximo();
	    
	    $statement = $this->m_connection->prepare($sql);
		$statement->bindValue(':id_uni', $id_uni, PDO::PARAM_INT);
		$statement->bindValue(':id_serv', $id_serv, PDO::PARAM_INT);
		$statement->bindValue(':num_senha', $num_senha, PDO::PARAM_INT);
		$statement->bindValue(':sig_senha', $sig_senha, PDO::PARAM_INT);
		$statement->bindValue(':msg_senha', $msg_senha, PDO::PARAM_STR);
		$statement->bindValue(':nm_local', $nm_local, PDO::PARAM_STR);
		$statement->bindValue(':num_guiche', $num_guiche, PDO::PARAM_INT);
		$statement->execute();
	}

	/**
	 * Retorna um array contendo as prioridades (Prioridade)
	 * 
	 * @return array
	 */
	public function get_prioridades() {
		$sql = $this->get_queries()->get_prioridades();
		
		$statement = $this->m_connection->prepare($sql);
		$statement->execute();
		
		$aux = $this->to_array($statement);
		$n 	 = count($aux);
		$pri = array();
		foreach ($aux as $p) {
			$id    = (int) $p['id_pri'];
			$pri[$id] = DB::getInstance()->criar_prioridade($p);
		}
		return $pri;
	}

	/**
	 * Transfere a senha informada para o novo Servico, mudando
	 * ou nao sua Prioridade
	 * 
	 * @param int $senha
	 * @param int $servico
	 * @param int $prioridade
	 */
	public function transfere_senha($id_atend, $servico, $prioridade) {
		$sql = $this->get_queries()->transfere_senha();
		
		$statement = $this->m_connection->prepare($sql);
		$statement->bindValue(':id_atend', $id_atend, PDO::PARAM_INT);
		$statement->bindValue(':servico', $servico, PDO::PARAM_INT);
		$statement->bindValue(':prioridade', $prioridade, PDO::PARAM_INT);
		$statement->execute();
	}

	public function atualiza_painel() {
		$sql = "SELECT COUNT(id_painel_senha) FROM painel_senha";
	
		$statement = $this->m_connection->prepare($sql);
		$statement->execute();
		
		$tmp = to_array($statement);
		$tmp = $tmp[0][0];
		
		return $tmp > 0;
	}
    
    /**
     * Distribui(Cria) uma senha de Atendimento e retorna a senha criada.
     * Joga uma PDOException se houver outra triagem da unidade distribuindo senha ao mesmo tempo(lock da row)
     * 
     * @param $id_uni ID da unidade
     * @param $id_serv ID do servico do atendimento
     * @param $id_pri ID da prioridade do atendimento
     * @param $num_guiche Numero do guiche
     * @param $id_stat Status da senha
     * @param $nm_cli Nome do cliente
     * @param $cpf CPF do cliente
     * @param $dt_che Data da distribuicao da senha
     */

    public function distribui_senha($id_uni, $id_serv, $id_pri, $num_guiche, $id_stat = 1, $nm_cli = "", $ident_cli="", $dt_cheg = null){
		if ($dt_cheg == null) {
            $dt_cheg = SGA::get_date("Y-m-d H:i:s");
        }
        $nm_cli = strtoupper($nm_cli);

        // Obtem a ultima senha e incrementa em 1
        // Protegido contra concorrencia
        $this->get_connection()->beginTransaction();
        try {
            $ids_stat = Atendimento::$ARRAY_TODOS_STATUS;

            $sql = $this->get_queries()->get_ultima_senha_lock();
            $sql = str_replace(':ids_stat', DB::get_lista_int_in($ids_stat), $sql);

            $statement = $this->get_connection()->prepare($sql);
            $statement->bindValue(':id_uni', $id_uni, PDO::PARAM_INT);
            // esse execute() irá jogar uma PDOException se a row a ser selecionada estiver lockada
            $statement->execute();

            $ret = DB::to_array($statement);
            // se não houver senha distribuida, usar 0
            $numero = (sizeof($ret) > 0) ? $ret[0]['num_senha'] : 0;
            $numero++;

            $sql = $this->get_queries()->distribui_senha();
            $statement = $this->get_connection()->prepare($sql);

            $statement->bindValue(':id_uni', $id_uni, PDO::PARAM_INT);
            $statement->bindValue(':id_serv', $id_serv, PDO::PARAM_INT);
            $statement->bindValue(':id_pri', $id_pri, PDO::PARAM_INT);
            $statement->bindValue(':id_stat', $id_stat, PDO::PARAM_INT);
            $statement->bindValue(':num_senha', $numero, PDO::PARAM_INT);
            $statement->bindValue(':nm_cli', $nm_cli, PDO::PARAM_STR);
            $statement->bindValue(':num_guiche', $num_guiche, PDO::PARAM_INT);
            $statement->bindValue(':dt_cheg', $dt_cheg, PDO::PARAM_STR);
            $statement->bindValue(':ident_cli', $ident_cli, PDO::PARAM_STR);
            $statement->execute(); // insere atendimento

            // obtem o ID do atendimento inserido
            $id_atend = $this->get_connection()->lastInsertId("atendimentos_id_atend_seq");

            // busca o atendimento inserido
            $retn = $this->get_atendimento($id_atend)->get_cliente()->get_senha();

            // confirma transação
            $this->get_connection()->commit();

            // retorna o atendimento criado
            return $retn;
        }
        catch (PDOException $e) {
            // capturo a exceção, só para executar rollback na transação
            $this->get_connection()->rollback();
            // jogo a exceção para frente, para o código que chamou este método
            throw $e;
        }
    }
    
	/**
     * Transfere uma senha de Atendimento e retorna a senha criada.
     * 
     * @param $id_uni ID da unidade
     * @param $id_serv ID do servico do atendimento
     * @param $id_pri ID da prioridade do atendimento
     * @param $num_guiche Numero do guiche
     * @param $id_stat Status da senha
     * @param $nm_cli Nome do cliente
     * @param $cpf CPF do cliente
     * @param $dt_che Data da distribuicao da senha
     */
    public function erro_triagem($id_uni, $id_serv,$num_senha, $id_pri, $num_guiche, $id_stat = 1, $nm_cli = "", $ident_cli="",$dt_cheg = ""){
    	$sql = $this->get_queries()->distribui_senha();
		$nm_cli = strtoupper($nm_cli);
		
		$statement = $this->m_connection->prepare($sql);
		$statement->bindValue(':id_uni', $id_uni, PDO::PARAM_INT);
		$statement->bindValue(':id_serv', $id_serv, PDO::PARAM_INT);
		$statement->bindValue(':id_pri', $id_pri, PDO::PARAM_INT);
		$statement->bindValue(':id_stat', $id_stat, PDO::PARAM_INT);
		$statement->bindValue(':num_senha', $num_senha, PDO::PARAM_INT);
		$statement->bindValue(':nm_cli', $nm_cli, PDO::PARAM_STR);
		$statement->bindValue(':num_guiche', $num_guiche, PDO::PARAM_INT);
		$statement->bindValue(':dt_cheg', $dt_cheg, PDO::PARAM_STR);
		$statement->bindValue(':ident_cli', $ident_cli, PDO::PARAM_STR);
		$statement->execute();
    }
    
    
    public function encerra_atendimentos($id_atend, $id_uni, $array) {
    	foreach($array as $servico) {
    		//mudar o 1 pelo peso do servico
    		DB::getInstance()->encerra_atendimento($id_atend, $id_uni,$servico,1);
    	}
    }
    public function encerra_atendimento($id_atend, $id_uni, $id_serv, $valor_peso){
    	$sql = $this->get_queries()->encerra_atendimento();
    	$statement = $this->m_connection->prepare($sql);
    	$statement->bindValue(':id_atend', $id_atend, PDO::PARAM_INT);
    	$statement->bindValue(':id_serv', $id_serv, PDO::PARAM_INT);
    	$statement->bindValue(':valor_peso', $valor_peso, PDO::PARAM_INT);
    	$statement->execute();
    }
	
   public function get_quantidade_fila($id_servico,$id_uni){
   		$id_stat = Atendimento::SENHA_EMITIDA;
    	$sql = $this->get_queries()->quantidade_fila();
		
		$statement = $this->m_connection->prepare($sql);
		
		$statement->bindValue(':id_serv',$id_servico, PDO::PARAM_INT);
		$statement->bindValue(':id_uni',$id_uni, PDO::PARAM_INT);
		$statement->bindValue(':id_stat',$id_stat,PDO::PARAM_INT);
		
		$statement->execute();
		$fila = $this->to_array($statement);
		$fila = $fila[0][0];
		return $fila;
    }
    
    public function get_quantidade_total($id_serv,$id_uni){
    	$sql = $this->get_queries()->quantidade_total();
		
		$statement = $this->m_connection->prepare($sql);
		
		$statement->bindValue(':id_serv',$id_serv, PDO::PARAM_INT);
		$statement->bindValue(':id_uni',$id_uni, PDO::PARAM_INT);
		
		$statement->execute();
		$total = $this->to_array($statement);
		$total = $total[0][0];
		return $total;
    }
   
    /**
     * Retorna a mensagem que é exibida na senha
     * @param $id_uni
     * @return String ($msg)
     */
    public function get_senha_msg_loc($id_uni){
    	$sql = $this->get_queries()->get_senha_msg_loc();
    	
    	$statement = $this->m_connection->prepare($sql);
    	
    	$statement->bindValue(':id_uni',$id_uni, PDO::PARAM_INT);
		
		$statement->execute();
		$msg = $this->to_array($statement);
		$msg = $msg[0][0];
		return $msg;
    	
    }
    
    /**
     * Modifica a mensagem (exibida na senha) e o id_usu de quem modificou
	 * @param $id_uni
	 * @param $id_usu
	 * @param $msg
	 * @return none
     */
	public function set_senha_msg_loc($id_uni,$id_usu,$msg){
    	$sql = $this->get_queries()->set_senha_msg_loc();
    	
    	$statement = $this->m_connection->prepare($sql);
    	
    	$statement->bindValue(':id_uni',$id_uni, PDO::PARAM_INT);
    	$statement->bindValue(':id_usu',$id_usu, PDO::PARAM_INT);
    	$statement->bindValue(':msg',$msg, PDO::PARAM_STR);
		
		$statement->execute();
    }
    
	/**
     * Retorna a mensagem padrao da unidade que é exibida na senha
     * @param $id_uni
     * @return String ($msg)
     */
    public function get_senha_msg_global(){
    	$sql = $this->get_queries()->get_senha_msg_global();
    	
    	$statement = $this->m_connection->prepare($sql);
		
		$statement->execute();
		$msg = $this->to_array($statement);
		$msg = $msg[0][0];
		return $msg;
    	
    }
    
    /**
     * Modifica a mensagem global e o id_usu de quem modificou
	 * @param $id_uni
	 * @param $id_usu
	 * @param $msg
	 * @return none
     */
	public function set_senha_msg_global($id_usu,$msg){
    	$sql = $this->get_queries()->set_senha_msg_global();
    	
    	$statement = $this->m_connection->prepare($sql);
    	
    	$statement->bindValue(':id_usu',$id_usu, PDO::PARAM_INT);
    	$statement->bindValue(':msg',$msg, PDO::PARAM_STR);
		
		$statement->execute();
    
	}
	
	public function set_senha_msg_global_unidades_locais($id_usu,$msg){
    	$sql = $this->get_queries()->set_senha_msg_global_unidades_locais();
    	
    	$statement = $this->m_connection->prepare($sql);
    	
    	$statement->bindValue(':id_usu',$id_usu, PDO::PARAM_INT);
    	$statement->bindValue(':msg',$msg, PDO::PARAM_STR);
		
		$statement->execute();
    }


    public function get_atendimento_senha_periodo($num_senha,$id_uni,$data_inicio,$data_fim){
    	$sql = $this->get_queries()->get_atendimento_senha_periodo();
    	
		$statement = $this->m_connection->prepare($sql);
		//$data_inicio = PDO::quote($data_inicio);
		$statement->bindValue(':num_senha', $num_senha, PDO::PARAM_INT);
		$statement->bindValue(':id_uni', $id_uni, PDO::PARAM_INT);
		$statement->bindValue(':dt_ini', $data_inicio, PDO::PARAM_STR);
		$statement->bindValue(':dt_fim', $data_fim, PDO::PARAM_STR);
		$statement->execute();

		$array = $this->to_array($statement);
		if (count($array) == 0)
		{
			return false;
		}
		
		$array_atendimentos = array();
		foreach ($array as $f ){
			$id_servicos = $f['id_servicos'];
			
			$hr_cheg  = $f['dt_cheg'];
			$hr_ini_atend = $f['dt_ini'];
			$hr_fim_atend = $f['dt_fim'];
			$id_usu = $f['id_usu'];//
			$login_usu = $f['login_usu'];
			
			$nm_pri = $f['nm_pri'];//
			$id_pri = $f['id_prio'];
			$peso_pri = $f['peso_pri'];
			$prioridade = new Prioridade($id_pri,$nm_pri,'',$peso_pri);
			
			$num_senha = $f['num_senha'];//
			$sigla_serv = $f['sigla_serv'];
			$senha = new Senha($sigla_serv,$num_senha);
			$senha->set_prioridade($prioridade);
			
			$id_stat = $f['id_stat'];
			$num_guiche = $f['num_guiche'];
			$id_atend =(int) $f['id_atend'];
			
			$ident_cli = $f['id_atend'];
			$cliente = new Cliente('',$senha,$ident_cli);
			
			$array_atendimentos[] = new Atendimento($id_atend, $cliente, $id_servicos, $id_stat,
			$hr_cheg, $hr_ini_atend , $hr_ini_atend , $hr_fim_atend, $num_guiche,$login_usu);			
			
			
			//$array_atendimentos[] = DB::getInstance()->criarAtendimento($f,$cli);
			//$pri    = DB::getInstance()->criar_prioridade($f);
			//$senha = DB::getInstance()->criar_senha($f);
			//$senha  = new Senha($sigla, $numero, $pri);
			//$senha->set_prioridade($pri);
			//$cli = DB::getInstance()->criar_cliente($f,$senha);
			
//			
//			// o usuario so esta disponivel depois de chamada
//			if ($id_usu ) {
//				$usuario = DB::getInstance()->criar_usuario($f);
//			}
//			else {
//				$usuario = null;
//			}
			
//			implode(',',$id_servicos);
			
    	}
    	
    	return $array_atendimentos; 
    }


    /**
     * Remove um serviço de um usuário em uma unidade
     *
     * @param $id_uni id da unidade
     * @param $id_serv id do serviço
     * @param $id_usu id do usuário
     */
    public function remover_servico_usu($id_uni, $id_serv, $id_usu) {
    	$sql = $this->get_queries()->remover_servico_usu();
    	$statement = $this->get_connection()->prepare($sql);
        
    	$statement->bindValue(':id_uni',$id_uni, PDO::PARAM_INT);
    	$statement->bindValue(':id_serv',$id_serv, PDO::PARAM_INT);
    	$statement->bindValue(':id_usu',$id_usu, PDO::PARAM_INT);

		$statement->execute();
    }
    
    /**
     * Remove todos os serviços de um usuário em uma unidade
     * 
     * @param $id_uni id da unidade
     * @param $id_usu id do usuário
     */
    public function remover_servicos_usu($id_uni,$id_usu){
    	$sql = $this->get_queries()->remover_servicos_usu();
    	$statement = $this->get_connection()->prepare($sql);

    	$statement->bindValue(':id_uni',$id_uni, PDO::PARAM_INT);
    	$statement->bindValue(':id_usu',$id_usu, PDO::PARAM_INT);

		$statement->execute();
		
    }
    public function adicionar_servico_usu($id_uni,$id_serv,$id_usu){
    	$sql = $this->get_queries()->adicionar_servico_usu();
    	$statement = $this->m_connection->prepare($sql);
    	$statement->bindValue(':id_uni',$id_uni, PDO::PARAM_INT);
    	$statement->bindValue(':id_serv',$id_serv, PDO::PARAM_INT);
    	$statement->bindValue(':id_usu',$id_usu, PDO::PARAM_INT);		
		$statement->execute();
    }
    public function alterar_usu($id_usu,$login_usu,$nm_usu,$ult_nm_usu){
    	$sql = $this->get_queries()->alterar_usu();
    	$statement = $this->m_connection->prepare($sql);
    	$statement->bindValue(':id_usu',$id_usu, PDO::PARAM_INT);
    	$statement->bindValue(':login_usu',$login_usu, PDO::PARAM_STR);
    	$statement->bindValue(':nm_usu',$nm_usu, PDO::PARAM_STR);
    	$statement->bindValue(':ult_nm_usu',$ult_nm_usu, PDO::PARAM_STR);		
		$statement->execute();
		
		return $statement->rowCount() === 1;
    }
    
    public function get_status($id_stat){
    	$sql = $this->get_queries()->get_status();
    	$statement = $this->m_connection->prepare($sql);
    	$statement->bindValue(':id_stat',$id_stat, PDO::PARAM_INT);
    	$statement->execute();
    	
    	$status = $this->to_array($statement);
		$status = $status[0][0];
		return $status;
    }
    
    public function insere_mensagem($id_uni,$id_usu){
    	$msg_global = $this->get_senha_msg_global();
    	    	
    	$sql = $this->get_queries()->insere_mensagem();

    	$statement = $this->m_connection->prepare($sql);
    	$statement->bindValue(':id_uni',$id_uni, PDO::PARAM_INT);
    	$statement->bindValue(':id_usu',$id_usu, PDO::PARAM_INT);
	   	$statement->bindValue(':msg_global',$msg_global, PDO::PARAM_STR);		
		$statement->execute();
    }

    public function get_estat_tempos_medios($ids_uni, $dt_min, $dt_max) {
    	$sql = $this->get_queries()->get_estat_tempos_medios();

    	$sql = str_replace(':ids_uni', DB::get_lista_int_in($ids_uni), $sql);
    	
    	$statement = $this->get_connection()->prepare($sql);

    	$statement->bindValue(':dt_min',$dt_min, PDO::PARAM_STR);
    	$statement->bindValue(':dt_max',$dt_max, PDO::PARAM_STR);
    	$statement->bindValue(':id_stat', Atendimento::ATENDIMENTO_ENCERRADO_CODIFICADO, PDO::PARAM_INT);
		$statement->execute();
		
		$array = $this->to_array($statement);
		
		return $array[0];
    }
    
	public function get_qtde_senhas_por_status($ids_uni, $dt_min, $dt_max) {
    	$sql = $this->get_queries()->get_qtde_senhas_por_status();
    	
    	$sql = str_replace(':ids_uni', DB::get_lista_int_in($ids_uni), $sql);
    	
    	$statement = new PDOStatement();
    	$statement = $this->m_connection->prepare($sql);
    	
    	$statement->bindValue(':dt_min', $dt_min, PDO::PARAM_STR);
    	$statement->bindValue(':dt_max', $dt_max, PDO::PARAM_STR);
		$statement->execute();
		
		return $this->to_array($statement);
    }
    
    public function get_estatistica_servico_mestres($ids_uni, $dt_min, $dt_max) {
    	$sql = $this->get_queries()->get_estatistica_servico_mestres();

    	$sql = str_replace(':ids_uni', DB::get_lista_int_in($ids_uni), $sql);
    	
    	$statement = $this->m_connection->prepare($sql);
    	
    	$statement->bindValue(':dt_min', $dt_min, PDO::PARAM_STR);
    	$statement->bindValue(':dt_max', $dt_max, PDO::PARAM_STR);
    	$statement->bindValue(':id_stat', Atendimento::ATENDIMENTO_ENCERRADO_CODIFICADO, PDO::PARAM_INT);
		$statement->execute();
		
		return $this->to_array($statement);
    }
    
    public function get_estatisticas_servicos_codificados($ids_uni, $dt_min, $dt_max) {
    	$sql = $this->get_queries()->get_estatistica_servico_codificados();
    	
    	$sql = str_replace(':ids_uni', DB::get_lista_int_in($ids_uni), $sql);
    	
    	$statement = $this->m_connection->prepare($sql);
    	
    	$statement->bindValue(':dt_min', $dt_min, PDO::PARAM_STR);
    	$statement->bindValue(':dt_max', $dt_max, PDO::PARAM_STR);
    	$statement->bindValue(':id_stat', Atendimento::ATENDIMENTO_ENCERRADO_CODIFICADO, PDO::PARAM_INT);
		$statement->execute();
		
		return $this->to_array($statement);
    }
    
    public function get_tempos_atend_por_usu($ids_uni, $dt_min, $dt_max) {
    	$sql = $this->get_queries()->get_tempos_atend_por_usu();
    	
    	$sql = str_replace(':ids_uni', DB::get_lista_int_in($ids_uni), $sql);
    	
    	$statement = $this->m_connection->prepare($sql);
    	
    	$statement->bindValue(':dt_min', $dt_min, PDO::PARAM_STR);
    	$statement->bindValue(':dt_max', $dt_max, PDO::PARAM_STR);
    	$statement->bindValue(':id_stat', Atendimento::ATENDIMENTO_ENCERRADO_CODIFICADO, PDO::PARAM_INT);
		$statement->execute();
		
		return $this->to_array($statement);
    }
    
    public function get_estat_atend_por_usu($ids_uni, $dt_min, $dt_max) {
    	$sql = $this->get_queries()->get_estat_atend_por_usu();
    	
    	$sql = str_replace(':ids_uni', DB::get_lista_int_in($ids_uni), $sql);
    	
    	$statement = $this->m_connection->prepare($sql);
    	
    	$statement->bindValue(':dt_min', $dt_min, PDO::PARAM_STR);
    	$statement->bindValue(':dt_max', $dt_max, PDO::PARAM_STR);
    	$statement->bindValue(':id_stat', Atendimento::ATENDIMENTO_ENCERRADO_CODIFICADO, PDO::PARAM_INT);
		$statement->execute();
		
		return $this->to_array($statement);
    }
    
	public function get_msg_status($id_uni){
		$sql = $this->get_queries()->get_msg_status();
		$statement = $this->m_connection->prepare($sql);
    	$statement->bindValue(':id_uni',$id_uni, PDO::PARAM_INT);
    	$statement->execute();
    	
    	$status = $this->to_array($statement);
		$status = $status[0][0];
		return $status;
	}
	
	public function set_msg_status($id_uni,$status_imp){
		$sql = $this->get_queries()->set_msg_status();
		$statement = $this->m_connection->prepare($sql);
    	$statement->bindValue(':id_uni',$id_uni, PDO::PARAM_INT);
    	$statement->bindValue(':status_imp',$status_imp, PDO::PARAM_INT);
    	$statement->execute();
	}
	
	public function get_nm_pri($id_pri){
		$sql = $this->get_queries()->get_nm_pri();
		$statement = $this->m_connection->prepare($sql);
    	$statement->bindValue(':id_pri',$id_pri, PDO::PARAM_INT);
    	$statement->execute();
    	
    	$nome = $this->to_array($statement);
		$nome = $nome[0][0];
		return $nome;
	}
	
	public function get_servicos_macro_nao_cadastrados_uni($id_uni) {
		$sql = $this->get_queries()->get_servicos_macro_nao_cadastrados_uni();
		$statement = $this->m_connection->prepare($sql);
    	$statement->bindValue(':id_uni', $id_uni, PDO::PARAM_INT);
    	$statement->execute();
    	
    	$servicos = $this->to_array($statement);
		return $servicos;
	}
	
	/**
	 * auto explicativo
	 * @param $id_usu, id do usuario
	 * @param $nova_senha, nova senha do usuario
	 * @param $senha_atual, senha atual passada para ser verificada
	 * @return bool TRUE somente se a senha($senha_atual) existe no banco e é igual ao parametro, false caso contrario
	 * @author robson
	 */
	public function alterar_senha_usu($id_usu,$nova_senha,$senha_atual){
		$sql = $this->get_queries()->alterar_senha_usu();
		$statement = $this->m_connection->prepare($sql);
    	$statement->bindValue(':id_usu', $id_usu, PDO::PARAM_INT);
    	$statement->bindValue(':nova_senha', $nova_senha, PDO::PARAM_STR);
    	$statement->bindValue(':senha_atual', $senha_atual, PDO::PARAM_STR);
    	$statement->execute();
    	
    	return $statement->rowCount() === 1;
	}
	
	public function alterar_senha_mod_usu ($id_usu,$nova_senha){
		$sql = $this->get_queries()-> alterar_senha_mod_usu();
		$statement = $this->m_connection->prepare($sql);
    	$statement->bindValue(':id_usu', $id_usu, PDO::PARAM_INT);
    	$statement->bindValue(':nova_senha', $nova_senha, PDO::PARAM_STR);
    	$statement->execute();
    	return $statement->rowCount() === 1;
	}
	
	public function set_status_usu($id_usu,$stat_usu){
		$sql = $this->get_queries()->set_status_usu();
		$statement = $this->m_connection->prepare($sql);
    	$statement->bindValue(':stat_usu', $stat_usu, PDO::PARAM_INT);
    	$statement->bindValue(':id_usu', $id_usu, PDO::PARAM_INT);
    	$statement->execute();
    	
    	return $statement->rowCount() === 1;
	}

    public static function get_lista_int_in($array) {
        if ($array == null) {
            $array = array("null");
        }
        else {
            // verificação de segurança (contra SQL Injection)
            // garante que todos os valores sao numeros
            // importante pois esses valores entrarão direto na consulta
            foreach ($array as $key => $value) {
                $array[$key] = (int) $value;
            }
        }

    	return implode(",", $array);
    }
    
    public function get_estat_atendimentos_encerradas($ids_uni, $dt_min, $dt_max){
		$sql = $this->get_queries()->get_estat_atendimentos_encerradas();
    	
		$sql = str_replace(':ids_uni', DB::get_lista_int_in($ids_uni), $sql);

    	$statement = $this->get_connection()->prepare($sql);
    	
    	$statement->bindValue(':dt_min', $dt_min, PDO::PARAM_STR);
    	$statement->bindValue(':dt_max', $dt_max, PDO::PARAM_STR);
		$statement->execute();
		
		return $this->to_array($statement);	    
    }

    public function get_ranking_unidades($ids_uni, $dt_min, $dt_max) {
    	$sql = $this->get_queries()->get_ranking_unidades();

    	$sql = str_replace(':ids_uni', DB::get_lista_int_in($ids_uni), $sql);

    	$statement = $this->get_connection()->prepare($sql);

    	$statement->bindValue(':dt_min',$dt_min, PDO::PARAM_STR);
    	$statement->bindValue(':dt_max',$dt_max, PDO::PARAM_STR);
    	$statement->bindValue(':id_stat', Atendimento::ATENDIMENTO_ENCERRADO_CODIFICADO, PDO::PARAM_INT);
		$statement->execute();

		return $this->to_array($statement);
    }

    public function get_estat_macro_serv_global($ids_uni, $dt_min, $dt_max) {
    	$sql = $this->get_queries()->get_estat_macro_serv_global();

    	$sql = str_replace(':ids_uni', DB::get_lista_int_in($ids_uni), $sql);

    	$statement = $this->get_connection()->prepare($sql);

    	$statement->bindValue(':dt_min',$dt_min, PDO::PARAM_STR);
    	$statement->bindValue(':dt_max',$dt_max, PDO::PARAM_STR);
    	$statement->bindValue(':id_stat', Atendimento::ATENDIMENTO_ENCERRADO_CODIFICADO, PDO::PARAM_INT);
		$statement->execute();

		return $this->to_array($statement);
    }

    public function get_estat_atendimentos_uni_global($ids_uni, $dt_min, $dt_max) {
        $sql = $this->get_queries()->get_estat_atendimentos_uni_global();

    	$sql = str_replace(':ids_uni', DB::get_lista_int_in($ids_uni), $sql);
        
    	$statement = $this->get_connection()->prepare($sql);

    	$statement->bindValue(':dt_min',$dt_min, PDO::PARAM_STR);
    	$statement->bindValue(':dt_max',$dt_max, PDO::PARAM_STR);
    	$statement->bindValue(':id_stat', Atendimento::ATENDIMENTO_ENCERRADO_CODIFICADO, PDO::PARAM_INT);
		$statement->execute();

		return $this->to_array($statement);
    }

	public function get_estat_atendimentos($ids_uni, $dt_min, $dt_max) {
		$sql = $this->get_queries()->get_estat_atendimentos();
    	$sql = str_replace(':ids_uni', DB::get_lista_int_in($ids_uni), $sql);
    	
    	$statement = $this->get_connection()->prepare($sql);
    	    	
    	$statement->bindValue(':dt_min', $dt_min, PDO::PARAM_STR);
    	$statement->bindValue(':dt_max', $dt_max, PDO::PARAM_STR);
		$statement->execute();
		
		return $this->to_array($statement);	    
    }

    public function get_tempos_medios_por_periodo($ids_uni, $dt_min, $dt_max) {
		$sql = $this->get_queries()->get_tempos_medios_por_periodo();
    	$sql = str_replace(':ids_uni', DB::get_lista_int_in($ids_uni), $sql);

    	$statement = $this->get_connection()->prepare($sql);

    	$statement->bindValue(':dt_min', $dt_min, PDO::PARAM_STR);
    	$statement->bindValue(':dt_max', $dt_max, PDO::PARAM_STR);
        $statement->bindValue(':id_stat', Atendimento::ATENDIMENTO_ENCERRADO_CODIFICADO, PDO::PARAM_INT);
		$statement->execute();
        
		return $this->to_array($statement);
    }
}

?>