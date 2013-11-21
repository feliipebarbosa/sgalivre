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
 * Classe TTriagem
 *
 * responsavel pela estrutura HTML da Triagem
 *
 */
class TTriagem extends Template {

	/**
	 * Método para importar arquivos de css e js
	 * @param $title
	 */
	public static function display_header($title='') {
		SGA::_include("modules/sga/triagem/content_loader.php");
		$tema = parent::get_tema();
		$mod_dir = Session::getInstance()->get(SGA::K_CURRENT_MODULE)->get_dir();
		$misc = '<link rel="stylesheet" href="themes/'. $tema->get_dir() .'/css/triagem.css" type="text/css" />' . "\n";
		$misc .= '<script type="text/javascript" src="modules/'.$mod_dir.'/lib/js/triagem.js"></script>' . "\n";
		$misc .= '<script type="text/javascript">
			            var triagem = new Triagem();
			            SGA.addOnLoadListener(triagem.refresh);
				</script>';
		parent::display_header($title, $misc);
	}

	/**
	 * Constrói tela da triagem
	 * @param $user
	 */
	public static function display_triagem($user) {
		$topo = array('TTriagem', 'display_triagem_topo');
		$menu = array('TTriagem', 'display_triagem_menu');
		$conteudo = array('TTriagem', 'display_triagem_content');
		Template::display_template_padrao($topo, $menu, $conteudo);
	}

	/**
	 * Constrói o topo da tela da triagem
	 */
	public static function display_triagem_topo() {
		$modulo = Session::getInstance()->get(SGA::K_CURRENT_MODULE);
		Template::display_topo_padrao($modulo);
	}

	/**
	 * Constrói menus da tela da triagem
	 */
	public static function display_triagem_menu() {
		$usuario = SGA::get_current_user();
		Template::display_user_info($usuario);
		Template::display_menu_padrao($modulo, $usuario);
	}

	/**
	 *
	 */
	public static function display_triagem_content() {

		TTriagem::display_conteudo();
		SGA::_include("modules/sga/triagem/content_loader.php");

	}

	/**
	 * Mostra conteudo da triagem
	 */
	public static function display_conteudo() {
        $id_uni = SGA::get_current_user()->get_unidade()->get_id();
		$serv = DB::getInstance()->get_servicos_unidade($id_uni, array(1));
		$prioridade = DB::getInstance()->get_prioridades();

		$ultimo = Session::getInstance()->get("ultima_senha");
        $senha = $ultimo == null ? "--" : $ultimo;
			
		Template::display_page_title('Serviços'); ?>

		<div id="conteudo_servicos">
			<div id="atualiza_ult_senha"><?php TTriagem::display_ult_senha($senha);?></div>
			
			
			<div id="nome_ident_desc">Nome e identificação do cliente:</div>
			<label>Nome:</label> <input title="Nome do cliente" type="text" id="client_name" name="client_name" value="" maxlength="100" />
			<label>Ident:</label> <input title="Doc. Identificação do cliente" type="text" id="client_ident" name="client_ident" value="" maxlength="11" />
			<form id="id_prio_sel">
				<div title="Tipos de prioridade" id="prioridade"><?php TTriagem::lista_prioridade($prioridade); ?></div>
			</form>
			<h1 id="titulo_servicos">Servi&ccedil;os</h1>
			<div id="conteudo_triagem"><?php TTriagem::servicos($serv);?></div>
		</div>
		<?php
	}
	
	/**
	 * Mostra a ultima senha
	 * @param $ultimo
	 * @return unknown_type
	 */
	public static function display_ult_senha($ultimo){
		?>
		<h1 id="ultima_senha">&Uacute;ltima Senha:
			<span id="num_senha"><?php echo $ultimo; ?></span>
		</h1>
		<?php
	}

