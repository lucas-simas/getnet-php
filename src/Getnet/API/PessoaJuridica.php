<?php
namespace Getnet\API;

class PessoaJuridica implements \JsonSerializable
{
    use TraitEntity;

    private $merchant_id;
    private $subseller_id;
    private $legal_document_number;
    private $legal_name;
    private $trade_name;
    private $subsellerid_ext;
    private $state_fiscal_document_number;
    private $email;
    private $phone;
    private $cellphone;
    private $business_address;
    private $bank_accounts;
    private $url_callback;
    private $accepted_contract;
    private $marketplace_store;
    private $automatic_anticipation;
    private $payment_plan;
    private $business_entity_type;
    private $economic_activity_classification_code;
    private $monthly_gross_income;
    private $billing_value;
    private $billing_date;
    private $federal_registration_status;
    private $founding_date;
    private $list_commissions;
    private $legal_representative;
    private $shareholders;

    public function __construct($data = [])
    {
        $this->merchant_id = $data['merchant_id'] ?? null;
		$this->subseller_id = $data['subseller_id'] ?? null;
        $this->legal_document_number = $data['legal_document_number'] ?? null;
        $this->legal_name = $data['legal_name'] ?? null;
        $this->trade_name = $data['trade_name'] ?? null;
        $this->subsellerid_ext = $data['subsellerid_ext'] ?? null;
        $this->state_fiscal_document_number = $data['state_fiscal_document_number'] ?? null;
        $this->email = $data['email'] ?? null;
        $this->phone = $data['phone'] ?? [];
        $this->cellphone = $data['cellphone'] ?? [];
        $this->business_address = $data['business_address'] ?? [];
        $this->bank_accounts = $data['bank_accounts'] ?? [];
        $this->url_callback = $data['url_callback'] ?? null;
        $this->accepted_contract = $data['accepted_contract'] ?? null;
        $this->marketplace_store = $data['marketplace_store'] ?? null;
        $this->automatic_anticipation = $data['automatic_anticipation'] ?? null;
        $this->payment_plan = $data['payment_plan'] ?? null;
        $this->business_entity_type = $data['business_entity_type'] ?? null;
        $this->economic_activity_classification_code = $data['economic_activity_classification_code'] ?? null;
        $this->monthly_gross_income = $data['monthly_gross_income'] ?? null;
        $this->billing_value = $data['billing_value'] ?? null;
        $this->billing_date = $data['billing_date'] ?? null;
        $this->federal_registration_status = $data['federal_registration_status'] ?? null;
        $this->founding_date = $data['founding_date'] ?? null;
        $this->list_commissions = $data['list_commissions'] ?? [];
        $this->legal_representative = $data['legal_representative'] ?? [];
        $this->shareholders = $data['shareholders'] ?? [];
    }

    // Getters e Setters para cada propriedade

	public function getMerchantId()
	{
		return $this->merchant_id;
	}
	public function setMerchantId($merchant_id)
	{
		$this->merchant_id = $merchant_id;
	}

	public function getSubsellerId()
	{
		return $this->subseller_id;
	}

	public function setSubsellerId($subseller_id)
	{
		$this->subseller_id = $subseller_id;
	}

	public function getLegalDocumentNumber()
	{
		return $this->legal_document_number;
	}
	public function setLegalDocumentNumber($legal_document_number)
	{
		$this->legal_document_number = $legal_document_number;
	}

	public function getLegalName()
	{
		return $this->legal_name;
	}
	public function setLegalName($legal_name)
	{
		$this->legal_name = $legal_name;
	}

	public function getTradeName()
	{
		return $this->trade_name;
	}
	public function setTradeName($trade_name)
	{
		$this->trade_name = $trade_name;
	}

	public function getSubselleridExt()
	{
		return $this->subsellerid_ext;
	}
	public function setSubselleridExt($subsellerid_ext)
	{
		$this->subsellerid_ext = $subsellerid_ext;
	}

