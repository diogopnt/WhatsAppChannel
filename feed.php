<?php
// Replace this URL with the RSS feed you want to fetch
$rssFeedUrl = 'https://www.jornaldenegocios.pt/rss';

// Fetch the RSS feed
$rssData = file_get_contents($rssFeedUrl);

if ($rssData !== false) {
    //Parse the XML data
    $xml = new SimpleXMLElement($rssData);

    foreach ($xml->channel->item as $item) {
        $title = (string)$item->title;
        $link = (string)$item->link;
        $description = (string)$item->description;

        sendMessage($title, $link, $description);
    }
} else {
    echo "Error fetching RSS feed";
}

function sendMessage($title, $link, $description)
{
    $curl = curl_init();

    $url = 'https://graph.facebook.com/v18.0/253772191149690/messages';
    $token = 'EAAP34pBSvoIBO4NEYZCdHvrIrH4nMUSCsruYADkqttZCKhiI5eucogTjszW6xrkLtO35ecRo1mQcS0CsN0zUShSdZBhNzo2SXLdWstZCuqeBxqWL9vZCJ0vfAkvieaKSlR836TSeKMZA1p6qP1lSfHb8toQUWZCiR4sI4hrRfmYCcm067Ne0DZBkDIRdhZBhtHtENCZBdkjZBvaJWVogSgSKpAZD';

    $payload = '
    {
      "messaging_product": "whatsapp",
      "recipient_type": "individual",
      "to": "351911197005",
      "type": "text",
      "text": {
        "preview_url": false,
        "body": "' . $title . '"
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
