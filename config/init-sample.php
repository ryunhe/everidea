<?php
define('HTDOCS_PATH', dirname(dirname(__FILE__)));

ini_set("include_path", ini_get("include_path") . PATH_SEPARATOR . HTDOCS_PATH . "/lib" . PATH_SEPARATOR);
function autoload($class) {
    include_once (strtolower(str_replace('\\', DIRECTORY_SEPARATOR, $class)) . '.php');
}
spl_autoload_register('autoload');

// Use a session to keep track of temporary credentials, etc
session_start();

// require_once("Thrift/autoload.php");
require_once ("Thrift/Thrift.php");
require_once ("Thrift/transport/TTransport.php");
require_once ("Thrift/transport/THttpClient.php");
require_once ("Thrift/protocol/TProtocol.php");
require_once ("Thrift/protocol/TBinaryProtocol.php");
require_once ("Thrift/packages/Errors/Errors_types.php");
require_once ("Thrift/packages/Types/Types_types.php");
require_once ("Thrift/packages/UserStore/UserStore.php");
require_once ("Thrift/packages/UserStore/UserStore_constants.php");
require_once ("Thrift/packages/NoteStore/NoteStore.php");
require_once ("Thrift/packages/Limits/Limits_constants.php");

// Dev token
define('AUTH_TOKEN', '');

// OAuth secret
define('OAUTH_CONSUMER_KEY', '');
define('OAUTH_CONSUMER_SECRET', '');

// Specify notebook
define('EVERIDEA_NOTEBOOK', '');

define('EVERNOTE_SERVER', 'https://www.evernote.com');
define('NOTESTORE_HOST', 'www.evernote.com');
define('NOTESTORE_PORT', '443');
define('NOTESTORE_PROTOCOL', 'https');

// Evernote server URLs. You should not need to change these values.
define('REQUEST_TOKEN_URL', EVERNOTE_SERVER . '/oauth');
define('ACCESS_TOKEN_URL', EVERNOTE_SERVER . '/oauth');
define('AUTHORIZATION_URL', EVERNOTE_SERVER . '/OAuth.action');

?>