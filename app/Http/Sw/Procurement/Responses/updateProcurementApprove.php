<?php


namespace App\Http\Sw\Procurement\Responses;

/**
 * @OA\Schema(
 *     title="updateProcurementApprove",
 *     description="updateProcurementApprove API",
 *     @OA\Xml(
 *         name="updateProcurementApprove"
 *     )
 * )
 */
interface updateProcurementApprove
{

    /**
     * @OA\Property(
     *      title="status",
     *      description="status",
     *      enum = {0,1},
     *      example=1
     * )
     *
     * @var integer
     */
    public $status;

}
