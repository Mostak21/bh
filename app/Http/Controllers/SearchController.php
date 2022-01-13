<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Search;
use App\Models\Product;
use App\Models\Category;
use App\Models\FlashDeal;
use App\Models\Brand;
use App\Models\Color;
use App\Models\Shop;
use App\Models\Attribute;
use App\Models\AttributeCategory;
use App\Utility\CategoryUtility;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class SearchController extends Controller
{
    public function index(Request $request, $category_id = null, $brand_id = null)
    {
        $query = $request->keyword;
        $sort_by = $request->sort_by;
        $min_price = $request->min_price;
        $max_price = $request->max_price;
        $seller_id = $request->seller_id;
        $attributes = Attribute::all();
        $selected_attribute_values = array();
        $colors = Color::all();
        $selected_color = null;
		$product1=0;

        $conditions = ['published' => 1];

        if($brand_id != null){
            $conditions = array_merge($conditions, ['brand_id' => $brand_id]);
        }elseif ($request->brand != null) {
            $brand_id = (Brand::where('slug', $request->brand)->first() != null) ? Brand::where('slug', $request->brand)->first()->id : null;
            $conditions = array_merge($conditions, ['brand_id' => $brand_id]);
        }

        if($seller_id != null){
            $conditions = array_merge($conditions, ['user_id' => Seller::findOrFail($seller_id)->user->id]);
        }

        $products = Product::where($conditions);
		

        if($category_id != null){
            $category_ids = CategoryUtility::children_ids($category_id);
            $category_ids[] = $category_id;
			
			
          $products->whereIn('category_id', $category_ids);

            //$attribute_ids = AttributeCategory::whereIn('category_id', $category_ids)->pluck('attribute_id')->toArray();
            //$attributes = Attribute::whereIn('id', $attribute_ids)->get();
			
			

        //Attribute Filter

        
        }
        else {
            // if ($query != null) {
            //     foreach (explode(' ', trim($query)) as $word) {
            //         $ids = Category::where('name', 'like', '%'.$word.'%')->pluck('id')->toArray();
            //         if (count($ids) > 0) {
            //             foreach ($ids as $id) {
            //                 $category_ids[] = $id;
            //                 array_merge($category_ids, CategoryUtility::children_ids($id));
            //             }
            //         }
            //     }
            //     $attribute_ids = AttributeCategory::whereIn('category_id', $category_ids)->pluck('attribute_id')->toArray();
            //     $attributes = Attribute::whereIn('id', $attribute_ids)->get();
            // }
        }
		
		
			


        if($min_price != null && $max_price != null){
            $products->where('unit_price', '>=', $min_price)->where('unit_price', '<=', $max_price);
        }

        if($query != null){
            $searchController = new SearchController;
            $searchController->store($request);

            $products->where(function ($q) use ($query){
                foreach (explode(' ', trim($query), 2) as $word) {
					 
                    $q->where('name', 'like', '%'.$word.'%')->orWhere('tags', 'like', '%'.$word.'%')->orWhereHas('product_translations', function($q) use ($word){
                        $q->where('name', 'like', '%'.$word.'%');
                    });
                }
            });
			
			$product1=Product::where('published', 1)->where('name', 'like', '%'.$query.'%')->get()->take(2);
        }
		
	/*	 if($query != null){
		$ip=$_SERVER['REMOTE_ADDR'];
		$hour=date("H");
		$day=date("j");
		$month=date("n");
		$ip=str_replace(".","",$ip);
		
		$seed=($ip+$hour+$day+$month);

        switch ($sort_by) {
            case 'newest':
                $products->sortByDesc('created_at');
                break;
            case 'oldest':
                $products->sortByAsc('created_at');
                break;
            case 'price-asc':
                $products->sortByAsc('unit_price');
                break;
            case 'price-desc':
                $products->sortByDesc('unit_price');
                break;
            default:
				//$products->all();
              $products->sortByDesc('created_at');
				
                break;
        }
		 
		 }
		
		 if(empty($query)){ */
		
		$ip=$_SERVER['REMOTE_ADDR'];
		$hour=date("H");
		$day=date("j");
		$month=date("n");
		$ip=str_replace(".","",$ip);
		
		$seed=($ip+$hour+$day+$month);

        switch ($sort_by) {
            case 'newest':
                $products->orderBy('created_at', 'desc');
                break;
            case 'oldest':
                $products->orderBy('created_at', 'asc');
                break;
            case 'price-asc':
                $products->orderBy('unit_price', 'asc');
                break;
            case 'price-desc':
                $products->orderBy('unit_price', 'desc');
                break;
            default:
				//$products->orderBy('unit_price', 'desc');
                $products->inRandomOrder($seed)->get();
                break;
        }
		
	
		
		
	

        if($request->has('color')){
            $str = '"'.$request->color.'"';
            $products->where('colors', 'like', '%'.$str.'%');
            $selected_color = $request->color;
        }

        if($request->has('selected_attribute_values')){
            $selected_attribute_values = $request->selected_attribute_values;
            foreach ($selected_attribute_values as $key => $value) {
                $str = '"'.$value.'"';
                $products->where('choice_options', 'like', '%'.$str.'%');
            }
        }

		$non_paginate_products = filter_products($products)->get();
		$attributes = array();
        foreach ($non_paginate_products as $key => $product) {
            if($product->attributes != null && is_array(json_decode($product->attributes))){
                foreach (json_decode($product->attributes) as $key => $value) {
                    $flag = false;
                    $pos = 0;
                    foreach ($attributes as $key => $attribute) {
                        if($attribute['id'] == $value){
                            $flag = true;
                            $pos = $key;
                            break;
                        }
                    }
                    if(!$flag){
                        $item['id'] = $value;
                        $item['values'] = array();
                        foreach (json_decode($product->choice_options) as $key => $choice_option) {
                            if($choice_option->attribute_id == $value){
                                $item['values'] = $choice_option->values;
                                break;
                            }
                        }
                        array_push($attributes, $item);
                    }
                    else {
                        foreach (json_decode($product->choice_options) as $key => $choice_option) {
                            if($choice_option->attribute_id == $value){
                                foreach ($choice_option->values as $key => $value) {
                                    if(!in_array($value, $attributes[$pos]['values'])){
                                        array_push($attributes[$pos]['values'], $value);
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        $selected_attributes = array();

        foreach ($attributes as $key => $attribute) {
            if($request->has('attribute_'.$attribute['id'])){
                foreach ($request['attribute_'.$attribute['id']] as $key => $value) {
                    $str = '"'.$value.'"';
                    $products = $products->where('choice_options', 'like', '%'.$str.'%');
                }

                $item['id'] = $attribute['id'];
                $item['values'] = $request['attribute_'.$attribute['id']];
                array_push($selected_attributes, $item);
            }
        }
			
		
		
        $products = filter_products($products)->with('taxes')->paginate(24)->appends(request()->query());

        return view('frontend.product_listing', compact('products','product1', 'query', 'category_id', 'brand_id', 'sort_by', 'seller_id','min_price', 'max_price', 'attributes', 'selected_attribute_values', 'colors', 'selected_color'));
    }

   public function listing(Request $request)
    {
        return $this->index($request);
    }

    public function listingByCategory(Request $request, $category_slug)
    {
        $category = Category::where('slug', $category_slug)->first();
        if ($category != null) {
            return $this->index($request, $category->id);
        }
        abort(404);
    }

    public function listingByBrand(Request $request, $brand_slug)
    {
        $brand = Brand::where('slug', $brand_slug)->first();
        if ($brand != null) {
            return $this->index($request, null, $brand->id);
        }
        abort(404);
    }

    //Suggestional Search
    public function ajax_search(Request $request)
    {
        $keywords = array();
        $query = $request->search;
		
     $products = Product::where('published', 1)->where('name', 'like', '%'.$query.'%')->get()->take(5);
       foreach ($products as $key => $product) {
            foreach (explode(',',$product->tags) as $key => $tag) {
                if(stripos($tag, $query) !== false){
                    if(sizeof($keywords) > 2){
                        break;
                    }
                    else{
                       if(!in_array(strtolower($tag), $keywords)){
                            array_push($keywords, strtolower($tag));
                       }
                    }
                }
            }
        } 
		
           $products = filter_products(Product::query()); 
 	       $products1 = Product::where('published', 1)->where('name', 'like', '%'.$query.'%')->get()->take(2);

		   $products = $products->where('published', 1)
                        ->where(function ($q) use ($query){
                            foreach (explode(' ', trim($query),2) as $word) {
                                $q->where('name', 'like', '%'.$word.'%')->orWhere('tags', 'like', '%'.$word.'%')->orWhereHas('product_translations', function($q) use ($word){
                                    $q->where('name', 'like', '%'.$word.'%');
                                });
                            }
                        })
                    ->get()->take(3);
		
        $categories = Category::where('name', 'like', '%'.$query.'%')->get()->take(3);

        $shops = Shop::whereIn('user_id', verified_sellers_id())->where('name', 'like', '%'.$query.'%')->get()->take(3);

        if(sizeof($keywords)>0 || sizeof($categories)>0 || sizeof($products)>0 || sizeof($shops) >0){
            return view('frontend.partials.search_content', compact('products', 'products1', 'categories', 'keywords', 'shops'));
        }
        return '0';
    }

     /* Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $search = Search::where('query', $request->keyword)->first();
        if($search != null){
            $search->count = $search->count + 1;
            $search->save();
        }
        else{
            $search = new Search;
            $search->query = $request->keyword;
            $search->save();
        }
    }
}
