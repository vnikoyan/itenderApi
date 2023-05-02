<?php

namespace App\Http\Sw\Participant;


interface SelectedParticipantSw
{
    /**
     * @OA\Get(
     *          path="/selected-participants/{organize_id}",
     *          tags={"SelectedParticipant"},
     *          summary="App\Http\Controllers\Api\Participant\ParticipantController@show",
     *          operationId="participantShow",
     *          security={
     *              {"bearerAuth": {}}
     *          },
     *          @OA\Parameter(
     *                name="organize_id",
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

    public function participantShow();

    /**
     * @OA\Get(
     *          path="/selected-participants/getByGroupId/{group_id}",
     *          tags={"SelectedParticipant"},
     *          summary="App\Http\Controllers\Api\Participant\ParticipantController@getByGroupId",
     *          operationId="participantgetByGroupId",
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

    public function participantgetByGroupId();


    /**
     * @OA\Post(
     *          path="/selected-participants",
     *          tags={"SelectedParticipant"},
     *           @OA\RequestBody(
     *               required=true,
     *               @OA\JsonContent(ref="#/components/schemas/createSelectedParticipant")
     *           ),
     *          security={
     *              {"bearerAuth": {}}
     *          },
     *          summary="App\Http\Controllers\Api\Auth\ParticipantController@store",
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
     * @OA\Put(
     *          path="/selected-participants/{selected_participants_id}",
     *          tags={"SelectedParticipant"},
     *           @OA\RequestBody(
     *               required=true,
     *               @OA\JsonContent(ref="#/components/schemas/createSelectedParticipant")
     *           ),
     *            @OA\Parameter(
     *                  name="selected_participants_id",
     *                  in="path",
     *                  @OA\Schema(
     *                      type="integer"
     *                 )
     *               ),
     *          security={
     *              {"bearerAuth": {}}
     *          },
     *          summary="App\Http\Controllers\Api\Auth\ParticipantController@update",
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
     * @OA\Delete(
     *          path="/selected-participants/{id}",
     *          tags={"SelectedParticipant"},
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
     *         summary="App\Http\Controllers\Api\Auth\ParticipantController@destroy",
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
