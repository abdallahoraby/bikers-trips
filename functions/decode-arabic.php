<?php

function decode($encode_str)
{
	$temp="";
	for ($i=0; $i <strlen($encode_str) ; $i++)
	{
		if ($i%2==0) {
			$temp.="%";
		}
		$temp.=$encode_str[$i];
	}

	return urldecode($temp);
}