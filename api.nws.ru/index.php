<?php
    include 'orm/db.php';
    header('charset=utf-8');
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    if (isset($_GET['method'])) {

    } else if (isset($_POST['method'])) {
        $method = $_POST['method'];
        $data = json_decode($_POST['data']);

        switch ($method) {
            // Действие при авторизации
            case 'login':
                $user = R::findOne('users', 'phone = ?', [$data['phone']]);
                
                if (isset($user)) {
                    if ($user['password'] == md5($data['password'])) {
                        $code = 200;
                        $array = [
                            'code' => $code,
                            'token' => new_token($user)
                        ];
                        send_json($code, $array);
                    } else {
                        $code = 401;
                        $array = [
                            'code' => $code,
                            'error' => 'Check the authorization data',
                            'error_ru' => 'Проверьте данные авторизации'
                        ];
                        send_json($code, $array);
                    }
                } else {
                    $code = 401;
                    $array = [
                        'code' => $code,
                        'error' => 'Check the authorization data',
                        'error_ru' => 'Проверьте данные авторизации'
                    ];
                    send_json($code, $array);
                }
                break;

            case 'logout': 
                logout();
                break;
            
            // Действие при неизвестном методе
            default:
                $code = 400;
                $array = [
                    'code' => $code,
                    'error' => 'The method was not specified',
                    'error_ru' => 'Вы не указали метод'
                ];
                send_json($code, $array);
        }
    } else {
        // Действие при не заданом методе
        $code = 400;
        $array = [
            'code' => $code,
            'error' => 'The method was not specified',
            'error_ru' => 'Вы не указали метод'
        ];
        send_json($code, $array);
    }

    // Отправка ответа
    function send_json($code, $array) {
        http_response_code($code);
        $json = json_encode($array, JSON_UNESCAPED_UNICODE);
        echo $json;
    }









    



















    // if ($data['method'] !== '') {
    //     switch ($data['method']) {

    //         case 'check_token': 
    //             if (check_token(getallheaders()['Authorization'])) {
    //                 $code = 200;
    //                 $array = [
    //                     'code' => $code,
    //                     'check_token' => check_token(getallheaders()['Authorization'])
    //                 ];
    //                 send_json($code, $array);
    //             } else {
    //                 $code = 200;
    //                 $array = [
    //                     'code' => $code,
    //                     'check_token' => check_token(getallheaders()['Authorization'])
    //                 ];
    //                 send_json($code, $array);
    //             }
    //             break;
    //         
    //         default: 
    //             $code = 400;
    //             $array = [
    //                 'code' => $code,
    //                 'error' => 'The method was not specified',
    //                 'error_ru' => 'Вы не указали метод'
    //             ];
    //             send_json($code, $array);
    //             break;
    //     }
    // } else {
    //     $code = 400;
    //     $array = [
    //         'code' => $code,
    //         'error' => 'The method was not specified',
    //         'error_ru' => 'Вы не указали метод'
    //     ];
    //     send_json($code, $array);
    // }
    

    // // Создание нового токена
    // function new_token($user) {
    //     $bytes = random_bytes(40);
    //     $new_token = bin2hex($bytes);

    //     while (check_token($new_token)) {
    //         $new_token = bin2hex($bytes);
    //     }

    //     // Создание сессии (время жизни: 1 день)
    //     $session = R::dispense('sessions');
    //     $session->user_id = $user['id'];
    //     $session->token = 'Bearer '.$new_token;
    //     $session->lifetime = time() + 86400;
    //     R::store($session);

    //     return $new_token;
    // }

    // // Проверка токена
    // function check_token($token) {
    //     if (isset($token)) {
    //         $token = R::findOne('sessions', 'token = ?', [$token]);
    //         return isset($token);
    //     } else {
    //         $code = 401;
    //         $array = [
    //             'code' => $code,
    //             'error' => 'Check the authorization data',
    //             'error_ru' => 'Проверьте данные авторизации'
    //         ];
    //         send_json($code, $array);
    //     }
        
    // }

    // // Удаление сессии
    // function logout() {
    //     $token = getallheaders()['Authorization'];

    //     if (check_token($token)) {
    //         $session = R::findOne('sessions', 'token = ?', [$token]);
    //         R::trash($session);
    //         $code = 200;
    //         $array = [
    //             'code' => $code
    //         ];
    //         send_json($code, $array);
    //     } else {
    //         $code = 403;
    //         $array = [
    //             'code' => $code,
    //             'error' => 'Not enough rights',
    //             'error_ru' => 'Недостаточно прав'
    //         ];
    //         send_json($code, $array);
    //     }
    // }

