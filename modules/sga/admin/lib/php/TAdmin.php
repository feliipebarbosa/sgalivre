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
 * Classe TAdministracao
 *
 * responsavel pela estrutura HTML do modulo Configuracao
 *
 */
class TAdmin extends Template {

	/**
	 * Método para importar arquivos de css e js
	 * @param $title
	 */
	public static function display_header($title='') {
		$tema = parent::get_tema();
		$mod_dir = Session::getInstance()->get(SGA::K_CURRENT_MODULE)->get_dir();
		$misc = '<link rel="stylesheet" href="themes/'. $tema->get_dir() .'/css/admin.css" type="text/css" />' . "\n";
		$misc .= '<script type="text/javascript" src="modules/'.$mod_dir.'/lib/js/admin.js"></script>' . "\n";
		$misc .= '<script type="text/javascript">
                        var adm = new Admin();
                        SGA.addOnLoadListener(adm.refresh);
		          </script>';
		parent::display_header($title, $misc);
	}

	/**
	 * Constrói tela de configurar unidade
	 * @param $user
	 */
	public static function display_administracao() {
		$topo = array('TAdmin', 'display_admin_topo');
		$menu = array('TAdmin', 'display_admin_menu');
		$conteudo = array('TAdmin', 'display_admin_content');
		Template::display_template_padrao($topo, $menu, $conteudo);
	}

	/**
	 * Constrói o topo da tela de configurar unidade
	 */
	public static function display_admin_topo() {
		$modulo = Session::getInstance()->get(SGA::K_CURRENT_MODULE);
		Template::display_topo_padrao($modulo);
	}

	/**
	 * Constrói menus da tela de configurar unidade
	 */
	public static function display_admin_menu() {
		$usuario = SGA::get_current_user();
		Template::display_user_info($usuario);
		Template::display_menu_padrao($modulo, $usuario);
	}

	public static function display_admin_content() {
	}

	/**
	 * Constrói os menus e componentes do Config atendimento de uma unidade
	 */
    public static function display_config_atendimento() {
        Template::display_page_title('Config Atendimento');

        Template::display_menu_button('Reiniciar<br />Senhas', 'themes/sga.default/imgs/edit-clear-history.png', 'Admin.reiniciarSenhas();', 'Armazena todas as senhas da unidade no histórico.');
    }

