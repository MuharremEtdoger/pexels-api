<?php

namespace App\Http\Controllers;
use WBW\Library\Pexels\Model\Photo;
use WBW\Library\Pexels\Model\Source;
use WBW\Library\Pexels\Provider\ApiProvider;
use WBW\Library\Pexels\Request\SearchPhotosRequest;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\DB;

class PexelsController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __construct(Request $request)
    {
       View::share('resources_path', URL::to('/').'/assets');
    }
	public function pexelsIndexFront(){
		$photosdb = DB::select('select * from photos');
		if(count($photosdb)<100){
			return redirect(route('pexels-guncelle')); 
		}else{
			$photographers = DB::select('select * from photographers');
			View::share('pageTitle','Webtures App - Pexels API');
			View::share('body_class','webtures-app-body-home');
			View::share('photographers',$photographers);
			return view('home');	
		}
	}	
	public function pexelsReset(){
		DB::table('photographers')->truncate();
		DB::table('photos')->truncate();
		DB::table('images')->truncate();
		return redirect(route('pexels-home')); 
	}
	public function pexelsPhotographerFront($id){
		$photographer = DB::select('select * from photographers where id=?',[$id]);
		if($photographer){
			$photo_infos=array();
			$photos = DB::select('select * from photos where photographer_id=?',[$id]);
			if($photos){
				foreach($photos as $photo){
					$photo_size=DB::select('select * from images where photo_id=?',[$photo->id]);
					$photo_infos[$photo->id]['title']=$photo->title;
					$photo_infos[$photo->id]['avg_color']=$photo->avg_color;
					$photo_infos[$photo->id]['pexel_url']=$photo->pexel_url;
					if($photo_size){
						$photo_infos[$photo->id]['sizes']=$photo_size[0];
					}
				}
			}
			$image_sizes_text=array(
				'getOriginal'=>'Orjinal',
				'getLarge2x'=>'Large 2X',
				'getLarge'=>'Large',
				'getMedium'=>'Medium',
				'getSmall'=>'Small',
				'getPortrait'=>'Portrait',
				'getLandscape'=>'Landscape',
				'getTiny'=>'Tiny',
			);
			View::share('pageTitle',$photographer[0]->getPhotographer);
			View::share('body_class','webtures-app-body-home');
			View::share('photo_infos',$photo_infos);
			View::share('image_sizes_text',$image_sizes_text);
            return view('photographer');
		}else{
			return redirect(route('pexels-home')); 
		}
	}
	public function pexelsCurl($url){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
		$headers = array();
		$headers[] = 'Authorization: 563492ad6f9170000100000105ca3b74cb85411ea9e6d007dac401e3';
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		$result = curl_exec($ch);
		if (curl_errno($ch)) {
			echo curl_error($ch);
			exit;
		}else{
			curl_close($ch);
			return json_decode($result);
		}
	}
    public function pexelsUpdatePost(Request $request){
		$all_request=$request->all(); 
		$photosdb = DB::select('select * from photos');
		$process_state=2;
		if(count($photosdb)<100){
			$process_state=1;
		}
		$import_error=0;
		if($process_state==1 && $all_request['s']){
			$tum_gorseller=array();	
			$page=1;
			$perpage=51;
			$url='https://api.pexels.com/v1/search?query='.$all_request['s'].'&per_page='.$perpage.'&page='.$page;
			$result=self::pexelsCurl($url);
			if($result){
				if(isset($result->photos)){
					while (count($tum_gorseller)<=100 && isset($result->next_page)){
					  $tum_gorseller=array_merge($tum_gorseller,$result->photos);
					  $url=$result->next_page;
			          $result=self::pexelsCurl($url);
					}
				}else{
					View::share('pageTitle','Webtures App - Pexels API');
					View::share('showForm',1);
					View::share('showWarningText','Görsel Bulunamadı veya 100\'den az bulundu');
					return view('pexelsform');
				}
			}else{
					View::share('pageTitle','Webtures App - Pexels API');
					View::share('showForm',1);
					View::share('showWarningText','Görsel Bulunamadı veya 100\'den az bulundu');
					return view('pexelsform');
			}
			if($tum_gorseller){
				foreach($tum_gorseller as $gorsel){
					$url=$gorsel->url;
					$getPhotographer=$gorsel->photographer;
					$getPhotographerUrl=$gorsel->photographer_url;
					$getPhotographerId=$gorsel->photographer_id;				
					$avg_color=$gorsel->avg_color;				
					$pexel_url=$gorsel->url;
					$alt=$gorsel->alt;
					$sizes=$gorsel->src;
					$photographers_tb_insert=[$getPhotographer,'location_data_not_view',$getPhotographerId,$getPhotographerUrl];
					$photographers = DB::select('select * from photographers where getPhotographerId=?',[$getPhotographerId]);
					if(count($photographers)>0){
						$is_insert_photographers=1;
						$photographer_id=$photographers[0]->id;
									
					}else{
						$is_insert_photographers=DB::insert('insert into photographers(getPhotographer,location,getPhotographerId,getPhotographerUrl) values (?, ?, ? ,?)',$photographers_tb_insert);
						$photographer_id=DB::getPdo()->lastInsertId();
					}
                    if($is_insert_photographers){
						$title=$gorsel->alt;
						$description=$title;
						$liked = ($gorsel->liked) ? $gorsel->liked : '-';
						$avg_color=$gorsel->avg_color;
						$photos_tb_insert=[$photographer_id,$title,$description,$liked,$avg_color,$pexel_url];
						$is_insert_photos=DB::insert('insert into photos(photographer_id,title,description,likes,avg_color,pexel_url) values (?, ?, ? ,?, ?,?)',$photos_tb_insert);
					    if($is_insert_photos){
							$photo_id=DB::getPdo()->lastInsertId();
							$size_keys=['photo_id','getOriginal','getLarge2x','getLarge','getMedium','getSmall','getPortrait','getLandscape','getTiny'];
							$val_keys=array();
							for($i=1;$i<=count($size_keys);$i++){ $val_keys[]='?'; }
							$images_tb_insert=[$photo_id];
							foreach($sizes as $size){
								$images_tb_insert[]=$size;
							}
							$insert_into_query_sql='insert into images('.implode(',',$size_keys).') values('.implode(',',$val_keys).')';
							$is_insert_photo_size=DB::insert($insert_into_query_sql,$images_tb_insert);
							if($is_insert_photo_size){
	 								
							}else{
							   $import_error=1;
							}
						}else{
							$import_error=1;
						}
					}else{
                        $import_error=1;
					}
				}
				if($import_error){
					View::share('pageTitle','Webtures App - Pexels API');
					View::share('showForm',1);
					View::share('showWarningText','Veritabanına eklenirken hata oluştu');
					return view('pexelsform');
				}else{
					return redirect(route('pexels-home'));
				}
			}else{
				View::share('pageTitle','Webtures App - Pexels API');
				View::share('showForm',1);
				View::share('showWarningText','Görsel Bulunamadı veya 100\'den az bulundu');
				return view('pexelsform');				
			}
		}else if($process_state==2 && $all_request['s']){
			View::share('pageTitle','Webtures App - Pexels API');
			View::share('showForm',1);
			View::share('showWarningText','Database üzerindeki kayıtları temizleyip tekrar istek gönderebilirsiniz');
			return view('pexelsform');			
		}else{
			View::share('pageTitle','Webtures App - Pexels API');
			View::share('showForm',1);
			return view('pexelsform');			
		}
    }
    public function pexelsUpdateFront(Request $request){
		View::share('pageTitle','Webtures App - Pexels API');
		View::share('showForm',1);
        return view('pexelsform'); 
    }
}
