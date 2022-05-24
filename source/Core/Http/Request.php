<?php

namespace Source\Core\Http;

/**
 * Class Request
 * @package 
 */
class Request 
{
    /** @var string */
    private $apiUrl;

    /** @var array */
    private $headers;

    /**   */
    private $fields;

    /** @var string */
    private $endpoint;

    /** @var string */
    private $method;

    /** @var object */
    public $response;


     /**
     * @param array|Request $headers
     */
    public function headers(?array $headers): ?Request
    {
        if (!$headers) {
            return $this;
        }

        foreach ($headers as $key => $header) {
            $this->headers[] = "{$key}: {$header}";
        }

        return $this;
    }

    /**
     * @param string $method
     * @param string $endpoint
     * @param array|null $fields
     * @param array|null $headers
     */
    public function request(string $method, array $fields = null): void
    {
        
       
        $this->method = $method;
        $this->fields = $fields;
        $this->dispatch();
    }
    
    /**
     * get
     *
     * @param  mixed $url
     * @return Request
     */
    public function get(string $url): ?Request
    {
        $this->apiUrl = $url;
        $this->request('GET');
        return $this;
        
    }
    
    /**
     * post
     *
     * @param  mixed $url
     * @return null|Request
     */
    public function post(string $url, ?array $data = null): ?Request
    {
        $this->apiUrl = $url;
        
        $this->request('POST', $data);
        
        return $this;
    }
    
    /**
     * update
     *
     * @param  mixed $url
     * @return void
     */
    public function update(string $url)
    {
        $this->apiUrl = $url;
        $this->request('UPDATE');
        return $this;
    }
    
    /**
     * delete
     *
     * @param  mixed $url
     * @return void
     */
    public function delete(string $url)
    {
        $this->apiUrl = $url;
        $this->request('DELETE');
        return $this;
    }

    /**
     * @return 
     */
    public function response()
    {
        return $this->response;
    }

    /**
     * @return object|null
     */
    public function error()
    {
        if (!empty($this->response->errors)) {
            return $this->response->errors;
        }

        return null;
    }

    /**
     *
     */
    private function dispatch(): void
    {
        $curl = curl_init();

        $this->fields = '['.json_encode($this->fields).']'; //http_build_query para xxx-www-form-urlencode
       
        if($this->method == 'POST'){
           // var_dump($this->fields);
            //exit;
        }
     
        curl_setopt_array($curl, array(
            CURLOPT_URL => "{$this->apiUrl}",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $this->method,
            CURLOPT_POSTFIELDS => $this->fields,
            CURLOPT_HTTPHEADER => $this->headers
            )
        );

        $this->response = json_decode(curl_exec($curl));
        curl_close($curl);
    }
}