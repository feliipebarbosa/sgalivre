<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TAgenda
 *
 * @author Felipe
 */
class TAgenda extends Template{
    
	/**
	 * MÃ©todo para importar arquivos de css e js
	 * @param $title
	 */
	public static function display_header($title='') {
		SGA::_include("modules/sga/agenda/content_loader.php");
		$tema = parent::get_tema();
		$mod_dir = Session::getInstance()->get(SGA::K_CURRENT_MODULE)->get_dir();
		$misc = '<link rel="stylesheet" href="themes/'. $tema->get_dir() .'/css/agenda.css" type="text/css" />' . "\n";
		$misc .= '<script type="text/javascript" src="modules/'.$mod_dir.'/lib/js/agenda.js"></script>' . "\n";
		parent::display_header($title, $misc);
	}

	/**
	 * Constrói tela da agenda
	 * @param $user
	 */
	public static function display_agenda(Usuario $user) {
		$topo = array('TAgenda', 'display_agenda_topo');
		$menu = array('TAgenda', 'display_agenda_menu');
		$conteudo = array('TAgenda', 'display_agenda_content');
		Template::display_template_padrao($topo, $menu, $conteudo);

	}

	/**
	 * Constrói o topo da tela da agenda
	 */
	public static function display_agenda_topo() {
		$modulo = Session::getInstance()->get(SGA::K_CURRENT_MODULE);
		Template::display_topo_padrao($modulo);
	}

	/**
	 * Constrói menus da tela da agenda
	 */
	public static function display_agenda_menu() {
		$usuario = SGA::get_current_user();
		TAtendimento::display_user_info($usuario);
		Template::display_menu_padrao($modulo, $usuario);
	}

	/**
	 * Mostra conteúdo do método agenda
	 */
	public static function display_agenda_content() {
		?>
			<div id="conteudo">
		        <?php
		            SGA::_include("modules/sga/agenda/content_loader.php");
		        ?>
			</div>
		<?php
	}

	public static function display_conteudo() {
        $id_uni = SGA::get_current_user()->get_unidade()->get_id();
		$serv = DB::getInstance()->get_servicos_unidade($id_uni, array(1));
		
		Template::display_page_title('Serviços'); ?>

		<div id="conteudo_servicos">			
			
			<div id="nome_ident_desc">Nome e identificação do cliente:</div>
			<label>Nome:</label> <input title="Nome do cliente" type="text" id="client_name" name="client_name" value="" maxlength="100" />
			<label>Ident:</label> <input title="Doc. Identificação do cliente" type="text" id="client_ident" name="client_ident" value="" maxlength="11" />
			
			<h1 id="titulo_servicos">Servi&ccedil;os</h1>
			<div id="conteudo_triagem"><?php TAgenda::servicos($serv);?></div>
		</div>
		<?php
	}

}

?>