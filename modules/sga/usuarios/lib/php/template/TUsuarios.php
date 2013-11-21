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
 * Classe TUsuarios
 *
 * responsavel pela estrutura HTML do módulo Usuarios
 *
 */
class TUsuarios extends Template {

	/**
	 * Mostra o cabeçalho da página Tusuarios
	 * @param $title
	 */
	public static function display_header($title='') {
		$tema = parent::get_tema();
		$mod_dir = Session::getInstance()->get(SGA::K_CURRENT_MODULE)->get_dir();
		$misc = '<link rel="stylesheet" href="themes/'. $tema->get_dir() .'/css/usuarios.css" type="text/css" />' . "\n";
		$misc .= '<script type="text/javascript" src="modules/'.$mod_dir.'/lib/js/usuarios.js"></script>' . "\n";
		$misc .= '<script type="text/javascript">
			                var usuarios = new Usuarios();
			                SGA.addOnLoadListener(usuarios.refresh);
		          </script>';
		parent::display_header($title, $misc);
	}

	/**
	 * Mostra o topo menu e conteudo padrões do SGA 
	 */
	public static function display_usuarios_template() {
		$topo = array('TUsuarios', 'display_usu_topo');
		$menu = array('TUsuarios', 'display_usu_menu');
		$conteudo = array('TUsuarios', 'display_usu_content');
		Template::display_template_padrao($topo, $menu, $conteudo);
	}

	/**
	 * 
	 */
	public static function display_usu_topo() {
		$modulo = Session::getInstance()->get(SGA::K_CURRENT_MODULE);
		Template::display_topo_padrao($modulo);
	}

	/**
	 * Exibe o medu do modulo usuarios
	 */
	public static function display_usu_menu() {
		$usuario = SGA::get_current_user();
		Template::display_user_info($usuario);
		Template::display_menu_padrao($modulo, $usuario);
	}

	/**
	 * Exibe conteudo central da pagina do modulo usuarios
	 */
	public static function display_usu_content() {
        TUsuarios::display_users_config();
	}

	/**
	 * Carrega conteudo central da pagina do modulo usuarios
 * @author dataprev
 *
	 * 
	 */
	public static function display_users_config() {
		Template::display_page_title('Usuários');
		?>
			<div id="config_users">
				<div id="config_user_search">
				    <?php
				    	TUsuarios::displayer_user_search();
				    ?>
			    </div>
			    <div id="config_user_search"><?php
			    $admin = SGA::get_current_user();
        
		        $raiz = DB::getInstance()->get_arvore_grupos();
		        $tmp_grupos = array();
		        TUsuarios::get_arvore_grupos_array($raiz, &$tmp_grupos);
		
		        // Obtem as lotacoes do admin nas quais ele pode editar usuarios
		        $lotacoes = DB::getInstance()->get_lotacoes_editaveis($admin->get_id(), Session::getInstance()->get(SGA::K_CURRENT_MODULE)->get_id(), $raiz->get_id(), true);
		        
		        // se o usuario não possui a permissao na raiz
		        // (caso existir deverá ser o unico grupo retornado devido ao filtro de redundancia)
		        // caso ele possuir a arvore inteira de grupos é exbida, não sendo necessário filtrar grupos
		        if (!$lotacoes[0]->get_grupo()->is_raiz()) {
		        	// Monta uma arvore parcial
		            // filtrando grupos não permitidos
		            $filhos = array();
		            $grupos = array();
		            $raiz->clear_filhos();
		            foreach ($lotacoes as $lotacao) {
		            	//$raiz->add_filho($lotacao->get_grupo());
		                $grupo = $lotacao->get_grupo();
		                $filhos = DB::getInstance()->get_sub_grupos($grupo->get_id());
		                if (sizeOf($filhos) > 0){
		                	foreach ($filhos as $f){
								$grupo->add_filho($f);	
        					}
		                }
		                $raiz->add_filho($grupo);
		            }
		        }
		        TUsuarios::display_arvore_grupos($raiz);
			    ?>
			    </div>
		     </div>
		<?php
	}
	
