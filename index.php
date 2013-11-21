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

define("PATH", "");
require('lib/environ.php');

function exception_handler(Exception $exception) {
  echo "<pre><h1>SGA Fatal Exception</h1>\nUncaught exception:\n" , $exception->getMessage(), "\n\nTrace:\n".$exception->getTraceAsString().'</pre>	';
}
set_exception_handler('exception_handler');

Session::getInstance();
// error handler function
/*function error_handler()
{
    $error = error_get_last();
    if ($error) {
        echo '<pre>';
        print_r($error);
        echo '</pre>';
    }
}
register_shutdown_function("error_handler");*/

//$a=lol();

// Workaround para Magic Quotes Enabled
// Magic Quotes esta depreciado a partir do PHP 5.3.0 e será removido no PHP 6
// mas ainda vem ativado por padrão no 5.2.x
if (get_magic_quotes_gpc()) {
    function stripslashes_deep($value)
    {
        $value = is_array($value) ?
                    array_map('stripslashes_deep', $value) :
                    stripslashes($value);

        return $value;
    }

    $_POST = array_map('stripslashes_deep', $_POST);
    $_GET = array_map('stripslashes_deep', $_GET);
    $_COOKIE = array_map('stripslashes_deep', $_COOKIE);
    $_REQUEST = array_map('stripslashes_deep', $_REQUEST);
}

if (!Config::SGA_INSTALLED) {
	if (isset($_GET['inst_redir'])) {
		$value = $_GET['inst_redir'];
		if (!empty($value) && ($path = SGA::is_install_path($value))) {
	    	SGA::_include($path);
	    }
	}
	else {
		SGA::_include('install/install.php');
	}
	exit();
}

if (sizeof($_GET) == 0) {
	// redirecionar para o modulo padrão
	// TODO modulo padrão deve ser configuravel
	if ($path = SGA::is_module_path('sga.inicio')) {
		SGA::_include($path);
	}
}
else {
	foreach ($_GET as $key => $value) {
	
	    switch ($key) {
	    case SGA::K_NEED_LOGIN:
	    	
	        SGA::_include("login/index.php?mod=".$_GET['dest']);
	        break;
	    case SGA::K_REG_LOGIN:
	        SGA::_include("login/reg_log.php");
	        break;
	    case SGA::K_NEED_UNIDADE:
	        SGA::_include("unidade/index.php");
	        break;
	    case SGA::K_SET_UNIDADE:
	        SGA::_include("unidade/set_unidade.php");
	        break;
	    case SGA::K_LOGOUT:
	        SGA::_include("logout/index.php");
	        break;
	    case SGA::K_RED_MOD:
	        if (!empty($value) && ($path = SGA::is_module_path($value))) {
	        	SGA::_include($path);
	        }
	        break;
	    case SGA::K_DIALOG:
	    	if (!empty($value) && ($path = SGA::is_dialog_path($value))) {
	            SGA::_include($path);
	        }
	        break;
	    case SGA::K_RED_UNI:
	    	
	    	break;
	    case SGA::K_RED_ONLY:
	        if (!empty($value)) {
	            SGA::_include($value);
	        }
	        break;
	    default:
	    	
	    }
	}    
}



?>
