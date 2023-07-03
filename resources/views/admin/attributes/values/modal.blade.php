<!-- add/edit form modal -->
<div class="modal fade" id="add_attribute_values" tabindex="-1" role="dialog" aria-labelledby="add_attribute_values"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="title">
                    افزودن
                </h5>
            </div>
            <form id="attributeForm" enctype="multipart/form-data">
                @csrf
            <div class="modal-body">
                <div class="error" id="modal_error"></div>
                <div>
                    <label>مقدار:</label>
                    <input class="form-control form-control-sm" id="attribute_value" name="attribute_value">
                    <input type="hidden" id="attribute_value_id" name="attribute_value_id">
                    <label class="mt-2" for="name">تصویر(50px*50px)</label>
                    <input class="form-control" id="image" name="image" type="file">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">بستن</button>
                <button type="submit" class="btn btn-success" id="add_update_attribute_values_button">افزودن</button>
            </div>
            </form>
        </div>
    </div>
</div>
<!-- add/edit form modal end -->
