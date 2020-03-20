<?php

namespace Nsls\Moodle\Rest;

use Nsls\Moodle\Exception\MoodleClientException;
use Nsls\Moodle\Http\Response;

class Courses extends Rest
{

    /**
     * Get all Courses.
     *
     * @return Response|null
     *
     * @throws MoodleClientException
     */
    public function all(): ?Response
    {
        return $this->client->request('core_course_get_courses');
    }

}