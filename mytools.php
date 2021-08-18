<?php
/*Initialize*/
$file = $argv[1];
$openFile = fopen($file, "r");
$data = fread($openFile,filesize($file));
$isFormat = false;
$isOutput = false;
$format = 'text';
$output = '';
foreach($argv as $key => $arg){
    if($arg == '-t' || $arg == '--type'){
        $isFormat = true;
        $input_format = $argv[$key+1];
        if($input_format == 'json'){
            $format = 'json';
        }elseif($input_format == 'text'){
            $format = 'text';
        }else{
            $format = 'error';
        }
    }
    if($arg == '-o' || $arg == '--output'){
        $isOutput = true;
        $output = $argv[$key+1];
    }

    if($arg == '-h' || $arg == '--help'){
        echo "Usage: php mytools.php [FILE]... [OPTION]...
To get log from certain log path in the system.

Mandatory arguments to long options are mandatory for short options too.
-t, --type                 to select file type output (text/json).
-o, --output               select specific output on the system
-h, --help                 display help menu";
        echo "\n";
        exit;
    }
}
fclose($openFile);
/*Proccess*/
$file_name = 'log.txt';
$data_finalize = '';
if($isFormat){
    if($format=='error'){
        echo "\nFormat not supported! check 'php mytools.php -h' for help \n";
        exit;
    }else{
        if($format == 'json'){
            $explode_data = explode(PHP_EOL,$data);
            $arr['log'] = array_values($explode_data);
            $data_finalize = json_encode($arr);
        }else{
            $data_finalize = $data;
            $data_finalize = str_replace("\/","/",$data_finalize);
        }
    }
}else{
    echo "\nNo format selected! check 'php mytools.php -h' for help \n";
    exit;
}
if($isOutput){
    $isOutput = true;
    $file_name = $output;
}
/*Execute*/
$execute = file_put_contents($file_name, $data_finalize);
if($execute){
    echo "\nImport log success!\n";
    if($isOutput){
        $shell = shell_exec('ls -l '.$file_name);
    }else{
        $shell = shell_exec('ls -l | grep '.$file_name);
    }
    echo $shell."\n";
    exit;
}else{
    echo "\nImport log failed! check 'php mytools.php -h' for help \n";
    exit;
}
?>