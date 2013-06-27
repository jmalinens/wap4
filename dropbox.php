<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past

require_once "/home/wap4/public_html/application/third_party/Dropbox/autoload.php";
$appInfo = \Dropbox\AppInfo::loadFromJsonFile("/home/wap4/public_html/application/config/dropbox.json");
$dbxConfig = new \Dropbox\Config($appInfo, "PHP-Example/1.0");
$webAuth = new \Dropbox\WebAuth($dbxConfig);

$sFile = $_GET['file'];
$aPath = pathinfo($sFile);
if (
    !in_array($aPath['extension'], ['mp4', 'mp3', 'avi', '3gp', 'aac', 'ogg', 'amr']) ||
    !ctype_alnum($aPath['filename']) ||
    !is_file(dirname(__FILE__)."/files/converted/$sFile")
) {
    echo "invalid file";
    exit;
}

if (empty($_GET['action'])) {
    
    list($requestToken, $authorizeUrl) = $webAuth->start("http://wap4.org/dropbox.php?file=$sFile&action=finish");

    setcookie("dropbox_auth", serialize($requestToken), time()+36000);
    header("Location: ".$authorizeUrl);
    exit;
    
} else {

    $requestToken = unserialize($_COOKIE['dropbox_auth']);
    list($accessToken, $dropboxUserId) = $webAuth->finish($requestToken);
    //print "Access Token: " . $accessToken->serialize() . "\n";

    $dbxClient = new \Dropbox\Client($dbxConfig, $accessToken);
    $accountInfo = $dbxClient->getAccountInfo();
    //print_r($accountInfo);

    $f = fopen(dirname(__FILE__)."/files/converted/$sFile", "rb");
    $result = $dbxClient->uploadFile('/'.$sFile, Dropbox\WriteMode::add(), $f);
    fclose($f);
    //print_r($result);
    ?>
    File uploaded to <?=$accountInfo['display_name']?> account :)<br/>
    File info:<br/>
    <ul>
        <li>Type: <?=$result['mime_type']?></li>
        <li>Size: <?=$result['size']?></li>
    </ul>
    <br/>
    <a href="http://www.dropbox.com">www.dropbox.com</a><br/>
    <a href="http://wap4.org">wap4.org</a>
    <?php
}