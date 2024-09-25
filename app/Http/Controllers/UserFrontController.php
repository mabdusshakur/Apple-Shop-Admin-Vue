<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Inertia\Inertia;

class UserFrontController extends Controller
{
    function homePage()
    {
        return Inertia::render('HomePage');
    }

    function loginPage()
    {
        return Inertia::render('Auth/LoginPage');
    }

    function verifyOtpPage()
    {
        return Inertia::render('Auth/VerifyPage');
    }

    function profilePage()
    {
        return Inertia::render('ProfilePage');
    }

    function wishListPage()
    {
        return Inertia::render('WishListPage');
    }
    function cartListPage()
    {
        return Inertia::render('CartListPage');
    }

    function productDetailsPage()
    {
        return Inertia::render('DetailsPage');
    }
    function policyPage()
    {
        return Inertia::render('PolicyPage');
    }
    function productByBrandPage()
    {
        return Inertia::render('ProductByBrandPage');
    }
    function productByCategoryPage()
    {
        return Inertia::render('ProductByCategoryPage');
    }

}