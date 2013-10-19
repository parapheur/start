<?php

// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../library'),
    get_include_path(),
)));

/** Zend_Application */
require_once 'Zend/Application.php';

// Create application, bootstrap, and run
$application = new Zend_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/configs/application.ini'
);
$application->bootstrap()
            ->run();

//Ce qui a été rajouté :

// $configPath sera reprise sous forme de tableau
$configPath = '../application/configs/application.ini';
// Création d'un objet à partir du fichier  ini
$config = new Zend_Config_Ini ( $configPath, 'production' );


// Construction d'un objet $db permettant d'utiliser la base de données
$db = Zend_Db_Table::getDefaultAdapter();



// Si on  veut  forcer l'adaptateur à se connecter au SGBD, on utilise sa méthode
// getConnection(),elle retournera alors un objet représentant la connexion en fonction de
// l'extension PHP utilisée, ou une exception si la connexion n'a pas été réalisée.
// Par exemple, si notre adaptateur utilise PDO, le retour sera un objet PDO.
$db->getConnection ();

//Un registre est un conteneur pour stocker des objets et des valeurs dans l'espace d'application.
//En stockant la valeur dans le registre, le même objet est toujours disponible partout dans
//votre application. Ce mécanisme est une alternative à l'utilisation du stockage global.
Zend_Registry::set ( 'db', $db );


// BD par default
Zend_Db_Table::setDefaultAdapter ( $db );