	/**
	 *
	 * @param $id_mestre
	 * @param $id_uni
	 */
	public static function list_sub_serv($id_mestre, $id_uni){
		?>
		<div id="desc_serv_mestre"><?php echo DB::getInstance()->get_servico_unidade($id_mestre,$id_uni)->get_descricao()?></div>
		
		<div id="list_sub_serv"><?php
		$sub_serv = DB::getInstance()->get_servicos_sub($id_mestre);
		if($sub_serv==array()){
			?>Não há subserviços<?php
		}
		?>
		<ul>
		<?php
		if($sub_serv!=array()){
			foreach($sub_serv as $sub) {
				?>
			<li id="li_list_sub"><?php echo $sub->get_sigla();?>-<?php echo $sub->get_nome();?></li>
			<?php
			}
		}
		?>
		</ul>
		<input type="button" onclick="window.closePopup(this);" value="Fechar" />
		</div>
		<?php
	}

	/**
	 * Lista dos servicos, há duas colunas (esquerda e direita) com metade dos serviços em cada uma.
	 * @param $list
	 */
	public static function servicos($list=array())
	{
		$id_uni = SGA::get_current_user()->get_unidade()->get_id();
		$status_imp = DB::getInstance()->get_msg_status($id_uni);
		foreach($list as $servico) {
			$fila = DB::getInstance()->get_quantidade_fila($servico->get_id(),$id_uni);
			$total = DB::getInstance()->get_quantidade_total($servico->get_id(),$id_uni);
			?>
			<div id="servico">
				<div title="Nome do serviço" id="servico_nome">
					<div id="servico_nome_titulo">Serviço</div>
					<div id="servico_nome_conteudo"><?php echo '<a href="javascript:Triagem.subServico('.$servico->get_id().');">'.$servico->get_sigla()." - ".$servico->get_nome().'</a>'; ?></div>
				</div>
				<div id="servico_atendimento">
					<div id="servico_atendimento_titulo">Atendimento</div>
					<div id="servico_atendimento_conteudo">
						<input title="Clique para emitir uma senha sem prioridade" id="bt_normal" class="btn" type="button" value="Normal" onclick="javascript:Triagem.distribuir(<?php echo $servico->get_id();?>, 1,<?php echo $status_imp;?>);" />
						<input title="Clique para emitir uma senha com a prioridade escolhida acima" id="bt_prior" class="btn" type="button" value="Prioridade" onclick="javascript:Triagem.confirmarPrioridade(<?php echo $servico->get_id();?>);" />
					</div>
				</div>
				<div title="Número de clientes na fila" id="servico_fila">
					<div id="servico_fila_titulo">Fila</div>
					<div id="servico_fila_conteudo"><?php echo $fila; ?></div>
				</div>
				<div title="Total de senhas emitidas" id="servico_total">
					<div id="servico_total_titulo">Total</div>
					<div id="servico_total_conteudo"><?php echo $total; ?></div>
				</div>
			</div>
			<?php
		}
			
	}

	/**
	 * Lista das prioridades
	 * @param $list
	 */
	public static function lista_prioridade($list=array())
	{
		foreach($list as $prioridade)
		{
			//Id=1 igual sem prioridades, não é necessário mostrar na tela
			if($prioridade->get_id()!=1) {
				TTriagem::prioridade($prioridade->get_nome(),$prioridade->get_id());
			}
		}
	}

	/**
	 * Botões de seleção das prioridades
	 * @param $nome
	 * @param $id
	 */
	public static function prioridade($nome,$id)
	{
		?>
		<div id="div_prioridade"><input type="radio" id="id_prioridade<?php echo $id?>"
			name="id_prioridade" value="<?php echo $id;?>" checked="checked" /> <label
			for="id_prioridade<?php echo $id?>"><?php echo $nome;?></label></div>
		<?php
	}

	/**
	 * HTML da senha para imprimir
	 * @param $senha
	 * @param $unidade
	 */
	public static function imprime($senha,$unidade)
	{
		$msg = DB::getInstance()->get_senha_msg_loc($unidade->get_id());
		$tema = parent::get_tema();
		?>
		<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en">
		<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
                <link rel="stylesheet" href="lib/js/jquery/theme/jquery-ui-1.7.1.custom.css" type="text/css" />
        <link rel="stylesheet" href="themes/<?php echo $tema->get_dir();?>/css/default.css" type="text/css" />
        <link rel="stylesheet" href="themes/<?php echo $tema->get_dir();?>/css/triagem.css" type="text/css" />
		</head>
		<body onLoad="window.print();window.close();" >
			<div align="center" id="imprime">
				<div align="center" id="imp_unid">
					<?php echo $unidade->get_nome();?> <br />
						<?php echo "Tipo de senha";?>
				</div>
				<div align="center" id="imp_senha">
					<?php echo $senha->get_sigla().$senha->get_full_numero()?>
					<div id="imp_data"><?php echo SGA::get_date("d/m/Y H:i"). $senha->get_prioridade();?></div>
				</div>
				<div align="center" id="imp_frase"><?php echo $msg;?></div>
			</div>
		</body>
		</html>
		<?php
	}

