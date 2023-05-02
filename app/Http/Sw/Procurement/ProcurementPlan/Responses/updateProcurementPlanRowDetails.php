<?php


namespace App\Http\Sw\Procurement\ProcurementPlan\Responses;

/**
 * @OA\Schema(
 *     title="updateProcurementPlanRowDetails",
 *     description="updateProcurementPlanRowDetails API",
 *     @OA\Xml(
 *         name="updateProcurementPlanRowDetails"
 *     )
 * )
 */

interface updateProcurementPlanRowDetails
{
 /**

                *                           @OA\Property(
        *                               title="count",
        *                               property="count",
        *                               description="count",
        *                               example="1"
        *                             ),
        *                           @OA\Property(
        *                               title="unit_amount",
        *                               property="unit_amount",
        *                               description="unit_amount",
        *                               example="1"
        *                             ),
        *                           @OA\Property(
        *                               title="type",
        *                               property="type",
        *                               description="type",
        *                               example="1"
        *                             ),
        *                           @OA\Property(
        *                            title="date",
        *                            property="date",
        *                            description="date",
        *                            example="2020-12-12"
        *                           ),
        *                           @OA\Property(
        *                               title="classifier_id",
        *                               property="classifier_id",
        *                               description="classifier_id",
        *                               example="1"
        *                             ),
        *                           @OA\Property(
        *                               title="financial_classifier_id",
        *                               property="financial_classifier_id",
        *                               description="financial_classifier_id",
        *                               example="1"
        *                             ),
        *                           @OA\Property(
        *                               title="budget_type",
        *                               property="budget_type",
        *                               description="budget_type",
        *                               example="0"
        *                             ),
        *                       )
        *                    ),
         */

        public  function procurement_id();
        public  function procurement();
        public  function cpv_id();
        public  function cpv_drop();
        public  function specifications_id();
        public  function type();
        public  function classifier_id();
        public  function user_id();
        public  function date();
        public  function is_condition();
        public  function budget_type();

}