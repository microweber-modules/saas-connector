<?php
namespace MicroweberPackages\Modules\SaasConnector\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class AdsBarController extends Controller
{
    public function index(Request $request) {

        return view('saas_connector::ads-bar');

    }
}
