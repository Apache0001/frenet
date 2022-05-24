<?php

namespace Source\Core;

/**
 * Request
 * @package Source\Core\Request
 */
class Request
{
    /** @var string */
    private $apiUrl;

    /** @var null|array */
    private $headers;

    /** @var null|array */
    private $fields;

    /** @var string */
    private $endpoint;

    /** @var string */
    private $method;

    /** @var mixed */
    private $response;

    /**
     * construct
     * @param string
     */
    public function __construct(string $apiUrl)
    {
        $this->apiUrl = $apiUrl;
        return;
    
    }

    /**
     * request
     *
     * @param string $method
     * @param string $endpoint
     * @param array|null $fields
     * @param array|null $headers
     * @return null|Request
     */
    public function request(string $method, string $endpoint, array $fields = null, array $headers = null): ?Request
    {
        $this->method =  $method;
        $this->endpoint = $endpoint;
        $this->fields = $fields;
        $this->headers($headers);
        

        $this->dispatch();

        return $this;
    }

    /**
     * headers
     *
     * @param array|null $headers
     * @return null|Request
     */
    public function headers(?array $headers): ?Request
    {
        if(!$headers){
            return null;
        }

        foreach($headers as $key => $header){
            $this->headers[] =  "{$key}: {$header}";
        }

        return $this;
    }

    /**
     * response
     *
     * @return mixed
     */
    public function response()
    {
        return $this->response;
    }

    /**
     * error
     *
     * @return mixed
     */
    public function error()
    {
        if(!empty($this->response->errors)){
            return $this->response->errors;
        }
    }

    /**
     * dispatch
     *
     * @return void
     */
    private function dispatch(): void
    {
        $curl = curl_init();

        if(empty($this->fields["files"])){
            $this->fields = (!empty($this->fields) ? http_build_query($this->fields) : null);
        }

        curl_setopt_array($curl, array(
            CURLOPT_URL => "{$this->apiUrl}/{$this->endpoint}",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $this->method,
            CURLOPT_POSTFIELDS => $this->fields,
            CURLOPT_HTTPHEADER => $this->headers
        ));

        $this->response = json_decode(curl_exec($curl));
        curl_close($curl);
        return;
    }


}