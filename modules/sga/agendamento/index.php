<?php
SGA::check_access('sga.agendamento');

try {    

    # verifica se o modulo esta devidamente instalado   
    Session::getInstance()->get(SGA::K_CURRENT_MODULE)->verifica();

    TAgendamento::display_header("Agendamento");

    $usuario = Session::getInstance()->get(SGA::K_CURRENT_USER);


    TAgendamento::display_agendamento(Session::getInstance()->get(SGA::K_CURRENT_USER));

    TAgendamento::display_footer();
    
    
   
        
}catch (Exception $e) {
	Template::display_exception($e);
}
?>