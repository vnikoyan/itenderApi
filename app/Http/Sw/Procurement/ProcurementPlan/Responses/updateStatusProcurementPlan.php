<?php
/**
 * @OA\Schema(
 *     title="updateStatusProcurementPlan",
 *     description="updateStatusProcurementPlan API",
 *     @OA\Xml(
 *         name="updateStatusProcurementPlan"
 *     )
 * )
 */
interface updateStatusProcurementPlan
{
    
        
        /**
        *   @OA\Property(
        *        title="status",
        *         property="status",
        *        description="status",
        *        enum= {"1", "2"},
        *        example=0
        *   ),
        *   
        */
        
        public $status;

}