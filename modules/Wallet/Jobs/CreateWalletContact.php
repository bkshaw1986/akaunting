<?php

namespace Modules\Wallet\Jobs;

use App\Abstracts\Job;
use App\Models\Common\Contact;
use App\Models\Auth\User;
use App\Jobs\Common\CreateContact;

class CreateWalletContact extends Job
{
  protected $type;
  protected $props;
  protected $aid;
  protected $cid;

  public function __construct($type, $cid, $aid, $props)
    {
      $this->type = $type;
      $this->cid = $cid;
      $this->aid = $aid;
      $this->props = $props;
    }

    public function handle()
    {
      $c = Contact::where('attribute_id', $this->aid)->first();
      if ($c) {
        return $c;
      }
      $user = User::where('attribute_id', $this->aid)->first();
      if (is_null($user))
      {
        $user = new class {};
        $user->name = isset($this->props['name']) ? $this->props['name'] : $this->props['email'];
        $user->email = $this->props['email'];
        $user->attribute_id = $this->props['attribute_id'];
        $user->id = null;
      }
      $contact = [
        'company_id' => $this->cid,
        'name' => $user->name,
        'email' => $user->email,
        'tax_number' => '',
        'phone' => '',
        'website' => '',
        'reference' => '',
        'enabled' => 1,
        'create_user' => false,
        'type' => $this->type,
        'currency_code' => 'USD',
        'user_id' => $user->id,
        'attribute_id' => $user->attribute_id
      ];
      $c = $this->dispatch(new CreateContact($contact));
      return $c;
    }
}