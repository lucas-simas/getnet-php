<?php
namespace Getnet\API;

/**
 * Class Token
 *
 * @package
 */
class CardToken extends Token
{

    public function __construct(string $number_token)
    {
        $this->number_token = $number_token;
    }
}
