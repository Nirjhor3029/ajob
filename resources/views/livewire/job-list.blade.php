<div>
    {{-- If your happiness depends on money, you will never be happy with yourself. --}}

    <?php
    $rand_number = rand(1,9);
    $background = "/assets/background_images/job-list-back-$rand_number.gif";
?>
<center>
    <form action="" method="get" id="search_box">
        <div class="form-group" >
            <label for="search_keywords">Search</label>
            <input type="text" wire:model='search_keywords'  class="form-control" name="search_keywords" id="" >
            
        </div>
    </form>
</center>

<!-- Search Results -->
<div class="row container-fluid">
    <div class="col-sm-6 job-list" style="background-image: url({{asset($background)}})">
        {{-- <form action="" method="get" id="search_box">
            <div class="form-group" >
                <label for="search_keywords">Search</label>
                <input type="text" wire:model='search_keywords'  class="form-control" name="search_keywords" id="" >
                {{$search_keywords}}
            </div>
        </form> --}}
    
        @if ($total_jobs)
            @foreach ($jobs as $job)
            <div class="card card-box" style="width: 18rem;">
                <div class="card-body">
                    <a href="{{$job->job_url}}" class="card_link" target="job_frame">
                        <h5 class="card-title"  >{{$job->job_name}}</h5>
                    </a>
                
                    <h6 class="mb-2 card-subtitle text-muted">Card subtitle</h6>
                    <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                    <a href="#" class="card-link">Card link</a>
                    <a href="#" class="card-link">Another link</a>
                </div>
            </div>
            @endforeach
        @else
            <div class="card card-box" style="width: 18rem;">
                <div class="card-body">
                    <a href="#" class="card_link" target="job_frame">
                        <h5 class="card-title"  >No Job Found</h5>
                    </a>
                
                    <h6 class="mb-2 card-subtitle text-muted">Card subtitle</h6>
                    <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                    <a href="#" class="card-link">Card link</a>
                    <a href="#" class="card-link">Another link</a>
                </div>
            </div>
        @endif
        
        
    </div>
    <div class="col-sm-6">
        <div>
            <span class="ml-2 badge badge-pill badge-primary number-badge">{{$total_jobs}}</span>
             jobs found.
        </div>
        <div>
            <iframe src="https://jobs.bdjobs.com/jobdetails.asp?id=935637&fcatId=4&ln=1" name="job_frame" frameborder="0" id="job_frame"></iframe>
        </div>
    </div>
</div>
        

    
</div>
