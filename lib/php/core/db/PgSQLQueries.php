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
 * Classe PgSQLQueries 
 * contem as queries especificas do PgSQL
 * 
 * Implementacao do DBQueries para utilizar o Banco de Dados PgSQL
 *
 */
class PgSQLQueries implements DBQueries {
	
	private static $instance;
	
	// Singleton
	public static function getInstance() {
		if (PgSQLQueries::$instance === null)
			PgSQLQueries::$instance = new PgSQLQueries();
		return PgSQLQueries::$instance;
	}

    public function has_mod_global_access() {
        return "SELECT *
                FROM usu_grup_cargo ugc
                INNER JOIN cargos_mod_perm cmp
                    ON (ugc.id_cargo = cmp.id_cargo)
                WHERE ugc.id_usu = :id_usu
                    AND cmp.id_mod = :id_mod";
    }

    public function get_variaveis_globais() {
        return "SELECT *
                FROM variaveis_sistema";
    }

    public function salvar_variavel_global() {
        return "SELECT sp_salvar_variavel_sistema(:chave, :valor)";
    }
	
	public function get_unidades() {
    	return "SELECT id_uni, unidades.id_tema, cod_uni, nm_uni, stat_uni,
    					nm_tema, desc_tema, autor_tema, dir_tema, 
    					g.id_grupo, g.nm_grupo, g.desc_grupo 
    			FROM unidades
    			INNER JOIN temas
    				ON temas.id_tema = unidades.id_tema 
    			INNER JOIN grupos_aninhados g
    				ON g.id_grupo = unidades.id_grupo
    			ORDER BY nm_uni ASC";
	}
	
	public function get_unidade() {
		return "SELECT id_uni, unidades.id_tema, cod_uni, nm_uni, stat_uni,
    					nm_tema, desc_tema, autor_tema, dir_tema, 
    					grupos_aninhados.id_grupo, grupos_aninhados.nm_grupo, grupos_aninhados.desc_grupo 
				FROM unidades 
				INNER JOIN temas
    				ON temas.id_tema = unidades.id_tema
    			INNER JOIN grupos_aninhados
    				ON grupos_aninhados.id_grupo = unidades.id_grupo
				WHERE id_uni = :id_uni";
	}
	
	public function get_unidade_by_codigo() {
		return "SELECT id_uni, unidades.id_tema, cod_uni, nm_uni, stat_uni,
    					nm_tema, desc_tema, autor_tema, dir_tema, 
    					grupos_aninhados.id_grupo, grupos_aninhados.nm_grupo, grupos_aninhados.desc_grupo 
				FROM unidades 
				INNER JOIN temas
    				ON temas.id_tema = unidades.id_tema
    			INNER JOIN grupos_aninhados
    				ON grupos_aninhados.id_grupo = unidades.id_grupo
				WHERE CAST(cod_uni as character varying) LIKE :cod_uni||'%'
				";
	}
	public function get_unidade_by_name() {
		return "SELECT id_uni, unidades.id_tema, cod_uni, nm_uni, stat_uni,
    					nm_tema, desc_tema, autor_tema, dir_tema, 
    					g.id_grupo, g.nm_grupo, g.desc_grupo 
				FROM unidades 
				INNER JOIN temas
    				ON temas.id_tema = unidades.id_tema
    			INNER JOIN grupos_aninhados g
    				ON g.id_grupo = unidades.id_grupo
				WHERE nm_uni ILIKE :nm_uni 
				";
	}
	
	public function get_unidades_by_usuario() {
		return "SELECT id_uni, unidades.id_tema, cod_uni, nm_uni, stat_uni,
    					nm_tema, desc_tema, autor_tema, dir_tema, 
    					grupos_aninhados.id_grupo, grupos_aninhados.nm_grupo, grupos_aninhados.desc_grupo 
				FROM unidades 
				INNER JOIN temas
    				ON temas.id_tema = unidades.id_tema
    			INNER JOIN grupos_aninhados
    				ON grupos_aninhados.id_grupo = unidades.id_grupo
				WHERE grupos_aninhados.id_grupo IN (
					SELECT folhas.id_grupo
					FROM grupos_aninhados pai
					INNER JOIN grupos_aninhados folhas ON folhas.esquerda >= pai.esquerda
						AND folhas.direita <= pai.direita
					WHERE pai.id_grupo IN (
						SELECT ugc.id_grupo FROM usu_grup_cargo ugc WHERE id_usu = :id_usu
					)
 				)";
	}

	public function get_unidades_by_grupos_mod_usu(){
	    return "SELECT id_uni, unidades.id_tema, cod_uni, nm_uni, stat_uni,
    				nm_tema, desc_tema, autor_tema, dir_tema
			FROM unidades
			INNER JOIN temas
    			ON temas.id_tema = unidades.id_tema
                WHERE unidades.id_grupo IN (
                    SELECT folhas.id_grupo
				FROM grupos_aninhados pai
				INNER JOIN grupos_aninhados folhas ON folhas.esquerda >= pai.esquerda
					AND folhas.direita <= pai.direita
				WHERE pai.id_grupo IN (
					:ids_grupo
				)
              )";
	}
	
    public function get_unidades_by_grupos() {
        return "SELECT id_uni, unidades.id_tema, cod_uni, nm_uni, stat_uni,
                    nm_tema, desc_tema, autor_tema, dir_tema
                FROM unidades
                INNER JOIN temas
                ON temas.id_tema = unidades.id_tema
                WHERE unidades.id_grupo IN
                (
                    SELECT filho.id_grupo
                    FROM grupos_aninhados filho
                    INNER JOIN grupos_aninhados pai
                    ON (filho.esquerda >= pai.esquerda AND filho.direita <= pai.direita)
                    WHERE filho.esquerda >=
                    (
                        SELECT esquerda
                        FROM grupos_aninhados
                        WHERE id_grupo = :id_grupo_1
                    )
                    AND filho.direita <=
                    (
                        SELECT direita
                        FROM grupos_aninhados
                        WHERE id_grupo = :id_grupo_2
                    )
                    AND pai.id_grupo IN
                    (
                        SELECT ugc.id_grupo
                        FROM usu_grup_cargo ugc
                        INNER JOIN cargos_mod_perm cmp
                        ON ugc.id_cargo = cmp.id_cargo
                        WHERE cmp.id_mod = :id_mod
                        AND cmp.permissao = 3
                        AND id_usu = :id_usu
                    )
                )";
    }
    
    public function get_unidade_by_grupo(){
    	return "SELECT id_uni, unidades.id_tema, cod_uni, nm_uni, stat_uni,
				nm_tema, desc_tema, autor_tema, dir_tema
				FROM unidades
				INNER JOIN temas
					ON temas.id_tema = unidades.id_tema
				WHERE unidades.id_grupo IN(SELECT id_grupo
					FROM grupos_aninhados
					WHERE id_grupo = :id_grupo)";
    }

	public function get_status_uni(){
		return "SELECT stat_uni 
				FROM unidades 
				WHERE id_uni = :id_uni";
	}
	
	public function set_status_uni(){
		return "UPDATE unidades
				SET stat_uni = :stat_uni 
				WHERE id_uni = :id_uni";
	}

	public function get_temas() {
	    return "SELECT id_tema, nm_tema, desc_tema, autor_tema, dir_tema 
			    FROM temas 
			    ORDER BY nm_tema ASC";
	}

	public function get_modulos() {
	    return "SELECT * 
		        FROM modulos 
		        WHERE stat_mod IN (:stats_mod)
                    AND tipo_mod IN (:tipos_mod)
		        ORDER BY nm_mod ASC";
	}

	public function get_modulo() {
	    return "SELECT * 
		        FROM modulos  
		        WHERE chave_mod = :chave
		        AND stat_mod = :status";
	}

	public function is_instalado() { 
	    return "SELECT * 
		        FROM modulos 
		        WHERE chave_mod = :chave 
		        AND stat_mod = 1";
	}
	
	/*
	    Queries de Login
	*/
	public function exists_usuario() { 
		return "SELECT login_usu 
				FROM usuarios 
				WHERE login_usu = :login_usu";
	}
	
