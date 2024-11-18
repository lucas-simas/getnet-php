<?php
namespace Getnet\API;

use Getnet\API\Exception\GetnetException;

/**
 * Class TransactionV2Payment
 *
 * @package Getnet\API
 */
class TransactionV2Payment implements \JsonSerializable
{
    use TraitEntity;

    // Pagamento completo à vista
    const TRANSACTION_CREDIT = "CREDIT";

    // Pagamento parcelado sem juros
    const TRANSACTION_DEBIT = "DEBIT";

    // Pagamento completo à vista
    const TRANSACTION_TYPE_FULL = "FULL";

    // Pagamento parcelado sem juros
    const TRANSACTION_TYPE_INSTALL_NO_INTEREST = "INSTALL_NO_INTEREST";

    // Pagamento parcelado com juros
    const TRANSACTION_TYPE_INSTALL_WITH_INTEREST = "INSTALL_WITH_INTEREST";

    const COF_ONE_CLICK = 'ONE_CLICK';

    const COF_ONE_CLICK_PAYMENT = 'ONE_CLICK_PAYMENT';

    const COF_RECURRING = 'RECURRING';

    const COF_RECURRING_PAYMENT = 'RECURRING_PAYMENT';


	private $payment_id;
	
	private $payment_method;

	private $transaction_type;

	private $number_installments;

	private $card;

	private $soft_descriptor;

	/**
	 *
	 * @return mixed
	 */
	public function getPaymentId()
	{
		return $this->payment_id;
	}

	/**
	 *
	 * @param mixed $payment_id
	 */
	public function setPaymentId($payment_id)
	{
		$this->payment_id = $payment_id;

		return $this;
	}

	/**
	 *
	 * @return mixed
	 */
	public function getPaymentMethod()
	{
		return $this->payment_method;
	}

	/**
	 *
	 * @param mixed $payment_method
	 */
	public function setPaymentMethod($payment_method)
	{
		$this->payment_method = $payment_method;

		return $this;
	}

	/**
	 *
	 * @return mixed
	 */
	public function getTransactionType()
	{
		return $this->transaction_type;
	}

	/**
	 *
	 * @param mixed $transaction_type
	 */
	public function setTransactionType($transaction_type)
	{
		$this->transaction_type = $transaction_type;

		return $this;
	}

	/**
	 *
	 * @return mixed
	 */
	public function getNumberInstallments()
	{
		return $this->number_installments;
	}

	/**
	 *
	 * @param mixed $number_installments
	 */
	public function setNumberInstallments($number_installments)
	{
		$this->number_installments = $number_installments;

		return $this;
	}

	/**
	 *
	 * @return CardV2
	 */
	public function card(Token $token = null)
    {
        $card = new CardV2($token);

        $this->setCard($card);

        return $card;
    }

	/**
	 *
	 * @return mixed
	 */
	public function getCard()
	{
		return $this->card;
	}

	/**
	 *
	 * @param CardV2 $card
	 */
	public function setCard(CardV2 $card)
	{
		$this->card = $card;

		return $this;
	}

	/**
	 *
	 * @return mixed
	 */
	public function getSoftDescriptor()
	{
		return $this->soft_descriptor;
	}
	
	/**
	 *
	 * @param mixed $soft_descriptor
	 */
	public function setSoftDescriptor($soft_descriptor)
	{
		$this->soft_descriptor = $soft_descriptor;
		
		return $this;
	}

}