<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;

class CannotBeInjuredException extends Exception
{
    /**
     * Render the exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function render($request)
    {
        if ($request->expectsJson()) {
            return response()->json(['message' => $this->message], 400);
        }

        return redirect()->back()->withError($this->message);
    }
}
