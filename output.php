<?php

ModelBase::setConnectionInfo(array(
    'host' => 'localhost',
    'dbname' => 'DB_NAME',
    'dbuser' => 'DB_USER',
    'password' => 'PASSWORD'
));

$db = new ModelBase;

$module_codes = $db->getModulesCode();
$snippet_codes = $db->getSnippetsCode();
$plugin_codes = $db->getPluginsCode();

foreach($module_codes as $module) {
    file_put_contents(__DIR__ . '/' . $module['name'].'.module.php', $module['modulecode']);
}

foreach($snippet_codes as $snippet) {
    file_put_contents(__DIR__ . '/' . $snippet['name'].'.snippet.php', $snippet['snippet']);
}

foreach($plugin_codes as $plugin) {
    file_put_contents(__DIR__ . '/' . $plugin['name'].'.plugin.php', $plugin['plugincode']);
}


class ModelBase
{
    private static $connInfo = array();
    protected $pdo = array();
    protected $name = '';
    protected $lastInsertId = 0;

    public function __construct()
    {
        $this->initDataBase();
    }

    final public function initDataBase()
    {
        $dsn = sprintf(
            'mysql:host=%s;dbname=%s;port=3306;charset=utf8mb4;',
            self::$connInfo['host'],
            self::$connInfo['dbname']
        );
        try {
            $this->pdo = new PDO(
                $dsn,
                self::$connInfo['dbuser'],
                self::$connInfo['password'],
                array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
        } catch (Exception $e) {
            echo 'error' . $e->getMesseage;
            die();
        }
    }

    final public static function setConnectionInfo(array $connInfo)
    {
        self::$connInfo = $connInfo;
    }

    public function getModulesCode()
    {
        $sql = 'SELECT * FROM `modx_site_modules`';
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            // $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($result === false) return array();
            return $result;
        } catch (PDOException $e) {
            echo 'error' . $e->getMesseage;
            die();
        }
    }


    public function getSnippetsCode()
    {
        $sql = 'SELECT * FROM `modx_site_snippets`';
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            // $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($result === false) return array();
            return $result;
        } catch (PDOException $e) {
            echo 'error' . $e->getMesseage;
            die();
        }
    }

    public function getPluginsCode()
    {
        $sql = 'SELECT * FROM `modx_site_plugins`';
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            // $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($result === false) return array();
            return $result;
        } catch (PDOException $e) {
            echo 'error' . $e->getMesseage;
            die();
        }
    }
}
