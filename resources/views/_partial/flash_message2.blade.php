@if (Session::has('flash_message'))
    <div id="flmsg" class="col-lg-6 alert alert-success {{ Session::has('penting') ? 'alert-important' : '' }}">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        {{ Session::get('flash_message') }}
    </div>
@endif