<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\TransactionRequest;
use App\Models\Transaction;
use Froiden\RestAPI\ApiController;
use Illuminate\Http\Request;

class TransactionController extends ApiController
{
    protected $model = Transaction::class;
    protected $storeRequest = TransactionRequest::class;
    protected $updateRequest = TransactionRequest::class;
}
