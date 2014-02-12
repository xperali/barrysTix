<?php
session_start();


$uri = $_SERVER['REQUEST_URI'];
if(strpos($uri,"+")!== false)
{
	$new_uri = str_replace('+', '-', $uri);
	
	header('Location: ' . $new_uri);
	
	exit;
}
//error_reporting(E_ALL);
//ini_set('display_errors', '1'); 
 
if($_GET['utm_source']=="ppc")
{
	$_SESSION["ppc"]="ppc";	
	setcookie("c_pcc", "ppc", time()+60*60*24*7);
}
elseif($_COOKIE["c_pcc"]=="ppc")
{
	$_SESSION["ppc"]="ppc1";	
}

function microtime_float() {
	return array_sum(explode(' ', microtime()));
}

$temps_debut_page=$temps_fin_page="";
/*$temps_debut_page = microtime_float();
$temps_debut_page1 = microtime_float();*/
 
 
function in_stopwords($sql)
{
	$arr_sw=array( "kid", "a's", "able", "about", "above", "according", "accordingly", "across", "actually", "after", "afterwards", "again", "against", "ain't", "all", "allow", "allows", "almost", "alone", "along", "already", "also", "although", "always", "am", "among", "amongst", "an", "and", "another", "any", "anybody", "anyhow", "anyone", "anything", "anyway", "anyways", "anywhere", "apart", "appear", "appreciate", "appropriate", "are", "aren't", "around", "as", "aside", "ask", "asking", "associated", "at", "available", "away", "awfully", "be", "became", "because", "become", "becomes", "becoming", "been", "before", "beforehand", "behind", "being", "believe", "below", "beside", "besides", "best", "better", "between", "beyond", "both", "brief", "but", "by", "c'mon", "c's", "came", "can", "can't", "cannot", "cant", "cause", "causes", "certain", "certainly", "changes", "clearly", "co", "com", "come", "comes", "concerning", "consequently", "consider", "considering", "contain", "containing", "contains", "corresponding", "could", "couldn't", "course", "currently", "definitely", "described", "despite", "did", "didn't", "different", "do", "does", "doesn't", "doing", "don't", "done", "down", "downwards", "during", "each", "edu", "eg", "eight", "either", "else", "elsewhere", "enough", "entirely", "especially", "et", "etc", "even", "ever", "every", "everybody", "everyone", "everything", "everywhere", "ex", "exactly", "example", "except", "far", "few", "fifth", "first", "five", "followed", "following", "follows", "for", "former", "formerly", "forth", "four", "from", "further", "furthermore", "get", "gets", "getting", "given", "gives", "go", "goes", "going", "gone", "got", "gotten", "greetings", "had", "hadn't", "happens", "hardly", "has", "hasn't", "have", "haven't", "having", "he", "he's", "hello", "help", "hence", "her", "here", "here's", "hereafter", "hereby", "herein", "hereupon", "hers", "herself", "hi", "him", "himself", "his", "hither", "hopefully", "how", "howbeit", "however", "i'd", "i'll", "i'm", "i've", "ie", "if", "ignored", "immediate", "in", "inasmuch", "inc", "indeed", "indicate", "indicated", "indicates", "inner", "insofar", "instead", "into", "inward", "is", "isn't", "it", "it'd", "it'll", "it's", "its", "itself", "just", "keep", "keeps", "kept", "know", "knows", "known", "last", "lately", "later", "latter", "latterly", "least", "less", "lest", "let", "let's", "like", "liked", "likely", "little", "look", "looking", "looks", "ltd", "mainly", "many", "may", "maybe", "me", "mean", "meanwhile", "merely", "might", "more", "moreover", "most", "mostly", "much", "must", "my", "myself", "name", "namely", "nd", "near", "nearly", "necessary", "need", "needs", "neither", "never", "nevertheless", "new", "next", "nine", "no", "nobody", "non", "none", "noone", "nor", "normally", "not", "nothing", "novel", "now", "nowhere", "obviously", "of", "off", "often", "oh", "ok", "okay", "old", "on", "once", "one", "ones", "only", "onto", "or", "other", "others", "otherwise", "ought", "our", "ours", "ourselves", "out", "outside", "over", "overall", "own", "particular", "particularly", "per", "perhaps", "placed", "please", "plus", "possible", "presumably", "probably", "provides", "que", "quite", "qv", "rather", "rd", "re", "really", "reasonably", "regarding", "regardless", "regards", "relatively", "respectively", "right", "said", "same", "saw", "say", "saying", "says", "second", "secondly", "see", "seeing", "seem", "seemed", "seeming", "seems", "seen", "self", "selves", "sensible", "sent", "serious", "seriously", "seven", "several", "shall", "she", "should", "shouldn't", "since", "six", "so", "some", "somebody", "somehow", "someone", "something", "sometime", "sometimes", "somewhat", "somewhere", "soon", "sorry", "specified", "specify", "specifying", "still", "sub", "such", "sup", "sure", "t's", "take", "taken", "tell", "tends", "th", "than", "thank", "thanks", "thanx", "that", "that's", "thats", "the", "their", "theirs", "them", "themselves", "then", "thence", "there", "there's", "thereafter", "thereby", "therefore", "therein", "theres", "thereupon", "these", "they", "they'd", "they'll", "they're", "they've", "think", "third", "this", "thorough", "thoroughly", "those", "though", "three", "through", "throughout", "thru", "thus", "to", "together", "too", "took", "toward", "towards", "tried", "tries", "truly", "try", "trying", "twice", "two", "un", "under", "unfortunately", "unless", "unlikely", "until", "unto", "up", "upon", "us", "use", "used", "useful", "uses", "using", "usually", "value", "various", "very", "via", "viz", "vs", "want", "wants", "was", "wasn't", "way", "we", "we'd", "we'll", "we're", "we've", "welcome", "well", "went", "were", "weren't", "what", "what's", "whatever", "when", "whence", "whenever", "where", "where's", "whereafter", "whereas", "whereby", "wherein", "whereupon", "wherever", "whether", "which", "while", "whither", "who", "who's", "whoever", "whole", "whom", "whose", "why", "will", "willing", "wish", "with", "within", "without", "won't", "wonder", "would", "would", "wouldn't", "yes", "yet", "you", "you'd", "you'll", "you're", "you've", "your", "yours", "yourself", "yourselves", "zero");
	
	$sql=str_replace(" +","/",$sql);
	$sql=str_replace("+","",$sql);
	$arrs=explode("/",$sql);
	//print_r($arrs);
	
	$l3=0;
	
	for($i=0;$i<count($arrs);$i++)
	{
		if(in_array(strtolower($arrs[$i]),$arr_sw))
			return true;
			
		if(strlen($arrs[$i])<=3)
			$l3++;
	}
	
	if($l3==count($arrs))
		return true;
		
	return false;
}


include("function_link.php");

///////Referal ID/////
include("shopping.php"); 
$ref=sql_filter($_GET["ref"]);


//error_reporting(E_ALL);
if($ref!="")
	$_SESSION["referer"]=$ref;
///////End Referal ID///

include("connection.php"); 
include("table.php");
include("GeoLocation.php"); 
include("functions_tickets.php");

$ip = $_SERVER['REMOTE_ADDR'];


$max_grouping=200;

$pag_post_param="";

