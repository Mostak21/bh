<?php


namespace App\Http\Controllers;
use App\Mail\VendorOnBoardEmailManager;
use App\Mail\InvoiceEmailManager;
use App\User;
use Mail;
use Auth;
use Session;
use DB;
use Illuminate\Http\Request;


class VendorOnBoard extends Controller
{
    public function becameAVendor()
    {
        return view('vendor_on_board.vendorform');
    }

    public function getData(Request $formData)
    {

//        echo $formData->input('name');
//        echo $formData->input('phone');
//        echo $formData->input('email');
//        echo $formData->input('business-name');
//        echo $formData->input('business-address');
//        echo $formData->input('business-category');
//        echo $formData->input('social-link');
//        echo $formData->input('website');
		


        //$array['view'] = 'emails.vendorOnBoard';
        //$array['subject'] = translate('You have new vendor rewuest').' - '.$formData->input('business-name');;
        //$array['from'] = $formData->input('email');
		
        //$array['from'] = env('MAIL_FROM_ADDRESS');
        //$array['vendordata'] = $formData;

        //sends email to customer with the invoice pdf attached
       // if(env('MAIL_USERNAME') != null){
           // try {
               // Mail::to(User::where('user_type', 'admin')->first()->email)->queue(new VendorOnBoardEmailManager($array));
				//var_dump(User::where('user_type', 'admin')->first()->email);
			//	\Mail::to('xtniloy@gmail.com')->send(new VendorOnBoardEmailManager($array));
			//	echo "success";
           // } catch (\Exception $e) {
			//	echo "faild";
          //  }
        //}
		
		$details = [
			'subject' => 'NEW Vendor - '.$formData->input('business-name'),
			'view' => 'emails.vendorOnBoard',
			
            'name' => $formData->input('name'),
            'phone' => $formData->input('phone'),
			'email' => $formData->input('email'),
			'business-name' => $formData->input('business-name'),
			'business-address' => $formData->input('business-address'),
			'business-category' => $formData->input('business-category'),
			'social-link' => $formData->input('social-link'),
			'website' => $formData->input('website')
        ];
		try {
        	Mail::to(User::where('user_type', 'admin')->first()->email)->send(new VendorOnBoardEmailManager($details));
        	Mail::to($formData->input('email'))->send(new VendorOnBoardEmailManager($details));
			return view('vendor_on_board.vendorform');
		}
		catch (\Exception $e) {
			echo "error";
    	}
}
}
