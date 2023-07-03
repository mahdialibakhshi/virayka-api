<div id="remove_modal" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    با حذف این سفارش,تمام جزئیات سفارش و تراکنش مربوط به آن حذف خواهد شد.
                </div>
                <p>آیا از حذف این سفارش اطمینان دارید ؟</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">نه!</button>
                <button onclick="RemoveOrder()" type="button" class="btn btn-success" data-dismiss="modal">حذف کن
                </button>

                <input id="order_id" type="hidden" value="">
            </div>
        </div>
    </div>
</div>
