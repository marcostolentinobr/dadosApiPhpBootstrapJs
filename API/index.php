<?

//BANCO DE DADOS
define('DB_LIB', 'mysql');
define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'CEP');
define('DB_CHARSET', 'utf8');
define('DB_USER', 'root');
define('DB_PASS', '');

//PRINT_R
function pr($dado, $print_r = true) {
    echo '<pre style="text-align: left">';
    if ($print_r) {
        print_r($dado);
    } else {
        var_dump($dado);
    }
    echo '</pre>';
}

//URL
$url = [];
if (isset($_GET['pg'])) {
    $url = explode('/', $_GET['pg']);
}
define('CLASSE', isset($url[0]) ? $url[0] : '');
define('METODO', isset($url[1]) ? $url[1] : '');
define('CHAVE', isset($url[2]) ? $url[2] : '');

//Arquivo existe inclui
$fileApi = 'modulos/' . CLASSE . '.php';
if (file_exists($fileApi)) {
    require_once 'Conexao.php';
    require_once $fileApi;
}

//Classe existe instancia
$Classe = CLASSE;
if (class_exists($Classe)) {
    $Classe = new $Classe();

    //Metodo existe chama
    $Metodo = METODO;
    if (method_exists($Classe, $Metodo)) {
        $Classe->$Metodo();
    }
}