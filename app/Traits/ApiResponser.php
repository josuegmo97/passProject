<?php

namespace App\Traits;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Validator;


trait ApiResponser{

    private function successResponse($data, $code){
        return response()->json($data, $code);
    }

    protected function errorResponse($message, $code = 422){

        $data = [];
        $data[] = $message;

        return response()->json(['errors'=>$data, 'code' => $code], $code);
    }

    protected function showAll(Collection $collection, $code = 200){
        return response()->json(['data' => $collection], $code);
    }

    protected function showOne(Model $instance, $code = 200){
        return response()->json(['data' => $instance], $code);
    }

    protected function showMessage($message, $code = 200)
	{
		return $this->successResponse(['data' => $message], $code);
    }

    protected function jgmo($request, $rules, $resp = 422)
    {
        $validator = Validator::make($request->all(),$rules);

        $switch = false;
        $return = '';

        if($validator->fails()){
            $messages=$validator->messages();
            $errors=$messages->all();

            $switch = true;

            $return = response()->json([
            'errors' => $errors,
            'sucess' => false
            ], $resp);
        }

        if($switch == true)
        {
            return $return;
        }else{
            return false;
        }
    }

    protected function slug_generate($value)
    {
        
    }
}


?>
