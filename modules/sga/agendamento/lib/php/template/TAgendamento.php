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
class TAgendamento extends Template{
    
	/**
	 * MÃ©todo para importar arquivos de css e js
	 * @param $title
	 */
	public static function display_header($title='') {
		SGA::_include("modules/sga/agendamento/content_loader.php");
		$tema = parent::get_tema();
		$mod_dir = Session::getInstance()->get(SGA::K_CURRENT_MODULE)->get_dir();
		$misc = '<link rel="stylesheet" href="themes/'. $tema->get_dir() .'/css/agendamento.css" type="text/css" />' . "\n";
		$misc .= '<script type="text/javascript" src="modules/'.$mod_dir.'/lib/js/agendamento.js"></script>' . "\n";
		parent::display_header($title, $misc);
	}

	/**
	 * Constrói tela da agenda
	 * @param $user
	 */
	public static function display_agendamento(Usuario $user) {
		$topo = array('TAgendamento', 'display_agendamento_topo');
		$menu = array('TAgendamento', 'display_agendamento_menu');
		$conteudo = array('TAgendamento', 'display_agendamento_content');
		Template::display_template_padrao($topo, $menu, $conteudo);

	}

	/**
	 * Constrói o topo da tela da agenda
	 */
	public static function display_agendamento_topo() {
		$modulo = Session::getInstance()->get(SGA::K_CURRENT_MODULE);
		Template::display_topo_padrao($modulo);
	}

	/**
	 * Constrói menus da tela da agenda
	 */
	public static function display_agendamento_menu() {
		$usuario = SGA::get_current_user();
		TAgendamento::display_user_info($usuario);
		Template::display_menu_padrao($modulo, $usuario);
	}

	/**
	 * Mostra conteúdo do método agenda
	 */
	public static function display_agendamento_content() {
		TAgendamento::display_conteudo();
		SGA::_include("modules/sga/agendamento/content_loader.php");
		       
	}

	public static function display_conteudo() {
        $id_uni = SGA::get_current_user()->get_unidade()->get_id();
		$serv = DB::getInstance()->get_servicos_unidade($id_uni, array(1));
		
		Template::display_page_title('Criar Agendamento'); 
		$horas = array('07:00'=>'07:00','08:00'=>'08:00','09:00'=>'09:00','10:00'=>'10:00','11:00'=>'11:00','12:00'=>'12:00','13:00'=>'13:00','14:00'=>'14:00','15:00'=>'15:00','16:00'=>'16:00','17:00'=>'17:00','18:00'=>'18:00','19:00'=>'19:00');
		?>
		

		<div id="conteudo_servicos">
		<form id="frm_criar_agendamento" method="post" action="" onsubmit="Agendamento.criar_agendamento(); return false;">						
			<table class="agendamento">
				<thead>
					<tr style="padding-button:30px;">
						<td style="width:30px; text-align:center; font-weight:bold; padding-button:30px;"> </td>
						<td style="width:80px; text-align:center; font-weight:bold; padding-button:30px;"> Dia/Mês/Ano</td>
						<td style="width:50px; text-align:center; font-weight:bold; padding-button:30px;"> Hora</td>
						<td style="width:80px; text-align:center; font-weight:bold; padding-button:30px;" > Atendente</td>
						<td style="width:80px; text-align:center; font-weight:bold; padding-button:30px;" > Unidade</td>
					</tr>		
				</thead>
				<tr style="border: solid #d1d1d1 1px;">
					<td style="width:30px; text-align:center;" ><input type="radio" name="agendamento" id="1" value="1" /></td>
					<td style="width:80px; text-align:center;" > 05/05/2014 </td>
					<td style="width:50px; text-align:center;"> 08:00 </td>
					<td style="width:80px; text-align:center;"> Kamila </td>
					<td style="width:80px; text-align:center;"> Cariacica </td>
				</tr>
				<tr style="border: solid #d1d1d1 1px;">
					<td style="width:30px; text-align:center;" ><input type="radio" name="agendamento" id="2" value="2" /></td>
					<td style="width:80px; text-align:center;" > 05/05/2014 </td>
					<td style="width:50px; text-align:center;"> 08:30 </td>
					<td style="width:80px; text-align:center;"> Kamila </td>
					<td style="width:80px; text-align:center;"> Cariacica </td>
				</tr>
			</table>	
			<br>
			<br>
			<div>
				<?php
					Template::display_action_button("Confirmar", "images/tick.png", "Agendamento.criar_agendamento();",'button','',true,'Clique para confirmar a criação do agendamento.');
            		Template::display_action_button("Voltar", "images/cross.png", "Agendamento.cancelarErroTriagem()",'button','',true,'Clique para voltar.');
				?>
			</div>
		</form>	
		</div>
		<?php
	}

}

?>