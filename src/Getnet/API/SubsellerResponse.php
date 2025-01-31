<?php
namespace Getnet\API;

/**
 * Class SubsellerResponse
 *
 * @package Getnet\API
 */
class SubsellerResponse implements \JsonSerializable
{

    protected $subseller_id;
    protected $status;
    public $error_message;
    public $error_code;
    public $status_code;
    public $status_label;
    public $responseJSON;
    public $responseArray;

    /**
     *
     * @return array
     */
    public function jsonSerialize(): mixed
    {
        return get_object_vars($this);
    }

    /**
     * TODO refactor and mapper individual and remove public props
     * @param array $json
     *
     * @return $this
     */
    public function mapperJson($json)
    {
        if (is_array($json)) {
            array_walk_recursive($json, function ($value, $key) {

                if (property_exists($this, $key)) {
                    $this->$key = $value;
                }
            });
        }

        if( isset($json['ModelState']) ){
            $error_msg = '';
            foreach( $json['ModelState'] as $key => $error ){
                if( $error_msg ){
                    $error_msg .= '; ';
                }
                $error_msg .= '['.$key.'] '.$error[0];
            }
            $this->setErrorMessage($error_msg);
        }
        
        if( isset($json['errors']) ){
            $error_msg = '';
            foreach( $json['errors'] as $error ){
                if( $error_msg ){
                    $error_msg .= '; ';
                }
                $error_msg .= $error;
            }
            $this->setErrorMessage($error_msg);
        }

        if( isset($json['success']) ){
            $this->setStatus('success');
        }

        $this->setResponseArray($json);
        $this->setResponseJSON($json);

        return $this;
    }

    /**
     *
     * @return mixed
     */
    public function getResponseJSON()
    {
        return $this->responseJSON;
    }

    /**
     *
     * @param mixed $array
     */
    public function setResponseJSON($array)
    {
        $this->responseJSON = json_encode($array, JSON_PRETTY_PRINT);

        return $this;
    }

    /**
     *
     * @return mixed
     */
    public function getResponseArray()
    {
        return $this->responseArray;
    }

    /**
     *
     * @param mixed $array
     */
    public function setResponseArray($array)
    {
        $this->responseArray = $array;

        return $this;
    }

    //Definindo getters e setters
    public function getSubsellerId()
    {
        return $this->subseller_id;
    }

    public function setSubsellerId($subseller_id)
    {
        $this->subseller_id = $subseller_id;

        return $this;
    }

    public function getStatus()
    {
        if ($this->status_code == 201) {
            $this->status = Transaction::STATUS_AUTHORIZED;
        } elseif ($this->status_code == 202) {
            $this->status = Transaction::STATUS_AUTHORIZED;
        } elseif ($this->status_code == 402) {
            $this->status = Transaction::STATUS_DENIED;
        } elseif ($this->status_code == 400) {
            $this->status = Transaction::STATUS_ERROR;
        } elseif ($this->status_code == 402) {
            $this->status = Transaction::STATUS_ERROR;
        } elseif ($this->status_code == 500) {
            $this->status = Transaction::STATUS_ERROR;
        } elseif ($this->status_code == 1 || (property_exists($this, 'redirect_url') && isset($this->redirect_url))) {
            $this->status = Transaction::STATUS_PENDING;
        } elseif (isset($this->status_label)) {
            // TODO check why
            $this->status = $this->status_label;
        }

        return $this->status;
    }

    /**
     *
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    public function getErrorMessage()
    {
        return $this->error_message;
    }

    public function setErrorMessage($error_message)
    {
        $this->error_message = $error_message;

        return $this;
    }

    public function getErrorCode()
    {
        return $this->error_code;
    }

    public function setErrorCode($error_code)
    {
        $this->error_code = $error_code;

        return $this;
    }

    public function setStatusCode($status_code)
    {
        $this->status_code = $status_code;

        return $this;
    }

    public function getStatusCode()
    {
        return $this->status_code;
    }

    public function setStatusLabel($status_label)
    {
        $this->status_label = $status_label;

        return $this;
    }

    public function getStatusLabel()
    {
        return $this->status_label;
    }


}