	/**
	 * Exibe o popup para cancelar senhas
	 * @param $servicos array de serviços
	 */
	public static function exibir_cancelar_senha() {
		?>
		<div title="Selecione o tipo de busca.">
			<label for="id_radio_servico">Por Serviço</label> <input type="radio" name="radio_cancelar_senha" id="id_radio_servico" value="servico" onclick="Triagem.onRadioCancelar(this)" /> 
			<label for="id_radio_senha">Por Senha</label> <input type="radio" name="radio_cancelar_senha" id="id_radio_senha" value="senha" onclick="Triagem.onRadioCancelar(this)" />
		</div>
		<div id="id_cancelar_senha"></div>
		<div>
			<input id="confirmar_cancelar_senha" title="Clique para cancelar a senha selecionada" type="button" class="botao_acao" onclick="Triagem.confirmaCancelarSenha(this);" value="Confirmar" />
			<input title="Clique para fechar a janela. A senha não será cancelada" type="button" class="botao_acao" onclick="window.closePopup(this);" value="Cancelar" />
		</div>
		<?php
	}

	/**
	 * Exibe caixa para colocar a senha diretamente à ser cancelada
	 */
	public static function exibir_cancelar_senha_por_senha() {
		?>
		<div id="id_exibir_cancelar_senha_por_senha">
		<form onsubmit="Triagem.procuraSenha(); return false;">
			<label for="id_text_senha">Digite a senha: </label> <input title="Digite o termo da busca" style="width: 105px" type="text" maxlength="10" id="id_text_senha" onkeypress="return SGA.txtBoxSoNumeros(this,event)" onclick="Triagem.selecionaTextBox(this)" /> 
			<?php parent::display_label_advertencia('label_cancelar_senha','advertencia');?>
			<input title="Clique para procurar a senha" type="submit" id="id_button_procurar" value="Procurar" />
			<div id="id_label_senha"></div>
		</form>
		</div>
		<?php

	}

	/**
	 * Exibe as informações da senha que será cancelada
	 * @param $atendimento
	 */
	public static function exibir_atendimento($atendimento, $id_uni) {
		?>
		<input type="hidden" id="id_id_atendimento" value="<?php echo $atendimento->get_id();?>" />
		<div title="Senha."><label for="id_label_numero_senha">Senha:</label>
			<label id="id_label_numero_senha"><?php echo $atendimento->get_cliente()->get_senha()->get_numero();?></label>
		</div>
		<div title="Serviço.">
			<label for="id_label_numero_senha">Serviço:</label>
			<label id="id_label_nome_serv"><?php echo DB::getInstance()->get_servico_unidade($atendimento->get_servico(),$id_uni)->get_nome();?></label>
		</div>
		<?php
	}

	/**
	 * Exibe dois select para selecionar a senha à ser cancelada filtrando pelos serviços
	 * @param $servicos
	 */
	public static function exibir_cancelar_senha_por_servico($servicos) {
		?>
		<div>Serviço:</div>
		<div>
			<label title="Selecione o serviço"> <?php echo parent::display_jump_menu($servicos,'servico_cancela_senha', $servico, '','Triagem.onServicoSelecionado();');?> </label> 
			<label id="label_cancel_senha" class="advertencia"></label>
		</div>
		<div>Senha:</div>
		<div>
			<label title="Selecione a senha" id="senhas_servico"> <?php echo parent::display_jump_menu(array(), "prioridade", '', "Senhas");?> </label> 
			<label id="label2_cancel_senha" class="advertencia"></label>
		</div>
		<?php
	}

