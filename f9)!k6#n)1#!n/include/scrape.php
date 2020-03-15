<?php
include($app_key.'/model/SnapDeal.php');
// if(intval('>')==0){
//     echo "fdsf";exit;
// }

// $url ="https://www.snapdeal.com/acors/json/product/get/search/18/40/20?q=&sort=plrty&brandPageUrl=&keyword=&searchState=k3=true|k5=0|k6=0|k7=/x0AABsSAAAAAAIAAAQ=|k8=0&pincode=&vc=&webpageName=categoryPage&campaignId=&brandName=&isMC=false&clickSrc=unknown&showAds=true&cartId=&page=cp";
// $page = file($url);
// print_r(implode(' ',$page));exit;
// foreach($page as $k => $p){
//     if(strpos($p,"dp-info-collect")!==false){
//         echo $k."<br>";
//     }
// }
$url = "https://www.snapdeal.com/acors/json/product/get/search/{{catid}}/{{offset}}/20?q=&sort=plrty&brandPageUrl=&keyword=&searchState=k3=true|k5=0|k6=0|k7=/x0AABsSAAAAAAIAAAQ=|k8=0&pincode=&vc=&webpageName=categoryPage&campaignId=&brandName=&isMC=false&clickSrc=unknown&showAds=true&cartId=&page=cp";
// echo "fds";exit;
$start = SnapDeal::where(null,null,null,'max:catid',[['catid','%LIKE%','']]);
// echo $start;exit;
for($i=$start; $i<1100; $i++){
    $page = file(str_replace(['{{catid}}','{{offset}}'],[$i,0],$url));
    $page = implode(' ', $page);
    $first = strpos($page,"jsNumberFound hidden")+22;
    $last = strpos($page, '<',$first);
    $tp = substr($page,$first,$last-$first);
    $pc = 100;
    for($j = 0; $j < intval($tp); $j = $j + 20){
        $record = SnapDeal::where(0,1,null,'first',['catid'=>$i,'offset'=>$j]);
        if($record){
            break;
        }
        if($j>0){
            $page = file(str_replace(['{{catid}}','{{offset}}'],[$i,$j],$url));
            $page = implode(' ', $page);
            $first = strpos($page,"pagination-lower-count")+24;
            if($first==24){
                break;
            }
            $last = strpos($page, '<',$first);
            $lc = substr($page,$first,$last-$first);
            
            $first = strpos($page,"pagination-upper-count",$last)+24;
            $last = strpos($page, '<',$first);
            $uc = substr($page,$first,$last-$first);
            
            $pc = intval($uc)-intval($lc)+1;
            // echo 'page_count'.$pc;
        }
        // for($k=352; $k<1700; $k = $k + 336){
        for($k=0; $k<min(20,$tp,$pc); $k++){
            // $first = strpos($page[$k],"value=")+7;
            // $last = strpos($page[$k], '"',$first);
            // $p = substr($page[$k],$first,$last-$first);
            // $p = str_replace("'",'"',$p);
            // echo $p;
            // $arr = json_decode($p);
            // print_r($arr);
            // exit;

            $arr = [];
            $arr['catid'] = $i;
            $arr['offset'] = $j;
            
            $first = strpos($page,"https://www.snapdeal.com/product",$last);
            $last = strpos($page, '"',$first);
            $arr['url'] = substr($page,$first,$last-$first);
            // echo $arr['url'].'<br>';
            
            $first = strpos($page,"srcset=",$last)+8;
            $last = strpos($page, '"',$first);
            $arr['image1'] = substr($page,$first,$last-$first);
            // echo substr($arr['image1'],strrpos($arr['image1'],'.')+1,10);exit;
            // echo $arr['image1'].'<br>';
            
            if(!in_array(strtolower(substr($arr['image1'],strrpos($arr['image1'],'.')+1,10)),["jpg","png","jpeg","gif","bmp"])){
                echo strtolower(substr($arr['image1'],strrpos($arr['image1'],'.')+1,10));exit;
            }
            
            $first = strpos($page,"data-src=",$last)+10;
            $last = strpos($page, '"',$first);
            $arr['image2'] = substr($page,$first,$last-$first);
            // echo $arr['image2'].'<br>';
            
            $first = strpos($page,"title=",$last)+7;
            $last = strpos($page, '"',$first);
            $arr['name'] = substr($page,$first,$last-$first);
            // echo $arr['name'].'<br>';
            
            $first = strpos($page,"value=",$last)+7;
            $last = strpos($page, '"',$first);
            $arr['image3'] = substr($page,$first,$last-$first);
            // echo $arr['image3'].'<br>';
            
            $first = strpos($page,"Rs.",$last);
            $last = strpos($page, '<',$first);
            $arr['price'] = substr($page,$first,$last-$first);
            // echo $arr['price'].'<br>';
            
            $first = strpos($page,'Rs.',$last);
            $last = strpos($page, '<',$first);
            $arr['price_after_discount'] = substr($page,$first,$last-$first);
            // echo $arr['price_after_discount'].'<br>';
            
            if(str_replace(' ','',$arr['price']) !== str_replace(' ','',$arr['price_after_discount'])){
            $first = strpos($page,"<span>",$last)+6;
            $last = strpos($page, '<',$first);
            $arr['discount'] = substr($page,$first,$last-$first);
            // echo $arr['discount'].'<br>';   
            }
            
            $first = strpos($page,"product-rating-count",$last)+23;
            $last = strpos($page, ')<',$first);
            if($first !== 23){
            $arr['rating'] = substr($page,$first,$last-$first);
            }
            // echo $arr['rating'].'<br>';
            
            // if(intval($arr['rating'])==0){
            //     echo $page;exit;
            // }
            
            $first = strpos($page,"https://www.snapdeal.com/product",$last);
            $last = strpos($page, '"',$first);
            $arr['url'] = substr($page,$first,$last-$first);
            // echo $arr['url'].'<br>';
            
            SnapDeal::create(null,$arr);
            // exit;
        }
        // if(intval($arr['rating'])==0){
        //     break;
        // }
        // exit;
    }
}
?>