<?php

/**
 * Date formater
 *
 * @param string $date
 * @param boolean $withTime
 *
 * @return string
 */
function dateFormat($date, $withTime = true)
{
    return $date ? now()->parse($date)->format($withTime ? 'jS F, Y - h:i A' : 'jS F, Y') : null;
}

/**
 * Api message response
 * 
 * @param string $status,
 * @param string $message
 * @param number $code
 * 
 * @return \Illuminate\Http\JsonResponse
 * 
 */
function messageResponse($status, $message = null, $code = 200)
{
    return response()->json([
        'status'  => $status,
        'message' => $message,
    ], $code);
}