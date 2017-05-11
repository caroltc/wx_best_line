<?php
namespace App\Api\V1\Controllers;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

/**
 * Created by PhpStorm.
 * User: root
 * Date: 17-5-9
 * Time: 下午2:00
 */
class LineController extends Controller
{

    public function show(Request $request)
    {
        $credentials = $request->only('unionId');
        try {
            // attempt to verify the credentials and create a token for the user
            if (!$token = JWTAuth::attempt($credentials)) {
                 return response()->json(['error' => 'invalid_credentials'], 401);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token']);
        }
        // all good so return the token
        return $this->response->array(compact('token'));
    }

    public function check(Request $request)
    {
        JWTAuth::parseToken();// and you can continue to chain methods
        $user = JWTAuth::parseToken()->authenticate();
        return ['user' => $user];
    }
}