	public function exists_modulo() { 
		return "SELECT id_mod 
				FROM modulos 
				WHERE chave_mod = :chave_mod";
	}
	
	public function get_usuario_login() {
	    return "SELECT login_usu FROM usuarios WHERE login_usu= :login_usu AND senha_usu = md5(:pass)";
    }
    
    public function get_status_usu(){
    	return "SELECT stat_usu FROM usuarios WHERE login_usu= :login_usu AND senha_usu = md5(:pass)";
    }
    
    /* DELETAR
    public function has_acesso() {
    	return "SELECT login_usu 
                FROM usuarios u 
                INNER JOIN usu_grup ug
                    ON u.id_usu=ug.id_usu 
                INNER JOIN mod_grup mg
                    ON mg.id_grup=ug.id_grup		             
                WHERE login_usu = :user 
                    AND senha_usu=sha1(:pass)
                    AND mg.id_mod = :id_mod";
	}
	*/
    
	public function get_lotacao_valida() {
		return "SELECT * FROM sp_get_lotacao_valida(:id_usu, :id_grupo)";
	}
	
	public function get_usuario() {
	    return "SELECT * 
		        FROM usuarios 		            
    		    WHERE login_usu = :login
    		    ";
	}
	
	public function get_usuario_by_mat(){
		return "SELECT * 
		        FROM usuarios 		            
    		    WHERE CAST(login_usu as character varying) LIKE :login||'%'
    		    AND	id_usu IN 
				(	SELECT id_usu
				 	FROM usu_grup_cargo
			 	 	WHERE id_cargo IN 
					(	SELECT id_cargo
				     	FROM cargos_aninhados 
				     	WHERE esquerda >=
				     	(	SELECT esquerda
				      		FROM cargos_aninhados
				      		WHERE id_cargo = :id_cargo_1
		        		)
		        		AND direita <=
		        		(
		        			SELECT direita
			        		FROM cargos_aninhados
			        		WHERE id_cargo = :id_cargo_2
		        		)
					)
					AND id_grupo IN
					(	SELECT id_grupo
		        		FROM grupos_aninhados 
		        		WHERE esquerda >=
		        		(
		        			SELECT esquerda
			        		FROM grupos_aninhados
			        		WHERE id_grupo = :id_grupo_1
		        		)
		        		AND direita <=
		        		(
			        		SELECT direita
			        		FROM grupos_aninhados
			        		WHERE id_grupo = :id_grupo_2
		        		)
					)
				)
    		    ORDER BY login_usu ASC
    		    ";
		
	}
	
	public function get_usuario_by_id() {
	    return "SELECT * 
		        FROM usuarios u 		            
    		    WHERE id_usu = :id_usu
    		    ";
	}
	
	public function get_usuarios_by_name() {
		return "SELECT *
				FROM usuarios u
				WHERE nm_usu ILIKE :nm_usu AND
				id_usu IN 
				(	SELECT id_usu
				 	FROM usu_grup_cargo
			 	 	WHERE id_cargo IN 
					(	SELECT id_cargo
				     	FROM cargos_aninhados 
				     	WHERE esquerda >=
				     	(	SELECT esquerda
				      		FROM cargos_aninhados
				      		WHERE id_cargo = :id_cargo_1
		        		)
		        		AND direita <=
		        		(
		        			SELECT direita
			        		FROM cargos_aninhados
			        		WHERE id_cargo = :id_cargo_2
		        		)
					)
					AND id_grupo IN
					(	SELECT id_grupo
		        		FROM grupos_aninhados 
		        		WHERE esquerda >=
		        		(
		        			SELECT esquerda
			        		FROM grupos_aninhados
			        		WHERE id_grupo = :id_grupo_1
		        		)
		        		AND direita <=
		        		(
			        		SELECT direita
			        		FROM grupos_aninhados
			        		WHERE id_grupo = :id_grupo_2
		        		)
					)
				)
				ORDER BY nm_usu ASC";
	}

	public function get_usuarios() {
	    return "SELECT * 
		        FROM usuarios AS usu 
				WHERE usu.stat_usu IN (:status)
		        ORDER BY nm_usu ASC";
	}
	
	public function get_todos_usuarios(){
		return "SELECT * 
		        FROM usuarios AS usu 
				";
	}
	
	public function get_usuarios_grupos_by_usuario() {
	    return "SELECT *
                FROM usuarios
                WHERE login_usu LIKE :termo_login
                    AND nm_usu LIKE :termo_nome
                    AND id_usu IN
                    (
                        SELECT id_usu
                        FROM usu_grup_cargo ugc_usu
                        INNER JOIN cargos_aninhados ca
                            ON (ugc_usu.id_cargo = ca.id_cargo)
                        WHERE ROW(ugc_usu.id_grupo, ugc_usu.id_cargo) IN
                        (
                            SELECT filho.id_grupo, adm_ca_filho.id_cargo
                            FROM grupos_aninhados filho
                            INNER JOIN grupos_aninhados pai
                                ON (filho.esquerda >= pai.esquerda AND filho.direita <= pai.direita)
                            INNER JOIN usu_grup_cargo ugc
                                ON (ugc.id_grupo = pai.id_grupo AND ugc.id_usu = :id_usu_1)
                            INNER JOIN cargos_aninhados adm_ca_pai
                                ON (ugc.id_cargo = adm_ca_pai.id_cargo)
                            INNER JOIN cargos_aninhados adm_ca_filho
                                ON (adm_ca_filho.esquerda >= adm_ca_pai.esquerda AND adm_ca_filho.direita <= adm_ca_pai.direita)
                            WHERE filho.esquerda >=
                            (
                                SELECT esquerda
                                FROM grupos_aninhados
                                WHERE id_grupo = :id_grupo_1
                            )
                            AND filho.direita <=
                            (
                                SELECT direita
                                FROM grupos_aninhados
                                WHERE id_grupo = :id_grupo_2
                            )
                            AND pai.id_grupo IN
                            (
                                SELECT ugc.id_grupo
                                FROM usu_grup_cargo ugc
                                INNER JOIN cargos_mod_perm cmp
                                ON ugc.id_cargo = cmp.id_cargo
                                WHERE cmp.id_mod = :id_mod
                                AND cmp.permissao = 3
                                AND id_usu = :id_usu_2
                            )
                        )
                )ORDER BY login_usu";
	}

    public function get_lotacoes_visiveis() {
        return "SELECT *
                FROM usu_grup_cargo ugc_usu
                INNER JOIN cargos_aninhados ca
                    ON (ugc_usu.id_cargo = ca.id_cargo)
                WHERE id_usu = :id_usu
                AND ROW(ugc_usu.id_grupo, ugc_usu.id_cargo) IN
                (
                    SELECT filho.id_grupo, adm_ca_filho.id_cargo
                    FROM grupos_aninhados filho
                    INNER JOIN grupos_aninhados pai
                        ON (filho.esquerda >= pai.esquerda AND filho.direita <= pai.direita)
                    INNER JOIN usu_grup_cargo ugc
                        ON (ugc.id_grupo = pai.id_grupo AND ugc.id_usu = :id_usu_admin_1)
                    INNER JOIN cargos_aninhados adm_ca_pai
                        ON (ugc.id_cargo = adm_ca_pai.id_cargo)
                    INNER JOIN cargos_aninhados adm_ca_filho
                        ON (adm_ca_filho.esquerda >= adm_ca_pai.esquerda AND adm_ca_filho.direita <= adm_ca_pai.direita)
                    WHERE filho.esquerda >=
                    (
                        SELECT esquerda
                        FROM grupos_aninhados
                        WHERE id_grupo = :id_grupo_1
                    )
                    AND filho.direita <=
                    (
                        SELECT direita
                        FROM grupos_aninhados
                        WHERE id_grupo = :id_grupo_2
                    )
                    AND pai.id_grupo IN
                    (
                        SELECT ugc.id_grupo
                        FROM usu_grup_cargo ugc
                        INNER JOIN cargos_mod_perm cmp
                            ON ugc.id_cargo = cmp.id_cargo
                        WHERE cmp.id_mod = :id_mod
                            AND cmp.permissao = 3
                            AND id_usu = :id_usu_admin_2
                    )
                )";
    }

