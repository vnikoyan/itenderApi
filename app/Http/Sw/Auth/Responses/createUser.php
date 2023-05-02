<?php
/**
 * @OA\Schema(
 *     title="Signup",
 *     description="createUser API",
 *     @OA\Xml(
 *         name="Signup"
 *     )
 * )
 */
interface createUser
{
    
        /**
         * @OA\Property(
         *      title="type",
         *      example="STATE",
         *      enum={"USER","STATE"}
         * )
         *
         * @var string
         */
        public $type;
        /**
         * @OA\Property(
         *      title="name",
         *      description="Name",
         *      example={"hy":"asd", "ru":"dasd", "en":"132asd"}
         * )
         *
         * @var string
         */

        public $name;

        /**
         * @OA\Property(
         *      title="email",
         *      description="Email",
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
         *      example="!A123456s"
         * )
         *
         * @var string
         */
        public $password;

      /**
         * @OA\Property(
         *      title="password_confirmation",
         *      description="password_confirmation",
         *      example="A123456s"
         * )
         *
         * @var string
         */
        public $password_confirmation;


        /**
         * @OA\Property(
         *      title="phone",
         *      description="phone",
         *      example="+37493000000"
         * )
         *
         * @var string
         */

        public $phone;
        /**
         * @OA\Property(
         *      title="tin",
         *      description="ՀՎՀՀ",
         *      example="123123312"
         * )
         *
         * @var string
         */

        public $tin;

        /**
         * @OA\Property(
         *      title="bank_account",
         *      description="bank_account",
         *      example="text"
         * )
         *
         * @var string
         */

        public $bank_account;
  /**
   * @OA\Property(
   *      title="region",
   *      description="region",
   *      example={"hy":"region", "ru":"region", "en":"region"}
   * )
   *
   * @var string
   */
  public $region;

  /**
   * @OA\Property(
   *      title="city",
   *      description="city",
   *      example={"hy":"city", "ru":"city", "en":"city"}
   * )
   *
   * @var string
   */
  public $city;

  /**
   * @OA\Property(
   *      title="address",
   *      description="address",
   *      example={"hy":"address", "ru":"address", "en":"address"}
   * )
   *
   * @var string
   */
  public $address;

  /**
   * @OA\Property(
   *      title="bank_name",
   *      description="bank_name",
   *      example={"hy":"bank_name", "ru":"bank_name", "en":"bank_name"}
   * )
   *
   * @var string
   */

  public $bank_name;

  
  /**
   * @OA\Property(
   *      title="director_name",
   *      description="director_name",
   *      example={"hy":"director_name", "ru":"director_name", "en":"director_name"}
   * )
   *
   * @var string
   */

  public $director_name;
  
  /**
   * @OA\Property(
   *      title="company_type",
   *      description="company_type",
   *      example={"hy":"ՊՈԱԿ", "ru":"ГНКО", "en":"ՊՈԱԿ"}
   * )
   *
   * @var string
   */

  public $company_type;


}