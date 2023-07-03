<div id="remove_modal" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <p>آیا از حذف این  مقاله اطمینان دارید ؟</p>
            </div>
            <div class="modal-footer">
                <form action="{{ route('admin.article.destroy') }}"  method="POST">
                    @csrf
                <button type="button" class="btn btn-danger" data-dismiss="modal">نه!</button>
                <button type="submit" class="btn btn-success" >حذف کن
                </button>
                <input id="id" name="id" type="hidden" value="">
                </form>
            </div>
        </div>
    </div>
</div>
