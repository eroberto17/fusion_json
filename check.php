<?php
$project_name = $_REQUEST["project_name"];
$app_name = $_REQUEST["app_name"];
$id = $_REQUEST["id"];
$repository = $_REQUEST["repository"];
$repository_second = $_REQUEST["repository_second"];
$repository_manifiesto = $_REQUEST["repository_manifiesto"];
$repository_manifiesto_second = $_REQUEST["repository_manifiesto_second"];

if(!isset($project_name)) {
    $project_name = '';
}
if(!isset($app_name)) {
    $app_name = '';
}
if(!isset($id)) {
    $id = '';
}
if(!isset($repository)) {
    $repository = '';
}
if(!isset($repository_second)) {
    $repository_second = '';
}
if(!isset($repository_manifiesto)) {
    $repository_manifiesto = '';
}
if(!isset($repository_manifiesto_second)) {
    $repository_manifiesto_second = '';
}

$file = 'Emtpy data: Utiliza el JSON de base';

$alias = $project_name.'-'.$app_name;

if($id == NULL){
    $id = 'Emtpy data: Conserva Hash ID original';
}

if($repository == NULL){
    $rep = 'Emtpy data: Conserva repositorio original';
}
else{
    $rep = 'https://github.com/'.$repository.'/'.$repository_second;
}

if($repository_manifiesto == NULL){
    $rep_man = 'Emtpy data: Conserva repositorio original';
}
else{
    $rep_man = 'https://github.com/'.$repository_manifiesto.'/'.$repository_manifiesto_second;
}

if (isset($_FILES['file']['tmp_name'])){
    $file = 'Archivo subido correctamente: '.$_FILES['file']['name'];
}

$arr=[
    "project_name" => $project_name,
    "app_name" => $app_name,
    "alias" => $alias,
    "id" => $id,
    "repository" => $rep,
    "repository_manifiesto" => $rep_man,
    "json_file" => $file,
];


$json =  json_encode($arr, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

// Output "no suggestion" if no hint was found or output correct values
echo $project_name === "" ? "no data" : $json;
?>