<div class="row">
    <div class="col-md-6">
        <ul class="nav nav-pills mb-3 mx-auto" id="pills-tab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="pills-daily-tab" data-bs-toggle="pill" data-bs-target="#pills-daily" type="button" role="tab" aria-controls="pills-daily" aria-selected="true">Daily</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true">Weekly</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile" aria-selected="false">Monthly</button>
            </li>
        </ul>

        <div class="tab-content custom-main-div-dynamic" id="pills-tabContent">
            <div class="tab-pane fade show active" id="pills-daily" role="tabpanel" aria-labelledby="pills-daily-tab">
                <?php
                $users = DB::table('users')->where('role_id', '!=', 1)->get();
                $data1 = [];
                foreach ($users as $user) {
                    $data = [];
                    $currentMonth = date('m');
                    $order = DB::table('orders')
                        ->where('created_by', $user->id)
                        ->whereDate('created_at', \Carbon\Carbon::today())
                        ->count();
                    $data[$user->name] = $order;
                    array_push($data1, $data);
                }
                $cnt = 0;
                $winner = 0;
                sort($data1);
                $coms = [];
                foreach ($data1 as $ky => $orders) {
                    $com = [];
                    foreach ($orders as $key => $value) {
                        $cnt = $value;
                        $winner = $key;
                        $com['winner'] = $winner;
                        $com['order'] = $value;
                        array_push($coms, $com);
                    }
                }
                usort($coms, function ($a, $b) {
                    return $a['order'] < $b['order'];
                });
                $comp = DB::table('orders')
                    ->where('created_by', Auth::user()->id)
                    ->whereDate('created_at', \Carbon\Carbon::today())
                    ->count();
                ?>
                <div class="row custom-dynamic-row justify-content-center mt-4">

                    <div class="col-xxl-11 col-xl-6 col-lg-8 col-md-6">
                        <?php $i = 0;
                        ?>
                        @foreach ($coms as $items)
                        <?php $i++; ?>
                        @if ($i <= 3) <div class="card card-bg1 d-flex justify-content-between winner-box mt-2">
                            <div class="parent-winner-box">
                                <div>
                                    <img src="{{ url('/') }}/public/assets/images/certificate/{{ $i }}-m.png" class="winner-img" alt="">
                                </div>
                                <div>
                                    <h5 class="houmanity-color">Winner {{ $i }}</h5>
                                    <h6>Name : {{ $items['winner'] }}</h6>
                                    <h6>Orders : {{ $items['order'] }}</h6>
                                    <a href="{{ route('admin-generate-certificate', ['winnerNumber' => $i, 'empName' => $items['winner']]) }}" class="btn btn-sm btn-success" data-bs-toggle="tooltip" data-bs-placement="top" title="Generate Certificate">Generate
                                        Certificate</a>
                                </div>
                            </div>
                    </div>
                    @endif
                    @endforeach
                </div>
            </div>
            <div class="mt-3">
                @if(Auth()->user() !== null && Auth()->user()->role_id == 1 || Auth()->user()->role_id == 4)
                <div class="text-center">
                    <a class="btn btn-primary" onclick="viewAll('daily')" href="#">See All</a>
                </div>
                @endif
            </div>
        </div>
        <div class="tab-pane fade" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
            <?php
            $users = DB::table('users')->where('role_id', '!=', 1)->get();
            $data1 = [];
            foreach ($users as $user) {
                $data = [];
                $currentMonth = date('m');
                $order = DB::table('orders')
                    ->where('created_by', $user->id)
                    ->whereBetween('created_at', [\Carbon\Carbon::now()->startOfWeek(), \Carbon\Carbon::now()->endOfWeek()])
                    ->count();
                $data[$user->name] = $order;
                array_push($data1, $data);
            }
            $cnt = 0;
            $winner = 0;
            sort($data1);
            $coms = [];
            foreach ($data1 as $orders) {
                $com = [];
                foreach ($orders as $key => $value) {
                    $cnt = $value;
                    $winner = $key;
                    $com['winner'] = $winner;
                    $com['order'] = $value;
                    array_push($coms, $com);
                }
            }
            usort($coms, function ($a, $b) {
                return $a['order'] < $b['order'];
            });
            $comp = DB::table('orders')
                ->where('created_by', Auth::user()->id)
                ->whereBetween('created_at', [\Carbon\Carbon::now()->startOfWeek(), \Carbon\Carbon::now()->endOfWeek()])
                ->count();
            ?>

            <div class="row custom-dynamic-row justify-content-center mt-4">
                <div class="col-xxl-11 col-xl-6 col-lg-8 col-md-6">
                    <?php $i = 0; ?>
                    @foreach ($coms as $items)
                    <?php $i++; ?>
                    @if ($i <= 3) <div class="card card-bg1 d-flex justify-content-between winner-box mt-2">
                        <div class="parent-winner-box">
                            <div>
                                <img src="{{ url('/') }}/public/assets/images/certificate/{{ $i }}-m.png" class="winner-img" alt="">
                            </div>
                            <div>
                                <h5 class="houmanity-color">Winner {{ $i }}</h5>
                                <h6>Name : {{ $items['winner'] }}</h6>
                                <h6>Orders : {{ $items['order'] }}</h6>
                                <a href="{{ route('admin-generate-certificate', ['winnerNumber' => $i, 'empName' => $items['winner']]) }}" class="btn btn-sm btn-success" data-bs-toggle="tooltip" data-bs-placement="top" title="Generate Certificate">Generate
                                    Certificate</a>
                            </div>
                        </div>
                </div>
                @endif
                @endforeach
            </div>
        </div>
        
        <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
            <?php
            $users = DB::table('users')->where('role_id', '!=', 1)->get();
            $data1 = [];
            foreach ($users as $user) {
                $data = [];
                $currentMonth = date('m');
                $order = DB::table('orders')
                    ->where('created_by', $user->id)
                    ->whereMonth('created_at', \Carbon\Carbon::now()->month)
                    ->count();
                $data[$user->name] = $order;
                array_push($data1, $data);
            }
            $cnt = 0;
            $winner = 0;
            sort($data1);
            $coms = [];
            foreach ($data1 as $ky => $orders) {
                $com = [];
                foreach ($orders as $key => $value) {
                    $cnt = $value;
                    $winner = $key;
                    $com['winner'] = $winner;
                    $com['order'] = $value;
                    array_push($coms, $com);
                }
            }

            usort($coms, function ($a, $b) {
                return $a['order'] < $b['order'];
            });
            usort($coms, function ($a, $b) {
                return $a['order'] < $b['order'];
            });
            $comp = DB::table('orders')
                ->where('created_by', Auth::user()->id)
                ->whereMonth('created_at', \Carbon\Carbon::now()->month)
                ->count();
            ?>
            <div class="row custom-dynamic-row justify-content-center mt-4">
                <div class="col-xxl-11 col-xl-6 col-lg-8 col-md-6">
                    <?php $i = 0;
                    ?>
                    @foreach ($coms as $items)
                    <?php $i++; ?>
                    @if ($i <= 3) <div class="card card-bg1 d-flex justify-content-between winner-box mt-2">
                        <div class="parent-winner-box">
                            <div>
                                <img src="{{ url('/') }}/public/assets/images/certificate/{{ $i }}-m.png" class="winner-img" alt="">
                            </div>
                            <div>
                                <h5 class="houmanity-color">Winner {{ $i }}</h5>
                                <h6>Name : {{ $items['winner'] }}</h6>
                                <h6>Orders : {{ $items['order'] }}</h6>
                                <a href="{{ route('admin-generate-certificate', ['winnerNumber' => $i, 'empName' => $items['winner']]) }}" class="btn btn-sm btn-success" data-bs-toggle="tooltip" data-bs-placement="top" title="Generate Certificate">Generate
                                    Certificate</a>
                            </div>
                        </div>
                </div>
                @endif
                @endforeach
            </div>
        </div>
    </div>
