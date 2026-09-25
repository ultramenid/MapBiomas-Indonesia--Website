<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Models\Initiative;
use App\Models\NewsItem;
use App\Models\Partner;
use App\Models\TeamMember;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function login()
    {
        return view('cms.login');
    }

    public function index()
    {
        $stats = [
            'news' => NewsItem::count(),
            'faqs' => Faq::count(),
            'team' => TeamMember::count(),
            'initiatives' => Initiative::count(),
            'partners' => Partner::count(),
            'users' => User::count(),
        ];

        $recentNews = NewsItem::orderByDesc('created_at')->take(5)->get();
        $recentFaqs = Faq::orderByDesc('id')->take(5)->get();

        return view('cms.index', compact('stats', 'recentNews', 'recentFaqs'));
    }

    public function logout(Request $request)
    {
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