$sel_sql="SELECT l.Event,l.EventID,l.Date,l.Time,l.Venue,l.VenueID,l.Headliner,l.HeadlinerID,v.City,v.State,n.note,'1' as ordr ";
$fr_sql=" FROM venue_list_active v,list_active l";
$wh_sql=" WHERE l.VenueID=v.VenueID ";


$expevent=array("sec championship");

/////// Suspended /////
$evt_name=sql_filter($_GET["EID"]);
$search_name=$_GET["search"];
$search_name=sql_filter($search_name);
//echo "***".$search_name."***";

if(strtolower($search_name)=='justin bieber')
{
    echo"<script>location.href='http://www.barrystickets.com/concert-tickets/justin-bieber/';</script>";
    exit;
}

if($search_name!='')
{
	$sql_srh="SELECT kid FROM keyword_search WHERE keyword LIKE '".mysql_real_escape_string($search_name)."'";
	if(mysql_num_rows(mysql_query($sql_srh))>0)
	{
		$sql_sri="UPDATE keyword_search SET ckey = ckey+1,date='".date('Y-m-d H:i:s')."' WHERE keyword LIKE '".mysql_real_escape_string($search_name)."'";
		mysql_query($sql_sri);
	}
	else
	{
		$sql_sru="INSERT INTO keyword_search (`keyword`, `ckey`, `date`) VALUES ('".mysql_real_escape_string($search_name)."','1','".date('Y-m-d H:i:s')."')";
		mysql_query($sql_sru);
	}
}

if(strpos(strtolower($evt_name),"rugby") || strpos(strtolower($evt_name),"world cup rugby") || strpos(strtolower($evt_name),"international rugby"))
	$evt_name="";

if(strpos(strtolower($search_name),"rugby") || strpos(strtolower($search_name),"world cup rugby") || strpos(strtolower($search_name),"international rugby"))
	$search_name="";

///// end Suspended //////

$ch_param=0;

//////// search by event name ////////////
if($evt_name!='')
{
	$ch_param=1;
	$event2=$evt_name;
	$evt_name=mysql_real_escape_string($evt_name);
	$evt_name=addslashes($evt_name);
	$evt_name=explode("(",$evt_name);
	$evt_name=trim($evt_name[0]);
	$evt_name=str_replace("-"," ",$evt_name);
	//$evt_name=str_replace("_","-",$evt_name);
	
	$search_evt=str_replace(","," +",$evt_name);
	$search_evt=str_replace(" "," +",$search_evt);
	$search_evt=str_replace("_"," +",$search_evt);
	$search_evt="+".$search_evt;
	if($_GET["Searchby"]!='venue' && $_POST["Searchby"]!='venue')
	{
		if ($_GET['game']=='away'  || $_GET['game']=='')
		{
			if(strpos($evt_name,"."))
			{
				$wh_sql.=" AND (l.Event LIKE '%".mysql_real_escape_string($evt_name)."%' OR l.Headliner LIKE '%".mysql_real_escape_string($evt_name)."%') ";
			}
			else
			{
				if(in_stopwords($search_evt) || in_array(strtolower($event2),$expevent))
				{
					$search_evtl=str_replace("+","%",$search_evt);
					$search_evtl=str_replace(" %","%",$search_evtl);
					$search_evtl=str_replace("%%","%",$search_evtl);
					$wh_sql.=" AND (l.Event LIKE '".$search_evtl."%' OR l.Headliner LIKE '".$search_evtl."%' OR l.Search_mix LIKE '".$search_evtl."%') ";	
				}
				else
					$wh_sql.=" AND ((MATCH (l.Event) AGAINST ('".$search_evt."' IN BOOLEAN MODE)) > 0.2 OR (MATCH (l.Search_mix) AGAINST ('".$search_evt."' IN BOOLEAN MODE)) > 0.2) ";
			}
		}
		elseif ($_GET['game']=='home')
		{
			if(strpos($evt_name,"."))
			{
				$wh_sql.=" AND l.Headliner LIKE '%".mysql_real_escape_string($evt_name)."%' ";
			}
			else
			{
				if(in_stopwords($search_evt))
				{
					$search_evtl=str_replace("+","%",$search_evt);
					$search_evtl=str_replace(" %","%",$search_evtl);
					$search_evtl=str_replace("%%","%",$search_evtl);
					$wh_sql.=" AND l.Headliner LIKE '".$search_evtl."%' ";	
				}
				else
					$wh_sql.=" AND (MATCH (l.Headliner) AGAINST ('".$search_evt."' IN BOOLEAN MODE)) > 0.2 ";
			}
		}
	}
}

//////// search by SEARCH ////////////

if($search_name!='')
{
	$search_name=str_replace("tickets","",strtolower($search_name));
	
	if(strtolower($search_name)=="galaxy" || strtolower($search_name)=="la galaxy" || strtolower($search_name)=="real madrid" || strtolower($search_name)=="everton" || strtolower($search_name)=="everton fc" || strtolower($search_name)=="juventus")
	{
		$sel_sql=str_replace(",'1' as ordr",",'0' as ordr",$sel_sql).$fr_sql." LEFT JOIN events_note n ON n.event_id=l.EventID AND n.status !='deleted' WHERE l.VenueID=v.VenueID AND l.EventID='1378356' UNION ALL "
				.$sel_sql;	
	}
	
	if(strtolower($search_name)=="sweet 16")
		$search_name="NCAA Tournament – Mens Regionals West";
	if(strtolower($search_name)=="rosebowl")
		$search_name="Rose Bowl";
	
	$ch_param=1;
	$event2=$search_name;
	if(strlen($search_name)<=3 || strpos($search_name,"."))
	{
		if(strtolower($search_name)=="bz")
			$wh_sql.=" AND (l.Event LIKE '%bz%' OR l.Event LIKE '%b\'z%') ";
		elseif(strtolower($search_name)=="grc")
			$wh_sql.=" AND (l.Event LIKE '%Rally%Cross%') ";
		else
			$wh_sql.=" AND l.Search_mix LIKE '%".str_replace("theatre","theat%",str_replace("theater","theat%",strtolower($search_name)))."%' ";
	}
	elseif(strtolower($search_name)=="rally cross")
	{
		$wh_sql.=" AND (l.Event LIKE '%Rally%Cross%') ";
	}
	else
	{
		$search_name=str_replace(","," +",$search_name);
		$search_name=str_replace("-"," +",$search_name);
		$search_name=str_replace(" "," +",$search_name);
		$search_name=mysql_real_escape_string($search_name);
		
		$search_name="+".$search_name;
		
		if(in_stopwords($search_name))
		{
			$search_namel=str_replace("+","%",$search_name);
			$search_namel=str_replace(" %","%",$search_namel);
			$search_namel=str_replace("%%","%",$search_namel);
			$wh_sql.=" AND l.Search_mix LIKE '".str_replace("theatre","theat%",str_replace("theater","theat%",strtolower($search_namel)))."%' ";	
		}
		else
			$wh_sql.=" AND (MATCH (l.Search_mix) AGAINST ('".str_replace("theatre","theat*",str_replace("theater","theat*",strtolower($search_name)))."' IN BOOLEAN MODE)) > 0.2 ";
	}
}
	
