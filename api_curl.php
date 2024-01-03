<?php
// Exemple de code officiel de Snusbase. Ce code est fourni tel quel et n’est fourni qu’à titre de référence sur la façon de
// gérer notre API. Pour accéder à l’API, contactez le service client par e-mail et décrivez brièvement votre
// cas d’utilisation et nous reviendrons vers vous dans les plus brefs délais.

function search(array $postData, $url = "https://api.snusbase.com/v3/search", $token="YOUR_AUTHENTICATION_TOKEN_HERE", $timeout = "40") 
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('authorization: '.$token));
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_USERAGENT, "Snusbase");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
        $response = curl_exec($ch);
        $curl_info = curl_getinfo($ch);
    
        return compact('response', 'curl_info');
        if(curl_errno($ch))
        {
            echo 'Curl error: ' . curl_error($ch);
        }
    }

// Create the search query / array (feed your own data, eg $type = "email"; $term = "test@test.com", etc)
$postData = Array("type" => $type, "term" => $term, "wildcard" => $wildcard, "limit" => $limit, "offset" => $offset);

// Pass search query / array to search function, store response in $apiResponse
$apiResponse = search($postData);
?>
