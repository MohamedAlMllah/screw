<!-- Modal -->
<div class="modal fade" id="joinModal" tabindex="-1" aria-labelledby="joinModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="joinModalLabel">Password Confirmation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h3>Enter the game password</h3>
                <form id="formJoin" action="" method="POST">
                    @csrf
                    <div class="form-group text-center">
                        <input type="text" class="form-control @error('password') is-invalid @enderror" placeholder="Enter password" value="{{old('password')}}" name="password">
                        <button type="submit" class="btn btn-primary mt-3">Join</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>