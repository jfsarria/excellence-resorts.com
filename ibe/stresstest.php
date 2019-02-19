<?
// http://excellence-resorts.com/ibe/stresstest.php

// https://www.sitepoint.com/stress-test-php-app-apachebench/

print file_get_contents('http://excellence-resorts.com/hotel_availability?api_version=4&hotels=[{"ta_id":499896,"partner_id":"1","partner_url":"http://excellence-resorts.com"},{"ta_id":649432,"partner_id":"2","partner_url":"http://excellence-resorts.com"},{"ta_id":218524,"partner_id":"3","partner_url":"http://excellence-resorts.com"}]&start_date=2017-11-15&end_date=2017-11-17&num_adults=2&lang=en_US&num_rooms=2&lang=en_US&currency=USD&user_country=US&device_type=d&query_key=stress_test');


//print file_get_contents('http://excellence-resorts.com/ibe/index.php?PAGE_CODE=ws.stresstest');