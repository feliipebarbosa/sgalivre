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
 * Classe TAtendimento
 *
 * responsavel pela estrutura HTML do Atendimento
 *
 */
class TAtendimento extends Template {

	/**
	 * Método para importar arquivos de css e js
	 * @param $title
	 */
	public static function display_header($title='') {
		$tema = parent::get_tema();
		$mod_dir = Session::getInstance()->get(SGA::K_CURRENT_MODULE)->get_dir();
		$misc = '<link rel="stylesheet" href="themes/'. $tema->get_dir() .'/css/atendimento.css" type="text/css" />' . "\n";
		$misc .= '<script type="text/javascript" src="modules/'.$mod_dir.'/lib/js/atendimento.js"></script>' . "\n";
		$misc .= '<script type="text/javascript">
			                var atend = new Atendimento();
			                SGA.addOnLoadListener(atend.refresh);
		          </script>';
		parent::display_header($title, $misc);
	}

	/**
	 * Constrói tela do atendimento
	 * @param $user
	 */
	public static function display_atendimento(Usuario $user) {
		$topo = array('TAtendimento', 'display_atendimento_topo');
		$menu = array('TAtendimento', 'display_atendimento_menu');
		$conteudo = array('TAtendimento', 'display_atendimento_content');
		Template::display_template_padrao($topo, $menu, $conteudo);

	}

	/**
	 * Constrói o topo da tela do atendimento
	 */
	public static function display_atendimento_topo() {
		$modulo = Session::getInstance()->get(SGA::K_CURRENT_MODULE);
		Template::display_topo_padrao($modulo);
	}

	/**
	 * Constrói menus da tela do atendimento
	 */
	public static function display_atendimento_menu() {
		$usuario = SGA::get_current_user();
		TAtendimento::display_user_info($usuario);
		Template::display_menu_padrao($modulo, $usuario);
	}

	/**
	 * Mostra conteúdo do método atendimento
	 */
	public static function display_atendimento_content() {
		?>
			<div id="conteudo">
		        <?php
		            SGA::_include("modules/sga/atendimento/content_loader.php");
		        ?>
			</div>
		    <div id="fila">
		    </div>
		<?php
	}

	/**
	 * Mostra informações do usuario
	 * @param $user
	 */
	public static function display_user_info(Usuario $user) {
		$modulo = Session::getInstance()->get(SGA::K_CURRENT_MODULE);
		?>
			<div id="info">
				<div title="Imagem do Módulo" id="foto"><img width="96" height="96" src="<?php echo $modulo->get_img();?>" /></div>
				<h3 title="Usuário"><span id="info_mat">Login:</span> <?php echo $user->get_login();?></h3>
				<h3 title="Grupo"><span id="info_grupo">Grupo:</span> <?php echo $user->get_lotacao()->get_grupo()->get_nome();?></h3>
				<h3 title="Cargo"><span id="info_cargo">Cargo:</span> <?php echo $user->get_lotacao()->get_cargo()->get_nome();?></h3>
				<h3 title="Guich&ecirc;"><span id="info_guiche">Guich&ecirc;:</span> <?php echo $user->get_num_guiche();?></h3>
			</div>
		<?php
	}

	/**
	 * Mostra fila do atendimento
	 * @param Usuario $usuario
	 * @param Fila $fila
	 */
	public static function display_atendimento_fila(Usuario $usuario, Fila $fila) {
		$total = $fila->size();
		echo "	<h3>Minha Fila: <span title='Total'>$total</span></h3>\n";
		foreach ($usuario->get_servicos() as $s) {
			$servico = DB::getInstance()->get_servico_unidade($s,$usuario->get_unidade()->get_id());
			$fila = DB::getInstance()->get_fila(array($s), $usuario->get_unidade()->get_id());
			echo '<div class="fila_servico">' . "\n";
			echo "	<h4>".$servico->get_sigla().'-'.$servico->get_nome()."</h4>\n";
			echo "	<span>".$fila->size()."</span>\n";
			echo "</div>\n";
		}
	}

	/**
	 * Mostra status do atendimento
	 * @param Atendimento $atendimento
	 * @param $usuario
	 */
	public static function display_by_status(Atendimento $atendimento, $usuario) {
		TAtendimento::show_info($atendimento->get_cliente());
	    $status = $atendimento->get_status();
	    switch ($status) {
		    case Atendimento::CHAMADO_PELA_MESA:
		        TAtendimento::display_atendimento_iniciar($atendimento, $usuario);
		        break;
		    case Atendimento::ATENDIMENTO_INICIADO:
		        TAtendimento::display_atendimento_atender($atendimento, $usuario);
		        break;
		    case Atendimento::ATENDIMENTO_ENCERRADO:
		    	TAtendimento::display_atendimento_encerrar($atendimento, $usuario);
		    	break;
		    case Atendimento::ERRO_TRIAGEM:
		    	TAtendimento::exibir_erro_triagem();
		    	break;

		    default:
		    	throw new Exception("Status ($status) de atendimento não suportado para exibição");
		    	break;
	    }

	}

