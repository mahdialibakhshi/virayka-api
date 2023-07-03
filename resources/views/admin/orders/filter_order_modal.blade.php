<div class="modal fade" id="filter_order_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">بازه‌ی زمانی برای گزارش گیری را انتخاب نمایید</h5>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group mb-2">
                        <label> تاریخ شروع : </label>
                        <div class="input-group">
                            <div class="input-group-prepend order-2">
                                                    <span class="input-group-text" id="order_start">
                                                        <i class="fas fa-clock"></i>
                                                    </span>
                            </div>
                            <input type="text" class="form-control" id="order_start_input"
                                   name="date_on_sale_from"
                                   value="">
                        </div>
                        <p id="start_date_error" class="input-error-validation">انتخاب تاریخ شروع الزامی است</p>
                    </div>
                    <div class="form-group mb-2">
                        <label> تاریخ پایان : </label>
                        <div class="input-group">
                            <div class="input-group-prepend order-2">
                                                    <span class="input-group-text" id="order_end">
                                                        <i class="fas fa-clock"></i>
                                                    </span>
                            </div>
                            <input type="text" class="form-control" id="order_end_input"
                                   name="date_on_sale_to"
                                   value="">
                        </div>
                        <p id="end_date_error" class="input-error-validation">انتخاب تاریخ پایان الزامی است</p>
                    </div>
                    <div class="form-group">
                        <label> وضعیت سفارش : </label>
                        <select id="delivery_status" name="delivery_status" class="form-control form-control-sm">
                            <option value="0">
                               نمایش همه
                            </option>
                            @foreach($order_status as $item)
                                <option value="{{ $item->id }}">
                                    {{ $item->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">بستن</button>
                <button onclick="getOrderReport(today = null, thisWeek = null, thisMonth = null, lastMonth = null, paginate = null,1)" type="button" class="btn btn-primary">تایید</button>
            </div>
        </div>
    </div>
</div>
