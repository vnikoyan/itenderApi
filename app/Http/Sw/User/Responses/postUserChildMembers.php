<?php


namespace App\Http\Sw\User\Responses;

/**
 * @OA\Schema(
 *     title="postUserChildMembers Edit api",
 *     description="User Edit Api",
 *     @OA\Xml(
 *         name="postUserChildMembers"
 *     )
 * )
 */


interface postUserChildMembers
{
      /**
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
        */
        public  function name();
        public  function position();

}