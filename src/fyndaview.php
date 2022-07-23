<?php

require 'vendor/autoload.php';

namespace Rahul\Fyndaview;

include('simple_html_dom.php');

function search($a,$b)  //search with keywords as input
{
    
//$a= "pokemon"; //test variable
//$b= "pikachu"; //test variable 
    
$y = 0; //variable for rank
    
for ($x = 0; $x <50; $x+=10) //loop for 5 pages
{
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, 'https://www.google.com/search?q='.$a.'+'.$b.'&start='.$x);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($curl);
    curl_close($curl);
    //echo $result;

    $domResults = new simple_html_dom();
    $domResults->load($result);
    
    $i=0;
    foreach($domResults->find('a[href^=/url?]') as $link) // for title and url
    {   
        $title = trim($link->plaintext); //the text of the search result
        $links  = trim($link->href);  //the url
        
        if (!preg_match('/^https?/', $links) && preg_match('/q=(.+)&amp;sa=/U', $links, $matches) && preg_match('/^https?/', $matches[1])) 
        {
        $links = $matches[1];
           } 
        else if (!preg_match('/^https?/', $links)) 
        { 
        continue;
            }
        
        $descr = $domResults->find('div[class=vwic3b yxk7lf muxgbd ydynvb lylwlc lebkkf]',$i); //description using google class for the div
        $i++; 
        if (!empty($descr->innertext)) 
        {echo 'Description: ' . $descr . '<br>';
         $promoted = "False";
         echo 'Keyword : '.$a. '&nbsp' .$b. '<br>'.'Ranking : '.$y++ . '<br>'.'Title : '. $title . '<br>'.'URL : ' . $links . '<br>'.'Promoted: ' . $promoted . '<br><br>';
        }
        
        else
        {echo 'Description: Description not present <br>';
         $promoted = "True";
         echo 'Keyword : '.$a. '&nbsp' .$b. '<br>'.'Ranking : '.$y++ . '<br>'.'Title : '. $title . '<br>'.'URL : ' . $links . '<br>'.'Promoted: ' . $promoted . '<br><br>';
             }
         }
    }                 
 }
?>