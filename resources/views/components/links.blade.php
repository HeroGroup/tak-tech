<td>
    @if(isset($routeEdit))
        <a href="{{ $routeEdit }}" class="btn btn-info btn-circle btn-sm" data-toggle="tooltip" title="Edit">
            <i class="fas fa-pen"></i>
        </a>
        &nbsp;
    @endif
    @if(isset($routeDelete))
        <form id="destroy-form-{{$itemId}}" method="post" action="{{$routeDelete}}" style="display:none">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="_method" value="DELETE">
        </form>
        <a href="#" data-toggle="modal" data-target="#destroyModal" class="btn btn-danger btn-circle btn-sm" title="Delete">
            <i class="fas fa-trash"></i>
        </a>
        &nbsp;
    @endif
</td>

<!-- Destroy Modal-->
<div class="modal fade" id="destroyModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Delete this row?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">Bear in mind that deleting is irreversible!</div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                <a class="btn btn-danger" href="javascript:void(0)" onclick="destroy({{$itemId}})">Delete</a>
            </div>
        </div>
    </div>
</div>

<script>
    $(function () {
        $('[data-toggle="tooltip"]').tooltip();
    });

    function destroy(itemId) {
        document.getElementById('destroy-form-'+itemId).submit();
    }
</script>
