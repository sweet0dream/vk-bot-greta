<?php

ini_set('display_errors', 1);

require_once('vendor/autoload.php');
include 'vk_bot-func.php';

$confirmation_token = '044d973a';
$data = json_decode(file_get_contents('php://input'));

//print_r(getUserVK(21577652));

if(isset($data->type) && $data->type !== NULL) {
    switch ($data->type) {
        case 'confirmation': 
            echo $confirmation_token; 
        break;  
            
        case 'message_new': 
            $chat_id = $data -> object -> peer_id;
            $message_text = explode(' ', $data -> object -> text);
            $user_id = $data -> object -> from_id;
    
            if($message_text[0] == 'Грета') {
                if($message_text[1] == 'правила') {
                    vk_msg_send($chat_id, getRules());
                } elseif($message_text[1] == 'привет') {
                    $response = getPrivet($user_id);
                    if(isset($response)) {
                        vk_msg_send($chat_id, $response);
                    }
                } elseif($message_text[1] == 'фас') {
                    $response = getFas($message_text[2], $user_id);
                    if(!empty($response) && isset($response['message']) && isset($response['attach'])) {
                        vk_msg_send($chat_id, $response['message'], $response['attach']);
                    }
                } elseif($message_text[1] == 'лизни') {
                    $response = getLiz($message_text[2]);
                    if(!empty($response) && isset($response['message']) && isset($response['attach'])) {
                        vk_msg_send($chat_id, $response['message'], $response['attach']);
                    }
                } elseif($message_text[1] == 'анекдот') {
                    vk_msg_send($chat_id, getJoke(11));
                } elseif($message_text[1] == 'вопрос:') {
                    vk_msg_send($chat_id, getRandomAnswer($message_text));
                } elseif($message_text[1] == 'погода') {
                    vk_msg_send($chat_id, getWeather());
                } elseif($message_text[1] == 'цуефа') {
                    vk_msg_send($chat_id, getCUEFA($message_text[2]));
                } elseif($message_text[1] == 'унизить') {
                    $response = getHumiliate($message_text[2]);
                    if(isset($response['attach'])) {
                        vk_msg_send($chat_id, $response['text'], $response['attach']);
                    } else {
                        vk_msg_send($chat_id, $response['text']);
                    }
                } elseif($message_text[1] == 'уничтожить') {
                    $response = getDestroy($message_text[2]);
                    if(isset($response['attach'])) {
                        vk_msg_send($chat_id, $response['text'], $response['attach']);
                    } else {
                        vk_msg_send($chat_id, $response['text']);
                    }
                } elseif($message_text[1] == 'ники') {
                    vk_msg_send($chat_id, showNick('all'));
                } elseif($message_text[1] == 'ник') {
                    vk_msg_send($chat_id, showNick(isLinkUser($message_text[2])));
                } elseif($message_text[1] == 'мой') {
                    if($message_text[2] == 'ник') {
                        if(isset($message_text[3])) {
                            vk_msg_send($chat_id, setNick([
                                'nick' => $message_text[3],
                                'user' => $user_id
                            ]));
                        } else {
                            vk_msg_send($chat_id, showNick($user_id));
                        }
                    }
                }
            }
            echo 'ok';
        break;
    }
}


?>