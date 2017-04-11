<?php

namespace App\Jobs;

use App\Repositories\UserRepository;

class ExampleJob extends Job
{
	protected  $message;
	
    public function __construct( $message )
    {
        $this->message = $message;
    }

    public function handle(UserRepository $users)
    {
        echo '<br/>start<br/>';
        echo $this->message;
//         for ($i=0; $i<10000; $i++)
//         {
//         	echo $i . '<br/>';
//         }
//         sleep(5);
        
//         $results = $users->getAgeLargerThan(10);
//         var_dump($results);
        
        echo '<br/>end<br/>';
    }
}
