<?php


namespace App\Http\Sw\Organize\Responses;

/**
 * @OA\Schema(
 *     title="OrganizeUpdate",
 *     description="OrganizeUpdate API",
 *     @OA\Xml(
 *         name="OrganizeUpdate"
 *     )
 * )
 */
class OrganizeUpdate
{
    /**
     *                     @OA\Property(
     *                           title="procurement_id",
     *                           property="procurement_id",
     *                           description="procurement_id",
     *                           type="integer",
     *                           example=1
     *                      ),
     *                      @OA\Property(
     *                           title="organize_type",
     *                           property="organize_type",
     *                           description="Գնման գործընթացի կազմակերպման եղանակը|0 => Էլեկտրոնային| 1 => Թղթային",
     *                           type="integer",
     *                           enum={"0","1"},
     *                           example=1
     *                      ),
     *                      @OA\Property(
     *                           title="text_approval_date",
     *                           property="text_approval_date",
     *                           description="Հրավերի և հայտարարության տեքստերի հաստատման",
     *                           example="2020-09-14"
     *                      ),
     *                      @OA\Property(
     *                           title="decision_number",
     *                           property="decision_number",
     *                           description="Որոշման համարը",
     *                           example="12"
     *                      ),
     *                      @OA\Property(
     *                           title="name",
     *                           property="name",
     *                           description="Գնման ընթացակարգի անվանումը",
     *                           example={"hy":"name","ru":"name"}
     *                      ),
     *                      @OA\Property(
     *                           title="code",
     *                           property="code",
     *                           description="Գնման ընթացակարգի ծածկագիրը",
     *                           example={"hy":"name","ru":"name"}
     *                      ),
     *                      @OA\Property(
     *                           title="public_date",
     *                           property="public_date",
     *                           description="Հրավերի հրապարակման ամսաթիվը",
     *                           example="2020-09-14"
     *                      ),
     *                      @OA\Property(
     *                           title="submission_date",
     *                           property="submission_date",
     *                           description="Ներկայացման ժամկետը",
     *                           example="1"
     *                      ),
     *                      @OA\Property(
     *                           title="opening_date",
     *                           property="opening_date",
     *                           description="Բացման ամսաթիվը",
     *                           example="2020-09-14"
     *                      ),
     *                      @OA\Property(
     *                           title="opening_time",
     *                           property="opening_time",
     *                           description="Բացման Ժամը",
     *                           example="2020-09-14"
     *                      ),
     *                      @OA\Property(
     *                           title="prepayment",
     *                           property="prepayment",
     *                           description="Կանխավճարի նախատեսում",
     *                           type="integer",
     *                           enum={0,1},
     *                           example=1
     *                      ),
     *
     *                      @OA\Property(
     *                           title="prepayment_max",
     *                           property="prepayment_max",
     *                           description="առավելագույն չափը(ՀՀ դրամ)	",
     *                           example="123"
     *                      ),
     *
     *                      @OA\Property(
     *                           title="prepayment_time",
     *                           property="prepayment_time",
     *                           description="ժամկետը(ամիսը, տարեթիվը)",
     *                           example="12-2020"
     *                      ),
     *
     *                      @OA\Property(
     *                           title="paper_fee",
     *                           property="paper_fee",
     *                           description="Թղթային ձևով հրավերի տրամադրումն| 0=> Անվճար | 1=> Վճարովի",
     *                           type="integer",
     *                           enum={0,1},
     *                           example=1
     *                      ),
     *
     *                       @OA\Property(
     *                           title="fee",
     *                           property="fee",
     *                           description="Գումարի չափը",
     *                           example="100"
     *                      ),
     *
     *                      @OA\Property(
     *                           title="evaluator_secretary_name",
     *                           property="evaluator_secretary_name",
     *                           description="Քարտուղար name",
     *                          example={"hy":"name","ru":"name"}
     *                      ),
     *                      @OA\Property(
     *                           title="evaluator_secretary_email",
     *                           property="evaluator_secretary_email",
     *                           description="Քարտուղար email",
     *                          example="test@tst.cpm"
     *                      ),
     *                      @OA\Property(
     *                           title="evaluator_secretary_phone",
     *                           property="evaluator_secretary_phone",
     *                           description="Քարտուղար phone",
     *                          example="8765432345"
     *                      ),
     *                      @OA\Property(
     *                           title="evaluator_member",
     *                           property="evaluator_member",
     *                           description="Քարտուղար name",
     *                           example={{"hy":"name1","ru":"name1"},{"hy":"name2","ru":"name2"},{"hy":"name3","ru":"name3"}}
     *                      ),
     *
     */

 }

