<?php


namespace App\Http\Sw\PurchasingProcess\Responses;
/**
 * @OA\Schema(
 *     title="createPurchasingProcess",
 *     description="createPurchasingProcess API",
 *     @OA\Xml(
 *         name="createPurchasingProcess"
 *     )
 * )
 */

interface createPurchasingProcess
{
    /**
     *                      @OA\Property(
     *                           title="procurement_plan_id",
     *                            property="procurement_plan_id",
     *                           description="procurement_plan_id",
     *                           example=16
     *                      ),
     *

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
     *                     @OA\Property(
     *                        property="users",
     *                        title="users",
     *                        @OA\Items(
     *                              @OA\Property(
     *                                   title="user_id",
     *                                    property="user_id",
     *                                   description="user_id",
     *                                   example=16
     *                              ),
     *                        )
     *                    )
     */

    public  function procurement_plan_id();
    public  function organisation_id();
    public  function count();
    public  function code();
    public  function address();
    public  function other_requirements();
    public  function is_all_participants();
    public  function is_full_decide();
}