	/**
	 * Carrega um array com a Arvore de grupos
     * @param $grupo
     * @param $array
     * 
	 */
    public static function get_arvore_grupos_array(Grupo $grupo, $array) {
        $array[$grupo->get_id()] = $grupo;
        foreach ($grupo->get_filhos() as $g) {
            TUsuarios::get_arvore_grupos_array($g, &$array);
        }
    }


	/**
	 * Exibe os botoes de de busca e criação do  usuario e o conteudo da busca
	 */
	public static function displayer_user_search() {
		$tmp['login'] = 'Por Usuário';
		$tmp['nome'] = 'Por Nome';
        
		?>
			<div id="criar_usuario">
				<?php Template::display_action_button("Criar Usu&aacute;rio", "images/insert.png", "Usuarios.criarUsuario();",'' ,'button',true, "Clique para criar um usuário.")?>
			</div>
			
			<div>
				<input title="Digite o termo da busca." maxlength="120" type="text" id="search_input" name="user_input" onkeypress="return SGA.txtBoxAlfaNumerico(this, event, 'id_buscar_usuario');"/>
				<?php Template::display_action_button("Buscar", "images/zoom.gif", "Usuarios.buscarUsuario();",'button','id_buscar_usuario',true,'Clique para buscar um usuário. Se deixar o campo em branco todos os usuários, a que você tem acesso, serão mostrados.')?>
				<div><?php echo parent::display_jump_menu($tmp, "search_type", "login", null, "",0,'',"'Selecione o tipo de busca.'");?></div>
			</div>
			
		<?php
		echo TUsuarios::display_resultado_users();
	}

	/**
	 * Exibe arvore de grupos
	 *@param $raiz
	 */
	public static function display_arvore_grupos($raiz) {
		?>
        <div title="Lista de grupos." class="lista">
            <ul id="lista_grupos"  class="arvore_grupos">
                <?php
                    TUsuarios::display_item_arvore_grupo($raiz);
                ?>
            </ul>
		</div>
		<?php
	}

	/**
	 * Constrói árvore dos grupos
     * @param $grupo
     *
	 */
    public static function display_item_arvore_grupo(Grupo $grupo) {
        $possui_filhos = sizeof($grupo->get_filhos()) > 0;
        ?>
            <li><div id="span_grupo_<?php echo $grupo->get_id();?>" class="<?php echo ($possui_filhos ? "item_grupo_pai" : "item_grupo_filho");?>"><a href="javascript:Usuarios.selectGrupo(<?php echo $grupo->get_id();?>);"><?php echo $grupo->get_nome();?></a></div>
                <?php
                    if ($possui_filhos) {
                        ?>
                        <ul>
                            <?php
                                foreach ($grupo->get_filhos() as $filho) {
                                    TUsuarios::display_item_arvore_grupo($filho);
                                }
                            ?>
                        </ul>
                        <?php
                    }
                ?>
            </li>
        <?php
    }

	/**
	 * Carrega resultado da busca de usuarios
	 * @param $result
	 */
	public static function display_resultado_users($result = null) {
		?>
			<div id="conteudo_resultado_usuarios">
				<?php
					// usar comparação !== para evitar que arrays vazios (0 encontrados) não sejam exibidos
					if ($result !== null) {
						TUsuarios::display_resultado_users_interno($result);
					}
				?>
			</div>
		<?php
	}

