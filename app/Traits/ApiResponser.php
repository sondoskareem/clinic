<?php
namespace App\Traits;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator ;

trait ApiResponser{
    //used only inside the trait
    private function sucessResponse($data , $code){
        return response()->json($data , $code);
    }
    //can be used outside the trait
    protected function sucessMseeage($data , $code){
        return response()->json(['msg'=>$data , 'code' => $code] , $code);
    }

    protected function errorResponse($message , $code){
        return response()->json(['error' =>$message, 'code' => $code] , $code);
    }

    protected function showAll(Collection $collection , $code = 200){
        if($collection->isEmpty()){
            return $this->sucessResponse(['data' => $collection] , $code);
        }
        
        
        return $this->sucessResponse( $collection, $code);
    }

    protected function showOne(Model $instanse , $code = 200){
       
        return $this->sucessResponse($instanse, $code);
    }

   
}