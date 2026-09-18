<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BankAccount;
use App\Models\BankCard;
use App\Models\Transaction;
use App\Models\Loan;
use App\Models\Investment;
use App\Models\CmsArticle;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $cards = BankCard::where('is_active', true)->take(3)->get();
        
        $recentTransactions = Transaction::with('account')
            ->orderBy('transaction_date', 'desc')
            ->orderBy('id', 'desc')
            ->take(6)
            ->get();

        $totalBalance = BankAccount::sum('balance');
        $totalAccounts = BankAccount::count();
        $totalInflowMonth = Transaction::where('type', 'credit')
            ->whereMonth('transaction_date', Carbon::now()->month)
            ->sum('amount');
        $totalOutflowMonth = Transaction::where('type', 'debit')
            ->whereMonth('transaction_date', Carbon::now()->month)
            ->sum('amount');

        // Weekly Activity (Sat, Sun, Mon, Tue, Wed, Thu, Fri)
        $days = [__('Sat'), __('Sun'), __('Mon'), __('Tue'), __('Wed'), __('Thu'), __('Fri')];
        $depositWeekly = [420, 330, 300, 340, 480, 160, 320];
        $withdrawWeekly = [210, 140, 240, 330, 390, 210, 330];

        // Expense Statistics
        $expenseCategories = [
            'labels' => [__('Entertainment'), __('Bill Expense'), __('Investment'), __('Others')],
            'data' => [30, 25, 25, 20],
            'colors' => ['#396AFF', '#FF1493', '#232360', '#FC7900']
        ];

        // Balance History (Jul -> Jun)
        $balanceHistory = [
            'labels' => [__('Jul'), __('Aug'), __('Sep'), __('Oct'), __('Nov'), __('Dec'), __('Jan'), __('Feb'), __('Mar'), __('Apr'), __('May'), __('Jun')],
            'data' => [120, 260, 220, 380, 680, 490, 410, 520, 360, 440, 380, 710]
        ];

        // Quick Transfer People
        $quickContacts = [
            [
                'name' => 'Charleen',
                'role' => 'CTO',
                'avatar' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=150&auto=format&fit=crop&q=80',
                'account' => '1029-3847-5612'
            ],
            [
                'name' => 'Livia Bator',
                'role' => 'CEO',
                'avatar' => 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?w=150&auto=format&fit=crop&q=80',
                'account' => '2093-8475-1029'
            ],
            [
                'name' => 'Randy Press',
                'role' => 'Director',
                'avatar' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=150&auto=format&fit=crop&q=80',
                'account' => '3049-5820-9941'
            ],
            [
                'name' => 'Michael Chen',
                'role' => 'Finance Lead',
                'avatar' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=150&auto=format&fit=crop&q=80',
                'account' => '4091-8273-6652'
            ],
        ];

        $latestAnnouncements = CmsArticle::where('is_published', true)->latest('published_at')->take(3)->get();

        return view('dashboard.index', compact(
            'cards',
            'recentTransactions',
            'totalBalance',
            'totalAccounts',
            'totalInflowMonth',
            'totalOutflowMonth',
            'days',
            'depositWeekly',
            'withdrawWeekly',
            'expenseCategories',
            'balanceHistory',
            'quickContacts',
            'latestAnnouncements'
        ));
    }
}