	/**
	 * Exibe o resultado da busca de usuarios
	 * @param $result
	 * @param $id_grupo
	 */
	public static function display_resultado_users_interno($result, $id_grupo) {
		$list = array();
		foreach ($result as $usuario) {
			$list[$usuario->get_id()] = $usuario;
		}
		?>
			<div><h3><?php echo sizeof($list);?> encontrado(s)</h3></div>
			<div>
				<?php
					if (sizeof($list) > 0) {
						if (sizeof($list) == 1) {
							TUsuarios::display_edit_user($result[0], $id_grupo);
						}
                        else {
							echo TUsuarios::display_usuarios($list, 'select_resultado_usuarios', '', "- Selecione - (Usuário - Nome)", 'Usuarios.onSelecionaUsuario();',0,'',"'Selecione um resultado.'");
                            ?>
                            <div class='config_user_info' id="config_user_info">
                            	<?php // Place holder atualizado via AJAX ?>
                            </div>
                            <?php
						}
					}
				?>
			</div>
		<?php
	}

	/**
	 * Monta um Jump Menu atraves do array passado por parametro
	 * Coloca cor vermelha nos usuarios desativados
	 * @param $array
	 * @param $name
	 * @param $default
	 * @param $label
	 * @param $onchange
	 * @param $size
	 * @param $eventos
	 * @param $title
	 * @return $jump
	 */
	static function display_usuarios($array, $name='', $default='', $label='', $onchange='', $size=0, $eventos = '', $title='') {
		$jump = '<select id="'.$name.'" name="'.$name.'" onchange="'.$onchange.' '.$eventos.'" size="'.$size.'" title='.$title.' >' . "\n";
		if (!empty($label)) {
		    $jump .= '	<option value="">'.$label.'</option>' . "\n";
		}
		if (!is_array($array)) {
			$array = array();
		}
		foreach ($array as $key) {
			$sel = '';

			if ($key == $default){
				$sel = 'selected="selected"';
			}
			$cor = ($key->get_status() == 0)?'red': 'black';
			$jump .= '	<option style="color:'.$cor.';" value="'.$key->get_id().'" '.$sel.'>'.$key->get_login().' - '.$key->get_nome().'</option>' . "\n";
		}
		$jump .= "</select>\n";
		return $jump;
	}

