<?php

/**
 * @OA\Schema(
 *     title="createSelectedParticipant",
 *     description="createSelectedParticipant API",
 *     @OA\Xml(
 *         name="createSelectedParticipant"
 *     )
 * )
 */

interface createSelectedParticipant
{
    /**
     *                      @OA\Property(
     *                           title="organize_row_id",
     *                            property="organize_row_id",
     *                           description="organize_row_id",
     *                           example=1
     *                      ),
     *
     *                      @OA\Property(
     *                           title="participant_group_id",
     *                           property="participant_group_id",
     *                           description="participant_group_id",
     *                           example=1
     *                      ),
     *                      @OA\Property(
     *                           title="bank",
     *                           property="bank",
     *                           description="bank",
     *                           example={"hy":"babke","ru":"babken"}
     *                      ),
     *                      @OA\Property(
     *                           title="director_full_name",
     *                           property="director_full_name",
     *                           description="director_full_name",
     *                           example={"hy":"babke","ru":"babken"}
     *                      ),
     *                     @OA\Property(
     *                           title="hh",
     *                           property="hh",
     *                           description="hh",
     *                           example="test"
     *                      ),
     *                     @OA\Property(
     *                           title="name",
     *                           property="name",
     *                           description="name",
     *                           example="name"
     *                      ),
     *                     @OA\Property(
     *                           title="manufacturer_name",
     *                           property="manufacturer_name",
     *                           description="manufacturer_name",
     *                           example="test"
     *                      ),
     *                     @OA\Property(
     *                           title="country_of_origin",
     *                           property="country_of_origin",
     *                           description="country_of_origin",
     *                           example="test"
     *                      ),
     *
     *
     */

    public  function organize_row_id();
    public  function row_group_id();
    public  function cost();
    public  function profit();
    public  function value();
    public  function vat();
}
