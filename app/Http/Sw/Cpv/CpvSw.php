<?php

interface UserSw
{

    /**
      *      @OA\Get(
      *          path="/cpv/{type}",
      *          tags={"Cpv"},
      *          summary="App\Http\Controllers\Api\Cpv\CpvController@index",
      *          operationId="getRootCpvByType",
      *              @OA\Parameter(
      *                  name="type",
      *                  description="1 => Ապրանքներ,2 =>Ծառայություններ,3=>Աշխատանքներ",
      *                  in="path",
      *                  @OA\Schema(
      *                      type="string"
      *                 )
      *             ),
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
      public function getCpvByTypeRoot();

      /**
        *      @OA\Get(
        *          path="/cpv/get_by_id/{id}",
        *          tags={"Cpv"},
        *          summary="App\Http\Controllers\Api\Cpv\CpvController@show",
        *          operationId="getCpvById",
      *              @OA\Parameter(
      *                  name="id",
      *                  in="path",
      *                  @OA\Schema(
      *                      type="string"
      *                 )
      *             ),
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
      public function CpvById();


    /**
      *      @OA\Get(
      *          path="/cpv/specifications/{id}",
      *          tags={"Cpv"},
      *          summary="App\Http\Controllers\Api\Cpv\CpvController@getSpecificationsByCpvId",
      *          operationId="getSpecificationsByCpvId",
      *              @OA\Parameter(
      *                  name="id",
      *                  description="Cpv Id ",
      *                  in="path",
      *                  @OA\Schema(
      *                      type="string"
      *                 )
      *             ),
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
      public function getSpecificationsByCpvId();

    /**
      *      @OA\Post(
      *          path="/cpv/specifications/{id}",
      *          tags={"Cpv"},
      *          summary="App\Http\Controllers\Api\Cpv\CpvController@setSpecificationsByCpvId",
      *          operationId="setSpecificationsByCpvId",
      *            @OA\RequestBody(
      *                required=true,
      *                @OA\JsonContent(ref="#/components/schemas/SetSpecificationsByCpvId")
      *            ),
      *           @OA\Parameter(
      *               name="id",
      *               description="Cpv Id ",
      *               in="path",
      *               @OA\Schema(
      *                   type="string"
      *              )
      *           ),
      *            security={
      *                {"bearerAuth": {}}
      *            },
      *               @OA\Response(
      *                 response=200,
      *                 description="Success",
      *                 @OA\MediaType(
      *                 mediaType="application/json",
      *               )
      *             ),
      *          )
      */
      public function setSpecificationsByCpvId();




    /**
     *      @OA\Get(
     *          path="/cpv/search",
     *          tags={"Cpv"},
     *          summary="App\Http\Controllers\Api\Cpv\CpvController@search",
     *          operationId="GetUserSearch",
     *              @OA\Parameter(
     *                  name="q",
     *                  in="query",
     *                  @OA\Schema(
     *                      type="string"
     *                 )
     *             ),
     *             security={
     *                 {"bearerAuth": {}}
     *             },
     *               @OA\Response(
     *                 response=200,
     *                 description="Success",
     *                 @OA\MediaType(
     *                 mediaType="application/json",
     *               )
     *             ),
     *          )
     */
    public function getCpvBySearch();


    /**
     *      @OA\Post(
     *          path="/cpv/getCpvByIds",
     *          tags={"Cpv"},
     *          summary="App\Http\Controllers\Api\Cpv\CpvController@index",
     *          operationId="getCpvByIds",
     *            @OA\RequestBody(
     *                required=true,
     *                @OA\JsonContent(ref="#/components/schemas/GetCpvByIds")
     *            ),
     *            security={
     *                {"bearerAuth": {}}
     *            },
     *               @OA\Response(
     *                 response=200,
     *                 description="Success",
     *                 @OA\MediaType(
     *                 mediaType="application/json",
     *               )
     *             ),
     *          )
     */
    public function getCpvByTypeRoot();

}
