<?php
/**
 * @OA\Schema(
 *     title="SetSpecificationsByCpvId Edit api",
 *     description="set Specifications by  Cpv",
 *     @OA\Xml(
 *         name="SetSpecificationsByCpvId"
 *     )
 * )
 */
interface SetSpecificationsByCpvId
{
        /**
         * @OA\Property(
         *      title="description",
         *      description="description",
         *      example={"hy":"description_hy", "ru":"description_ru", "en":"description_en"}
         * )
         *
         * @var string
         */
        public $description;

}