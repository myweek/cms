<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * 显示后台仪表盘
     */
    public function index(): View
    {
        return view('admin.dashboard');
    }
}
