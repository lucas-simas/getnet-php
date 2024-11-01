<?php
namespace Getnet\API;

use Exception;
use Getnet\API\Exception\GetnetException;

/**
 * Class Request
 *
 * @package Getnet\API
 */
class Request
{

    /**
     * Base url from api
     *
     * @var string
     */
    private $baseUrl = '';

    private $raw_request = '';

    private $raw_response = '';

    public const CURL_TYPE_AUTH = "AUTH";

    public const CURL_TYPE_POST = "POST";

    public const CURL_TYPE_PUT = "PUT";

    public const CURL_TYPE_GET = "GET";

    public const CURL_TYPE_DELETE = "DELETE";

    /**
     * Request constructor.
     *
     * @param Getnet $credentials
     * TODO create local variable to $credentials
     */
    public function __construct(Getnet $credentials)
    {
        $this->baseUrl = $credentials->getEnvironment()->getApiUrl();

        if (! $credentials->getAuthorizationToken()) {
            $this->auth($credentials);
        }
    }

    /**
     *
     * @param Getnet $credentials
     * @return Getnet
     * @throws Exception
     */
    public function auth(Getnet $credentials)
    {
        if ($this->verifyAuthSession($credentials)) {
            return $credentials;
        }

        $scope = $credentials->getScope();
        $url_path = "/auth/oauth/v2/token";
        if( $scope == 'mgm' ){
            $url_path = "/credenciamento/auth/oauth/v2/token";
        }

        $params = [
            "scope"         => $scope,
            "grant_type"    => "client_credentials"
        ];

        $querystring = http_build_query($params);

        try {
            $response = $this->send($credentials, $url_path, self::CURL_TYPE_AUTH, $querystring);
        } catch (Exception $e) {
            throw new GetnetException($e->getMessage(), 100);
        }

        $credentials->setAuthorizationToken($response["access_token"]);
       
        // Save auth session
        if ($credentials->getKeySession()) {
            $response['generated'] = microtime(true);
            $_SESSION[$credentials->getKeySession()] = $response;
        }

        return $credentials;
    }

    /**
     * start session for use
     *
     * @param Getnet $credentials
     * @return boolean
     */
    private function verifyAuthSession(Getnet $credentials)
    {
        if ($credentials->getKeySession() && isset($_SESSION[$credentials->getKeySession()]) && $_SESSION[$credentials->getKeySession()]["access_token"]) {

            $auth = $_SESSION[$credentials->getKeySession()];
            $now = microtime(true);
            $init = $auth["generated"];

            if (($now - $init) < $auth["expires_in"]) {
                $credentials->setAuthorizationToken($auth["access_token"]);

                return true;
            }
        }

        return false;
    }

