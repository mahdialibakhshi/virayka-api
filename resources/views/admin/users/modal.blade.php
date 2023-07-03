<div id="remove_modal" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="alert alert-danger">
                    با حذف کاربر,تمامی سفارشات,تراکنش‌ها و ... مربوط به این کاربر حذف خواهد شد.
                </div>
            </div>
            <div class="modal-body">
                <p>آیا از حذف این کاربر اطمینان دارید ؟</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">نه!</button>
                <button onclick="RemoveUser()" type="button" class="btn btn-success" data-dismiss="modal">حذف کن
                </button>

                <input id="user_id" type="hidden" value="">
            </div>
        </div>
    </div>
</div>
