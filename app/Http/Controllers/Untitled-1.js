<!DOCTYPE html>
<html>
<body>

<h2>HTML Forms</h2>

<form action="/action_page.php">
    @csrf
  <label for="fname">First name:</label><br>
  <input type="text" id="fname" name="fname" value="John"><br>
  <label for="lname">Last name:</label><br>
  <input type="text" id="lname" name="lname" value="Doe"><br><br>
  <input type="submit" value="Submit">
  <input type="hidden" id="token" name="_token" value="{{ csrf_field() }}">
</form> 

<p>If you click the "Submit" button, the form-data will be sent to a page called "/action_page.php".</p>

</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.2.0/crypto-js.min.js"></script>


<script>
    $(document).ready(function() {
        $.ajax({
            type: 'GET',
            url: '{{ url("/create_token") }}',
            dataType: "json",
            async:false,
            success: function(data) {
                console.log(data, "Check Tokennnnn");
                var body = {
                                "totalAmount": 2.81,
                                "currencyCode": "HKD",
                                "effectiveDuration":600,
                                "notificationUri":"http://127.0.0.1:8000/return",
                                "appSuccessCallback":"http://127.0.0.1:8000/success",
                                "appFailCallback":"http://127.0.0.1:8000/failure",
                                "merchantData": {
                                    "orderId": "ID12345678",
                                    "orderDescription": "Description displayed to customer",
                                    "additionalData": "Arbitrary additional data - logged but not displayed",
                                    "shoppingCart": [
                                        {
                                            "category1": "General categorization",
                                            "category2": "More specific categorization",
                                            "category3": "Highly specific categorization",
                                            "quantity": 1,
                                            "price": 1,
                                            "name": "Item 1",
                                            "sku": "SKU987654321",
                                            "currencyCode": "HKD"
                                        },
                                        {
                                            "category1": "General categorization",
                                            "category2": "More specific categorization",
                                            "category3": "Highly specific categorization",
                                            "quantity": 2,
                                            "price": 1,
                                            "name": "Item 2",
                                            "sku": "SKU678951234",
                                            "currencyCode": "HKD"
                                        }
                                    ]
                                }
                            };

                            const date = new Date('2018-08-02T20:17:46.384Z');

                // Use Date methods to format it as RFC3339
                const year = date.getUTCFullYear();
                const month = String(date.getUTCMonth() + 1).padStart(2, '0');
                const day = String(date.getUTCDate()).padStart(2, '0');
                const hours = String(date.getUTCHours()).padStart(2, '0');
                const minutes = String(date.getUTCMinutes()).padStart(2, '0');
                const seconds = String(date.getUTCSeconds()).padStart(2, '0');
                const milliseconds = String(date.getUTCMilliseconds()).padStart(3, '0');

                // Construct the RFC3339 formatted string
                const rfc3339DateTime = `${year}-${month}-${day}T${hours}:${minutes}:${seconds}.${milliseconds}Z`;

                console.log(rfc3339DateTime);
                
                var targetUrl = '/payments/paymentrequests'
                var method = body.method;
                var sha256digest = CryptoJS.SHA256(body);
                var base64sha256 = CryptoJS.enc.Base64.stringify(sha256digest);
                var content_length =  JSON.stringify(body).length;            

                var headerHash = {
                    'Request-Date-Time' : '"'+rfc3339DateTime+'"',
                    'Api-Version': '0.12',
                    'Trace-Id': "9ea99677-bc0f-4ec8-9bf6-a2b447834f92",
                    'Authorization': 'Bearer ' + data.access_token,
                    'Digest' : computedDigest,
                    '(request-target)' : method + ' ' + targetUrl
                };
                
                var config = {
                    algorithm : 'hmac-sha256',
                    keyId : "<?php echo env('PAYME_SINGING_KEY_ID'); ?>",
                    secretkey : CryptoJS.enc.Base64.parse("<?php echo env('PAYME_SINGING_KEY'); ?>"),
                    headers : ['(request-target)', 'Api-Version', 'Request-Date-Time', 'Trace-Id', 'Authorization', 'Digest']
                };
                var signature = computeHttpSignature(config, headerHash);
                // var computedDigest = 'SHA-256=' + base64sha256;
                var computedDigest = 'SHA-256=Kuat92wWwJk+SeYuYAAwq4F4MMkD9fVa+xbPEwN2Zew=';
        
               console.log(signature, 'Check First')
               console.log(computedDigest, "Check Digesttttt")
            if(data){
                var data = JSON.parse(data);
                var accessToken = data.accessToken;
                console.log(accessToken);
                $.ajax({
                    type: 'POST',
                    url: '{{ url("/create_payment_form") }}',
                    dataType: "json",
                    // async:false,
                    data: {
                        access_token: accessToken,
                        digest: computedDigest,
                        signature: signature,
                        _token: '{{csrf_token()}}',
                    },
                    success: function(response) {
                        // alert("var");
                        console.log(response);
                    }

                });
            } else {
                
            }
             
            }
        });
    });

    
    function computeHttpSignature(config, headerHash) {
        var template = 'keyId="${keyId}",algorithm="${algorithm}",headers="${headers}",signature="${signature}"',
            sig = template;
        
        // compute sig here
        var signingBase = '';
        config.headers.forEach(function(h){
            if (signingBase !== '') {
                signingBase += '\n';
                
            }
            signingBase += h.toLowerCase() + ": " + headerHash[h];
        });
        
        var hashf = (function() {
            switch (config.algorithm) {
                case 'hmac-sha1': return CryptoJS.HmacSHA1;
                case 'hmac-sha256': return CryptoJS.HmacSHA256;
                case 'hmac-sha512': return CryptoJS.HmacSHA512;
                default : return null;
            }
            }());
        
        var hash = hashf(signingBase, config.secretkey);
        var signatureOptions = {
                keyId : config.keyId,
                algorithm: config.algorithm,
                headers: config.headers,
                signature : CryptoJS.enc.Base64.stringify(hash)
            };
        
        // build sig string here
        Object.keys(signatureOptions).forEach(function(key) {
            var pattern = "${" + key + "}",
                value = (typeof signatureOptions[key] != 'string') ? signatureOptions[key].join(' ') : signatureOptions[key];
            sig = sig.replace(pattern, value);
        });
        
        return sig;
    }
</script>
</html>