	/**
	 *Exibe as informações para criar um novo usuario ou editar um ja existente 
	 * @param $usuario
	 * @param $id_grupo
	 */
    public static function display_edit_user(Usuario $usuario = null, $id_grupo = null) {
        $disabled = false;
        $editando = $usuario != null;
        $id_usu = null;
        
        $tmp = array();
		foreach (DB::getInstance()->get_todos_usuarios() as $t){
			$tmp[$t->get_id()] = $t->get_login();
		}
		$logins_usu = implode(',',$tmp);
       
        if ($editando)
        {
            if ($usuario->get_status() == 1) {
                $status = 'Desativar';
            }
            else {
                $status = 'Ativar';
                $disable='disabled="disabled"';
            }

            $id_usu = $usuario->get_id();
            $nm_usu = $usuario->get_nome();
            $ult_nm_usu = $usuario->get_sobrenome();
            $login_usu = $usuario->get_login();

        }

        //$auto_disable = (Session::getInstance()->get(SGA::K_CURRENT_USER)->get_id() == $usuario->get_id())? 'disabled="disabled"' : '';
        ?>
        <form>
            <div id="editar_usuario">
            <?php if($editando){?>
            	<h1>EDITAR USUÁRIO</h1>
            <?php }?>
            <div title="Nome do usuário.">
            <span>Nome</span>
            <input type="text" id="nm_user" name="nm_user" <?php echo $disable;?> value="<?php echo $nm_usu;?>" maxlength="20" size="27"/><label class="advertencia" id="id_label_editar_nm"></label>
            <label class="advertencia" generated="true" for="nm_user" id="id_label_edit_nome"></label>
            </div>
            <div title="Sobrenome do usuário.">
            <span>Sobrenome</span>
            <input type="text" id="ult_nm_user" <?php echo $disable;?> name="ult_nm_user" value="<?php echo $ult_nm_usu;?>" maxlength="100" size="27"/><label class="advertencia" id="id_label_editar_ult_nm"></label>
            <label class="advertencia" generated="true" for="ult_nm_user" id="id_label_edit_ult_nome"></label>
            </div>
            <div title="Nome do usuário, máximo 10 dígitos.">
                <span>Usuário</span>
                <input  type="text" id="login_usu" name="login_usu" <?php echo $disable;?> value="<?php echo $login_usu;?>" maxlength="10"  onkeypress="return SGA.txtBoxAlfaNumerico(this,event);" size="27" />
                <label class="advertencia" generated="true" for="login_usu" id="id_label_editar_mat"></label>
            </div>
            <?php
            if ($editando) {
                ?>
                <div title="Clique no botão para alterar a senha do usuário."><span>Senha</span><input title="Clique para alterar a senha do usuário" type="button" <?php echo $disable;?> class="btn" value="Alterar Senha" onclick="Usuarios.alterarSenha();" /></div>
                <input type="hidden" id="input_id_usuario" value="<?php echo $usuario->get_id();?>" />
                <input type="hidden" id="input_lg_usuario" value="<?php echo $usuario->get_login();?>" />
                <?php
            }
            else {
                ?>
                <div title="Senha do usuário, mínimo 6 caracteres.">
	                <span>Senha</span>
	                <input type="password" id="senha_usu" name="senha_usu" maxlength="40" />
	                <?php parent::display_label_advertencia('id_label_senha')?>
                </div>
                <div title="Confirmação da senha do usuário.">
	                <span>Confirmar Senha</span>
	                <input type="password" id="senha_usu2" name="senha_usu2" maxlength="40" />
	                <?php parent::display_label_advertencia('id_label_senha2')?>
                </div>
                
                <?php
            }
            ?>
            <div class="cleaner"></div>
            <div title="Grupos dos quais o usuário faz parte." >
                <span>Grupos/Cargos</span>
                <span id="group_list">
                    <?php
                    $tmp = array();
                    if ($usuario != null) {
                        // editando
                        $admin = SGA::get_current_user();
                        $id_mod = Session::getInstance()->get((SGA::K_CURRENT_MODULE))->get_id();

                        $lotacoes = DB::getInstance()->get_lotacoes_visiveis($usuario->get_id(), $admin->get_id(), $id_mod, $id_grupo);
                    }
                    else {
                        // criando
                        $lotacoes = array();
                    }

                    TUsuarios::display_user_group_list($lotacoes);
                    parent::display_label_advertencia('id_label_editar_grupos');
                    ?>
                </span>
                <span class="bts_edit">
                    <input title="Clique para ADICIONAR uma lotação." type="button" <?php echo $disable;?> class="btn" value="Adicionar" onclick="Usuarios.adicionarLotacao(<?php echo $id_usu;?>);" />
                    <input title="Clique para EDITAR uma lotação." type="button" <?php echo $disable;?> class="btn" value="Editar" onclick="Usuarios.editarLotacao();" />
                    <input title="Clique para REMOVER uma lotação." type="button" <?php echo $disable;?> class="btn" value="Remover" onclick="Usuarios.removerLotacao();" />
                </span>
            </div>
            <div class="cleaner"></div>
            <div title="Serviços que o usuário pode atender.">
                <span>Serviços</span>
                <div id="div_select_unidades">
                    <?php
                    $ids_grupo = array();
                    foreach ($lotacoes as $l) {
                        $ids_grupo[] = $l->get_grupo()->get_id();
                    }
                    TUsuarios::display_select_uni_serv($id_grupo, $ids_grupo);
                    ?>
                </div>
                <span></span>
                <span id="serv_list">
                    <?php
                    TUsuarios::display_user_serv_list(null, null, 'select_servicos_atendidos',$disabled);
                    ?>
                </span>
                <span class="bts_edit">
                    <input title="Clique para ADICIONAR um serviço ao usuário." type="button" <?php echo $disable;?> class="btn" value="Adicionar" onclick="Usuarios.adicionarServ();" />
                    <input title="Clique para REMOVER um serviço do usuário." type="button" <?php echo $disable;?> class="btn" value="Remover" onclick="Usuarios.removerServ();" />
                </span>
                <div class="cleaner"></div>
                <ul class="config_user_control">
                    <li><input title="Clique aqui para SALVAR as alterações." type="button" <?php echo $disable;?> class="btn" onclick="Usuarios.editarUsuario(this,'<?php echo $logins_usu;?>');" value="Salvar" /></li>
                    <li><input title="Clique aqui para CANCELAR as alterações." type="button" class="btn" <?php echo $disable;?> onclick="<?php echo ($editando ? 'Usuarios.onSelecionaUsuario();' : 'window.closePopup(this);');?>" value="Cancelar" /></li>
                    <?php
                    if ($editando) {
                        if($usuario->get_id() == Session::getInstance()->get(SGA::K_CURRENT_USER)->get_id()){
                        	$bt_disab = "disabled='disabled';";
                        }else{
                        	$bt_disab = "";	
                        }
                    	?>
                        <li><input title="Clique aqui para <?php echo $status;?> o usuário." type="button" class="btn" <?php echo $bt_disab?> onclick="Usuarios.modificaStatus(<?php echo $id_usu;?>,<?php echo $usuario->get_status();?>);" value="<?php echo $status;?>" /></li>
                        <?php
                    }
                    ?>
                </ul>
            </div>
        </form>
        <?php
    }
	
