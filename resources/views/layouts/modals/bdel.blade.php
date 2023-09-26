<!-- Modal -->
<div class="modal fade" id="bdelModal" tabindex="-1" aria-labelledby="bdelModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bdelModalLabel">تبديل</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h3>عايز تبدله مع انهي كارت ؟</h3>
                <form id="formBdel" action="" method="POST">
                    @csrf
                    <div class="form-group text-center">
                        <select class="form-select" aria-label="Default select example" name="order">
                            @foreach($user->hands as $key => $hand)
                            <option value="{{$key+1}}">{{$key+1}}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-success mt-3">بدل</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>