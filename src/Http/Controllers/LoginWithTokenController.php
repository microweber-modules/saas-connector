<?php
namespace MicroweberPackages\Modules\SaasConnector\Http\Controllers;

use App\Helper;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use MicroweberPackages\User\Models\User;

class LoginWithTokenController extends Controller
{
    public function index(Request $request) {

        $redirect = $request->get('redirect', false);

        $token = $request->get('token', false);
        if (empty($token)) {
            return redirect(admin_url());
        }

        if (validateLoginWithToken($token)) {

            $user = User::where('is_admin', '=', '1')->first();
            if ($user !== null) {
                \Illuminate\Support\Facades\Auth::login($user);
                if (!empty($redirect)) {
                    return redirect($redirect);
                }

                return redirect(admin_url());
            }
        }

        return redirect(admin_url());
    }

}
