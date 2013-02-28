<?php
require 'vendor/autoload.php';
require 'lib/cloudfront.php';

define(KEY_PAIR_ID, 'APKAJSJGP254DGRLS4QQ');
define(BASE_PATH, '/osha-php');

$privateKeyName = "pk-" . KEY_PAIR_ID . ".pem";
$privateKeyPath = realpath("config/" . $privateKeyName);
$expiration = 600;

$files = array(
  "Scaffold-demo-v04-x264-2000Kbps-720p",
  "Scaffold-Platform_Construction30p",
  "Scaffold-Foundation-30p-Apple-3000kbps",
  "Scaffold-Access-Part1",
  "Scaffold-Foundation-30p-x264-2000Kbps"
);

$app = new \Slim\Slim();

$app->get('/urls/:name', function ($name) use ($app) {
    global $expiration, $privateKeyPath;
    $urls = array(
      "rtmp" => "rtmpe://live.ekadoo.net/cfx/st/mp4:" . getSignedURL($name, $expiration, $privateKeyPath, KEY_PAIR_ID),
      "http" => getSignedURL('https://dte7jqtnaroib.cloudfront.net/' . $name . '.mp4', $expiration, $privateKeyPath, KEY_PAIR_ID)
    );
    $response = $app->response();
    $response['Content-Type'] = 'application/json';
    $response->body(str_replace("\\", "", json_encode($urls)));
});

$app->get('/video(/:name)', function ($name = null) use ($app) {
    global $files;
    $app->render("index.html.php", array("name" => $name ? $name : $files[0], "files" => $files));
});

$app->get('/', function () use ($app) {
    global $files;
    $app->redirect(BASE_PATH . '/video/' . $files[0]);
});

$app->run();
