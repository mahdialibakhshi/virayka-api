<!-- add/edit form modal -->
<div class="modal fade" id="add_product_attribute" tabindex="-1" role="dialog" aria-labelledby="add_product_attribute"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="title">
                    مشخصات فنی / افزودن
                </h5>
            </div>
            <div class="modal-body">
                <div class="error" id="modal_error"></div>
                <div>
                    <label>مشخصه‌ی فنی:</label>
                    <select id="attribute_id" class="form-control form-control-sm mt-1" data-live-search="true">
                        <option value="" selected>انتخاب کنید</option>
                        @foreach($attributes as $attribute)
                            <option value="{{ $attribute->id }}">{{ $attribute->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div id="attribute_input_value" class="mt-2">
                    <label class="mt-2">مقدار :</label>
                    <select class="form-control form-control-sm" id="attribute_product_input">
                        <option value="">
                            انتخاب کنید
                        </option>
                    </select>
                </div>
                <div id="attribute_input_short_text" class="mt-2">
                    <label class="mt-2">اختصار :</label>
                    <input name="short_text" id="short_text" class="form-control form-control-sm">
                </div>
                <div id="attribute_input_priority" class="mt-2">
                    <label class="mt-2">اولویت نمایش :</label>
                    <input type="number" name="priority" id="priority" class="form-control form-control-sm">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">بستن</button>
                <button type="submit" class="btn btn-success" id="add_update_product_attributes_button">افزودن</button>
                <input type="hidden" name="methodId" id="methodId" value="">
            </div>
        </div>
    </div>
</div>
<!-- add/edit form modal end -->
