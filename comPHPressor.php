<?php
/**
 * ComPHPressor
 *
 * @author Lloyd Watkin <lloyd@evilprofessor.co.uk>
 * @since  19/11/2011
 */
define('APPLICATION_PATH', realpath(__DIR__));

if ((false === file_exists(APPLICATION_PATH . '/config.ini'))
    || (false === is_readable(APPLICATION_PATH . '/config.ini'))
    || (!$config = parse_ini_file(APPLICATION_PATH . '/config.ini', true))
) {
    echo "Configuration file does not exist, is not "
        . "readable or invalid" . PHP_EOL;
    exit(1);
}
include APPLICATION_PATH . '/src/ComPHPressor.php';
$compiler = new ComPHPressor($config, getcwd());
$compiler->build();
