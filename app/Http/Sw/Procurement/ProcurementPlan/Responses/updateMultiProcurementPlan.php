<?php
/**
 * @OA\Schema(
 *     title="updateMultiProcurementPlan",
 *     description="updateMultiProcurementPlan API",
 *     @OA\Xml(
 *         name="updateMultiProcurementPlan"
 *     )
 * )
 */
interface updateMultiProcurementPlan
{

        /**

        *
        *             @OA\Property(
        *                property="procurement",
        *                title="procurement",
        *                @OA\Items(
        *                      @OA\Property(
        *                           title="id",
        *                            property="id",
        *                           description="id",
        *                           example=123
        *                      ),
        *                      @OA\Property(
        *                           title="cpv_id",
        *                            property="cpv_id",
        *                           description="cpv_id",
        *                           example=16
        *                      ),
        *                      @OA\Property(
        *                           title="cpv_drop",
        *                           property="cpv_drop",
        *                           description="cpv_drop",
        *                           example=1
        *                      ),
        *                     @OA\Property(
        *                           title="cpv_type",
        *                           property="cpv_type",
        *                           description="cpv_type",
        *                           enum={1,2,3},
        *                           example=1
        *                      ),
        *                      @OA\Property(
        *                           title="unit",
        *                           property="unit",
        *                           description="unit",
        *                           example="kg"
        *                      ),
        *                      @OA\Property(
        *                           title="specifications_id",
        *                           property="specifications_id",
        *                           description="specifications_id",
        *                           example=1
        *                      ),
        *
        *                      @OA\Property(
        *                           title="count",
        *                           property="count",
        *                           description="count",
        *                           example=1
        *                      ),
        *
        *                      @OA\Property(
        *                           title="unit_amount",
        *                           property="unit_amount",
        *                           description="unit_amount",
        *                           example=1
        *                      ),
        *
        *                      @OA\Property(
        *                           title="type",
        *                           property="type",
        *                           description="type",
        *                           example=1
        *                      ),
        *                      @OA\Property(
        *                           title="classifier_id",
        *                           property="classifier_id",
        *                           description="classifier_id",
        *                           example=1
        *                      ),
        *                      @OA\Property(
        *                           title="user_id",
        *                           property="user_id",
        *                           description="user_id",
        *                           example=1
        *                      ),
        *
        *                      @OA\Property(
        *                           title="date",
        *                           property="date",
        *                           description="date",
        *                           example="2020-12-12"
        *                      ),
        *
        *                      @OA\Property(
        *                           title="is_condition",
        *                           property="is_condition",
        *                           description="is_condition",
        *                           example=1
        *                      ),
        *
        *                )
        *        )
        *
        *
        */

        public function procurement();
        public function id();
        public function cpv_id();
        public function cpv_drop();
        public function unit();
        public function specifications_id();
        public function count();
        public function unit_amount();
        public function type();
        public function classifier_id();
        public function user_id();
        public function date();
        public function is_condition();

}
