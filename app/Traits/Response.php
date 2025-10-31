<?php
namespace App\Traits;

trait Response
{
    /**
     * Success response formatter
     *
     * @uses return error response
     * @param string  $message
     * @param mixed  $data
     *
     * @return array|object
     */
    /**
     * return success response.
     *
     * @param $message
     * @param $data
     *
     * @return \Illuminate\Http\Response
     */
    public function sendSuccess($data = null, $message = null)
    {
        // set default message
        if (empty($message)) {
            $message = __('messages.request.success');
        }

        if (is_array($message)) {
            $extract = array_values($message);
            $message = $extract[0];
        }

        $response['code'] = http_response_code();
        $response['success'] = true;
        $response['message'] = $message;
        $response['data'] = (!is_null($data)) ? $data : null;

        return response()->json($response, http_response_code());
    }

    /**
     * return error response.
     *
     * @param $message
     * @param $data
     *
     * @return \Illuminate\Http\Response
     */
    public function SendError($data = null, $message = null)
    {
        // set default message
        if (empty($message)) {
            $message = __('messages.request.error');
        }

        if (is_array($message)) {
            $extract = array_values($message);
            $message = $extract[0];
        }

        $response['code'] = http_response_code();
        $response['success'] = false;
        $response['message'] = $message;
        $response['data'] = (!is_null($data)) ? $data : null;

        return response()->json($response, http_response_code());
    }

    /**
     * Private function message
     * @param string|array  $message
     * @return string|array
     */
    private function message($message)
    {
        $message = (empty($message)) ? __('messages.request.success') : $message;

        // check if message constructed in array format (multiple message)
        if (is_array($message)) {
            $extract = array_values($message);
            $message = $extract[0];
        }

        return $message;
    }

    /**
     * Cast data into object
     * @param $response
     * @return object
     */
    private function castToObject($response)
    {
        return (object) $response;
    }
}