	public function getStateFiscalDocumentNumber()
	{
		return $this->state_fiscal_document_number;
	}
	public function setStateFiscalDocumentNumber($state_fiscal_document_number)
	{
		$this->state_fiscal_document_number = $state_fiscal_document_number;
	}

	public function getEmail()
	{
		return $this->email;
	}
	public function setEmail($email)
	{
		$this->email = $email;
	}

	public function getPhone()
	{
		return $this->phone;
	}
	public function setPhone($phone)
	{
		$this->phone = $phone;
	}

	public function getCellphone()
	{
		return $this->cellphone;
	}
	public function setCellphone($cellphone)
	{
		$this->cellphone = $cellphone;
	}

	public function getBusinessAddress()
	{
		return $this->business_address;
	}
	public function setBusinessAddress($business_address)
	{
		$this->business_address = $business_address;
	}

	public function getBankAccounts()
	{
		return $this->bank_accounts;
	}
	public function setBankAccounts($bank_accounts)
	{
		$this->bank_accounts = $bank_accounts;
	}

	public function getUrlCallback()
	{
		return $this->url_callback;
	}
	public function setUrlCallback($url_callback)
	{
		$this->url_callback = $url_callback;
	}

	public function getAcceptedContract()
	{
		return $this->accepted_contract;
	}
	public function setAcceptedContract($accepted_contract)
	{
		$this->accepted_contract = $accepted_contract;
	}

	public function getMarketplaceStore()
	{
		return $this->marketplace_store;
	}
	public function setMarketplaceStore($marketplace_store)
	{
		$this->marketplace_store = $marketplace_store;
	}

	public function getAutomaticAnticipation()
	{
		return $this->automatic_anticipation;
	}
	public function setAutomaticAnticipation($automatic_anticipation)
	{
		$this->automatic_anticipation = $automatic_anticipation;
	}

	public function getPaymentPlan()
	{
		return $this->payment_plan;
	}
	public function setPaymentPlan($payment_plan)
	{
		$this->payment_plan = $payment_plan;
	}

	public function getBusinessEntityType()
	{
		return $this->business_entity_type;
	}
	public function setBusinessEntityType($business_entity_type)
	{
		$this->business_entity_type = $business_entity_type;
	}

	public function getEconomicActivityClassificationCode()
	{
		return $this->economic_activity_classification_code;
	}
	public function setEconomicActivityClassificationCode($economic_activity_classification_code)
	{
		$this->economic_activity_classification_code = $economic_activity_classification_code;
	}

	public function getMonthlyGrossIncome()
	{
		return $this->monthly_gross_income;
	}
	public function setMonthlyGrossIncome($monthly_gross_income)
	{
		$this->monthly_gross_income = $monthly_gross_income;
	}

	public function getBillingValue()
	{
		return $this->billing_value;
	}
	public function setBillingValue($billing_value)
	{
		$this->billing_value = $billing_value;
	}

	public function getBillingDate()
	{
		return $this->billing_date;
	}
	public function setBillingDate($billing_date)
	{
		$this->billing_date = $billing_date;
	}

	public function getFederalRegistrationStatus()
	{
		return $this->federal_registration_status;
	}
	public function setFederalRegistrationStatus($federal_registration_status)
	{
		$this->federal_registration_status = $federal_registration_status;
	}

	public function getFoundingDate()
	{
		return $this->founding_date;
	}
	public function setFoundingDate($founding_date)
	{
		$this->founding_date = $founding_date;
	}

	public function getListCommissions()
	{
		return $this->list_commissions;
	}
	public function setListCommissions($list_commissions)
	{
		$this->list_commissions = $list_commissions;
	}

	public function getLegalRepresentative()
	{
		return $this->legal_representative;
	}
	public function setLegalRepresentative($legal_representative)
	{
		$this->legal_representative = $legal_representative;
	}

	public function getShareholders()
	{
		return $this->shareholders;
	}
	public function setShareholders($shareholders)
	{
		$this->shareholders = $shareholders;
	}

}