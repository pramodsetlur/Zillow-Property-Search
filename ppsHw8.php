<?php

    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST');
    
    $street = str_replace(" ","+",$_GET['street']);
    $city = str_replace(" ","+",$_GET['city']);
    $state = $_GET['state'];

    $url = "http://www.zillow.com/webservice/GetDeepSearchResults.htm?zws-id=X1-ZWz1dxtpnlk1e3_44w9p&address=".$street."&citystatezip=".$city."%2C+".$state."&rentzestimate=true";

    $xml = simplexml_load_file($url);
    $valid = $xml->message->code;
    if($valid==0)
    {
        $responseXml = $xml->response->results->result;

        $streetResponse = $responseXml->address->street;   
        $zipcodeResponse = $responseXml->address->zipcode; 
        $cityResponse = $responseXml->address->city; 
        $stateResponse = $responseXml->address->state;
        $homeDetailsResponse = $responseXml->links->homedetails;


        $propertyType = $responseXml->useCode;
        $yearBuilt = $responseXml->yearBuilt;
        $lotSize = $responseXml->lotSizeSqFt;
        $finishedArea = $responseXml->finishedSqFt;
        $bathrooms = $responseXml->bathrooms;
        $bedrooms = $responseXml->bedrooms;
        $taxAssessmentYear = $responseXml->taxAssessmentYear;
        $taxAssessment = $responseXml->taxAssessment;
        $lastSoldDate = $responseXml->lastSoldDate;
        $lastSoldPrice = $responseXml->lastSoldPrice;
        $zpId = $responseXml->zpid;
        

        $propertyEstimate = $responseXml->zestimate->amount;
        $propertyLastUpdated = $responseXml->zestimate->{'last-updated'};

        $overallValueChange = $responseXml->zestimate->valueChange;

        $lowCurrency = $responseXml->zestimate->valuationRange->low;
        $highCurrency = $responseXml->zestimate->valuationRange->high;

        $rentAmount = $responseXml->rentzestimate->amount;
        $rentLastUpdated = $responseXml->rentzestimate->{'last-updated'};
        $rentValueChange = $responseXml->rentzestimate->valueChange;

        $rentRangeLow = $responseXml->rentzestimate->valuationRange->low;
        $rentRangeHigh = $responseXml->rentzestimate->valuationRange->high;

        date_default_timezone_set('America/Los_Angeles');

        
        $varHomeDetailsResponse = $homeDetailsResponse;
        $varStreetResponse = $streetResponse;
        $varCityResponse = $cityResponse;
        $varStateResponse = $stateResponse;
        $varZipcodeResponse = $zipcodeResponse;
        
        $chartUrl1 = "http://www.zillow.com/webservice/GetChart.htm?zws-id=X1-ZWz1dxtpnlk1e3_44w9p&unit-type=percent&zpid=".$zpId."&width=600&height=300&chartDuration=1year";
        $chartResponse1 = simplexml_load_file($chartUrl1);
        $chartResUrl1 = $chartResponse1->response->url;
        
        $chartUrl5 = "http://www.zillow.com/webservice/GetChart.htm?zws-id=X1-ZWz1dxtpnlk1e3_44w9p&unit-type=percent&zpid=".$zpId."&width=600&height=300&chartDuration=5years";
        $chartResponse5 = simplexml_load_file($chartUrl5);
        $chartResUrl5 = $chartResponse5->response->url;
        
        $chartUrl10 = "http://www.zillow.com/webservice/GetChart.htm?zws-id=X1-ZWz1dxtpnlk1e3_44w9p&unit-type=percent&zpid=".$zpId."&width=600&height=300&chartDuration=10years";
        $chartResponse10 = simplexml_load_file($chartUrl10);
        $chartResUrl10 = $chartResponse10->response->url;
                
        if($propertyType != "")
        {
            $varPropertyType = $propertyType;
        }
        else
        {
            $varPropertyType = "NA";
        }

        if($lastSoldPrice != "")
        {
            $lastSoldPrice = '$'.number_format((float)$lastSoldPrice,2);  
            $varLastSoldPrice = $lastSoldPrice;
        }
        else
        {
            $varLastSoldPrice = "NA";
        }

        if($yearBuilt != "")
        {
            $varYearBuilt = $yearBuilt;
        }
        else
        {
            $varYearBuilt = "NA";
        }

        if($lastSoldDate != "")
        {
            $lastSoldDate_fm = date_create($lastSoldDate);
            $lastSoldDate_fm = date_format($lastSoldDate_fm,'d-M-Y');
            $varLastSoldDate = $lastSoldDate_fm;
        }
        else
        {
            $varLastSoldDate = "NA";
        }

        if($lotSize != "")
        {
            $lotSize = number_format((float)$lotSize);
            $varLotSize = $lotSize." sq. ft.";
        }
        else
        {
            $varLotSize = "NA";
        }

        if($propertyLastUpdated != "")
        {
            $propertyLastUpdated_fm = date_create($propertyLastUpdated);
            $propertyLastUpdated_fm = date_format($propertyLastUpdated_fm,'d-M-Y');
            $varPropertyLastUpdated = $propertyLastUpdated_fm;
        }
        else
        {
            $varPropertyLastUpdated = "NA";
        }

        if($propertyEstimate != "")
        {
            $propertyEstimate = '$'.number_format((float)$propertyEstimate,2);
            $varPropertyEstimate = $propertyEstimate;
        }
        else
        {
            $varPropertyEstimate = "NA";
        }

        if($finishedArea != "")
        {
            $finishedArea = number_format((float)$finishedArea); 
            $varFinishedArea = $finishedArea." sq. ft.";
        }
        else
        {
            $varFinishedArea = "NA";
        }


        if($overallValueChange != "")
        {
            $fbOverallValueChange = $overallValueChange;
            if($fbOverallValueChange > 0)
            {
                $fbOverallValueChange = '$'.number_format((float)$fbOverallValueChange,2);

            }
            else
            {
                $fbOverallValueChange = -1 * $fbOverallValueChange; 
                $fbOverallValueChange = '-'.'$'.number_format((float)$fbOverallValueChange,2);
            }
            if($overallValueChange < 0)
            {
                $overallValueChangeNegative = true;
                $overallValueChange = -1 * $overallValueChange;
            }
            else
                $overallValueChangeNegative = false;

            if($overallValueChangeNegative == true)
                $varOverallValueChange = "<img src='http://cs-server.usc.edu:45678/hw/hw6/down_r.gif'/>";
            else
                $varOverallValueChange = "<img src='http://cs-server.usc.edu:45678/hw/hw6/up_g.gif'/>";                            
               
            $overallValueChange = '$'.number_format((float)$overallValueChange,2);
            $varOverallValueChange .= $overallValueChange;
        }

        else
        {
            $varOverallValueChange = "NA";  
        }


        if($bathrooms != "")
        {
            $varBathrooms = $bathrooms;  
        }
        else
        {
            $varBathrooms = "NA";  
        }


        if($lowCurrency != "" && $highCurrency != "")
        {
            $lowCurrency = '$'.number_format((float)$lowCurrency,2);
            $highCurrency = '$'.number_format((float)$highCurrency,2);

            $varLowCurrency = $lowCurrency;
            $varHighCurrency = $highCurrency;
        }
        else
        {
            $varLowCurrency = "NA";
            $varHighCurrency = "NA";
        }


        if($bedrooms != "")
        {
            $varBedrooms = $bedrooms;
        }
        else
        {
            $varBedrooms = "NA";
        }

        if($rentLastUpdated != "")
        {
            $rentLastUpdated_fm = date_create($rentLastUpdated);
            $rentLastUpdated_fm = date_format($rentLastUpdated_fm,'d-M-Y');   
            $varRentLastUpdated = $rentLastUpdated_fm;
        }
        else
        {
            $varRentLastUpdated = "NA";
        }


        if($rentAmount != "")
        {
            $rentAmount = '$'.number_format((float)$rentAmount,2);
            $varRentAmount = $rentAmount;
        }
        else
        {
            $varRentAmount = "NA";
        }

        if($taxAssessmentYear != "")
        {
            $varTaxAssessmentYear = $taxAssessmentYear;
        }
        else
        {
            $varTaxAssessmentYear = "NA";
        }



        if($rentValueChange != "")
        {
            if($rentValueChange < 0)
            {
                $rentValueChangeNegative = true;
                $rentValueChange = -1 * $rentValueChange;
            }
            else
                $rentValueChangeNegative = false;

            
            if($rentValueChangeNegative == true)
                $varRentValueChange = "<img src='http://cs-server.usc.edu:45678/hw/hw6/down_r.gif'/>";
            else
                $varRentValueChange = "<img src='http://cs-server.usc.edu:45678/hw/hw6/up_g.gif'/>";
           
            $rentValueChange = '$'.number_format((float)$rentValueChange,2);
            $varRentValueChange .= $rentValueChange;
        }
        else
        {
            $varRentValueChange = "NA";
        }


        if($taxAssessment != "")
        {
            $taxAssessment = '$'.number_format((float)$taxAssessment,2);
            $varTaxAssessment = $taxAssessment;
        }
        else
        {
            $varTaxAssessment = "NA";
        }


        if($rentRangeLow != "" && $rentRangeHigh != "")
        {
            $rentRangeLow = '$'.number_format((float)$rentRangeLow,2);
            $rentRangeHigh = '$'.number_format((float)$rentRangeHigh,2);

            $varRentRangeLow = $rentRangeLow;
            $varRentRangeHigh = $rentRangeHigh;
        }
        else
        {
            $varRentRangeLow = "NA";
            $varRentRangeHigh = "NA";
        } 
        
        
        /*Creating JSON Array*/
        $resultArray = array(
            'valid'=>(string)$valid,
            'zpid'=>array('name'=>'zpid','value'=>(string)$zpId),
            'homeDetails'=>array('name'=>'Home Details','value'=>(string)$varHomeDetailsResponse),
            'streetResponse'=>array('name'=>'Street Details','value'=>(string)$varStreetResponse),
            'cityResponse'=>array('name'=>'City Details','value'=>(string)$varCityResponse),
            'stateResponse'=>array('name'=>'State Details','value'=>(string)$varStateResponse),
            'zipResponse'=>array('name'=>'Zip Code','value'=>(string)$varZipcodeResponse),
            'propertyType'=>array('name'=>'Property Type','value'=>(string)$varPropertyType),
            'lastSoldPrice'=>array('name'=>'Last Sold Price','value'=>(string)$varLastSoldPrice),
            'yearBuilt'=>array('name'=>'Year Built','value'=>(string)$varYearBuilt),
            'lastSoldDate'=>array('name'=>'Last Sold Date','value'=>(string)$varLastSoldDate),
            'lotSize'=>array('name'=>'Lot Size','value'=>(string)$varLotSize),
            'zestimate'=>array('name'=>"Zestimate <sup>&#174;</sup> Property Estimate as of $varPropertyLastUpdated",'value'=>(string)$varPropertyEstimate),
            'finishedArea'=>array('name'=>'Finished Area','value'=> (string)$varFinishedArea),
            'overallChange'=>array('name'=>'30 day overall change','value'=>(string)$varOverallValueChange),
            'bathrooms'=>array('name'=>'Bathrooms','value'=>(string)$varBathrooms),
            'allTimePropertyRange'=>array('name'=>'All Time Property Range','value'=>"$varLowCurrency-$varHighCurrency"),
            'bedrooms'=>array('name'=>'Bedrooms','value'=>(string)$varBedrooms),
            'rentZestimate'=>array('name'=>"Rent Zestimate <sup>&#174;</sup> Rent Valuation as of $varRentLastUpdated",'value'=>(string)$varRentAmount),
            'taxAssessmentYear'=>array('name'=>'Tax Assessment Year','value'=>(string)$varTaxAssessmentYear),
            'monthRentChange'=>array('name'=>'30 Days Rent Change','value'=>(string)$varRentValueChange),
            'taxAssessment'=>array('name'=>'Tax Assessment','value'=>(string)$varTaxAssessment),
            'allTimeRentChange'=>array('name'=>'All Time Rent Change','value'=>"$varRentRangeLow - $varRentRangeHigh"),
            'chartUrl1'=>(string)$chartResUrl1,
            'chartUrl5'=>(string)$chartResUrl5,
            'chartUrl10'=>(string)$chartResUrl10,
            'fbOverallValueChange'=>(string)$fbOverallValueChange
            );
        
        echo json_encode($resultArray);
    }
    else
    {
        $valid="1";
         $resultArray = array(
            'valid'=>(string)$valid);
        echo json_encode($resultArray);
    }
?>
