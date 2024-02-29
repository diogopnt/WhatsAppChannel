<?php
// Replace this URL with the RSS feed you want to fetch
$jsonFeedUrl = 'https://terranova-d10.pictonio.pt/recentnews';

// Fetch the RSS feed
$jsonData = file_get_contents($jsonFeedUrl);

if ($jsonData !== false) {
    //Decode the JSON data
    $feedItems = json_decode($jsonData, true);

    foreach ($feedItems as $item) {
        $title = $item['title'];
        $link = $item['view_node'];

        sendMessage($title, $link);
    }
} else {
    echo "Error fetching JSON feed";
}

function sendMessage($title, $link)
{
    $curl = curl_init();

    $url = 'https://graph.facebook.com/v18.0/253772191149690/messages';
    $token = 'EAAP34pBSvoIBO5p5c7lwsuggxt0LwPlfqJsX7KMkRJhyQbOJBG9WBWMhxhMl2mzcJBTFGH3YE0IEauW6CJxxZBVinnY6tGxFCTzw6MQMCgcB46JZBKdcAmXxuuH2WRrcZCdCqZB2NKtFkSZB1Fxjqcm8SDmusvUhZCA5sXyLSIQsjhHfGYjZBIi2SjuikHkOdZA6vPMg4LZC92AxfwJKGZAw49';

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
