<?php

namespace Nsls\Moodle\Rest;

use Nsls\Moodle\Exception\MoodleClientException;
use Nsls\Moodle\Http\Response;

class Groups extends Rest
{
    /**
     * Get the groups in a Course.
     *
     * @param int $course
     *
     * @throws MoodleClientException
     *
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
     *
     * @return Response|null
     * @throws MoodleClientException
     */
    public function addUsersToGroup(array $users): ?Response
    {
        return $this->client->request('core_group_add_group_members', ['members' => $users]);
    }

}