<div class="aiz-card-box border border-light rounded hov-shadow-md mt-1 mb-2 has-transition bg-white">
    <div class="position-relative">
			@php
			$soldout=1;
			$stock=\App\Models\ProductStock::where('product_id',$product->id)->first();
            //return $stock->qty;
			if(empty($stock)){
				$soldout=0;
		     }
		   if(!empty($stock)){
				
		          if($stock->qty==0){
					$soldout=0;
						}
		     }
		    
		@endphp
        @if ($product->unit_price > 5000 && ($product->unit_price-$product->discount)>= 5000)
      <!--  <div class="ribbon ribbon-top-right"><span>EMI</span></div> -->
        @endif 
		
		@if ($soldout==0)
		<div class="ribbon ribbon-top-right"><span style="background-color: black;"><small>Sold Out</small></span></div>
        @endif
        <a href="{{ route('product', $product->slug) }}" class="d-block">
            @if ($product->shipping_type == "free") <span class="tag-text">Free Delivery</span> @endif
            <img
                class="img-fit lazyload mx-auto h-140px h-md-210px"
                src="{{ static_asset('assets/img/placeholder.jpg') }}"
                data-src="{{ uploaded_asset($product->thumbnail_img) }}"
                alt="{{  $product->getTranslation('name')  }}"
                onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';"
            >
        </a>
        @if ($product->wholesale_product)
            <span class="absolute-bottom-left fs-11 text-white fw-600 px-2 lh-1-8" style="background-color: #455a64">
                {{ translate('Wholesale') }}
            </span>
        @endif
        <div class="absolute-top-right aiz-p-hov-icon">
            <a href="javascript:void(0)" onclick="addToWishList({{ $product->id }})" data-toggle="tooltip" data-title="{{ translate('Add to wishlist') }}" data-placement="left">
                <i class="la la-heart-o"></i>
            </a>
            <a href="javascript:void(0)" onclick="addToCompare({{ $product->id }})" data-toggle="tooltip" data-title="{{ translate('Add to compare') }}" data-placement="left">
                <i class="las la-sync"></i>
            </a>
           {{-- <a href="javascript:void(0)" onclick="showAddToCartModal({{ $product->id }})" data-toggle="tooltip" data-title="{{ translate('Add to cart') }}" data-placement="left">
                <i class="las la-shopping-cart"></i>
            </a> --}}
        </div>
    </div>
    <div class="p-md-3 p-2 text-left">
        <div class="fs-15">
            @if(home_base_price($product) != home_discounted_base_price($product))
                <del class="fw-600 opacity-50 mr-1">{{ home_base_price($product) }}</del>
            @endif
            <span class="fw-700 text-primary"> <a href="{{ route('product', $product->slug) }}">{{ home_discounted_base_price($product) }}</a></span>
            <span class="aiz-p-hov-icon" style="float:right;border-radius: 100px;">
                    <a href="javascript:void(0)" onclick="showAddToCartModal({{ $product->id }})" data-toggle="tooltip" data-title="{{ translate('Add to cart') }}" data-placement="left" style="font-size: x-large;  transform: none !important;">
                        <i class="las la-shopping-cart"></i>
                    </a>
                </span>
        </div>
        <div class="rating rating-sm mt-1">
            {{ renderStarRating($product->rating) }}
        </div>
		@if(!empty($product->brand->name))
				<a class="d-block text-reset" style="color: #8c8c8c !important; font-size: smaller;"> {{  $product->brand->name }}</a>
		@endif
        <h3 class="fw-600 fs-13 text-truncate-2 lh-1-4 mb-0 h-35px">
            <a href="{{ route('product', $product->slug) }}" class="d-block text-reset">{{  $product->getTranslation('name')  }}</a>
        </h3>
        @if (addon_is_activated('club_point'))
            <div class="rounded px-2 mt-2 bg-soft-secondary border-soft-secondary border">
                {{ translate('Club Point') }}:
                <span class="fw-700 float-right">{{ $product->earn_point }}</span>
            </div>
        @endif
    </div>
</div>
