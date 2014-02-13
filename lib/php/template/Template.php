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
*
*	Classe contendo metodos para montar o HTML
*
*
*/
class Template {

	const ACAO_INSERIR = 0;
	const ACAO_ATUALIZAR = 1;
	
	static function get_tema() {
		$usuario = Session::getInstance()->get(SGA::K_CURRENT_USER);
		if ($usuario == null || $usuario->get_unidade() == null) {
			// TODO Unidade não disponivel(usuario não logado)
			// Permitir que o tema padrão seja configuravel
			return array_shift(DB::getInstance()->get_temas());
		}
		else {
			return $usuario->get_unidade()->get_tema();
		}
	}
	
	/**
	 * Cabecalho da Pagina
	 */
	static function display_header($title='', $misc='', $tema_dir = null) {
        if ($tema_dir == null) {
            $tema_dir = Template::get_tema()->get_dir();
        }
		?>
		<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en">
		<head>		
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<?php $title=($title=='')?'Sistema de Gerenciamento de Atendimento':$title;?>
		<title><?php echo SGA::NAME, ': ', $title; ?></title>
		<link rel="stylesheet" href="lib/js/jquery/theme/jquery-ui-1.7.1.custom.css" type="text/css" />
        <link rel="stylesheet" href="themes/<?php echo $tema_dir;?>/css/default.css" type="text/css" />
		
        <script type="text/javascript" src="lib/js/core/default.js"></script>
		<script type="text/javascript" src="lib/js/libjsx/ajax/Ajax.js"></script>
        <script type="text/javascript" src="lib/js/jquery/jquery-1.3.1.min.js"></script>
        <script type="text/javascript" src="lib/js/jquery/jquery-ui-1.7.1.custom.min.js"></script>
        <script type="text/javascript" src="lib/js/jquery/jquery.validate.js"></script>
        <script type="text/javascript" src="lib/js/core/jquery.cookie.js"></script>
        <script type="text/javascript" src="lib/js/core/jquery.treeview.js"></script>
		<script>
            window.onload = SGA.onLoad;
            SGA.addOnLoadListener(new SGA().refresh);
		</script>
		<?php echo $misc;?>
		</head>
		<body>
			<div id="geral">
		<?php
	}
	
	/**
	 * Rodape da pagina
	 */
	static function display_footer() {
		?>
					</div>
				</body>
			</html>
		<?php
	}

    static function display_popup_header($title = '') {
		?>
            <div class="window_popup">
                <div class="window_popup_title">
                    <?php echo SGA::NAME, ': ', $title; ?>
                </div>
		<?php
	}
	
	static function display_popup_footer() {
		?>
            </div>
		<?php	
	}
	
	public static function display_template_padrao($topo, $menu, $conteudo) {	    
	    ?>	
		    <div id="template_padrao">
			    <div id="template_topo">
				    <?php
				    	call_user_func($topo); 
				    ?>
			    </div>
			    <div id="template_menu">
				    <?php
					    call_user_func($menu); 
				    ?>
			    </div>
			    <div id="template_conteudo">
			        <?php
			            call_user_func($conteudo); 
			        ?>
			    </div>
		    </div>
	    <?php
	}

	public static function display_user_info(Usuario $user) {
		$modulo = Session::getInstance()->get(SGA::K_CURRENT_MODULE);
		?>
			<div id="info">
				<div title="Imagem do Módulo" id="foto"><img width="96" height="96" src="<?php echo $modulo->get_img();?>" /></div>
				<h3 title="Usuário"><span id="info_mat">Usuário:</span> <?php echo $user->get_login();?></h3>
                <?php
                $lotacao = $user->get_lotacao();
                if ($lotacao != null) {
                    ?>
                    <h3 title="Grupo"><span id="info_grupo">Grupo:</span> <?php echo $lotacao->get_grupo()->get_nome();?></h3>
                    <h3 title="Cargo"><span id="info_cargo">Cargo:</span> <?php echo $lotacao->get_cargo()->get_nome();?></h3>
                    <?php
                }
				?>
			</div>
		<?php
	}
	
    public static function display_topo_padrao(Modulo $modulo) {
        ?>
        <span id="date_time"><?php echo time();?></span> <?php // tag aonde ficara mostrando a data e hora ?>
        <h1>
            <?php echo $modulo->get_nome();?>
            <?php
            $unidade = SGA::get_current_user()->get_unidade();
            if ($unidade != null)
            {
                // exibir unidade
                ?>
                - <?php echo $unidade->get_nome();?>
                <?php
            }
            ?>
        </h1>
        <?php
    }

