<?php
namespace Getnet\API;

use Getnet\API\Exception\GetnetException;

/**
 * Class Getnet
 *
 * @package Getnet\API
 */
class Getnet
{

    private $client_id;

    private $client_secret;

    private $seller_id;

    private $merchant_id;

    private $scope;

    private $environment;

    private $authorizationToken;

    private $keySession;

    private Request $last_request;

    // TODO add monolog
    private $debug = false;

    /**
     *
     * @param string $client_id
     * @param string $client_secret
     * @param Environment|null $environment
     * @return Getnet
     */
    public function __construct($client_id, $client_secret, $seller_id, $merchant_id, $scope = 'oob', Environment $environment = null, $keySession = null)
    {
        if (! $environment) {
            $environment = Environment::production();
        }

        $this->setClientId($client_id);
        $this->setClientSecret($client_secret);
        $this->setSellerId($seller_id);
        $this->setMerchantId($merchant_id);
        $this->setScope($scope);
        $this->setEnvironment($environment);
        $this->setKeySession($keySession);

        $request = new Request($this);
    }

    /**
     *
     * @return string
     */
    public function getClientId()
    {
        return $this->client_id;
    }

    /**
     *
     * @param string $client_id
     */
    public function setClientId($client_id)
    {
        $this->client_id = (string) $client_id;

        return $this;
    }

    /**
     *
     * @return mixed
     */
    public function getClientSecret()
    {
        return $this->client_secret;
    }

    /**
     *
     * @param mixed $client_secret
     */
    public function setClientSecret($client_secret)
    {
        $this->client_secret = (string) $client_secret;

        return $this;
    }

    public function getSellerId()
    {
        return $this->seller_id;
    }

    public function setSellerId($seller_id)
    {
        $this->seller_id = (string) $seller_id;

        return $this;
    }

    public function getMerchantId()
    {
        return $this->merchant_id;
    }

    public function setMerchantId($merchant_id)
    {
        $this->merchant_id = (string) $merchant_id;

        return $this;
    }

    public function getScope()
    {
        return $this->scope;
    }

    public function setScope($scope)
    {
        $this->scope = (string) $scope;

        return $this;
    }

    /**
     *
     * @return Environment
     */
    public function getEnvironment()
    {
        return $this->environment;
    }

    /**
     *
     * @param Environment $environment
     */
    public function setEnvironment(Environment $environment)
    {
        $this->environment = $environment;

        return $this;
    }

    /**
     *
     * @return mixed
     */
    public function getAuthorizationToken()
    {
        return $this->authorizationToken;
    }

    /**
     *
     * @param mixed $authorizationToken
     */
    public function setAuthorizationToken($authorizationToken)
    {
        $this->authorizationToken = (string) $authorizationToken;

        return $this;
    }

    /**
     *
     * @return mixed
     */
    public function getKeySession()
    {
        return $this->keySession;
    }

    /**
     *
     * @param mixed $keySession
     */
    public function setKeySession($keySession)
    {
        $this->keySession = (string) $keySession;
    }

    /**
     *
     * @return bool|null
     */
    public function getDebug()
    {
        return $this->debug;
    }

    /**
     *
     * @param bool|null $debug
     */
    public function setDebug($debug = false)
    {
        $this->debug = $debug;

        return $this;
    }

    /**
     *
     * @param Transaction $transaction
     * @return BaseResponse|AuthorizeResponse
     */
    public function authorize(Transaction $transaction)
    {
        try {
            if ($this->debug) {
                print $transaction->toJSON();
            }

            $request = new Request($this);

            $response = null;
            if ($transaction->getCredit()) {
                $response = $request->post($this, "/v1/payments/credit", $transaction->toJSON());
            } elseif ($transaction->getDebit()) {
                $response = $request->post($this, "/v1/payments/debit", $transaction->toJSON());
            } else {
                throw new GetnetException("Error select credit or debit");
            }

            $authresponse = new AuthorizeResponse();
            $authresponse->mapperJson($response);

            return $authresponse;
        } catch (\Exception $e) {
            return $this->generateErrorResponse($e);
        }
    }

    /**
     *
     * @param mixed $payment_id
     * @return BaseResponse|AuthorizeResponse
     */
    public function authorizeConfirm($payment_id, $amount)
    {
        $bodyParams = array(
            'amount' => $amount
        );

        try {
            if ($this->debug) {
                print json_encode($bodyParams);
            }

            $request = new Request($this);
            $response = $request->post($this, "/v1/payments/credit/" . $payment_id . "/confirm", $bodyParams);

            $authresponse = new AuthorizeResponse();
            $authresponse->mapperJson($response);

            return $authresponse;
        } catch (\Exception $e) {
            return $this->generateErrorResponse($e);
        }
    }

