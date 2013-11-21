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
 * Classe TConfiguracao
 *
 * responsavel pela estrutura HTML do modulo Configuracao
 *
 */
class TConfiguracao extends Template {

	/**
	 * Método para importar arquivos de css e js
	 * @param $title
	 *
	 */
	public static function display_header($title='') {
		$tema = parent::get_tema();
		$mod_dir = Session::getInstance()->get(SGA::K_CURRENT_MODULE)->get_dir();
		$misc = '<link rel="stylesheet" href="themes/'. $tema->get_dir() .'/css/configuracao.css" type="text/css" />' . "\n";
		$misc .= '<script type="text/javascript" src="modules/'.$mod_dir.'/lib/js/configuracao.js"></script>' . "\n";
		$misc .= '<script type="text/javascript">
			                var conf = new Configuracao();
			                SGA.addOnLoadListener(conf.refresh);
		          </script>';
		parent::display_header($title, $misc);
	}

	/**
	 * Constrói a tela do módulo configuação global
	 * @param $user
	 *
	 */
	public static function display_configuracao($user) {
		$topo = array('TConfiguracao', 'display_config_topo');
		$menu = array('TConfiguracao', 'display_config_menu');
		$conteudo = array('TConfiguracao', 'display_config_content');
		Template::display_template_padrao($topo, $menu, $conteudo);
	}

	/**
	 * Constrói o topo da tela de configuação global
	 */
	public static function display_config_topo() {
		$modulo = Session::getInstance()->get(SGA::K_CURRENT_MODULE);
		Template::display_topo_padrao($modulo);
	}

	/**
	 * Constrói menus da tela de configuação global
	 *
	 */
	public static function display_config_menu() {
		$usuario = SGA::get_current_user();
		Template::display_user_info($usuario);
		Template::display_menu_padrao($modulo, $usuario);
	}

	/**
	 *
	 *
	 */
	public static function display_config_content() {
	}

	/**
	 * Exibe a tela de gerenciamento de grupos no Config. global
	 *
	 */
	public static function display_group_control() {
		Template::display_page_title('Grupos');
		?>
			<div id="config_groups_list">
				<?php echo TConfiguracao::display_group_control_content();?>
			</div>
		<?php
	}

    /**
     * Exibe a tela do Config. atendimento
     */
    public static function display_config_atendimento() {
        Template::display_page_title('Config Atendimento');

        Template::display_menu_button('Reiniciar<br />Senhas', 'themes/sga.default/imgs/edit-clear-history.png', 'Configuracao.reiniciarSenhas();', "Armazena todas as senhas de todas unidades no histórico.");
    }

    /*
     *
     */
    public static function display_config_atendimento_vars() {
        ?>
        <div id="atendimento_vars">

        </div>
        <?php
    }

    public static function display_atendimento_var() {
        ?>

        <?php
    }

	/**
	 * Conteudo da tela de configuracao dos grupos
	 *
	 */
	public function display_group_control_content() {
        $raiz = DB::getInstance()->get_arvore_grupos();
		?>
		<div class="botoes">
			<?php Template::display_action_button("Novo", "images/insert.png", "Configuracao.novoGrupo();",'button', 'btn_novo_grupo',true,'Clique para criar um grupo.')?>
			<?php Template::display_action_button("Editar", "images/edit.png", "Configuracao.editarGrupo();",'button', 'btn_editar_grupo',true,'Clique para editar um grupo.')?>
			<?php Template::display_action_button("Remover", "images/cross.png","Configuracao.verificaSelectRemover();",'button', 'btn_remover_grupo',true,'Clique para remover um grupo.')?>
		</div>
        <div title="Grupo" class="lista">
            <ul id="lista_grupos"  class="arvore_grupos">
                <?php
				  	$unidades = DB::getInstance()->get_unidades();
			        $tmp = array();
			        foreach ($unidades as $uni){
			        	$tmp[$uni->get_grupo()->get_id()] = $uni->get_nome();
			        }
                    TConfiguracao::display_item_arvore_grupo($raiz, $tmp);

                ?>
            </ul>
		</div>
		<?php
	}



