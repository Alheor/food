<?php

namespace App\Diary;

use App\Calculators\NutritionalValueCalculator;
use App\DayDiary;
use App\Dish;
use App\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class DayRation
{
    const DATA_ERROR = 1;

    private $dayObject;
    private $errorList = [];
    private $productListData = [];
    /** @var NutritionalValueCalculator  */
    private $nvc;

    /**
     * DayRation constructor.
     * @param DayDiary $day
     * @param NutritionalValueCalculator $nvc
     */
    public function __construct(DayDiary $day, NutritionalValueCalculator $nvc)
    {
        $this->dayObject = $day;
        $this->nvc = $nvc;

        if ($this->isValid()) {
            $this->paramsPrepare();
        }
    }


    /**
     * @return bool
     */
    public function isValid()
    {
        if (!isset($this->dayObject->data['mealList']) || !is_array($this->dayObject->data['mealList'])) {
            $this->errorList[self::DATA_ERROR] = 'Products data is empty or not an array';
        }

        return empty($this->errorList);
    }


    private function paramsPrepare()
    {
        $this->productLoader();
        $bjuc = $this->nvc->getAmountNutritionalValue();
        $this->calculateNutritionalValue();

        $this->dayObject->b = $bjuc['b'];
        $this->dayObject->j = $bjuc['j'];
        $this->dayObject->u = $bjuc['u'];
        $this->dayObject->k = $bjuc['k'];
        $this->dayObject->w = $bjuc['w'];
        //var_dump($bjuc);
    }

    private function productLoader()
    {
        $queryGuids = [];

        try {
            foreach ($this->dayObject->data['mealList'] as $eating) {
                foreach ($eating['productList']?? [] as $product) {
                    $queryGuids[$product['guid']] = $product['weight'];

                    if(!isset($this->productListData[$eating['mealGuid']][$product['guid']])) {
                        $this->productListData[$eating['mealGuid']][$product['guid']] = [
                            'guid' => $product['guid'],
                            'weight' => $product['weight']
                        ];
                    } else {
                        $this->productListData[$eating['mealGuid']][$product['guid']]['weight'] += $product['weight'];
                    }
                }
            }

            /** @var Collection $productList */
            $productList = Product::whereIn('guid', array_keys($queryGuids))->get();
            $dishList = Dish::whereIn('guid', array_keys($queryGuids))->get();

            $productList = $productList->merge($dishList);

            /** @var Product $product */
            foreach ($productList as $product) {
                $product->manufacturer->name;
                foreach ($this->productListData as $mealGuid => $products) {
                    foreach ($products as $key => $el) {
                        if ($el['guid'] === $product->guid) {
                            $this->nvc->addProduct(
                                $product,
                                $mealGuid,
                                $this->productListData[$mealGuid][$product->guid]['weight']
                            );
                            $this->productListData[$mealGuid][$key]['source'] = $product;
                        }
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


    public function calculateNutritionalValue()
    {
        foreach ($this->productListData as $mealGuid => &$eating) {
            foreach ($eating as &$productData) {
                $productData = array_merge($productData, $this->nvc->getProductNutritionalValueByGuid($mealGuid, $productData['guid']));
            }
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