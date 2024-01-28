<?php

namespace App\Http\Controllers/*\Auth*/;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Foundation\Auth\RedirectsUsers;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Http\Request;
use App\Providers\RouteServiceProvider;
use Auth;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AdminLoginController extends Controller
{

    use  ThrottlesLogins;
    public function __construct()
    {
        parent::__construct();
        $this->middleware('guest:admin',['except'=>['logout']]);
//        App::setLocale('ar');
//        session()->put('locale', 'ar');
    }
//    public function index()
//    {
//        $title = "Cars";
//        return view('admin.home',compact('title'));
//    }
    public function showLoginForm()
    {

        return view('admin.admin-login');
    }
    public function login(Request $request)
    {
        $this->validate($request,
            ['email' => 'required',
                'password' => 'required|min:8|regex:/^.*(?=[^a-z]*[a-z])(?=[^A-Z]*[A-Z]).*$/' ]);

        if(Auth::guard('admin')->attempt(['email' => $request->email,'password'=>$request->password] ,$request->remember)){
            return redirect()->intended(route('admin.home'));
        }
//        else {
//            return back()->withErrors([
//                'email' => 'hghghg1'  ]);
//        }

//        if (! RateLimiter::tooManyAttempts($this->throttleKey($request), 3)) {
//            return redirect('admin/login')->withErrors([
//                'email' => 'no no'  ]);
//        }
        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {

            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            if ($request->hasSession()) {
                $request->session()->put('auth.password_confirmed_at', time());
            }

            return $this->sendLoginResponse($request);
        }
        $this->incrementLoginAttempts($request);

        return redirect('admin/login')->withInput($request->only('email','remember'))->withErrors([
            'email' => __('message.invalid_login')   ]);/*__('validation.invalid_admin_password')*/
    }

    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect('admin/login');
    }


    protected function credentials(Request $request)
    {
        return $request->only('email', 'password');
    }

    public function username()
    {
        return 'email';
    }


    protected function attemptLogin(Request $request)
    {
        return Auth::guard('admin')->attempt(
            $this->credentials($request), $request->filled('remember')
        );
    }
//    protected function hasTooManyLoginAttempts(Request $request)
//    {
//        return $this->limiter()->tooManyAttempts(
//            $this->throttleKey($request), $this->maxAttempts()
//        );

}
