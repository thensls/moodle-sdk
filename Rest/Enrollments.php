<?php

namespace Nsls\Moodle\Rest;

use Nsls\Moodle\Exception\MoodleClientException;
use Nsls\Moodle\Http\Response;

class Enrollments extends Rest
{

    /**
     * Enroll a user in a course.
     *
     * @param int $userId
     *   User ID.
     * @param int $courseId
     *   Course ID.
     * @param int $roleid
     *   Role ID.
     * @return Response|null
     * @throws MoodleClientException
     */
    public function enrollUserInCourse(int $userId, int $courseId, int $roleid): ?Response
    {
        return $this->client->request('enrol_manual_enrol_users', [
            'userid' => $userId,
            'courseid' => $courseId,
            'roleid' => $roleid,
            'timestart' => REQUEST_TIME,
        ]);
    }

    /**
     * Unenroll users from a course.
     *
     * @param array $unenrollments
     *   Unenrollments.
     * @return Response|null
     * @throws MoodleClientException
     */
    public function unenrollUsersFromCourse(array $unenrollments): ?Response
    {
        return $this->client->request('enrol_manual_unenrol_users', ['enrolments' => $unenrollments]);
    }

    /**
     * Get enrolled users in a course.
     *
     * @param int $courseId
     *   Course ID.
     * @return Response|null
     * @throws MoodleClientException
     */
    public function getEnrolledUsersInCourse(int $courseId): ?Response
    {
        return $this->client->request('core_enrol_get_enrolled_users', ['courseid' => $courseId]);
    }

    /**
     * Get enrolled courses for a user.
     *
     * @param int $userId
     *   User ID.
     * @param bool $returnUserCount
     *   Return the user count.
     * @return Response|null
     * @throws MoodleClientException
     */
    public function getEnrolledCoursesForUser(int $userId, $returnUserCount = FALSE): ?Response
    {
        return $this->client->request('core_enrol_get_users_courses', ['userid' => $userId, 'returnusercount' => $returnUserCount]);
    }

}