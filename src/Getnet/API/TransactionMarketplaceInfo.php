<?php
namespace Getnet\API;

/**
 * Class TransactionMarketplaceInfo
 *
 * @package Getnet\API
 */
class TransactionMarketplaceInfo implements \JsonSerializable
{
    use TraitEntity;

    private $subseller_sales_amount;

    private $subseller_id;

    private $order_items;

    private $request;

    /**
     *
     * @return mixed
     */
    public function getSubsellerSalesAmount()
    {
        return $this->subseller_sales_amount;
    }

    /**
     *
     * @param mixed $subseller_sales_amount
     */
    public function setSubsellerSalesAmount($subseller_sales_amount)
    {
        $this->subseller_sales_amount = (int) (string) ($subseller_sales_amount * 100);

        return $this;
    }

    /**
     *
     * @return mixed
     */
    public function getSubsellerId()
    {
        return $this->subseller_id;
    }

    /**
     *
     * @param mixed $subseller_id
     */
    public function setSubsellerId($subseller_id)
    {
        $this->subseller_id = (string) $subseller_id;

        return $this;
    }

    /**
     *
     * @return mixed
     */
    public function getOrderItems()
    {
        return $this->order_items;
    }

    /**
     *
     * @param mixed $order_items
     */
    public function setOrderItems($order_items)
    {
        $this->order_items = $order_items;

        return $this;
    }

    /**
     *
     * @return mixed
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     *
     * @param mixed $request
     */
    public function setRequest($request)
    {
        $this->request = $request;

        return $this;
    }
    

}