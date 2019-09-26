<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class UserController extends Controller {

    /**
     * @OA\Get(
     *     path="/api/login",
     *     @OA\Parameter(
     *         name="email",
     *         example="test@test.com",
     *         in="query",
     *         description="The user email",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="password",
     *         example="password",
     *         in="query",
     *         description="the user password",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *          response="200",
     *          description="Succes Login",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string"),
     *              @OA\Property(property="api_token", type="string")
     *          )
     *     ),
     *     @OA\Response(
     *          response="402",
     *          description="Error: Bad request. When required parameters were not supplied.",
     *          @OA\JsonContent(
     *              @OA\Property(property="email", type="string"),
     *              @OA\Property(property="password", type="string")
     *          )
     *     ),
     *   )
     * )
     */

    public function __construct(){}

    /**
     * Authenticate Users.
     *
     * @param Request $request
     *   The request.
     * @return Response $status
     *   The response.
     * @throws ValidationException
     */
    public function authenticate(Request $request) {
        $this->validate($request, [
            'email' => 'required',
            'password' => 'required'
        ]);

        $status = response()->json(['status' => 'Fail, please, try again'],401);
        $user = User::where('email', $request->input('email'))->first();
        if($user) {
            if(Hash::check($request->input('password'), $user->password)){
                $apikey = base64_encode(Str::random(40));
                User::where('email', $request->input('email'))->update(['api_key' => "$apikey"]);

                $status = response()->json(['status' => 'Success', 'api_token' => $apikey]);
            }
        }

        return $status;
    }
}