	public static function display_menu_padrao($modulo, $usuario) {
		if (!Session::getInstance()->exists('MENU')){
			$menu = DB::getInstance()->get_menu(Session::getInstance()->get(SGA::K_CURRENT_MODULE)->get_chave());
			Session::getInstance()->set('MENU', $menu);
		}
		else {
			$menu = Session::getInstance()->get('MENU');
		}
		?>
			<ul>
				<?php
					foreach ($menu as $item) {
						?>
							<li><a href="<?php echo $item->get_link();?>" title="<?php echo $item->get_descricao();?>"><?php echo $item->get_nome();?></a></li>
						<?php
					}
				?>

			</ul>
		<?php
	}

	/**
	 * Monta um Jump Menu atraves do array passado por parametro
	 */
	static function display_jump_menu($array, $name='', $default='', $label='', $onchange='', $size=0, $eventos = '', $title='', $disabled='', $max_width="auto;", $multiple="", $class = "") {
		$jump = '<select  id="'.$name.'" name="'.$name.'" "'.$multiple.'" class="'.$class.'" style="max-width:'.$max_width.'" onchange="'.$onchange.'" size="'.$size.'" title="'.$title.'" '.$disabled.'>' . "\n";
		if (!empty($label)) {
		    $jump .= '	<option value="">'.$label.'</option>' . "\n";
		}
		if (!is_array($array)) {
			$array = array();
		}
		foreach ($array as $key => $value) {
			$sel = '';
			if ($key == $default)
				$sel = 'selected="selected"';
			$jump .= '	<option value="'.$key.'" '.$sel.' ondblclick="'.$eventos.'" >'.$value.'</option>' . "\n";
		}
		$jump .= "</select>\n";
		return $jump;	
	}

	/**
	 * Monta cabecalho de login
	 */
	static function display_login_header($label='Enviar', $action='') {
		?>
		<div id="login_header">
			<h1><a href="./" title="Início"><?php echo SGA::NAME; ?></a></h1>
		</div>
		<?php
	}

	/**
	 * Monta formulario de acesso (login)
	 */
	static function display_login_form($label='Enviar', $action='', $method='post') {
		?>
		<div id="login">
			<form action="<?php echo $action;?>" method="<?php echo $method;?>">
				<h2><?php echo SGA::NAME, ': Acesso'; ?></h2>
				<div><h3>Usuário:</h3><span><input id="login_usu" type="text" name="user" /></span></div>
				<div><h3>Senha:</h3><span><input id="senha_usu" type="password" name="pass" /></span></div>
				<div><input type="submit" value="<?php echo $label;?>" /></div>
			</form>
		</div>
		<?php
	}
	
	static function display_exception(Exception $e, $title = 'Erro', $onClickOk = '',$blackout = 0 ) {
		$debug = false; //TODO isso deve ser configuravel
		$message = $e->getMessage();
		if ($debug) {
			$message .= '<pre>'.$e->getTraceAsString().'</pre';
		}
		Template::display_error($message, $title, $blackout, $onClickOk);
	}
	
	static function display_error($message, $title = 'Erro', $blackout = 0, $onClickOk = '') {
        Template::display_popup_header($title);
		?>
            <div id="window_popup_icon">
                <img src="themes/sga.default/imgs/dialog-error.png"/>
            </div>
			<div id="window_popup_content">
				<div id="window_error_dialog_message">
					<p><?php echo $message;?></p>
	
				</div>
			</div>
            <div id="window_popup_controls">
                <input id="btn_window_error_ok" class="button" type="button" onclick="window.closePopup(this);<?php echo $onClickOk;?>" value="Ok" />
            </div>
            <script type="text/javascript">
                SGA.addOnLoadListener(function() {
                    SGA.seleciona("btn_window_error_ok");
                });
            </script>
		<?php
       Template::display_popup_footer();
	}

	static function display_input_dialog($message, $title, $callback, $valor='') {
        Template::display_popup_header($title);
		?>
            <form id ="id_form_window_popup" onsubmit="window.closeInputDialog(this, <?php echo $callback;?>); return false;">
                <div id="window_popup_icon">
                    <img src="themes/sga.default/imgs/dialog-input.png"/>
                </div>
                <div id="window_popup_content">
                    <div id="window_input_dialog_message">
                        <p><?php echo $message;?></p>
                        <input type="text" id="txt_input_dialog" value="<?php echo $valor; ?>" maxlength="3" />
                    </div>
                	<div id="window_popup_controls">
                    	<input id="btn_window_input_ok" class="button" type="submit" value="Ok" />
                    	<input type="button" onclick="location.href='?mod=sga.inicio' " value="Cancelar" />
                	</div>
                </div>
                <script type="text/javascript">
                    SGA.addOnLoadListener(function() {
                        SGA.seleciona("btn_window_input_ok");
                    });
                </script>
            </form>
		<?php
        Template::display_popup_footer();
	}
	
	static function display_select_unidade($content, $title, $callback) {
		//FIXME copiar estilos de window_input_dialog para window_dialog
		?>
			<div id="w1indow_input_dialog">
				<div id="window_input_dialog_message">
					<h2><?php echo $title; ?></h2>
					<div>
                        <form method="post" action="?set_uni" >
						<p>
							<?php echo $content;?>
					    	<input type="submit" value="Ok" />
					    </p>
					    </form>
				    </div>
				</div>				
			</div>
		<?php
	}
	
