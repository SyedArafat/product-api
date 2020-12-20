<?php

namespace App\Http\Controllers;

use App\FileManager\FileManager;
use App\Repositories\ProductRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class ProductController extends Controller
{
    use FileManager;

    private $repository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->repository = $productRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $data = $this->repository->get();
        return response()->json(["message" => "success", "code" =>200, "data" => $data ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        try {
            $this->validate($request, [
                'product_id'  => 'required',
                'title'       => 'required',
                'description' => 'required',
                'price'       => 'required',
                'image'       => 'required|file|max:5000'
            ]);
            $image = $this->storeFile($request, 'image','product_image');
            $this->repository->createProduct($request, $image);
            return response()->json(["message" => "success", "code" =>200, "data" => ["message" => "Product created successfully"]]);
        } catch (ValidationException $exception) {
            return response()->json(["message" => "failed", "code" =>400, "data" => $exception->getMessage()]);
        } catch (\Throwable $exception) {
            return response()->json(["message" => "failed", "code" =>500, "data" => $exception->getMessage()]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  int  $id
     * @return JsonResponse
     */
    public function update(Request $request, $id)
    {
        try {
            $this->validate($request, [
                'product_id'  => 'required',
                'title'       => 'required',
                'description' => 'required',
                'price'       => 'required',
                'image'       => 'required|file|max:5000'
            ]);
            $image = $this->storeFile($request, 'image','product_image');
            $this->repository->setID($id)->updateProduct($request, $image);
            return response()->json(["message" => "Product updated", "code" => 200]);
        } catch (\Throwable $exception) {
            dd($exception);
            return response()->json(["message" => $exception->getMessage(), "code" => 500]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        try {
            $product = $this->repository->setID($id)->getProduct();
            $this->deleteFile($product->image);
            $this->repository->setID($id)->delete();
            return response()->json(["message" => "Product deleted", "code" => 200]);
        } catch (\Throwable $exception) {
            return response()->json(["message" => $exception->getMessage(), "code" => 500]);
        }
    }

    public function getUser()
    {
        return response()->json([
            'message' => "Successful",
            'code'    => 200,
            'user'    => auth()->user()
        ]);
    }
}
