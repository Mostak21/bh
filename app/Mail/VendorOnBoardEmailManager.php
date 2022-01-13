<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class VendorOnBoardEmailManager extends Mailable
{
    use Queueable, SerializesModels;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $array;


    public function __construct($array)
    {
        $this->array = $array;
    }
    /**
     * Build the message.
     *
     * @return $this
     */
     public function build()
     {
		 
		 return $this->subject($this->array['subject'])
                     ->view($this->array['view']);

        // return $this->view($this->array['view'])
                     //->from($this->array['from'])
                     //->subject($this->array['subject'])
                     //->with([
                     //    'vendordata' => $this->array['vendordata']
                     //])
			// ;
     }
}
