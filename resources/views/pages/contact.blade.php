@extends('layouts.frontLayout.front_design')

@section('content')


<section>
    <div class="container">
        <div class="row">
            <div class="col-sm-3">
                @include('layouts.frontLayout.front_sidebar')
            </div>
            <div class="col-sm-9 padding-right">
                <div class="features_items">
                    <!--features_items-->
                    <h2 class="title text-center">Contact Us</h2>
                    @if(Session::has('flash_message_error'))
                    <div class="alert alert-error alert-block">
                        <button type="button" class="close" data-dismiss="alert">x</button>
                        <strong>{!! session('flash_message_error') !!} </strong>
                    </div>
                    @endif
                    @if(Session::has('flash_message_success'))
                    <div class="alert alert-success alert-block">
                        <button type="button" class="close" data-dismiss="alert">x</button>
                        <strong>{!! session('flash_message_success') !!} </strong>
                    </div>
                    @endif
                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    <div class="contact-form">
                        <div class="status alert alert-success" style="display: none"></div>
                        <form action="{{ url('page/contact') }}" method="post" id="main-contact-form"
                            class="contact-form row" name="contact-form">
                            {{ csrf_field() }}
                            <div class="form-group col-md-6">
                                <input type="text" name="name" class="form-control" required="required"
                                    placeholder="Name">
                            </div>
                            <div class="form-group col-md-6">
                                <input type="email" name="email" class="form-control" required="required"
                                    placeholder="Email">
                            </div>
                            <div class="form-group col-md-12">
                                <input type="text" name="subject" class="form-control" required="required"
                                    placeholder="Subject">
                            </div>
                            <div class="form-group col-md-12">
                                <textarea name="message" id="message" required="required" class="form-control" rows="8"
                                    placeholder="Your Message Here"></textarea>
                            </div>
                            <div class="form-group col-md-12">
                                <input type="submit" name="submit" class="btn btn-primary pull-right" value="Submit">
                            </div>
                        </form>
                    </div>

                </div>
                <!--features_items-->

            </div>
        </div>
    </div>
</section>
@endsection
