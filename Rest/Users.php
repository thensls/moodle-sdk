<?php

namespace Nsls\Moodle\Rest;

use Nsls\Moodle\Exception\MoodleClientException;
use Nsls\Moodle\Http\Response;

class Users extends Rest
{

    /**
     * Get users by specific field.
     *
     * @param string $field
     *   Field name.
     * @param array $values
     *   Values for the users.
     *
     * @return Response|null
     *
     *
     * @throws MoodleClientException
     */
    public function getMultipleByField($field, array $values): ?Response
    {
        return $this->client->request('core_user_get_users_by_field', ['field' => $field, 'values' => $values]);
    }

    /**
     * Create multiple users.
     *
     * @param array $users
     *   Users.
     * @return Response|null
     * @throws MoodleClientException
     */
    public function createMultiple(array $users): ?Response
    {
        return $this->client->request('core_user_create_users', ['users' => $users]);
    }

    /**
     * Update users.
     *
     * @param array $users
     *   Users.
     * @return Response|null
     * @throws MoodleClientException
     */
    public function updateMultiple(array $users): ?Response
    {
        return $this->client->request('core_user_update_users', ['users' => $users]);
    }

    /**
     * Delete users.
     *
     * @param array $userIds
     *   Users.
     * @return Response|null
     * @throws MoodleClientException
     */
    public function deleteMultiple($userIds): ?Response
    {
        return $this->client->request('core_user_delete_users', ['userids' => $userIds]);
    }

}