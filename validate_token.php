<?php

    header('Access-Control-Allow-Origin:*');
    header('Access-Control-Allow-Method:POST');
    header('Content-Type:application/json');
    require 'vendor/autoload.php';
    use Firebase\JWT\JWT;
    use Firebase\JWT\Key;
    $header = apache_request_headers();

        if(isset($header['x-auth-token']))
        {
            try{
            $token = $header['x-auth-token'];
            $decode = JWT::decode($token, new Key('FeedmixKaibiganKo', 'HS256'));
            $refresh_token=$decode->exp;
            }
            catch(Exception $e)
            {
                http_response_code(404);
                die();    
            }   
        }
        else
        {
            return false;
        }
?>