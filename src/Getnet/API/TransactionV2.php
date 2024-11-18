<?php
namespace Getnet\API;

/**
 * Class Transaction
 *
 * @package Getnet\API
 */
class TransactionV2 implements \JsonSerializable
{
    use TraitEntity;

    const STATUS_AUTHORIZED = "AUTHORIZED";

    const STATUS_CONFIRMED = "CONFIRMED";

    const STATUS_PENDING = "PENDING";
    
    const STATUS_WAITING = "WAITING";

    const STATUS_APPROVED = "APPROVED";

    const STATUS_CANCELED = "CANCELED";

    const STATUS_DENIED = "DENIED";

    const STATUS_ERROR = "ERROR";

    private $idempotency_key;

    private $order_id;

    private $request_id;

    private $data;

    /**
     *
     * @return mixed
     */
    public function getIdempotencyKey()
    {
        return $this->idempotency_key;
    }

    /**
     *
     * @param mixed $$idempotency_key
     */
    public function setIdempotencyKey($idempotency_key)
    {
        $this->idempotency_key = (string) $idempotency_key;

        return $this;
    }

    /**
     *
     * @return mixed
     */
    public function getOrderId()
    {
        return $this->order_id;
    }

    /**
     *
     * @param mixed $order_id
     */
    public function setOrderId($order_id)
    {
        $this->order_id = (string) $order_id;

        return $this;
    }

    /**
     *
     * @return mixed
     */
    public function getRequestId()
    {
        return $this->request_id;
    }

    /**
     *
     * @param mixed $request_id
     */
    public function setRequestId($request_id)
    {
        $this->request_id = (string) $request_id;

        return $this;
    }

    /**
     *
     * @param string|null $data
     * @return TransactionV2Data
     */
    public function order($data = null)
    {
        $data = new TransactionV2Data($data);
        $this->setTData($data);

        return $data;
    }

    /**
     *
     * @return $tdata
     */
    public function getTData()
    {
        return $this->data;
    }

    /**
     *
     * @param $data
     */
    public function setTData($data)
    {
        $this->data = $data;

        return $this;
    }
    
}
