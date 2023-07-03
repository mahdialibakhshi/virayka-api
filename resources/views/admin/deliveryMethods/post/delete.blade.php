<!-- add/edit form modal -->
<div class="modal fade" id="RemoveModal" tabindex="-1" role="dialog" aria-labelledby="userModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
                <div class="modal-body">
                    آیا از حذف این مورد اطمینان دارید ؟
                </div>
            <form method="post" action="{{ route('admin.delivery_method.delete') }}">
                @csrf
                @method('delete')
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" data-dismiss="modal">نه !</button>
                    <button type="submit" class="btn btn-danger" id="addButton">حذف</button>
                    <input type="hidden" name="Item" id="Item" value="">
                </div>
            </form>
        </div>
    </div>
</div>
<!-- add/edit form modal end -->
