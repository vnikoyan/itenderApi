<?php

interface ProcurementSw
{


      /**
      *      @OA\Get(
      *          path="/procurement",
      *          tags={"Procurement"},
      *          summary="App\Http\Controllers\Api\Procurement\ProcurementController@index",
      *          operationId="procurementIndex",
      *     security={
      *         {"bearerAuth": {}}
      *     },
      *               @OA\Response(
      *                 response=200,
      *                 description="Success",
      *                 @OA\MediaType(
      *                 mediaType="application/json",
      *               )
      *             ),
      *          )
      */

    public function procurement();

    /**
      *      @OA\Post(
      *          path="/procurement",
      *          tags={"Procurement"},
      *      @OA\RequestBody(
      *          required=true,
      *          @OA\JsonContent(ref="#/components/schemas/createProcurement")
      *      ),
      *     security={
      *         {"bearerAuth": {}}
      *     },
      *              summary="App\Http\Controllers\Api\Auth\ProcurementController@store",
      *              operationId="signup",
      *               @OA\Response(
      *                   response=200,
      *                   description="Success",
      *                   @OA\MediaType(
      *                      mediaType="application/json",
      *                  )
      *             ),
      *          )
    */
    public function createUser();

    /**
     *      @OA\Put(
     *          path="/procurement/approve/{procurement_id}",
     *          tags={"Procurement"},
     *          summary="App\Http\Controllers\Api\Procurement\ProcurementPlanController@edit",
     *          operationId="EditApprove",
     *              @OA\Parameter(
     *                  name="procurement_id",
     *                  in="path",
     *                  @OA\Schema(
     *                      type="integer"
     *                 )
     *               ),
     *          security={
     *           {"bearerAuth": {}}
     *          },
     *           @OA\Response(
     *             response=200,
     *             description="Success",
     *             @OA\MediaType(
     *             mediaType="application/json",
     *             )
     *           ),

     *          )
     */
    public function getUserByUserId();


}
