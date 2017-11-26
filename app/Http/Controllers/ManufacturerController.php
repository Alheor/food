<?php

namespace App\Http\Controllers;

use App\Attributes;
use App\Manufacturer;
use App\Product;
use App\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Nathanmac\GUID\Facades\GUID;

class ManufacturerController extends Controller
{
    public function getManufacturersList(Request $request)
    {
        $search = trim($request->get('search'));

        if (!$request->has('search')) {
            $manufacturers = Manufacturer::orderBy('name', 'asc')->get();

            return view('Product.manufacturers', [
                'tplName' => 'productManufacturers',
                'manufacturers' => $manufacturers,
                'jsguid' => $request->get('guid')
            ]);
        } else {
            $manufacturers = Manufacturer::where('name', 'LIKE', "%{$search}%")
                ->orderBy('name', 'asc')
                ->get();

            return view('Product.manufacturers', [
                'tplName' => 'productManufacturersSearch',
                'manufacturers' => $manufacturers
            ]);
        }
    }

    public function addManufacturer(Request $request)
    {
        if ('GET' === $request->method()) {
            return view('Product.manufacturers', [
                'tplName' => 'addManufacturer',
            ]);
        }

        if ('POST' === $request->method()) {
            $mName = trim($request->get('mName'));

            if (empty($mName) || mb_strlen($mName) < 2) {
                throw new \Exception('Неверное имя производителя');
            }

            $manufacturer = new Manufacturer();

            $manufacturer->user_id = Auth::id();
            $manufacturer->guid = strtoupper(guid());
            $manufacturer->name = $mName;

            $manufacturer->save();
        }
    }
}
