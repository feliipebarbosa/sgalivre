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
		TAgenda::display_user_info($usuario);
		Template::display_menu_padrao($modulo, $usuario);
	}

	/**
	 * Mostra conteúdo do método agenda
	 */
	public static function display_agenda_content() {
		TAgenda::display_conteudo();
		SGA::_include("modules/sga/agenda/content_loader.php");
		       
	}

	public static function display_conteudo() {
        $id_uni = SGA::get_current_user()->get_unidade()->get_id();
		$serv = DB::getInstance()->get_servicos_unidade($id_uni, array(1));
		
		Template::display_page_title('Criar Agenda'); 
		$horas = array('07:00'=>'07:00','08:00'=>'08:00','09:00'=>'09:00','10:00'=>'10:00','11:00'=>'11:00','12:00'=>'12:00','13:00'=>'13:00','14:00'=>'14:00','15:00'=>'15:00','16:00'=>'16:00','17:00'=>'17:00','18:00'=>'18:00','19:00'=>'19:00');
		?>
		

		<div id="conteudo_servicos">
		<form id="frm_criar_agenda" method="post" action="" onsubmit="Agenda.criar_agen(); return false;">			
			
			<table>
				<thead>
					<tr>
						<td> Hora</td>
						<td> Segunda</td>
						<td> Terça</td>
						<td> Quarta</td>
						<td> Quinta</td>
						<td> Sexta</td>
						<td> Sábado</td>
					</tr>		
				</thead>
				<tr>
					<td>08:00</td>
					<td><input type="radio" id="segunda_08:00" value="segunda_08:00" /></td>
					<td><input type="radio" id="terca_08:00" value="terca_08:00" /></td>
					<td><input type="radio" id="quarta_08:00" value="quarta_08:00" /></td>
					<td><input type="radio" id="quinta_08:00" value="quinta_08:00" /></td>
					<td><input type="radio" id="sexta_08:00" value="sexta_08:00" /></td>
					<td><input type="radio" id="sabado_08:00" value="sabado_08:00" /></td>
				</tr>
				<tr>
					<td>08:30</td>
					<td><input type="radio" id="segunda_08:30" value="segunda_08:30" /></td>
					<td><input type="radio" id="terca_08:30" value="terca_08:30" /></td>
					<td><input type="radio" id="quarta_08:30" value="quarta_08:30" /></td>
					<td><input type="radio" id="quinta_08:30" value="quinta_08:30" /></td>
					<td><input type="radio" id="sexta_08:30" value="sexta_08:30" /></td>
					<td><input type="radio" id="sabado_08:30" value="sabado_08:30" /></td>
				</tr>
				<tr>
					<td>09:00</td>
					<td><input type="radio" id="segunda_09:00" value="segunda_09:00" /></td>
					<td><input type="radio" id="terca_09:00" value="terca_09:00" /></td>
					<td><input type="radio" id="quarta_09:00" value="quarta_09:00" /></td>
					<td><input type="radio" id="quinta_09:00" value="quinta_09:00" /></td>
					<td><input type="radio" id="sexta_09:00" value="sexta_09:00" /></td>
					<td><input type="radio" id="sabado_09:00" value="sabado_09:00" /></td>
				</tr>
				<tr>
					<td>09:30</td>
					<td><input type="radio" id="segunda_09:30" value="segunda_09:30" /></td>
					<td><input type="radio" id="terca_09:30" value="terca_09:30" /></td>
					<td><input type="radio" id="quarta_09:30" value="quarta_09:30" /></td>
					<td><input type="radio" id="quinta_09:30" value="quinta_09:30" /></td>
					<td><input type="radio" id="sexta_09:30" value="sexta_09:30" /></td>
					<td><input type="radio" id="sabado_09:30" value="sabado_09:30" /></td>
				</tr>
				<tr>
					<td>10:00</td>
					<td><input type="radio" id="segunda_10:00" value="segunda_10:00" /></td>
					<td><input type="radio" id="terca_10:00" value="terca_10:00" /></td>
					<td><input type="radio" id="quarta_10:00" value="quarta_10:00" /></td>
					<td><input type="radio" id="quinta_10:00" value="quinta_10:00" /></td>
					<td><input type="radio" id="sexta_10:00" value="sexta_10:00" /></td>
					<td><input type="radio" id="sabado_10:00" value="sabado_10:00" /></td>
				</tr>
				<tr>
					<td>10:30</td>
					<td><input type="radio" id="segunda_10:30" value="segunda_10:30" /></td>
					<td><input type="radio" id="terca_10:30" value="terca_10:30" /></td>
					<td><input type="radio" id="quarta_10:30" value="quarta_10:30" /></td>
					<td><input type="radio" id="quinta_10:30" value="quinta_10:30" /></td>
					<td><input type="radio" id="sexta_10:30" value="sexta_10:30" /></td>
					<td><input type="radio" id="sabado_10:30" value="sabado_10:30" /></td>
				</tr>
				<tr>
					<td>11:00</td>
					<td><input type="radio" id="segunda_11:00" value="segunda_11:00" /></td>
					<td><input type="radio" id="terca_11:00" value="terca_11:00" /></td>
					<td><input type="radio" id="quarta_11:00" value="quarta_11:00" /></td>
					<td><input type="radio" id="quinta_11:00" value="quinta_11:00" /></td>
					<td><input type="radio" id="sexta_11:00" value="sexta_11:00" /></td>
					<td><input type="radio" id="sabado_11:00" value="sabado_11:00" /></td>
				</tr>
				<tr>
					<td>11:30</td>
					<td><input type="radio" id="segunda_11:30" value="segunda_11:30" /></td>
					<td><input type="radio" id="terca_11:30" value="terca_11:30" /></td>
					<td><input type="radio" id="quarta_11:30" value="quarta_11:30" /></td>
					<td><input type="radio" id="quinta_11:30" value="quinta_11:30" /></td>
					<td><input type="radio" id="sexta_11:30" value="sexta_11:30" /></td>
					<td><input type="radio" id="sabado_11:30" value="sabado_11:30" /></td>
				</tr>
				<tr>
					<td>12:00</td>
					<td><input type="radio" id="segunda_12:00" value="segunda_12:00" /></td>
					<td><input type="radio" id="terca_12:00" value="terca_12:00" /></td>
					<td><input type="radio" id="quarta_12:00" value="quarta_12:00" /></td>
					<td><input type="radio" id="quinta_12:00" value="quinta_12:00" /></td>
					<td><input type="radio" id="sexta_12:00" value="sexta_12:00" /></td>
					<td><input type="radio" id="sabado_12:00" value="sabado_12:00" /></td>
				</tr>
				<tr>
					<td>12:30</td>
					<td><input type="radio" id="segunda_12:30" value="segunda_12:30" /></td>
					<td><input type="radio" id="terca_12:30" value="terca_12:30" /></td>
					<td><input type="radio" id="quarta_12:30" value="quarta_12:30" /></td>
					<td><input type="radio" id="quinta_12:30" value="quinta_12:30" /></td>
					<td><input type="radio" id="sexta_12:30" value="sexta_12:30" /></td>
					<td><input type="radio" id="sabado_12:30" value="sabado_12:30" /></td>
				</tr>
				<tr>
					<td>13:00</td>
					<td><input type="radio" id="segunda_13:00" value="segunda_13:00" /></td>
					<td><input type="radio" id="terca_13:00" value="terca_13:00" /></td>
					<td><input type="radio" id="quarta_13:00" value="quarta_13:00" /></td>
					<td><input type="radio" id="quinta_13:00" value="quinta_13:00" /></td>
					<td><input type="radio" id="sexta_13:00" value="sexta_13:00" /></td>
					<td><input type="radio" id="sabado_13:00" value="sabado_13:00" /></td>
				</tr>
				<tr>
					<td>13:30</td>
					<td><input type="radio" id="segunda_13:30" value="segunda_13:30" /></td>
					<td><input type="radio" id="terca_13:30" value="terca_13:30" /></td>
					<td><input type="radio" id="quarta_13:30" value="quarta_13:30" /></td>
					<td><input type="radio" id="quinta_13:30" value="quinta_13:30" /></td>
					<td><input type="radio" id="sexta_13:30" value="sexta_13:30" /></td>
					<td><input type="radio" id="sabado_13:30" value="sabado_13:30" /></td>
				</tr>
				<tr>
					<td>14:00</td>
					<td><input type="radio" id="segunda_14:00" value="segunda_14:00" /></td>
					<td><input type="radio" id="terca_14:00" value="terca_14:00" /></td>
					<td><input type="radio" id="quarta_14:00" value="quarta_14:00" /></td>
					<td><input type="radio" id="quinta_14:00" value="quinta_14:00" /></td>
					<td><input type="radio" id="sexta_14:00" value="sexta_14:00" /></td>
					<td><input type="radio" id="sabado_14:00" value="sabado_14:00" /></td>
				</tr>
				<tr>
					<td>14:30</td>
					<td><input type="radio" id="segunda_14:30" value="segunda_14:30" /></td>
					<td><input type="radio" id="terca_14:30" value="terca_14:30" /></td>
					<td><input type="radio" id="quarta_14:30" value="quarta_14:30" /></td>
					<td><input type="radio" id="quinta_14:30" value="quinta_14:30" /></td>
					<td><input type="radio" id="sexta_14:30" value="sexta_14:30" /></td>
					<td><input type="radio" id="sabado_14:30" value="sabado_14:30" /></td>
				</tr>
				<tr>
					<td>15:00</td>
					<td><input type="radio" id="segunda_15:00" value="segunda_15:00" /></td>
					<td><input type="radio" id="terca_15:00" value="terca_15:00" /></td>
					<td><input type="radio" id="quarta_15:00" value="quarta_15:00" /></td>
					<td><input type="radio" id="quinta_15:00" value="quinta_15:00" /></td>
					<td><input type="radio" id="sexta_15:00" value="sexta_15:00" /></td>
					<td><input type="radio" id="sabado_15:00" value="sabado_15:00" /></td>
				</tr>
				<tr>
					<td>15:30</td>
					<td><input type="radio" id="segunda_15:30" value="segunda_15:30" /></td>
					<td><input type="radio" id="terca_15:30" value="terca_15:30" /></td>
					<td><input type="radio" id="quarta_15:30" value="quarta_15:30" /></td>
					<td><input type="radio" id="quinta_15:30" value="quinta_15:30" /></td>
					<td><input type="radio" id="sexta_15:30" value="sexta_15:30" /></td>
					<td><input type="radio" id="sabado_15:30" value="sabado_15:30" /></td>
				</tr>
				<tr>
					<td>16:00</td>
					<td><input type="radio" id="segunda_16:00" value="segunda_16:00" /></td>
					<td><input type="radio" id="terca_16:00" value="terca_16:00" /></td>
					<td><input type="radio" id="quarta_16:00" value="quarta_16:00" /></td>
					<td><input type="radio" id="quinta_16:00" value="quinta_16:00" /></td>
					<td><input type="radio" id="sexta_16:00" value="sexta_16:00" /></td>
					<td><input type="radio" id="sabado_16:00" value="sabado_16:00" /></td>
				</tr>
				<tr>
					<td>16:30</td>
					<td><input type="radio" id="segunda_16:30" value="segunda_16:30" /></td>
					<td><input type="radio" id="terca_16:30" value="terca_16:30" /></td>
					<td><input type="radio" id="quarta_16:30" value="quarta_16:30" /></td>
					<td><input type="radio" id="quinta_16:30" value="quinta_16:30" /></td>
					<td><input type="radio" id="sexta_16:30" value="sexta_16:30" /></td>
					<td><input type="radio" id="sabado_16:30" value="sabado_16:30" /></td>
				</tr>
				<tr>
					<td>17:00</td>
					<td><input type="radio" id="segunda_17:00" value="segunda_17:00" /></td>
					<td><input type="radio" id="terca_17:00" value="terca_17:00" /></td>
					<td><input type="radio" id="quarta_17:00" value="quarta_17:00" /></td>
					<td><input type="radio" id="quinta_17:00" value="quinta_17:00" /></td>
					<td><input type="radio" id="sexta_17:00" value="sexta_17:00" /></td>
					<td><input type="radio" id="sabado_17:00" value="sabado_17:00" /></td>
				</tr>
				<tr>
					<td>17:30</td>
					<td><input type="radio" id="segunda_17:30" value="segunda_17:30" /></td>
					<td><input type="radio" id="terca_17:30" value="terca_17:30" /></td>
					<td><input type="radio" id="quarta_17:30" value="quarta_17:30" /></td>
					<td><input type="radio" id="quinta_17:30" value="quinta_17:30" /></td>
					<td><input type="radio" id="sexta_17:30" value="sexta_17:30" /></td>
					<td><input type="radio" id="sabado_17:30" value="sabado_17:30" /></td>
				</tr>
			</table>	
			<span title="Selecione o horario início da manhã"><span>Hora Início Manhã:</span>
				<?php
					echo parent::display_jump_menu($horas,'hour_start_morning', $default, '');
				?>
			</span>

			<span title="Selecione o horario fim da manhã"><span>Hora Fim Manhã:</span>
				<?php
					echo parent::display_jump_menu($horas,'hour_end_morning', $default, '');
				?>
			</span>

			<span title="Selecione o horario início da tarde"><span>Hora Início Tarde:</span>
				<?php
					echo parent::display_jump_menu($horas,'hour_start_afternoon', $default, '');
				?>
			</span>

			<span title="Selecione o horario fim da tarde"><span>Hora Fim Tarde:</span>
				<?php
					echo parent::display_jump_menu($horas,'hour_end_afternoon', $default, '');
				?>
			</span>
			<br>
			<br>
			<div>
				<?php
					Template::display_action_button("Confirmar", "images/tick.png", "Agenda.criar_agen();",'button','',true,'Clique para confirmar a criação da agenda.');
            		Template::display_action_button("Voltar", "images/cross.png", "Agenda.cancelarErroTriagem()",'button','',true,'Clique para voltar.');
				?>
			</div>
		</form>	
		</div>
		<?php
	}

}

?>