    /**
     *
     * @param mixed $payment_id
     * @param mixed $payer_authentication_response
     * @return BaseResponse|AuthorizeResponse
     */
    public function authorizeConfirmDebit($payment_id, $payer_authentication_response)
    {
        $bodyParams = array(
            "payer_authentication_response" => $payer_authentication_response
        );

        try {
            if ($this->debug) {
                print json_encode($bodyParams);
            }

            $request = new Request($this);
            $response = $request->post($this, "/v1/payments/debit/" . $payment_id . "/authenticated/finalize", json_encode($bodyParams));

            $authresponse = new AuthorizeResponse();
            $authresponse->mapperJson($response);

            return $authresponse;
        } catch (\Exception $e) {
            return $this->generateErrorResponse($e);
        }
    }

    /**
     * Estorna ou desfaz transações feitas no mesmo dia (D0).
     *
     * @param string $payment_id
     * @param int|string $amount_val
     * @return AuthorizeResponse|BaseResponse
     */
    public function authorizeCancel($payment_id, $amount_val)
    {
        $bodyParams = array(
            "amount" => $amount_val
        );

        try {
            if ($this->debug) {
                print json_encode($bodyParams);
            }

            $request = new Request($this);
            $response = $request->post($this, "/v1/payments/credit/" . $payment_id . "/cancel", json_encode($bodyParams));

            $authresponse = new AuthorizeResponse();
            $authresponse->mapperJson($response);

            return $authresponse;
        } catch (\Exception $e) {
            return $this->generateErrorResponse($e);
        }
    }

    /**
     * Solicita o cancelamento de transações que foram realizadas há mais de 1 dia (D+n).
     *
     * @param mixed $payment_id
     * @param mixed $cancel_amount
     * @param mixed $cancel_custom_key
     * @return AuthorizeResponse|BaseResponse
     */
    public function cancelTransaction($payment_id, $cancel_amount, $cancel_custom_key)
    {
        $bodyParams = array(
            "payment_id" => $payment_id,
            "cancel_amount" => $cancel_amount,
            "cancel_custom_key" => $cancel_custom_key
        );

        try {
            if ($this->debug) {
                print json_encode($bodyParams);
            }

            $request = new Request($this);
            $response = $request->post($this, "/v1/payments/cancel/request", json_encode($bodyParams));
            $authresponse = new AuthorizeResponse();
            $authresponse->mapperJson($response);

            return $authresponse;
        } catch (\Exception $e) {
            return $this->generateErrorResponse($e);
        }
    }

    /**
     * Retorna os dados da solicitação de cancelamento pela chave gerada pelo cliente ou pelo identificador (D+n).
     *
     * @return AuthorizeResponse|BaseResponse
     */
    public function getCancellationRequest(string $cancelRequestId)
    {
        try {
            $request = new Request($this);
            $response = $request->get($this, "/v1/payments/cancel/request/" . $cancelRequestId);
            $authresponse = new AuthorizeResponse();
            $authresponse->mapperJson($response);
            
            return $authresponse;
        } catch (\Exception $e) {
            return $this->generateErrorResponse($e);
        }
    }

    /**
     *
     * @param Transaction $transaction
     * @return BaseResponse|BoletoRespose
     */
    public function boleto(Transaction $transaction)
    {
        try {
            if ($this->debug) {
                print $transaction->toJSON();
            }

            $request = new Request($this);
            $response = $request->post($this, "/v1/payments/boleto", $transaction->toJSON());

            $boletoresponse = new BoletoRespose();
            $boletoresponse->mapperJson($response);
            $boletoresponse->setBaseUrl($request->getBaseUrl());
            $boletoresponse->generateLinks();

            return $boletoresponse;
        } catch (\Exception $e) {
            return $this->generateErrorResponse($e);
        }
    }

    /**
     * Payment confirmation is sent via notifications
     *
     * @param PixTransaction $pix
     * @return BaseResponse|PixResponse
     * @link https://developers.getnet.com.br/api#tag/Notificacoes-1.0
     */
    public function pix(PixTransaction $pix)
    {
        try {
            if ($this->debug) {
                print $pix->toJSON();
            }

            $request = new Request($this);
            $response = $request->post($this, "/v1/payments/qrcode/pix", $pix->toJSON());

            $pixResponse = new PixResponse();
            // Add fields do not return in response
            $pixResponse->mapperJson($pix->toArray());
            // Add response fields
            $pixResponse->mapperJson($response);

            return $pixResponse;
        } catch (\Exception $e) {
            return $this->generateErrorResponse($e);
        }
    }

    /**
     *
     * @return array
     */
    public function getPaymentPlanList()
    {
        try {
            $request = new Request($this);

            $response = $request->get($this, "/v1/mgm/pf/consult/paymentplans/{$this->merchant_id}");

            return $response;
        } catch (\Exception $e) {
            return $this->generateSSErrorResponse($e);
        }
    }

