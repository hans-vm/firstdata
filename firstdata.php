<?php
/**
 * Call the FirstData Payment API.
 * @param string $endpoint The API endpoint. Can be 'https://api.globalgatewaye4.firstdata.com/transaction/v12' or 'https://api.demo.globalgatewaye4.firstdata.com/transaction/v12'.
 * @param string $hmac_key HMAC key.
 * @param string  $key_id Key ID.
 * @param string $data_string The JSON encoded string of payment details. For example,
 *                            {
 *                                "gateway_id": "xxx",
 *                                "password": "xxx",
 *                                "transaction_type": "00",
 *                                "amount": "##",
 *                                "cc_number": "xxx",
 *                                "cc_expiry": "####",
 *                                "cardholder_name": "xxx"
 *                            }
 *
 */
function callFirstDataAPI($endpoint, $hmac_key, $key_id, $data_string) {
    $content_type = 'application/json; charset=UTF-8';
    $content_digest = sha1($data_string);
    $hashtime = gmdate('c');

    $hashstr = "POST\n" . $content_type . "\n" . $content_digest . "\n" . $hashtime . "\n/transaction/v12";
    $authstr = base64_encode(hash_hmac('sha1', $hashstr, $hmac_key, true));

    $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $endpoint);
        curl_setopt($ch, CURLOPT_VERBOSE, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_NOPROGRESS, 1);
        curl_setopt($ch, CURLOPT_SSLVERSION, 4);
        curl_setopt($ch, CURLOPT_SSL_CIPHER_LIST, 'SSLv3');
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: '.$content_type,
            'Accept: application/json',
            'Authorization: GGE4_API ' . $key_id . ':' .$authstr,
            'x-gge4-Date: ' . $hashtime,
            'x-GGe4-Content-SHA1: ' . $content_digest
        ));

        $res = curl_exec($ch);
    curl_close($ch);

    return json_decode($res);
}
