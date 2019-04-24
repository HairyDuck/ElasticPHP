<?php


class ES
{
    public static $utc_tz;
    const   ACCESS_KEY = 'XXXXXXXXXXXXXX';
    const   SECRET_KEY = 'XXXXXXXXXXXXXX';
    public $index;
    public $domain;

    public function setDomain($domain)
    {
        $this->domain = $domain;

        return $this;
    }

    public function setIndex($index)
    {
        $this->index = $index;

        return $this;
    }

    public function search($payload)
    {
        $f = $this->execute('_search', $payload);

        return $f;
    }

    private function execute($action, $payload)
    {
        if (!self::$utc_tz) {
            self::$utc_tz = new \DateTimeZone('UTC');
        }

        
        $datestamp = new \DateTime('now', self::$utc_tz);
        $longdate = $datestamp->format('Ymd\\THis\\Z');
        $shortdate = $datestamp->format('Ymd');

        $ksecret = 'AWS4'.self::SECRET_KEY;
        $kdate = hash_hmac('sha256', $shortdate, $ksecret, true);
        $kregion = hash_hmac('sha256', 'eu-west-1', $kdate, true);
        $kservice = hash_hmac('sha256', 'es', $kregion, true);
        $ksigning = hash_hmac('sha256', 'aws4_request', $kservice, true);

        $params = [
            'Expect'           => null,
            'Host'             => $this->domain.'.eu-west-1.es.amazonaws.com',
            'Accept'           => 'application/json',
            'Content-Type'     => 'application/json',
            'X-Amz-Date'       => $longdate,
        ];

        $params2S = [
            'host'           => $this->domain.'.eu-west-1.es.amazonaws.com',
            'X-Amz-Date'     => $longdate,
        ];

        $canonical_request = $this->createCanonicalRequest($params2S, $payload, '/'.$this->index.'/'.$action);

        $signed_request = hash('sha256', $canonical_request);
        $sign_string = "AWS4-HMAC-SHA256\n{$longdate}\n$shortdate/eu-west-1/es/aws4_request\n".$signed_request;
        $signature = hash_hmac('sha256', $sign_string, $ksigning);

        $params['Authorization'] = 'AWS4-HMAC-SHA256 Credential='.self::ACCESS_KEY."/$shortdate/eu-west-1/es/aws4_request, ".
        "SignedHeaders=host;x-amz-date, Signature=$signature";

        $url = 'https://'.$this->domain.'.eu-west-1.es.amazonaws.com/'.$this->index.'/'.$action;

        $curl_headers = [];
        foreach ($params as $p => $k) {
            $curl_headers[] = $p.': '.$k;
        }

        $curl = curl_init();

        curl_setopt_array($curl, [
          CURLOPT_URL            => $url,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_CONNECTTIMEOUT => 2,
          CURLOPT_TCP_NODELAY    => true,
          CURLOPT_SSL_VERIFYHOST => false,
          CURLOPT_SSL_VERIFYPEER => false,
          CURLOPT_TIMEOUT        => 2,
          CURLOPT_IPRESOLVE      => CURL_IPRESOLVE_V4,
          CURLOPT_POST           => count($payload),
          CURLOPT_POSTFIELDS     => json_encode($payload),
          CURLOPT_HTTPHEADER     => $curl_headers,
        ]);

        $result = json_decode(curl_exec($curl), true);
        curl_close($curl);

        return $result;
    }

    private function createCanonicalRequest(array $params, $payload, $uri = '/')
    {
        $canonical_request = [];
        $canonical_request[] = 'POST';
        $canonical_request[] = $uri;
        $canonical_request[] = '';

        $can_headers = [
          'host' => $this->domain.'.eu-west-1.es.amazonaws.com',
        ];

        foreach ($params as $k => $v) {
            $can_headers[strtolower($k)] = trim($v);
        }

        uksort($can_headers, 'strcmp');

        foreach ($can_headers as $k => $v) {
            $canonical_request[] = $k.':'.$v;
        }

        $canonical_request[] = '';
        $canonical_request[] = implode(';', array_keys($can_headers));
        $canonical_request[] = hash('sha256', json_encode($payload));

        $canonical_request = implode("\n", $canonical_request);

        return $canonical_request;
    }
}
