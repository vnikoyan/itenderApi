<?php

/**
 * @OA\Schema(
 *     title="CreateOrganizeRowArray",
 *     description="CreateOrganizeRowArray API",
 *     @OA\Xml(
 *         name="CreateOrganizeRowArray"
 *     )
 * )
 */

namespace App\Http\Sw\Organize\Responses;


interface CreateOrganizeRowArray
{


        /**
        *             @OA\Property(
        *                property="organize_row",
        *                title="organize_row",
        *                @OA\Items(
        *				   	@OA\Property(
        *				   	     title="procurement_plan_id",
        *				   	     property="procurement_plan_id",
        *				   	     description="procurement_plan_id",
        *				   	     type="integer",
        *				   	     example=1
        *				   	),
        *				   	@OA\Property(
        *				   	      title="plan_details_id",
        *				   	      property="plan_details_id",
        *				   	      description="plan_details_id",
        *				   	      type="integer",
        *				   	      example=1
        *				   	 ),
        *				   	@OA\Property(
        *				   	      title="organize_id",
        *				   	      property="organize_id",
        *				   	      description="organize_id",
        *				   	      type="integer",
        *				   	      example=1
        *				   	 ),
        *				   	@OA\Property(
        *				   	      title="count",
        *				   	      property="count",
        *				   	      description="count",
        *				   	      type="float",
        *				   	      example=20
        *				   	 ),
        *                )
        *             ),
     *
     */

}