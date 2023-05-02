<?php

interface ClassifierSw
{
          
      /**
        *      @OA\Get(
        *          path="/classifier",
        *          tags={"Settings"},
        *          summary="App\Http\Controllers\Api\Settings\ClassifierController@index",
        *          operationId="getClassifier",
        *          security={
        *              {"bearerAuth": {}}
        *          },
        *               @OA\Response(
        *                 response=200,
        *                 description="Success",
        *                 @OA\MediaType(
        *                 mediaType="application/json",
        *               )
        *             ),
        *          )
      */
      public function getClassifier();
          
      /**
        *      @OA\Get(
        *          path="/classifier/{cpv_id}",
        *          tags={"Settings"},
        *          summary="App\Http\Controllers\Api\Settings\ClassifierController@index",
        *          operationId="getClassifierByCpvId",
        *            security={
        *                {"bearerAuth": {}}
        *            },
        *              @OA\Parameter(
        *                  name="cpv_id",
        *                  in="path",
        *                  @OA\Schema(
        *                      type="integer"
        *                 )
        *             ),
        *               @OA\Response(
        *                 response=200,
        *                 description="Success",
        *                 @OA\MediaType(
        *                 mediaType="application/json",
        *               )
        *             ),
        *          )
      */
      public function getClassifierByCpvId();

   /**
        *      @OA\Get(
        *          path="/financialClassifier",
        *          tags={"Settings"},
        *          summary="App\Http\Controllers\Api\Settings\FinancialClassifier@index",
        *          operationId="FinancialClassifier",
        *          security={
        *              {"bearerAuth": {}}
        *          },
        *               @OA\Response(
        *                 response=200,
        *                 description="Success",
        *                 @OA\MediaType(
        *                 mediaType="application/json",
        *               )
        *             ),
        *          )
      */
      public function FinancialClassifier();
    
}