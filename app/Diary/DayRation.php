<?php

namespace App\Diary;

use App\Calculators\NutritionalValueCalculator;
use App\DayDiary;
use App\Product;
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
        if (!isset($this->dayObject->data['products']) || !is_array($this->dayObject->data['products'])) {
            $this->errorList[self::DATA_ERROR] = 'Products data is empty or not an array';
        }

        if (!isset($this->dayObject->data['weight'])) {
            $this->errorList[self::DATA_ERROR] = 'Myself weight is empty';
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
        $this->dayObject->my_weight = $this->dayObject->data['weight'];
        //var_dump($bjuc);
    }

    private function productLoader()
    {
        $queryGuids = [];
        try {
            foreach ($this->dayObject->data['products'] as $key => $eating) {
                foreach ($eating as $product) {
                    $queryGuids[$product['guid']] = $product['weight'];
                    $this->productListData[$key][$product['guid']] = [
                        'guid' => $product['guid'],
                        'weight' => $product['weight']
                    ];
                }
            }

            $productList = Product::whereIn('guid', array_keys($queryGuids))->get();

            /** @var Product $product */
            foreach ($productList as $product) {
                $this->nvc->addProduct($product,  $queryGuids[$product->guid]);
                $product->manufacturer->name;
                foreach (array_keys($this->productListData) as $key) {
                    if(isset($this->productListData[$key][$product->guid])) {
                        $this->productListData[$key][$product->guid]['name'] = $product->name;
                        $this->productListData[$key][$product->guid]['source'] = $product;
                        break;
                    }
                }
                unset($queryGuids[$product->guid]);
            }

            if (!empty($queryGuids)) {
                throw new \Exception('Some products not found: '. array_keys($queryGuids));
            }

        } catch (\Throwable $t) {
            $this->errorList[self::DATA_ERROR] = 'Products data problem: '. $t->getMessage();
        }
    }


    public function calculateNutritionalValue()
    {
        foreach ($this->productListData as &$eating) {
            foreach ($eating as &$productData) {
                $productData = array_merge($productData, $this->nvc->getProductNutritionalValueByGuid($productData['guid']));
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