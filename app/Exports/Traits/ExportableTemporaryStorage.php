<?php

namespace App\Exports\Traits;

use App\Storage\ExportsTemporaryStorage;
use Illuminate\Support\Facades\RateLimiter;
use Maatwebsite\Excel\Concerns\Exportable;

trait ExportableTemporaryStorage
{
    use Exportable;

    public bool $send_via_email = false;

    public function shouldSendViaEmail(): static
    {
        $this->send_via_email = true;

        return $this;
    }

    /**
     * Create an HTTP response that represents the object.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toResponse($request)
    {

        // if ($this->send_via_email) {
        //     $storage = new ExportsTemporaryStorageAndShareViaEmail($this);

        //     return [
        //         'message' => 'The report is being generated and will be sent to your email once ready.',
        //     ];
        // }

        $storage = new ExportsTemporaryStorage($this);

        return $storage->toResponse($request);
    }

    protected function abortIfRateLimited($user, $type)
    {
        $key = $type . '|' . $user->uid;
        if (RateLimiter::tooManyAttempts($key, 5)) {
            abort(
                429,
                'You have requested too many times for the download. Please check your email for the downloadable file(s). You can try again ' .
                    RateLimiter::availableIn($key) .
                    ' seconds.',
            );
        }
        RateLimiter::hit($key, 30 * 60);
    }
}
