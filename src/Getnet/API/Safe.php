<?php
namespace Getnet\API;

/**
 * Class Safe
 *
 * @package Getnet\API
 */
class Safe implements \JsonSerializable
{
    use TraitEntity;
    
    private $card_id;

    private $number_token;

    private $brand;

    private $cardholder_name;

    private $expiration_month;

    private $expiration_year;

    private $customer_id;

    private $cardholder_identification;

    private $verify_card;

    private $security_code;



    /**
     * Token constructor.
     *
     * @param string $card_number
     * @param string $customer_id
     * @param Getnet $credencial
     */
    public function __construct($card_info, Getnet $credencial)
    {

        //Definições
        if( isset($card_info['number_token']) ){
            $this->setNumberToken($card_info['number_token']);
        }
        if( isset($card_info['card_id']) ){
            $this->setCardId($card_info['card_id']);
        }
        if( isset($card_info['brand']) ){
            $this->setBrand($card_info['brand']);
        }
        if( isset($card_info['cardholder_name']) ){
            $this->setCardholderName($card_info['cardholder_name']);
        }
        if( isset($card_info['expiration_month']) ){
            $this->setExpirationMonth($card_info['expiration_month']);
        }
        if( isset($card_info['expiration_year']) ){
            $this->setExpirationYear($card_info['expiration_year']);
        }
        if( isset($card_info['customer_id']) ){
            $this->setCustomerId($card_info['customer_id']);
        }
        if( isset($card_info['cardholder_identification']) ){
            $this->setCardholderIdentification($card_info['cardholder_identification']);
        }
        if( isset($card_info['verify_card']) ){
            $this->setVerifyCard($card_info['verify_card']);
        }
        if( isset($card_info['security_code']) ){
            $this->setSecurityCode($card_info['security_code']);
        }

        if( isset($card_info['card_id']) && $card_info['card_id'] ){
            $this->loadSafeCard($credencial);

        }
        else {
            $this->defineSafeCard($credencial);
        }
    }

    /**
     *
     * @return mixed
     */
    public function __toString()
    {
        return $this->number_token;
    }

    /**
     */
    public function loadSafeCard(Getnet $credencial)
    {
        $request = new Request($credencial);
        $response = $request->get($credencial, "/v1/cards/{$this->card_id}");
        
        $this->setNumberToken($response["number_token"]);
        $this->setBrand($response["brand"]);
        $this->setCardholderName($response["cardholder_name"]);
        $this->setExpirationMonth($response["expiration_month"]);
        $this->setExpirationYear($response["expiration_year"]);
        $this->setCustomerId($response["customer_id"]);
        if( isset($response["cardholder_identification"]) && $response["cardholder_identification"] ){
            $this->setCardholderIdentification($response["cardholder_identification"]);
        }
        if( isset($response["verify_card"]) && $response["verify_card"] ){
            $this->setVerifyCard($response["verify_card"]);
        }
        if( isset($response["security_code"]) && $response["security_code"] ){
            $this->setSecurityCode($response["security_code"]);
        }

        return $this;
    }

    /**
     */
    public function defineSafeCard(Getnet $credencial)
    {
        $data = array(
            'number_token'                  => $this->number_token,
            'brand'                         => $this->brand,
            'cardholder_name'               => substr($this->cardholder_name, 0, 25),
            'expiration_month'              => $this->expiration_month,
            'expiration_year'               => $this->expiration_year,
            'customer_id'                   => $this->customer_id,
            'cardholder_identification'     => $this->cardholder_identification,
            'verify_card'                   => $this->verify_card,
            'security_code'                 => $this->security_code
        );

        $request = new Request($credencial);
        $response = $request->post($credencial, "/v1/cards", json_encode($data));
        $this->setCardId($response["card_id"]);

        return $this;
    }

    public function getNumberToken()
    {
        return $this->number_token;
    }

    public function getCardId()
    {
        return $this->card_id;
    }

    public function getBrand()
    {
        return $this->brand;
    }

    public function getCardholderName()
    {
        return $this->cardholder_name;
    }

    public function getExpirationMonth()
    {
        return $this->expiration_month;
    }

    public function getExpirationYear()
    {
        return $this->expiration_year;
    }

    public function getCustomerId()
    {
        return $this->customer_id;
    }  

    public function getCardholderIdentification()
    {
        return $this->cardholder_identification;
    }

    public function getVerifyCard()
    {
        return $this->verify_card;
    }

    public function getSecurityCode()
    {
        return $this->security_code;
    }

    public function setNumberToken($number_token)
    {
        $this->number_token = $number_token;
        return $this;
    }

    public function setCardId($card_id)
    {
        $this->card_id = $card_id;
        return $this;
    }

    public function setBrand($brand)
    {
        $this->brand = $brand;
        return $this;
    }

    public function setCardholderName($cardholder_name)
    {
        $this->cardholder_name = $cardholder_name;
        return $this;
    }

    public function setExpirationMonth($expiration_month)
    {
        $this->expiration_month = $expiration_month;
        return $this;
    }

    public function setExpirationYear($expiration_year)
    {
        $this->expiration_year = $expiration_year;
        return $this;
    }

    public function setCustomerId($customer_id)
    {
        $this->customer_id = $customer_id;
        return $this;
    }

    public function setCardholderIdentification($cardholder_identification)
    {
        $this->cardholder_identification = $cardholder_identification;
        return $this;
    }

    public function setVerifyCard($verify_card)
    {
        $this->verify_card = $verify_card;
        return $this;
    }

    public function setSecurityCode($security_code)
    {
        $this->security_code = $security_code;
        return $this;
    }

    
}