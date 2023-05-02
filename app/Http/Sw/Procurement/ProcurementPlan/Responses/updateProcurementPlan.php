<?php
/**
 * @OA\Schema(
 *     title="updateProcurementPlan",
 *     description="updateProcurementPlan API",
 *     @OA\Xml(
 *         name="updateProcurementPlan"
 *     )
 * )
 */
interface updateProcurementPlan
{
        /**
        *                      @OA\Property(
        *                           title="cpv_type",
        *                           property="cpv_type",
        *                           description="cpv_type",
        *                           enum={1,2,3},
        *                           example=1
        *                      ),
        *                      @OA\Property(
        *                           title="specifications_id",
        *                           property="specifications_id",
        *                           description="specifications_id",
        *                           example="1"
        *                      ),
         *                     @OA\Property(
         *                          title="unit",
         *                          property="unit",
         *                          description="unit",
         *                          example="1"
         *                     ),
        *                      @OA\Property(
        *                           title="user_id",
        *                           property="user_id",
        *                           description="user_id",
        *                           example="1"
        *                      ),
        *                      @OA\Property(
        *                           title="is_condition",
        *                           property="is_condition",
        *                           description="is_condition",
        *                           example="1"
        *                      ),
        *        )
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
