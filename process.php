<?php
$project_name = $_REQUEST["project_name"];
$app_name = $_REQUEST["app_name"];
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

$alias = $project_name.'-'.$app_name;


if (isset($_FILES['file']['tmp_name'])){
    $jsonString = file_get_contents($_FILES['file']['tmp_name']);
}
else{
    $jsonString = file_get_contents('file.json');
}

$data = json_decode($jsonString, true);

if (isset($data['artifacts'][0]['sourceId'])) {

    //sourceId
    $text = explode(":", $data['artifacts'][0]['sourceId']);
    $text_second_part = explode("/", $text[1]);

    $repository_original = $text_second_part[0];
    if($repository != NULL) {
        $text_second_part[0] = $repository;
    }
    else{
        $repository = $text_second_part[0];
    }

    $app_name_text = explode("-", $text_second_part[1]);

    $jsonReplace = str_replace(array($repository_original,$text_second_part[1], $app_name_text[2]),array($text_second_part[0], $alias, $app_name),$jsonString);

    $data = json_decode($jsonReplace, true);

    //definitionReference
    if($repository_second != NULL) {
        $data['artifacts'][0]['definitionReference']['artifactSourceDefinitionUrl']['id'] = 'https://github.com/'.$text_second_part[0].'/'.$repository_second;
    }
    if($repository_manifiesto != NULL) {
        $data['artifacts'][1]['definitionReference']['artifactSourceDefinitionUrl']['id'] = 'https://github.com/'.$repository_manifiesto.'/'.$repository_manifiesto_second;
        $data['artifacts'][2]['definitionReference']['artifactSourceDefinitionUrl']['id'] = 'https://github.com/'.$repository_manifiesto.'/'.$repository_manifiesto_second;
    }

/*
//Replace Harcodeado//

    $text_second_part[1] = $alias;

    $new_value_as = $text[0].':'.$text_second_part[0].'/'.$text_second_part[1];
    $data['artifacts'][0]['sourceId']=$new_value_as;

    //alias
    $data['artifacts'][0]['alias'] = $alias;

    //definitionReference
    $data['artifacts'][0]['definitionReference']['artifactSourceDefinitionUrl']['id'] = 'https://github.com/'.$text_second_part[0].'/'.$text_second_part[1];

    //definition
    $data['artifacts'][0]['definitionReference']['definition']['id'] = $text_second_part[0].'/'.$text_second_part[1];
    $data['artifacts'][0]['definitionReference']['definition']['name'] = $text_second_part[0].'/'.$text_second_part[1];

//End Replace Harcodeado//
*/

}

$arr=[
    "project_name" => $project_name,
    "app_name" => $app_name,
    "alias" => $alias,
    "repository" => $repository,
];


$json =  json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

// Output "no suggestion" if no hint was found or output correct values
echo $project_name === "" ? "no data" : $json;
?>