    public function get_unidades_visiveis() {
        return "SELECT *
                FROM unidades u
                INNER JOIN temas t
    				ON t.id_tema = u.id_tema
    			INNER JOIN grupos_aninhados g
    				ON g.id_grupo = u.id_grupo
                WHERE u.id_uni IN
                (
                    SELECT id_uni
                    FROM unidades
                    INNER JOIN grupos_aninhados
                        ON grupos_aninhados.id_grupo = unidades.id_grupo
                    WHERE grupos_aninhados.id_grupo IN
                    (
                        SELECT folhas.id_grupo
                        FROM grupos_aninhados pai
                        INNER JOIN grupos_aninhados folhas ON folhas.esquerda >= pai.esquerda
                            AND folhas.direita <= pai.direita
                        WHERE pai.id_grupo IN
                        (
                            SELECT ugc.id_grupo FROM usu_grup_cargo ugc WHERE id_usu = :id_usu
                        )
                    )
                )
                AND u.id_grupo IN
                (
                    SELECT DISTINCT filho.id_grupo
                    FROM grupos_aninhados pai, grupos_aninhados filho
                    WHERE filho.esquerda >= pai.esquerda
                        AND filho.esquerda <= pai.direita
                        AND filho.esquerda >=
                        (
                            SELECT esquerda
                            FROM grupos_aninhados
                            WHERE id_grupo = :id_grupo_1
                        )
                        AND filho.direita <=
                        (
                            SELECT direita
                            FROM grupos_aninhados
                            WHERE id_grupo = :id_grupo_2
                        )
                        AND pai.id_grupo IN
                        (
                            SELECT ugc.id_grupo
                            FROM usu_grup_cargo ugc
                            INNER JOIN cargos_mod_perm cmp
                                ON ugc.id_cargo = cmp.id_cargo
                            WHERE cmp.id_mod = :id_mod
                                AND cmp.permissao = 3
                                AND id_usu = :id_usu_admin
                        )
                )";
    }

	public function get_usuario_servicos_unidade() {
		return "SELECT usus.id_serv
		        FROM usu_serv usus
		        INNER JOIN uni_serv unis
		        	ON (usus.id_serv = unis.id_serv AND usus.id_uni = unis.id_uni)
                INNER JOIN servicos s
                    ON s.id_serv = unis.id_serv
		        WHERE usus.id_usu = :id_user
		            AND unis.id_uni = :id_uni
                    AND s.stat_serv IN (:status)
					AND unis.stat_serv IN (:status)";
	}
	
	public function inserir_usuario() {
		return "INSERT INTO usuarios
				( 
					login_usu, nm_usu, 
					ult_nm_usu, senha_usu, stat_usu 
				) 
				VALUES 
				(
					:login_usu, :nm_usu, 
					:ult_nm_usu, md5(:senha_usu), 1
				)";
	}
	
	public function atualizar_usuario() {
		return "UPDATE usuarios
				SET login_usu = :login_usu,
					nm_usu = :nm_usu,
					ult_nm_usu = :ult_nm_usu
				WHERE id_usu = :id_usu";
	}
	public function inserir_unidade() {
		return "INSERT INTO unidades
				( 
					id_grupo , id_tema,cod_uni , nm_uni 
				) 
				VALUES 
				(
					:id_grupo, 1, :cod_uni, :nm_uni
				)";
	}
	
	public function atualizar_unidade() {
		return "UPDATE unidades
				SET id_grupo = :id_grupo, 
					cod_uni = :cod_uni,
					nm_uni = :nm_uni
				WHERE id_uni = :id_uni";
	}
	
	public function get_grupos() {
	    return "SELECT id_grupo, nm_grupo, desc_grupo, esquerda
		        FROM grupos_aninhados 
		        ORDER BY nm_grupo ASC";
	}
	
	public function get_arvore_grupos() {
	    return "SELECT no.*, pai.id_grupo as id_grupo_pai
                FROM grupos_aninhados pai
                INNER JOIN
                    (
                        SELECT no.id_grupo, MAX(pai.esquerda) as esquerda
                        FROM grupos_aninhados no
                        INNER JOIN grupos_aninhados pai
                            ON no.esquerda > pai.esquerda  AND no.esquerda < pai.direita
                        GROUP BY no.id_grupo
                    ) SQ
                    ON (pai.esquerda = SQ.esquerda)
                RIGHT OUTER JOIN grupos_aninhados no
                    ON (no.id_grupo = SQ.id_grupo)
                ORDER BY no.esquerda";
	}
	
	public function get_sub_grupos() {
		return "SELECT id_grupo, nm_grupo, desc_grupo, esquerda
		        FROM grupos_aninhados 
		        WHERE esquerda >
		        	(
		        		SELECT esquerda
		        		FROM grupos_aninhados
		        		WHERE id_grupo = :id_grupo_1 
		        	)
		        	AND direita <
		        	(
		        		SELECT direita
		        		FROM grupos_aninhados
		        		WHERE id_grupo = :id_grupo_2 
		        	)
		        ORDER BY nm_grupo ASC";
	}
	
	public function get_grupos_candidatos_pai() {
		return "SELECT id_grupo, nm_grupo, desc_grupo
		        FROM grupos_aninhados g
		        WHERE esquerda <
			        (
			        	SELECT esquerda
			        	FROM grupos_aninhados
			        	WHERE id_grupo = :id_grupo_1
			        )
			        OR direita > 
			        (
			        	SELECT direita
			        	FROM grupos_aninhados
			        	WHERE id_grupo = :id_grupo_2
			        )
		        ORDER BY nm_grupo ASC";
	}
	
	public function get_grupos_folha_disponiveis() {
		return "SELECT DISTINCT (ga.id_grupo), nm_grupo, desc_grupo 
		        FROM grupos_aninhados AS ga
				WHERE ga.direita = ga.esquerda + 1 AND
				ga.id_grupo NOT IN (SELECT id_grupo 
									FROM unidades)
				ORDER BY nm_grupo ASC";
	}
	
	public function get_lotacoes_editaveis() {
		return "SELECT ga.*, ugc.id_cargo
				FROM usu_grup_cargo ugc
                INNER JOIN grupos_aninhados ga
                    ON (ga.id_grupo = ugc.id_grupo)
				WHERE ugc.id_usu = :id_usu
                    AND id_cargo IN
                    (
                        SELECT id_cargo
                        FROM cargos_mod_perm
                        WHERE id_mod = :id_mod
                    )
                    AND ga.id_grupo IN
                    (
                        SELECT id_grupo
                        FROM grupos_aninhados
                        WHERE esquerda >=
                        (
                            SELECT esquerda
                            FROM grupos_aninhados
                            WHERE id_grupo = :id_grupo_1
                        )
                        AND direita <=
                        (
                            SELECT direita
                            FROM grupos_aninhados
                            WHERE id_grupo = :id_grupo_2
                        )
                    )
                ORDER BY ga.esquerda ASC";
	}
	
	public function get_grupos_by_permissao_usuario() {
		return "SELECT DISTINCT no.id_grupo, no.nm_grupo, no.desc_grupo, no.esquerda
				FROM grupos_aninhados AS no,
				grupos_aninhados AS pai
				WHERE no.esquerda >= pai.esquerda
				    AND no.esquerda <= pai.direita
				    AND pai.id_grupo IN (
				        SELECT ugc.id_grupo
				        FROM usu_grup_cargo ugc
				        INNER JOIN cargos_mod_perm cmp
				            ON (cmp.id_cargo = ugc.id_cargo)
				        WHERE cmp.id_mod = :id_mod
				            AND cmp.permissao = 3
				            AND id_usu = :id_usu
				    )
				ORDER BY no.esquerda";
	}
	
	public function get_grupo_by_id() {
		return "SELECT id_grupo, nm_grupo, desc_grupo, esquerda
				FROM grupos_aninhados
				WHERE id_grupo = :id_grupo
				";
	}
	