///////// search by headliner id ////////////////////
if(($_GET["HeadlinerID"]!='' && is_numeric($_GET["HeadlinerID"])) || ($_GET["HID"]!='' && is_numeric($_GET["HID"])))
{
	$ch_param=1;
	$HID= sql_filter($_GET["HID"]);
	if($_GET["HeadlinerID"]!='')
		$hd_id=mysql_real_escape_string($_GET["HeadlinerID"]);
	if($_GET["HID"]!='')
		$hd_id=mysql_real_escape_string($_GET["HID"]);
		
	$wh_sql.=" AND l.HeadlinerID='".$hd_id."' ";
        
}

///////// search by state //////////////////
if($_GET["ByState"]!='')
{
	$getstate = sql_filter($_GET["ByState"]);
	$getstate = mysql_real_escape_string($getstate);
}
if($_POST["searchstate"]!='')
{
	if($pag_post_param=="")
		$pag_post_param.="?searchstate=".$_POST["searchstate"];
	else
		$pag_post_param.="&searchstate=".$_POST["searchstate"];
		
	$getstate = sql_filter($_POST["searchstate"]);
	$getstate = mysql_real_escape_string($getstate);
}
if($_GET["searchstate"]!='')
{
	$getstate = sql_filter($_GET["searchstate"]);
	$getstate = mysql_real_escape_string($getstate);
}
if($getstate!='')
{
	$ch_param=1;
	$event2=$getstate;
	$wh_sql.=" AND v.State='".$getstate."' ";
}

///////// search by city ////////////////
if ($_GET["ByCity"]!='')
{
	$getcity = sql_filter($_GET["ByCity"]);
	$getcity=str_replace("-"," ",$getcity);
	$getcity=str_replace("_","-",$getcity);
	$getcity = mysql_real_escape_string($getcity);
}
if ($_POST["searchcity"]!='')
{
	if($pag_post_param=="")
		$pag_post_param.="?searchcity=".$_POST["searchcity"];
	else
		$pag_post_param.="&searchcity=".$_POST["searchcity"];
		
	$getcity = sql_filter($_POST["searchcity"]);
	$getcity=str_replace("-"," ",$getcity);
	$getcity=str_replace("_","-",$getcity);
	$getcity = mysql_real_escape_string($getcity);
}
if ($_GET["searchcity"]!='')
{
	$getcity = sql_filter($_GET["searchcity"]);
	$getcity=str_replace("-"," ",$getcity);
	$getcity=str_replace("_","-",$getcity);
	$getcity = mysql_real_escape_string($getcity);
}
if ($_POST["ByZipCode"]!='' && !is_numeric($_POST["ByZipCode"]))
{
	if($pag_post_param=="")
		$pag_post_param.="?ByZipCode=".$_POST["ByZipCode"];
	else
		$pag_post_param.="&ByZipCode=".$_POST["ByZipCode"];
		
	$getcity = sql_filter($_POST["ByZipCode"]);
	$getcity=str_replace("-"," ",$getcity);
	$getcity=str_replace("_","-",$getcity);
	$getcity = mysql_real_escape_string($getcity);
}
if ($_GET["ByZipCode"]!='' && !is_numeric($_GET["ByZipCode"]))
{
	$getcity = sql_filter($_GET["ByZipCode"]);
	$getcity=str_replace("-"," ",$getcity);
	$getcity=str_replace("_","-",$getcity);
	$getcity = mysql_real_escape_string($getcity);
}
if($getcity!='')
{
	$ch_param=1;
	$event2=$getcity;
	$wh_sql.=" AND v.City='".$getcity."' ";
}

/////////// search by categorie ///////////
if ($_GET["cat"]!='')
{
	$ch_param=1;
	$getcat = sql_filter($_GET["cat"]);
	$getcat = mysql_real_escape_string($getcat);
	if(is_numeric($getcat))
		$wh_sql.=" AND l.CategoryID='".$getcat."' ";
	else
	{
		$event2=$_GET["cat"];
		$fr_sql=str_replace("FROM","FROM categories c,",$fr_sql);
		$wh_sql.=" AND c.CategoryType='".$getcat."' AND c.CategoryID=l.CategoryID ";
	}
}

///////// search by venue id ////////////////
if($_GET["VenueID"]!='' && is_numeric($_GET["VenueID"]))
{
	$ch_param=1;
	$vn_id = sql_filter($_GET["VenueID"]);
	$vn_id = mysql_real_escape_string($vn_id);
	$wh_sql.=" AND l.VenueID='".$vn_id."' ";
}

//////// search by venue name //////////////
if( $_GET["ByVenue"]!='')
{
	$getvn = sql_filter($_GET["ByVenue"]);
	$getvn = mysql_real_escape_string($getvn);
}
if( $_POST["searchzip"]!='')
{
	if($pag_post_param=="")
		$pag_post_param.="?searchzip=".$_POST["searchzip"];
	else
		$pag_post_param.="&searchzip=".$_POST["searchzip"];
		
	$getvn = sql_filter($_POST["searchzip"]);
	$getvn = mysql_real_escape_string($getvn);
}
if( $_GET["searchzip"]!='')
{
	$getvn = sql_filter($_GET["searchzip"]);
	$getvn = mysql_real_escape_string($getvn);
}
if( $evt_name!='' && ($_GET["Searchby"]=='venue' || $_POST["Searchby"]=='venue'))
{
	$ch_param=1;
	
	if(in_stopwords($search_evt))
	{
		$search_evtl=str_replace("+","%",$search_evt);
		$search_evtl=str_replace(" %","%",$search_evtl);
		$search_evtl=str_replace("%%","%",$search_evtl);
		$wh_sql.=" AND l.Venue LIKE '".$search_evtl."%' ";	
	}
	else
		$wh_sql.=" AND (MATCH (l.Venue) AGAINST ('".$search_evt."' IN BOOLEAN MODE)) > 0.2 ";
}
if($getvn!='')
{
	$ch_param=1;
	$event2=$getvn;
	$search_vn=str_replace(","," +",$getvn);
	$search_vn=str_replace(" "," +",$search_vn);
	$search_vn=str_replace("_"," +",$search_vn);
	$search_vn=str_replace("-"," +",$search_vn);
	$search_vn="+".$search_vn;
	
	if(in_stopwords($search_vn))
	{
		$search_vnl=str_replace("+","%",$search_vn);
		$search_vnl=str_replace(" %","%",$search_vnl);
		$search_vnl=str_replace("%%","%",$search_vnl);
		$wh_sql.=" AND l.Venue LIKE '".$search_vnl."%' ";	
	}
	else
		$wh_sql.=" AND (MATCH (l.Venue) AGAINST ('".$search_vn."' IN BOOLEAN MODE)) > 0.2 ";
}

