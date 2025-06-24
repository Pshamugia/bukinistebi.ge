<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class PaymentCallbackController extends Controller
{
    protected $success_status = "Succeeded";

    public function handle(Request $request)
    {
        $paymentId = $request->input('PaymentId');


        Log::info("Got paymentID from cb:", [
            "paymentID" => $paymentId,
        ]);

        if (!$paymentId) {
            Log::warning('Callback received without PaymentId');
            return response()->json(['error' => 'Missing PaymentId'], 400);
        }

        $token = $this->getAccessToken();
        if (!$token) {
            Log::error('Failed to get access token in callback');
            return response()->json(['error' => 'Auth error'], 500);
        }

        //$response = Http::withToken($token)->get(env('TBC_BASE_URL') . "/v1/tpay/payments/$paymentId");
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'apikey' => env('TBC_API_KEY'),
            'Content-Type' => 'application/json',
        ])->get(env('TBC_BASE_URL') . "/v1/tpay/payments/$paymentId");


        if (!$response->ok()) {
            Log::error('Failed to fetch payment info from TBC', [
                'paymentId' => $paymentId,
                'response' => $response->body()
            ]);
            return response()->json(['error' => 'Payment fetch failed'], 500);
        }

        $data = $response->json();

        if (($data['status'] ?? '') !== $this->success_status) {
            Log::info('Payment not finalized yet or not successful', ['paymentId' => $paymentId, 'status' => $data['status'] ?? null]);
            return response()->json(['status' => 'not finalized'], 200);
        }


        // extracting from generated id
        $parts = explode('-', $data['merchantPaymentId'] ?? '');
        if (count($parts) < 5 || $parts[0] !== 'AUC' || $parts[1] !== 'FEE') {
            Log::warning('Invalid merchantPaymentId format', [
                'merchantPaymentId' => $data['merchantPaymentId'] ?? 'null'
            ]);
            return response()->json(['error' => 'Invalid format'], 400);
        }

        $userId = (int) $parts[2];
        $auctionId = (int) $parts[3];

        DB::beginTransaction();
        try {
            $updated = DB::table('auction_users')
                ->where('user_id', $userId)
                ->where('auction_id', $auctionId)
                ->whereNull('paid_at')
                ->update([
                    'paid_at' => now(),
                    'updated_at' => now(),
                ]);

            if ($updated) {
                DB::commit();
                Log::info('Auction fee marked as paid', compact('userId', 'auctionId'));
            } else {
                DB::rollback();
                Log::warning('No unpaid auction_users row found to update', compact('userId', 'auctionId'));
            }
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Database error in payment callback', [
                'error' => $e->getMessage(),
                'userId' => $userId,
                'auctionId' => $auctionId
            ]);
        }

        return response()->json(['status' => 'ok'], 200);
    }

    private function getAccessToken()
    {
        $response = Http::asForm()->withHeaders([
            'apikey' => env('TBC_API_KEY'),
        ])->post('https://api.tbcbank.ge/v1/tpay/access-token', [
            'client_id' => env('TBC_CLIENT_ID'),
            'client_secret' => env('TBC_CLIENT_SECRET'),
        ]);

        if ($response->successful()) {
            $tokenData = $response->json();
            return $tokenData['access_token'] ?? null;
        } else {
            Log::error('Failed to retrieve access token', ['response' => $response->json()]);
            return null;
        }
    }
}
