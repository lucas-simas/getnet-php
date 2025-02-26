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
     * @param TransactionV2 $transaction
     * @return BaseResponse|AuthorizeResponse
     */
    public function authorize(TransactionV2 $transaction)
    {
        $request = null;
        
        try {
            if ($this->debug) {
                print $transaction->toJSON();
            }

            $request = new Request($this);
            $response = $request->post($this, "/v2/payments", $transaction->toJSON());

            $authresponse = new AuthorizeResponse();
            $authresponse->mapperJson($response);

            return $authresponse;
        } catch (\Exception $e) {
            return $this->generateErrorResponse($e, $request);
        }
    }

    /**
     *
     * @param mixed $payment_id
     * @return BaseResponse|AuthorizeResponse
     */
    public function authorizeConfirm($payment_id, $amount)
    {
        $request = null;
        
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
            return $this->generateErrorResponse($e, $request);
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
        $request = null;
        
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
            return $this->generateErrorResponse($e, $request);
        }
    }

    /**
     * Estorna ou desfaz transações feitas no mesmo dia (D0).
     *
     * @param string $payment_id
     * @param int|string $amount_val
     * @return AuthorizeResponse|BaseResponse
     */
    public function authorizeCancel($payment_id, $amount_val = null)
    {
        $request = null;
        
        $bodyParams = [];
        if( $amount_val ){
            $bodyParams['amount'] = $amount_val;
        }

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
            return $this->generateErrorResponse($e, $request);
        }
    }

    /**
     * Solicita o cancelamento de transações que foram realizadas há mais de 1 dia (D+n).
     *
     * @param mixed $idempotency_key
     * @param mixed $cancel_amount
     * @param mixed $cancel_custom_key
     * @return AuthorizeResponse|BaseResponse
     */
    public function cancelTransactionV2($idempotency_key, $payment_id, $payment_method, $amount = null, $custom_key = null, $additional_data = null )
    {
        $request = null;
        
        $bodyParams = array(
            "idempotency_key"   => $idempotency_key,
            "payment_id"        => $payment_id,
            "payment_method"    => $payment_method,
        );
        if(  $amount ){
            $bodyParams['amount'] = $amount;
        }
        if(  $custom_key ){
            $bodyParams['custom_key'] = $custom_key;
        }
        if(  $additional_data ){
            $bodyParams['additional_data']['split']['subseller_list_payment'] = $additional_data;
        }

        try {
            if ($this->debug) {
                print json_encode($bodyParams);
            }

            $request = new Request($this);
            $response = $request->post($this, "/v2/payments/cancel", json_encode($bodyParams));
            $authresponse = new AuthorizeResponse();
            $authresponse->mapperJson($response);

            return $authresponse;
        } catch (\Exception $e) {
            return $this->generateErrorResponse($e, $request);
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
    public function cancelTransaction($payment_id, $cancel_amount = null, $cancel_custom_key = null, $marketplace_subseller_payments = null )
    {
        $request = null;
        
        $bodyParams = array(
            "payment_id" => $payment_id,
        );
        if(  $cancel_amount ){
            $bodyParams['cancel_amount'] = $cancel_amount;
        }
        if(  $cancel_custom_key ){
            $bodyParams['cancel_custom_key'] = $cancel_custom_key;
        }
        if(  $marketplace_subseller_payments ){
            $bodyParams['marketplace_subseller_payments'] = $marketplace_subseller_payments;
        }

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
            return $this->generateErrorResponse($e, $request);
        }
    }

    /**
     * Retorna os dados da solicitação de cancelamento pela chave gerada pelo cliente ou pelo identificador (D+n).
     *
     * @return AuthorizeResponse|BaseResponse
     */
    public function getCancellationRequest(string $cancelRequestId = null, string $cancel_custom_key = null)
    {
        $request = null;
        
        $path = "/v1/payments/cancel/request";
        if( $cancelRequestId ){
            $path .= "/{$cancelRequestId}";
        }
        else if( $cancel_custom_key ){
            $path .= "?cancel_custom_key={$cancel_custom_key}";
        }

        try {
            $request = new Request($this);
            $response = $request->get($this, $path);
            $authresponse = new AuthorizeResponse();
            $authresponse->mapperJson($response);
            
            return $authresponse;
        } catch (\Exception $e) {
            return $this->generateErrorResponse($e, $request);
        }
    }

    /**
     *
     * @param Transaction $transaction
     * @return BaseResponse|BoletoRespose
     */
    public function boleto(Transaction $transaction)
    {
        $request = null;

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
            return $this->generateErrorResponse($e, $request);
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
        $request = null;

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
            return $this->generateErrorResponse($e, $request);
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
    public function getCallbackPJSubsellerByCNPJ( string $cnpj )
    {
        try {
            $request = new Request($this);

            $response = $request->get($this, "/v1/mgm/pj/callback/{$this->merchant_id}/{$cnpj}");

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
    public function getCallbackPFSubsellerByCPF( string $cpf )
    {
        try {
            $request = new Request($this);

            $response = $request->get($this, "/v1/mgm/pf/callback/{$this->merchant_id}/{$cpf}");

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

    public function generateStatement( $params )
    {
        try {
            $get_params = [
                'seller_id'                 => $this->seller_id,
                'transaction_date_init'     => $params['transaction_date_init'],
                'transaction_date_end'      => $params['transaction_date_end'],
            ];
            if( isset($params['order_id']) && $params['order_id'] ){
                $get_params['order_id'] = $params['order_id'];
            }

            //Gerando url com os parametros
            $url = "/v1/mgm/statement?" . http_build_query($get_params);

            $request = new Request($this);

            $response = $request->get($this, $url);

            return $response;
        } catch (\Exception $e) {
            return $this->generateSSErrorResponse($e);
        }
    }

    public function generateV2Statement( $params )
    {
        try {
            $get_params = [
                'seller_id'                 => $this->seller_id,
                'page'                      => 1,
            ];
            
            $get_params = array_merge($get_params, $params);

            //Gerando url com os parametros
            $url = "/v2/mgm/statement/get-paginated-statement?" . http_build_query($get_params);

            $request = new Request($this);

            $response = $request->get($this, $url);

            return $response;
        } catch (\Exception $e) {
            return [
                'status'    => Transaction::STATUS_ERROR,
                'message'   => $e->getMessage()
            ];
        }
    }

    public function generatePaginatedStatement( $params )
    {
        try {
            $get_params = [
                'seller_id'                 => $this->seller_id,
            ];
            
            $get_params = array_merge($get_params, $params);

            //Gerando url com os parametros
            $url = "/v3/mgm/statement/paginated?" . http_build_query($get_params);

            $request = new Request($this);

            $response = $request->get($this, $url);

            return $response;
        } catch (\Exception $e) {
            return [
                'status'    => Transaction::STATUS_ERROR,
                'message'   => $e->getMessage()
            ];
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
    private function generateErrorResponse($e, Request $request = null)
    {
        $error = new BaseResponse();
        $error_array = json_decode($e->getMessage(), true);
        $error_array['raw_request'] = $request->getRawRequest();
        $error_array['raw_response'] = $request->getRawResponse();
        $error->mapperJson($error_array);
        
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