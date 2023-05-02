<?php
/**
 * @OA\Schema(
 *     title="createParticipant",
 *     description="createParticipant API",
 *     @OA\Xml(
 *         name="createParticipant"
 *     )
 * )
 */
 interface createParticipant
    {
     /**
      *             @OA\Property(
      *                property="organize_id",
      *                  title="organize_id",
      *                  description="organize_id",
      *                  example=7
      *             ),
      *            @OA\Property(
      *                property="group_id",
      *                  title="group_id",
      *                  description="group_id",
      *                  example=7
      *             ),
      *
      *             @OA\Property(
      *                property="participant",
      *                title="participant",
      *                @OA\Items(
      *                      @OA\Property(
      *                           title="tin",
      *                            property="tin",
      *                           description="tin",
      *                           example=16
      *                      ),
      *
      *                      @OA\Property(
      *                           title="name",
      *                           property="name",
      *                           description="name",
      *                           example={"hy":"test","ru":"test"}
      *                      ),
      *                      @OA\Property(
      *                           title="address",
      *                           property="address",
      *                           description="address",
      *                           example={"hy":"test","ru":"test"}
      *                      ),
      *                      @OA\Property(
      *                           title="email",
      *                           property="email",
      *                           description="email",
      *                           example="test@test.com"
      *                      ),
      *
      *                      @OA\Property(
      *                           title="phone",
      *                           property="phone",
      *                           description="phone",
      *                           example="9222222"
      *                      ),
      *
      *                      @OA\Property(
      *                           title="date_of_submission",
      *                           property="date_of_submission",
      *                           description="date_of_submission",
      *                           example="2020-12-12"
      *                      ),
      *                )
      *        )
      *
      *
      */

     public  function organize_id();
     public  function group_id();
     public  function tin();
     public  function name();
     public  function address();
     public  function email();
     public  function phone();
     public  function date_of_submission();



 }
