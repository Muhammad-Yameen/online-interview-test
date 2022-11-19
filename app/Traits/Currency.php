<?php

namespace App\Traits;

Trait Currency {


    public function currency($given_amount = null)
    {
        $amount = new \NumberFormatter("en_US", \NumberFormatter::CURRENCY);
        return $amount->formatCurrency($given_amount, 'AED');
    }


}
