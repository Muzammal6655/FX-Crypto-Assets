@extends('frontend.layouts.app')
@section('title', 'Profile')

@section('content')
<div class="login-page">
    <div class="container">
        <div class="login-signup-wrapper">
            <div class="row m-0">
                <div class="col-lg-5 left p-0 ">
                    <div class="content">
                        <div class="logo">
                            <a href="{{ url('/') }}"><img src="{{asset('images/logo.svg')}}" alt="" class="img-fluid" /></a>
                        </div>
                        <p>KYC</p>
                    </div>
                    <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                        <a class="nav-link mr-lg-0 mr-1" href="{{ url('/profile') }}">Profile</a>
                        <a class="nav-link active mr-lg-0 mr-1" href="{{ url('/documents') }}">KYC</a>
                        <a class="nav-link" href="{{ url('/otp-auth/info') }}">2FA</a>
                    </div>
                </div>
                <div class="col-lg-7 right">
                    <div class="content-wrapper">
                        <div class="tab-content" id="v-pills-tabContent">
                            <div class="tab-pane fade show active" id="v-pills-signup" role="tabpanel" aria-labelledby="v-pills-signup-tab">
                                <div class="form-wrapper">
                                    <h2>Documents Verification</h2>

                                    @if((!empty($user->passport) && $user->passport_status == 0) || (!empty($user->photo) && $user->photo_status == 0))
                                    <div class="alert alert-danger persist-alert" role="alert">
                                        Your uploaded documents are under verification. <br>{{settingValue('doc_approval_days')}} days are required to verify the documents.
                                    </div>
                                    @else
                                     <div class="alert alert-danger persist-alert" role="alert">
                                         Document size must be less then 10MB.
                                    </div>
                                    @endif

                                    @include('frontend.messages')
                                    <form class="text-left" id="documents-form" method="POST" action="{{ url('/documents') }}" enctype="multipart/form-data">
                                        {{ csrf_field() }}

                                        <p>
                                            <strong>Passport:</strong>
                                            @php $passport_status = $user->passport_status @endphp
                                            @if(!empty($user->passport))
                                        <div class="pull-right">
                                            Approval Status:
                                            @if($passport_status == 0)
                                            <span class="badge bg-warning">Pending</span>
                                            @elseif($passport_status == 1)
                                            <span class="badge bg-success">Approved</span>
                                            @elseif($passport_status == 2)
                                            <span class="badge bg-danger">Rejected</span>
                                            @endif
                                        </div>
                                        @endif
                                        </p>
                                        <div class="form-group">
                                            <div>
                                                @if (!empty($user->passport) && \File::exists(public_path() . '/storage/users/' . $user->id . '/documents/' . $user->passport))
                                               <!--  <a href="{{ checkImage(asset(env('PUBLIC_URL').'storage/users/' . $user->id . '/documents/' . $user->passport),'placeholder.png',$user->passport) }}" download="">Download</a> -->
                                                @else
                                                <strong><i>No passport provided</i></strong>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="form-group">
                                           <!--  <input type="file" class="form-control" name="passport" accept="image/jpeg,image/png"> -->
                                            <input type="file" class="form-control"    name="passport"  accept=".pdf,.jpeg,.png,.jpg,.image/"/>
                                        </div>

                                        <p>
                                            <strong>Photo:</strong>
                                            @php $photo_status = $user->photo_status;@endphp
                                            @if(!empty($user->photo))
                                        <div class="pull-right">
                                            Approval Status:
                                            @if($photo_status == 0)
                                            <span class="badge bg-warning">Pending</span>
                                            @elseif($photo_status == 1)
                                            <span class="badge bg-success">Approved</span>
                                            @elseif($photo_status == 2)
                                            <span class="badge bg-danger">Rejected</span>
                                            @endif
                                        </div>
                                        @endif
                                        </p>
                                        <div class="form-group">
                                            <div>
                                                @if (!empty($user->photo) && \File::exists(public_path() .'/storage/users/' . $user->id . '/documents/' . $user->photo))
                                               <!--  <a href="{{ checkImage(asset(env('PUBLIC_URL').'storage/users/' . $user->id . '/documents/' . $user->photo),'placeholder.png',$user->photo) }}" download="">Download</a> -->
                                                @else
                                                <strong><i>No photo provided</i></strong>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <input type="file" class="form-control" name="photo" accept=".pdf,.jpeg,.png,.jpg,.image/">
                                        </div>
                                        <p>
                                            <strong>AU DOC:</strong>
                                            @php $au_doc_verification_status = $user->au_doc_verification_status @endphp
                                            @if(!empty($user->au_doc_verification))
                                        <div class="pull-right">
                                            Approval Status:
                                            @if($au_doc_verification_status == 0)
                                            <span class="badge bg-warning">Pending</span>
                                            @elseif($au_doc_verification_status == 1)
                                            <span class="badge bg-success">Approved</span>
                                            @elseif($au_doc_verification_status == 2)
                                            <span class="badge bg-danger">Rejected</span>
                                            @endif
                                        </div>
                                        @endif
                                        </p>
                                        <div class="form-group">
                                            <div>
                                                @if (!empty($user->au_doc_verification) && \File::exists(public_path() . '/storage/users/' . $user->id . '/documents/' . $user->au_doc_verification))
                                                <!-- <a href="{{ checkImage(asset(env('PUBLIC_URL').'storage/users/' . $user->id . '/documents/' . $user->au_doc_verification),'placeholder.png',$user->au_doc_verification) }}" download="">Download</a> -->
                                                @else
                                                <strong><i>No AU Doc  provided</i></strong>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <input type="file" class="form-control" name="au_doc_verification" accept=".pdf,.jpeg,.png,.jpg,.image/">
                                        </div>
                                        @if(!empty($user->documents_rejection_reason))
                                        @if( $user->photo_status == 2 || $user->passport_status == 2 ||$user->au_doc_verification_status == 2 )
                                        <strong>Documents Rejection Reason:</strong>
                                        <p>{{ $user->documents_rejection_reason }}</p>
                                        @endif
                                        @endif
                                   
                                        <div class="bottom">
                                            <div class="btn-wrap">
                                                <button type="submit" class="btn-theme text-capitalize" id="btn-submit">Save
                                                    <span class="btn-theme__inner">
                                                        <span class="btn-theme__blobs">
                                                            <span class="btn-theme__blob"></span>
                                                            <span class="btn-theme__blob"></span>
                                                            <span class="btn-theme__blob"></span>
                                                            <span class="btn-theme__blob"></span>
                                                        </span>
                                                    </span>
                                                </button>
                                                <svg xmlns="http://www.w3.org/2000/svg" version="1.1" class="btn-svg">
                                                    <defs>
                                                        <filter id="goo">
                                                            <feGaussianBlur in="SourceGraphic" result="blur" stdDeviation="10"></feGaussianBlur>
                                                            <feColorMatrix in="blur" mode="matrix" values="1 0 0 0 0 0 1 0 0 0 0 0 1 0 0 0 0 0 21 -7" result="goo"></feColorMatrix>
                                                            <feBlend in2="goo" in="SourceGraphic" result="mix">
                                                            </feBlend>
                                                        </filter>
                                                    </defs>
                                                </svg>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
<script>
 $(document).ready(function () {
        $("#documents-form").submit(function (e) {
            $("#btn-submit").attr("disabled", true);
            return true;
        });
    });
</script>
@endsection