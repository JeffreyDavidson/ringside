@props(['method', 'action'])

<form method="POST" action="{!! $action !!}" {{ $attributes }}>

    @csrf
    @method($method)

    {{ $slot }}

    <div class="kt-portlet__foot">
        <div class="kt-form__actions">
            <button type="submit" class="btn btn-primary">Submit</button>
            <button type="reset" class="btn btn-secondary">Cancel</button>
        </div>
    </div>

</form>