//////// search by zip code /////////
if ($_GET["ByZipCode"]!='' && is_numeric($_GET["ByZipCode"]))
{
	$getzip= sql_filter($_GET["ByZipCode"]);
	$getzip = mysql_real_escape_string($getzip);
}
if ($_POST["ByZipCode"]!='' && is_numeric($_POST["ByZipCode"]))
{
	if($pag_post_param=="")
		$pag_post_param.="?ByZipCode=".$_POST["ByZipCode"];
	else
		$pag_post_param.="&ByZipCode=".$_POST["ByZipCode"];
		
	$getzip= sql_filter($_POST["ByZipCode"]);
	$getzip = mysql_real_escape_string($getzip);
}
if($getzip!='')
{
	$Miles= sql_filter($_GET["ByMiles"]);
	if($Miles=='' || !is_numeric($Miles))
		$Miles=50;
	$venue_arr = GetVenuesFromZipRadius($Miles, $getzip);
	if(count($venue_arr)>0)
	{
		$ch_param=1;
		$keys=array_keys($venue_arr);
		$i=0;
		$wh_sql.=" AND ( ";
		foreach($keys as $vid_venue)
		{
			$v_name= $vid_venue;	
			
			$wh_sql.=" l.Venue LIKE '%".$v_name."%' ";
				 
			if($i+1<count($keys))
				$wh_sql.=" OR ";
			$i++; 
		}
				  
		$wh_sql.=" ) ";
	}
}

///////// search by date ///////////
if ($_GET['month']!='' && $_GET['year']!='')
{
	$d1=date('Y-m-d',mktime(0,0,0,$_GET['month'],1,$_GET['year'])); 
	$d2=date('Y-m-d',mktime(0,0,0,$_GET['month']+1,1,$_GET['year'])); 
}
if ($_POST['daterange']!='')
{
	if($pag_post_param=="")
		$pag_post_param.="?daterange=".$_POST["daterange"];
	else
		$pag_post_param.="&daterange=".$_POST["daterange"];
		
	list($d1,$d2)=split(",",$_POST['daterange']);
}
if ($_GET['daterange']!='')
{
	list($d1,$d2)=split(",",$_GET['daterange']);
}
if($d1!='' && $d2!='')
{
	$ch_param=1;
	$wh_sql.=" AND Date >='".mysql_real_escape_string($d1)."' AND Date <='".mysql_real_escape_string($d2)."' ";
}
elseif($d1!='')
{
	$ch_param=1;
	$wh_sql.=" AND Date ='".mysql_real_escape_string($d1)."' ";
}
else
	$wh_sql.=" AND Date >='".date("Y-m-d")."' ";

if($ch_param==0)
	$ntic= " AND 1!=1 ";

if(strpos(strtolower($wh_sql), 'parking')===false)
     $wh_sql.=" AND l.Event NOT LIKE '%parking%' ";

$event2=str_replace("-"," ",$event2);
$event2=str_replace("+"," ",$event2);
$event2=str_replace("\'","'",$event2);


?>
<?php $site_url = "http://www.barrystickets.com/"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<script type="text/javascript">var _sf_startpt=(new Date()).getTime()</script>

    <title><? echo $event2; ?> Tickets - Find <? echo $event; ?> Tickets Information</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta name="description" content="Find <? echo $event2; ?> Tickets information to purchase the best <? echo $event2; ?> tickets" />
<meta name="keywords" content="<? echo $event2; ?> Tickets, Cheap Tickets, <?  echo $event2; ?>, Tickets, Sport Tickets, Concert Tickets, Theater Tickets" />
<META name="robots" content="INDEX,FOLLOW" />

<? include "include/header_scripts.php"; ?>
<script type="text/javascript">  _kmq.push(['record', 'Search']);</script>
<style type="text/css">
<!--

.txt-search-result {
	font-size: 13px;
	font-family: Verdana;
	font-weight: bold;
}
.link-sport
{
	color: #0099FF;
	text-decoration: none;
	font-size: 12px
}
.link-theatre
{
	color: #FF6600;
	text-decoration: none;
font-size: 12px
}
.link-concert
{
	color: #00CC00;
	text-decoration: none;
font-size: 12px
}
.link-city
{
	color: #9966CC;
	text-decoration: none;
font-size: 12px
}
.link-venue
{
	color: #0000FF;
	text-decoration: none;
font-size: 12px
}

-->
</style>

<SCRIPT LANGUAGE='JavaScript'> function openWindow(url) { popupWin = window.open(url, 'openWin', "width=400, height=250, scrollbars=yes"); } </SCRIPT> 
<SCRIPT language="JavaScript" type="text/javascript">
<!--
function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}

function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_swapImgRestore() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}

function MM_swapImage() { //v3.0
  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}
//-->
</script>



<script language="JavaScript" src="http://www.barrystickets.com/affiliate-tracking.js"></script>

<script src='http://connect.facebook.net/en_US/all.js'></script>
<div id="fb-root"></div>

<script type="text/javascript">

window.fbAsyncInit = function() {

	FB.init({

	  appId   : '258277237545874', // should be replaced with your Facebook Application ID

	  status  : true, // check login status

	  cookie  : true, // enable cookies to allow the server to access the session
	  
	  oauth   : true,

	  xfbml   : true // parse XFBML

	});

};

</script>

<script type="text/javascript">

function streampublish_dialogs_g(eventname,event_id,venuename,date,evtlink)//_name,_link,_picture,_description
{	
	//alert('feed');
	FB.ui(
	{
		method: 'feed',//stream.publish //feed //oauth //apprequests

		name: 'I\'m going to see '+eventname+' – tickets still available on BarrysTickets.com',

		link: evtlink,

		picture: 'http://www.barrystickets.com/fb_sdk/fb.jpg',

		description: eventname+' at '+venuename+' on '+date
		

		},

		function(response) {

		    /*if (response && response.post_id) {

				alert('Post was published with post_id:' + response.post_id);

			} else {

				alert('Post was not published.');

			}*/

		}
	);
}

function streampublish_dialogs_m(eventname,event_id,venuename,date,evtlink)//_name,_link,_picture,_description
{
	
	FB.ui(
	{
		method: 'feed',//stream.publish

		name: 'I might go and see '+eventname+', who wants to come?',

		link: evtlink,

		picture: 'http://www.barrystickets.com/fb_sdk/fb.jpg',

		description: eventname+' at '+venuename+' on '+date

		},

		function(response) {

		   /* if (response && response.post_id) {

				alert('Post was published with post_id:' + response.post_id);

			} else {

				alert('Post was not published.');

			}*/

		}
	);
}

</script>

<script>

function ajax_get(fichier) {

	if(window.XMLHttpRequest) // FIREFOX

		xhr_object = new XMLHttpRequest();

	else if(window.ActiveXObject) // IE

		xhr_object = new ActiveXObject("Microsoft.XMLHTTP");

	else

		return(false);

	xhr_object.open("GET", fichier, false);

	xhr_object.send(null);

	if(xhr_object.readyState == 4) return(xhr_object.responseText);

	else return(false);

}

function fb_bnt(bfb,event_id,eventname,venuename,date,evtlink) {

	//alert(bfb+','+event_id+','+eventname+','+venuename+','+date);
	//alert(eventname);

	fichier='http://www.barrystickets.com/fb_sdk/fb_script.php?fbtype='+bfb+'&event_id='+event_id+'&eventname='+eventname+'&p=t';
	
	var content=ajax_get(fichier);

	//alert(content);
	//alert(content.indexOf('window.opener.streampublish_dialogs'));
	
	if(content.indexOf('streampublish_dialogs_g')!=-1)
	{
		streampublish_dialogs_g(eventname,event_id,venuename,date,evtlink);
	}
	else if(content.indexOf('streampublish_dialogs_m')!=-1)
	{
		streampublish_dialogs_m(eventname,event_id,venuename,date,evtlink);
	}
	else
	{
		winRef=window.open (content, 'FaceBookConnect', config='height=300, width=400, toolbar=no, menubar=no, scrollbars=no, resizable=no, location=no, directories=no, status=no');

	}
}
</script>
<script type="text/javascript">
function fshow(id)
{
	//alert('s'+id);
	document.getElementById('ftr'+id).style.visibility="visible";
	document.getElementById('ftr'+id).style.display="";	
}
function fhide(id)
{
	//alert('h'+id);
	document.getElementById('ftr'+id).style.visibility="hidden";
	document.getElementById('ftr'+id).style.display="none";
}
</script>

