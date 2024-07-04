<?php

if (isset($_GET['db_url'])) {
    $url = $_GET['db_url'];
    echo "<br/>Database url: <br/> $url";
    echo "<br/>";
    echo "<br/>Database version: <br/>";

    try {
        // mysql:host=db-mysql;dbname=laravel;port=3306;user=adminz;password=123123
        // pgsql:host=db-pgsql;dbname=laravel;port=5432;user=adminz;password=123123
        $conn = new PDO($url);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $conn->query("SELECT version() AS version");
        $stmt->execute();
        $result = $stmt->fetchObject()->version;
        echo "$result";
    } catch (\Exception | PDOException $e) {
        var_dump($e);
    }
}

$flag = INFO_ALL;

if (isset($_GET['flag'])) {
    $flag = $_GET['flag'] == 'modules' ? INFO_MODULES : INFO_ALL;
}

if (isset($_GET['putenv'])) {
    putenv($_GET['putenv']);
}

phpinfo($flag);

// php -m
//php -r "print_r(get_loaded_extensions());"
//php -r "var_dump(extension_loaded('pdo'));"
//php -r "var_dump(extension_loaded('pdo_mysql'));"
//php -r "var_dump(extension_loaded('pdo_pgsql'));"
//php -r "var_dump(extension_loaded('json'));"
