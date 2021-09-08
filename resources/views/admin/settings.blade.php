@extends('admin.layouts.app')

@section('title', 'Settings')
@section('sub-title', 'Site Settings')

@section('content')
<div class="main-content">
  <div class="content-heading clearfix">

    <ul class="breadcrumb">
      <li><a href="{{url('admin/dashboard')}}"><i class="fa fa-home"></i> Home</a></li>
      <li>Settings</li>
    </ul>
  </div>
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-8 col-md-offset-2">
        <div class="panel">
          <div class="panel-heading">
            <h3 class="panel-title">Settings</h3>
          </div>
          <div class="panel-body">
            @include('admin.messages')
            <form id="settings-form" class="form-horizontal label-left" action="{{url('admin/settings')}}"
              enctype="multipart/form-data" method="POST">
              {{ csrf_field() }}

              <div class="form-group">
                <label class="col-sm-3 control-label">Site Title</label>
                <div class="col-sm-9">
                  <input type="text" name="site_title" maxlength="200" class="form-control"
                    value="{{isset($settings['site_title']) ? $settings['site_title'] : ''}}" required>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-3 control-label">Contact Number</label>
                <div class="col-sm-9">
                  <input type="text" name="contact_number" maxlength="50" class="form-control"
                    value="{{isset($settings['contact_number']) ? $settings['contact_number'] : ''}}" required>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-3 control-label">Contact Email</label>
                <div class="col-sm-9">
                  <input type="email" name="contact_email" maxlength="200" class="form-control"
                    value="{{isset($settings['contact_email']) ? $settings['contact_email'] : ''}}" required>
                </div>
              </div>

              <h4 class="heading">Social Media Links</h4>

              <div class="form-group">
                <label class="col-sm-3 control-label">Facebook</label>
                <div class="col-sm-9">
                  <input type="url" name="facebook" maxlength="200" class="form-control"
                    value="{{isset($settings['facebook']) ? $settings['facebook'] : ''}}">
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-3 control-label">Twitter</label>
                <div class="col-sm-9">
                  <input type="url" name="twitter" maxlength="200" class="form-control"
                    value="{{isset($settings['twitter']) ? $settings['twitter'] : ''}}">
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-3 control-label">Google +</label>
                <div class="col-sm-9">
                  <input type="url" name="google_plus" maxlength="200" class="form-control"
                    value="{{isset($settings['google_plus']) ? $settings['google_plus'] : ''}}">
                </div>
              </div>

              <h4 class="heading">Wallet Address</h4>

              <div class="form-group">
                <label class="col-sm-3 control-label">Wallet Address</label>
                <div class="col-sm-9">
                  <input type="text" name="wallet_address" class="form-control"
                    value="{{isset($settings['wallet_address']) ? $settings['wallet_address'] : ''}}">
                </div>
              </div>

              <h4 class="heading">User Deletion Settings</h4>

              <div class="form-group">
                <label class="col-sm-3 control-label">Delete user after number of days</label>
                <div class="col-sm-9">
                  <input type="number" name="user_deletion_days" min="0" max="100" class="form-control"
                    value="{{isset($settings['user_deletion_days']) ? $settings['user_deletion_days'] : ''}}">
                </div>
              </div>

              <div class="text-right">
                <a href="{{url('admin')}}">
                  <button type="button" class="btn cancel btn-fullrounded">
                    <span>Cancel</span>
                  </button>
                </a>

                <button type="submit" class="btn btn-primary btn-fullrounded">
                  <span>Save</span>
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('js')
<script>
  $(function(){
        $('#settings-form').validate({
            errorElement: 'div',
            errorClass: 'help-block',
            focusInvalid: true,
            
            highlight: function (e) {
              $(e).closest('.form-group').removeClass('has-info').addClass('has-error');
            },
            success: function (e) {
              $(e).closest('.form-group').removeClass('has-error');
              $(e).remove();
            },
            errorPlacement: function (error, element) {
              if (element.is('input[type=checkbox]') || element.is('input[type=radio]')) {
                var controls = element.closest('div[class*="col-"]');
                if (controls.find(':checkbox,:radio').length > 1)
                        controls.append(error);
                else
                      error.insertAfter(element.nextAll('.lbl:eq(0)').eq(0));
              } 
              else if (element.is('.select2')) {
                error.insertAfter(element.siblings('[class*="select2-container"]:eq(0)'));
              } 
              else if (element.is('.chosen-select')) {
                error.insertAfter(element.siblings('[class*="chosen-container"]:eq(0)'));
              } 
              else
                    error.insertAfter(element);
            },
            invalidHandler: function (form,validator) {
              $('html, body').animate({
                scrollTop: $(validator.errorList[0].element).offset().top - scrollTopDifference
            }, 500);
            },
            submitHandler: function (form,validator) {
              if($(validator.errorList).length == 0)
              {
                document.getElementById("page-overlay").style.display = "block";
                return true;
              }
            }
        });
    });

</script>
@endsection