</head>

<body onload="MM_preloadImages('http://www.barrystickets.com/images/44.gif');">
<?php include_once "include/header.php"; ?>
<?	
$tickets_content="";
if($HID!="")
{
?>
<script type="text/javascript">
pr_page_id='<? echo $HID; ?>';
pr_write_review='/powerreviews/wrapper.php?pageId=<? echo $HID; ?>';
pr_read_review='/powerreviews/product.php?id=<? echo $HID; ?>';
snippet(document);
</script>
	
<?
}


////// pagination /////////////
$numberOfEventPerPage = 50; 

if (isset($_GET['page']))
{
	$page = mysql_real_escape_string(sql_filter($_GET['page']));
	if(!is_numeric($page))
		$page = 1;
}
else 
	$page = 1; 

$tic_sql_count="SELECT COUNT(l.EventID) ticnbr ".$fr_sql." LEFT JOIN events_note n ON n.event_id=l.EventID AND n.status !='deleted' ".$wh_sql." ".$ntic." ";

//echo "<!--***".$tic_sql_count."***-->";
$tic_result = mysql_query($tic_sql_count) or die(mysql_error());
$tic_nbr_row=mysql_fetch_array($tic_result);
$tic_nbr=$tic_nbr_row['ticnbr'];

$numberOfPages  = ceil($tic_nbr / $numberOfEventPerPage);
$firstEvent = ($page - 1) * $numberOfEventPerPage;
				
$tic_query=$sel_sql." ".$fr_sql." LEFT JOIN events_note n ON n.event_id=l.EventID AND n.status !='deleted' ".$wh_sql." ".$ntic." ORDER BY ordr, Date,l.EventID LIMIT $firstEvent,  $numberOfEventPerPage";
//echo "***".$tic_query."*** ";
//echo "<!--**$tic_query-->";
$noticketlisting=0;

$tic_result = mysql_query($tic_query) or die(mysql_error());

//echo "---".$tic_nbr ."---";
if($tic_nbr == 0)
	$noticketlisting=1;

?>
	
