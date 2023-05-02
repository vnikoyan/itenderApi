<?php
/**
 * @OA\Schema(
 *     title="UserGetSuccess Edit api",
 *     description="User Edit Api",
 *     @OA\Xml(
 *         name="UserGetSuccess"
 *     )
 * )
 */
interface UserGetSuccess
{
        /**
         * @OA\Property(
         *      title="name",
         *      description="name",
         *      example="Babken"
         * )
         *
         * @var string
         */
        public $name;

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
         *      title="old_password",
         *      description="old_password",
         *      example="123456"
         * )
         *
         * @var string
         */
        public $old_password;
      /**
         * @OA\Property(
         *      title="new_password",
         *      description="new_password",
         *      example="654321"
         * )
         *
         * @var string
         */
        public $new_password;

}