    /**
     * Exibe um jump menu com as unidades que o usuario tem acesso
     * @param $id_grupo_filtro
     * @param $ids_grupo
     * @return unknown_type
     */
    public static function display_select_uni_serv($id_grupo_filtro, $ids_grupo) {
        $admin = SGA::get_current_user();

        $unidades = DB::getInstance()->get_unidades_by_grupos_mod_usu($ids_grupo);

        $tmp_uni = array();
        foreach ($unidades as $u) {
            $tmp_uni[$u->get_id()] = $u->get_nome();
        }
        
        echo Template::display_jump_menu($tmp_uni, 'usu_id_uni_serv', '', '-- Unidade --', 'Usuarios.onSelecionaUnidadeServicos();');
    }

	/**
	 * Exibe o Popup para alterar senha
	 */
	public static function display_edit_pass_user() {
	    ?>
	    <form onsubmit ="Usuarios.confirmaAlterarSenha(); return false;">
            <div><span>Nova senha</span><input type="password" id="nova_senha" maxlength="40" /><?php parent::display_label_advertencia('id_senha_nova')?></div>
            <div><span>Confirmar nova senha</span><input type="password" id="confirmar_nova_senha" maxlength="40" /><?php parent::display_label_advertencia('id_senha_confirma')?></div>

            <span class="config_user_control">
               <input type="submit" class="btn"  value="Salvar" />
               <input type="button" class="btn" onclick="window.closePopup(this);" value="Cancelar" />
            </span>
         </form>
        <?php
	}
	
	/**
	 * Exibe Popup com os serviços por unidade que o usuario editado não tem acesso
	 * @param $id_servicos
	 * @param $id_usu
	 */
	public static function display_new_serv_user($id_servicos, $id_uni) {
		?>
			<div>
				Serviços:
                <?php
	                $hidden = TUsuarios::display_serv_uni_list($id_servicos, $id_uni);
                ?>
           </div>
           	<div id="separador">
           	<span>
           		<?php
                if (!$hidden) {
                ?>
					<input id="confirmar_novo_servico" type="button" class="btn" value="Confirmar" onclick="Usuarios.adicionarServUsu(this);" />
                <?php
                }
                ?>
	            <input type="button" class="btn" value="Cancelar" onclick="window.closePopup(this);" />
	        </span>
           </div>
		<?php
	}
	
