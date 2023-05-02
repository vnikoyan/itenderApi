<?php


namespace App\Http\Sw\Participant;


interface ParticipantRowSw
{
    /**
     *      @OA\Get(
     *          path="/participant-row/{group_id}",
     *          tags={"ParticipantRow"},
     *          summary="App\Http\Controllers\Api\Participant\ParticipantRowController@show",
     *          operationId="participantRowShow",
     *          security={
     *              {"bearerAuth": {}}
     *          },
     *          @OA\Parameter(
     *                name="group_id",
     *                in="path",
     *                @OA\Schema(
     *                    type="integer"
     *               )
     *             ),
     *             @OA\Response(
     *               response=200,
     *               description="Success",
     *               @OA\MediaType(
     *               mediaType="application/json",
     *             )
     *           ),
     *        )
     */

    public function participantRowShow();

    /**
     *      @OA\Get(
     *          path="/participant-row/getByOrganizeRowId/{organize_row_id}",
     *          tags={"ParticipantRow"},
     *          summary="App\Http\Controllers\Api\Participant\ParticipantRowController@getByOrganizeRowId",
     *          operationId="getByOrganizeRowId",
     *          security={
     *              {"bearerAuth": {}}
     *          },
     *          @OA\Parameter(
     *                name="organize_row_id",
     *                in="path",
     *                @OA\Schema(
     *                    type="integer"
     *               )
     *             ),
     *             @OA\Response(
     *               response=200,
     *               description="Success",
     *               @OA\MediaType(
     *               mediaType="application/json",
     *             )
     *           ),
     *        )
     */

    public function getByOrganizeRowId();


    /**
     *      @OA\Post(
     *          path="/participant-row",
     *          tags={"ParticipantRow"},
     *           @OA\RequestBody(
     *               required=true,
     *               @OA\JsonContent(ref="#/components/schemas/createParticipantRow")
     *           ),
     *          security={
     *              {"bearerAuth": {}}
     *          },
     *          summary="App\Http\Controllers\Api\Participant\ParticipantRowController@store",
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
     *      @OA\Put(
     *          path="/participant-row/{participant_id}",
     *          tags={"ParticipantRow"},
     *           @OA\RequestBody(
     *               required=true,
     *               @OA\JsonContent(ref="#/components/schemas/updateParticipantRow")
     *           ),
     *            @OA\Parameter(
     *                  name="participant_id",
     *                  in="path",
     *                  @OA\Schema(
     *                      type="integer"
     *                 )
     *               ),
     *          security={
     *              {"bearerAuth": {}}
     *          },
     *          summary="App\Http\Controllers\Api\Participant\ParticipantRowController@update",
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
    public function updateParticipant();

    /**
     *      @OA\Delete(
     *          path="/participant-row/{id}",
     *          tags={"ParticipantRow"},
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
     *         summary="App\Http\Controllers\Api\Participant\ParticipantRowController@destroy",
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
     *      @OA\Get(
     *          path="/participant-row/histories/{row_id}",
     *          tags={"ParticipantRow"},
     *          summary="App\Http\Controllers\Api\Procurement\ParticipantRowController@getHistories",
     *          operationId="getHistories",
     *              @OA\Parameter(
     *                  name="row_id",
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

    public function getHistories();
}
