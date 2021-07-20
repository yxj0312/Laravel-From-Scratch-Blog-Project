<?php

namespace App\Services;

use MailchimpMarketing\ApiClient;

class Newsletter
{
    public function subscribe(string $email)
    {
        $mailchimp = new ApiClient();

        $mailchimp->setConfig([
            'apiKey' => config('services.mailchimp.key'),
            'server' => 'us6'
        ]);

        return $mailchimp->lists->addListMember('1841442fdf', [
            'email_address' => $email,
            'status' => 'subscribed'
        ]);
    }
}