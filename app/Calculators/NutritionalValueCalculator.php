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
    private $amountCellulose = 0;

    /**
     * @param $mealGuid
     * @param int $weight
     */
    public function addProduct($product, $mealGuid, int $weight) //todo один интерфейс для продуктов и блюд
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
        $cellulose = (int)(($this->productList[$elIndex][0]->cellulose * $this->productList[$elIndex][1] / 100) * 10) / 10;

        return [
            'b' => $b,
            'j' => $j,
            'u' => $u,
            'k' => $k,
            'cellulose' => $cellulose,
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

    private function getAmountCellulose()
    {
        foreach ($this->productList as $el) {
            $amount = $el[0]->cellulose * $el[1] / 100;
            $this->amountCellulose += (int)($amount * 10) / 10;

            //echo 'u: (', $el[0]->cellulose, ') ', (int)($amount * 10) / 10, "\n";
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
        $this->getAmountCellulose();

        return [
            'b' => $this->amountB,
            'j' => $this->amountJ,
            'u' => $this->amountU,
            'k' => $this->amountK,
            'cellulose' => $this->amountCellulose,
            'w' => $this->amountW
        ];
    }

    /**
     * @param int $weight
     * @return array
     */
    public function getAmountNutritionalValueFromDifferentWeight(int $weight)
    {
        $this->getAmountB();
        $this->getAmountJ();
        $this->getAmountU();
        $this->getAmountW();
        $this->getAmountK();
        $this->getAmountCellulose();

        $coeff = 1;
        if ($weight > 0) {
            $coeff = $this->amountW / $weight;
        }

        $resB = $this->amountB * $coeff * 100 / $this->amountW;
        $resJ = $this->amountJ * $coeff * 100 / $this->amountW;
        $resU = $this->amountU * $coeff * 100 / $this->amountW;
        $resK = $this->amountK * $coeff * 100 / $this->amountW;


        return [
            'b' => round($resB,1),
            'j' => round($resJ, 1),
            'u' => round($resU, 1),
            'k' => ceil($resK),
            'cellulose' => $this->amountCellulose,
            'w' => $this->amountW,
        ];
    }
}