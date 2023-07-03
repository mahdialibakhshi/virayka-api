<!-- add/edit form modal -->
<div class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="updateModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="title">
                    توضیحات
                </h5>
            </div>
            <div class="modal-body">
                <textarea id="description" class="w-100"></textarea>
                <p id="error"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">بستن</button>
                <button type="submit" class="btn btn-success" id="updateDewscriptionButton">ویرایش</button>
                <input type="hidden" name="methodId" id="methodId" value="">
            </div>
        </div>
    </div>
</div>
<!-- add/edit form modal end -->
