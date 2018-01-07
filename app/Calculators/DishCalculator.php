<?php

namespace App\Calculators;


use App\Dish;
use App\Product;

class DishCalculator
{
    const DATA_ERROR = 1;

    /** @var NutritionalValueCalculator  */
    private $nvc;

    /** @var array  */
    private $dish;

    /** @var array */
    private $errorList;

    /** @var array */
    private $productListData;

    /**
     * DishCalculator constructor.
     * @param NutritionalValueCalculator $nvc
     * @param Dish $dish
     */
    public function __construct(NutritionalValueCalculator $nvc, Dish $dish)
    {
        $this->nvc = $nvc;
        $this->dish = $dish;

        if ($this->isValid()) {
            $this->paramsPrepare();
        }
    }

    private function paramsPrepare()
    {
        $this->productLoader();
        $bjuc = $this->nvc->getAmountNutritionalValueFromDifferentWeight((int)$this->dish->data['data']['weight_after']);
        $this->calculateNutritionalValue();

        $this->dish->b = $bjuc['b'];
        $this->dish->j = $bjuc['j'];
        $this->dish->u = $bjuc['u'];
        $this->dish->k = $bjuc['k'];
        $this->dish->cellulose = $bjuc['cellulose'];
        $this->dish->weight_before = $bjuc['w'];

        //var_dump($bjuc);
    }

    /**
     * @return bool
     */
    public function isValid()
    {
        if (!isset($this->dish->data['data']) || !is_array($this->dish->data['data'])) {
            $this->errorList[self::DATA_ERROR] = 'Products data is empty or not an array';
        }

        if (!isset($this->dish->data['data']['product_list']) || !is_array($this->dish->data['data']['product_list'])) {
            $this->errorList[self::DATA_ERROR] = 'Products data is empty or not an array';
        }

        if (!isset($this->dish->data['data']['weight_after']) || !is_numeric($this->dish->data['data']['weight_after'])) {
            $this->errorList[self::DATA_ERROR] = 'Weight after is empty or not an numeric';
        }

        return empty($this->errorList);
    }


    public function productLoader()
    {
        $queryGuids = [];

        try {
            foreach ($this->dish->data['data']['product_list'] as $product) {
                $queryGuids[$product['guid']] = $product['weight'];

                if(!isset($this->productListData[$product['guid']])) {
                    $this->productListData[$product['guid']] = [
                        'guid' => $product['guid'],
                        'weight' => $product['weight']
                    ];
                } else {
                    $this->productListData[$product['guid']]['weight'] += $product['weight'];
                }
            }

            $productList = Product::whereIn('guid', array_keys($queryGuids))->get();

            /** @var Product $product */
            foreach ($productList as $product) {
                $product->manufacturer->name;
                foreach ($this->productListData as $guid => $el) {
                    if ($el['guid'] === $product->guid) {
                        $this->nvc->addProduct(
                            $product,
                            $guid,
                            $this->productListData[$product->guid]['weight']
                        );
                        $this->productListData[$product->guid]['source'] = $product;
                    }
                }

                unset($queryGuids[$product->guid]);
            }

            //print_r($this->productListData);exit;
            if (!empty($queryGuids)) {
                throw new \Exception('Some products not found: '. array_keys($queryGuids));
            }

        } catch (\Throwable $t) {
            $this->errorList[self::DATA_ERROR] = 'Products data problem: '. $t->getMessage();
        }
    }


    private function calculateNutritionalValue()
    {
        foreach ($this->productListData as $guid => &$productData) {
            $productData = array_merge($productData, $this->nvc->getProductNutritionalValueByGuid($guid, $productData['guid']));
        }
    }

    /**
     * @return array
     */
    public function getProductData()
    {
        return $this->productListData;
    }

    /**
     * @return array
     */
    public function getErrors() {
        return $this->errorList;
    }
}