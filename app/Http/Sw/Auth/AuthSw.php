<?php

interface AuthSw
{
          

    /**
      *      @OA\Post(
      *          path="/auth/login",
      *          tags={"Auth"},
      *      @OA\RequestBody(
      *          required=true,
      *          @OA\JsonContent(ref="#/components/schemas/Login")
      *      ),
      *              summary="App\Http\Controllers\Api\Auth\AuthController@login",
      *              operationId="login",
      *               @OA\Response(
      *                 response=200,
      *                 description="Success",
      *                 @OA\MediaType(
      *                 mediaType="application/json",
      *               )
      *             ),
      *          )
    */
    public function login();

    /**
      *      @OA\Post(
      *          path="/auth/signup",
      *          tags={"Auth"},
      *      @OA\RequestBody(
      *          required=true,
      *          @OA\JsonContent(ref="#/components/schemas/createUser")
      *      ),
      *              summary="App\Http\Controllers\Api\Auth\AuthController@createUser",
      *              operationId="signup",
      *               @OA\Response(
      *                   response=200,
      *                   description="Success",
      *                   @OA\MediaType(
      *                      mediaType="application/json",
      *                  )
      *             ),
      *          )
    */
    public function createUser();
    
}