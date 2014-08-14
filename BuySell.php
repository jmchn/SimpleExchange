<?php
	// This is all for cookies.
	$mtd=$_POST['method'];
	$quantity=(double)$_POST["quantitys"];
	$total = (double)$_COOKIE["money"];
	$expire=time()+60*60*24*30;
	echo "processing...";
	if($quantity<=0)
	{	
		// quantity cant be less than 1
		echo "<script language=\"JavaScript\"> alert(\"you need to buy at least 1\");history.back();</script>";
		exit;
	}
	if(empty($_POST["Buy"]))
	{
		// if you didn't search for anything the Buy tag will be empty
		echo "<script language=\"JavaScript\"> alert(\"You need to search for symbol first.\");history.back();</script>";
		exit;
	}
	if($mtd == "Buy")
	{
		// method for Buy
		$ask = (double) $_POST['Buy'];

		
		if($quantity * $ask > $total)
		{
			//pop_out 
			// error_handling
			echo "<script language=\"JavaScript\"> alert(\"you cannot buy this much\");history.back();</script>";
			exit;
		}
		else
		{
			
			if(!isset($_COOKIE["name"]))
			{
				// first time
				$name[]=$_POST['name'];
				$quantityArray[]=(string)$quantity;
				$price[]=$_POST['Buy'];
				$symbol[]=$_POST['symbol'];
				setcookie("name",serialize($name),$expire);
				setcookie("quantity",serialize($quantityArray),$expire);
				setcookie("price",serialize($price),$expire);
				setcookie("symbol",serialize($symbol),$expire);
			}
			else
			{
				
				$name= unserialize($_COOKIE['name']);
				$quantityArray =unserialize($_COOKIE['quantity']);
				$price =unserialize($_COOKIE['price']);
				$symbol=unserialize($_COOKIE['symbol']);
				$num = count($name);
				$found = False;
				for($i=0; $i<$num;++$i)
				{
					//looking for the same Name;
					
					if(strcmp($name[$i],$_POST['name'])==0)
					{
						$totalCost=$quantity * $_POST['Buy'] + $quantityArray[$i]*$price[$i];
						$quantityArray[$i] = $quantity+$quantityArray[$i];
						$price[$i]=$totalCost/$quantityArray[$i];
						$found = True;
						break;
					}
				}
				if(!$found){
					$name[]=$_POST['name'];
					$quantityArray[]=$quantity;
					$price[]=$_POST['Buy'];
					$symbol[]=$_POST['symbol'];
				}
				setcookie("name",serialize($name),$expire);
				setcookie("quantity",serialize($quantityArray),$expire);
				setcookie("price",serialize($price),$expire);
				setcookie("symbol",serialize($symbol),$expire);
			}
			setcookie("money",(string)($total-$quantity*$ask),$expire);
			echo "<script language=\"JavaScript\"> alert(\"Your trade is Successful!\");window.location.href=\"http://stormy-retreat-1206.herokuapp.com/\";</script>";
			exit;
		}
		
		
	}
	else
	{
		// the Sell case
		$bid =(double) $_POST['Sell'];
		if(!isset($_COOKIE["name"]))
		{
			echo "<script language=\"JavaScript\"> alert(\"you don't have anything to sell\");history.back();</script>";
			exit;
		}
		else
		{
			$name= unserialize($_COOKIE['name']);
			$quantityArray =unserialize($_COOKIE['quantity']);
			$price =unserialize($_COOKIE['price']);
			$symbol = unserialize($_COOKIE['symbol']);
			$num = count($name);
			$found = False;
			
			for($i=0;$i<$num;++$i)
			{	
				// looking for the share you gonna sell
				if(strcmp($name[$i],$_POST['name'])==0)
				{
					if((double)$quantityArray[$i]<$quantity)
					{
						echo "<script language=\"JavaScript\"> alert(\"you dont have that much to sell\");history.back();</script>";
						exit;
					}
					$quantityArray[$i] = $quantityArray[$i]-$quantity;
					if((double)$quantityArray[$i]==0)
					{
						// if the amount of share you hold is 0;
						array_splice($name,$i,1);
						array_splice($quantityArray,$i,1);
						array_splice($price,$i,1);
						array_splice($symbol,$i,1);
					}
					$found=True;
					break;
					
				}

			}
			if(!$found)
			{
			echo "<script language=\"JavaScript\"> alert(\"you dont have that share to sell\");history.back();</script>";
			exit;
			}
			setcookie("name",serialize($name),$expire);
			setcookie("quantity",serialize($quantityArray),$expire);
			setcookie("price",serialize($price),$expire);
			setcookie("money",(string)($total+$quantity*$bid),$expire);
			echo "<script language=\"JavaScript\"> alert(\"Your trade is Successful!\");window.location.href=\"http://stormy-retreat-1206.herokuapp.com/\";</script>";
			exit;
			
		}
		
	}
?>