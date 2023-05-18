<?php

class miniES
{
    private $awsAccessKey;
    private $awsSecretKey;
    private $esEndpoint;

    public function __construct($awsAccessKey, $awsSecretKey, $esEndpoint)
    {
        $this->awsAccessKey = $awsAccessKey;
        $this->awsSecretKey = $awsSecretKey;
        $this->esEndpoint = $esEndpoint;
    }

    private function sendRequest($method, $path, $params = [], $data = [])
    {
        $url = $this->esEndpoint.$path;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
        ]);

        if (!empty($data)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }

        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, $this->awsAccessKey.':'.$this->awsSecretKey);

        $response = curl_exec($ch);
        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        return [
            'status_code' => $statusCode,
            'response'    => $response,
        ];
    }

    public function createIndex($indexName)
    {
        $path = "/$indexName";
        $method = 'PUT';

        return $this->sendRequest($method, $path);
    }

    public function deleteIndex($indexName)
    {
        $path = "/$indexName";
        $method = 'DELETE';

        return $this->sendRequest($method, $path);
    }

    public function indexDocument($indexName, $document)
    {
        $path = "/$indexName/_doc";
        $method = 'POST';

        return $this->sendRequest($method, $path, [], $document);
    }

    public function search($indexName, $query)
    {
        $path = "/$indexName/_search";
        $method = 'GET';

        return $this->sendRequest($method, $path, ['q' => $query]);
    }
}

// Usage example:

$accessKey = 'YOUR_AWS_ACCESS_KEY';
$secretKey = 'YOUR_AWS_SECRET_KEY';
$endpoint = 'https://your-es-endpoint.amazonaws.com';

$es = new miniES($accessKey, $secretKey, $endpoint);

// Create an index
$indexResponse = $es->createIndex('my_index');
print_r($indexResponse);

// Index a document
$document = [
    'title'   => 'Sample Document',
    'content' => 'This is a sample document.',
];
$indexDocumentResponse = $es->indexDocument('my_index', $document);
print_r($indexDocumentResponse);

// Search documents
$searchResponse = $es->search('my_index', 'sample');
print_r($searchResponse);

// Delete an index
$deleteResponse = $es->deleteIndex('my_index');
print_r($deleteResponse);
