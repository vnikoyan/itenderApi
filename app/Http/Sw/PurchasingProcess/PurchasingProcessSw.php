<?php


namespace App\Http\Sw\PurchasingProcess;


interface PurchasingProcessSw
{

    /**
     *      @OA\Get(
     *          path="/purchasing-process/{purchasing_process_Id}",
     *          tags={"PurchasingProcess"},
     *          summary="App\Http\Controllers\Api\PurchasingProcess\PurchasingProcessController@show",
     *          operationId="ProcurementPlanshow",
     *              @OA\Parameter(
     *                  name="purchasing_process_Id",
     *                  in="path",
     *                  @OA\Schema(
     *                      type="integer"
     *                 )
     *               ),
     *              security={
     *                  {"bearerAuth": {}}
     *              },
     *               @OA\Response(
     *                 response=200,
     *                 description="Success",
     *                 @OA\MediaType(
     *                 mediaType="application/json",
     *               )
     *             ),
     *          )
     */

    public function purchasing();


    /**
     *      @OA\Get(
     *          path="/purchasing-process/getByOrganisationId/{organisation_id}",
     *          tags={"PurchasingProcess"},
     *          summary="App\Http\Controllers\Api\PurchasingProcess\PurchasingProcessController@showByOrganisation",
     *          operationId="getByOrganisationId",
     *              @OA\Parameter(
     *                  name="organisation_id",
     *                  in="path",
     *                  @OA\Schema(
     *                      type="integer"
     *                 )
     *               ),
     *              security={
     *                  {"bearerAuth": {}}
     *              },
     *               @OA\Response(
     *                 response=200,
     *                 description="Success",
     *                 @OA\MediaType(
     *                 mediaType="application/json",
     *               )
     *             ),
     *          )
     */

    public function getByOrganisationId();

    /**
     *      @OA\Post(
     *          path="/purchasing-process",
     *          tags={"PurchasingProcess"},
     *           @OA\RequestBody(
     *               required=true,
     *               @OA\JsonContent(ref="#/components/schemas/createPurchasingProcess")
     *           ),
     *          security={
     *              {"bearerAuth": {}}
     *          },
     *          summary="App\Http\Controllers\Api\PurchasingProcess\PurchasingProcessController@store",
     *          operationId="createParticipant",
     *           @OA\Response(
     *               response=200,
     *               description="Success",
     *               @OA\MediaType(
     *                  mediaType="application/json",
     *              )
     *         ),
     *      )
     */
    public function createParticipant();


    /**
     * @OA\Delete(
     *          path="/purchasing-process/{purchasing_process_Id}",
     *          tags={"PurchasingProcess"},
     *          @OA\Parameter(
     *                  name="purchasing_process_Id",
     *                  in="path",
     *                  @OA\Schema(
     *                      type="integer"
     *              )
     *          ),
     *          security={
     *              {"bearerAuth": {}}
     *          },
     *         summary="App\Http\Controllers\Api\PurchasingProcess\PurchasingProcessController@destroy",
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


    /**
     *      @OA\Post(
     *          path="/purchasing-process/user/{purchasing_process_Id}",
     *          tags={"PurchasingProcess"},
     *           @OA\RequestBody(
     *               required=true,
     *               @OA\JsonContent(ref="#/components/schemas/createUserPurchasingProcess")
     *           ),
     *            @OA\Parameter(
     *                  name="purchasing_process_Id",
     *                  in="path",
     *                  @OA\Schema(
     *                      type="integer"
     *                 )
     *               ),
     *          security={
     *              {"bearerAuth": {}}
     *          },
     *          summary="App\Http\Controllers\Api\PurchasingProcess\PurchasingProcessController@update",
     *          operationId="createUserPurchasingProcess",
     *           @OA\Response(
     *               response=200,
     *               description="Success",
     *               @OA\MediaType(
     *                  mediaType="application/json",
     *              )
     *           ),
     *         )
     */
    public function createUserPurchasingProcess();

    /**
     * @OA\Delete(
     *          path="/purchasing-process/user/{purchasing_process_Id}/{user_id}",
     *          tags={"PurchasingProcess"},
     *          @OA\Parameter(
     *                  name="purchasing_process_Id",
     *                  in="path",
     *                  @OA\Schema(
     *                      type="integer"
     *              )
     *          ),
     *          @OA\Parameter(
     *                  name="user_id",
     *                  in="path",
     *                  @OA\Schema(
     *                      type="integer"
     *              )
     *          ),
     *          security={
     *              {"bearerAuth": {}}
     *          },
     *         summary="App\Http\Controllers\Api\PurchasingProcess\PurchasingProcessController@deleteUser",
     *         operationId="deleteUserPurchasingProcess",
     *          @OA\Response(
     *              response=200,
     *              description="Success",
     *              @OA\MediaType(
     *                 mediaType="application/json",
     *             )
     *        ),
     *      )
     */
    public function deleteUserPurchasingProcess();

    /**
     *      @OA\Put(
     *          path="/purchasing-process/{purchasing_process_Id}",
     *          tags={"PurchasingProcess"},
     *           @OA\RequestBody(
     *               required=true,
     *               @OA\JsonContent(ref="#/components/schemas/updatePurchasingProcess")
     *           ),
     *            @OA\Parameter(
     *                  name="purchasing_process_Id",
     *                  in="path",
     *                  @OA\Schema(
     *                      type="integer"
     *                 )
     *               ),
     *          security={
     *              {"bearerAuth": {}}
     *          },
     *          summary="App\Http\Controllers\Api\PurchasingProcess\PurchasingProcessController@update",
     *          operationId="updateParticipant",
     *           @OA\Response(
     *               response=200,
     *               description="Success",
     *               @OA\MediaType(
     *                  mediaType="application/json",
     *              )
     *         ),
     *      )
     */
    public function updatePurchasingProcess();



}
