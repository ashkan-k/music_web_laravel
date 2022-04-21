<?php

namespace App\Http\Controllers;

use App\Http\Traits\Responses;
use Illuminate\Http\Request;

class BulkActionController extends Controller
{
    use Responses;

   public function admin_bulk(Request $request)
   {
       $model = $request->model;
       $items = $request->items;
       $action = $request->action;

       $model = "App\\Models\\" . $model;

       if (!in_array($action, $model::$bulk_actions)){
           return $this->FailResponse('عملیات مورد نظر مجاز نیست!');
       }

       try {
           if ($action == 'delete'){
               $model::whereIn('id', $items)->delete();
           }

           else if ($action == 'approve_status'){
               $model::whereIn('id', $items)->ChangeStatus('AP');
           }

           else if ($action == 'reject_status'){
               $model::whereIn('id', $items)->ChangeStatus('RJ');
           }

           else if ($action == 'block'){
               $model::whereIn('id', $items)->ChangeBlockStatus(true);
           }

           else if ($action == 'unblock'){
               $model::whereIn('id', $items)->ChangeBlockStatus(false);
           }

           else if ($action == 'cash'){
               $model::whereIn('id', $items)->ChangeType(true);
           }

           else if ($action == 'free'){
               $model::whereIn('id', $items)->ChangeType(false);
           }

           else if ($action == 'active'){
               $model::whereIn('id', $items)->ChangeActiveStatus(true);
           }

           else if ($action == 'deactive'){
               $model::whereIn('id', $items)->ChangeActiveStatus(false);
           }
       }
       catch (\Exception $exception){
           return $this->FailResponse('خطایی هنگام انجام عملیات پیش آمده است!');
       }

       return $this->SuccessResponse('عملیات مورد نظر با موفقیت انجام شد!');
   }
}
