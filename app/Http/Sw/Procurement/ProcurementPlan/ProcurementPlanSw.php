<?php

interface ProcurementPlanSw
{
      /**
      *      @OA\Get(
      *          path="/procurement-plan",
      *          tags={"Procurement"},
      *          summary="App\Http\Controllers\Api\Procurement\ProcurementPlanController@index",
      *          operationId="procurementPlanIndex",
      *                security={
      *                   {"bearerAuth": {}}
      *                 },
      *               @OA\Response(
      *                 response=200,
      *                 description="Success",
      *                 @OA\MediaType(
      *                 mediaType="application/json",
      *               )
      *             ),
      *          )
      */
    public function procurementPlan();



      /**
      *      @OA\Get(
      *          path="/procurement-plan/{procurement_id}",
      *          tags={"Procurement"},
      *          summary="App\Http\Controllers\Api\Procurement\ProcurementPlanController@show",
      *          operationId="procurementPlanshow",
      *              @OA\Parameter(
      *                  name="procurement_id",
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

    public function procurementPlanById();


      /**
      *      @OA\Get(
      *          path="/procurement-plan/getByCpvType/{procurement_id}/{cpv_type}",
      *          tags={"Procurement"},
      *          summary="App\Http\Controllers\Api\Procurement\ProcurementPlanController@getByCpvType",
      *          operationId="getByCpvType",
      *              @OA\Parameter(
      *                  name="procurement_id",
      *                  in="path",
      *                  @OA\Schema(
      *                      type="integer"
      *                 )
      *               ),
      *              @OA\Parameter(
      *                  name="cpv_type",
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

    public function getByCpvType();

      /**
      *      @OA\Get(
      *          path="/procurement-plan/histories/{row_id}",
      *          tags={"Procurement"},
      *          summary="App\Http\Controllers\Api\Procurement\ProcurementPlanController@getHistories",
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

      /**
      *      @OA\Get(
      *          path="/procurement-plan/histories-details/{details_id}",
      *          tags={"Procurement"},
      *          summary="App\Http\Controllers\Api\Procurement\ProcurementPlanController@getHistories",
      *          operationId="getHistories",
      *              @OA\Parameter(
      *                  name="details_id",
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

    public function getHistoriesDetails();




      /**
      *      @OA\Get(
      *          path="/procurement-plan/getValidType/{procurement_id}/{cpv_id}",
      *          tags={"Procurement"},
      *          summary="App\Http\Controllers\Api\Procurement\ProcurementPlanController@getValidType",
      *          operationId="getValidType",
      *              @OA\Parameter(
      *                  name="procurement_id",
      *                  in="path",
      *                  @OA\Schema(
      *                      type="integer"
      *                 )
      *               ),
      *              @OA\Parameter(
      *                  name="cpv_id",
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

    public function getValidType();

    /**
      *      @OA\Post(
      *          path="/procurement-plan",
      *          tags={"Procurement"},
      *          @OA\RequestBody(
      *              required=true,
      *              @OA\JsonContent(ref="#/components/schemas/createProcurementPlan")
      *          ),
      *          security={
      *              {"bearerAuth": {}}
      *          },
      *         summary="App\Http\Controllers\Api\Procurement\ProcurementPlanController@store",
      *         operationId="ProcurementControllerStore",
      *          @OA\Response(
      *              response=200,
      *              description="Success",
      *              @OA\MediaType(
      *                 mediaType="application/json",
      *             )
      *        ),
      *      )
    */
    public function createUser();


