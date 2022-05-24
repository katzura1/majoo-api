<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductCategoryController extends Controller
{
    public function create(Request $request)
    {
        $data_validator = [
            'name' => 'required|string|unique:product_categories,name,NULL,id,deleted_at,NULL',
        ];

        $validator = Validator::make($request->all(), $data_validator);

        if ($validator->fails()) {
            $response = [
                'code' => 400,
                'message' => $validator->errors()->all(),
                'data' => [],
            ];
            return response()->json($response, 200);
        } else {
            $product_category = ProductCategory::create($request->all());
            $response = [
                'code' => 201,
                'message' => 'Product category created',
                'data' => $product_category,
            ];
            return response()->json($response, 200);
        }
    }

    public function read(Request $request, $id = '')
    {
        if (empty($id)) {
            $product_categories = ProductCategory::all();
            $response = [
                'code' => 201,
                'message' => 'Product categories read',
                'data' => $product_categories,
            ];
            return response()->json($response, 200);
        } else {
            $product_category = ProductCategory::find($id);
            if ($product_category) {
                $response = [
                    'code' => 201,
                    'message' => 'Product category read',
                    'data' => $product_category,
                ];
                return response()->json($response, 200);
            } else {
                $response = [
                    'code' => 404,
                    'message' => 'Product category not found',
                    'data' => [],
                ];
                return response()->json($response, 200);
            }
        }
    }

    public function update(Request $request, $id)
    {
        $data_validator = [
            'name' => 'required|string|unique:product_categories,name,' . $id . ',id,deleted_at,NULL',
        ];

        $validator = Validator::make($request->all(), $data_validator);

        if ($validator->fails()) {
            $response = [
                'code' => 400,
                'message' => $validator->errors()->all(),
                'data' => [],
            ];
            return response()->json($response, 200);
        } else {
            $product_category = ProductCategory::find($id);
            if ($product_category) {
                $product_category->update($request->all());
                $response = [
                    'code' => 201,
                    'message' => 'Product category updated',
                    'data' => $product_category,
                ];
                return response()->json($response, 200);
            } else {
                $response = [
                    'code' => 404,
                    'message' => 'Product category not found',
                    'data' => [],
                ];
                return response()->json($response, 200);
            }
        }
    }

    public function delete(Request $request, $id)
    {
        $product_category = ProductCategory::find($id);
        if ($product_category) {
            $product_category->delete();
            $response = [
                'code' => 201,
                'message' => 'Product category deleted',
                'data' => [],
            ];
            return response()->json($response, 200);
        } else {
            $response = [
                'code' => 404,
                'message' => 'Product category not found',
                'data' => [],
            ];
            return response()->json($response, 200);
        }
    }
}