	/**
	 * Exibe as informações do usuário que está sendo atendido.
	 * @param $cliente
	 */
	public static function show_info(Cliente $cliente){
		if($cliente->get_senha()->get_prioridade()->get_id() != 1){
			$cor_prio = 'atendimento_prioridade';
		}
		?>
		<div id="info_senha">
			<div id="num_senha">
		    	Senha: <?php echo $cliente->get_senha();?>
			</div>
			<div id="prio_cli" class="<?php echo $cor_prio;?>">
			    Prioridade: <?php echo $cliente->get_senha()->get_prioridade();?>
			</div>
			<div id="nome_cli">
	    		Nome: <?php echo $cliente->get_nome();?>
			</div>
			<div id="ident_cli">
				Ident.: <?php echo $cliente->get_ident();?>
			</div>
		</div>
		<?php
	}

	/**
	 * Mostra o botão para chamar próximo da fila
	 */
	public static function display_atendimento_chamar() {
		?>
        	<div class="info_centro">
	  			<div>
                    <form action="" method="post">
                    	<?php Template::display_menu_button("Chamar Pr&oacute;ximo", "images/atendimento/prox.png", "Atendimento.proximo(this)",'Clique para chamar o próximo da fila.')?>
                    </form>
				</div>
			</div>
		<?php
	}

	/**
	 * Mostra tela com os botões (Não compareceu, Chamar, Iniciar) relacionados ao atendimento logo após ter sido chamado pela mesa
	 * @param $atendimento
	 * @param $usuario
	 */
	public static function display_atendimento_iniciar($atendimento, $usuario) {
		?>
			<div class="info_centro">
		  		<div>
		            <form action="" method="post">
		                <?php
                            Template::display_menu_button("Erro<br />Triagem", "images/atendimento/erro.png", "Atendimento.erroTriagem(this)",'Clique para redirecionar a pessoa para o serviço correto.');
                            Template::display_menu_button("N&atilde;o Compareceu", "images/atendimento/naocompareceu.png", "Atendimento.naoCompareceu(this)",'Clique se a pessoa não compareceu.');
                            Template::display_menu_button("Chamar Novamente", "images/atendimento/prox.png", "Atendimento.proximo(this)",'Clique para chamar novamente a senha.', 'button', 'btn_chamar_proximo');
                            Template::display_menu_button("Iniciar Atendimento", "images/atendimento/iniciaratendimento.png", "Atendimento.iniciar(this)",'Clique para iniciar o atendimento.');
                         ?>
		            </form>
				</div>
			</div>
		<?php
	}

	/**
	 * Mostra na parte central da tela as listas com os serviços
	 * para inserir nas estatisticas
	 * @param $atendimento
	 * @param $usuario
	 */
	public static function display_atendimento_encerrar($atendimento, $usuario) {
		?>
            <form id="id_form_encerra_atendimento" action="" method="post">
                <?php
                    // se estiver ativo, exibe a parte de redirecionamento, senão essa parte fica oculta
                    $redirecionando = Session::getInstance()->get("redirecionar");

                    $class_redir = "";
                    if ($redirecionando == true) {
                        $checkedHtml = "checked=true";
                    }
                    else {
                        $class_redir = "invisible";
                    }
                ?>
                <div id="modulos_icons">
                	<?php
                		$id_user = SGA::get_current_user()->get_id();
						$id_uni = SGA::get_current_user()->get_unidade()->get_id();
                        $id_servicos_usu = DB::getInstance()->get_usuario_servicos_unidade($id_user,$id_uni, array(Servico::SERVICO_ATIVO));
                        $tmp = array();
						foreach ($id_servicos_usu as $id_serv) {
							$tmp[$id_serv] = DB::getInstance()->get_servico($id_serv)->get_nome();
						}
                	?>
                	<span>
	                	<h5>Macrosserviço</h5>
		                <span><?php echo Template::display_jump_menu($tmp,'list_servico_mestre',null,null,"Atendimento.onServicoSelecionado()", 7, '',"'Lista de macrosserviços.'");?></span>
	                </span>
	                <span>
		                <h5>Subserviço</h5>
		                <span id="id_sub_servico"><?php echo Template::display_jump_menu(array(),'list_sub_servico',null,null,null, 7,'',"'Lista de subserviços.'");?></span>
						<?php Template::display_action_button("Inserir", "images/insert.png", "Atendimento.adicionaServicoAtendido();",'button','',true,'Clique para marcar o serviço como atendido.')?>
					</span>
				</div>
				<div style="clear: both;" id="modulos_icons">
					<span>
	                	<span id="id_servico_atendido"><?php echo Template::display_jump_menu(array(),'list_servico_atendido[]',null,null,null,10,'',"'Lista de serviços atendidos.'");?></span>
						<?php Template::display_action_button("Excluir", "images/cross.png", "Atendimento.removeItem();",'button','',true,'Clique para tirar o serviço da lista de serviços atendidos.')?>
					</span>
	           	</div>
                <div style="clear: both;" id="id_div_redirecionar">
                    <input type="checkbox" name="check_redirecionar" id="id_check_redirecionar" <?php echo $checkedHtml;?> value="true" onclick="Atendimento.toggleRedirecionar();"/>
                    <label for="id_check_redirecionar">Redirecionar para outro macrosserviço após encerrar.</label>
					<span class="<?php echo $class_redir ?>" id="id_span_redirecionar">
	                	<?php TAtendimento::exibir_erro_triagem_select(1); ?>
					</span>
	           	</div>
	        	<div style="clear: both;" id="modulos_icons">
	        		<span>
						<?php
                            Template::display_action_button("Confirmar", "images/tick.png", "Atendimento.confirmaEncerra();",'button','',true,'Clique para confirmar a codificação.');
                            Template::display_action_button("Voltar", "images/cross.png", "Atendimento.cancelarErroTriagem()",'button','',true,'Clique para voltar.');
                        ?>
	        		</span>
	        	</div>
            </form>
		<?php
	}

