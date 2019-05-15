@extends('layouts.app')

@section('styles')
<link href="{{ asset('css/select2.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="post-prev-title">
                <h3>Concerns</h3>
            </div>
            <hr class="mt-3">
        </div>
    </div>
    <div class="row mt-3 justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header text-white bg-primary">
                    <h5 class="text-oswald mb-0">Update Concern</h5>
                </div>
                <div class="card-body">
                    <form action="{{route('admin.concern.update', $concern->id)}}" method="post">
                        {{ csrf_field() }} {{method_field('PUT')}}

                        <input type="hidden" id="custId" name="concerns_id" value="{{$concern->id}}" >
        
                       <div class="md-form">
                             <select class="select-wrapper mdb-select" name="priority" id="priority" >
                                  <option value="" disabled selected>Select</option>
                                  <option value="level 1(within 24 hours)" {{ old('priority') == 'level 1(within 24 hours)' ? 'selected' : ''}}>Level 1(within 24 hours)</option>
                                  <option value="level 2(2-3 days)" {{ old('priority') == 'level 2(2-3 days)' ? 'selected' : ''}}>Level 2(2-3 days)</option>
                                  <option value="level 3(4 and above)" {{ old('priority') == 'level 3(4 and above)' ? 'selected' : ''}}>Level 3(4 and above)</option>      
                              </select>
                              <label>Priority Level<span class="red-asterisk">*</span></label>
                            </div>

                         <div class="md-form">
                             <select class="select-wrapper mdb-select" name="status" id="status">
                                  <option value="" disabled selected>Select</option>
                                  <option value="Ongoing" {{ old('sub_category') == 'Ongoing' ? 'selected' : ''}}>Ongoing</option>
                                  <option value="Resolved" {{ old('sub_category') == 'Resolved' ? 'selected' : ''}}>Resolved</option>
                                  <option value="Closed" {{ old('sub_category') == 'Closed' ? 'selected' : ''}}>Closed</option>        
                              </select>
                              <label>Status<span class="red-asterisk">*</span></label>
                        </div>
                        
                        <div class="md-form ">
                          <p class="select2Label mb-0 mt-3">Endorse to</p>
                            <select class="select-wrapper mdb-select" id="receiver2" name="receiver2" style="width:100% !important;">
                              <option value="" disabled selected>Select</option>
                                @foreach ($admins as $admin)
                                    <option value="{{ $admin->id }}" {{ $admin->id === old('admin') ? 'selected' : ''  }}>{{ $admin->name() }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="select2Label">Remarks</label>
                            <textarea type="text" id="remark" name="remark" rows="5" class="form-control rounded-0 {{$errors->has('remark') ? 'is-invalid' : ''}}">{{old('remark')}}</textarea>
                            @if ($errors->has('remark'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('remark') }}</strong>
                            </span>
                            @endif
                        </div>

                        <div class="form-group">
                          <label class="select2Label">Notes:</label>
                            <textarea type="text" id="comment" name="comment" rows="5" class="form-control rounded-0 {{$errors->has('comment') ? 'is-invalid' : ''}}">{{old('comment')}}</textarea>
                            @if ($errors->has('comment'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('comment') }}</strong>
                            </span>
                            @endif
                        </div>

                        <button type="submit" name="submit" class="btn btn-primary float-right mt-4"><i class="fa fa-pencil"></i> Update</button>
                    </form>

                   <!--  <div class="form-group">
                            <label class="select2Label">Add Notes</label>
                            <form id="comment-form" method="post" action="{{ route('admin.concern.update' , $concern->id) }}" >
                                {{ csrf_field() }}  {{method_field('PUT')}}
                                <textarea type="text" class="form-control" name="comment"></textarea>
                                <div class="row" style="padding: 0 10px 0 10px;">
                                  <div class="form-group">
                                      <input type="submit" class="btn btn-primary btn-lg" style="width: 100%" name="submit">
                                  </div>
                                </div>
                            </form>
                        </div> -->

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="{{ asset('js/select2.min.js') }}"></script>
<script>
    $('.mdb-select').material_select();
    $('.multiple-select').select2();

    $('.multiple-select').select2().val({!! json_encode(old('admins')) !!}).trigger('change');
  
    $('.datepicker').pickadate({
        max: new Date(),
        formatSubmit: 'yyyy-mm-dd',
        hiddenPrefix: 'formatted_',
        selectYears: 50
    });

</script>
@endsection