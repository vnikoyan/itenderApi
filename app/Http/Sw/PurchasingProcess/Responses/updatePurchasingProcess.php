<?php


namespace App\Http\Sw\PurchasingProcess\Responses;

/**
 * @OA\Schema(
 *     title="updatePurchasingProcess",
 *     description="updatePurchasingProcess API",
 *     @OA\Xml(
 *         name="updatePurchasingProcess"
 *     )
 * )
 */
interface updatePurchasingProcess
{
    /**
     *                      @OA\Property(
     *                           title="count",
     *                           property="count",
     *                           description="count",
     *                           example=16
     *                      ),
     *                     @OA\Property(
     *                           title="code",
     *                           property="code",
     *                           description="code",
     *                           example="AA-11212"
     *                      ),
     *                     @OA\Property(
     *                           title="address",
     *                           property="address",
     *                           description="address",
     *                           example="address"
     *                      ),
     *                     @OA\Property(
     *                           title="other_requirements",
     *                           property="other_requirements",
     *                           description="other_requirements",
     *                           example="Other requirements"
     *                      ),
     *                     @OA\Property(
     *                           title="deadline",
     *                           property="deadline",
     *                           description="deadline",
     *                           example="2020-12-12"
     *                      ),
     *                     @OA\Property(
     *                           title="is_full_decide",
     *                           property="is_full_decide",
     *                           description="is_full_decide",
     *                           enum={0,1},
     *                           example=1
     *                      ),
     *                     @OA\Property(
     *                           title="is_all_participants",
     *                           property="is_all_participants",
     *                           description="is_all_participants",
     *                           enum={0,1},
     *                           example=1
     *                      ),
     */

    public  function organisation_id();
    public  function count();
    public  function code();
    public  function address();
    public  function other_requirements();
    public  function is_all_participants();
    public  function is_full_decide();
}