	/**
	 * Exibe um array de subserviços
	 * @param $sub_servicos array
	 */
	public static function exibir_sub_servico($sub_servicos){
		$tmp = array();
		foreach ($sub_servicos as $sub_servico) {
			$nm_serv = $sub_servico->get_nome();
			$tmp[$sub_servico->get_id()] = $nm_serv;
		}
		echo Template::display_jump_menu($tmp, 'list_sub_servico', '', null, null, 7,'Atendimento.adicionaServicoAtendido();');
	}

	/**
	 * Após iniciado atendimento, mostra tela com os botões (erro de triagem, encerrar) relacionados ao encerramento do atendimento
	 * @param $atendimento
	 * @param $usuario
	 */
	public static function display_atendimento_atender($atendimento, $usuario) {
		?>
           	<div class="info_centro">
				<div>
		            <form action="" method="post">
		                <?php

                            Template::display_menu_button("Erro<br />Triagem", "images/atendimento/erro.png", "Atendimento.erroTriagem(this)",'Clique para redirecionar a pessoa para o serviço correto.');
                            Template::display_menu_button("N&atilde;o<br /> Compareceu", "images/atendimento/naocompareceu.png", "Atendimento.naoCompareceu(this)",'Clique se a pessoa não compareceu.');
                            Template::display_menu_button("Encerrar<br /> Atendimento", "images/atendimento/encerrar.png", "Atendimento.encerrar(this, false)",'Clique para encerrar o atendimento.');
                            Template::display_menu_button("Encerrar e<br />Redirecionar", "images/atendimento/redirecionar.png", "Atendimento.encerrar(this, true)",'Clique para encerrar o atendimento, porém redirecionando a pessoa para outro serviço.');

                        ?>
		            </form>
				</div>
			</div>
		<?php
	}

	/**
	 * Tela  com opções do erro de triagem (confirmar ou cancelar)
	 * @param $atendimento
	 * @param $usuario
	 */
	public static function exibir_erro_triagem() {
        ?>
            <form action="" method="post" id="id_triagem_error">
                <div id="triagem_error_list">
                    <h5 style="font-size: 15px;">Macrosserviço</h5>
                    <?php TAtendimento::exibir_erro_triagem_select(10); ?>
                </div>
                <div id="triagem_error_btns">
                    <?php
                        Template::display_action_button("Confirmar", "images/tick.png", "Atendimento.confirmaErroTriagem()",'button','',true,'Clique para confirmar o redirecionamento.');
                        Template::display_action_button("Voltar", "images/cross.png", "Atendimento.cancelarErroTriagem()",'button','',true,'Clique para voltar.');
                    ?>
                </div>
            </form>
        <?php
    }

    public static function exibir_erro_triagem_select($size) {
        $usuario = SGA::get_current_user();
        $id_uni = $usuario->get_unidade()->get_id();
        $id_usu = $usuario->get_id();

        // obtem todos servicos que o usuario NAO atende
        $servicos = DB::getInstance()->get_servicos_unidade_erro_triagem($id_uni, $id_usu, array(Servico::SERVICO_ATIVO));

        $tmp = array();
        foreach ($servicos as $s) {
            $tmp[$s->get_id()] = $s->get_nome();
        }

        $label = $size == 1 ? "-- Selecione --" : null;
        echo Template::display_jump_menu($tmp, 'servico_erro_triagem' ,null ,$label ,"" ,$size ,'' ,"'Lista de macrosserviços.'");

        // Obtem todos os servicos do usuario nessa unidade
        //$servicos = $usuario->get_servicos();
        //$tmp = array();
        //foreach ($servicos as $s) {
        //    $tmp[$s->get_id()] = $s->get_nome();
        //}

        // insere outro select contendo os serviços que o usuário atende
        // este select fica invisivel, e é utilizado pelo javascript apenas
        //echo Template::display_jump_menu($tmp,'servico_usu_erro_triagem',null,null,"",$size, '', '', '', "auto;", "", "invisible");
    }
}

?>
