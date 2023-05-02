<?php

interface UserSw
{

      /**
        *      @OA\Get(
        *          path="/me",
        *          tags={"User"},
        *          summary="App\Http\Controllers\Api\User@me",
        *          operationId="getMe",
        *               security={
        *                 {"bearerAuth": {}}
        *               },
        *               @OA\Response(
        *                 response=200,
        *                 description="Success",
        *                 @OA\MediaType(
        *                   mediaType="application/json",
        *                 )
        *               ),
        *        )
      */
      public function getMe();

      /**
        *      @OA\Get(
        *          path="/user/root-user",
        *          tags={"User"},
        *          summary="App\Http\Controllers\Api\User@getRootUser",
        *          operationId="getRootUser",
        *               security={
        *                  {"bearerAuth": {}}
        *               },
        *               @OA\Response(
        *                 response=200,
        *                 description="Success",
        *                 @OA\MediaType(
        *                 mediaType="application/json",
        *               )
        *             ),
        *          )
      */
      public function getRootUser();

      /**
        *      @OA\Get(
        *          path="/user/user-child",
        *          tags={"User"},
        *          summary="App\Http\Controllers\Api\User@getUserChild",
        *          operationId="getUserChild",
        *           security={
        *              {"bearerAuth": {}}
        *            },
        *            @OA\Response(
        *              response=200,
        *              description="Success",
        *              @OA\MediaType(
        *                   mediaType="application/json",
        *              )
        *             ),
        *          )
      */
      public function getUserChild();

      /**
        *      @OA\Get(
        *          path="/user/user-group",
        *          tags={"User"},
        *          summary="App\Http\Controllers\Api\User@getUserGrup",
        *          operationId="getUserChild",
        *           security={
        *               {"bearerAuth": {}}
        *           },
        *           @OA\Response(
        *              response=200,
        *              description="Success",
        *              @OA\MediaType(
        *              mediaType="application/json",
        *             )
        *           ),
        *        )
      */
      public function getUserGrup();

      /**
        *      @OA\Get(
        *          path="/user/{user_id}",
        *          tags={"User"},
        *          summary="App\Http\Controllers\Api\User@show",
        *          operationId="GetUser",
        *              @OA\Parameter(
        *                  name="user_id",
        *                  in="path",
        *                  @OA\Schema(
        *                      type="string"
        *                 )
        *             ),
        *             security={
        *                  {"bearerAuth": {}}
        *             },
        *             @OA\Response(
        *                 response=200,
        *                 description="Success",
        *                 @OA\MediaType(
        *                 mediaType="application/json",
        *               )
        *            ),
        *        )
      */
      public function getUserByUserId();

      /**
        *      @OA\Put(
        *          path="/user",
        *          tags={"User"},
        *          summary="App\Http\Controllers\Api\User@edit",
        *          operationId="Edit",
        *           @OA\RequestBody(
        *             required=true,
        *             @OA\JsonContent(ref="#/components/schemas/createUser")
        *            ),
        *            security={
        *             {"bearerAuth": {}}
        *            },
        *               @OA\Response(
        *                 response=200,
        *                 description="Success",
        *                 @OA\MediaType(
        *                 mediaType="application/json",
        *                 example={
        *                   "data": {"id": 2, "name": "Babken", "email": "test@test.com"}, "timestamp": 1591470736.817241
        *                  }
        *               )
        *             ),

        *          )
      */
      public function Edit();
    /**
     *      @OA\Get(
     *          path="/user/search",
     *          tags={"User"},
     *          summary="App\Http\Controllers\Api\User@search",
     *          operationId="GetUserSearch",
     *              @OA\Parameter(
     *                  name="q",
     *                  in="query",
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
    public function GetUserSearch();


    /**
     *      @OA\Post(
     *          path="/user/responsible",
     *          tags={"User"},
     *          @OA\RequestBody(
     *              required=true,
     *              @OA\JsonContent(ref="#/components/schemas/createStateResponsibleUser")
     *          ),
     *          security={
     *              {"bearerAuth": {}}
     *          },
     *         summary="App\Http\Controllers\Api\User\UsersController@createStateResponsibleUser",
     *         operationId="createStateResponsibleUser",
     *          @OA\Response(
     *              response=200,
     *              description="Success",
     *              @OA\MediaType(
     *                 mediaType="application/json",
     *             )
     *        ),
     *      )
     */
    public function Responsible();



    /**
     *      @OA\Get(
     *          path="/user/user-child-members",
     *          tags={"User"},
     *          security={
     *              {"bearerAuth": {}}
     *          },
     *         summary="App\Http\Controllers\Api\User\UsersController@getResponsibleUser",
     *         operationId="getResponsibleMembersUser",
     *          @OA\Response(
     *              response=200,
     *              description="Success",
     *              @OA\MediaType(
     *                 mediaType="application/json",
     *             )
     *        ),
     *      )
     */
    public function getResponsibleMembersUser();



    /**
     *      @OA\Post(
     *          path="/user/user-child-members/{user_id}",
     *          tags={"User"},
      *         @OA\Parameter(
      *                  name="user_id",
      *                  in="path",
      *                  @OA\Schema(
      *                      type="string"
      *                 )
      *             ),
     *          @OA\RequestBody(
     *              required=true,
     *              @OA\JsonContent(ref="#/components/schemas/postUserChildMembers")
     *          ),
     *          security={
     *              {"bearerAuth": {}}
     *          },
     *         summary="App\Http\Controllers\Api\User\UsersController@postUserChildMembers",
     *         operationId="postUserChildMembers",
     *          @OA\Response(
     *              response=200,
     *              description="Success",
     *              @OA\MediaType(
     *                 mediaType="application/json",
     *             )
     *        ),
     *      )
     */
    public function postUserChildMembers();

    /**
     *      @OA\Put(
     *          path="/user/members/{members_id}",
     *          tags={"User"},
     *         @OA\Parameter(
     *              name="members_id",
     *              in="path",
     *              @OA\Schema(
     *                 type="string"
     *             )
     *         ),
     *          @OA\RequestBody(
     *              required=true,
     *              @OA\JsonContent(ref="#/components/schemas/postUserChildMembers")
     *          ),
     *          security={
     *              {"bearerAuth": {}}
     *          },
     *         summary="App\Http\Controllers\Api\User\UsersController@putUserChildMembers",
     *         operationId="putUserChildMembers",
     *          @OA\Response(
     *              response=200,
     *              description="Success",
     *              @OA\MediaType(
     *                 mediaType="application/json",
     *             )
     *        ),
     *      )
     */
    public function putUserChildMembers();

    /**
     *      @OA\Delete(
     *          path="/user/members/{members_id}",
     *          tags={"User"},
     *         @OA\Parameter(
     *                  name="members_id",
     *                  in="path",
     *                  @OA\Schema(
     *                      type="string"
     *                 )
     *             ),
     *          security={
     *              {"bearerAuth": {}}
     *          },
     *         summary="App\Http\Controllers\Api\User\UsersController@deleteUserChildMembers",
     *         operationId="deleteUserChildMembers",
     *          @OA\Response(
     *              response=200,
     *              description="Success",
     *              @OA\MediaType(
     *                 mediaType="application/json",
     *             )
     *        ),
     *      )
     */
    public function deleteUserChildMembers();
}