	/**
	 * carrega jump menu com os serviços por unidade que o usuario editado ainda nao tem acesso
	 * @param $id_servicos
	 * @param $id_uni
	 * @return $hidden boolean
	 */
	public static function display_serv_uni_list($id_servicos, $id_uni) {
		$servicos = DB::getInstance()->get_servicos_unidade($id_uni);

		$tmp = array();
		foreach ($servicos as $s) {
			if(array_search($s->get_id(), $id_servicos) === FALSE){
				$tmp[$s->get_id()] = $s->get_sigla().'-'.$s->get_nome();
			}
		}
		if(sizeof($tmp) == 0){
			echo 'Não há nenhum serviço a ser adicionado.';
			return $hidden = true;
		}else{
        	echo parent::display_jump_menu($tmp, 'select_serv_uni', '', 'Todos', '');
        	return $hidden = false;
		}
	}

	/**
	 * Carrega um jump menu com as lotacoes do usuario
	 * @param $usuario
	 * @param $id_select
	 * @param $disable
	 */
	public static function display_user_group_list($lotacoes) {
         foreach ($lotacoes as $l) {
	        	$tmp[$l->get_grupo()->get_id().';'.$l->get_cargo()->get_id()] = $l->get_grupo()->get_nome().' - '.$l->get_cargo()->get_nome();
         }
         
        echo parent::display_jump_menu($tmp, 'select_grupos_usuario', '', null, '', 6,'','',$disable,"","multiple");
	}
	
	/**
	 * Carrega um jump menu com os serviços que o usuario pode executar na unidade passada por paramentro
	 * @param $id_usu
	 * @param $id_select
	 * @param $disable
	 * @param $id_uni
	 */
	public static function display_user_serv_list($id_usu, $id_uni, $id_select = 'select_servicos_atendidos', $disable = '') {
        $tmp = array();

        if ($id_usu != null) {
            $id_servicos = DB::getInstance()->get_usuario_servicos_unidade($id_usu, $id_uni, array(Servico::SERVICO_INATIVO, Servico::SERVICO_ATIVO));

            $servicos = array();
            $count = 0;
            foreach($id_servicos as $id) {
                $servicos[$count] = DB::getInstance()->get_servico_unidade($id, $id_uni);
                $count++;
            }
            foreach ($servicos as $s) {
                $tmp[$s->get_id()] = $s->get_sigla().'-'.$s->get_nome();
            }
        }
        echo parent::display_jump_menu($tmp,$id_select, '', null, '', 6,'','',$disable,"","multiple");
	}
	
