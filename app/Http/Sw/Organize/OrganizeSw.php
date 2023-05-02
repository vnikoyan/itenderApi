<?php

interface OrganizeSw
{

      /**
      *      @OA\Get(
      *          path="/organize/{id}",
      *          tags={"Organize"},
      *          summary="App\Http\Controllers\Api\Organize\OrganizeController@show",
      *          operationId="OrganizeGetById",
      *          @OA\Parameter(
      *                  name="id",
      *                  in="path",
      *                  @OA\Schema(
      *                      type="integer"
      *              )
      *         ),
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

    public function OrganizeGetById();

    /**
     *      @OA\Get(
     *          path="/organize/getByUser",
     *          tags={"Organize"},
     *          summary="App\Http\Controllers\Api\Organize\OrganizeController@getByUser",
     *          operationId="OrganizegetByUser",
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

    public function OrganizeGetByUser();



    /**
     *      @OA\Get(
     *          path="/organize/getAllData/{id}",
     *          tags={"Organize"},
     *          summary="App\Http\Controllers\Api\Organize\OrganizeController@show",
     *          operationId="getAllData",
     *          @OA\Parameter(
     *                  name="id",
     *                  in="path",
     *                  @OA\Schema(
     *                      type="integer"
     *              )
     *         ),
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
    public function getAllData();

    /**
     *      @OA\Post(
     *          path="/organize",
     *          tags={"Organize"},
     *          @OA\RequestBody(
     *              required=true,
     *              @OA\JsonContent(ref="#/components/schemas/CreateOrganize")
     *          ),
     *          security={
     *              {"bearerAuth": {}}
     *          },
     *         summary="App\Http\Controllers\Api\Organize\OrganizeController@store",
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
     *          path="/organize/{id}",
     *          tags={"Organize"},
     *          @OA\Parameter(
     *                  name="id",
     *                  in="path",
     *                  @OA\Schema(
     *                      type="integer"
     *              )
     *          ),
     *          @OA\RequestBody(
     *              required=true,
     *              @OA\JsonContent(ref="#/components/schemas/OrganizeUpdate")
     *          ),
     *          security={
     *              {"bearerAuth": {}}
     *          },
     *         summary="App\Http\Controllers\Api\Organize\OrganizeController@update",
     *         operationId="OrganizeUpdate",
     *          @OA\Response(
     *              response=200,
     *              description="Success",
     *              @OA\MediaType(
     *                 mediaType="application/json",
     *             )
     *        ),
     *      )
     */
    public function OrganizeUpdate();


    /**
     *      @OA\Delete(
     *          path="/organize/{id}",
     *          tags={"Organize"},
     *          @OA\Parameter(
     *                  name="id",
     *                  in="path",
     *                  @OA\Schema(
     *                      type="integer"
     *              )
     *          ),
     *          security={
     *              {"bearerAuth": {}}
     *          },
     *         summary="App\Http\Controllers\Api\Organize\OrganizeController@destroy",
     *         operationId="OrganizeDelete",
     *          @OA\Response(
     *              response=200,
     *              description="Success",
     *              @OA\MediaType(
     *                 mediaType="application/json",
     *             )
     *        ),
     *      )
     */
    public function OrganizeDelete();


}