	/**
	 * Tela para configuação da mensagem local
	 * @param Unidade $unidade
	 */
	public static function config_msg($unidade){
		Template::display_page_title('Mensagem/Impressão Local');

		$msg_local = DB::getInstance()->get_senha_msg_loc($unidade->get_id());
		$msg_global = DB::getInstance()->get_senha_msg_global($unidade->get_id());
		$status_imp = DB::getInstance()->get_msg_status($unidade->get_id());

		if($status_imp ==1){
			$sim = "checked='checked'";
		}else{
			$nao = "checked='checked'";
		}

		?>
		<div>
			<h3>Mensagem padrão - Local</h3>
			Configura a mensagem exibida na senha.
			<div id="alterar_mensagem">
				<div title = "Mensagem que será exibida nas senhas." id="alterar" >
					<label>Mensagem:</label>
					<input id="mensagem" type="text" size="45px;" maxlength="100" value="<?php echo $msg_local;?>" />
					<input id="msg_local" type="hidden" value="<?php echo $msg_local;?>"/>
				</div>
				<div id="botoes_alterar">
					<?php Template::display_action_button("Global", "images/world.png", "Admin.padrao('$msg_global');",'button','',true,'Clique para mostrar a mensagem global.')?>
					<?php Template::display_action_button("Salvar", "images/tick.png", "Admin.alteraMsg()",'button','',true,'Clique para salvar a mensagem.')?>
					<?php Template::display_action_button("Cancelar", "images/cross.png", "Admin.local('$msg_local');",'button','',true,'Clique para cancelar as informações.')?>
				</div>
			</div>
		</div>
		<div>
			<h3>Impressão</h3>
			Ativar impressão da senha.
			<div id="alterar_ativa_imp">
				<div title="Selecione Sim para ativar ou Não para desativar a impressão das senhas." >
					<input type="radio" id="bt_sim" name="confirm_opcao" value="sim" <?php echo $sim;?>/>
					<label for ="bt_sim">Sim</label>
					<input type="radio" id="bt_nao" name="confirm_opcao" value="nao" <?php echo $nao;?>/>
					<label for="bt_nao">Não</label>
					<?php Template::display_action_button("Salvar", "images/tick.png", "window.showYesNoDialog('Admin.alteraImp()','Deseja confirmar a configuração da impressão de senha?','Confirmar Alteração');",'button','',true,'Clique para confirmar a escolha.')?>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Exibe o popup para gerenciar serviços
	 * @param $servicos array
	 */
	public static function exibir_gerenciar_servicos($servicos) {
		Template::display_page_title('Serviços');
		?>
			<div id="gerenciar_servicos">
				<h4>Gerenciar Serviços</h4>
				<div>
					<form id="gs_frm">
						<?php
							$mensagem = 'DESEJA REALMENTE REMOVER O SERVIÇO DA UNIDADE?';
							$titulo = 'REMOVER SERVIÇO';
							foreach($servicos as $s){
								$s_id = $s->get_id();
								$ativo = ($s->get_status()==Servico::SERVICO_ATIVO);
								?>
									<div id="gs">
										<div id="gs_servico">
											<?php /*<input type="hidden" id="id_id_serv_<?php echo $s_id;?>" value="<?php echo $s_id;?>">*/?>
											<input title="Este controle estará marcado se o serviço estiver ativo na unidade." id="gs_label_nm_serv<?php echo $cont?>" name="gs_label_nm_serv" value="<?php echo $s->get_nome();?>" type="checkbox" disabled="disabled" <?php if($ativo){echo 'checked="checked"';};?> id="id_checkbox_<?php echo $s_id;?>" />
											<label title="Código - Nome Local do serviço" for="id_checkbox_<?php echo $s_id;?>"><?php echo $s_id.' - '.$s->get_nome();?></label>
										</div>
										<div id="gs_botoes">
											<input title="Sigla local do serviço." type="text" disabled="disabled" class="sigla" id="id_text_sigla_<?php echo $s_id;?>" value="<?php echo $s->get_sigla();?>" />
											<?php /*<input type="text" id="id_text">*/?>
											<input title="Clique para alterar o serviço" type="button" onclick="Admin.alterarServ(<?php echo $s_id.",'".$s->get_nome()."','".$s->get_sigla()."','".$s->get_status();?>');" value="Alterar"/>
											<input title="Clique para remover o serviço da unidade" type="button" onclick="window.showYesNoDialog('Admin.removerServicoUni(<?php echo $s_id;?>)','<?php echo $mensagem;?>','<?php echo $titulo;?>');" value="Excluir"/>
										</div>
									</div>
								<?php
							}
						?>
					</form>
					<div id="gs_novo_servico">
						<?php Template::display_action_button("Novo Serviço", "images/insert.png", "Admin.novoServico();",'button','',true,'Clique para adicionar um serviço à unidade.')?>
					</div>
	        	</div>
        	</div>
	    <?php
	}