	/**
	 * Exibe o popup para reativar senhas
	 * @param $servicos array
	 */
	public static function exibir_reativar_senha($servicos) {
		if(empty($servicos)){
			?>
			<div>Não há senhas para reativar.
				<ul>
					<li><input title="Clique para fechar a janela." id="confirmar_reativar_senha" type="button" onclick="window.closePopup(this);" value="OK" /></li>
				</ul>
			</div>
			<?php
		}else{
			$prioridade = DB::getInstance()->get_prioridades();
			$ids_serv = array();
			$jump_macro = array();
			$jump_macro[-1] = "Todos";
			foreach ($servicos as $s) {
				$jump_macro[$s->get_id()] = $s->get_sigla().'-'.$s->get_nome();
				$ids_serv[] =  $s->get_id();
			}
				
			?>
			<div>Servico:</div>
			<div><?php echo parent::display_jump_menu($jump_macro,'servico_reativa_senha', -1, null,'Triagem.onServicoSelecionadoReativar();');?></div>
			
			<div>Senha:</div>
			<div>
				<label id="senhas_servico"> 
				<?php
					$id_uni = SGA::get_current_user()->get_unidade()->get_id();
					$fila = DB::getInstance()->get_fila($ids_serv, $id_uni,$ids_stat=array(Atendimento::SENHA_CANCELADA, Atendimento::NAO_COMPARECEU));
					TTriagem::exibir_senhas_servico($fila->get_atendimentos(),'Triagem.onPrioridadeSenha();');
				?> 
				</label> 
				<label id="id_label_senha_servico" class="advertencia"></label>
			</div>
			
			<div>Prioridade:</div>
			<div>
				<label id="senhas_prioridade">
				<?php TTriagem::exibir_prioridade_senha($prioridade);?> 
				</label> 
				<?php parent::display_label_advertencia('id_label_prioridade_servico');?>
			</div>
			<span>
				<input id="confirmar_reativar_senha" title="Clique para reativar a senha." type="button" onclick="Triagem.confirmaReativarSenha(this);" value="Confirmar" />
				<input title="Clique para fechar a janela. A senha não será reativada." type="button" onclick="window.closePopup(this);" value="Cancelar" />
			</span>
			<?php
		}
	}

	/**
	 *
	 * @param $prioridades
	 * @param $default
	 */
	public static function exibir_prioridade_senha($prioridades, $default = null){

		echo parent::display_jump_menu($prioridades,'list_prio',$default,'Prioridade','');

	}

	/**
	 * Exibe as senhas de um array de atendimentos
	 * @param $atendimentos array
	 */
	public static function exibir_senhas_servico($atendimentos, $onchange = ""){
		$tmp = array();
		$id_uni = SGA::get_current_user()->get_unidade()->get_id();
		foreach ($atendimentos as $atendimento) {
			$cliente = $atendimento->get_cliente();
			$numero = $cliente->get_senha()->get_full_numero();
				
			$tmp[$atendimento->get_id()] = $numero;
			$tmp[$atendimento->get_id()] .= '-'.DB::getInstance()->get_servico_unidade($atendimento->get_servico(), $id_uni)->get_sigla();
				
		}
		echo Template::display_jump_menu($tmp, 'id_cancelar_senhas', '', "Senhas", $onchange);

	}

	/**
	 * Caixa pra confirmar prioridade
	 * @param $id_servico
	 * @param $id_prioridade
	 */
	public static function display_confima_prioridade ($id_servico, $id_prioridade){
		$id_uni = SGA::get_current_user()->get_unidade()->get_id();
		$nm_pri = DB::getInstance()->get_nm_pri($id_prioridade);
		$status_imp = DB::getInstance()->get_msg_status($id_uni);

		?>
		<div align="center">Confimar prioridade: <?php echo $nm_pri?>?</div>
		<div align="center">
			<input id="confirmar_prioridade" title="Clique para emitir a senha" type="button" onclick="Triagem.distribuir(<?php echo $id_servico;?>, <?php echo $id_prioridade;?>,<?php echo $status_imp;?>)" value="Confirmar" /> 
			<input title="Clique para fechar a janela. A senha não será emitida" type="button" onclick="window.closePopup(this);" value="Cancelar" />
		</div>
		<?php
	}
}

?>