	public function get_grupo_pai_by_id() {
		return "SELECT pai.id_grupo, pai.nm_grupo, pai.desc_grupo
				FROM grupos_aninhados AS no,
				grupos_aninhados AS pai
				WHERE no.esquerda > pai.esquerda
				    AND no.direita < pai.direita
				AND no.id_grupo = :id_grupo_filho
				ORDER BY pai.esquerda DESC
				";
	}
	
	public function salvar_session_id() {
		return "SELECT sp_salvar_session_id(:id_usu, :session_id)";
	}

    public function set_session_status() {
		return "UPDATE usu_session
                SET stat_session = :stat_session
                WHERE id_usu = :id_usu";
	}

	public function verificar_session_id() {
		return "SELECT stat_session
				FROM usu_session
				WHERE id_usu = :id_usu
					AND session_id = :session_id";
	}
	
	public function get_cargos() {
		return "SELECT id_cargo, nm_cargo, desc_cargo, esquerda
				FROM cargos_aninhados
				ORDER BY nm_cargo";
	}

    public function get_arvore_cargos() {
	    return "SELECT no.*, pai.id_cargo as id_cargo_pai
                FROM cargos_aninhados pai
                INNER JOIN
                    (
                        SELECT no.id_cargo, MAX(pai.esquerda) as esquerda
                        FROM cargos_aninhados no
                        INNER JOIN cargos_aninhados pai
                            ON no.esquerda > pai.esquerda  AND no.esquerda < pai.direita
                        GROUP BY no.id_cargo
                    ) SQ
                    ON (pai.esquerda = SQ.esquerda)
                RIGHT OUTER JOIN cargos_aninhados no
                    ON (no.id_cargo = SQ.id_cargo)
                ORDER BY no.esquerda";
	}
	
	public function get_cargos_candidatos_pai() {
		return "SELECT id_cargo, nm_cargo, desc_cargo, esquerda
		        FROM cargos_aninhados g
		        WHERE esquerda <
			        (
			        	SELECT esquerda
			        	FROM cargos_aninhados
			        	WHERE id_cargo = :id_cargo_1
			        )
			        OR direita > 
			        (
			        	SELECT direita
			        	FROM cargos_aninhados
			        	WHERE id_cargo = :id_cargo_2
			        )
		        ORDER BY nm_cargo ASC";
	}
	
	public function get_cargo_pai_by_id() {
		return "SELECT pai.id_cargo, pai.nm_cargo, pai.desc_cargo
				FROM cargos_aninhados AS no,
				cargos_aninhados AS pai
				WHERE no.esquerda > pai.esquerda
				    AND no.direita < pai.direita
				AND no.id_cargo = :id_cargo_filho
				ORDER BY pai.esquerda DESC
				";
	}
	
	public function get_cargo() {
		return "SELECT id_cargo, nm_cargo, desc_cargo, esquerda
				FROM cargos_aninhados
				WHERE id_cargo = :id_cargo
				";
	}
	
	public function get_sub_cargos() {
		return "SELECT id_cargo, nm_cargo, desc_cargo, esquerda
		        FROM cargos_aninhados 
		        WHERE esquerda >=
		        	(
		        		SELECT esquerda
		        		FROM cargos_aninhados
		        		WHERE id_cargo = :id_cargo_1 
		        	)
		        	AND direita <=
		        	(
		        		SELECT direita
		        		FROM cargos_aninhados
		        		WHERE id_cargo = :id_cargo_2 
		        	)
		        ORDER BY nm_cargo ASC";
	}
	
	public function get_permissoes_cargo() {
		return "SELECT cmp.id_mod, cmp.permissao
				FROM modulos m
				INNER JOIN cargos_mod_perm cmp
					ON m.id_mod = cmp.id_mod
				WHERE cmp.id_cargo = :id_cargo";
	}
	
	public function inserir_cargo() {
		return "SELECT sp_inserir_cargo(:id_cargo_pai, :nm_cargo, :desc_cargo)";
	}
	
	public function atualizar_cargo() {
		return "SELECT sp_atualizar_cargo(:id_cargo, :id_cargo_pai, :nm_cargo, :desc_cargo)";
	}
	
	public function remover_cargo() {
		return "SELECT sp_remover_cargo_cascata(:id_cargo)";
	}
	
	public function remover_servico_uni() {
		return "DELETE FROM uni_serv
				WHERE id_uni = :id_uni
					AND id_serv = :id_serv";
	}
	
	public function inserir_permissao_modulo_cargo() {
		return "INSERT INTO cargos_mod_perm
				( 
					id_cargo , id_mod , permissao
				) 
				VALUES
				(
					:id_cargo, :id_mod, :permissao
				)";
	}
	
	public function remover_permissao_modulo_cargo() {
		return "DELETE FROM cargos_mod_perm 
				WHERE id_cargo = :id_cargo 
					AND id_mod = :id_mod";
	}
	
	public function remover_permissoes_cargo() {
		return "DELETE FROM cargos_mod_perm 
				WHERE id_cargo = :id_cargo";
	}
	
	public function inserir_servico_uni(){
		return "INSERT INTO uni_serv
				VALUES
				(:id_uni, :id_serv, :id_loc, :nome_serv, :sigla, :status_serv)";
	}
	
	public function alterar_servico_uni(){
		return "UPDATE uni_serv
				SET 
					nm_serv = :nome_serv,
					sigla_serv = :sigla,
					stat_serv = :status_serv					  
				WHERE
					id_uni = :id_uni
					AND id_serv = :id_serv";
	}
	
	public function get_lotacao() {
		return "SELECT c.id_cargo, c.nm_cargo, c.desc_cargo
				FROM cargos_aninhados c
				WHERE id_cargo IN (
				    SELECT id_cargo
				    FROM usu_grup_cargo ugc
				    WHERE ugc.id_usu = :id_usu
				        AND ugc.id_grupo = :id_grupo
				)
				";
	}
	
	public function inserir_lotacao() {
		return "INSERT INTO usu_grup_cargo 
				VALUES (:id_usu, :id_grupo, :id_cargo)";
	}
	
	public function atualizar_lotacao() {
		return "UPDATE usu_grup_cargo 
				SET id_cargo = :id_cargo,  
					id_grupo = :id_grupo_novo
				WHERE id_usu = :id_usu 
					AND id_grupo = :id_grupo";
	}
	
	public function remover_lotacao() {
		return "DELETE FROM usu_grup_cargo 
				WHERE id_usu = :id_usu 
					AND id_grupo = :id_grupo";
	}

    public function remover_lotacoes() {
		return "DELETE FROM usu_grup_cargo
				WHERE id_usu = :id_usu";
	}

	/* DELETAR
	public function get_grupos_unidade() {
		return "SELECT g.id_grup, g.nm_grup 
		        FROM grupos g 
		        INNER JOIN uni_grup u  
		        	ON g.id_grup=u.id_grup
		        WHERE u.id_uni = :id_uni 
get_unidades_by_grupos		        ";
	}*/
	
	public function criar_grupo() {
	    return "SELECT sp_inserir_grupo(:id_grupo_pai, :nm_grupo, :desc_grupo)";
	}
	
	public function atualizar_grupo() {
		return "SELECT sp_atualizar_grupo(:id_grupo, :id_grupo_pai, :nm_grupo, :desc_grupo)";
	}
	
	public function remover_grupo() {
		return "SELECT sp_remover_grupo_cascata(:id_grupo)";
	}

	public function remover_senha_uni_msg(){
		return "DELETE FROM senha_uni_msg WHERE id_uni= :id_uni";
	}
	
	public function remover_unidade() {
		return "DELETE FROM unidades WHERE id_uni= :id_uni";
	}
	
	public function get_menu() {
	    return "SELECT id_menu, e.id_mod, nm_menu, desc_menu, lnk_menu, ord_menu 
		        FROM menus e
		        INNER JOIN modulos o
		            ON e.id_mod=o.id_mod
		        WHERE o.chave_mod= :chave_mod
		        ORDER BY ord_menu ASC";
	}
	
	public function get_menu_link() {
	    return "SELECT lnk_menu 
		        FROM menus e		            
		        WHERE id_menu = :id_menu";
	}

