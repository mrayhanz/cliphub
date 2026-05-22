<?php

namespace App\Http\Controllers\Brand;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Deposit;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class FinanceController extends Controller
{
    public function __construct()
    {
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = config('midtrans.is_production');
        \Midtrans\Config::$isSanitized = config('midtrans.is_sanitized');
        \Midtrans\Config::$is3ds = config('midtrans.is_3ds');
    }

    public function index(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        
        $depositsQuery = $user->deposits();
        
        $status = $request->input('status');
        if ($status) {
            $depositsQuery->where('status', $status);
        }
        
        $search = $request->input('search');
        if (!empty($search)) {
            $depositsQuery->where(function($q) use ($search) {
                $q->where('order_id', 'like', "%{$search}%")
                  ->orWhere('amount', 'like', "%{$search}%");
            });
        }
        
        $currentSort = $request->query('sort', 'newest');
        switch ($currentSort) {
            case 'oldest':
                $depositsQuery->orderBy('created_at', 'asc');
                break;
            case 'amount_high':
                $depositsQuery->orderBy('amount', 'desc');
                break;
            case 'amount_low':
                $depositsQuery->orderBy('amount', 'asc');
                break;
            case 'name_asc':
                $depositsQuery->orderBy('order_id', 'asc');
                break;
            case 'name_desc':
                $depositsQuery->orderBy('order_id', 'desc');
                break;
            case 'newest':
            default:
                $depositsQuery->orderBy('created_at', 'desc');
                break;
        }

        $deposits = $depositsQuery->paginate(10);
        
        // Count total escrow and available balance
        $balance = $user->balance ?? 0;
        $escrow = $user->campaigns()->where('status', 'active')->sum('budget');
        $activeCampaigns = $user->campaigns()->where('status', 'active')->orderBy('created_at', 'desc')->get();
        
        $filters = [
            '' => ['label' => 'Semua', 'icon' => 'list'],
            'success' => ['label' => 'Berhasil', 'icon' => 'check-circle'],
            'pending' => ['label' => 'Menunggu', 'icon' => 'clock'],
            'expired' => ['label' => 'Kedaluwarsa', 'icon' => 'alert-circle'],
            'failed' => ['label' => 'Gagal', 'icon' => 'x-circle'],
        ];

        $sortOptions = [
            'newest' => 'Terbaru',
            'oldest' => 'Terlama',
            'name_asc' => 'Order ID (A-Z)',
            'name_desc' => 'Order ID (Z-A)',
            'amount_high' => 'Nominal Tertinggi',
            'amount_low' => 'Nominal Terendah',
        ];

        return view('brand.finance.index', compact('user', 'deposits', 'balance', 'escrow', 'activeCampaigns', 'status', 'search', 'filters', 'sortOptions', 'currentSort'));
    }

    public function topup(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:10000',
        ]);

        /** @var \App\Models\User $user */
        $user = auth()->user();
        $orderId = 'DEP-' . $user->id . '-' . time();
        $amount = $request->amount;

        // Create pending deposit
        $deposit = $user->deposits()->create([
            'order_id' => $orderId,
            'amount' => $amount,
            'status' => 'pending',
        ]);

        // Request Snap Token
        $params = array(
            'transaction_details' => array(
                'order_id' => $orderId,
                'gross_amount' => $amount,
            ),
            'customer_details' => array(
                'first_name' => $user->name,
                'email' => $user->email,
            ),
        );

        try {
            $snapToken = \Midtrans\Snap::getSnapToken($params);
            
            // Save snap token to deposit
            $deposit->update(['snap_token' => $snapToken]);

            return response()->json([
                'status' => 'success',
                'snap_token' => $snapToken
            ]);
        } catch (\Exception $e) {
            Log::error('Midtrans Snap Error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal terhubung ke secure payment gateway.'
            ], 500);
        }
    }

    public function handleCallbackCallback(Request $request)
    {
        // This is a manual fallback for localhost environments where Midtrans Webhook cannot reach
        $request->validate(['order_id' => 'required']);
        
        $response = DB::transaction(function () use ($request) {
            $deposit = Deposit::where('order_id', $request->order_id)->lockForUpdate()->first();
            if (!$deposit) {
                return response()->json(['status' => 'error', 'message' => 'Deposit not found'], 404);
            }

            // Only update if it's currently pending.
            if ($deposit->status === 'pending') {
                $deposit->update([
                    'status' => 'success',
                    'payment_type' => $request->payment_type ?? 'midtrans_gateway'
                ]);
                
                // Update user balance
                $user = User::where('id', $deposit->user_id)->lockForUpdate()->first();
                if ($user) {
                    $user->increment('balance', $deposit->amount);
                }
            }

            return response()->json(['status' => 'success']);
        });

        return $response;
    }

    // Midtrans Webhook (Called server-to-server)
    public function webhook(Request $request)
    {
        // Add basic validation
        $serverKey = config('midtrans.server_key');
        $hashed = hash('sha512', $request->order_id . $request->status_code . $request->gross_amount . $serverKey);
        
        if ($hashed !== $request->signature_key) {
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $transactionStatus = $request->transaction_status;
        $orderId = $request->order_id;

        $response = DB::transaction(function () use ($orderId, $transactionStatus, $request) {
            $deposit = Deposit::where('order_id', $orderId)->lockForUpdate()->first();
            if (!$deposit) {
                return response()->json(['message' => 'Deposit not found'], 404);
            }

            // If transaction already success, just ignore
            if ($deposit->status === 'success') {
                return response()->json(['message' => 'Already updated']);
            }

            if ($transactionStatus == 'capture' || $transactionStatus == 'settlement') {
                $deposit->update([
                    'status' => 'success',
                    'payment_type' => $request->payment_type
                ]);
                
                // Update user balance
                $user = User::where('id', $deposit->user_id)->lockForUpdate()->first();
                if ($user) {
                    $user->increment('balance', $deposit->amount);
                }
            } elseif ($transactionStatus == 'cancel' || $transactionStatus == 'deny') {
                $deposit->update(['status' => 'failed']);
            } elseif ($transactionStatus == 'expire') {
                $deposit->update(['status' => 'expired']);
            } elseif ($transactionStatus == 'pending') {
                $deposit->update(['status' => 'pending']);
            }

            return response()->json(['message' => 'OK']);
        });

        return $response;
    }
}
