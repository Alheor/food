<?php

namespace App\Calculators;


use App\Product;

class NutritionalValueCalculator
{
    private $productList = [];

    private $amountB = 0;
    private $amountJ = 0;
    private $amountU = 0;
    private $amountK = 0;
    private $amountW = 0;

    /**
     * @param Product $product
     * @param $mealGuid
     * @param int $weight
     */
    public function addProduct(Product $product, $mealGuid, int $weight)
    {
        $elIndex = md5($mealGuid . $product->guid);

        if(isset($this->productList[$elIndex])) {
            $this->productList[$elIndex][1] += $weight;
        } else {
            $this->productList[$elIndex] = [$product, $weight];
        }
    }

    /**
     * @param $guid
     * @return array|null
     */
    public function getProductNutritionalValueByGuid($mealGuid, $guid)
    {
        $elIndex = md5($mealGuid . $guid);
        if (!isset($this->productList[$elIndex])) {
            return [];
        }

        $b = (int)(($this->productList[$elIndex][0]->b * $this->productList[$elIndex][1] / 100) * 10) / 10;
        $j = (int)(($this->productList[$elIndex][0]->j * $this->productList[$elIndex][1] / 100) * 10) / 10;
        $u = (int)(($this->productList[$elIndex][0]->u * $this->productList[$elIndex][1] / 100) * 10) / 10;
        $k = (int)(($this->productList[$elIndex][0]->k * $this->productList[$elIndex][1] / 100) * 10) / 10;

        return [
            'b' => $b,
            'j' => $j,
            'u' => $u,
            'k' => $k,
            'w' => $this->productList[$elIndex][1],
        ];
    }


    private function getAmountB()
    {
        foreach ($this->productList as $el) {
            $amount = $el[0]->b * $el[1] / 100;
            $this->amountB += (int)($amount * 10) / 10;

            //echo 'j: (', $el[0]->b, ') ', (int)($amount * 10) / 10, "\n";
        }
    }


    private function getAmountJ()
    {
        foreach ($this->productList as $el) {
            $amount = $el[0]->j * $el[1] / 100;
            $this->amountJ += (int)($amount * 10) / 10;

            //echo 'j: (', $el[0]->j, ') ', (int)($amount * 10) / 10, "\n";
        }
    }


    private function getAmountU()
    {
        foreach ($this->productList as $el) {
            $amount = $el[0]->u * $el[1] / 100;
            $this->amountU += (int)($amount * 10) / 10;

            //echo 'u: (', $el[0]->u, ') ', (int)($amount * 10) / 10, "\n";
        }
    }


    private function getAmountW()
    {
        foreach ($this->productList as $el) {
            $this->amountW += $el[1];
        }
    }


    private function getAmountK()
    {
        if ($this->amountB === 0) {
            $this->getAmountB();
        }

        if ($this->amountJ === 0) {
            $this->getAmountJ();
        }

        if ($this->amountJ === 0) {
            $this->getAmountU();
        }

        $amount = floor($this->amountB * 4 + $this->amountJ * 9 + $this->amountU * 4);
        $this->amountK = $amount;

        //echo 'k: ', $amount, "\n";
    }

    /**
     * @return array
     */
    public function getAmountNutritionalValue()
    {
        $this->getAmountB();
        $this->getAmountJ();
        $this->getAmountU();
        $this->getAmountW();
        $this->getAmountK();

        return [
            'b' => $this->amountB,
            'j' => $this->amountJ,
            'u' => $this->amountU,
            'k' => $this->amountK,
            'w' => $this->amountW,
        ];
    }
}