	public function get_total_fila() { 
	    return "SELECT COUNT(id_atend)
	    		FROM atendimentos
	    		WHERE id_stat = 1
                    AND id_uni = :id_uni";
	}
    
    public function get_ultima_senha_lock() {
	    return "SELECT num_senha, sigla_serv, id_atend
                FROM atendimentos a
                LEFT JOIN uni_serv u
                    ON a.id_serv = u.id_serv
                        AND a.id_uni = u.id_uni
                WHERE id_stat IN (:ids_stat)
                    AND a.id_uni = :id_uni
                ORDER BY num_senha DESC
                LIMIT 1
                FOR UPDATE OF a NOWAIT";
	}

	public function get_ultima_senha() {
	    return "SELECT num_senha, sigla_serv, id_atend
                FROM atendimentos a 
                LEFT JOIN uni_serv u 
                    ON a.id_serv = u.id_serv
                        AND a.id_uni = u.id_uni
                WHERE id_stat IN (:ids_stat)
                    AND a.id_uni = :id_uni
                ORDER BY num_senha DESC
                LIMIT 1
                ";
	}

	public function get_proxima_senha_numero() {
	    return "SELECT senha 
	    		FROM atendimentos 
	    		ORDER BY senha DESC 
	    		";
	}

    public function reiniciar_senhas_unidade() {
        return "SELECT * FROM sp_acumular_atendimentos_unidade(:id_uni, :dt_max)";
    }

    public function reiniciar_senhas_global() {
        return "SELECT * FROM sp_acumular_atendimentos(:dt_max)";
    }

	public function get_servico() {
	    return "SELECT * 
                FROM servicos s  
                WHERE s.id_serv = :id_serv";
	}
	
	public function get_servico_current_uni() {
	    return "SELECT u.id_serv, id_macro, u.nm_serv, sigla_serv, desc_serv 
                FROM servicos s 
                INNER JOIN uni_serv u 
                    ON s.id_serv = u.id_serv 
				WHERE s.id_serv = :id_serv
					AND u.id_uni = :id_uni";
	}
	
	public function inserir_servico() {
		return "INSERT INTO servicos
				(id_macro, nm_serv, desc_serv, stat_serv)
				VALUES
				(:id_macro, :nm_serv, :desc_serv, :stat_serv)";
	}
	
	public function atualizar_servico() {
		return "UPDATE servicos
				SET id_macro = :id_macro,
					nm_serv = :nm_serv,
					desc_serv = :desc_serv,
                    stat_serv = :stat_serv
				WHERE id_serv = :id_serv";
	}
	
	public function atualizar_sub_servico(){
		return "UPDATE servicos
				SET stat_serv = :stat_serv
				WHERE id_macro = :id_serv";
	}

	public function atualiza_stat_uni_serv(){
		return "UPDATE uni_serv 
				SET stat_serv = :stat_serv
				WHERE id_serv IN (SELECT id_serv 
								  FROM servicos  
								  WHERE id_macro = :id_serv 
								  	OR id_serv = :id_serv)
				";
	}
	
	public function get_stat_serv(){
		return "SELECT stat_serv
				FROM servicos
				WHERE id_serv = :id_serv";
	} 
	
	public function remover_servico() {
		return "DELETE FROM servicos
				WHERE id_serv = :id_serv";
	}
	
	public function get_servicos() {
	    return "SELECT * 
	    		FROM servicos s
                WHERE s.stat_serv IN (:stats_serv)
	    		ORDER BY desc_serv ASC";
	}
	
	public function get_servicos_unidade() {
	    return "SELECT  us.id_serv, us.nm_serv, us.sigla_serv, us.stat_serv, s.desc_serv
		        FROM uni_serv us 
		        INNER JOIN servicos s 
                    ON us.id_serv = s.id_serv
		        WHERE us.id_uni = :id_uni
		         	AND us.stat_serv IN (:stats_serv)
                    AND s.stat_serv IN (:stats_serv)
		        ORDER BY us.nm_serv ASC";
	}

	public function get_servicos_mestre() { 
	    return "SELECT * 
		        FROM servicos s
		        WHERE s.id_macro IS NULL
                    AND s.stat_serv IN (:stats_serv)
		        ORDER BY s.nm_serv ASC";
	}
	
	public function get_servicos_mestre_unidade() { 
	    return "SELECT * 
		        FROM uni_serv us
		        INNER JOIN servicos s 
					ON us.id_serv = s.id_serv
		        WHERE us.id_uni = :id_uni
                    AND s.id_macro IS NULL
                    AND s.stat_serv IN (:stats_serv)
					AND us.stat_serv IN (:stats_serv)
		        ORDER BY us.nm_serv ASC";
	}
	
	public function get_serv_disponiveis_uni (){
		return "SELECT us.id_serv, us.nm_serv, us.stat_serv, s.nm_serv AS nm_serv_mestre
		        FROM uni_serv us
		        INNER JOIN servicos s 
					ON us.id_serv = s.id_serv
		        WHERE us.id_uni = :id_uni
                    AND s.stat_serv IN (:stats_serv)
					AND us.stat_serv IN (:stats_serv)
		        ORDER BY us.nm_serv ASC";
		
	}
	
	public function get_servicos_unidade_transfere_senha(){
		return "SELECT * 
		        FROM uni_serv us
		        INNER JOIN servicos s 
					ON us.id_serv = s.id_serv
		        WHERE us.id_uni = :id_uni
					AND us.id_serv != :id_serv
                    AND s.stat_serv IN (:stats_serv)
					AND us.stat_serv IN (:stats_serv)
		        ORDER BY us.nm_serv ASC";
		
	}

	public function get_servicos_unidade_reativar(){
		return "SELECT *
				FROM servicos s
				INNER JOIN uni_serv us
					ON s.id_serv = us.id_serv
                WHERE us.id_uni = :id_uni
                    AND s.stat_serv IN (:stats_serv)
					AND us.stat_serv IN (:stats_serv)
                    AND s.id_serv IN
                    (
                        SELECT id_serv
                        FROM atendimentos a
                        WHERE a.id_stat IN (:id_stat)
                        AND id_uni= :id_uni
                    )";

	}
	
		
	public function get_servicos_sub_unidade() {
	    return "SELECT * FROM uni_serv AS us, servicos AS s
				WHERE us.id_serv = s.id_serv
					AND s.id_macro = :mestre
					AND s.stat_serv IN (:stats_serv)
					AND us.stat_serv IN (:stats_serv)
					AND us.id_uni = :id_uni
					AND s.id_macro IS NOT NULL
				ORDER BY us.nm_serv ASC";
	}
	
	public function get_servicos_sub_nao_cadastrados_uni(){
		return"	SELECT * 
				FROM servicos s
				WHERE s.id_serv
				NOT IN (SELECT id_serv 
						FROM uni_serv 
						WHERE id_uni = :id_uni
						)
				AND s.stat_serv = 1
				AND s.id_macro IS NOT NULL
				ORDER BY s.id_macro, s.nm_serv";
	}
	
	public function get_servicos_sub() {
	    return "SELECT * 
	    		FROM servicos AS s
				WHERE s.id_macro = :id_macro
					AND s.id_macro IS NOT NULL
                    AND s.stat_serv IN (:stats_serv)
				ORDER BY s.desc_serv ASC";
	}

	public function get_proximo_atendimento() {
	    return "SELECT a.id_atend, a.nm_cli, a.ident_cli, a.num_senha, a.id_pri, a.id_stat, a.dt_cheg, a.dt_cha, a.dt_ini, a.dt_fim,
	                    p.nm_pri, p.desc_pri, p.peso_pri, us.sigla_serv, us.id_serv, us.nm_serv, s.desc_serv
                FROM atendimentos a
                    INNER JOIN uni_serv us
                        ON us.id_serv = a.id_serv
                            AND us.id_uni = a.id_uni
                    INNER JOIN servicos s
                        ON us.id_serv = s.id_serv
                    INNER JOIN prioridades p
                        ON p.id_pri = a.id_pri
                WHERE s.id_serv in (:servicos)
                    AND us.stat_serv = 1
                    AND a.id_stat = 1
                    AND a.id_uni = :id_uni
                ORDER BY p.peso_pri DESC, a.num_senha ASC
                FOR UPDATE";
	}
	
