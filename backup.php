<?php
include 'config.php';

function get_timestanp($filename) {
    if(file_exists($filename)) {
        return date("Y-m-d H:i:s", filemtime($filename));
    }

    return "";
}

function human_filesize($bytes, $dec = 2): string {
    $size   = array('B', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
    $factor = floor((strlen($bytes) - 1) / 3);
    if ($factor == 0) $dec = 0;

    return sprintf("%.{$dec}f %s", $bytes / (1024 ** $factor), $size[$factor]);
}

function get_size($filename) {
    if(file_exists($filename)) {
        return human_filesize(filesize($filename));
    }

    return "";
}

$run = $_GET["run"];
$backuped = "";
if(is_numeric($run) and (int)$run < count($config)) {
    $name = $config[(int)$run]["name"];
    $path = $config[(int)$run]["path"];
    exec('zip -r backup_' . $name .'.zip "' . $path . '"');
    $backuped = $name;
}

?>
<!DOCTYPE html>
<html>
<head><title>Web Backup</title></head>
<body>
<?php
if($backuped != "") {
    echo('<div><p style="background-color: LawnGreen">' . $backuped . ' backed up.</p></div>');
}
echo("<table><tr><th>Name</th><th>Job</th><th>Date/Time</th><th>Size</th><th>Download</th></tr>\n");
for($row = 0; $row < count($config); $row++) {
    $name = $config[$row]["name"];
    echo('<tr><td>' . $name . '</td><td><a href="backup.php?run=' . $row . '">Run</a></td>');
    $filename = 'backup_' . $name .'.zip';
    echo('<td>' . get_timestanp($filename) . '</td>');
    echo('<td>' . get_size($filename) . '</td><td>');
    if(file_exists($filename)) {
        echo('<a href="' . $filename .'">download</a>');
    }
    echo("</td></tr>\n");
}
echo('</table>');
?>
</body>
</html>