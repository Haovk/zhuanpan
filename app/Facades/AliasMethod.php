<?php  
namespace App\Facades;

class AliasMethod  
{  
    private $length;  
    private $prob_arr;  
    private $alias;  
  
    // public function __construct ($pdf)  
    // {  
    //     $this->length = 0;  
    //     $this->prob_arr = $this->alias = array();  
    //     $this->_init($pdf);  
    // }  
    private function init($pdf)
    {  
        $this->length = 0;  
        $this->prob_arr = $this->alias = array();  
        $this->length = count($pdf);  
        if($this->length == 0)  
            die("pdf is empty");  
        if(sprintf("%01.4f",array_sum($pdf)) != 1.0000)
            die("pdf sum not equal 1, sum:".array_sum($pdf));  
  
        $small = $large = array();
		$average=1.0/$this->length;
        for ($i=0; $i < $this->length; $i++)
        {   
            $pdf[$i] *= $this->length;  
            if($pdf[$i] < $average)  
                $small[] = $i;  
            else  
                $large[] = $i;  
        }  
  
        while (count($small) != 0 && count($large) != 0)   
        {  
            $s_index = array_shift($small);  
            $l_index = array_shift($large);  
            $this->prob_arr[$s_index] = $pdf[$s_index]*$this->length;  
            $this->alias[$s_index] = $l_index;  
  
            $pdf[$l_index] += $pdf[$s_index]-$average;  
            if($pdf[$l_index] < $average)  
                $small[] = $l_index;  
            else  
                $large[] = $l_index;  
        }  
  
        while(!empty($small))  
            $this->prob_arr[array_shift($small)] = 1.0;
        while (!empty($large))  
            $this->prob_arr[array_shift($large)] = 1.0;  
    }  
    public function next_rand($pdf)  
    {  
        $this->init($pdf);
        $column = mt_rand(0, $this->length - 1);  
        return mt_rand() / mt_getrandmax() < $this->prob_arr[$column] ? $column : $this->alias[$column];  
    }  
}