<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use DateTime;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    protected function sendFailedLoginResponse(Request $request)
    {
        throw ValidationException::withMessages([
            $this->username() => [trans('メールアドレスまたはパスワードが違います。')],
        ]);
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function authenticated(Request $request, $user)
    {
        if (Storage::disk('local')->exists('config/paid_holidays.csv')) {
            $config = Storage::disk('local')->get('config/paid_holidays.csv');
            $config = str_replace(array("\r\n", "\r"), "\n", $config);
            $configArray = collect(explode("\n", $config));

            foreach ($configArray as $index => $dat) {
                $item = preg_split("/,/", $dat);
                if ($index == 0 || $dat == "") continue;

                $joinDate = new DateTime($user->joined_date);
                $year = intval($joinDate->format('Y'));
                $month = intval($joinDate->format('m'));
                $day = intval($joinDate->format('d'));

                $lastLoginRaw = $user->last_login;
                $lYear = 1990;
                $lMonth = 1;
                $lDay = 1;
                if ($lastLoginRaw != null && $lastLoginRaw != "") {
                    $lastLogin = new DateTime($user->last_login ?? "1990-01-01 0:00:00");
                    $lYear = intval($lastLogin->format('Y'));
                    $lMonth = intval($lastLogin->format('m'));
                    $lDay = intval($lastLogin->format('d'));
                }

                $current = new \DateTime();
                $cYear = intval($current->format('Y'));
                $cMonth = intval($current->format('m'));
                $cDay = intval($current->format('d'));

                $dat0 = intval($lYear . sprintf("%02d", $lMonth) . sprintf("%02d", $lDay));
                $dat1 = intval($year . sprintf("%02d", $month) . sprintf("%02d", $day));

                $target_day = date("Y-m-1", strtotime($joinDate->format("Y-m-d")));
//1ヶ月後の日時を取得
                $diffTime = strtotime($target_day . "+" . $item[0] . " month");
                $dat2 = intval(date("Y", $diffTime) . date("m", $diffTime) . date("d", $diffTime));

                $dat3 = intval($cYear . sprintf("%02d", $cMonth) . sprintf("%02d", $cDay));

                //echo "DAT0: ".$dat0."<br>DAT1: ".$dat1."<br>DAT2: ".$dat2."<br>DAT3: ".$dat3."<br><br>";

                if ($dat2 <= $dat0) {
                    //echo "<br><strong>SKIP</strong><br>";
                    continue;
                }

                if ($dat3 > $dat2) {
                    //echo "<br><strong>GET (".intval($item[1])." / ".($user->paid_holiday + intval($item[1])).")</strong><br>";
                    $user->paid_holiday = $user->paid_holiday + intval($item[1]);
                }
            }

            $user->last_login = new \DateTime();
            $user->save();
        }
    }
}
