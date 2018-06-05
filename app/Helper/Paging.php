<?php

namespace App\Helper;

class Paging {

    public static $allItems;
    public static $allPages;
    public static $perPage;
    public static $pageNow;
    public static $triggerZone;

    public static function create($allItems, $perPage, $pageNow, $url, $triggerZone){
        self::$allItems = $allItems;
        self::$perPage = $perPage;
        self::$pageNow = $pageNow;
        self::$triggerZone = $triggerZone;
        self::$allPages = ceil( $allItems/$perPage );

        $data['items'] = self::triggerZone();
        $data['pageNow'] = $pageNow;
        $data['url'] = $url;

        $paging['skip'] = self::skip();
        $paging['paging'] = self::build( $data );
        $paging['perPage'] = self::$perPage;

        return $paging;
    }

    public static function build($data){
        $content_paging ='';

        if($data['items'][0] != $data['items'][1]){
           $start = $data['items'][0];

            while( $start <=  $data['items'][1]){

                $content_paging .= '<a '.(($data['pageNow'] == $start)? 'class="current"' :'').' href="'.url( $data['url'] .( ( $start == 1 )?'':'/'.$start )).'">'.$start.'</a>';

                $start++;
            }
        }
        
        return $content_paging;
    }


    public static function skip(){
       $skip = self::$pageNow*self::$perPage - self::$perPage;
       return (is_null($skip)?0:$skip);
    }

    public static function triggerZone(){
        $startP = self::$pageNow - self::$triggerZone;
        $startL = 0;
        if($startP<1){
            $startL = ($startP*-1)+1;
            $startP = 1;
        }
        $endP = self::$pageNow + self::$triggerZone + $startL;
        $endL = (self::$allPages-$endP);
       if($endL<0){
           $endP= self::$allPages;
           if($startP + $endL < 1  ){
               $startP = 1;
           }else{
               $startP = $startP + $endL;
           }
       }

       return [$startP,$endP];
    }

}