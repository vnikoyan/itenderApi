<?php
/**
 * @OA\Schema(
 *     title="Login",
 *     description="Login API",
 *     @OA\Xml(
 *         name="Login"
 *     )
 * )
 */
interface Login
{
        /**
         * @OA\Property(
         *      title="email",
         *      description="email",
         *      example="test@test.com"
         * )
         *
         * @var string
         */
        public $email;

      /**
         * @OA\Property(
         *      title="password",
         *      description="password",
         *      example="123456"
         * )
         *
         * @var string
         */
        public $password;

}