    /**
     *
     * @param Getnet $credentials
     * @param mixed $url_path
     * @param mixed $method
     * @param mixed $jsonBody
     * @throws Exception
     * @return mixed
     * @throws \Exception
     */
    private function send(Getnet $credentials, $url_path, $method, $jsonBody = null) {

        $curlConnection = curl_init();

        //Definindo header
        $header = [
            "Accept: application/json", 
            "Content-Type: application/json",
            'Authorization: Basic '. base64_encode($credentials->getClientId() . ':' . $credentials->getClientSecret())
        ];

        // Auth
        if ($method === self::CURL_TYPE_AUTH) {
            $header[1] = 'application/x-www-form-urlencoded';
        } 
        else {
            //
            if (! empty($credentials->getSellerId())) {
                $header[] = 'seller_id: ' . $credentials->getSellerId();
            }
            $header[2] = 'Authorization: Bearer ' . $credentials->getAuthorizationToken();
        }

        curl_setopt($curlConnection, CURLOPT_URL, $this->getFullUrl($url_path));
        curl_setopt($curlConnection, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($curlConnection, CURLOPT_TIMEOUT, 30);
        curl_setopt($curlConnection, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curlConnection, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($curlConnection, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curlConnection, CURLOPT_HTTPHEADER, $header);

        // Add custom method
        if (in_array($method, [self::CURL_TYPE_DELETE, self::CURL_TYPE_PUT])) {
            curl_setopt($curlConnection, CURLOPT_CUSTOMREQUEST, $method);
        }

        // Add body params
        $body = null;
        if (! empty($jsonBody)) {
            $body = is_string($jsonBody) ? $jsonBody : json_encode($jsonBody);
            curl_setopt($curlConnection, CURLOPT_POST, 1);
            curl_setopt($curlConnection, CURLOPT_POSTFIELDS, $body);
        }

        //Definindo encoding
        curl_setopt($curlConnection, CURLOPT_ENCODING, "");

        $response = null;
        $errorMessage = '';

        try {
            $this->setRawRequest($this->getFullUrl($url_path), $body, $header, $method);
            $response = curl_exec($curlConnection);
            $this->setRawResponse($response);
        } catch (Exception $e) {
            $message = "Request Exception, error: {$e->getMessage()}";
            $this->setRawResponse($message);
            throw new GetnetException($message, 100);
        }

        // Verify error
        if ($response === false) {
            $errorMessage = curl_error($curlConnection);
        }

        $statusCode = (int) curl_getinfo($curlConnection, CURLINFO_HTTP_CODE);
        curl_close($curlConnection);

        if ($statusCode >= 400) {
            // TODO see what it means code 100
            throw new GetnetException($response, 100);
        }

        // Status code 204 don't have content. That means $response will be always false
        // Provides a custom content for $response to avoid error in the next if logic
        if ($statusCode === 204) {
            return [
                'status_code' => 204
            ];
        }

        if (! $response) {
            throw new GetnetException("Empty response, curl_error: $errorMessage", $statusCode);
        }

        $responseDecode = json_decode($response, true);

        if (is_array($responseDecode) && isset($responseDecode['error'])) {
            throw new GetnetException($responseDecode['error_description'], 100);
        }

        $responseDecode['raw_request'] = $this->getRawRequest();
        $responseDecode['raw_response'] = $this->getRawResponse();

        $credentials->setLastRequest($this);

        return $responseDecode;
    }

    /**
     * Get request full url
     *
     * @param string $url_path
     * @return string $url(config) + $url_path
     */
    private function getFullUrl($url_path)
    {
        if (stripos($url_path, $this->baseUrl, 0) === 0) {
            return $url_path;
        }

        return $this->baseUrl . $url_path;
    }

    /**
     *
     * @return string
     */
    public function getBaseUrl()
    {
        return $this->baseUrl;
    }

    /**
     *
     * @param Getnet $credentials
     * @param mixed $url_path
     * @return mixed * @throws Exception
     */
    public function get(Getnet $credentials, $url_path)
    {
        return $this->send($credentials, $url_path, self::CURL_TYPE_GET);
    }

    /**
     *
     * @param Getnet $credentials
     * @param mixed $url_path
     * @param mixed $params
     * @return mixed * @throws Exception
     */
    public function post(Getnet $credentials, $url_path, $params)
    {
        return $this->send($credentials, $url_path, self::CURL_TYPE_POST, $params);
    }

    /**
     *
     * @param Getnet $credentials
     * @param mixed $url_path
     * @param mixed $params
     * @return mixed * @throws Exception
     */
    public function put(Getnet $credentials, $url_path, $params)
    {
        return $this->send($credentials, $url_path, self::CURL_TYPE_PUT, $params);
    }

    /**
     *
     * @param Getnet $credentials
     * @param mixed $url_path
     * @return mixed * @throws Exception
     */
    public function delete(Getnet $credentials, $url_path)
    {
        return $this->send($credentials, $url_path, self::CURL_TYPE_DELETE);
    }
    
    public function custom(Getnet $credentials, string $method, string $url_path, $body = null)
    {
        
        if (!in_array($method, [
            self::CURL_TYPE_AUTH,
            self::CURL_TYPE_POST,
            self::CURL_TYPE_PUT,
            self::CURL_TYPE_GET,
            self::CURL_TYPE_DELETE
        ])) {
            throw new GetnetException("Invalid request method: {$method}");
        }
        
        return $this->send($credentials, $url_path, $method, $body);
    }

    /**
     * @return string
     */
    public function getRawRequest()
    {
        return $this->raw_request;
    }

    /**
     * @return string
     */
    public function getRawResponse()
    {
        return $this->raw_response;
    }

    /**
     * @param $url
     * @param $body
     * @param $headers
     * @param $method
     */
    public function setRawRequest( $url, $body, $headers, $method )
    {
        if ( $method == self::CURL_TYPE_AUTH ) {
            $method = self::CURL_TYPE_POST;
        }

        $request_string = 'Request: '.date('Y-m-d H:i:s').";";
        $request_string .= "URL: $url; ";
        $request_string .= "Method: $method; ";
        $request_string .= "Headers: " . implode(", ", $headers) . "; ";
        $request_string .= "Body: $body;";

        $this->raw_request = $request_string;
    }

    /**
     * @param $raw_response
     */
    public function setRawResponse($raw_response)
    {
        $response_string = 'Response: '.date('Y-m-d H:i:s').";";
        $response_string .= "Body: ". json_encode($raw_response) .";";

        $this->raw_response = $response_string;
    }

}
