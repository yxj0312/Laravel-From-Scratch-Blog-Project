# Ep 61 Toy Chests and Contracts

This next episode is supplementary. It's slightly more advanced, and reviews service containers, providers, and contracts. Though I do my best to break it all down, please feel free to ask any questions you might have in the comments below. Otherwise, if you feel left behind by this episode, the truth is you can sneak by for a long time without fully understanding these concepts.

imaging services container is a toy chest, you can add things to and you can fetch things out.

```php
class NewsletterController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Newsletter $newsletter)
    {
        request()->validate(['email' => 'required|email']);

        try {
            $newsletter->subscribe(request('email'));
        } catch (Exception $e) {
            throw ValidationException::withMessages([
                'email' => 'This email could not be added to our newsletter list.'
            ]);
        }

        return redirect('/')->with('success', 'You are now signed up for our newsletter!');
    }
}
```

```php
class Newsletter
{
    public function subscribe(string $email, string $list = null)
    {
        $list ??= config('services.mailchimp.lists.subscribers');

        return $this->client()->lists->addListMember($list, [
            'email_address' => $email,
            'status' => 'subscribed'
        ]);
    }

    protected function client()
    {
        // you can also inject the config in the constructor
        return (new ApiClient())->setConfig([
            'apiKey' => config('services.mailchimp.key'),
            'server' => 'us6'
        ]);
    }
}
```

In the case of this Newsletter dependency:

1. Laravel starts by checking that toy chest(service container): do we have Newsletter?
2. If there's no Newsletter in that container, maybe we can just magically make one for it
3. Laravel checks Newletter class, and  there's no constructor dependency
