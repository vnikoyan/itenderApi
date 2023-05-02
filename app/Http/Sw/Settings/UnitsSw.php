<?php

interface UserSw
{
          
      /**
        *      @OA\Get(
        *          path="/units",
        *          tags={"Settings"},
        *          summary="App\Http\Controllers\Api\Settings\UnitsController@index",
        *          operationId="getUnits",
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
      public function getUnits();


    
}