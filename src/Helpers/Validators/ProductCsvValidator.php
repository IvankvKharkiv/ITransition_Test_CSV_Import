<?php

namespace App\Helpers\Validators;

class ProductCsvValidator
{
    const DISCONTINUED = 'yes';
    /*
     * in real project I would have used google API to calculate GBPTOUSD
     * */
    const GBPTOUSD = 1.37;

    /**
     * Validates the given in array data.
     *
     * @param array $productData Array of data related to one product
     *
     * @return array<string, int>|null
     */
    public static function validate(array $productData){
        $errorMassage = '';
        if (!isset($productData['Product Code'])) {
            $errorMassage = $errorMassage . 'Product code is not set. ';
        }

        if (!isset($productData['Product Name'])) {
            $errorMassage = $errorMassage . 'Product name is not set. ';
        }

        if (!isset($productData['Product Description'])) {
            $errorMassage = $errorMassage . 'Product description is not set. ';
        }

        if (isset($productData['Stock']) && $productData['Stock'] != '') {
            if (!is_numeric($productData['Stock'])) {
                $errorMassage = $errorMassage . 'Product stock is not a number. Given "' . $productData['Stock'] . '" ';
            }
        }

        if (isset($productData['Cost in GBP'])) {
            if (!is_numeric($productData['Cost in GBP'])) {
                $errorMassage = $errorMassage . 'Product price (Cost in GBP) is not a number. Given "' . $productData['Cost in GBP'] . '" ';
            } elseif ((floatval($productData['Cost in GBP'])*self::GBPTOUSD) < 5 && isset($productData['Stock']) && is_numeric($productData['Stock']) && intval($productData['Stock']) < 10) {
                $errorMassage = $errorMassage . 'Product price (Cost in GBP) is less than 5$ and less than 10pcs in stock. ';
            } elseif ((floatval($productData['Cost in GBP'])*self::GBPTOUSD) > 1000) {
                $errorMassage = $errorMassage . 'Product price (Cost in GBP) is more than 1000$. ';
            }
        } else {
            $errorMassage = $errorMassage . 'Product price (Cost in GBP) can\'t me empty. ';
        }

        if (isset($productData['Discontinued']) && $productData['Discontinued'] != '') {
            if ( strcasecmp($productData['Discontinued'], self::DISCONTINUED) != 0 ) {
                $errorMassage = $errorMassage . 'Product discontinued must be \'yes\' or nothing. Value "' . $productData['Discontinued']  . '" is given. ';
            }
        }

        if ($errorMassage == '') {
            return null;
        } else {
            $productData['error'] = $errorMassage;
            return $productData;
        }

    }

}