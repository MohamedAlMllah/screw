<!-- Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Delete Confirmation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h3>Are you sure you want to end this game?</h3>
                <h5>The game will end for all players</h5>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <form id="formDelete" action="" method="POST">
                    @csrf
                    <input id="method" type="hidden" name="_method" value="delete" />
                    <button type="submit" class="btn btn-danger">End game</button>
                </form>
            </div>
        </div>
    </div>
</div>