	public function get_atendimentos_by_usuario() {
		return "SELECT a.id_atend, a.nm_cli, a.num_senha, a.id_pri, a.id_stat, a.dt_cha, a.dt_ini, a.dt_fim, 
	                    p.nm_pri, p.desc_pri, p.peso_pri, us.sigla_serv, us.id_serv, us.nm_serv, s.desc_serv 
				FROM atendimentos a 
					INNER JOIN uni_serv us 
						ON us.id_serv = a.id_serv
							AND us.id_uni = a.id_uni
					INNER JOIN servicos s 
		            	ON us.id_serv = s.id_serv
					INNER JOIN prioridades p 
						ON p.id_pri=a.id_pri 
				WHERE a.id_usu = :id_usu
					AND a.id_uni = :id_uni
					AND a.id_stat in (:status)";
	}
	
	public function get_atendimento_por_senha() {
	    return "SELECT a.id_atend, a.nm_cli, a.num_senha, a.id_pri, a.id_stat, a.dt_cha, a.dt_ini, a.dt_fim, 
                    p.nm_pri, p.desc_pri, p.peso_pri, s.sigla_serv, s.id_serv , dt_cheg
				FROM atendimentos a 
					INNER JOIN uni_serv s 
						ON s.id_serv=a.id_serv
                            AND s.id_uni = a.id_uni
					INNER JOIN prioridades p 
						ON p.id_pri=a.id_pri 
				WHERE a.num_senha = :num_senha
					AND a.id_uni = :id_uni
					AND a.id_stat = :id_stat
				";
	}
	
	public function get_atendimento() {
	    return "SELECT a.id_atend, a.nm_cli, a.num_senha, a.id_pri, a.id_stat, a.dt_cha, a.dt_ini, a.dt_fim, 
	                    p.nm_pri, p.desc_pri, p.peso_pri, s.sigla_serv, s.id_serv 
				FROM atendimentos a 
					INNER JOIN uni_serv s 
						ON s.id_serv = a.id_serv
						AND a.id_uni = s.id_uni
					INNER JOIN prioridades p 
						ON p.id_pri = a.id_pri 
				WHERE a.id_atend = :id_atendimento
				";
	}
	
	public function get_fila() {
	    // id_stat = 1 -> passou pela triagem
	    return "SELECT a.id_atend, a.nm_cli, a.num_senha, a.id_pri, a.id_stat, to_char(a.dt_cha,'DD/MM/YYYY') as dt_cha, to_char(a.dt_cheg,'HH24:MI:SS') as dt_cheg,
				 		to_char(a.dt_ini,'HH24:MI:SS') as dt_ini,to_char(a.dt_fim,'HH24:MI:SS') as dt_fim,  
	                    p.nm_pri, p.desc_pri, p.peso_pri, us.sigla_serv, us.id_serv
				FROM atendimentos a 
				INNER JOIN uni_serv us
					ON us.id_serv = a.id_serv
					AND a.id_uni = us.id_uni
                INNER JOIN servicos s
                    ON s.id_serv = us.id_serv
				INNER JOIN prioridades p 
					ON p.id_pri = a.id_pri
				WHERE us.id_serv IN (:servicos)
                    AND s.stat_serv = 1
					AND us.stat_serv = 1
					AND us.id_uni = :id_uni
					AND a.id_stat IN (:id_stat)
				ORDER BY p.peso_pri DESC, a.num_senha ASC";
	}
	
	public function set_atendimento_status() {
	    return "UPDATE atendimentos SET 
    	            id_stat = :status,
    	            :column = :dt_time
	            WHERE id_atend= :id_atend";
	}
	
	public function set_atendimento_prioridade() {
		return "UPDATE atendimentos 
				SET id_pri = :id_pri
	            WHERE id_atend = :id_atend";
	}
	
	public function set_atendimento_usuario() {
	    return "UPDATE atendimentos
	    		SET id_usu = :id_usu
	            WHERE id_atend= :id_atend";
	}
	
	public function set_atendimento_guiche(){
		return "UPDATE atendimentos
				SET num_guiche = :num_guiche
				WHERE id_atend = :id_atend";
	}
	public function chama_proximo() {
    	return "INSERT INTO painel_senha 
	            (id_uni, id_serv, num_senha, sig_senha, msg_senha, nm_local, num_guiche) 
	            VALUES 
	            (:id_uni, :id_serv, :num_senha, :sig_senha, :msg_senha, :nm_local, :num_guiche)";
	}

	public function get_prioridades() {
	    return "SELECT * 
	    		FROM prioridades 
	    		ORDER BY stat_pri, desc_pri ASC";
	}

	public function transfere_senha() {
	    return "UPDATE atendimentos 
		        SET id_serv = :servico, 
		       		id_pri = :prioridade  
		        WHERE id_atend = :id_atend";
	}

	public function distribui_senha(){
		return 
		"
			INSERT INTO atendimentos
			(
				id_uni,
				id_serv, id_pri, id_stat,
				num_senha, nm_cli, num_guiche,
				dt_cheg,ident_cli
			) VALUES ( 
				:id_uni,
				:id_serv, :id_pri, :id_stat,
				:num_senha, :nm_cli, :num_guiche,
				:dt_cheg, :ident_cli
			)
		";
	}
	
	public function encerra_atendimento(){
		return "INSERT INTO atend_codif
				(
					id_atend, id_serv, valor_peso
				) 
				VALUES
				(
					:id_atend, :id_serv, :valor_peso
				)";
	
	}
	
	public function quantidade_total(){
		return "SELECT count(id_serv) 
				FROM atendimentos 
				WHERE id_uni= :id_uni
				AND id_serv= :id_serv";
	}
	
	public function quantidade_fila(){
		return "SELECT count(id_serv)
				FROM atendimentos 
				WHERE id_uni= :id_uni
				AND id_serv= :id_serv
				AND id_stat= :id_stat";
	}

public function get_senha_msg_loc(){
		return "SELECT msg_local
				FROM senha_uni_msg
				WHERE id_uni = :id_uni ";
	}
	
	public function set_senha_msg_loc(){
		return "UPDATE senha_uni_msg 
				SET id_usu = :id_usu , msg_local = :msg
				WHERE id_uni = :id_uni ";
		
	}
	

	public function get_senha_msg_global(){
		return "SELECT msg_global
				FROM senha_uni_msg
				LIMIT 1";

	}
	
	public function set_senha_msg_global(){
		return "UPDATE senha_uni_msg
				SET id_usu = :id_usu , msg_global = :msg";
		
	}
	
	public function set_senha_msg_global_unidades_locais(){
		return "UPDATE senha_uni_msg
				SET id_usu = :id_usu , msg_global = :msg , msg_local = :msg";

	}
	
	public function remover_servico_usu(){
		return "DELETE FROM usu_serv 
				WHERE id_uni = :id_uni 
					AND id_usu = :id_usu
					AND id_serv = :id_serv";
	}
	
	public function remover_servicos_usu(){
		return "DELETE FROM usu_serv 
				WHERE id_uni = :id_uni 
					AND id_usu = :id_usu";
	}
	
	public function adicionar_servico_usu(){
		return "INSERT INTO usu_serv VALUES(:id_uni,:id_serv,:id_usu)";
	}


