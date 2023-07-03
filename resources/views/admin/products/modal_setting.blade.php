<div id="setting_modal" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <p>حالت نمایش محصولات در سایت :</p>
                <div class="toolbox-item toolbox-sort select-box text-dark d-flex align-center">
                    <?php
                    $setting=\App\Models\Setting::first();
                    $sort=$setting->product_sort;
                    ?>
                    <select  name="orderby" class="form-control" onchange="priority_show_active(this)">
                        <option {{ $sort==0 ? 'selected' : '' }} value="0">پیش فرض
                        </option>
                        <option {{ $sort==1 ? 'selected' : '' }} value="1">جدید ترین
                        </option>
                        <option {{ $sort==2 ? 'selected' : '' }} value="2">قدیمی ترین
                        </option>
                        <option {{ $sort==3 ? 'selected' : '' }} value="3">قیمت ،نزولی
                        </option>
                        <option {{ $sort==4 ? 'selected' : '' }} value="4">قیمت ،صعودی
                        </option>
                        <option {{ $sort==5 ? 'selected' : '' }} value="5">محبوب ترین ها
                        </option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">

            </div>
        </div>
    </div>
</div>
