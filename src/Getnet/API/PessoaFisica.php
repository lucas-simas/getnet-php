<?php
namespace Getnet\API;

class PessoaFisica implements \JsonSerializable
{
    use TraitEntity;

    private $merchant_id;
    private $subseller_id;
    private $legal_document_number;
    private $legal_name;
    private $birth_date;
    private $occupation;
    private $business_address;
    private $email;
    private $bank_accounts;
    private $list_commissions;
    private $payment_plan;
    private $accepted_contract;
    private $marketplace_store;
    private $mothers_name;
    private $subsellerid_ext;
    private $monthly_gross_income;
    private $gross_equity;
    private $mailing_address;
    private $phone;
    private $cellphone;
    private $url_callback;
    private $automatic_anticipation;

    public function __construct($data = [])
    {
        $this->merchant_id = $data['merchant_id'] ?? null;
        $this->subseller_id = $data['subseller_id'] ?? null;
        $this->legal_document_number = $data['legal_document_number'] ?? null;
        $this->legal_name = $data['legal_name'] ?? null;
        $this->birth_date = $data['birth_date'] ?? null;
        $this->occupation = $data['occupation'] ?? null;
        $this->business_address = $data['business_address'] ?? [];
        $this->email = $data['email'] ?? null;
        $this->bank_accounts = $data['bank_accounts'] ?? [];
        $this->list_commissions = $data['list_commissions'] ?? [];
        $this->payment_plan = $data['payment_plan'] ?? null;
        $this->accepted_contract = $data['accepted_contract'] ?? null;
        $this->marketplace_store = $data['marketplace_store'] ?? null;
        $this->mothers_name = $data['mothers_name'] ?? null;
        $this->subsellerid_ext = $data['subsellerid_ext'] ?? null;
        $this->monthly_gross_income = $data['monthly_gross_income'] ?? null;
        $this->gross_equity = $data['gross_equity'] ?? null;
        $this->mailing_address = $data['mailing_address'] ?? [];
        $this->phone = $data['phone'] ?? [];
        $this->cellphone = $data['cellphone'] ?? [];
        $this->url_callback = $data['url_callback'] ?? null;
        $this->automatic_anticipation = $data['automatic_anticipation'] ?? null;
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

    public function getBirthDate()
    {
        return $this->birth_date;
    }
    public function setBirthDate($birth_date)
    {
        $this->birth_date = $birth_date;
    }

    public function getOccupation()
    {
        return $this->occupation;
    }
    public function setOccupation($occupation)
    {
        $this->occupation = $occupation;
    }

    public function getBusinessAddress()
    {
        return $this->business_address;
    }
    public function setBusinessAddress($business_address)
    {
        $this->business_address = $business_address;
    }

    public function getEmail()
    {
        return $this->email;
    }
    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getBankAccounts()
    {
        return $this->bank_accounts;
    }
    public function setBankAccounts($bank_accounts)
    {
        $this->bank_accounts = $bank_accounts;
    }

    public function getListCommissions()
    {
        return $this->list_commissions;
    }
    public function setListCommissions($list_commissions)
    {
        $this->list_commissions = $list_commissions;
    }

    public function getPaymentPlan()
    {
        return $this->payment_plan;
    }
    public function setPaymentPlan($payment_plan)
    {
        $this->payment_plan = $payment_plan;
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

    public function getMothersName()
    {
        return $this->mothers_name;
    }
    public function setMothersName($mothers_name)
    {
        $this->mothers_name = $mothers_name;
    }

    public function getSubselleridExt()
    {
        return $this->subsellerid_ext;
    }
    public function setSubselleridExt($subsellerid_ext)
    {
        $this->subsellerid_ext = $subsellerid_ext;
    }

    public function getMonthlyGrossIncome()
    {
        return $this->monthly_gross_income;
    }
    public function setMonthlyGrossIncome($monthly_gross_income)
    {
        $this->monthly_gross_income = $monthly_gross_income;
    }

    public function getGrossEquity()
    {
        return $this->gross_equity;
    }
    public function setGrossEquity($gross_equity)
    {
        $this->gross_equity = $gross_equity;
    }

    public function getMailingAddress()
    {
        return $this->mailing_address;
    }
    public function setMailingAddress($mailing_address)
    {
        $this->mailing_address = $mailing_address;
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

    public function getUrlCallback()
    {
        return $this->url_callback;
    }
    public function setUrlCallback($url_callback)
    {
        $this->url_callback = $url_callback;
    }

    public function getAutomaticAnticipation()
    {
        return $this->automatic_anticipation;
    }
    public function setAutomaticAnticipation($automatic_anticipation)
    {
        $this->automatic_anticipation = $automatic_anticipation;
    }

}