	/**
	 * Constrói árvore dos grupos
     * @param $grupo
     * @param array $tmp
     * array com a chave do id_grupo da Unidade valor com nome da Unidade
	 */
    public static function display_item_arvore_grupo(Grupo $grupo, $tmp) {
        $possui_filhos = sizeof($grupo->get_filhos()) > 0;
        ?>
            <li><span id="span_grupo_<?php echo $grupo->get_id();?>" class="<?php echo ($possui_filhos ? "item_grupo_pai" : "item_grupo_filho");?>"><a href="javascript:Configuracao.selectGrupo(<?php echo $grupo->get_id();?>, '<?php echo $grupo->get_nome();?>');"><?php echo $grupo->get_nome();?></a></span>
                <?php
                    if ($possui_filhos) {
                        ?>
                        <ul>
                            <?php
                                foreach ($grupo->get_filhos() as $filho) {
                                    TConfiguracao::display_item_arvore_grupo($filho,$tmp);
                                }
                            ?>
                        </ul>
                        <?php
                    }else if (array_key_exists($grupo->get_id(),$tmp)){
                    	?><span title="Unidade" id="span_unid_grupo<?php echo $grupo->get_id();?>" class="item_unid_grupo"><a><?php echo $tmp[$grupo->get_id()];?></a></span><?php
                    }
                ?>
            </li>
        <?php
    }


    /**
     * Tag que mostra imagem específica de acordo com id_grupo passado
	 * @param $id_grupo
	 *
     */
	public function display_group_control_image($id_grupo) {
		?>
			<img src="?redir=modules/sga/configuracao/grupos/img_grupo.php?id_grupo=<?php echo $id_grupo;?>" />
		<?php
	}

	/**
	 * Popup quando solicitado remoção de grupo
	 * @param $grupos
	 *
	 */
	public static function display_confirm_remover_grupo($grupos){
	    ?>
		    <form id = "form_remov_grupo">
		    	<h3 id="id_atencao" class="remov_grup">Atenção</h3>
		    	<div id="txt_remov_grup">Remover um grupo implica na remoção de seus filhos em cascata,
		    							 <br>ou seja, todo grupo filho direto ou indireto do grupo será removido.
	    		<br><br>Os seguintes grupos serão removidos:<br>
		    	</div>
		    	<div id="remov_grup">
		    	<?php
		    	foreach ($grupos as $grupo){
			 	echo '<span>', $grupo, '</span>';
		    	}
				?>
				</div>
				<div id="botoes_remov">
			    	<input id="okbutton" type="button" onclick="Configuracao.removerGrupo();" value="Confirmar" />
			    	<input id="cancelbutton" type="button" onclick="window.closePopup(this);" value="Cancelar" />
				</div>
			</form>
		<?php

	}


	/**
	 * Tela de criar/editar grupo
	 * @param $grupo
	 * @param $id_grupo_pai
	 *
	 */
	public static function display_grupo(Grupo $grupo = null, $id_grupo_pai = 0) {
		if ($grupo != null) {
			$default = $grupo->get_pai()->get_id();
			$nm_grupo = $grupo->get_nome();
			$desc_grupo = $grupo->get_descricao();
			$id_grupo = $grupo->get_id();
			$acao = "Editar";

			// pega somente os grupos que podem ser pai deste grupo
			$tmp = DB::getInstance()->get_grupos_candidatos_pai($id_grupo);
		}
		else {
			$tmp = DB::getInstance()->get_grupos();
			$acao = "Criar";
			$default = $id_grupo_pai;
		}

		$grupos = array();
		foreach ($tmp as $g) {
			$grupos[$g->get_id()] = $g->get_nome()."";
		}
		?>
		<div id="novo_grupo">
			<form id="novo_grupo_form" action="salvar_grupo.php" method="post" onsubmit="Configuracao.salvarGrupo(); return false;">
				<?php
					// o grupo raiz nao deve mostrar o seletor de pai
					if (count($grupos) > 0) {
						?>
							<div title="Selecione o grupo superior"><span>Superior:</span>
							<?php
								echo parent::display_jump_menu($grupos,'id_grupo_pai', $default, '');
							?>
							</div>
						<?php
					}
				?>
				<div title="Nome do grupo." ><span>Nome:</span>
					<input type="text" id="nm_grupo" name="nm_grupo" value="<?php echo $nm_grupo;?>" /><?php parent::display_label_advertencia('id_label_nm_grupo')?>
				</div>
				<?php
					if ($grupo != null) {
						?>
							<input type="hidden" id="id_grupo" name="id_grupo" value="<?php echo $id_grupo;?>" />
						<?php
					}
				?>
				<div title="Descrição do grupo."><span>Descri&ccedil;&atilde;o:</span>
					<textarea id="desc_grupo" name="desc_grupo" rows="4" cols="40"><?php echo $desc_grupo;?></textarea>
				</div>
				<span>
					<input id="btn_save" title="Clique para salvar." type="submit" class="btn" value="<?php echo $acao;?>" />
				    <input id="btn_cancel" title="Clique para fechar a janela" type="button" class="btn" onclick="window.closePopup(this);" value="Cancelar" />
			    </span>
			</form>
		</div>
		<?php
	}

