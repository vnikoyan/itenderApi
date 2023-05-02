<?php


namespace App\Http\Sw\PurchasingProcess;


interface PurchasingProcessSwPercent
{

    /**
     *      @OA\Get(
     *           path="/purchasing-process-percent/{id}",
     *           tags={"PurchasingProcessPercent"},
     *           summary="App\Http\Controllers\Api\PurchasingProcess\PurchasingProcessPercentController@show",
     *           operationId="PurchasingProcessPercentByid",
     *           @OA\Parameter(
     *              name="id",
     *              in="path",
     *              @OA\Schema(
     *                  type="integer"
     *              )
     *           ),
     *           security={
     *              {"bearerAuth": {}}
     *           },
     *           @OA\Response(
     *             response=200,
     *             description="Success",
     *             @OA\MediaType(
     *             mediaType="application/json",
     *           )
     *         ),
     *      )
     */
    public function PurchasingProcessPercentByid();
    /**
     *      @OA\Get(
     *           path="/purchasing-process-percent/getByPurchasingProcessId/{purchasing_process_id}",
     *           tags={"PurchasingProcessPercent"},
     *           summary="App\Http\Controllers\Api\PurchasingProcess\PurchasingProcessPercentController@show",
     *           operationId="getByPurchasingProcessId",
     *           @OA\Parameter(
     *              name="purchasing_process_id",
     *              in="path",
     *              @OA\Schema(
     *                  type="integer"
     *              )
     *           ),
     *           security={
     *              {"bearerAuth": {}}
     *           },
     *           @OA\Response(
     *             response=200,
     *             description="Success",
     *             @OA\MediaType(
     *             mediaType="application/json",
     *           )
     *         ),
     *      )
     */
    public function getByPurchasingProcessId();



    /**
     *      @OA\Post(
     *          path="/purchasing-process-percent",
     *          tags={"PurchasingProcessPercent"},
     *          @OA\RequestBody(
     *              required=true,
     *              @OA\JsonContent(ref="#/components/schemas/CreatePurchasingProcessPercentPercent")
     *          ),
     *          security={
     *              {"bearerAuth": {}}
     *          },
     *         summary="App\Http\Controllers\Api\PurchasingProcess\PurchasingProcessPercentController@store",
     *         operationId="PurchasingProcessPercentstore",
     *          @OA\Response(
     *              response=200,
     *              description="Success",
     *              @OA\MediaType(
     *                 mediaType="application/json",
     *             )
     *        ),
     *      )
     */
    public function PurchasingProcessPercentstore();




    /**
     *      @OA\Put(
     *          path="/purchasing-process-percent/{id}",
     *          tags={"PurchasingProcessPercent"},
     *          @OA\Parameter(
     *                  name="id",
     *                  in="path",
     *                  @OA\Schema(
     *                      type="integer"
     *              )
     *          ),
     *          @OA\RequestBody(
     *              required=true,
     *              @OA\JsonContent(ref="#/components/schemas/CreatePurchasingProcessPercentPercent")
     *          ),
     *          security={
     *              {"bearerAuth": {}}
     *          },
     *         summary="App\Http\Controllers\Api\Organize\OrganizeRowPercentController@update",
     *         operationId="PurchasingProcessPercentUpdate",
     *          @OA\Response(
     *              response=200,
     *              description="Success",
     *              @OA\MediaType(
     *                 mediaType="application/json",
     *             )
     *        ),
     *      )
     */
    public function PurchasingProcessPercentUpdate();






}
