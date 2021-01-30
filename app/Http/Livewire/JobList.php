<?php

namespace App\Http\Livewire;

use App\Models\url\JobUrl;
use Livewire\Component;

class JobList extends Component
{
    public $search_keywords = "";
    public $jobs;
    public $total_jobs;

    public function mount(){
        // $this->search_keywords = "php";
        $this->jobs = JobUrl::all();
    }

    public function render()
    {
        if($this->search_keywords==""){
            $this->jobs = JobUrl::all();
        }else{
            $this->jobs = JobUrl::where('job_name','LIKE','%'.$this->search_keywords.'%')->get();
        }
        $this->total_jobs = $this->jobs->count();
        return view('livewire.job-list');
    }

    public function search(){
//        $this->jobs = JobUrl::where('job_name',$this->search_keywords)->get();
    }
}