	/**
	 * Constrói tela de configuração dos cargos
	 *
	 */
	public static function display_cargos_config() {
		Template::display_page_title('Cargos');
		?>
			<div id="config_groups_list">
				<?php echo TConfiguracao::display_cargos_config_content();?>
			</div>
		<?php
	}

	/**
	 * Conteudo da tela de configuração dos cargos
     *
	 */
    public function display_cargos_config_content() {
		?>
            <div class="botoes">
            	<?php Template::display_action_button("Novo", "images/insert.png", "Configuracao.novoCargo();",'button','',true,'Clique para criar um grupo.')?>
            	<?php Template::display_action_button("Editar", "images/edit.png", "Configuracao.editarCargo();",'button','btn_editar_cargo',true,'Clique para editar um grupo.')?>
            	<?php Template::display_action_button("Remover", "images/cross.png", "Configuracao.removerCargo();",'button','btn_remover_cargo',true,'Clique para remover um grupo.')?>
			</div>
            <div title="Lista de grupos." id='ajax_select_cargos_list' class="lista">
                <?php TConfiguracao::display_arvore_cargos();?>
            </div>
		<?php
	}

	/**
	 * Constrói arvore dos cargos
     *
	 */
    public static function display_arvore_cargos() {
        $raiz = DB::getInstance()->get_arvore_cargos();
        ?>
        <ul id="lista_cargos"  class="arvore_cargos">
            <?php
            TConfiguracao::display_item_arvore_cargo($raiz);
            ?>
        </ul>
        <?php
    }

