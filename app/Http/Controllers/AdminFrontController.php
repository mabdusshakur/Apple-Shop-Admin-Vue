<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminFrontController extends Controller
{
    function dashboardPage()
    {
        return Inertia::render('DashboardPage');
    }

    function productPage()
    {
        return Inertia::render('ProductPage');
    }

    function categoryPage()
    {
        return Inertia::render('CategoryPage');
    }

    function brandPage()
    {
        return Inertia::render('BrandPage');
    }
}