     /**
     *
     * @return array|SubsellerResponse
     */
    public function getPJSubsellerByCNPJ( string $cnpj )
    {
        try {
            $request = new Request($this);

            $response = $request->get($this, "/v1/mgm/pj/consult/{$this->merchant_id}/{$cnpj}");

            $ssresponse = new SubsellerResponse();
            $ssresponse->mapperJson($response);

            return $ssresponse;
        } catch (\Exception $e) {
            return $this->generateSSErrorResponse($e);
        }
    }

    /**
     *
     * @return array|SubsellerResponse
     */
    public function createPJSubseller( PessoaJuridica $params )
    {
        try {
            $request = new Request($this);

            $response = $request->post($this, "/v1/mgm/pj/create-presubseller", $params);

            $ssresponse = new SubsellerResponse();
            $ssresponse->mapperJson($response);

            return $ssresponse;
        } catch (\Exception $e) {
            return $this->generateSSErrorResponse($e);
        }
    }

    /**
     *
     * @return array|SubsellerResponse
     */
    public function complementPJSubseller( PessoaJuridica $params )
    {
        try {
            $request = new Request($this);

            $response = $request->put($this, "/v1/mgm/pj/complement", $params);

            $ssresponse = new SubsellerResponse();
            $ssresponse->mapperJson($response);

            return $ssresponse;
        } catch (\Exception $e) {
            return $this->generateSSErrorResponse($e);
        }
    }

    /**
     *
     * @return array|SubsellerResponse
     */
    public function updatePJSubseller( PessoaJuridica $params )
    {
        try {
            $request = new Request($this);

            $response = $request->put($this, "/v1/mgm/pj/update-subseller", $params);

            $ssresponse = new SubsellerResponse();
            $ssresponse->mapperJson($response);

            return $ssresponse;
        } catch (\Exception $e) {
            return $this->generateSSErrorResponse($e);
        }
    }

    /**
     *
     * @return array|SubsellerResponse
     */
    public function getPFSubsellerByCPF( string $cpf )
    {
        try {
            $request = new Request($this);

            $response = $request->get($this, "/v1/mgm/pf/consult/{$this->merchant_id}/{$cpf}");

            $ssresponse = new SubsellerResponse();
            $ssresponse->mapperJson($response);

            return $ssresponse;
        } catch (\Exception $e) {
            return $this->generateSSErrorResponse($e);
        }
    }

    /**
     *
     * @return array|SubsellerResponse
     */
    public function createPFSubseller( PessoaFisica $params )
    {
        try {
            $request = new Request($this);

            $response = $request->post($this, "/v1/mgm/pf/create-presubseller", $params);

            $ssresponse = new SubsellerResponse();
            $ssresponse->mapperJson($response);

            return $ssresponse;
        } catch (\Exception $e) {
            return $this->generateSSErrorResponse($e);
        }
    }

    public function complementPFSubseller( PessoaFisica $params )
    {
        try {
            $request = new Request($this);

            $response = $request->put($this, "/v1/mgm/pf/complement", $params);

            $ssresponse = new SubsellerResponse();
            $ssresponse->mapperJson($response);

            return $ssresponse;
        } catch (\Exception $e) {
            return $this->generateSSErrorResponse($e);
        }
    }

    public function updatePFSubseller( PessoaFisica $params )
    {
        try {
            $request = new Request($this);

            $response = $request->put($this, "/v1/mgm/pf/update-subseller", $params);

            $ssresponse = new SubsellerResponse();
            $ssresponse->mapperJson($response);

            return $ssresponse;
        } catch (\Exception $e) {
            return $this->generateSSErrorResponse($e);
        }
    }
    
    public function customRequest(string $method, string $url_path, $body = null)
    {
        $request = new Request($this);
        
        return $request->custom($this, $method, $url_path, $body);
    }
    
    /**
     * 
     * @param \Exception $e
     * @return \Getnet\API\BaseResponse
     */
    private function generateErrorResponse($e)
    {
        $error = new BaseResponse();
        $error->mapperJson(json_decode($e->getMessage(), true));
        
        if (empty($error->getStatus())) {
            $error->setStatus(Transaction::STATUS_ERROR);
        }
        
        return $error;
    }

    /**
     * 
     * @param \Exception $e
     * @return \Getnet\API\SubsellerResponse
     */
    private function generateSSErrorResponse($e)
    {
        $error = new SubsellerResponse();
        $error->mapperJson(json_decode($e->getMessage(), true));
        
        if (empty($error->getStatus())) {
            $error->setStatus(Transaction::STATUS_ERROR);
        }
        
        return $error;
    }

    /**
     * 
     * @return Request
     */
    public function getLastRequest()
    {
        return $this->last_request;
    }

    /**
     * 
     * @param Request $request
     */
    public function setLastRequest(Request $request)
    {
        $this->last_request = $request;
    }

}