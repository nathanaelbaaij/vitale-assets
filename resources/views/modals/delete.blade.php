<div class="modal modal-danger fade in" id="delete">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" aria-label="Close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">{{ ucfirst($name) }} verwijderen</h4>
            </div>
            <div class="modal-body">
                <p>Weet u zeker dat u dit wilt verwijderen?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Annuleren</button>

                <form action="{{ $action }}" method="POST" style="margin: 0 !important;">
                    @method('DELETE')
                    @csrf
                    <button type="submit" class="btn btn-outline">Definitief verwijderen</button>
                </form>

            </div>
        </div>
    </div>
</div>