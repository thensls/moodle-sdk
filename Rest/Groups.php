<?php

namespace Nsls\Moodle\Rest;

use Nsls\Moodle\Exception\MoodleClientException;
use Nsls\Moodle\Http\Response;

class Groups extends Rest
{

    /**
     * Create single group.
     *
     * @param array $group
     *   Group.
     *
     * @return Response|null
     * @throws MoodleClientException
     */
    public function create(array $group): ?Response
    {
        return $this->createMultiple([$group]);
    }

    /**
     * Create groups.
     *
     * @param array $groups
     *   Groups.
     *
     * @return Response|null
     * @throws MoodleClientException
     */
    public function createMultiple(array $groups): ?Response
    {
        return $this->client->request('core_group_create_groups', ['groups' => $groups]);
    }

    /**
     * Get the groups in a Course.
     *
     * @param int $course
     *   Course ID.
     *
     * @throws MoodleClientException
     * @return Response|null
     */
    public function getGroupsInCourse(int $course): ?Response
    {
        return $this->client->request('core_group_get_course_groups', ['courseid' => $course]);
    }

    /**
     * Get group for a user in a course.
     *
     * @param int $userId
     *   User ID.
     * @param int $courseId
     *   Course ID.
     *
     * @return Response|null
     * @throws MoodleClientException
     */
    public function getGroupForUserInCourse(int $userId, int $courseId): ?Response
    {
        return $this->client->request('core_group_get_course_user_groups', ['userid' => $userId, 'courseid' => $courseId]);
    }

    /**
     * Add users to a group.
     *
     * @param array $users
     *   Users.
     *
     * @return Response|null
     * @throws MoodleClientException
     */
    public function addUsersToGroup(array $users): ?Response
    {
        return $this->client->request('core_group_add_group_members', ['members' => $users]);
    }

    /**
     * Removes users from a group.
     *
     * @param array $users
     *   Users.
     *
     * @return Response|null
     * @throws MoodleClientException
     */
    public function removeUsersFromGroup(array $users): ?Response
    {
        return $this->client->request('core_group_delete_group_members', ['members' => $users]);
    }
}