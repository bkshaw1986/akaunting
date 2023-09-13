<?php

namespace App\Http\Controllers\Auth;

use App\Abstracts\Http\Controller;
use App\Http\Requests\Auth\Login as Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Str;
use Illuminate\Http\Request as Req;
use App\Models\Auth\User;
use \GuzzleHttp\Client;

class Login extends Controller
{
  use AuthenticatesUsers;

  /**
   * Where to redirect users after login.
   *
   * @var string
   */
  protected $redirectTo = '/';

  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
    $this->middleware('guest', ['except' => 'destroy']);
  }

  public function create()
  {
    return view('auth.login.create');
  }

  public function store(Request $request)
  {
    // Attempt to login
    if (!auth()->attempt($request->only('email', 'password'), $request->get('remember', false))) {
      return response()->json([
        'status' => null,
        'success' => false,
        'error' => true,
        'message' => trans('auth.failed'),
        'data' => null,
        'redirect' => null,
      ]);
    }

    // Get user object
    $user = user();

    // Check if user is enabled
    if (!$user->enabled) {
      $this->logout();

      return response()->json([
        'status' => null,
        'success' => false,
        'error' => true,
        'message' => trans('auth.disabled'),
        'data' => null,
        'redirect' => null,
      ]);
    }

    $company = $user->withoutEvents(function () use ($user) {
      return $user->companies()->enabled()->first();
    });

    // Logout if no company assigned
    if (!$company) {
      $this->logout();

      return response()->json([
        'status' => null,
        'success' => false,
        'error' => true,
        'message' => trans('auth.error.no_company'),
        'data' => null,
        'redirect' => null,
      ]);
    }

    // Redirect to portal if is customer
    if ($user->isCustomer()) {
      $path = session('url.intended', '');

      // Path must start with company id and 'portal' prefix
      if (!Str::startsWith($path, $company->id . '/portal')) {
        $path = route('portal.dashboard', ['company_id' => $company->id]);
      }

      return response()->json([
        'status' => null,
        'success' => true,
        'error' => false,
        'message' => trans('auth.login_redirect'),
        'data' => null,
        'redirect' => url($path),
      ]);
    }

    // Redirect to landing page if is user
    $url = route($user->landing_page, ['company_id' => $company->id]);

    return response()->json([
      'status' => null,
      'success' => true,
      'error' => false,
      'message' => trans('auth.login_redirect'),
      'data' => null,
      'redirect' => redirect()->intended($url)->getTargetUrl(),
    ]);
  }

  public function destroy()
  {
    $this->logout();

    return redirect()->route('login');
  }

  public function logout()
  {
    auth()->logout();

    // Session destroy is required if stored in database
    if (config('session.driver') == 'database') {
      $request = app('Illuminate\Http\Request');

      $request->session()->invalidate();
      $request->session()->regenerateToken();
      $request->session()->getHandler()->destroy($request->session()->getId());
    }
  }

  // Added by Rajasekar from OLD version 
  public function authenticate(Req $request)
  {
    $leftR = $request['GatewayLeftR'];
    $leftId = $request['GatewayLeftID'];
    $rightR = $request['GatewayRightR'];
    $rightId = $request['GatewayRightID'];
    $cLeftR = $request['ContextLeftR'];
    $cLeftId = $request['ContextLeftID'];
    $cRightR = $request['ContextRightR'];
    $cRightId = $request['ContextRightID'];
    $agenofPe = $request['AgentOfPE'];
    $gatewayPe = $request['GatewayPE'];
    $arr = [
      'GatewayLeftR'   => $leftR,
      'GatewayLeftID'  => $leftId,
      'GatewayRightR'  => $rightR,
      'GatewayRightID' => $rightId,
      'ContextLeftR'   => $cLeftR,
      'ContextLeftID'  => $cLeftId,
      'ContextRightR'  => $cRightR,
      'ContextRightID' => $cRightId,

    ];
    $client = new Client();
    $urlparts = parse_url($request->url());
    $domain = 'simplia.com'; // $urlparts ['host'];
    $siteUrl = 'https://' . $domain . '/LivingScript_0070/Authenticate';
    // $client->setDefaultOption('verify', false);
    $response = $client->request('POST', $siteUrl, ['verify' => false, 'json' => $arr]);

    $resp_code = $response->getStatusCode();
    $body = $response->getBody();
    $a = json_decode($body, true);
    if ($resp_code == '200' && isset($a['Attribution'])) {
      $aid = $a['Attribution'][0]['ID'];
      $user = $this->login($aid);
      if ($user) {
        // Redirect to landing page if is user
        $company = $user->withoutEvents(function () use ($user) {
          return $user->companies()->enabled()->first();
        });
        $url = route($user->landing_page, ['company_id' => $company->id]);
        $redirect = redirect()->intended($url)->getTargetUrl();
        return response()->redirectTo($redirect);
      }
    }
    return response()->json(['error' => 'Not authorized.'], 403);
  }
  public function authenticateSimple(Req $request)
  {
    $aid = $request['aid'];

    if ($aid) {
      $response = $this->login($aid);
      if ($response) {
        return response()->redirectTo('/');
      }
    }
    return response()->json(['error' => 'Not authorized.'], 403);
  }
  private function login($aid)
  {
    $userOb = User::where('attribute_id', $aid)->first();
    if (!$userOb) {
      return false;
    }
    if (!auth()->loginUsingId($userOb->id, true)) {
      return false;
    }

    // Get user object
    $user = user();

    // Check if user is enabled
    if (!$user->enabled) {
      $this->logout();

      return false;
    }
    session(['dashboard_id' => $user->dashboards()->enabled()->pluck('id')->first()]);

    return $user;
  }
}