<div align="center">
 <table width="968" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td width="158" align="left" valign="top">
						<table width="158"  border="0" cellspacing="0" cellpadding="0">
						  <tr>
							<td style="padding-right:5px ">
							<div id="leftside_filter">
							<?php 
							if($noticketlisting==0)
							{
								$sql_fil=$tic_query;
								$sql_fil=split("LIMIT",$sql_fil);
								$sql_fil=$sql_fil[0];
								//echo "***".$sql_fil."***";
								include_once "include/leftside_filter.php"; 
								
							}
							else
							{
								include_once "include/leftside_tickets.php"; 
							}
							
							?>
							</div>
							</td>
						  </tr>
						  <tr>
							<td><?php include_once "include/leftside_event_tickets.php"; ?></td>
						  </tr>
						</table>

           				
                        </td>
           	<td id="eventside" align="left" valign="top">
                        	<div align="left"><img src="images/roundtop_shadow.gif" alt="" width="26" height="6"/></div>
                            	<div id="eside" align="left">
               					<table width="780" border="0" cellpadding="0" cellspacing="0">
										<tr>
                                            <td width="603" align="left" valign="top">
											
											<table width="100%"  border="0" cellspacing="0" cellpadding="0">
											  <tr>
												<td>
												
												
												<?
												$mRadius=100;
												//63.149.234.142
												//$_SERVER['REMOTE_ADDR']
												$sql="SELECT *
												FROM `ip_group_city`
												WHERE `ip_start` <= INET_ATON( '".$_SERVER['REMOTE_ADDR']."' )
												ORDER BY `ip_start` DESC
												LIMIT 1";
												$query = mysql_query($sql);
												$resultf=mysql_fetch_array($query);
												$city=$resultf['city'];
												//$city_short=strtoupper($city);
												$ZipCode=$resultf['zipcode'];
												$geocity=0;
												if($city!='')
												{
													$sql="SELECT *
													FROM `venue_info`
													WHERE `city` = '".$city."'
													LIMIT 1";
													$query = mysql_query($sql);
													$result=mysql_fetch_array($query);
													$custLatitude=$result['latitude'];
													$custLongitude=$result['longitude'];
													if($custLatitude=='' || $custLongitude=='')
													{
														$geocity=1;
														$sql="SELECT *
														FROM `geo_loc`
														WHERE `zip_name` = '".$city."'
														LIMIT 1";
														$query = mysql_query($sql);
														$result=mysql_fetch_array($query);
														$custLatitude=$result['latitude'];
														$custLongitude=$result['longitude'];
													}
												}
												if(($custLatitude=='' || $custLongitude=='') && $ZipCode!='')
												{
													$sql="SELECT *
													FROM `venue_info`
													WHERE `zip` = '".$ZipCode."'
													LIMIT 1";
													$query = mysql_query($sql);
													$result=mysql_fetch_array($query);
													$custLatitude=$result['latitude'];
													$custLongitude=$result['longitude'];
													if($custLatitude=='' || $custLongitude=='')
													{
														$geocity=1;
														$sql="SELECT *
														FROM `geo_loc`
														WHERE `zip` = '".$ZipCode."'
														LIMIT 1";
														$query = mysql_query($sql);
														$result=mysql_fetch_array($query);
														$custLatitude=$result['latitude'];
														$custLongitude=$result['longitude'];
													}
												}
												$ipcity=0;
												if($custLatitude=='' || $custLongitude=='')
												{
													$custLatitude=$resultf['latitude'];
													$custLongitude=$resultf['longitude'];
													$ipcity=1;
												}
												
												//echo "***".$custLatitude."***".$custLongitude."***";
												list($LowLatitude, $HighLatitude, $LowLongitude, $HighLongitude) = GetMileRadius($custLatitude, $custLongitude, $mRadius);
												if($ipcity==0)
												{
													$VenueSql = "SELECT  distinct VenueID,longitude,latitude FROM venue_info,venue_list_active WHERE venue_list_active.State = venue_info.state and venue_info.city = venue_list_active.City  and  latitude <= $HighLatitude AND latitude >= $LowLatitude AND longitude >= $LowLongitude AND longitude <= $HighLongitude";
													if($geocity==1)
														$VenueSql = "SELECT  distinct VenueID,longitude,latitude FROM geo_loc,venue_list_active WHERE venue_list_active.State = geo_loc.state_abrev  and zip_name = venue_list_active.City  and  latitude <= $HighLatitude AND latitude >= $LowLatitude AND longitude >= $LowLongitude AND longitude <= $HighLongitude";
												}
												else
												{
													if($city!='')
														$VenueSql = "SELECT  distinct VenueID FROM venue_list_active WHERE City ='".$city."'";
													else
														$VenueSql = "SELECT  VenueID FROM venue_list_active WHERE 1!=1";
													//$VenueSql = "SELECT  distinct VenueID,longitude,latitude FROM ip_group_city,venue_list_active WHERE ip_group_city.city = venue_list_active.City  and  latitude <= $HighLatitude AND latitude >= $LowLatitude AND longitude >= $LowLongitude AND longitude <= $HighLongitude";
												}
												//echo "***".$VenueSql."***";
												if (! $VenueResult = mysql_query($VenueSql)) 
												{
													return FALSE;
												} 
												else 
												{	
													
													$VenueArray = array();
													while ($row = mysql_fetch_array($VenueResult)) 
													{
														$VenueDistance = GetDistance( $row['latitude'], $row['longitude'], $custLatitude, $custLongitude, "M");
														if($VenueDistance <= $mRadius && !in_array($row['VenueID'],$VenueArray))
															$VenueArray[$row['VenueID']] = $row['VenueID'];
													}
													//print_r($VenueArray);
													//return $VenueArray;
												}
												//print_r($VenueArray);
												
												$evt_neer=0;
												$neer_ct="";
												$neer_ct_3="";
												$neer_top=3;
												
												if(count($VenueArray)>0)
												{
													for($e=0;$e < count($event_total_arr);$e++)
													{
														if(in_array($event_total_arr[$e]['VenueID'],$VenueArray))
														{
															$oclick=' onmouseover="fshow(\''.$evt_neer.'\')" onmouseout="fhide(\''.$evt_neer.'\')" '; 
															
															if(fmod($evt_neer,2)==0)	
																$neer_ct.='<tr height="35" class="vevent ewhite" '.$oclick.' >';
															else
																$neer_ct.='<tr height="35" class="vevent egrey" '.$oclick.' >';
															
															$event_link=explode("(",$event_total_arr[$e]['Event']);
															$event_link=trim($event_link[0]);
															
															$event_link= str_replace("-", "_",$event_link);
															$event_link= str_replace(" ", "-",$event_link);
															
															//$neer_ct.='<td><A href="tickets.php?EID='.$event_link.'" class="event"><b class="summary" title="'.$event_total_arr[$e]['Event'].'"> '.$event_total_arr[$e]['Event'].'</b></A><br><span style="color:#006600 ">'.$event_total_arr[$e]['note'].'</span></td>';
															
															$evt_link=mk_evt_link($event_total_arr[$e]['Event'],$event_total_arr[$e]['EventID'],$event_total_arr[$e]['City'],$event_total_arr[$e]['Date']);
															//$evt_link=str_replace(":","-",$evt_link);
															$evt_link=urlencode($evt_link);
															
															$neer_ct.='<td><A href="'.$evt_link.'" class="event"><b class="summary" title="'.$event_total_arr[$e]['Event'].'"> '.$event_total_arr[$e]['Event'].'</b></A><br><span style="color:#006600 ">'.$event_total_arr[$e]['note'].'</span></td>';
															
															$dar = datecall($event_total_arr[$e]['Date']);
															$day = strtotime($event_total_arr[$e]['Date']);
															$day=date('l', $day);
															
															if($dar[0]!="2022")
															{
																$neer_ct.='<td align="center"><abbr class="dtstart" title="'.date("Y-m-d\TH:i:s",strtotime($event_total_arr[$e]['Date']." ".$event_total_arr[$e]['Time'])).'"> <b>'.$dar[1].' '.$dar[2].', '.$dar[0].'</b><br>'.$day.' - '.$event_total_arr[$e]['Time'].'</abbr></td>  ';
															}
															else	
															{
													
																$neer_ct.='<td align="center"><abbr class="dtstart" title="TBA" ><b>TBA</b></abbr></td>'; 
															}
															
															$venue_link=str_replace("-", "_",$event_total_arr[$e]['Venue']);	
															$venue_link=str_replace(" ", "-",$venue_link);	
															$city_link=str_replace("-","_",$event_total_arr[$e]['City']);
															$city_link=str_replace(" ","-",$city_link);
															$state_link=str_replace("-","_",$event_total_arr[$e]['State']);
															$state_link=str_replace(" ","-",$state_link);
															
															$neer_ct.='<td class="location vcard"><B><A href="tickets.php?ByVenue='.$venue_link.'" class="fn org venue" >'.$event_total_arr[$e]['Venue'].'</A></B><BR><span class="adr"><A href="tickets.php?ByCity='.$city_link.'" class="venue"><span class="locality">'.$event_total_arr[$e]['City'].'</span>, </A><A href="tickets.php?ByState='.$state_link.'" class="venue"><span class="region">'.$event_total_arr[$e]['State'].'</span></A></span></td> ';
																														
															$neer_ct.='<td  height="35" ><a href="'.$evt_link.'" class="url" ><img src="http://www.barrystickets.com/images/event/view_tickets.gif" alt="View tickets"  border="0" id="view-tickets-'.$evt_neer.'" /></a></td></tr>';
															
															$sty=' style="display:none; visibility:hidden;"';
		
															if(fmod($evt_neer,2)==0)	
																$neer_ct.= '<tr height="35" id="ftr'.$evt_neer.'" class="vevent ewhite" align="center" valign="middle" '.$sty.' '.$oclick.' >';
															else
																$neer_ct.= '<tr height="35" id="ftr'.$evt_neer.'" class="vevent egrey" align="center" valign="middle" '.$sty.' '.$oclick.' >';
															
															$neer_ct.= "<td colspan='40'>
																	<img src='fb_sdk/images/fblg.png' style='padding-bottom:3px'>&nbsp;&nbsp;
																	<input type=\"image\" src='fb_sdk/images/fbbtn.png' name=\"ImGoing\" onclick='fb_bnt(\"1\",\"".$event_total_arr[$e]['EventID']."\",\"".$event_total_arr[$e]['Event']."\",\"".$event_total_arr[$e]['Venue']."\",\"".date("D d M Y",strtotime($event_total_arr[$e]['Date']))."\",\"".$evt_link."\");'  />&nbsp;
																	<input type=\"image\" src='fb_sdk/images/fbbtn1.png' name=\"IMightGo\" onclick='fb_bnt(\"2\",\"".$event_total_arr[$e]['EventID']."\",\"".$event_total_arr[$e]['Event']."\",\"".$event_total_arr[$e]['Venue']."\",\"".date("D d M Y",strtotime($event_total_arr[$e]['Date']))."\",\"".$evt_link."\");'  />
																	</td></tr>";
															
															if($evt_neer<$neer_top)
																$neer_ct_3=$neer_ct;
															
															$evt_neer++;
														}
													}
												}
												
												echo '<div id="geo_tickets">';
												if($evt_neer>0)
												{
													echo '<h2>Events near you</h2>
													<table id="ticks" width="590" border="0" cellspacing="0" cellpadding="0">
														<tr id="tpticks" class="egrey">
															<!-- <td height="33" align="center" valign="middle"><strong>#</strong></td> -->
															<td width="230" height="33" align="center" valign="middle"><strong>EVENT</strong></td>
															<td width="130" align="center" valign="middle"><strong>DATE</strong></td>
															<td width="200" align="center" valign="middle"><strong>VENUE</strong></td>
															<td width="30" align="center" valign="middle"><strong>TICKETS</strong></td>
														</tr>';
													echo $neer_ct_3;
													echo '</table>';
													
													if($evt_neer>=$neer_top)
														echo ' <br><a href="#" onclick="show_neer()" class="event" style="color:#FF0000;font-size:12px;font-weight:bold " >Show all \''.$event2.' \' events near you</a><br>';
														
													
												}
												echo '</div>
													<div id="geo_tickets_all" style="visibility:hidden; display:none"><!-- style="visibility:hidden; display:none " -->';
												if($evt_neer>=$neer_top)
												{
													?>
													<script language="javascript">
													function show_neer()
													{
														document.getElementById('geo_tickets_all').style.visibility="visible";
														document.getElementById('geo_tickets_all').style.display="";
														document.getElementById('geo_tickets').style.visibility="hidden";
														document.getElementById('geo_tickets').style.display="none";
													}
													</script>
													<?
													echo '<h2>Events near you</h2>
													<table id="ticks" width="590" border="0" cellspacing="0" cellpadding="0">
														<tr id="tpticks" class="egrey">
															<!-- <td height="33" align="center" valign="middle"><strong>#</strong></td> -->
															<td width="230" height="33" align="center" valign="middle"><strong>EVENT</strong></td>
															<td width="130" align="center" valign="middle"><strong>DATE</strong></td>
															<td width="200" align="center" valign="middle"><strong>VENUE</strong></td>
															<td width="30" align="center" valign="middle"><strong>TICKETS</strong></td>
														</tr>';
													echo $neer_ct;
													echo '</table><br>';
												}
												echo '</div>';
												
												if($noticketlisting!=1)
													echo '<br><h2>Displaying '.$event2.' events</h2>';
												?>
												
												<div id="inside_tickets">
												<?
												
												if($noticketlisting==1)
												{
													include "include/notickets_listing_2.php";
													notickets($event2);
												}
												else
												{
												?>
													

<?


echo '<table id="ticks" width="590" border="0" cellspacing="0" cellpadding="0">
	<tr id="tpticks" class="egrey">
		<!-- <td height="33" align="center" valign="middle"><strong>#</strong></td> -->
		<td width="230" height="33" align="center" valign="middle"><strong>EVENT</strong></td>
		<td width="130" align="center" valign="middle"><strong>DATE</strong></td>
		<td width="200" align="center" valign="middle"><strong>VENUE</strong></td>
		<td width="30" align="center" valign="middle"><strong>TICKETS</strong></td>
	</tr>';
	
	$j=$evt_neer;
	while($tic_rows=mysql_fetch_array($tic_result))
	{
            $evt_link=mk_evt_link($tic_rows['Event'],$tic_rows['EventID'],$tic_rows['City'],$tic_rows['Date']);
            //$evt_link=str_replace(":","-",$evt_link);
            $evt_link=urlencode($evt_link);
            	
            if($evt_name!="Linkin Park" || $tic_rows['Time']!="TBA")
		{
			$oclick=' onmouseover="fshow(\''.$j.'\')" onmouseout="fhide(\''.$j.'\')" ';
			
			if(fmod($j,2)==0)	
				echo '<tr height="35" class="vevent ewhite" '.$oclick.' >';
			else
				echo '<tr height="35" class="vevent egrey" '.$oclick.' >';
			
			$event_link=explode("(",$tic_rows['Event']);
			$event_link=trim($event_link[0]);
			
			$event_link= str_replace("-", "_",$event_link);
			$event_link= str_replace(" ", "-",$event_link);
			
			//echo '<td><A href="tickets.php?EID='.$event_link.'" class="event"><b class="summary" title="'.$tic_rows['Event'].'"> '.$tic_rows['Event'].'</b></A><br><span style="color:#006600 ">'.$tic_rows['note'].'</span></td>';
			
			$evt_link=mk_evt_link($tic_rows['Event'],$tic_rows['EventID'],$tic_rows['City'],$tic_rows['Date']);
			//$evt_link=str_replace(":","-",$evt_link);
			$evt_link=urlencode($evt_link);
			/*if($tic_rows['VenueID']=="105234" && ($tic_rows['HeadlinerID']=="101534" || $tic_rows['HeadlinerID']=="101535"))
				$evt_link="new_listing/index.php?prodID=".$tic_rows['EventID'];	*/
			echo '<td><A href="'.$evt_link.'" class="event"><b class="summary" title="'.$tic_rows['Event'].'"> '.$tic_rows['Event'].'</b></A><br><span style="color:#006600 ">'.$tic_rows['note'].'</span></td>';
			
			$dar = datecall($tic_rows['Date']);
			$day = strtotime($tic_rows['Date']);
			$day=date('l', $day);
			
			if($dar[0]!="2022")
			{
				echo '<td align="center"><abbr class="dtstart" title="'.date("Y-m-d\TH:i:s",strtotime($tic_rows['Date']." ".$tic_rows['Time'])).'" ><b>'.$dar[1].' '.$dar[2].', '.$dar[0].'</b><br>'.$day.' - '.$tic_rows['Time'].'</abbr></td>  ';
			}
			else	
			{
	
				echo '<td align="center"><abbr class="dtstart" title="TBA" ><b>TBA</b></abbr></td>'; 
			}
			
			$venue_link=str_replace("-", "_",$tic_rows['Venue']);	
			$venue_link=str_replace(" ", "-",$venue_link);	
			$city_link=str_replace("-","_",$tic_rows['City']);
			$city_link=str_replace(" ","-",$city_link);
			$state_link=str_replace("-","_",$tic_rows['State']);
			$state_link=str_replace(" ","-",$state_link);
			
			echo '<td class="location vcard"><B><A href="tickets.php?ByVenue='.$venue_link.'" class="fn org venue" >'.$tic_rows['Venue'].'</A></B><BR><span class="adr"><A href="tickets.php?ByCity='.$city_link.'" class="venue"><span class="locality">'.$tic_rows['City'].'</span>, </A><A href="tickets.php?ByState='.$state_link.'" class="venue"><span class="region">'.$tic_rows['State'].'</span></A></span></td> ';
			$btn_src = "http://www.barrystickets.com/images/event/view_tickets.gif";
                        if(strpos(strtolower($evt_link), 'parking')!==false){
                            $btn_src = "http://www.barrystickets.com/images/event/view_parking_blue.gif";
                        }
			echo '<td  height="35" ><a href="'.$evt_link.'" class="url" ><img src="'.$btn_src.'" alt="View tickets"  border="0" id="view-tickets-'.$j.'" /></a></td></tr>';
			echo"</tr>";
			
			$sty=' style="display:none; visibility:hidden;"';
			
			if(fmod($j,2)==0)	
				echo '<tr height="35" id="ftr'.$j.'" class="vevent ewhite" align="center" valign="middle" '.$sty.' '.$oclick.' >';
			else
				echo '<tr height="35" id="ftr'.$j.'" class="vevent egrey" align="center" valign="middle" '.$sty.' '.$oclick.' >';
			
			echo "<td colspan='40'>
	<img src='fb_sdk/images/fblg.png' style='padding-bottom:3px'>&nbsp;&nbsp;
	<input type=\"image\" src='fb_sdk/images/fbbtn.png' name=\"ImGoing\" onclick='fb_bnt(\"1\",\"".$tic_rows['EventID']."\",\"".$tic_rows['Event']."\",\"".$tic_rows['Venue']."\",\"".date("D d M Y",strtotime($tic_rows['Date']))."\",\"".$evt_link."\");'  />&nbsp;
	<input type=\"image\" src='fb_sdk/images/fbbtn1.png' name=\"IMightGo\" onclick='fb_bnt(\"2\",\"".$tic_rows['EventID']."\",\"".$tic_rows['Event']."\",\"".$tic_rows['Venue']."\",\"".date("D d M Y",strtotime($tic_rows['Date']))."\",\"".$evt_link."\");'  />
	</td></tr>
	
				\n";
			$j++;
                }
		
	}
	echo '</table>';
	
	?>
	
												<?
												}
												?>
												</div>
                                                <style type="text/css">
	.pgg {
		color:#505050;
		text-decoration:none;
		font-size:12px;
		}
	</style>
    <script type="text/javascript">
	function gopage(param)
	{
		param=param.replace("?","");
		//alert(param);
		ajaxpg("tickets_pages.php",param,"inside_tickets");	
		return false;
	}
	function ajaxpg(page,data,contener) 
	{
		document.getElementById(contener).innerHTML ='<table width="100%" height="600"  border="0" cellspacing="0" cellpadding="0" align="center" style="vertical-align:middle; "><tr><td width="100%" height="600" valign="middle" align="center"><img src="http://www.barrystickets.com/images/44.gif"></td></tr></table>';
		//alert(page+"/"+data);
		var xhr_object = null; 
		if(window.XMLHttpRequest)
			xhr_object = new XMLHttpRequest();// Firefox
		else if(window.ActiveXObject)
			xhr_object = new ActiveXObject("Microsoft.XMLHTTP");// Internet Explorer 
		else { // XMLHttpRequest non supporté par le navigateur 
			alert("Your browser don\'t support XMLHTTPRequest objects...");
		return;
		} 
		xhr_object.open("POST", page, true); 
		xhr_object.onreadystatechange = function() { 
			if(xhr_object.readyState == 4)
			{	
				//alert(xhr_object.responseText);
				document.getElementById(contener).innerHTML = xhr_object.responseText;
			}
		} 
		xhr_object.setRequestHeader("Content-type", "application/x-www-form-urlencoded"); 
		xhr_object.send( data );
	}
	</script>
	<?
	
	if($tic_nbr !=0 && $numberOfPages>1)
	{
		echo '<br><br><div align="center"><span class="pgg">Page : </span>';
		for ($k = 1 ; $k <= $numberOfPages ; $k++)
		{
			if($k==$page)
			{
				echo '<b><span class="pgg">'.$k.'</span></b>&nbsp;';
			}
			else
			{
				$path=split(".php",$_SERVER['REQUEST_URI']);
				$path=split("&page=",$path[1]);
				$path=$path[0];
				if($pag_post_param!="")
					$path=$pag_post_param;
				//$path_arr=
				echo '<b><a href="#" class="pgg" onclick="return gopage(\''.$path.'&page='.$k.'\');">'.$k.'</a> </b>';
			}
		}
		echo '<div>';
	}
	
	
	?>
												</td>
											  </tr>
											  <tr>
												<td>
												
												<div align="center">
				 <table  width="603"  border="0" cellspacing="0" cellpadding="0">
				  <tr>
					<td>
					<? //include "include/whybuy.php"; ?>
					</td>
					</tr>
					<tr>
					<td valign="top" align="center">
					
