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
<html><head><title>Web Backup</title>
<style>
body {
  font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
  padding: 1em;
}
p.success {
  background-color: LawnGreen;
  padding: 1em;
}
.backups {
  font-family: Arial, Helvetica, sans-serif;
  border-collapse: collapse;
}
.backups td, .backups th {
  border: 1px solid #ddd;
  padding: 12px 16px;
}
.backups tr:nth-child(even) { background-color: #f2f2f2; }
.backups tr:hover { background-color: #ddd; }
.backups th {
  text-align: left;
  background-color: navy;
  color: white;
}
.btn {
  border-radius: 5px;
  color: white;
  background-color: darkorchid;
  padding: 6px 18px;
  text-decoration: none;
}
</style></head>
<body>
<h1>Web Backup</h1>
<?php
if($backuped != "") {
    echo('<div><p class="success"><b>' . $backuped . '</b> backed up.</p></div>');
}
echo('<table class="backups"><tr><th>Name</th><th>Job</th><th>Date/Time</th><th>Size</th><th>Download</th></tr>');
echo("\n");
for($row = 0; $row < count($config); $row++) {
    $name = $config[$row]["name"];
    echo('<tr><td>' . $name . '</td><td><a class="btn" href="backup.php?run=' . $row . '">Run</a></td>');
    $filename = 'backup_' . $name .'.zip';
    echo('<td>' . get_timestanp($filename) . '</td>');
    echo('<td>' . get_size($filename) . '</td><td>');
    if(file_exists($filename)) {
        echo('<a class="btn" href="' . $filename .'">Download</a>');
    }
    echo("</td></tr>\n");
}
echo('</table>');
?>
<div><br /><a class="btn" href="backup.php">Reload</a></div>
</body>
</html>