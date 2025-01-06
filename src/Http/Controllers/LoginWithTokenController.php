<?php

namespace MicroweberPackages\Modules\SaasConnector\Http\Controllers;

use App\Helper;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use MicroweberPackages\User\Models\User;

class LoginWithTokenController extends Controller
{
    public function index(Request $request)
    {
        $redirect = $request->get('redirect', false);

        $token = $request->get('token', false);
        if (empty($token)) {
            return redirect(admin_url());
        }

        $syncAdminDetails = $request->get('sync_admin_details', false);

        $validateLoginWithToken = validateLoginWithToken($token);
        if ($validateLoginWithToken) {

            $user = User::where('is_admin', '=', '1')->first();
            if ($user !== null) {

                if ($syncAdminDetails) {
                    if (isset($validateLoginWithToken['user']['email'])) {
                        if ($user->email != $validateLoginWithToken['user']['email']) {
                            $user->email = $validateLoginWithToken['user']['email'];
                            if (isset($validateLoginWithToken['user']['first_name'])) {
                                $user->first_name = $validateLoginWithToken['user']['first_name'];
                            }
                            if (isset($validateLoginWithToken['user']['last_name'])) {
                                $user->last_name = $validateLoginWithToken['user']['last_name'];
                            }
                            $user->save();
                        }
                    }
                }

                \Illuminate\Support\Facades\Auth::login($user);

                if (request()->wantsJson()) {
                    return response()->json([
                        'success' => true,
                        'id' => $user->id,
                        'is_admin' => $user->is_admin,
                        'redirect' => admin_url()
                    ]);
                }


                if (!empty($redirect)) {
                    return redirect($redirect);
                }

                return redirect(admin_url());
            }
        }

        return redirect(admin_url());
    }

}
