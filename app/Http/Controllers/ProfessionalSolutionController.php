<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ProfessionalSolutionController extends Controller
{
    public function suggestSolution($inquiry) {

        $filePath = storage_path('solutions.json');
        $contents = File::get($filePath);
        $solutionsData = json_decode($contents, true);
        //선호하는 타입이 없다면 랜덤으로 타입을 정하고 성향에 맞는 리스트를 추천
        $randomType = array_rand($solutionsData);
        $solutionType = $inquiry->type ?? $randomType;
        $lifestyleTags = $inquiry->tags;

        //선호하는 타입의 솔루션 리스트
        $solutionList = $solutionsData[$solutionType];

        //태그 일치하는것만 필터링
        $filterSolutions = array_filter($solutionList, function ($solution) use ($lifestyleTags) {
            return count(array_intersect($solution['tags'], $lifestyleTags)) > 0;
        });

        $tagOrder = array_flip($lifestyleTags);
        usort($filterSolutions, function ($a, $b) use($lifestyleTags) {

            $aTag = $a['tags'];
            $bTag = $b['tags'];

            //태그 겹친 경우 우선순위 태그 많은 순
            if (count($bTag) !== count($aTag)) {
                return count($bTag) - count($aTag);
            }

            //선호 태그가 앞에 있는것을 우선으로 정렬
            $aInTag = in_array($aTag[0], $lifestyleTags);
            $bInTag = in_array($bTag[0], $lifestyleTags);
        
            if ($aInTag && !$bInTag) {
                return -1;
            } elseif (!$aInTag && $bInTag) {
                return 1;
            }

        });

        return reset($filterSolutions);
    }
}
