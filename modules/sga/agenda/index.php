<?php
SGA::check_access('sga.agenda');

try {    

    # verifica se o modulo esta devidamente instalado   
    Session::getInstance()->get(SGA::K_CURRENT_MODULE)->verifica();

    TAgenda::display_header("Agenda");

    $usuario = Session::getInstance()->get(SGA::K_CURRENT_USER);


    TAgenda::display_agenda(Session::getInstance()->get(SGA::K_CURRENT_USER));

    TAgenda::display_footer();
    
    
   
        
}catch (Exception $e) {
	Template::display_exception($e);
}
?>