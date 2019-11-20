<?php

namespace App\Http\Helpers;

/**
 * Common response trait
 */
trait ResponseTrait
{

    /**
     * Response - Success
     * @param type $data
     * @param type $message
     * @param type $code
     * @return type
     */
    public function success($data = null, $message = 'SUCCESS', $code = 200)
    {
        $meta = [
            'status' => true,
            'message' => trans('message.'.$message),
            'message_code' => $message,
            'status_code' => $code
        ];
        //return response()->json(['meta' => $meta, 'data' => !is_array($data) ? [$data] : $data], $code);
        return response()->json(['meta' => $meta, 'data' => $data], $code);
    }

    /**
     * Response - Error
     * @param type $message
     * @param type $code
     * @return type
     */
    public function error($message = 'ERROR', $code = 422)
    {
        $meta = [
            'status' => false,
            'message' => trans('error.'.$message),
            'message_code' => $message,
            'status_code' => $code
        ];
        return response()->json(['meta' => $meta], $code);
    }

    /**
     * Response - Server side validation error message
     * @param type $validation
     * @return type
     */
    public function validationError(
        $validation,
        $message = 'VALIDATION_ERROR',
        $code = 422
    ) {
        $fieldMessages = $validation->errors();
        //return response($messages)->setStatusCode(422, 'Unprocessable Entity');
        $meta          = [
            'status' => false,
            'message' => 'Server side validation',
            'message_code' => $message,
            'status_code' => 422
        ];
        return response()->json(['meta' => $meta, 'errors' => $fieldMessages], $code);
    }
}
