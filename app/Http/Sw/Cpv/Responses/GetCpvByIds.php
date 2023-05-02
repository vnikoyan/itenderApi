<?php


namespace App\Http\Sw\Cpv\Responses;

/**
 * @OA\Schema(
 *     title="GetCpvByIds Edit api",
 *     description="GetCpvByIds",
 *     @OA\Xml(
 *         name="GetCpvByIds"
 *     )
 * )
 */
interface GetCpvByIds
{
    /**
     * @OA\Property(
     *      title="ids",
     *      description="ids",
     *      example={1,2,4,3}
     *
     * )
     *
     * @var string
     */
    public $ids;
}
