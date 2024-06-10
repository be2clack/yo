<?php

class Update{

    public function addLog($line="",$status="",$version=""){

      $logs = [];
      $getUpdate = findOne("uni_updates","version=?", [$version]);

      if($getUpdate){
         if($getUpdate["logs"]) $logs = json_decode($getUpdate["logs"], true);
         $logs[] = ["status"=>$status,"line"=>$line,"time"=>date("Y-m-d H:i:s")];
         update("update uni_updates set logs=? where version=?", [json_encode($logs),$version]);
      }

    }

}

?>