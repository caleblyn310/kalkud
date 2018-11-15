<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class AlumniMail extends Mailable
{
    use Queueable, SerializesModels;
    protected $c = '';

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($content)
    {

        $this->c = $content;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $address = 'caleb@skkkbandung.or.id';
 
        $name = 'admin';
 
        $subject = 'Tiket Natal Bersama SMP SMA Alumni SKKK Bandung';
 
        $this->view('emails.email')->from($address, $name)->subject($subject)->attach(asset('storage/alumni/'.$this->c.'.pdf'),[
            'as' => 'e-Ticket Natal 12 December.pdf','mime' => 'application/pdf']);
        
        //$this->markdown('emails.alumnimail')->with('content',$this->content);

        return redirect('alumni');
    }
}
