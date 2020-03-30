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
$courses = $moodle->courses()->all();

// Get the full repsonse as an array
print $courses->toArray();

// Or original Json
print $courses->getContent();

// Or get the actual response and use any symfony/httpclient method
// https://symfony.com/doc/current/components/http_client.html#processing-responses
$response = $courses->getResponse();
```

*Note:* You can prevent any error handling on responses by passing false parameter
```php
print $groups->toArray(false);
print $groups->getContent(false);
```

#### Using an implemented Rest query endpoint:
A special group of endpoints are they query endpoints. These are part of the custom Moodle plugin "local_nslsqt", and allow for SQL SELECT queries.
```php
// Get the first 10 enrolled users in a course.
$sql = "SELECT DISTINCT u.id, u.username, u.firstname, u.lastname
  FROM {user} u
  JOIN {user_enrolments} ue ON (ue.userid = u.id)
  JOIN {enrol} e ON (e.id = ue.enrolid AND e.courseid = :courseid)";

$params = [
  [
      'param' => 'courseid',
      'value' => 4,
  ]
];
$query = $client->query()->getRecords($sql, $params, 0, 10);
print $query->toArray();
```
*Note:* These are read-only queries, for UPDATE and DELETE statements please use the standard REST endpoints. 

#### Using an non-implemented Rest endpoint

```php
$client = $moodle->getClient();
$request = $client->request('core_group_get_course_groups', ['courseid' => 4]);
print $request->toArray();
```


