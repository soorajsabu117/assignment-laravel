<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Products;
use Validator,Exception,DB;
use League\Csv\Reader;

class ProductController extends Controller
{
    //
    private function validateAccesToken($access_token)
    {

        $user = User::where(['access_token' => $access_token])->get();

        if ($user->count() == 0) {
            http_response_code(401);
            echo json_encode([
                'status' => "0",
                'message' => 'Session Expired Please login to continue',
                'oData' => [],
                'errors' => (object) [],
            ]);
            exit;

        } else {
            $user = $user->first();
            if ($user != null) { 
                return $user->id;
            } else {
                http_response_code(401);
                echo json_encode([
                    'status' => "0",
                    'message' => 'Session Expired Please login to continue',
                    'oData' => [],
                    'errors' => (object) [],
                ]);
                exit;
            }
        }
    }
    public function file_upload(REQUEST $request){
        $status = "0";
        $message = "";
        $o_data = [];
        $errors = [];

        $validator = Validator::make($request->all(), [
            'access_token' => 'required',
            'csv_file' => 'required|mimes:csv,txt',
        ]);
        
        if ($validator->fails()) {
            $status = "0";
            $message = "Kindly fill all the mandatory fields";
            $errors = $validator->messages();
            
        }else{
            try{
                $user_id = $this->validateAccesToken($request->access_token);
                $file = $request->file('csv_file');
                $csv = Reader::createFromPath($file->getPathname(), 'r');
                $csv->setHeaderOffset(0);

                
                $processed_data = [];
                foreach ($csv as $data) {
                    if(isset($data['ProductName'])){
                        if($data['ProductName'] != '' && $data['Price'] != '' && $data['Sku']){
                            $processed_data[] = [
                                'product_name'      =>  $data['ProductName'],
                                'price'             =>  $data['Price']??0,
                                'sku'               =>  $data['Sku']??'',
                                'description'       =>  $data['Description']??'',
                                'user_id'           =>  $user_id,
                                'updated_at'        =>  gmdate('Y-m-d H:i:s'),
                                'created_at'        =>  gmdate('Y-m-d H:i:s')
                            ];
                        }
                    }
                }
                if(!empty($processed_data)){
                    DB::transaction(function () use ($processed_data) {
                        Products::insert($processed_data);
                    });
                    $status  = "1";
                    $message = "Product imported successfully";
                }else{
                    $message = "invalid csv file uploaded";
                }
                
            } catch (Exception $e) {
                $message = $e->getMessage();
            }

        }
        return response()->json(['status' => $status, 'message' => $message,'oData'=>(object)$o_data,'errors'=>(object)$errors]);
    }

    public function get_prodcuts(REQUEST $request){
        $status = "0";
        $message = "";
        $o_data = [];
        $errors = [];

        $validator = Validator::make($request->all(), [
            'access_token' => 'required'
        ]);
        
        if ($validator->fails()) {
            $status  = "0";
            $message = "Kindly fill all the mandatory fields";
            $errors  = $validator->messages();
            
        }else{
            $user_id = $this->validateAccesToken($request->access_token);
            $page   = $request->page??1;
            $limit  = $request->limit??10;
            $offset = ($page - 1) * $limit;

            $list   = Products::where(['user_id'=>$user_id])->orderBy('id','desc')->take($limit)->skip($offset)->get();
            if($list->count() > 0){
                $status  = "1";
                $message = "data fetched successfully";
                $o_data['list'] = $list->toArray();
            }else{
                $message = "no data to list";
            }
        }
        return response()->json(['status' => $status, 'message' => $message,'oData'=>(object)$o_data,'errors'=>(object)$errors]);
    }
}
