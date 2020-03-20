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

}