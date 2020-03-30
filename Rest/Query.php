<?php


namespace Nsls\Moodle\Rest;


use Nsls\Moodle\Exception\MoodleClientException;
use Nsls\Moodle\Http\Response;

class Query extends Rest
{

    /**
     * @param string $sql
     * @param array $parameters
     * @param int $limitFrom
     * @param int $limitNumber
     * @return Response|null
     * @throws MoodleClientException
     */
    public function getRecords(string $sql, array $parameters = [], int $limitFrom = 0, $limitNumber = 0): ?Response
    {
        return $this->client->request('nsls_queries_get_records_sql', ['sql' => $sql, 'params' => $parameters, 'limitfrom' => $limitFrom, 'limitnum' => $limitNumber]);
    }

}