	/**
	 * Exibe popup com os grupos e cargos visiveis pelo usuario e que o usuario editado não tem acesso
	 * @param $admin
	 * @param $grupo
	 * @param $cargo
	 * @param $id_usu
	 * @param $id_grupo_selecionado
	 * @return unknown_type
	 */
	public static function display_user_group(Usuario $admin, Grupo $grupo = null, Cargo $cargo = null, $id_usu = null , $id_grupo_selecionado = null) {
		$editando = $grupo != null && $cargo != null;
        if ($editando) {
			$acaoStr = "editar";
			$grupo_default = $grupo->get_id();
			$cargo_default = $cargo->get_id();
			$acao = Template::ACAO_ATUALIZAR;
            // obtem a lotacao do admin no grupo a ser editado, para saber seu poder(Cargo) sobre este grupo
            $lotacao = DB::getInstance()->get_lotacao_valida($admin->get_id(), $grupo_default);
            $cargos = DB::getInstance()->get_sub_cargos($lotacao->get_cargo()->get_id());
		}
		else {
			$acaoStr = "criar";
			$acao = Template::ACAO_INSERIR;
            $cargos = array();
		}

		$modulo = Session::getInstance()->get(SGA::K_CURRENT_MODULE);

		// retorna os grupos(e seus subgrupos) ao qual esse usuário tem acesso ao módulo de usuários
		$grupos = DB::getInstance()->get_grupos_by_permissao_usuario($admin->get_id(), $modulo->get_id());
		
	//verifica se está adicionando 
		if ($id_usu != null){
			//Traz um array das lotações possíveis de serem editadas pelo usuário que esta gerenciando o modulo usuario 
			$lotacoes = DB::getInstance()->get_lotacoes_visiveis($id_usu,$admin->get_id(), $modulo->get_id(),$id_grupo_selecionado);	
			
			//array que armazenará somente os ids dos grupos trazidos pela var. $lotacoes
			$ids_grupos_visiveis = array();
			foreach ($lotacoes as $lot){
				$ids_grupos_visiveis[] = $lot->get_grupo()->get_id();
			}
			
			//array que armazenará todos os ids dos grupos permitido ao usuário que esta gerenciando o módulo 
			$ids_grupos_permitidos = array();
			foreach ($grupos as $g) {
				$ids_grupos_permitidos[] = $g->get_id();
			}
			
			//array que armazena os ids dos grupos que o usuário editado ainda nao possui
			$array_diff = array_diff($ids_grupos_permitidos,$ids_grupos_visiveis);

			// prepara o array de grupos para ser usado com jump_menu
			for ($i=0; $i < count($grupos); $i++) {
				if($grupos[$i]->get_id()== $array_diff[$i]){
					$tmp_grupos[$grupos[$i]->get_id()] = $grupos[$i]->get_nome();
					asort($tmp_grupos);
				}
			}
		
		}else{
				// prepara o array de grupos para ser usado com jump_menu
				foreach ($grupos as $g) {
					$tmp_grupos[$g->get_id()] = $g->get_nome();
					asort($tmp_grupos);
				}
		}

		?>
			<form id="novo_grupo_cargo_form" action="salvar_grupo.php" method="post" >
				<input type="hidden" id='id_grupo_default' value='<?php echo $grupo_default;?>' />
				<div><span>Grupo:</span>
					<?php echo parent::display_jump_menu($tmp_grupos, 'usuario_id_grupo', $grupo_default, '-- Grupos --', 'Usuarios.onSelectGrupoLotacao();' );?><?php parent::display_label_advertencia('id_label_grupo')?>
					<label id="lbl_grupo" class="advertencia"></label>
				</div>
				<div>
					<label id="select_cargo_lotacao">
						<?php echo TUsuarios::display_lotacao_select_cargos($cargos, $cargo_default);?>
					</label>
					<?php echo parent::display_label_advertencia('id_label_cargo');?>
					<label id="lbl_cargo" class="advertencia"></label>
				</div>
				<div id="separador">
	                <span><input type="button" id="save_grupo_user" class="btn" onclick="Usuarios.salvarLotacao(this<?php echo ($editando ? ", {$grupo->get_id()}, {$cargo->get_id()}" : "");?>);" value="Salvar" /></span>
			    	<span><input type="button" class="btn" onclick="window.closePopup(this);" value="Cancelar" /></span>
				</div>    
			</form>
		<?php
	}
	
	/**
	 * Exibe um jump menu com os cargos passados por parametro
	 * @param $cargos
	 * @param $cargo_default
	 */
	public static function display_lotacao_select_cargos($cargos, $cargo_default = null) {
        $tmp_cargos = array();
		// prepara o array de cargos para ser usado com jump_menu
		foreach ($cargos as $c) {
			$tmp_cargos[$c->get_id()] = $c->get_nome();
		}
        ?>
            <span>Cargo:</span>
            <?php echo parent::display_jump_menu($tmp_cargos, 'usuario_id_cargo', $cargo_default, '-- Cargos --');?>
            <?php parent::display_label_advertencia('id_label_cargo')?>
        <?php
	}
}

?>

