<?php


namespace App\Http\Sw\Settings\Package;


interface PackageSw
{
    /**
     *      @OA\Get(
     *          path="/package",
     *          tags={"Settings"},
     *              summary="App\Http\Controllers\Api\Settings\PackageController@index",
     *              operationId="PackageList",
     *               @OA\Response(
     *                   response=200,
     *                   description="Success",
     *                   @OA\MediaType(
     *                      mediaType="application/json",
     *                  )
     *             ),
     *          )
     */
    public function PackageList();
}
