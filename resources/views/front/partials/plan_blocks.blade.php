<div class="row subscription-plans">
    <div class="col-4">
        <div class="card" style="width: 18rem;">
          <div class="card-body">
            <h5 class="card-title text-center font-weight-bold">Free User</h5>
            <ul class="card-text">
                <li>For casual browsing</li>
                <li>Access daily news</li>
                <li>Access daily essay questions</li>
                <li>Access comments</li>
            </ul>
            <div class="border text-center p-1 mb-2">
                Rs 0
            </div>
            <a href="{{ route('user::subscription', ['action' => 'review', 'plan' => 'free']) }}" class="btn btn-primary float-right">Pick</a>
          </div>
        </div>
    </div>
    <div class="col-4">
        <div class="card" style="width: 18rem;">
          <div class="card-body">
            <h5 class="card-title text-center font-weight-bold">Premium User</h5>
            <ul class="card-text">
                <li>For prelim test series</li>
                <li><strong>Everything from <u>Free</u></strong></li>
                <li>Prelim mock test series</li>
                <li>Site notifications</li>
            </ul>
            <div class="border text-center p-1 mb-2">Rs 4999<sup class="text-success">*</sup></div>
            <a href="{{ route('user::subscription', ['action' => 'review', 'plan' => 'premium']) }}" class="btn btn-primary float-right">Pick</a>
          </div>
        </div>
    </div>
    <div class="col-4">
        <div class="card" style="width: 18rem;">
          <div class="card-body">
            <h5 class="card-title text-center font-weight-bold">Pro User</h5>
            <ul class="card-text">
                <li>For prelims and essay writing</li>
                <li><strong>Everything from <u>Premium</u></strong></li>
                <li>Notes section</li>
                <li>Media section</li>
                <li>Search section</li>
                <li>Topic wise news, daily questions, and past mock questions</li>
                <li>Upload and save essays</li>
                <li>View and save other aspirant's essays</li>
                <li>Article access</li>
            </ul>
            <div class="border text-center p-1 mb-2">Rs 9999<sup class="text-success">*</sup></div>
            <a href="{{ route('user::subscription', ['action' => 'review', 'plan' => 'pro']) }}" class="btn btn-primary float-right">Pick</a>
          </div>
        </div>
    </div>
    <div class="w-100"></div>
    <div class="col mt-4">
        <p><span class="text-danger font-weight-bold">*</span> Prices may increase without intimation.</p>
        <p><span class="text-success font-weight-bold">*</span> Transparent pricing - No extra charges - All prices shown are final. - No GST, no service, and no net banking charges.<br/>All plans valid till CSE mains</p>
    </div>
    <div class="w-100"></div>
</div>
