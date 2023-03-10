<?php
namespace MicroweberPackages\Modules\SaasConnector\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class AdsBarController extends Controller
{
    public function index(Request $request) {

        $saasUrl = getWebsiteManagerUrl();
        $isLiveEdit = $request->get('live_edit', false);

        return view('saas_connector::ads-bar', [
            'saasUrl' => $saasUrl,
            'isLiveEdit' => $isLiveEdit
        ]);

    }
}
