<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function create(Request $request)
    {
        $data_validator = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:admins,email,NULL,id,deleted_at,NULL',
            'password' => 'required|string|min:6',
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
            $request['password'] = password_hash($request['password'], PASSWORD_DEFAULT);
            $admin = Admin::create($request->all());
            $response = [
                'code' => 201,
                'message' => 'User created',
                'data' => $admin,
            ];
            return response()->json($response, 200);
        }
    }

    public function read(Request $request, $id = '')
    {
        if (empty($id)) {
            $admins = Admin::all();
            $response = [
                'code' => 200,
                'message' => 'Admins read',
                'data' => $admins,
            ];
            return response()->json($response, 200);
        } else {
            $admin = Admin::find($id);
            if ($admin) {
                $response = [
                    'code' => 200,
                    'message' => 'User read',
                    'data' => $admin,
                ];
                return response()->json($response, 200);
            } else {
                $response = [
                    'code' => 404,
                    'message' => 'User not found',
                ];
                return response()->json($response, 200);
            }
        }
    }

    public function update(Request $request, $id)
    {
        $admin = Admin::find($id);
        if ($admin) {
            $data_validator = [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:admins,email,' . $id . ',id,deleted_at,NULL',
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
                $admin->update($request->all());
                $response = [
                    'code' => 200,
                    'message' => 'User updated',
                    'data' => $admin,
                ];
                return response()->json($response, 200);
            }
        } else {
            $response = [
                'code' => 404,
                'message' => 'User not found',
            ];
            return response()->json($response, 200);
        }
    }

    public function update_password(Request $request, $id)
    {
        $admin = Admin::find($id);
        if ($admin) {
            $data_validator = [
                'password' => 'required|string|min:6',
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
                $admin->update(['password' => password_hash($request['password'], PASSWORD_DEFAULT)]);
                $response = [
                    'code' => 200,
                    'message' => 'User password updated',
                    'data' => $admin,
                ];
                return response()->json($response, 200);
            }
        } else {
            $response = [
                'code' => 404,
                'message' => 'User not found',
            ];
            return response()->json($response, 200);
        }
    }

    public function delete(Request $request, $id)
    {
        $admin = Admin::find($id);
        if ($admin) {
            $admin->delete();
            $response = [
                'code' => 200,
                'message' => 'User deleted',
                'data' => [],
            ];
            return response()->json($response, 200);
        } else {
            $response = [
                'code' => 404,
                'message' => 'User not found',
            ];
            return response()->json($response, 200);
        }
    }

    public function login(Request $request)
    {
        $data_validator = [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6',
        ];

        $validator = Validator::make($request->all(), $data_validator);

        if ($validator->fails()) {
            $response = [
                'code' => 400,
                'message' => $validator->errors()->all(),
                'data' => [],
            ];
        } else {
            $admin = Admin::where('email', $request['email'])->first();
            if ($admin) {
                if (password_verify($request['password'], $admin->password)) {
                    $response = [
                        'code' => 200,
                        'message' => 'User logged in',
                        'data' => $admin,
                    ];
                } else {
                    $response = [
                        'code' => 401,
                        'message' => 'Wrong password',
                        'data' => [],
                    ];
                }
            } else {
                $response = [
                    'code' => 404,
                    'message' => 'User not found',
                    'data' => [],
                ];
            }
        }
        return response()->json($response, 200);
    }
}
