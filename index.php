<?php
if(!isset($_COOKIE["money"]))
{	
	// cookie set:-----money
	$expire=time()+60*60*24*30;
	setcookie("money","100000.00",$expire);
	echo "<script language=\"JavaScript\"> window.location.reload();</script>";
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Jianming Chen">
    <link rel="icon" href="../../favicon.ico">

    <center><title>Simple Stock Exchange</title></center>
    <style type="text/css">
	
	#leftDiv {
	 background-color: #FFFFFF;
	 height: 250px;
	 width: 50%;
	 position: absolute;
	 top: 90px;
	 left: 0px;
	 overflow: auto;
	}
	#rightDiv {
	 background-color: #FFFFFF;
	 height: 800px;
	 width: 50%;
	 right:0px;
	 top: 90px;
	 position: absolute;
	 overflow: auto;
	}
	#leftDownDiv{
	 background-color: #FFFFFF;
	 height: 250px;
	 width: 50%;
	 position: absolute;
	 top: 340px;
	 left: 0px;
	 overflow: auto;
	}
	</style>
  	</head>

  <body>
	<p align =center><font size="22">Simple Stock Exchange </font></p>
	<hr>
	<div id="leftDiv">
	<center>
	Search Symbol
	<form name="searchForm" action="index.php" method="POST"  >
	<input type="text" name="symbol" placeholder = "Enter Symbol" id="searchText" align = right size ="50">
	<input type="submit" name="submit1" value="Search" id = "search" >
	</form>
	
	<?PHP
	
	if(isset($_POST['submit1'])){
	// if the submit button is pressed
	$symbol = $_POST['symbol'];
	$url = "http://data.benzinga.com/stock/";
	$url .=$symbol;
 	$content=file_get_contents($url);
	$obj= json_decode($content);
	if(isset($obj->{'status'})|| empty($symbol))
	{	
		// if the status exists; meaning its fail to retrieve the info.u
		echo "The symbol is empty or not found, please enter another symbol";
	}
	else
	{
		// the info is found .
		echo "<br />";
		echo "<br />";
		echo $obj->{'name'};
		echo "<br />";
		echo "<br />";
		echo "<table width=\"500\" border=\"1\" align=\"center\" style=\"border-collapse:collapse;table-layout:fixed;word-break:break-all;\">";
		echo "<tr>";
		echo "<th>Bid</th>";
		echo "<th>Ask</th>";
		echo "</tr>";
		echo "<tr>";
		echo "<th>".$obj->{'bid'}."</th>";
		echo "<th>".$obj->{'ask'}."</th>";
		echo "</tr>";
		echo "</table>";
	}
	}
	?>
	</div>
	</center>
	<center>
	<div id="leftDownDiv">
	<form name="form" action = "BuySell.php" method="POST"  >
	<input type="text" id="quan" name="quantitys"  placeholder = "Quantity" align = left size="50">
	<input type="submit" id="buy" name="method" value="Buy">
	<input type="submit" id="sell" name="method" value="Sell" >
	<input type="hidden" name="Buy" value=<?php echo $obj->{'ask'}; ?> >
	
	<input type="hidden" name="Sell" value=<?php echo $obj->{'bid'}; ?> >
	<input type="hidden" name="name" value="<?php echo $obj->{'name'}; ?>" >
	<input type="hidden" name="symbol" value="<?php echo $obj->{'symbol'}; ?> ">

	</form>
	</center>
	</div>
	<div id="rightDiv">
	<p>Your Cash:$  
	<?php 
	echo $_COOKIE["money"]; 
	?> 
	</p>
	<center><p>Current Portfolio</p></center>
	
	<table id = "myTable" width="90%" border="1" align="center" style="border-collapse:collapse;table-layout:fixed;word-break:break-all;">
	<tr>
	  <th>Company</th>
 	  <th>Quantity</th>
 	  <th>PricePaid</th>
	  <th> </th>
	</tr>
	<?php
	if(isset($_COOKIE["name"]))
	{
		// there is shares in your Portfolio
		$arrayNames=unserialize($_COOKIE['name']);
		$arrayQuantity=unserialize($_COOKIE['quantity']);
		$arrayPrice=unserialize($_COOKIE['price']);
		$arraySymbol=unserialize($_COOKIE['symbol']);
		$num = count($arrayNames);
		for($i=0 ; $i<$num ; ++$i)
		{
			// show one share
			echo "<center><tr>";
			echo "<td width=\"50%\" align=center>".$arrayNames[$i]."</td>";
			echo "<td width=\"17%\" align=center>".$arrayQuantity[$i]."</td>";
			echo "<td width=\"17%\" align=center>".$arrayPrice[$i]."</td>";
			echo "<td align=center><button type = \"button\" onclick = \"viewStock('".$arraySymbol[$i]."')\" >View Stock</button></td>";
			echo "</tr></center>";
		}
	}
	?>
	</table>
	</div>
<script language="javascript">
var searched= false;
function viewStock( symbol ){
	// imitate the fill and press
	searchForm.symbol.value= symbol;
	searchForm.submit1.click();
}
function judge()
{
	if(searchForm.symbol.value==" " || searchForm.symbol.value=="")
	{
		alert("Please enter the quantity first.");
		searched = false;
		
	}
	searched=true;
}
function judgeSearched()
{
	if(!searched)
	{
		alert("You haven't searched any symbol yet. Please search a symbol first.");
		return false;
	}
	var str = form.quantitys.value;
	if(str.trim()=="")
	{
		alert("Please enter the quantity first.");
		return false;
	}
	
}
</script>


  </body>
</html>