    /**
      *      @OA\Post(
      *          path="/procurement-plan/storeDetails/{procurement_id}",
      *          tags={"Procurement"},
      *          @OA\RequestBody(
      *              required=true,
      *              @OA\JsonContent(ref="#/components/schemas/createDetailsProcurementPlan")
      *          ),
      *          security={
      *              {"bearerAuth": {}}
      *          },
     *                       @OA\Parameter(
        *                  name="procurement_id",
        *                  in="path",
        *                  @OA\Schema(
        *                        type="integer"
        *                 )
        *               ),
      *         summary="App\Http\Controllers\Api\Procurement\ProcurementPlanController@store",
      *         operationId="storeDetails",
      *          @OA\Response(
      *              response=200,
      *              description="Success",
      *              @OA\MediaType(
      *                 mediaType="application/json",
      *             )
      *        ),
      *      )
    */
    public function storeDetails();

      /**
        *      @OA\Put(
        *          path="/procurement-plan/{procurement_row_id}",
        *          tags={"Procurement"},
        *          summary="App\Http\Controllers\Api\Procurement\ProcurementPlanController@edit",
        *          operationId="Edit",
        *          @OA\RequestBody(
        *              required=true,
        *              @OA\JsonContent(ref="#/components/schemas/updateProcurementPlan")
        *          ),
        *              @OA\Parameter(
        *                  name="procurement_row_id",
        *                  in="path",
        *                  @OA\Schema(
        *                      type="integer"
        *                 )
        *               ),
        *          security={
        *           {"bearerAuth": {}}
        *          },
        *           @OA\Response(
        *             response=200,
        *             description="Success",
        *             @OA\MediaType(
        *             mediaType="application/json",
        *             )
        *           ),

        *          )
      */
      public function getUserByUserId();



      /**
        *      @OA\Put(
        *          path="/procurement-plan/editDetails/{procurement_details_id}",
        *          tags={"Procurement"},
        *          summary="App\Http\Controllers\Api\Procurement\ProcurementPlanController@editDetails",
        *          operationId="getUserByUserDetailsId",
        *          @OA\RequestBody(
        *              required=true,
        *              @OA\JsonContent(ref="#/components/schemas/updateProcurementPlanRowDetails")
        *          ),
        *              @OA\Parameter(
        *                  name="procurement_details_id",
        *                  in="path",
        *                  @OA\Schema(
        *                      type="integer"
        *                 )
        *               ),
        *          security={
        *           {"bearerAuth": {}}
        *          },
        *           @OA\Response(
        *             response=200,
        *             description="Success",
        *             @OA\MediaType(
        *             mediaType="application/json",
        *             )
        *           ),

        *          )
      */
      public function getUserByUserDetailsId();

     /**
        *      @OA\Put(
        *          path="/procurement-plan/status/{procurement_row_id}",
        *          tags={"Procurement"},
        *          summary="App\Http\Controllers\Api\Procurement\ProcurementPlanController@updateStatus",
        *          operationId="changeStatus",
        *          @OA\RequestBody(
        *              required=true,
        *              @OA\JsonContent(ref="#/components/schemas/updateStatusProcurementPlan")
        *          ),
        *              @OA\Parameter(
        *                  name="procurement_row_id",
        *                  in="path",
        *                  @OA\Schema(
        *                      type="integer"
        *                 )
        *               ),
        *          security={
        *           {"bearerAuth": {}}
        *          },
        *           @OA\Response(
        *             response=200,
        *             description="Success",
        *             @OA\MediaType(
        *             mediaType="application/json",
        *             )
        *           ),

        *          )
      */
      public function changeStatus();





