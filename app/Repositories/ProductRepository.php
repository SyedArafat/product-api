<?php

namespace App\Repositories;

use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class ProductRepository
{
    private $id;

    private $product;

    public function __construct(Product $model)
    {
        $this->product = $model;
    }

    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    public function get()
    {
        return $this->product->orderBy('created_at', 'desc')->get();
    }

    public function delete()
    {
        $this->product->destroy($this->id);
    }

    /**
     * @param Request $request
     * @param $image
     */
    public function createProduct(Request $request, $image)
    {
        $data = Arr::only($request->all(), ['title', 'product_id', 'description', 'price']);
        $data = array_merge($data, ['image' => $image]);
        $this->product->create($data);
    }

    public function getProduct()
    {
        return $this->product->find($this->id);
    }

    /**
     * @param Request $request
     * @param $image
     */
    public function updateProduct(Request $request, $image)
    {
        $product = $this->getProduct();
        $product->title = $request->title;
        $product->product_id = $request->product_id;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->image = $image;
        $product->save();

    }
}
