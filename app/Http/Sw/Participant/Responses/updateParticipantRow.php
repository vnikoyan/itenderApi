<?php

/**
 * @OA\Schema(
 *     title="updateParticipantRow",
 *     description="updateParticipantRow API",
 *     @OA\Xml(
 *         name="updateParticipantRow"
 *     )
 * )
 */

interface updateParticipantRow
{
    /**

     *                      @OA\Property(
     *                           title="organize_row_id",
     *                            property="organize_row_id",
     *                           description="organize_row_id",
     *                           example=16
     *                      ),
     *
     *                      @OA\Property(
     *                           title="row_group_id",
     *                           property="row_group_id",
     *                           description="row_group_id",
     *                           example=16
     *                      ),
     *                      @OA\Property(
     *                           title="cost",
     *                           property="cost",
     *                           description="cost",
     *                           example=16
     *                      ),
     *                     @OA\Property(
     *                           title="profit",
     *                           property="profit",
     *                           description="profit",
     *                           example=16
     *                      ),
     *                     @OA\Property(
     *                           title="value",
     *                           property="value",
     *                           description="value",
     *                           example=16
     *                      ),
     *                     @OA\Property(
     *                           title="vat",
     *                           property="vat",
     *                           description="vat",
     *                           example=16
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
