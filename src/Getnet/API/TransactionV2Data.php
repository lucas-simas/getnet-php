<?php
namespace Getnet\API;

/**
 * Class TransactionV2Data
 *
 * @package Getnet\API
 */
class TransactionV2Data implements \JsonSerializable
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

    private $amount;

    private $currency = "BRL";

    private $customer_id;

    private $payment;

    private $additional_data = [
        'split'     => [],
        'customer'  => [],
        'device'    => [],
        'order'     => [],
    ];

    /**
     *
     * @return mixed
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     *
     * @param mixed $amount
     */
    public function setAmount($amount)
    {
        $this->amount = (int) (string) ($amount * 100);

        return $this;
    }

    /**
     *
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     *
     * @param string $currency
     */
    public function setCurrency($currency)
    {
        $this->currency = (string) $currency;

        return $this;
    }

    /**
     *
     * @param mixed $id
     * @return Customer
     */
    public function customer($id = null)
    {
        $customer = new Customer($id);

        $this->setCustomer($customer);

        return $customer;
    }

    /**
     *
     * @return Customer
     */
    public function getCustomer()
    {
        return $this->additional_data['customer'];
    }
    
    /**
     *
     * @return TransactionV2Payment
     */
    public function payment()
    {
        $payment = new TransactionV2Payment();

        $this->setPayment($payment);

        return $payment;
    }
    
    /**
     *
     * @param TransactionV2Payment $payment
     */
    public function setPayment(TransactionV2Payment $payment)
    {
        $this->payment = $payment;

        return $this;
    }

    /**
     *
     * @return TransactionV2Payment
     */
    public function getPayment()
    {
        return $this->payment;
    }

    /**
     *
     * @param Customer $customer
     */
    public function setCustomer(Customer $customer)
    {
        $this->additional_data['customer'] = $customer;

        return $this;
    }

    /**
     *
     * @return mixed
     */
    public function getOrder()
    {
        return $this->additional_data['order'];
    }

    /**
     *
     * @param mixed $order
     */
    public function setOrder(mixed $order)
    {
        $this->additional_data['order']['items'] = $order;

        return $this;
    }

    /**
     *
     * @param string $device_id
     * @return Device
     */
    public function device($device_id = null)
    {
        $device = new Device($device_id);

        $this->setDevice($device);

        return $device;
    }
    
    /**
     *
     * @return Device
     */
    public function getDevice()
    {
        return $this->additional_data['device'];
    }

    /**
     *
     * @param Device $device
     */
    public function setDevice(Device $device)
    {
        $this->additional_data['device'] = $device;

        return $this;
    }

    /**
     *
     * @param string $split_id
     * @return Split
     */
    public function split($split_id)
    {
        $split = new Split($split_id);

        $this->setSplit($split);

        return $split;
    }

    /**
     *
     * @return mixed
     */
    public function getSplit()
    {
        return $this->additional_data['split'];
    }

    /**
     *
     * @param mixed $split
     */
    public function setSplit(mixed $split)
    {
        $this->additional_data['split']['subseller_list_payment'] = $split;

        return $this;
    }

    public function setCustomerId($customer_id)
    {
        $this->customer_id = $customer_id;

        return $this;
    }

    public function getCustomerId()
    {
        return $this->customer_id;
    }
    
}
