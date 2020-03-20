# Moodle SDK

## Setup

Make sure the environment has a valid ssh key to the NSLS github account.

**1. Add to composer.json**
```json
{
    "repositories": [
        {
            "type": "vcs",
            "url":  "git@github.com:thensls/moodle-sdk.git"
        }
    ]
}
```

**2a. composer**
```bash
composer require nsls/moodlesdk:dev-master 
```

**2b.**
Follow instructions to generate token on Github and paste in console 


## Quickstart

### Examples Using Factory

Initiate the Moodle SDK

```php
$moodle = Nsls\Moodle\Factory::instantiate('my_token', 'server_url');

// OR set the environment variables MOODLE_TOKEN and MOODLE_SERVER_URL 

$moodle = Nsls\Moodle\Factory::instantiate();
```

#### Using an implemented Rest endpoint:

```php
$groups = $moodle->courses()->all();

// Get the full repsonse as an array
print $groups->toArray();

// Or original Json
print $groups->getContent();

// Or get the actual response and use any symfony/httpclient method
// https://symfony.com/doc/current/components/http_client.html#processing-responses
$response = $groups->getResponse();
```

*Note:* You can prevent any error handling on responses by passing false parameter
```php
print $groups->toArray(false);
print $groups->getContent(false);
```

#### Using an non-implemented Rest endpoint

```php
$client = $moodle->getClient();
$request = $client->request('core_group_get_course_groups', ['courseid' => 4]);
print $request->toArray();
```