    /**
     * Conteudo da árvore dos cargos
     * @param $cargo
     *
     */
    public static function display_item_arvore_cargo(Cargo $cargo) {
        $possui_filhos = sizeof($cargo->get_filhos()) > 0;
        ?>
            <li><span id="span_cargo_<?php echo $cargo->get_id();?>" class="<?php echo ($possui_filhos ? "item_cargo_pai" : "item_cargo_filho");?>"><a href="javascript:Configuracao.selectCargo(<?php echo $cargo->get_id();?>, '<?php echo $cargo->get_nome();?>');"><?php echo $cargo->get_nome();?></a></span>
                <?php
                    if ($possui_filhos) {
                        ?>
                        <ul>
                            <?php
                                foreach ($cargo->get_filhos() as $filho) {
                                    TConfiguracao::display_item_arvore_cargo($filho);
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
     * Tela de criar/editar cargo
	 * @param $cargo
	 * @param $id_cargo_pai
	 *
     */
	public function display_view_cargo(Cargo $cargo = null, $id_cargo_pai = 0) {
		if ($cargo == null) {
			$acao = "Criar";
			$nm_cargo = "";
			$desc_cargo = "";
			$default = $id_cargo_pai;

			$tmp = DB::getInstance()->get_cargos();
		}
		else {
			$acao = "Editar";
			$nm_cargo = $cargo->get_nome();
			$desc_cargo = $cargo->get_descricao();
			$id_cargo = $cargo->get_id();

			if (!$cargo->is_raiz()) {
				$default = $cargo->get_pai()->get_id();
			}

			// pega somente os grupos que podem ser pai deste grupo
			$tmp = DB::getInstance()->get_cargos_candidatos_pai($id_cargo);
		}

		$cargos = array();
		foreach ($tmp as $c) {
			$cargos[$c->get_id()] = $c->get_nome()."";
		}
		?>
            <div id="view_cargo">
            	<form id="frm_view_cargo" method="post" action="" onsubmit="Configuracao.salvarCargo(); return false;">
				<?php
					// o cargo raiz nao deve mostrar o seletor de cargo superior
					if (count($cargos) > 0) {
						?>
							<div title="Selecione o cargo superior"><span>Superior:</span>
							<?php
								echo parent::display_jump_menu($cargos,'id_cargo_pai', $default, '');
							?>
							</div>
						<?php
					}
				?>
				<div title="Nome do cargo.">
					<span>Nome:</span>
					<input type="text" id="nm_cargo" name="nm_cargo" value="<?php echo $nm_cargo;?>" /><?php parent::display_label_advertencia('id_label_nm_cargo')?>
					<?php
						if ($cargo != null) {
							?>
								<input type="hidden" id="id_cargo" name="id_cargo" value="<?php echo $id_cargo;?>" />
							<?php
						}
					?>
				</div>
				<div title="Descrição do cargo."><span>Descri&ccedil;&atilde;o:</span>
					<textarea id="desc_cargo" name="desc_cargo" rows="4" cols="40"><?php echo $desc_cargo;?></textarea>
				</div>
				<div id="adverte_modulos"></div>
				<div title="Selecione o(s) modulo(s) ao(s) qual(is) o grupo terá acesso">
					<?php
						$modulos = DB::getInstance()->get_modulos(array (Modulo::MODULO_ATIVO)) ;
						if($modulos) {
							foreach ($modulos as $m) {
								$check = '';
								if($acao == 'Editar'){
									$check = ($cargo->has_permissao($m)) ? 'checked="checked"' : '';
								}
								$disabled = '';
								if($m->get_chave()=='sga.inicio'){
									$disabled = 'disabled="disabled"';
									$check = 'checked="checked"';
								}
								?>
									<div id='modulos'>
										<input type="checkbox" name="cargo_modulos[]" <?php echo $check;?> id="mod_<?php echo $m->get_id();?>" value="<?php echo $m->get_id();?>" <?php echo $disabled;?>/>
										<label for="mod_<?php echo $m->get_id();?>"><?php echo $m->get_nome();?> </label>
									</div>
								<?php
							}
						}else{
							?>
								<div id='modulos'>Não há módulos.</div>
							<?php

						}
					?>
				</div>
				<span>
					<input id="btn_save" title="Clique para salvar." type="submit" class="btn" value="<?php echo $acao;?>" />
				    <input id="btn_cancel" title="Clique para fechar a janela." type="button" class="btn" onclick="window.closePopup(this);" value="Cancelar" />
				</span>
		        </form>
			</div>
		<?php
	}

	/**
	 * Tela de configuração dos serviços
	 *
	 */
	public static function display_servicos_config() {
		Template::display_page_title('Serviços');
		?>
			<div id="serv_config">
				<span id="serv_config_macro">
					<?php echo TConfiguracao::display_macro_serv_control();?>
				</span>
				<span id="serv_config_sub">
					<?php echo TConfiguracao::display_sub_serv_control();?>
				</span>
			</div>
		<?php
	}

	/**
	 * Constrói parte da configuração dos macrosserviços
	 *
	 */
	public static function display_macro_serv_control() {
		?>
			<div id="macro_serv">
				<?php echo TConfiguracao::display_macro_serv_content();?>
			</div>
		<?php
	}

	/**
	 * Conteudo da configuração dos macrosserviços
	 *
	 */
	public function display_macro_serv_content() {
		$servicos = array();
		foreach (DB::getInstance()->get_servicos_mestre() as $macro_serv) {
			$servicos[$macro_serv->get_id()] = $macro_serv;
		}
		?>
			<h3>Macrosserviços:</h3>
			<div title="Lista de macro serviços." class="lista">
            	<?php echo TConfiguracao::display_jump_servicos($servicos, "macro_serv_list", "", null, "Configuracao.onSelecionaMacro();", 20,"","", "multiple");?>
            </div>
            <div class="botoes">
            	<?php Template::display_action_button("Novo", "images/insert.png", "Configuracao.novoMacroServico();",'button','',true,'Clique para criar um macrosserviço.')?>
            	<?php Template::display_action_button("Editar", "images/edit.png", "Configuracao.editarMacroServico();",'button','',true,'Clique para editar um macrosserviço.')?>
            	<?php Template::display_action_button("Remover", "images/cross.png", "Configuracao.removerMacroServico();",'button','',true,'Clique para remover um macrosserviço.')?>
	        </div>
		<?php
	}

	/**
	 * Conteudo das configurações dos subserviços
	 *
	 */
	public static function display_sub_serv_control() {
		?>
			<div id="sub_serv">
				<h3>Subserviços:</h3>
				<div title="Lista de subserviços." class="lista">
					<div id="sub_serv_content">
						<?php echo TConfiguracao::display_sub_serv_content();?>
					</div>
				</div>
				<div class="botoes">
					<?php Template::display_action_button("Novo", "images/insert.png", "Configuracao.novoSubServico();",'button','',true,'Clique para criar um subserviço.')?>
	            	<?php Template::display_action_button("Editar", "images/edit.png", "Configuracao.editarSubServico();",'button','',true,'Clique para editar um subserviço.')?>
	            	<?php Template::display_action_button("Remover", "images/cross.png", "Configuracao.removerSubServico();",'button','',true,'Clique para remover um subserviço.')?>
		        </div>
			</div>

		<?php
	}

	/**
	 * Lista dos subserviços
	 * @param $id_macro
	 */
	public function display_sub_serv_content($id_macro = null) {
		if ($id_macro == null) {
			$label = "- Selecione um macrosserviço - ";
		}
		else {
			$sub_servicos = array();
			foreach (DB::getInstance()->get_servicos_sub($id_macro) as $s) {
				$sub_servicos[$s->get_id()] = $s;
			}
		}
		echo TConfiguracao::display_jump_servicos($sub_servicos, "sub_serv_list", "", $label, "", 20);
	}

	/**
	 * Tela de criar/editar macrosserviços
	 * @param $servico
	 */
	public static function display_view_macro_serv(Servico $servico = null) {
		if ($servico == null) {
			$acao = "Criar";
			$desc_serv = "";
			$nm_serv = "";
            $stat_serv = 1;
		}
		else {
			$acao = "Editar";
			$nm_serv = $servico->get_nome();
			$desc_serv = $servico->get_descricao();
			$id_serv = $servico->get_id();
            $stat_serv = $servico->get_status();
		}
		//teste
		$servicos = array();
		$idServicos = array();
		foreach (DB::getInstance()->get_servicos_mestre() as $macro_serv) {
			$servicos[$macro_serv->get_id()] = $macro_serv->get_nome()."";
			$idServicos[$macro_serv->get_id()] = $macro_serv->get_id()."";
		}
		$array_serv = implode(',',$servicos);
		$array_id   = implode(',',$idServicos);
		?>
            <div id="view_servico" >
            	<form id="frm_view_servico" method="post" action="" onsubmit="Configuracao.salvarMacroServico('<?php echo $acao;?>','<?php echo $array_serv;?>','<?php echo$array_id;?>'); return false;">
            	<div title="Nome do macrosserviço." ><span>Nome:</span>
                    <input type="text" id="nm_serv" name="nm_serv" maxlength="50" value="<?php echo $nm_serv;?>" /><?php parent::display_label_advertencia('id_label_nm_macro'); ?>
                </div>
                <div title="Status do macrosserviço." ><span>Ativo:</span>
						<input type="checkbox" id="stat_serv" name="stat_serv" maxlength="50" value="1" <?php echo ($stat_serv ? 'checked="true"' : "");?> /><label id="id_label_nm_sub" class='advertencia'></label>
						<input type="hidden" id="old_status" value="<?php echo $stat_serv;?>"/>
				</div>
            	<div title="Descrição do macrosserviço">
					<span>Descrição:<?php parent::display_label_advertencia('id_label_desc_macro'); ?></span>
					<textarea id="desc_serv" name="desc_serv" rows="4" cols="40"><?php echo $desc_serv;?></textarea>
                    <?php
						if ($servico != null) {
							?>
								<input type="hidden" id="id_serv" name="id_serv" value="<?php echo $id_serv;?>" />
							<?php
						}
					?>
				</div>
				<div id="btn">
					<span>
						<input title="Clique para salvar." type= "submit" class="btn" value="<?php echo $acao;?>" />
					    <input title="Clique para fechar a janela." type="button" class="btn" onclick="window.closePopup(this);" value="Cancelar" />
					</span>
		        </div>
		        </form>
			</div>
		<?php
	}

	/**
	 * Tela de criar/editar subserviços
	 * @param $servico
	 * @param $id_macro_atual
	 *
	 */
	public static function display_view_sub_serv(Servico $servico = null,$id_macro_atual) {
		$macros = array();
		$macros_status = array();
		$macros_ids = array();
		foreach (DB::getInstance()->get_servicos_mestre() as $s) {
			$macros_status[] = $s->get_status(). ',';
			$macros_ids[] = $s->get_id().',';
			$macros[$s->get_id()] = $s->get_nome();
		}
		$macros_ids = implode(',', $macros_ids);
		$macros_status = implode(',', $macros_status);

		if ($servico == null) {
			$acao = "Criar";
			$nm_serv = "";
			$desc_serv = "";
            $stat_serv = 1;
		}
		else {
			$acao = "Editar";
			$desc_serv = $servico->get_descricao();
			$nm_serv = $servico->get_nome();
			$id_serv = $servico->get_id();
			$id_macro_atual = $servico->get_mestre();
            $stat_serv = $servico->get_status();
		}
		?>
            <div id="view_servico">
            	<form id="frm_view_servico" method="post" action="" onsubmit="Configuracao.salvarSubServico('<?php echo $macros_status;?>','<?php echo $macros_ids?>'); return false;">
					<div>
						<span>Macro:</span>
						<?php echo Template::display_jump_menu($macros, 'id_macro', $id_macro_atual, '- Selecione -','',0,'','Selecione o macrosserviço.');?> <?php parent::display_label_advertencia('label_macro', 'advertencia'); ?>
					</div>
					<div title="Nome do subserviço." ><span>Nome:</span>
						<input type="text" id="nm_serv" name="nm_serv" maxlength="50" value="<?php echo $nm_serv;?>" /><label id="id_label_nm_sub" class='advertencia'></label>
					</div>
                    <div title="Status do Subserviço." ><span>Ativo:</span>
						<input type="checkbox" id="stat_serv" name="stat_serv" maxlength="50" value="1" <?php echo ($stat_serv ? 'checked="true"' : "");?> /><label id="id_label_nm_sub" class='advertencia'></label>
						<input type="hidden" id="old_status" value="<?php echo $stat_serv;?>"/>
					</div>
					<div title="Descrição do subserviço." ><span>Descrição:<label id="id_label_desc_sub" class='advertencia'></label></span>
						<textarea id="desc_serv" name="desc_serv" rows="4" cols="40"><?php echo $desc_serv;?></textarea>
						<?php
							if ($servico != null) {
								?>
									<input type="hidden" id="id_serv" name="id_serv" value="<?php echo $id_serv;?>" />
								<?php
							}
						?>
					</div>
					<span>
						<input title="Clique para salvar." type="submit" class="btn" value="<?php echo $acao;?>" />
					    <input title="Clique para fechar a janela." type="button" class="btn" onclick="window.closePopup(this);" value="Cancelar" />
					</span>
		        </form>
			</div>
		<?php
	}

	// UNIDADES

	/**
	 * Constrói tela de configuração de unidades
	 *
	 */
	public static function display_unidades_config() {
		Template::display_page_title('Unidades');
		?>
			<div id="config_uni">
				<div id="config_uni_search">
				    <?php
				    	TConfiguracao::displayer_unidades_search();
				    ?>
			    </div>
			    <div class='config_uni_info'  id="config_uni_info" > </div>
			</div>
		<?php
	}

	/**
	 * Conteudo da tela de configuração de unidades
	 *
	 */
	public static function displayer_unidades_search() {
		$tmp['codigo'] = 'Por Código';
		$tmp['nome'] = 'Por Nome';
		?>
			<div id="criar_unidade">
				<?php Template::display_action_button("Criar Unidade", "images/insert.png", "Configuracao.criarUnidade();",'button','',true,'Clique para CRIAR uma unidade.')?>
			</div>
			<div>
				<input title="Digite o termo de busca." type="text" id="search_input" maxlength="50" name="search_input" onkeypress="return SGA.txtBoxAlfaNumerico(this,event,'id_buscar_uni','id_buscar_uni');"/>
				<?php Template::display_action_button("Buscar", "images/zoom.gif", "Configuracao.buscarUnidade('search_input','search_type');",'button','id_buscar_uni',true,'Clique para BUSCAR uma unidade. se deixar o campo em branco todas as unidades, a que você tem acesso, serão mostradas.')?>
				<div><?php echo parent::display_jump_menu($tmp, "search_type", null, null, "",0,'',"'Selecione o modo de busca.'");?></div>
			</div>
			<?php
				echo TConfiguracao::display_resultado_unidades();
	}

	/**
	 * Constói tela de resultado após busa de unidades
	 * @param $result
	 *
	 */
	public static function display_resultado_unidades($result = null) {
		?>
			<div id="conteudo_resultado_unidade">
				<?php
					// usar comparação !== para evitar que arrays vazios (0 encontrados) não sejam exibidos
					if ($result !== null) {
						TConfiguracao::display_resultado_unidades_interno($result);
					}
				?>
			</div>
		<?php
	}

	/**
	 * Tela de resultado após busca de unidades
	 * @param $result
	 *
	 */
	public static function display_resultado_unidades_interno($result) {
		$list = array();
		foreach ($result as $unidade) {
			$list[$unidade->get_id()] = $unidade;
		}
		?>
			<div><h3><?php echo sizeof($list);?> encontrado(s)</h3></div>
			<div >
				<?php
				if (sizeof($list) > 0) {
					if(sizeof($list) == 1){
						TConfiguracao::display_edit_uni($result[0]);
					}else{
						echo TConfiguracao::display_unidades($list, 'select_resultado_unidades', '', "- Selecione - (Grupo - Código - Nome)", 'Configuracao.onSelecionaUnidade(this);',0,'',"'Selecione um resultado.'");
					}
				}
				?>
			</div>
		<?php
	}

	/**
	 * Constrói select com os grupos
	 * @param $id_tag
	 * @param $disable
	 * @param $id_grupo
	 */
	public static function display_grupos($id_tag, $disable='', Grupo $grupo= null, $max_width="auto;") {
		$tmp = array();
		foreach (DB::getInstance()->get_grupos_folha_disponiveis() as $g) {
			$tmp[$g->get_id()] = $g->get_nome();
		}
		if ($grupo != null){
			$default = $grupo->get_id();
			$tmp[$default] = $grupo->get_nome();
		}else{
			$default = "";
		}
		echo parent::display_jump_menu($tmp, $id_tag, $default,'', '',0,'',"'Selecione um grupo.'",$disable, $max_width);
	}

	/**
	 * Tela prar criar unidade
	 */
	public static function display_nova_unidade() {
		$tmp = array();
		foreach (DB::getInstance()->get_unidades() as $t){
			$tmp[$t->get_id()] = $t->get_codigo();
		}
		$array_uni = implode(',',$tmp);
	    ?>
		   <form id="nova_uni" onsubmit="Configuracao.salvarUnidade(false,'<?php echo $array_uni;?>'); return false;">
	            <div id="cod_criar_uni" title='Código da unidade'><span>Código:</span><input type="text" id="cod_uni_novo" name="cod_uni_novo" maxlength="10"  onkeypress="return SGA.txtBoxAlfaNumerico(this,event);"  /><label class="advertencia" id="id_label_criar_cod"></label></div>
	            <div title="Nome da unidade" ><span>Nome:</span><input type="text" id="nm_uni_novo" name="nm_uni_novo" maxlength="50" /><label class="advertencia" id="id_label_criar_nm"></label></div>
	            <div title="Grupo ao qual a unidade pertence."><span>Grupo:</span><?php echo TConfiguracao::display_grupos('id_grupo_novo'); ?><label class="advertencia" id="id_label_criar_grupo"></label></div>
	            <span class="config_uni_control">
	               <input id="btn_save" title="Clique para salvar a nova unidade." type="submit" class="btn" value="Salvar" />
	               <input id="btn_cancel" title="Clique para fechar a janela." type="button" class="btn" onclick="window.closePopup(this);" value="Cancelar" />
	            </span>
	        </form>
        <?php
	}

	/**
	 * Tela para editar unidade
	 * @param Unidade $unidade
	 *
	 */
	public static function display_edit_uni(Unidade $unidade) {
		$max_width = "167px;margin:5px 0 5px;";
		if($unidade->get_stat_uni() == 1){
			$status = 'Desativar';
		}else{
			$status = 'Ativar';
			$disable = 'disabled="disabled"';
		}

		$unidades = DB::getInstance()->get_unidades();
		foreach ($unidades as $uni){
			$tmp[] = $uni->get_codigo();
		}
		$cod_unidades = implode(",",$tmp);
		?>
		<div id="edit_uni_info">
			<div id="editar_unidade">
				<h1>Editar Unidade</h1>
				<input type="hidden" id="id_uni" name="id_uni" value="<?php echo $unidade->get_id();?>" />
	            <div title='Código da unidade'>
		            <span>Código:</span>
		            <span>
		            	<input type="text" id="cod_uni" name="cod_uni" <?php echo $disable;?> value="<?php echo $unidade->get_codigo();?>" maxlength="10" onkeypress="return SGA.txtBoxAlfaNumerico(this,event);" />
		            	<label class="advertencia" id="id_label_editar_cod" ></label>
		            </span>
	            </div>
                    <div style="clear: both;" />
	            <div id="nome_unidade" title="Nome da unidade" >
	            	<span>Nome:</span>
		            <span>
		            	<input type="text" id="nm_uni" name="nm_uni" maxlength="50" <?php echo $disable;?> value="<?php echo $unidade->get_nome();?>" />
		            	<label class="advertencia" id="id_label_editar_nm"></label>
		            </span>
		        </div>
                    <div style="clear: both;" />
	            <div title="Grupo ao qual a unidade pertence.">
		            <span>Grupo:</span>
		            <span>
		            	<?php echo TConfiguracao::display_grupos('id_grupo',$disable,$unidade->get_grupo(),$max_width);?>
		            	<label class="advertencia"  id="id_label_editar_grupo" ></label>
		            </span>
	            </div>
	            <ul class="config_uni_control">
		            <li><input title="Clique para SALVAR as alterações." type="button"  class="btn" value="Salvar" <?php echo $disable;?> onclick="Configuracao.salvarUnidade(true,'<?php echo $cod_unidades;?>','<?php echo $unidade->get_codigo();?>');" /></li>
	                <li><input title="Clique para CANCELAR as alterações." type="button"  class="btn" value="Cancelar" <?php echo $disable;?> onclick="Configuracao.refreshDebug();" /></li>
	                <li><input title="Clique para <?php echo $status;?> a unidade." type="button"  class="btn" value="<?php echo $status;?>" onclick="Configuracao.modificaStatusUni(<?php echo $unidade->get_id();?>,<?php echo $unidade->get_stat_uni();?>);" /></li>
	            	<li><input title="Clique para EXCLUIR a unidade." type="button"  class="btn" value="Excluir" onclick="Configuracao.removerUni(<?php echo $unidade->get_id();?>);" /></li>
	            </ul>
			</div>
		</div>
		<?php
	}

	/**
	 * Tela de configuração da mensagem global
	 */
	public static function config_msg(){
		Template::display_page_title('Mensagem Global');
		$msg = DB::getInstance()->get_senha_msg_global();
		?>
		<div>
			Configura a mensagem padrão global exibida na senha.
			<div id="alterar">
				<form id="id_form_alterar" onsubmit="Configuracao.alteraMsg(); return false;">
					<label>Mensagem:</label>
					<input title="Mensagem global." id="mensagem" type="text" size="50" value="<?php echo $msg;?>" />
					<div title="Selecione sim ou não para aplicar ou não a mensagem a todas as unidades." id="id_aplica_todas">
						Aplicar a todas unidades:
						<input type="radio" name="radio_aplicar_msg_todas_unidades" id="id_radio_sim_todas_unidades"  />
						<label for="id_radio_sim_todas_unidades">Sim</label>
						<input type="radio" name="radio_aplicar_msg_todas_unidades" id="id_radio_nao_todas_unidades" checked="checked"/>
						<label for="id_radio_nao_todas_unidades">Não</label>
					</div>
					<div  id="botoes_alterar">
						<?php Template::display_action_button("Salvar", "images/tick.png", "",'submit','',true,'Clique para salvar as alterações.');?>
						<?php Template::display_action_button("Cancelar", "images/cross.png", "Configuracao.padrao('$msg');",'button','',true,'Clique para cancelar as alterações.')?>
					</div>
				</form>
			</div>
		</div>
		<?php
	}

	/**
	 * Monta um Jump Menu atraves do array passado por parametro
	 * Coloca cor vermelha nas unidades desativadas
	 *
	 * @param $array
	 * @param $name
	 * @param $default
	 * @param $label
	 * @param $onchange
	 * @param $size
	 * @param $eventos
	 * @param $title
	 *
	 * @return $jump
	 *
	 */
	static function display_unidades($array, $name='', $default='', $label='', $onchange='', $size=0, $eventos = '', $title='') {
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
			$cor = ($key->get_stat_uni() == 0)?'red': 'black';
			$jump .= '	<option style="color:'.$cor.';" value="'.$key->get_id().'" '.$sel.'>'.$key->get_grupo()->get_nome().' - '.$key->get_codigo().' - '.$key->get_nome().'</option>' . "\n";
		}
		$jump .= "</select>\n";
		return $jump;
	}

	/**
	 * Exibe uma lista de serviços
	 * @param $array
	 * @param $name
	 * @param $default
	 * @param $label
	 * @param $onchange
	 * @param $size
	 * @param $eventos
	 * @param $title
	 * @param $multiple
	 * @return unknown_type
	 */
	static function display_jump_servicos($array, $name='', $default='', $label='', $onchange='', $size=0, $eventos = '', $title='',$multiple = "") {
		$jump = '<select id="'.$name.'" name="'.$name.'" onchange="'.$onchange.' '.$eventos.'" size="'.$size.'" title='.$title.'" "'.$multiple.' >' . "\n";
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
			$jump .= '	<option style="color:'.$cor.';" value="'.$key->get_id().'" '.$sel.'>'.str_pad($key->get_id(), 2, "0", STR_PAD_LEFT).' - '.$key->get_nome().'</option>' . "\n";
		}
		$jump .= "</select>\n";
		return $jump;
	}
}
?>