	static function display_confirm_dialog($message, $title = "Informação", $onClickOk = "") {
        Template::display_popup_header($title);
		?>
            <div id="window_popup_icon">
                <img src="themes/sga.default/imgs/dialog-information.png"/>
            </div>
			<div id="window_popup_content">
				<div id="window_confirm_dialog_message">
					<p><?php echo $message;?></p>
					
				</div>				
            	<div id="window_popup_controls">
                	<input id="button" class="button" type="button" onclick="window.closePopup(this); <?php echo $onClickOk;?>" value="Ok" />
            	</div>
            </div>
		<?php
        Template::display_popup_footer();
	}
	
	public static function display_confirm_dialog_refresh($message, $title){
        Template::display_popup_header($title);
		?>
            <div id="window_confirm_dialog">
				<div id="window_confirm_dialog_message">
					<h2><?php echo SGA::NAME, ': ', $title; ?></h2>
					<p><?php echo $message;?></p>
					<div>
					    <input id="button" class="button" type="button" onclick="window.closePopup(this)" value="Ok" />
				    </div>
				</div>
			</div>
		<?php
        Template::display_popup_footer();
	}
	
	static function display_yes_no_dialog($message, $title, $onclickok, $remove_response_popup = false, $onclickcancel='') {
		Template::display_popup_header($title);
		?>
            <div id="window_popup_icon">
                <img src="themes/sga.default/imgs/dialog-information.png"/>
            </div>
			<div id="window_popup_content">
				<div id="window_yesno_dialog_message">
					<p><?php echo $message;?></p>
					
				</div>				
			</div>
            <div id="window_popup_controls">
                <input id="okbutton" type="button" onclick="<?php echo $onclickok;?>" value="Confirmar" />
					    <input id="cancelbutton" type="button" onclick="window.closePopup(this);<?php echo $onclickcancel;?>" value="Cancelar" />
            </div>
		<?php
        Template::display_popup_footer();
	}

	/**
	 * Fecha a janela aberta por script
	 */
	static function close_window() {
		echo '<script type="text/javascript"> window.close(); </script>';
	}

	/**
	 * Atualiza a página (refresh)
	 * @return none
	 */
	static function refresh(){
		echo '<script type="text/javascript"> document.location.reload(); </script>';
	}
	
	/**
	 * Cria botões personalizados
	 * @return none
	 */
	static function display_button($text, $image, $onclick = '', $css_class = 'btn', $type = 'button', $id = '',$img_left, $title='Clique.', $disabled = false) {
		?>
            <button title="<?php echo $title?>" type="<?php echo $type; ?>" id="<?php echo $id;?>" class="<?php echo $css_class; ?>" onclick="<?php echo $onclick; ?>"  <?php echo ($disabled ? "disabled=\"false\"" : "");?>>
                <img src="<?php echo $image; ?>" alt="" />
                <?php echo $text;?>
            </button>
		<?php
	}
	
	static function display_action_button ($text, $image, $onclick, $type = 'button', $id = '', $img_left = true, $title='', $disabled = false) {
		Template::display_button($text, $image, $onclick, "botao_acao", $type, $id,$img_left, $title, $disabled);
	}
	
	static function display_menu_button ($text, $image, $onclick,$title ,$type = 'button', $id = '', $img_left = true) {
		Template::display_button('<div class="btn_txt">'.$text.'</div>', $image, $onclick, "botao_menu", $type, $id, $img_left,$title);
	}
	
	static function display_date_field($id, $value ,$onclick) {
	?>
		<input type="text" class="date_field" id="<?php echo $id;?>" name="<?php echo $id;?>" maxlength="10" value="<?php echo $value;?>" onclick="<?php echo $onclick; ?>"/>
	<?php
    /*
     * <input type="text" class="date_field" id="<?php echo $id;?>" name="<?php echo $id;?>" maxlength="10" value="<?php echo $value;?>" onkeypress="return SGA.txtBoxFormat(this, '99/99/9999', event);" onclick="<?php echo $onclick; ?>"/>
		<button type="button" class="calendar_button" onclick="displayCalendar(document.getElementById('<?php echo $id; ?>'), 'dd/mm/yyyy', this)"><img src="images/calendar/calendar.png" /></button>
     */
	}
	static function display_page_title($title) {
	?>
		<h3 class="page_title"><?php echo Session::getInstance()->get(SGA::K_CURRENT_MODULE)->get_nome(), ' > ', $title?></h3>
	<?php
	}
	static function display_label_advertencia($id,$class='advertencia',$text='') {
	?>
		<label id="<?php echo $id;?>" class="<?php echo $class;?>" ><?php echo $text;?></label>
	<?php
	}
	
}

?>
