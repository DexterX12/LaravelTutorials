<?php
namespace App\Http\Controllers;

use Illuminate\View\View;

class HomeController extends Controller

{

    public function index(): View
    {
        return view('home.index');
    }

    public function contact(): View
    {
        $viewData = [];

        $pageTitle = "Contact Information - Online Store";
        $pageSubTitle = "Contact Information";

        $contactInfo = [
            "email" => "dex@fake.com",
            "phone" => "(123) 456-7890"
        ];

        $viewData["title"] = $pageTitle;
        $viewData["subtitle"] = $pageSubTitle;
        $viewData["contact"] = $contactInfo;

        return view('home.contact', ["viewData" => $viewData]);
    }

}