<!-- <table width="100%"  border="0" cellspacing="0" cellpadding="0">
					  <tr>
						<td width="405" rowspan="2" align="left" valign="top" style="padding-left:5px; ">
       						Welcome to Barry&rsquo;s Ticket Service; the number one Los Angeles tickets provider on the secondary market today. A dedicated member of both the BBB and NATB for over a quarter of a century, customer service is the foundation of our enterprise.<br />
                            <div align="center"><img src="images/logos/bbb_online.gif" alt="BBB Online" title="BBB Online" width="103" height="35" />&nbsp;&nbsp;&nbsp;<img src="images/logos/natb.gif" alt="NATB" title="NATB" width="50" height="31" /></div> 
                    	</td>

					  </tr>
					
					</table> -->
					</td>
				  </tr>
				    <tr>
					  <td align="center">
					  <!-- <div align="center"><img src="images/logos/controlscan.gif" alt="Control Scan" title="Control Scan" width="81" height="29" />&nbsp;&nbsp;&nbsp;<img src="images/logos/eclick.gif" alt="e-click" title="e-click" width="40" height="35" />&nbsp;&nbsp;&nbsp;<img src="images/logos/fedex.gif" alt="Fedex" title="Fedex" width="58" height="25" />&nbsp;&nbsp;&nbsp;<img src="images/logos/mcafee_secure.gif" alt="Mcafee Secure" title="Mcafee Secure" width="71" height="29" />&nbsp;&nbsp;&nbsp;<img src="images/logos/natb.gif" alt="NATB" title="NATB" width="50" height="31" />&nbsp;&nbsp;&nbsp;<img src="images/logos/us_chamber_commerce.gif" alt="US Chamber Commerce" title="US Chamber Commerce" width="37" height="37" />&nbsp;&nbsp;&nbsp;<img src="images/logos/bbb.gif" alt="BBB" title="BBB" width="31" height="39"/></div> -->
					  <? //include ("include/security.php"); ?>
					  </td>
					  </tr>
				</table>

				 </div>
												
												</td>
											  </tr>
											</table>

											</td>
                                            <td id="rightevent" width="177" align="left" valign="top">
											<?php include_once "include/rightside_event.php"; ?>
                                        </td>
                                   		</tr>
										
                                  </table>
									
                                    
              </div>
                        		<div align="left"><img src="images/roundbottom_shadow.gif" alt="" width="26" height="6"/></div>
                            
                      </td>
                	</tr>
                </table>
                
                							<?php include_once "include/footer.php"; ?>
	<?

//$temps_fin_page = microtime_float();
//echo "<br>******************* executon time 1  : ".round($temps_fin_page - $temps_debut_page, 6)." ***********<br>";

//$temps_debut_page=$temps_fin_page="";
//$temps_debut_page = microtime_float();
?>
	</body>
    <script type="text/javascript">
var _sf_async_config={};
/** CONFIGURATION START **/
_sf_async_config.uid = 25569;
_sf_async_config.domain = "barrystickets.com"; /** CHANGE THIS **/
/** CONFIGURATION END **/
(function(){
  function loadChartbeat() {
    window._sf_endpt=(new Date()).getTime();
    var e = document.createElement("script");
    e.setAttribute("language", "javascript");
    e.setAttribute("type", "text/javascript");
    e.setAttribute("src",
       (("https:" == document.location.protocol) ?
         "https://a248.e.akamai.net/chartbeat.download.akamai.com/102508/" :
         "http://static.chartbeat.com/") +
       "js/chartbeat.js");
    document.body.appendChild(e);
  }
  var oldonload = window.onload;
  window.onload = (typeof window.onload != "function") ?
     loadChartbeat : function() { oldonload(); loadChartbeat(); };
})();
</script>
</html>

