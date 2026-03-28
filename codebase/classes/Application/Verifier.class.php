<?php
namespace Application;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Verifier
{
    public $userId;
    public $role;

    public function decode($jwt) 
    {   
        if (!empty($jwt)) {
            $jwt = trim($jwt);

            if (substr($jwt, 0, 7) === 'Bearer ') {
                $jwt = substr($jwt, 7);
            }

            try {
                // 👉 FIXED: Secret key now matches your Node.js service exactly!
                $secret = "M@k3_Th1s_S0m3th1ng_RanD0m_AnD_S3cur3_!";
                
                $token = JWT::decode($jwt, new Key($secret, 'HS256'));
                $this->userId = $token->userId;
                $this->role = $token->role;

                return true; // Return true so index.php knows it passed!
            } catch (\Throwable $e) {
                // The token wasn't valid.
                return false;
            }
        }
        return false;
    }
}