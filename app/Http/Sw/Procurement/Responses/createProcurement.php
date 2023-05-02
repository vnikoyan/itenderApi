<?php
/**
 * @OA\Schema(
 *     title="createProcurement",
 *     description="createProcurement API",
 *     @OA\Xml(
 *         name="createProcurement"
 *     )
 * )
 */
interface createProcurement
{
    
        /**
        * @OA\Property(
        *      title="year",
        *      description="year",
        *      example=2020
        * )
        *
        * @var integer
        */
        public $year;

     
        /**
        * @OA\Property(
        *      title="name",
        *      description="name",
        *      example="String"
        * )
        *
        * @var String
        */
        public $name;


}