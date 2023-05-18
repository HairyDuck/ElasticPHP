[![StyleCI](https://github.styleci.io/repos/162427371/shield?branch=master)](https://github.styleci.io/repos/162427371?branch=master)

# ElasticPHP - Mini PHP Wrapper for AWS Elasticsearch

ElasticPHP is a simple and lightweight PHP interface for interacting with AWS Elasticsearch. It provides a minimalistic alternative to the AWS SDK, offering a small and fast solution without the need for PEAR or COMPOSER dependencies. This standalone library aims to simplify the process of working with AWS Elasticsearch by providing an easy-to-learn and easy-to-modify interface.

## Features

- Small and fast PHP wrapper for AWS Elasticsearch.
- Doesn't require PEAR or COMPOSER dependencies.
- Completely standalone and does not rely on the AWS SDK.

## Requirements

- PHP 7.0 or higher.
- An AWS account with access to Elasticsearch service.

## Installation

To use ElasticPHP, simply download the `ElasticPHP.php` file and include it in your PHP project.

```php
require_once 'ElasticPHP.php';
```

## Usage

### Initializing ElasticPHP

```php
$accessKey = 'YOUR_AWS_ACCESS_KEY';
$secretKey = 'YOUR_AWS_SECRET_KEY';
$endpoint = 'https://your-es-endpoint.amazonaws.com';

$es = new ElasticPHP($accessKey, $secretKey, $endpoint);
```

Replace `'YOUR_AWS_ACCESS_KEY'`, `'YOUR_AWS_SECRET_KEY'`, and `'https://your-es-endpoint.amazonaws.com'` with your actual AWS access key, secret key, and Elasticsearch endpoint URL respectively.

### Creating an Index

```php
$indexResponse = $es->createIndex('my_index');
```

### Deleting an Index

```php
$deleteResponse = $es->deleteIndex('my_index');
```

### Indexing a Document

```php
$document = [
    'title' => 'Sample Document',
    'content' => 'This is a sample document.',
];
$indexDocumentResponse = $es->indexDocument('my_index', $document);
```

### Searching Documents

```php
$searchResponse = $es->search('my_index', 'sample');
```

## Error Handling

ElasticPHP uses the HTTP status code to indicate the success or failure of a request. You can check the status code and response using the following code:

```php
$status = $response['status_code'];
$responseData = $response['response'];

if ($status === 200) {
    // Request was successful
    // Process $responseData
} else {
    // Request failed
    // Handle the error
}
```

## Contributing

Contributions are welcome! If you find any issues or have suggestions for improvements, please feel free to open an issue or submit a pull request.

## License

This project is licensed under the [MIT License](LICENSE).

---

That's it! You now have ElasticPHP, a mini PHP wrapper for AWS Elasticsearch, which allows you to perform basic operations such as creating an index, deleting an index, indexing documents, and searching for documents. Feel free to modify the code to suit your specific needs or add additional functionality as required.

If you have any further questions or need assistance, please don't hesitate to ask.
