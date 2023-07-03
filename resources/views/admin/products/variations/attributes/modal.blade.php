<div id="remove_modal" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>تمامی محصولات مربوط به این رنگ حذف خواهد شد.
                    آیا از حذف این رنگ اطمینان دارید ؟</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">نه!</button>
                <button onclick="Remove()" type="button" class="btn btn-success" data-dismiss="modal">حذف کن
                </button>
                <input id="id" type="hidden" value="">
            </div>
        </div>
    </div>
</div>

<div id="remove_modal_attr" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>تمامی محصولات مرتبط با این ویژگی حذف خواهند شد.آیا از حذف این ویژگی اطمینان دارید ؟</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">نه!</button>
                <button onclick="RemoveAttr()" type="button" class="btn btn-success" data-dismiss="modal">حذف کن
                </button>
                <input id="attr_value" type="hidden" value="">
            </div>
        </div>
    </div>
</div>
