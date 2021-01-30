<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\url\JobCategory;
use App\Models\url\JobLocation;
use App\Models\url\JobSite;
use App\Models\url\JobUrl;
use Goutte\Client;
use Illuminate\Http\Request;
use Symfony\Component\DomCrawler\Crawler;

class dashboardController extends Controller
{

    private $categoryUrls = [];
    private $jobUrls = [];
    private $job_site_id;

    function cleanup_numbers($string) {
        $numbers = array("1", "2", "3", "4", "5", "6", "7", "8", "9", "0", "(" , ")");
        $string = str_replace($numbers, '', $string);
        return $string;
    }

    function extractNumbers($string){
        $string = str_replace("-"," ",$string);
        $number = (int) filter_var($string, FILTER_SANITIZE_NUMBER_INT);
        return $number;
    }



    public function index()
    {
        $url = "https://www.bdjobs.com/";
        $job_site = JobSite::where('site_name','bdjobs.com')->first();
        $url = $job_site->site_url;
        $job_site_id = $job_site->id;
        $this->job_site_id = $job_site_id;
        //        return $job_site;
        //    $url = "https://jobs.bdjobs.com/jobsearch.asp?fcatId=6";

        $client = new Client();
        $page = $client->request('GET', $url);



        
        $page->filter("a")->each(function($item,$i=0){
            $pattern_jobs = "~\bjobs.bdjobs.com\b~";
            $pattern_location = "~\bLocationId\b~";
            $pattern_cat = "~\bicatId\b~";
            // $pattern = "~\b\b~";
            $str = $item->link()->getUri();
            if (preg_match($pattern_jobs, $str)) {

                if(preg_match($pattern_location, $str)){

                    $location_text = $this->cleanup_numbers($item->text());
                    $location_job_number = $this->extractNumbers($item->text()) ;

                    $existing_location = JobLocation::where('location_name',$location_text)
                        ->where('job_site_id',$this->job_site_id)
                        ->first();
                    if(is_null($existing_location)){
                        $job_location = new JobLocation();
                        $job_location->job_site_id = $this->job_site_id;
                        $job_location->location_name = $location_text;
                        $job_location->location_url = $item->link()->getUri();

                    }else{
                        $job_location = $existing_location;
                    }
                    $job_location->number_of_job = $location_job_number;
                    $job_location->save();

                }elseif (preg_match($pattern_cat, $str)) {

                    $cat_text_name = $this->cleanup_numbers($item->text());
                    $cat_job_number = $this->extractNumbers($item->text());

                    $existing_category = JobCategory::where('job_site_id',$this->job_site_id)
                        ->where('category_name',$cat_text_name)
                        ->first();

                    if(is_null($existing_category)){
                        $job_cat = new JobCategory();
                        $job_cat->job_site_id = $this->job_site_id;
                        $job_cat->category_name = $cat_text_name;
                        $job_cat->number_of_job = $cat_job_number;
                        $job_cat->category_url = $item->link()->getUri();
                    }else{
                        $job_cat = $existing_category;
                        $job_cat->number_of_job = $cat_job_number;
                    }
                    $job_cat->save();

                    $this->categoryUrls[$i] = [
                        "text" => $item->text(),
                        "url" => $item->link()->getUri(),
                        "amount" => $cat_job_number
                    ];
                    $i++;
                }

            }
        });


        //        return $this->categoryUrls;

        return $this->crawlToTheLink();

        exit;

        return view('admin.dashboard');
    }

    //public $job_site_id = 0;
    public $job_category_id = 0;

    function crawlToTheLink(){
        //dd($this->categoryUrls[55]['url']);

        $job_site_name = "bdjobs.com";
        $job_site = JobSite::where('site_name',"$job_site_name")->first();

        $categories = JobCategory::where('job_site_id',$job_site->id)->get();
        $job_counter = 0;
        foreach($categories as $category){

            $this->job_category_id = $category->id;
            $this->job_site_id = $category->job_site_id;

            //dd($category->category_url);

            $client = new Client();
            $jobs = $client->request('GET', $category->category_url);
            $jobs->filter("a")->each(function($item,$i=0){

                $job_name = $item->text();
                $job_link = $item->link()->getUri();


                $pattern_jobs = "~\bjobs.bdjobs.com\b~";
                $pattern_id= "~\bid\b~";
                $pattern_ln = "~\bln\b~";
                // $pattern = "~\b\b~";
                $str = $item->link()->getUri();

                if (preg_match($pattern_jobs, $str) && preg_match($pattern_id, $str) && preg_match($pattern_ln, $str)) {

                    //dd($item);
                    $this->jobUrls[$i] = [
                        "text" => $job_name,
                        "url" => $job_link,
                        "cat_id" => $this->job_category_id
                    ];
                    $i++;

                    if($this->isDuplicated($job_link)){
                        $job_url = new JobUrl();
                        $job_url->job_site_id = $this->job_site_id;
                        $job_url->job_cat_id = $this->job_category_id;
                        $job_url->job_name = $job_name;
                        $job_url->job_url = $job_link;
                        $job_url->save();
                        echo "($i) ".$item->text()."<br/>";
                    }

                }

            });
            //            return $this->jobUrls;
        }
        //        exit;
        //        return $this->jobUrls;
    }


    public static function isDuplicated($job_url){
        $exists = JobUrl::where('job_url',$job_url)->count();
        return $exists ? false : true;
    }


    public $crawler;
    public function jobDetails($url=1){
        $url = "https://jobs.bdjobs.com/jobdetails.asp?id=935363&fcatId=2&ln=1";

        $client = new Client();
        $crawler = $client->request('GET', $url);
        $this->crawler = $crawler;

        dd($crawler);

    }

}

