<?php

function snail($n)
{
	$resultArray=[];
	$j=$n;
	for($i=$n;$i>=0;$i--)
	{
		 
		
		if($i == $n)
		{
			 
			$resultArray[]= str_repeat($n,5);
		}elseif ($i==0){
			$resultArray[]= $n."101".$n;
		}elseif($i <$n)	{
			 
			$resultArray[]= $n.str_repeat($i,3).$n;
		}
		 
			 
	}
	$bottomArray=$resultArray;
	array_pop($bottomArray);
	$bottomArray=array_reverse($bottomArray);
	 
	$resultArray=array_merge($resultArray,$bottomArray);
	
	return $resultArray;
}

echo "testing";
echo "<pre>";
print_r(snail(3));