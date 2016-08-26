<?php
$regID = 'dsBwSXCCnAw:APA91bHIimyZTJCDSxwnfmrYCRZxjrESffGmkQbVLvwpje5JIhlbDynb4ivjx9Ry19BWzf16xh5kWHT7MvUgyChLfuIHUvOuGGcRZlt1YFi0SuJDBvHtuLmpKVAlFwHpZAyyuEvo2DBd';

$content = [
    'short' => 'Ao menos um livro nao foi renovado.',
    'long' => 'Livro 1 - Renovado Livro 2 - Nao Renovado Livro 3 - Nao Renovado'
];

$jsonObjectContent = [
    'to' => sprintf('%s',$regID),
    'notification' => ['short' => $content['short'], 'long' => $content['long']]
];
$url = 'https://fcm.googleapis.com/fcm/send';
$formatedContent = json_encode($jsonObjectContent);
$apiKey = 'AIzaSyCbM3-xQBdwIOjer9bICwCg9ZQS7FAubhs';
$cmd = 'curl --header "Authorization: key='.$apiKey.'" --header Content-Type:"application/json" '.$url.' -d \''.$formatedContent.'\'';

$cmd = 'curl --header "Authorization: key=AIzaSyCbM3-xQBdwIOjer9bICwCg9ZQS7FAubhs" --header Content-Type:"application/json" https://fcm.googleapis.com/fcm/send -d \'{"to":"dsBwSXCCnAw:APA91bHIimyZTJCDSxwnfmrYCRZxjrESffGmkQbVLvwpje5JIhlbDynb4ivjx9Ry19BWzf16xh5kWHT7MvUgyChLfuIHUvOuGGcRZlt1YFi0SuJDBvHtuLmpKVAlFwHpZAyyuEvo2DBd","notification":{"short":"Ao menos um livro nao foi renovado.","long":"Livro 1 - Renovado Livro 2 - Nao Renovado Livro 3 - Nao Renovadooooo"}}\'';


echo exec($cmd, $nome);
print_r($nome);
die;
