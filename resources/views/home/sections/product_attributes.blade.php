<div class="col-6">
    <ul class="list-group">
        @foreach($product_attributes_original_items as $product_attribute)
            @if($product_attribute->is_active==1)
                <li class="list-group-item">
                                                        <span>
                                                        <img src="{{ imageExist(env('ATTR_UPLOAD_PATH'),$product_attribute->attribute->image) }}">
                                                    </span>
                    {{ $product_attribute->attribute->name }}:
                    @php
                        $attribute_values=$product_attribute->attributeValues($product_attribute->value,$product_attribute->attribute_id);
                    @endphp
                    @if($attribute_values==null)
                        {{ $product_attribute->value }}
                    @else
                        {{ $attribute_values->name }}
                    @endif</li>
            @endif
        @endforeach
    </ul>
</div>
