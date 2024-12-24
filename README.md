Real-Time Bidding (RTB) Banner Campaign Response

Overview

This PHP project handles bid requests and generates suitable responses for Banner campaigns in RTB scenarios.

Files

rtb_handler.php - Main script to process bid requests.
campaign.php - Contains predefined campaign details.
README.md - Documentation file.



Send a POST request to the server with a bid request JSON.

Example: 
Send Request:

{
    "id": "myB92gUhMdC5DUxndq3yAg",
    "imp": [
        {
            "id": "1",
            "bidfloor": 0.01
        }
    ],
    "device": {
        "geo": {
            "country": "Bangladesh"
        }
    }
}

Output:
 
{
  "id": "myB92gUhMdC5DUxndq3yAg",
  "bid": {
    "id": "1",
    "price": 0.1,
    "ad": {
      "campaign": "Test_Banner_13th-31st_march_Developer",
      "advertiser": "TestGP",
      "creative_type": "1",
      "image_url": "https://s3-ap-southeast-1.amazonaws.com/elasticbeanstalk-ap-southeast-1-5410920200615/CampaignFile/20240117030213/D300x250/e63324c6f222208f1dc66d3e2daaaf06.png",
      "landing_page": "https://adplaytechnology.com/"
    }
  }
}