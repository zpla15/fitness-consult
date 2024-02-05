<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\FitnessConsult;
use App\Http\Controllers\ProfessionalSolutionController;

/**
* @OA\Info(
*     title="Fitness Consult API", version="1.0", description="Fitness Consult API Documentation",
*     @OA\Contact(
*         email="zpla9621@gmail.com",
*         name="kim"
*     )
* )
*/

class FitnessConsultController extends Controller
{
    /**
     * @OA\Post (
     *     path="/api/v1/how-to-lose-weight",
     *     tags={"피트니스 상담문의"},
     *     summary="피트니스 상담",
     *     description="피트니스 상담문의",
     *     @OA\RequestBody(
     *         description="피트니스 상담문의 정보",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema (
     *                 @OA\Property (property="name", type="string", description="유저 이름", example="차은우"),
     *                 @OA\Property (property="solutionType", type="string", description="선호 솔루션 타입", example="DIET"),
     *                 @OA\Property (property="lifestyleTags", type="array", description="라이프 스타일 태그", example="['enough_money']", @OA\Items(type="string")),    
     *             )
     *         )
     *     ),
     *     @OA\Response(response="200", description="Success"),
     *     @OA\Response(response="400", description="Fail")
     * )
     */

    public function fitnessInquiry(Request $request)
    {
        $validator = [
            'name' => 'required',
            'lifestyleTags' => 'required'
        ];

        $validatorCheck = Validator::make($request->all(), $validator);
        if($validatorCheck->fails()) {
            $response = $validatorCheck->errors()->all();
            return response($response, 400);
        }

        $name = $request->input('name');
        $solutionType = $request->input('solutionType');
        $lifestyleTags = $request->input('lifestyleTags');

        if(!is_array($lifestyleTags)){
            $lifestyleTags = array($lifestyleTags);
        }

        $inquiry = new FitnessConsult($name, $solutionType, $lifestyleTags);

        $professionalSolution = new ProfessionalSolutionController();
        $response = $professionalSolution->suggestSolution($inquiry);

        return response($response, 200);
    }
}
