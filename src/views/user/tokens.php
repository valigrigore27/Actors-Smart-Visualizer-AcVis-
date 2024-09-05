<?php


function generateToken($username, $accountType, $id)
{
    $header = [
        'alg' => 'HS256',
        'typ' => 'JWT'
    ];

    $payload = [
        'username' => $username,
        'accountType' => $accountType,
        'id' => $id,
        'exp' => time() + (60 * 1000)

    ];

    $key = 'rfhbg32gv';
    $encodedHeader = base64_encode(json_encode($header));
    $encodedPayload = base64_encode(json_encode($payload));
    $signature = base64_encode(hash_hmac('SHA256', $encodedHeader . $encodedPayload, $key));

    $token = $encodedHeader . '.' . $encodedPayload . '.' . $signature;
    return $token;
}

function validateToken($token)
{
    $token_parts = explode('.', $token);

    $key = 'rfhbg32gv';
    $signature = base64_encode(hash_hmac('SHA256', $token_parts[0] . $token_parts[1], $key));

    if ($signature != $token_parts[2]) {
        return false; // Invalid signature
    }

    $payload = json_decode(base64_decode($token_parts[1]), true);

    if (isset($payload['exp']) && $payload['exp'] >= time()) {
        $username = $payload['username'];
        $accountType = $payload['accountType'];
        $id = $payload['id'];

        return true; // Valid token

    } else {
        return false;
    }
}
