<?php


namespace App\Http\Sw\Organize;


interface OrganizeSwPercent
{

    /**
     *      @OA\Get(
     *           path="/organize-row-percent/{id}",
     *           tags={"OrganizeSwPercent"},
     *           summary="App\Http\Controllers\Api\Organize\OrganizeController@show",
     *           operationId="OrganizePercentByid",
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
    public function OrganizePercentByid();


    /**
     *      @OA\Get(
     *           path="/organize-row-percent/byOrganize/{organize_row_id}",
     *           tags={"OrganizeSwPercent"},
     *           summary="App\Http\Controllers\Api\Organize\OrganizeRowPercentController@organizeRow",
     *           operationId="OrganizePercentByOrganize",
     *           @OA\Parameter(
     *              name="organize_row_id",
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
    public function OrganizePercentByOrganize();



    /**
     *      @OA\Post(
     *          path="/organize-row-percent",
     *          tags={"OrganizeSwPercent"},
     *          @OA\RequestBody(
     *              required=true,
     *              @OA\JsonContent(ref="#/components/schemas/CreateOrganizeRowPercent")
     *          ),
     *          security={
     *              {"bearerAuth": {}}
     *          },
     *         summary="App\Http\Controllers\Api\Organize\OrganizeRowPercentController@store",
     *         operationId="OrganizeStore",
     *          @OA\Response(
     *              response=200,
     *              description="Success",
     *              @OA\MediaType(
     *                 mediaType="application/json",
     *             )
     *        ),
     *      )
     */
    public function OrganizeStore();



    /**
     *      @OA\Put(
     *          path="/organize-row-percent/{id}",
     *          tags={"OrganizeSwPercent"},
     *          @OA\Parameter(
     *                  name="id",
     *                  in="path",
     *                  @OA\Schema(
     *                      type="integer"
     *              )
     *          ),
     *          @OA\RequestBody(
     *              required=true,
     *              @OA\JsonContent(ref="#/components/schemas/OrganizePercentUpdate")
     *          ),
     *          security={
     *              {"bearerAuth": {}}
     *          },
     *         summary="App\Http\Controllers\Api\Organize\OrganizeRowPercentController@update",
     *         operationId="OrganizePercentUpdate",
     *          @OA\Response(
     *              response=200,
     *              description="Success",
     *              @OA\MediaType(
     *                 mediaType="application/json",
     *             )
     *        ),
     *      )
     */
     public function OrganizePercentUpdate();

}

