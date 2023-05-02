<?php


namespace App\Http\Sw\Participant;


interface ParticipantSw
{
    /**
     *      @OA\Get(
     *          path="/participant",
     *          tags={"Participant"},
     *          summary="App\Http\Controllers\Api\Participant\ParticipantController@index",
     *          operationId="participantShow",
     *          security={
     *              {"bearerAuth": {}}
     *          },
     *             @OA\Response(
     *               response=200,
     *               description="Success",
     *               @OA\MediaType(
     *               mediaType="application/json",
     *             )
     *           ),
     *        )
     */

    public function index();

    /**
     *      @OA\Get(
     *          path="/participant/suggestions",
     *          tags={"Participant"},
     *          summary="App\Http\Controllers\Api\Participant\ParticipantController@suggestions",
     *          operationId="participantShow",
     *          security={
     *              {"bearerAuth": {}}
     *          },
     *             @OA\Response(
     *               response=200,
     *               description="Success",
     *               @OA\MediaType(
     *               mediaType="application/json",
     *             )
     *           ),
     *        )
     */

    public function suggestions();
    /**
     *      @OA\Get(
     *          path="/participant/notSuggestions",
     *          tags={"Participant"},
     *          summary="App\Http\Controllers\Api\Participant\ParticipantController@notSuggestions",
     *          operationId="participantShow",
     *          security={
     *              {"bearerAuth": {}}
     *          },
     *             @OA\Response(
     *               response=200,
     *               description="Success",
     *               @OA\MediaType(
     *               mediaType="application/json",
     *             )
     *           ),
     *        )
     */

    public function notSuggestions();


    /**
     *      @OA\Get(
     *          path="/participant/{organize_id}",
     *          tags={"Participant"},
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
     *      @OA\Get(
     *          path="/participant/getByGroupId/{group_id}",
     *          tags={"Participant"},
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
     *      @OA\Post(
     *          path="/participant",
     *          tags={"Participant"},
     *           @OA\RequestBody(
     *               required=true,
     *               @OA\JsonContent(ref="#/components/schemas/createParticipant")
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
     *      @OA\Put(
     *          path="/participant/{participant_id}",
     *          tags={"Participant"},
     *           @OA\RequestBody(
     *               required=true,
     *               @OA\JsonContent(ref="#/components/schemas/updateParticipant")
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
     *      @OA\Delete(
     *          path="/participant/{id}",
     *          tags={"Participant"},
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
