<?php

namespace Modules\Wallet\Http\Controllers;

use App\Abstracts\Http\ApiController;
use Illuminate\Http\Request;
use App\Models\Auth\User;
use App\Models\Setting\Setting;
use Modules\Wallet\Jobs\CreateUser;
use App\Jobs\Auth\UpdateUser;
use App\Jobs\Common\CreateCompany;
use \GuzzleHttp\Client;

class Account extends ApiController
{
    public function createUser(Request $request)
    {
      $u = $request->all();
      $user = User::where('attribute_id', $u['ID'])->first();
      if ($user) {
        return $user;
      }
      $name = isset($u['name']) ? $u['name'] : (isset($u['given_name']) && isset($u['family_name']) ? $u['given_name'] . ' ' . $u['family_name'] : $u['GatewayRightDN']);
      $data = [
        'name'                  => $name,
        'email'                 => $u['email'],
        'password'              => 'demo',
        'password_confirmation' => 'demo',
        'roles'                 => [2],
        'enabled'               => 1,
        'locale'                => 'en-US',
        'landing_page'          => 'dashboard',
        'address'               => $u['address'],
        'currency'              => 'USD',
        'attribute_id'          => $u['ID']
  
      ];
      $company = $this->dispatch(new CreateCompany($data));
      $data['companies'] = [$company['id']];
      $user = $this->dispatch(new CreateUser($data));
      // Update Setting
      Setting::where('company_id', $company->id)->where('key', 'wizard.completed')->update([ 'value' => 1 ]);
      return $user;
      
    }

    public function updateUser(Request $request) {
      $u = $request->all();
      $user = User::where('attribute_id', $u['ID'])->first();
      if ($user) {
        $update = $this->dispatch(new UpdateUser($user, $u));
        return $update;
      }
      return false;
    }
    
    public function authenticate(Request $request)
    {
      $aid = $request['aid'];

      $leftR = $request['GatewayLeftR'];
      $leftId = $request['GatewayLeftID'];
      $rightR = $request['GatewayRightR'];
      $rightId = $request['GatewayRightID'];
      $cLeftR = $request['ContextLeftR'];
      $cLeftId = $request['ContextLeftID'];
      $cRightR = $request['ContextRightR'];
      $cRightId = $request['ContextRightID'];
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
      // $client = new Client();
      // $urlparts = parse_url($request->url());
      // $domain = 'oxygenaccountingapacheserver-0015.laxroute53.com';// $urlparts ['host'];
      // $siteUrl = 'https://'.$domain.'/LivingScript_0070/Authenticate';
      // // $client->setDefaultOption('verify', false);
      // $response = $client->request('POST', $siteUrl, ['verify' => false, 'json' => $arr]);

      // $resp_code = $response->getStatusCode();
      // $body = $response->getBody();
      // $a = json_decode($body, true);
      // if ($resp_code == '200' && isset($a['Attribution']))
      if ($aid)
      {
        // $aid = $a['Attribution'][0]['ID'];
        $response = $this->login($aid);
        if ($response) {
          return response()->redirectTo('/');
        } else {
          
          return response()->json(['error' => 'Not authorized.'],403);
        }
      }

      return response()->json(['error' => 'Not authorized.'],403);
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

      return true;
    }
}
