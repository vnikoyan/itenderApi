<?php


namespace App\Http\Sw\Settings\Event;


interface EventSw
{


    /**
     *      @OA\Get(
     *          path="/event",
     *          tags={"Settings"},
     *              summary="App\Http\Controllers\Api\Settings\EventController@index",
     *              operationId="eventList",
     *               @OA\Response(
     *                   response=200,
     *                   description="Success",
     *                   @OA\MediaType(
     *                      mediaType="application/json",
     *                  )
     *             ),
     *          )
     */
    public function getList();

    /**
     *      @OA\Get(
     *          path="/event/{id}",
     *          tags={"Settings"},
     *              summary="App\Http\Controllers\Api\Settings\EventController@show",
     *              operationId="eventBYid",
     *              @OA\Parameter(
     *                  name="id",
     *                  in="path",
     *                  @OA\Schema(
     *                      type="integer"
     *                 )
     *               ),
     *               @OA\Response(
     *                   response=200,
     *                   description="Success",
     *                   @OA\MediaType(
     *                      mediaType="application/json",
     *                  )
     *             ),
     *          )
     */
    public function getById();



}
