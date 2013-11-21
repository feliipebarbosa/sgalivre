<?php
try {
    if (Config::SGA_INSTALLED) {
        Template::display_error('O SGA já está instalado.');
    }
    else {
        if (Session::getInstance()->exists('SGA_INSTALL_STEP')) {
            phpinfo();
        }
    }
}
catch (Exception $e) {
    Template::display_exception($e);
}
?>
