@extends('admin.layouts.setting')

@section('tabs')
<div class="row">
    <div class="col-md-12">
        @if(Session::has('alert'))
            <p class="alert
            {{ Session::get('alert-class', 'alert-danger') }}">{{Session::get('alert') }}</p>
        @endif
        <form action="{{ route('save_general_settings') }}" method="POST" class="row g-3" enctype="multipart/form-data">
           @csrf
           <div class="col-md-6">
                <label for="logo" class="form-label">Logo <span class="text-danger">*</span></label>
                <input class="form-control" type="file" id="logo" name="logo">
                @if ($errors->has('logo'))
                    <span class="text-danger">{{ $errors->first('logo') }}</span>
                @endif
            </div>
            <div class="col-md-6 mt-5">
                <?php $logo=URL::asset("settings/".$settings->logo); ?>
                    <input type="hidden" name="uploaded_logo" value="{{ $settings->logo }}">
                    <img id="preview-image-before-upload" src="{{ $logo }}"
                      alt="" style="max-height: 100px; width:100px;">
            </div>
            <div class="col-md-6">
                <label for="favicon" class="form-label">Favicon <span class="text-danger">*</span></label>
                <input class="form-control" type="file" id="favicon" name="favicon">
                @if ($errors->has('favicon'))
                    <span class="text-danger">{{ $errors->first('favicon') }}</span>
                @endif
            </div>
                <div class="col-md-6 mt-5">
                <?php $favicon=URL::asset("settings/".$settings->favicon); ?>
                    <input type="hidden" name="uploded_favicon" value="{{ $settings->favicon }}">
                    <img id="preview-image-before-upload-favicon" src="{{ $favicon }}"
                      alt="" style="max-height: 50px;">
                </div>
            <div class="col-12">
                <label for="SiteName" class="form-label">Company Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="site_name" id="SiteName" placeholder="Company Name" value="{{ $settings->site_name }}">
                @if ($errors->has('site_name'))
                    <span class="text-danger">{{ $errors->first('site_name') }}</span>
                @endif
            </div>
            <div class="col-12">
                <label for="SiteUrl" class="form-label">Site Url <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="site_url" id="SiteUrl" placeholder="Company Name" value="{{ $settings->site_url }}">
                @if ($errors->has('site_url'))
                    <span class="text-danger">{{ $errors->first('site_url') }}</span>
                @endif
            </div>
            <div class="col-12">
                <label for="dateFormat" class="form-label">Date Format <span class="text-danger">*</span></label>
                <select name="date_format" id="" class="form-control">
                    <option value="d/m/Y" @if($setting->date_format == 'd/m/Y') selected @endif>31/1/2023 (d/m/Y)</option>
                    <option value="m/d/Y" @if($setting->date_format == 'm/d/Y') selected @endif>1/31/2023 (m/d/Y)</option>
                    <option value="F d, Y" @if($setting->date_format == 'F d, Y') selected @endif>January 31, 2023 (F d, Y)</option>
                    <option value="d F, Y" @if($setting->date_format == 'd F, Y') selected @endif>31 January, 2023 (d F, Y)</option>
                    <option value="d/m/Y H::i:s" @if($setting->date_format == 'd/m/Y H::i:s') selected @endif>31/1/2023 12::40:00 (d/m/Y H::i:s)</option>
                     <option value="d-m-Y H::i:s" @if($setting->date_format == 'd-m-Y H::i:s') selected @endif>31-1-2023 12::40:00 (d/m/Y H::i:s)</option>
                </select>
            </div>
           <!--  <h6>Attendance Time</h6>
            <div class="col-3">
                <label for="">Hours</label>
                <input type="text" class="form-control" name="attendance_hours" value="{{$setting->attendance_hours}}">
            </div>
            <div class="col-3">
                <label for="">Minutes</label>
                <input type="text" class="form-control" name="attendance_minutes" value="{{$setting->attendance_minutes}}">
            </div> -->
            <div class="col-12">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('script')
<script>
    $(document).ready(function (e) {
        $(".timezone optgroup option").each(function(){
            if($(this).val() == "{{ $setting->timezone }}"){
                $(this).attr('selected','selected');
            }
        })
        $('#logo').change(function(){
        let reader = new FileReader();
        reader.onload = (e) => {
            $('#preview-image-before-upload').attr('src', e.target.result);
        }
        reader.readAsDataURL(this.files[0]);
        });

        $('#favicon').change(function(){
        let reader = new FileReader();
        reader.onload = (e) => {
            $('#preview-image-before-upload-favicon').attr('src', e.target.result);
        }
        reader.readAsDataURL(this.files[0]);
        });

    });
</script>
@endsection
