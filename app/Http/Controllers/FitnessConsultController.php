<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\FitnessConsult;
use App\Http\Controllers\ProfessionalSolutionController;

class FitnessConsultController extends Controller
{

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

        $inquiry = new FitnessConsult($name, $solutionType, $lifestyleTags);

        $professionalSolution = new ProfessionalSolutionController();
        $response = $professionalSolution->suggestSolution($inquiry);

        return response($response, 200);
    }
}
