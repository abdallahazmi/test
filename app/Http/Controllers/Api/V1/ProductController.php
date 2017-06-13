<?php

namespace App\Http\Controllers\Api\V1;

use App\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;

/**
 * @SWG\Swagger(
 *   basePath="/api",
 *   @SWG\Info(
 *     title="Authentication API",
 *     version="1.0.0",
 *     description="This is a sample Authentication api and store product CRUD",
 *
 *   ),
 *
 *
 * )
 */

class ProductController extends Controller
{

    /**
     * @SWG\Get(
     *
     *   path="/products",
     *   summary="List All Product ",
     *
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=406, description="not acceptable"),
     *   @SWG\Response(response=500, description="internal server error")
     * )
     *
     */
    public function index() {
        $products = Product::orderBy('id', 'desc')->paginate(20)->toArray();

        if (count($products))
            $this->is_success = true;

        return response()->json(['success' => $this->is_success, 'message' => [], 'data' => $products]);
    }

    /**
     * @SWG\Post(
     *   path="/product/store",
     *   summary="Add new Product by user Api",
     *
     *
     *     @SWG\Parameter(
     *     name="Authorization",
     *     in="header",
     *     description="use Bearer . Token",
     *     required=true,
     *     type="string"
     *   ),
     *
     *     @SWG\Parameter(
     *     name="title",
     *     in="formData",
     *     description="Product title.",
     *     required=true,
     *     type="string"
     *   ),
     *
     *     @SWG\Parameter(
     *     name="description",
     *     in="formData",
     *     description="Product description .",
     *     required=true,
     *     type="string"
     *   ),
     *     @SWG\Parameter(
     *     name="category_id",
     *     in="formData",
     *     description="Product category ID.",
     *     required=true,
     *     type="integer"
     *   ),
     *
     *     @SWG\Parameter(
     *     name="image",
     *     in="formData",
     *     description="Image product.",
     *     required=true,
     *     type="file"
     *   ),
     *
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=406, description="not acceptable"),
     *   @SWG\Response(response=500, description="internal server error")
     * )
     *
     */
    public function store(Request $request) {


        $user = Auth::user();
        $product = new Product();
        $Input = $request->all();
        $product->title =$Input['title'];
        $product->description =$Input['description'];
        $product->category_id =$Input['category_id'];
        $product->user_id = $user->id;

        if(Input::hasFile('image')){
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalName();
            $image->move("uploads/", $imageName);
            $product->image='uploads/' .$imageName;
            if ($product->save()) {
                return response()->json(['success' => true, 'message' => [trans('common.success_added')]]);
            }
        }


        return response()->json(['success' => false, 'message' => [trans('common.failed_added')]]);

    }

    /**
     * @SWG\Get(
     *   path="/product/{product_id}",
     *   summary="Show one Product by id ",
     *    operationId="getCustomerRates",
     *
     *     @SWG\Parameter(
     *     name="product_id",
     *     in="path",
     *     description="Target Product.",
     *     required=true,
     *     type="integer"
     *   ),
     *
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=406, description="not acceptable"),
     *   @SWG\Response(response=500, description="internal server error")
     * )
     *
     */
    public function show($id) {
        $product = Product::with(['user'])->find($id);

        if (count($product))
            $this->is_success = true;

        return response()->json(['success' => $this->is_success, 'message' => [], 'data' => $product]);

    }

    private function uploadPic($id, $file) {

        $image_path = storage_path('stores' . DIRECTORY_SEPARATOR . $id);

        if (!\File::exists($image_path)) {
            \File::makeDirectory($image_path, 0755, true, true);
        }

        $filename = md5($file) . '.' . $file->getClientOriginalExtension();
        \Image::make($file)->save($image_path . DIRECTORY_SEPARATOR . $filename);
        \DB::table('store')->where('id', $id)->update(['logo_file_name' => $filename]);
    }

}