	public function get_atendimento_senha_periodo(){
		return "SELECT (SELECT ARRAY(SELECT id_serv
				FROM view_historico_atend_codif
				WHERE id_atend = a.id_atend))as id_servicos,a.id_atend,a.ident_cli, a.num_senha, a.id_stat, to_char(a.dt_cheg,'DD/MM/YYYY HH24:MI:SS') as dt_cheg,
				to_char(a.dt_ini,'HH24:MI:SS') as dt_ini,to_char(a.dt_fim,'HH24:MI:SS') as dt_fim,p.nm_pri,p.id_pri,p.peso_pri, u.login_usu, a.num_guiche, s.sigla_serv
				FROM view_historico_atendimentos a
				INNER JOIN
					(SELECT  id_atend, id_serv
						FROM view_historico_atendimentos  
						WHERE num_senha = :num_senha
						AND id_uni = :id_uni
						AND dt_cheg >= :dt_ini
						AND dt_cheg <= :dt_fim)  atendimentos 
				ON a.id_atend = atendimentos.id_atend
				LEFT OUTER JOIN usuarios u
				ON u.id_usu = a.id_usu
				INNER JOIN uni_serv s 
				ON s.id_serv=a.id_serv
				AND s.id_uni = a.id_uni
				INNER JOIN prioridades p 
				ON p.id_pri = a.id_pri ";
	}

	public function alterar_usu(){
		return "UPDATE usuarios
				SET 
					nm_usu = :nm_usu,
					login_usu = :login_usu,
					ult_nm_usu = :ult_nm_usu					  
				WHERE
					id_usu = :id_usu";
	}
	
	public function get_status(){
		return "SELECT nm_stat
				FROM atend_status
				WHERE id_stat = :id_stat ";
	}

	public function insere_mensagem(){
		return "INSERT INTO senha_uni_msg 
				(
					id_uni,id_usu,msg_global
				)
				VALUES 
				(
					:id_uni,:id_usu,:msg_global
				)";
	}
	
	public function get_estat_tempos_medios() {
		return "SELECT count(id_atend) as \"count_atend\", 
				to_char((extract(epoch from (AVG(dt_cha - dt_cheg)))||' s')::interval, 'HH24:MI:SS') as \"avg_espera\", 
				to_char((extract(epoch from (AVG(dt_ini - dt_cha)))||' s')::interval, 'HH24:MI:SS') as \"avg_desloc\", 
				to_char((extract(epoch from (AVG(dt_fim - dt_ini)))||' s')::interval, 'HH24:MI:SS') as \"avg_atend\",
				to_char((extract(epoch from (AVG(dt_fim - dt_cheg)))||' s')::interval, 'HH24:MI:SS') as \"avg_total\"
				FROM view_historico_atendimentos
				WHERE dt_cheg >= :dt_min
    				AND dt_cheg <= :dt_max
    				AND id_stat = :id_stat
    				AND id_uni IN (:ids_uni)";
	}
	
	public function get_qtde_senhas_por_status() {
		return 'SELECT id_stat, COUNT(id_stat)
				FROM view_historico_atendimentos
				WHERE dt_cheg >= :dt_min
    				AND dt_cheg <= :dt_max
    				AND id_uni IN (:ids_uni)
				GROUP BY id_stat';
	}
	
	public function get_estatistica_servico_mestres() {
		return "SELECT coalesce(s.id_macro, s.id_serv) as \"id_macro\",  s.nm_serv, count(coalesce(s.id_macro, s.id_serv)),
					to_char((extract(epoch from (AVG(dt_cha - dt_cheg)))||' s')::interval, 'HH24:MI:SS') as \"avg_espera\", 
					to_char((extract(epoch from (AVG(dt_ini - dt_cha)))||' s')::interval, 'HH24:MI:SS') as \"avg_desloc\", 
					to_char((extract(epoch from (AVG(dt_fim - dt_ini)))||' s')::interval, 'HH24:MI:SS') as \"avg_atend\",
					to_char((extract(epoch from (AVG(dt_fim - dt_cheg)))||' s')::interval, 'HH24:MI:SS') as \"avg_total\"
				FROM view_historico_atendimentos a
				INNER JOIN servicos s 
					ON a.id_serv = s.id_serv
				WHERE a.dt_cheg >= :dt_min
    				AND a.dt_cheg <= :dt_max
    				AND a.id_stat = :id_stat
					AND a.id_uni IN (:ids_uni)
					AND s.id_macro is null
				GROUP BY coalesce(s.id_macro, s.id_serv), s.nm_serv";
	}
	
	
	
	public function get_estatistica_servico_codificados() {
		return "SELECT s.nm_serv, count(coalesce(s.id_macro, s.id_serv)),
					to_char((extract(epoch from (AVG(dt_cha - dt_cheg)))||' s')::interval, 'HH24:MI:SS') as \"avg_espera\", 
					to_char((extract(epoch from (AVG(dt_ini - dt_cha)))||' s')::interval, 'HH24:MI:SS') as \"avg_desloc\", 
					to_char((extract(epoch from (AVG(dt_fim - dt_ini)))||' s')::interval, 'HH24:MI:SS') as \"avg_atend\",
					to_char((extract(epoch from (AVG(dt_fim - dt_cheg)))||' s')::interval, 'HH24:MI:SS') as \"avg_total\"
				FROM view_historico_atend_codif ac
				INNER JOIN view_historico_atendimentos a 
					ON ac.id_atend = a.id_atend
				INNER JOIN servicos s 
					ON ac.id_serv = s.id_serv
				WHERE a.dt_cheg >= :dt_min
    				AND a.dt_cheg <= :dt_max
    				AND a.id_stat = :id_stat
					AND a.id_uni IN (:ids_uni)
				GROUP BY s.nm_serv";
	}
	
	public function get_tempos_atend_por_usu() {
		return "SELECT u.nm_usu||' '||u.ult_nm_usu AS \"nome\", SQ.count_atend, SQ.qtde_senhas, SQ.avg_desloc, SQ.avg_atend
				FROM usuarios u
				INNER JOIN
				(
					SELECT a.id_usu, count(DISTINCT a.id_atend) AS \"qtde_senhas\", count(ac.id_atend) AS \"count_atend\",
						to_char((extract(epoch from (AVG(dt_ini - dt_cha)))||' s')::interval, 'HH24:MI:SS') as \"avg_desloc\", 
						to_char((extract(epoch from (AVG(dt_fim - dt_ini)))||' s')::interval, 'HH24:MI:SS') as \"avg_atend\"
					FROM view_historico_atendimentos a
                                        INNER JOIN view_historico_atend_codif ac
                                            ON ac.id_atend = a.id_atend
					WHERE a.dt_cheg >= :dt_min
    					AND a.dt_cheg <= :dt_max
    					AND a.id_stat = :id_stat
						AND a.id_uni IN (:ids_uni)
					GROUP BY a.id_usu
				) SQ
				ON (u.id_usu = SQ.id_usu)";
	}
	
	public function get_estat_atend_por_usu() {
		return "SELECT u.nm_usu||' '||u.ult_nm_usu AS \"nome\", s.nm_serv, SQ.count_atend
				FROM usuarios u
				INNER JOIN
					(
						SELECT a.id_usu, ac.id_serv, COUNT(ac.id_serv) AS \"count_atend\"
						FROM view_historico_atendimentos a
                                                INNER JOIN view_historico_atend_codif ac
                                                    ON a.id_atend = ac.id_atend
						WHERE dt_cheg >= :dt_min
							AND dt_cheg <= :dt_max
							AND id_stat = :id_stat
							AND id_uni IN (:ids_uni)
						GROUP BY a.id_usu, ac.id_serv
					) SQ
					ON (u.id_usu = SQ.id_usu)
				INNER JOIN servicos s
					ON (SQ.id_serv = s.id_serv)
                                ORDER BY \"nome\"";
	}
	
	public function get_msg_status(){
		return "SELECT status_imp 
				FROM senha_uni_msg 
				WHERE id_uni = :id_uni";
	}
	
	public function set_msg_status(){
		return "UPDATE senha_uni_msg 
				SET 
					status_imp = :status_imp
				WHERE 
					id_uni = :id_uni";
	}
	
	public function get_nm_pri(){
		return "SELECT nm_pri
				FROM prioridades
				WHERE id_pri = :id_pri";
	}
	
	public function get_servicos_macro_nao_cadastrados_uni(){
		return "SELECT * 
				FROM servicos 
				WHERE id_serv
							  NOT IN (SELECT id_serv 
									  FROM uni_serv 
									  WHERE id_uni = :id_uni)
				AND stat_serv = 1
				AND id_macro is null
				ORDER BY nm_serv";
	}
	
	public function alterar_senha_usu() {
		return "UPDATE usuarios
				SET senha_usu = md5(:nova_senha)
				WHERE id_usu = :id_usu
				AND senha_usu = md5(:senha_atual)";
	}
	
	public function alterar_senha_mod_usu() {
		return "UPDATE usuarios
				SET senha_usu = md5(:nova_senha)
				WHERE id_usu = :id_usu";
	}

	public function get_servicos_unidade_erro_triagem(){
		return "SELECT * 
		        FROM uni_serv us 
		        INNER JOIN servicos s 
		              ON us.id_serv = s.id_serv 
		         WHERE us.id_uni = :id_uni
		         	AND us.stat_serv IN (:stats_serv)
					AND s.id_serv NOT IN (
						SELECT id_serv 
						FROM usu_serv 
						WHERE id_usu = :id_usu
						AND id_uni = :id_uni)
		         ORDER BY us.nm_serv ASC";
	}
	
	public function set_status_usu(){
		return "UPDATE usuarios 
				SET stat_usu = :stat_usu 
				WHERE id_usu = :id_usu";
	}

	public function get_estat_atendimentos_encerradas(){

		return"	SELECT a.num_senha, a.id_uni, qtd_serv.count,a.nm_cli,to_char(a.dt_cheg,'DD/MM/YYYY') as dt_cheg,
		 		to_char(a.dt_ini,'HH24:MI:SS') as dt_ini,to_char(a.dt_fim,'HH24:MI:SS') as dt_fim, s.nm_serv,
		 		u.login_usu, a.num_guiche, to_char(a.dt_cha,'HH24:MI:SS') as dt_cha, uni.nm_uni,
                to_char ((dt_fim - dt_ini),'HH24:MI:SS') as tempo
				FROM view_historico_atendimentos a
				LEFT OUTER JOIN usuarios u
					ON u.id_usu = a.id_usu
				INNER JOIN uni_serv s 
					ON s.id_serv=a.id_serv
				AND s.id_uni = a.id_uni
				INNER JOIN(
					SELECT id_atend, COUNT(id_serv)
					FROM view_historico_atend_codif
					GROUP BY id_atend) qtd_serv
				ON a.id_atend = qtd_serv.id_atend
				INNER JOIN unidades uni
				ON a.id_uni = uni.id_uni				
				WHERE a.id_stat = 8
				AND a.dt_cheg >= :dt_min
				AND a.dt_cheg <= :dt_max
				AND a.id_uni IN (:ids_uni)
				ORDER BY a.id_uni, a.num_senha, a.dt_cheg";
	}
	public function get_estat_atendimentos(){
		return"	SELECT a.num_senha, a.id_serv, s.nm_serv, s.id_uni, a.nm_cli, uni.nm_uni, st.nm_stat,
				to_char(a.dt_cheg,'DD/MM/YYYY') as dt_cheg, to_char(a.dt_ini,'HH24:MI:SS') as hr_ini,
				to_char(a.dt_fim,'HH24:MI:SS') as hr_fim, u.login_usu, a.num_guiche,
				to_char(a.dt_cha,'HH24:MI:SS') as hr_cha,
				to_char(a.dt_cheg,'HH24:MI:SS') as hr_cheg,
				to_char((dt_fim - dt_ini),'HH24:MI:SS') as tmp_atend,
				to_char((dt_cha - dt_cheg),'HH24:MI:SS') as tmp_fila,
				to_char((dt_ini - dt_cha),'HH24:MI:SS') as tmp_desl,
				to_char((dt_fim - dt_cheg),'HH24:MI:SS') as tmp_total
				
				FROM view_historico_atendimentos a
				LEFT OUTER JOIN usuarios u
					ON u.id_usu = a.id_usu
				INNER JOIN uni_serv s 
					ON s.id_serv=a.id_serv AND s.id_uni = a.id_uni
                INNER JOIN unidades uni
                    ON uni.id_uni = a.id_uni
                INNER JOIN atend_status st
                	ON a.id_stat = st.id_stat
				WHERE a.dt_cheg >= :dt_min
                    AND a.dt_cheg <= :dt_max
                    AND a.id_uni IN (:ids_uni)
                ORDER BY a.id_uni, a.num_senha, a.dt_cheg";
                
	}
	
	public function get_ranking_unidades() {
        return "SELECT u.nm_uni, SQ.count_atend, SQ.avg_espera, SQ.avg_desloc, SQ.avg_atend, SQ.avg_total
                FROM unidades u
                INNER JOIN
                (
                    SELECT id_uni, count(id_atend) as \"count_atend\",
                        extract(epoch from (AVG(dt_cha - dt_cheg))) as \"avg_espera\",
                        extract(epoch from (AVG(dt_ini - dt_cha))) as \"avg_desloc\",
                        extract(epoch from (AVG(dt_fim - dt_ini))) as \"avg_atend\",
                        extract(epoch from (AVG(dt_fim - dt_cheg))) as \"avg_total\"
                    FROM view_historico_atendimentos vha
                    WHERE dt_cheg >= :dt_min
                        AND dt_cheg <= :dt_max
                        AND id_stat = :id_stat
                        AND vha.id_uni IN (:ids_uni)
                    GROUP BY id_uni
                ) SQ
                ON (u.id_uni = SQ.id_uni)
                ";
    }

    public function get_estat_macro_serv_global() {
        return "SELECT s.nm_serv, SQ.count_serv
                FROM servicos s
                INNER JOIN
                (
                    SELECT s.id_serv, COUNT(vha.id_serv) as count_serv
                    FROM view_historico_atendimentos vha
                    INNER JOIN servicos s
                        ON (s.id_serv = vha.id_serv)
                    WHERE dt_cheg >= :dt_min
                        AND s.id_macro IS NULL
                        AND dt_cheg <= :dt_max
                        AND id_stat = :id_stat
                        AND vha.id_uni IN (:ids_uni)
                    GROUP BY s.id_serv
                ) SQ ON (SQ.id_serv = s.id_serv)";
    }

    public function get_estat_atendimentos_uni_global() {
        return "SELECT u.nm_uni, SQ.count_serv
                FROM unidades u
                INNER JOIN
                (
                    SELECT id_uni, COUNT(vha.id_serv) as count_serv
                    FROM view_historico_atendimentos vha
                    WHERE dt_cheg >= :dt_min
                        AND dt_cheg <= :dt_max
                        AND id_stat = :id_stat
                        AND vha.id_uni IN (:ids_uni)
                    GROUP BY id_uni
                ) SQ ON (SQ.id_uni = u.id_uni)";
    }

    public function get_estat_serv_uni() {
        return "SELECT u.nm_uni, us.nm_serv, SQ.count_serv
                FROM uni_serv us
                INNER JOIN
                (
                    SELECT id_uni, id_serv, COUNT(vha.id_serv) as count_serv
                    FROM view_historico_atendimentos vha
                    WHERE dt_cheg >= :dt_min
                        AND dt_cheg <= :dt_max
                        AND id_stat = :id_stat
                        AND vha.id_uni IN (:ids_uni)
                    GROUP BY id_uni, id_serv
                ) SQ ON (SQ.id_serv = us.id_serv AND SQ.id_uni = us.id_uni)
                INNER JOIN unidades u
                    ON (u.id_uni = SQ.id_uni)
                ORDER BY nm_uni";
    }

    public function get_tempos_medios_por_periodo() {
        return "SELECT date_trunc('month', dt_cheg) as dt_atend,
                    count(id_atend) as count_atend,
                    extract(epoch from (AVG(dt_cha - dt_cheg))) as avg_espera,
                    extract(epoch from (AVG(dt_ini - dt_cha))) as avg_desloc,
                    extract(epoch from (AVG(dt_fim - dt_ini))) as avg_atend,
                    extract(epoch from (AVG(dt_fim - dt_cheg))) as avg_total
                FROM view_historico_atendimentos vha
                WHERE dt_cheg >= :dt_min
                    AND dt_cheg <= :dt_max
                    AND id_stat = :id_stat
                    AND vha.id_uni IN (:ids_uni)
                GROUP BY dt_atend
                ORDER BY dt_atend";
    }
}

?>
