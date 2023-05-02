<?php


namespace App\Http\Sw\User\Responses;

/**
 * @OA\Schema(
 *     title="createStateResponsibleUser Edit api",
 *     description="User Edit Api",
 *     @OA\Xml(
 *         name="createStateResponsibleUser"
 *     )
 * )
 */

interface createStateResponsibleUser
{

      /**
        *                @OA\Property(
        *                   property="name",
        *                   title="name",
        *                   description="name",
        *                   example={"hy":"test","ru":"test"}
        *                ),
        *                @OA\Property(
        *                   property="email",
        *                   title="email",
        *                   description="email",
        *                   example="test@test.com"
        *                ),
        *
        *             @OA\Property(
        *                property="members",
        *                title="members",
        *                @OA\Items(
        *                      @OA\Property(
        *                           title="name",
        *                            property="name",
        *                           description="name",
        *                           example={"hy":"test","ru":"test"}
        *                      ),
        *                      @OA\Property(
        *                           title="position",
        *                           property="position",
        *                           description="position",
        *                           example={"hy":"position","ru":"position"}
        *                      ),
        *
        *                )
        *        )
        *
        *
        */
        public  function name();
        public  function email();
        public  function position();

}