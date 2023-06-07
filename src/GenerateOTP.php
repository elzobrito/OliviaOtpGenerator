<?php

namespace OliviaOTP;

class GenerateOTP
{
    public function get($secret, $counter)
    {
        return $this->generateOTP($secret, $counter);
    }

    public function getExpiryTime($secret, $counter, $expiryTime)
    {
        $currentTime = time();
        $validUntil = $currentTime + $expiryTime;

        if ($currentTime > $validUntil) {
            return false;
        }

        return $this->generateOTP($secret, $counter);
    }

    private function generateOTP($secret, $counter)
    {
        $hash = hash_hmac('sha1', pack('NN', 0, $counter), base64_decode($secret), true);
        $offset = ord(substr($hash, -1)) & 0x0F;
        $otp = (
            ((ord($hash[$offset + 0]) & 0x7F) << 24) |
            ((ord($hash[$offset + 1]) & 0xFF) << 16) |
            ((ord($hash[$offset + 2]) & 0xFF) << 8) |
            (ord($hash[$offset + 3]) & 0xFF)
        ) % 1000000;
        return str_pad($otp, 6, '0', STR_PAD_LEFT);
    }
}