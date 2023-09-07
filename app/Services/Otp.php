<?php

namespace App\Services;

use App\Jobs\SendOtpViaEmailJob;
use Exception;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;


class Otp
{

    protected $model, $recipient, $checker, $medium, $ttl;

    private function __construct(string $model, object $recipient, array $checker, int $ttl = 10, string $medium = 'email')
    {
        $this->model = $model;
        $this->recipient = $recipient;
        $this->checker = $checker;
        $this->ttl = $ttl;
        $this->medium = $medium;
    }

    public static function send(string $model, object $recipient, array $checker, int $ttl = 10, string $medium = 'email')
    {
        return (new static($model, $recipient, $checker, $ttl, $medium))->build();
    }

    public function build()
    {
        return $this->sendOtp($this->recipient);
    }

    private function generateOTP()
    {
        $otp = random_int(100000, 999999);
        $otp_exist = $this->model::where([['token', $otp], ['expired_at', '>=', now()]])->first();
        if (!$otp_exist) {
            return $otp;
        } else {
            $this->generateOTP();
        }
    }

    private function getOtp()
    {
        $otp = $this->generateOTP();
        $this->model::updateOrCreate($this->checker, ['token' => $otp, 'expired_at' => now()->addMinutes($this->ttl)]);
        return $otp;
    }

    public function sendOtp($recipient)
    {
        try {
            $otp = $this->getOtp();
            SendOtpViaEmailJob::dispatch($recipient, $otp);
        } catch (Exception $e) {
            Log::info(json_encode($e));
            throw new HttpResponseException(response()->error(Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage()));
        }
    }
}
