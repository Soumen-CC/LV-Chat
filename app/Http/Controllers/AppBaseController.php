<?php



namespace App\Http\Controllers;



use InfyOm\Generator\Utils\ResponseUtil;

use Response;



/**

 * @SWG\Swagger(

 *   basePath="/api/v1",

 *   @SWG\Info(

 *     title="Laravel Generator APIs",

 *     version="1.0.0",

 *   )

 * )

 * This class should be parent class for other API controllers

 * Class AppBaseController

 */

class AppBaseController extends Controller
{

    public function send_request($url, $type, $postFields = null){
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $type,
            //CURLOPT_POSTFIELDS => $postFields,
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }

    public function sendResponse($result, $message)
    {

        return Response::json(ResponseUtil::makeResponse($message, $result));

    }



    public function sendError($error, $code = 404)
    {

        return Response::json(ResponseUtil::makeError($error), $code);

    }



    public function sendSuccess($message)

    {

        return Response::json(['message' => $message, 'success' => true], 200);

    }



    public function sendData($data)

    {

        return Response::json($data, 200);

    }

}

