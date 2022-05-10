#  Archetype-dev PHP-SDK 


In this guide we're going to learn the installation and configuration of archetype SDK, and also share some examples on how to use Archetype SDK on your PHP project.

If you're using Laravel application, [follow the insturctions here](https://github.com/ArchetypeAPI/archetype-sdk-laravel-sample)
<br/>

# Installation & configuration

## Install Archetype SDK through composer
```bash 
 composer require archetype-dev/php-sdk 
```
<br/>

Then, in your main PHP file, put the below code to configure the SDK: 

```php
// init.php
<?php 

require_once 'vendor/autoload.php';
use Archetype\Archetype;

$options = [
    'app_id' => '699dd288309f4c9992cb9eeeeee',
    'secret_key' => 'archetype_sk_test_473d910da6074cbca68eeee4c6037',
];

Archetype::init($options);
```

That's it for the installation and configuration.
<br/>

## Using Archetype Authentication
You can use archetype authentication to authorize your users.
 
 Before you start using Archetype auth system, you need 3 steps:
 1. Register your user and store their api key. 
 2. Then Subscribe that user to a specific product/plan using Archetype::createCheckoutSession method as explained in the bottom.
 3. When user requesting authorized routes, the url must include `apikey`, either as a header or input or url query, for example ``your-domain.com/user-dashboard?apikey=user-api-key-you-got-from-registering-archetype-user``

 <br/>
 Now you can protect your application with our authentication by adding the Archetype::middleware method in anywhere you want to be authorized, just like the example shown below:
 
 
 ```php
 // profile.php
<?php 

require_once 'init.php';

use Archetype\Archetype;

header('Content-Type: application/json');
// you should call this end point with apkikey query param, or as a POST.
$response = Archetype::middleware();

// This is protected by archetype, if error happens, it will throw an error, you can catch the errors with try and catch blocks.
echo json_encode($response);
  
 ```
 
 ## Register your users
 Archetype provides a way to register new users and new API Keys via our SDK, see the below example: 
  ```php
  // registerUser.php
<?php 

require_once 'init.php';

use Archetype\Archetype;

header('Content-Type: application/json');

$response = Archetype::registerUser('234902394eerui3', 'Some Names', 'johDoes@domain.com');


echo json_encode($response);

  
 ```
 You can pass a custom uid which is a unique identifier for each user and optionally add details like their emails to register a new user. 
 You'll soon be able to add custom attributes and flags for each user. This will automatically create an API key.
 
 Below is a sample response after generating a new user

```json
{
 "apikeys": [
  "0d15b36c917a43f282d1a6e3b"
 ],
 "app_id": "699dd288309f4c9992cb9437eeeeeee",
 "attrs": [],
 "custom_uid": "abei9394aefff303e22eee",
 "deleted_at": null,
 "description": null,
 "email": "hello@archetype.dev",
 "first_seen": 1651591806.863881,
 "group": null,
 "is_new": true,
 "is_trial": false,
 "last_seen": 1651591806.863886,
 "live_mode": false,
 "name": "Archetype Team",
 "quota": 0,
 "renewal_number": 0,
 "sandbox_end": null,
 "status": "not_subscribed",
 "stripe_uid": "cus_LccvjqG11c",
 "subscription_date": null,
 "subscription_id": null,
 "tier_id": null,
 "trial_end": null,
 "uid": "626e7734ff19437783cb2919bceeeeeeeb"
}
```
The API Key will not be tied to a specific plan unless the user subscribes.

## Retrieve User

When the user is logged in and authorized whether via a session based token or however you think about auth, you can actually pass their custom_uid unique identifier to get their details like API keys, quota, usage and more. 
More details can be found in the Users page.

```php
 // user.php
 <?php 

require_once 'init.php';

use Archetype\Archetype;

header('Content-Type: application/json');

$response = Archetype::getUser('234902394eerui3');


echo json_encode($response);

  
 ```
 This returns a user JSON object

```json
{
 "apikeys": [
 "0d15b36c917a43f282d1a6e3bbeeeeeee"
 ],
 "app_id": "699dd288309f4c9992cb9437e39eeeee",
 "attrs": [],
 "custom_uid": "abei9394aefff303eeeeee",
 "email": "hello@archetype.dev",
 "first_seen": 1651591806.863881,
 "has_quota": false,
 "is_new": true,
 "is_trial": false,
 "last_seen": 1651591806.863886,
 "last_updated": 1651590222.462463,
 "live_mode": false,
 "renewal_number": 0,
 "start_time": 1651590222.462468,
 "status": "active",
 "stripe_app_id": "acct_1KtwzhGhb9fn7iOb",
 "stripe_subscription_id": "sub_1KvNggGhb9fe",
 "subscription_date": 1651748509.594179,
 "subscription_id": "sub_1KvNggGhb9fn7iObpeee",
 "tier_id": null,
 "uid": "626e7734ff19437783cb2919beeeeeeeee"
}
```

## Retrieve Available Products
This function returns all the products that you currently and publicly offer to your users. Pulling this list is how you can dynamically render prices.

```php
  // products.php
<?php 

require_once 'init.php';

use Archetype\Archetype;

header('Content-Type: application/json');

$response = Archetype::getProducts();


echo json_encode($response);

  
 ```
This returns a JSON object that is a list of products.

```javascript
[
  {
    app_id: 'YOUR_APP_ID',
    currency: 'usd',
    description: 'Basic tier',
    endpoints: [],
    has_full_access: true,
    has_quota: true,
    has_trial: true,
    is_active: true,
    is_free: false,
    is_new: true,
    name: 'Basic',
    period: 'month',
    price: 124,
    quota: 1000,
    tier_id: 'YOUR_TIER_ID',
    trial_length: 7,
    trial_time_frame: 'days'
  }
]
```

## Generate Checkout Sessions.

Once you get a product, you can pass the tier_id provided in the getproducts function and the user's custom_uid to generate a checkout session url.

What this does is create an ephemeral link to stripe that allows the user to enter their credit card details to purchase a subscription. This handles both creating and updating a checkout session.

The function returns a URL which you can then use to redirect a user to. In your API Settings Page on the Archetype side, you can set a return and redirect url after they've completed (or cancelled) the checkout process.

```php
 // createCheckoutUrl.php
<?php 

require_once 'init.php';

use Archetype\Archetype;

header('Content-Type: application/json');

$response = Archetype::createCheckoutSession('234902394eerui3', 'prod_LbP4oEy2HURprT');


echo json_encode($response);

  
 ```
 
 ## Cancel Products

We lastly provide an easy functionality for you to allow a user to cancel their subscription without any headache on your end.

```php
// cancelSubscription.php
<?php 

require_once 'init.php';

use Archetype\Archetype;

header('Content-Type: application/json');
// pass custom UID
$response = Archetype::cancelSubscription('234902394eerui3');


echo json_encode($response);

  ```
  
## Tracking without Middleware

If you want to track individual endpoints, you can simply add a track function to the call that'll asynchronously log the call without any further input.

You can optionally supply the user's API Key or their Custom uid that you provided to track events based on users.

```php 
// logUser.php
<?php 

require_once 'init.php';

use Archetype\Archetype;

header('Content-Type: application/json');
// pass user apikey you get from Archetype::getUser method
$response = Archetype::log('887b3af4b62a43e98a6932ad31e3c012');


echo json_encode($response);

```
<br/>

That's it. To learn more, head over to [Archetype.dev](https://docs.archetype.dev/docs).