</div>
<div class="col-md-12">
    <div class="card">
        <div id="kt_app_toolbar" class="app-toolbar  pt-6 pb-2 ">
            <div id="kt_app_toolbar_container" class="container-fluid d-flex align-items-stretch ">
                <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
                    <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
                        <h1 class="page-heading d-flex flex-column justify-content-center text-gray-900 fw-bold fs-3 m-0">
                            Generate Certificate
                        </h1>
                    </div>

                </div>
            </div>
        </div>
        <div id="kt_app_content" class="app-content  flex-column-fluid">
            <div id="kt_app_content_container" class="app-container  container-fluid">
                <form class="form" action="{{ route('generate-certificate') }}">
                    <div class="row mt-2">
                        <label class="form-lebel required">Certificate Title</label>
                        <input type="text" name="certificate_title" id="certificate_title" value="{{old('certificate_title')}}" placeholder="Certificate Title" maxlength="25" class="form-control">
                        @if ($errors->has('certificate_title'))
                        <span class="text-danger">{{ $errors->first('certificate_title') }}</span>
                        @endif
                    </div>
                    <div class="row mt-2">
                        <label for="Lead" class="form-label required">Select Employee</label>
                        <select name="employee_id" id="employee_id" class="form-control select2">
                            <option value="">Select Employee...</option>
                            @foreach ($employeeList as $employee)
                            <option value="{{ $employee->id }}" @if(old("employee_id")==$employee->id) {{"selected"}} @endif>{{ $employee->name }}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('employee_id'))
                        <span class="text-danger">{{ $errors->first('employee_id') }}</span>
                        @endif
                    </div>
                    <div class="row mt-3">
                        <label for="Lead" class="form-label required">Certificate Description</label>
                        <textarea id="certificate_description" name="certificate_description" class="form-control" cols="10" rows="10" placeholder="Certificate Description" maxlength="150">{{old('certificate_description')}}</textarea>
                        @if ($errors->has('certificate_description'))
                        <span class="text-danger">{{ $errors->first('certificate_description') }}</span>
                        @endif
                    </div>
                    <div class="row mt-2 w-50">
                        <input type="submit" class="btn btn-primary btm-sm" value="Generate Certificate"></input>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>
@section('page')
<script>
    function viewAll(type) {
        window.location.href = '{{route("admin-show-winner-list")}}?type=' + type;
    }
</script>
@endsection