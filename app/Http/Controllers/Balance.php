<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Balance extends Controller
{
    //
    public function getBalance(request $Request){
        $idClinent = $Request->account_id;

        $Cl = DB::select(
            DB::raw("SELECT client_id
            , SUM(COALESCE(CASE WHEN action_type = 'debit' THEN action_amount END,0)) total_debits
            , SUM(COALESCE(CASE WHEN action_type = 'credit' THEN action_amount END,0)) total_credits
            , SUM(COALESCE(CASE WHEN action_type = 'credit' THEN action_amount END,0)) 
            - SUM(COALESCE(CASE WHEN action_type = 'debit' THEN action_amount END,0)) balance 
         FROM balance 
         where client_id = $idClinent
        GROUP  
           BY client_id
        HAVING balance <> 0"
        ));

        
       return response()->json($Cl,200);

    }

    public function event(request $Request){

        $origin = $Request->origin ? $Request->origin : null;
        $destination = $Request->destination ? $Request->destination : null;
        $type = $Request->type ? $Request->type : null;
        $amount = $Request->amount ? $Request->amount : null;


        switch($type){
            case "withdraw":

                $totalNow = $this->searchBalance($destination);

                //var_dump($totalNow); die();
                if(!empty($totalNow)){
                    $tt = $totalNow[0]->balance;
                    if($amount < $tt){
                        $this->debitBalance($destination, $amount);
                        return response()->json(['message' => 'Withdraw performed sucessfully!'], 200);
                    }else {
                        return response()->json(['message' => 'user\'s balace isnt enought!'], 406);
                     
                    }
                } else {
                    return response()->json(['message' => 'user not exist!'], 404);
                }

            break;


            case "transfer":

                $totalNow = $this->searchBalance($origin);

                //var_dump($totalNow); die();
                if(!empty($totalNow)){
                    $tt = $totalNow[0]->balance;
                    if($amount < $tt){
                        $this->debitBalance($origin, $amount);
                        $this->depositBalance($destination, $amount);
                        $array =  array("destination" => array ("id" => $destination, "balance" => $amount),
                                        "origin" => array ("id" => $origin, "balance" => $amount),                   
                                        "message" => 'Transfer performed sucessfully!' );
                    return response()->json( $array, 201);


                    }else {
                        return response()->json(['message' => 'origin \'s balace isnt enought!'], 406);
                     
                    }
                }else{
                    return response()->json(['message' => 'user not exist!'], 404);
                }
            break;

            case "deposit":
                $totalNow = $this->searchBalance($destination);
                
                if(!empty($totalNow)){
                    $this->depositBalance($destination, $amount);
                    $array =  array("destination" => array ("id" => $destination,
                                                            "balance" => $amount,)
                                                            , "message" => 'Deposit performed sucessfully' );
                    return response()->json( $array, 200);


                }else {
                    $this->depositBalance($destination, $amount);
                    $array =  array("destination" => array ("id" => $destination,
                                                            "balance" => $amount,)
                                                            , "message" => 'Account created sucessfully!' );
                    return response()->json( $array, 200);
                }

            break;
        }


    }

    public function debitBalance($client_Id, $amount){
        

        $raw = DB::insert(
            DB::raw("INSERT INTO balance
            ( client_id, action_type, action_amount, created_at, updated_at)
            VALUES ($client_Id, 'debit', $amount, NOW(), NOW())
            "));

         //var_dump($raw); die();
        return $raw;
    }

    public function depositBalance($client_Id, $amount){
        $raw = DB::insert(
            DB::raw("INSERT INTO balance
            ( client_id, action_type, action_amount, created_at, updated_at)
            VALUES ( $client_Id, 'credit', $amount, NOW(), NOW())
            "));

  
        return $raw;
    }

    public function createBalance($amount){

        $sc= DB::insert(
            DB::raw("select max(client_id) from balance"));

        if(! isnull($sc[0]->client_id)){

            $raw = DB::insert(
                DB::raw("INSERT INTO balance
                ( client_id, action_type, action_amount, created_at, updated_at)
                VALUES ( " . $sc[0]->client_id . ", 'credit', $amount, NOW(), NOW())
                "));
                $arrayInsert = array("client_id" => $sc[0]->client_id, "amount" => $amount);
            return $arrayInsert;
        }

    }

    public function searchBalance($clientID){
        $Cl = DB::select(
            DB::raw("SELECT client_id
            , SUM(COALESCE(CASE WHEN action_type = 'debit' THEN action_amount END,0)) total_debits
            , SUM(COALESCE(CASE WHEN action_type = 'credit' THEN action_amount END,0)) total_credits
            , SUM(COALESCE(CASE WHEN action_type = 'credit' THEN action_amount END,0)) 
            - SUM(COALESCE(CASE WHEN action_type = 'debit' THEN action_amount END,0)) balance 
         FROM balance 
         where client_id = $clientID
        GROUP  
           BY client_id
        HAVING balance <> 0"
        ));

        return $Cl;
    }

    public function resetTest(request $Request){
        DB::table('balance')->truncate();
        return response()->json(["messenge" =>"table reseted!"],200);
    }

}
