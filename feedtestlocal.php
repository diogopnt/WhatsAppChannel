<?php
// Replace this URL with the RSS feed you want to fetch
$jsonFilePath = 'feed.json';

// Read the JSON data from the file
$jsonData = file_get_contents($jsonFilePath);

if ($jsonData !== false) {
    // Decode the JSON data
    $feedItems = json_decode($jsonData, true);

    if ($feedItems !== null) {
        foreach ($feedItems as $item) {
            $title = $item['title'];
            $link = $item['view_node'];

            sendMessage($title, $link);
        }
    } else {
        echo "Error decoding JSON data";
    }
} else {
    echo "Error reading JSON file";
}

function sendMessage($title, $link)
{
    $curl = curl_init();

    $url = 'https://graph.facebook.com/v18.0/253772191149690/messages';
    $token = 'EAAP34pBSvoIBO0hlMXISSveuMV6P1pnk8xFGfKLvtM0n6xZAG9j4jBEK39niO1mYdpnjdXSPS9AUmSuIU8vkZBH1Um85EeE0DZB6G7dY6KJuBoQF6x0lWIgfcPXioj7cXTUrFweuFxqNJsi8CwJ1bZCEjIZBScWTSKpOjCD2VM1IbR5BNnlW3PW8aLHwTfkZCCZBqTJIbEu3VFxAisn820ZD';

    $payload = '
    {
      "messaging_product": "whatsapp",
      "recipient_type": "individual",
      "to": "351911197005",
      "type": "text",
      "text": {
        "preview_url": true,
        "body": "' . $title . '\n' . $link . '"
        }
    }';

    echo "<br>";
    echo "----";
    echo "<br>";
    echo $payload;

    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => $payload,
        CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer ' . $token,
            'Content-Type: application/json',
            'Cookie: ps_l=0; ps_n=0'
        ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    echo $response;
}
