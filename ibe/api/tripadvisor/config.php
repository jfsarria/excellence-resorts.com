<?php
header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header ("Content-Type:application/json");
?>
{
    "api_version": 7,
    "configuration": {
        "emergency_contacts": [
            {
                "full_name": "Mirek Nisenbaum",
                "email": "mirek@artbymobile.com",
                "phone_number": "1 (866) 540-2585"
            },
            {
                "full_name": "Mirek Nisenbaum",
                "email": "mirek@artbymobile.com",
                "phone_number": "1 (866) 540-2585"
            }
        ],
        "info_contacts": [
            {
                "full_name": "Mirek Nisenbaum",
                "email": "mirek@artbymobile.com",
                "phone_number": "1 (866) 540-2585"
            }
        ],
        "languages": [
            "en"
        ],
        "pref_hotels": 50,
        "five_min_rate_limit": 10000
    },
    "debug_info": "The ip of the request is <?=$_SERVER["SERVER_ADDR"]?>"
}