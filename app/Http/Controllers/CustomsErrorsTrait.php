<?php

namespace App\Http\Controllers;

trait CustomsErrorsTrait
{
	public function getErrorMessage($message)
    {
        return
        [
            [
                'status' => 'FAILED',
                'message' => $message,
            ]
        ];
    }

	public function showSuccessMessage($message)
	{
		return
        [
            [
                'status' => 'OK',
                'message' => $message,
            ]
        ];
	}
}