      /**
      *      @OA\Get(
      *          path="/procurement-plan/getByFinancialClassifiers/{procurement_id}/{financial_classifier_id}/{type}",
      *          tags={"Procurement"},
      *          summary="App\Http\Controllers\Api\Procurement\ProcurementPlanController@show",
      *          operationId="procurementPlanshow",
      *              @OA\Parameter(
      *                  name="procurement_id",
      *                  in="path",
      *                  @OA\Schema(
      *                      type="integer"
      *                 )
      *               ),
      *              @OA\Parameter(
      *                  name="financial_classifier_id",
      *                  in="path",
      *                  @OA\Schema(
      *                      type="integer"
      *                 )
      *               ),
      *              @OA\Parameter(
      *                  name="type",
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

    public function getByFinancialClassifiers();



      /**
      *      @OA\Get(
      *          path="/procurement-plan/getByClassifiers/{procurement_id}/{financial_classifier_id}/{type}",
      *          tags={"Procurement"},
      *          summary="App\Http\Controllers\Api\Procurement\ProcurementPlanController@show",
      *          operationId="getByClassifiers",
      *              @OA\Parameter(
      *                  name="procurement_id",
      *                  in="path",
      *                  @OA\Schema(
      *                      type="integer"
      *                 )
      *               ),
      *              @OA\Parameter(
      *                  name="financial_classifier_id",
      *                  in="path",
      *                  @OA\Schema(
      *                      type="integer"
      *                 )
      *               ),
      *              @OA\Parameter(
      *                  name="type",
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

    public function getByClassifiers();

      /**
      *      @OA\Get(
      *          path="/procurement-plan/getByFinancialClassifierId/{procurement_id}",
      *          tags={"Procurement"},
      *          summary="App\Http\Controllers\Api\Procurement\ProcurementPlanController@show",
      *          operationId="getByFinancialClassifierId",
      *              @OA\Parameter(
      *                  name="procurement_id",
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

    public function getByFinancialClassifierId();
      /**
      *      @OA\Get(
      *          path="/procurement-plan/getByClassifierId/{procurement_id}",
      *          tags={"Procurement"},
      *          summary="App\Http\Controllers\Api\Procurement\ProcurementPlanController@show",
      *          operationId="getByClassifierId",
      *              @OA\Parameter(
      *                  name="procurement_id",
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

    public function getByClassifierId();

      /**
      *      @OA\Get(
      *          path="/procurement-plan/getByClassifiersForFinancial/{procurement_id}/{classifier_id}",
      *          tags={"Procurement"},
      *          summary="App\Http\Controllers\Api\Procurement\ProcurementPlanController@show",
      *          operationId="getByClassifiersForFinancial",
      *              @OA\Parameter(
      *                  name="procurement_id",
      *                  in="path",
      *                  @OA\Schema(
      *                      type="integer"
      *                 )
      *               ),
       *          @OA\Parameter(
      *                  name="classifier_id",
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

    public function getByClassifiersForFinancial();

      /**
      *      @OA\Get(
      *          path="/procurement-plan/getProcurementByClassifierIdFinancialId/{procurement_id}/{classifier_id}/{financial_classifier}/{cpv_type}",
      *          tags={"Procurement"},
      *          summary="App\Http\Controllers\Api\Procurement\ProcurementPlanController@show",
      *          operationId="getProcurementByClassifierIdFinancialId",
      *              @OA\Parameter(
      *                  name="procurement_id",
      *                  in="path",
      *                  @OA\Schema(
      *                      type="integer"
      *                 )
      *               ),
       *              @OA\Parameter(
      *                  name="classifier_id",
      *                  in="path",
      *                  @OA\Schema(
      *                      type="integer"
      *                 )
      *               ),
       *              @OA\Parameter(
      *                  name="financial_classifier",
      *                  in="path",
      *                  @OA\Schema(
      *                      type="integer"
      *                 )
      *               ),
       *             @OA\Parameter(
      *                  name="cpv_type",
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

    public function getProcurementByClassifierIdFinancialId();





      /**
      *      @OA\Get(
      *          path="/procurement-plan/getByClassifiersForFinancialCpvType/{procurement_id}/{classifier_id}/{financial_classifier}",
      *          tags={"Procurement"},
      *          summary="App\Http\Controllers\Api\Procurement\ProcurementPlanController@getByClassifiersForFinancialCpvType",
      *          operationId="getByClassifiersForFinancialCpvType",
      *              @OA\Parameter(
      *                  name="procurement_id",
      *                  in="path",
      *                  @OA\Schema(
      *                      type="integer"
      *                 )
      *               ),
       *              @OA\Parameter(
      *                  name="classifier_id",
      *                  in="path",
      *                  @OA\Schema(
      *                      type="integer"
      *                 )
      *               ),
       *              @OA\Parameter(
      *                  name="financial_classifier",
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

    public function getByClassifiersForFinancialCpvType();



      /**
      *      @OA\Get(
      *          path="/procurement-plan/getByFinancialClassifierCuntCpvType/{procurement_id}/{financial_classifier}",
      *          tags={"Procurement"},
      *          summary="App\Http\Controllers\Api\Procurement\ProcurementPlanController@getByFinancialClassifierCuntCpvType",
      *          operationId="getByClassifiersForFinancialCpvType",
      *              @OA\Parameter(
      *                  name="procurement_id",
      *                  in="path",
      *                  @OA\Schema(
      *                      type="integer"
      *                 )
      *               ),
       *              @OA\Parameter(
      *                  name="financial_classifier",
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

    public function getByFinancialClassifierCuntCpvType();




      /**
      *      @OA\Get(
      *          path="/procurement-plan/getByCpvGroup/{procurement_id}/{cpv_type}",
      *          tags={"Procurement"},
      *          summary="App\Http\Controllers\Api\Procurement\ProcurementPlanController@getByCpvGroup",
      *          operationId="getByCpvGroup",
      *              @OA\Parameter(
      *                  name="procurement_id",
      *                  in="path",
      *                  @OA\Schema(
      *                      type="integer"
      *                 )
      *               ),
      *              @OA\Parameter(
      *                  name="cpv_type",
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

    public function getByCpvGroup();






      /**
      *      @OA\Get(
      *          path="/procurement-plan/getByQuery/{procurement_id}",
      *          tags={"Procurement"},
      *          summary="App\Http\Controllers\Api\Procurement\ProcurementPlanController@getByCpvGroup",
      *          operationId="getByQuery",
      *              @OA\Parameter(
      *                  name="procurement_id",
      *                  in="path",
      *                  @OA\Schema(
      *                      type="integer"
      *                 )
      *               ),
      *              @OA\Parameter(
      *                  name="financial_classifier_id",
      *                  in="query",
      *                  @OA\Schema(
      *                      type="integer"
      *                 )
      *               ),
      *              @OA\Parameter(
      *                  name="classifier_id",
      *                  in="query",
      *                  @OA\Schema(
      *                      type="integer"
      *                 )
      *               ),
      *              @OA\Parameter(
      *                  name="cpv_type",
      *                  in="query",
      *                  @OA\Schema(
      *                      type="integer"
      *                 )
      *               ),
      *              @OA\Parameter(
      *                  name="type",
      *                  in="query",
      *                  @OA\Schema(
      *                      type="integer"
      *                 )
      *               ),
      *              @OA\Parameter(
      *                  name="query",
      *                  in="query",
      *                  @OA\Schema(
      *                      type="string"
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

    public function getByQuery();




      /**
      *      @OA\Get(
      *          path="/procurement-plan/getListByCpvGroup/{procurement_id}/{cpv_type}/{cpv_group}",
      *          tags={"Procurement"},
      *          summary="App\Http\Controllers\Api\Procurement\ProcurementPlanController@getListByCpvGroup",
      *          operationId="getListByCpvGroup",
      *              @OA\Parameter(
      *                  name="procurement_id",
      *                  in="path",
      *                  @OA\Schema(
      *                      type="integer"
      *                 )
      *               ),
      *              @OA\Parameter(
      *                  name="cpv_type",
      *                  in="path",
      *                  @OA\Schema(
      *                      type="integer"
      *                 )
      *               ),
      *              @OA\Parameter(
      *                  name="cpv_group",
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

    public function getListByCpvGroup();



}
