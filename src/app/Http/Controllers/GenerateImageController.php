<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\ImageManagerStatic as Image;
use mysql_xdevapi\Exception;

/**
 * Class GenerateImageController
 * @package App\Http\Controllers
 */
class GenerateImageController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/generate-image/",
     *     description="Will attempt to create a new image",
     *     security={
     *        {"bearerAuth": {}}
     *     },
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     description="image to upload",
     *                     property="image",
     *                     type="file",
     *                     format="file",
     *                 )
     *             )
     *         )
     *      ),
     *     @OA\Response(
     *          response="200",
     *          description="Succes on Generate Image",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string"),
     *              @OA\Property(property="data", type="object")
     *          )
     *     ),
     *     @OA\Response(
     *          response="400",
     *          description="Error on Generate Image",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string"),
     *              @OA\Property(property="error", type="string")
     *          )
     *     ),
     *     @OA\Response(
     *          response="401",
     *          description="Unauthorized Error"
     *     ),
     *   )
     * )
     */

    /**
     * @OA\SecurityScheme(
     *     securityScheme="bearerAuth",
     *     in="header",
     *     type="http",
     *     description="Token Api security",
     *     name="Token api",
     *     scheme="bearer",
     *     bearerFormat="bearer",
     * )
     */

    /**
     * GenerateImageController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Generate function.
     *
     * @param Request $request
     *   The request.
     *
     * @return \Illuminate\Http\JsonResponse
     *   The return.
     */
    public function generate(Request $request) {
        $user = Auth::user();
        $host = $request->getSchemeAndHttpHost();
        if ($request->hasFile('image')) {
            $original_filename = $request->file('image')->getClientOriginalName();
            $original_filename_arr = explode('.', $original_filename);
            $file_ext = end($original_filename_arr);
            $destination_path = base_path() . '/public/user/';
            $image = time() . '.' . $file_ext;
            if ($request->file('image')->move($destination_path, $image)) {
                $user->image = $this->addTextToImage( $destination_path.$image, $user->name,$host);
            } else {
                return $this->responseRequestError('Cannot upload file');
            }
        }else{
            $user->image = $this->addTextToImage(null, $user->name, $host);
        }

        return $this->responseRequestSuccess($user);
    }

    /**
     * Add text to image.
     *
     * @param null $image
     *   The image file or null
     * @param string $name.
     *   The name of image.
     * @param string $host
     *   The host url to public folder.
     *
     * @return bool|string
     *   The return.
     */
    private function addTextToImage($image = null, $name, $host) {
        try {
            // open an image file
            $img = Image::make(base_path() . '/public/user/default.jpg');
            if($image) {
                $img = Image::make($image);
            }
            // resize image instance
            $img->resize(600, 480);
            // use callback to define details
            $img->text($name, 50, 250, function ($font) {
                $font->file(base_path() . '/public/Roboto-Bold.ttf');
                $font->size(120);
            });
            // save image in desired format
            $image_returned = '/public/user/'.time() . '.jpg';
            $img->save(base_path() . $image_returned);

            return $host . str_replace('/public', '', $image_returned);
        }catch (Exception $e) {
            report($e);
            return false;
        }
    }

    /**
     * Response request succes,
     *
     * @param object $data
     *   The data object.
     *
     * @return \Illuminate\Http\JsonResponse
     *   The return.
     */
    protected function responseRequestSuccess($data) {
        return response()->json(['status' => 'success', 'data' => $data], 200);
    }

    /**
     * Response request error.
     *
     * @param string $message
     *   The message.
     * @param int $statusCode
     *   The status code.
     *
     * @return \Illuminate\Http\JsonResponse
     *   The return.
     */
    protected  function responseRequestError($message = 'Bad request', $statusCode = 400) {
        return response()->json(['status' => 'error', 'error' => $message], $statusCode);
    }

}