	/**
	 *
	 * Exibe o popup para criar um novo servico
	 * @param $servicos array
	 * @param $id_serv
	 * @param $nome_serv
	 * @param $sigla_serv
	 * @param $status_serv
	 */
	public static function exibir_novo_servico($servicos,$id_serv,$nome_serv="Digite o nome do novo servico",$sigla_serv='',$status_serv) {
		$checkbox = "";
		$editando =  $sigla_serv != '';
		$escondeCria = "";
		$escondeEdit = "";
		$nome_orig = "";
		$disabled = "";
		$title_nome = "Nome local do serviço que será exibido nos módulos Triagem, Monitor, Atendimento e Usuários";
		if ($editando) {
			$macro = "Subserviço";
			$disabled = 'disabled="true"';
			$escondeCria = "display: none;";
			if ($status_serv == Servico::SERVICO_ATIVO){
				$checkbox = "checked='checked'";
			}
			$servico = DB::getInstance()->get_servico($id_serv);
			if ($servico->get_mestre()==null){
				$macro = "Macrosserviço";
			}
			$nome_orig = $servico->get_nome();
		}else{
			$nome_serv = current($servicos);
			$escondeEdit = "display: none;";
			$checkbox = "checked='checked'";
		}
		if (sizeof($servicos) > 0) {
		?>
				<div>
					<form id="id_form_novo_servico" onsubmit="Admin.criar(this,'<?php echo $stat_serv_macro;?>'); return false;" >
						<div style="<?php echo $escondeCria;?>">
							<div id="servicos_uni">
								<div>Macrosserviços:</div>
								<label id="label_servicos">
									<?php echo  parent::display_jump_menu($servicos,'id_select_novo_servico','', '','Admin.preencheInput();',0,'',"'Selecione o serviço que deve ser adicionado à unidade.'", $disabled);?>
								</label>
							</div>
							<input type="button" name="Especializar subserviço" value="Especializar subserviço" id="btn_especializarServ" title="Especializar subserviço significa desmembrar um subserviço do macro, criando uma fila de atendimento exclusiva. Será visível nos demais módulos do sistema como um serviço normal, mas único." onclick="Admin.especializarServ();"></input>
						</div>
						<div  style="<?php echo $escondeEdit;?>">
							<label id="tip_serv" for="id_tipo" title="Define se é macro ou subserviço">Tipo: <?php echo $macro;?></label>
							<label id="cod_serv" for="id_cod" title="Código do Serviço">Código: <?php echo $id_serv;?></label>
							<label id="nm_serv"for="id_nm_orig" title="Nome global do serviço">Nome Global: <?php echo $nome_orig;?></label>
						</div>
						<div title="Nome do serviço" style="margin-top: 3px; clear: both;">
							<label for="id_text_novo" title="<?php echo $title_nome;?>">Nome </label>
							<label style="<?php echo $escondeEdit;?>">Local: </label>
							<input type="text" id="id_text_novo" name="id_text_novo" title="<?php echo $title_nome;?>" maxlength="50" class="nome" value="<?php echo $nome_serv;?>" /><?php parent::display_label_advertencia('id_label_novo_nm_serv')?>
							<label id="id_nome_servico"> </label>
							<input type="checkbox" <?php echo $checkbox;?> title="Marque para ativar o serviço" id="id_checkbox_novo" />
							<label for="id_checkbox_novo" title="Marque para ativar o serviço">Ativo</label>
							<div id="serv_exist"></div>
						</div>
						<div title="Sigla do serviço" >
							<label for="id_text_sigla">Sigla:</label>
							<input type="text" maxlength=1 class="sigla" value='<?php echo $sigla_serv;?>' id="id_text_sigla" name="id_text_sigla" />
							<?php parent::display_label_advertencia('id_label_novo_sigla_serv')?>
						</div>
						<span>
							<input title="Clique para confirmar o serviço." id="ok" style="margin-top: 2px" type="submit" value="Confirmar" />
				    		<input title="Clique para cancelar a operação e fechar a janela." id="cancelarNovoServ" style="margin-top: 4px" type="button" onclick="window.closePopup(this);" value="Cancelar" />
				    	</span>
		        	</form>
	        	</div>
		    <?php
		}else{
			?>
				<div>Não há nenhum serviço a ser adicionado.</div>
				<div>
					<input title="Clique para fechar a janela." id="ok" type="button" onclick="window.closePopup(this);" value="OK" />
	        	</div>
			<?php
		}
	}

	/**
	 * Constrói uma lista com subserviços ou macrosserviços
	 * @param servicos - Array com os serviços
	 * @param tpServico - O tipo dos serviços
	 */
	public static function exibir_servicos($servicos,$tpServico){
		?>
		<div><?php echo $tpServico;?></div>
		<div>
			<label id="label_servicos">
				<?php echo Template::display_jump_menu($servicos, 'id_select_novo_servico', '', '', 'Admin.preencheInput();',0,'',"'Selecione o subserviço que deve ser adicionado à unidade.'");?>
			</label>
		</div>
		<?php
	}

	/**
	 * Janela para confirmação da ação de desativação de serviços.
	 * @param $id_uni
	 * @param $id_serv
	 * @param $id_loc
	 * @param $nome_novo_serv
	 * @param $sigla
	 * @param $status_serv
	 * @return unknown_type
	 */
	public static function display_confirm_desativar_serv($id_uni,$id_serv,$id_loc,$nome_novo_serv,$sigla,$status_serv){
		?>
			<div id="desativar_serv">
			    <h3 class="remov_grup">Atenção</h3>
			    <div id="txt_remov_grup"><?php echo $msg;?>
		    	<br><br>Ao desativar este serviço, as senhas ainda não atendidas serão perdidas.<br>
		    	</div>
				<div id="botoes_remov">
				    <input id="okbutton" type="button" onclick="Admin.confirmado_salvar_servico($id_uni,$id_serv,$id_loc,$nome_novo_serv,$sigla,$status_serv)" value="Confirmar" />
				    <input id="cancelbutton" type="button" onclick="window.closePopup(this);" value="Cancelar" />
				</div>
			</div>
		<?php
	}
}

?>
