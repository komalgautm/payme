<!DOCTYPE html>
<html>
<body>

<h2>HTML Forms</h2>

<form>
    @csrf
    <input type="hidden" id="token" name="_token" value="">
    <label for="fname"> Amount: </label>
    <input type="text" id="amount" name="amount" value="amount"><br>
    <input type="submit" value="Submit">
</form> 
<div id="paymentRequest"></div>
</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.2.0/crypto-js.min.js"></script>
<!-- Include Axios from a CDN -->
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>


<script>
    $(document).ready(function() {
        $.ajax({
            type: 'GET',
            url: '{{ url("/create_token") }}',
            dataType: "json",
            async:false,
            success: function(data) {
   
                console.log(data, "Check Tokennnnn");
                const body = {
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
                
                const date = new Date();

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
                // console.log(rfc3339DateTime);
                
                var trace_id =  uuidv4();
                    
                // Convert the JSON object to a string
                const jsonString = JSON.stringify(body);

                // Convert the string to UTF-8 bytes
                const textEncoder = new TextEncoder();
                const utf8Bytes = textEncoder.encode(jsonString);

                // Calculate the SHA-256 hash
                async function sha256Digest(buffer) {
                const hashBuffer = await crypto.subtle.digest('SHA-256', buffer);
                return hashBuffer;
                }

                // Convert the hash to Base64
                async function base64Encode(buffer) {
                const uint8Array = new Uint8Array(buffer);
                return btoa(String.fromCharCode.apply(null, uint8Array));
                }

                // Calculate the digest value
                let base64Digest;
                (async () => {
                    const sha256Hash = await sha256Digest(utf8Bytes);
                    base64Digest = await base64Encode(sha256Hash);
                    console.log('Digest Value:', base64Digest);
                })();

                setTimeout(() => {
                console.log('Digest Value2:', base64Digest);
                var computedDigest = 'SHA-256=' + base64Digest;
                console.log();
                }, 1000);

                var targetUrl = '/payments/paymentrequests';
                var method = 'post';
                // var sha256digest = CryptoJS.SHA256(body);
                // var base64sha256 = CryptoJS.enc.Base64.stringify(sha256digest);
                // var computedDigest = 'SHA-256=' + base64sha256;
                // var content_length =  JSON.stringify(body).length;   
                
                
                // var computedDigest ='SHA-256=COCDd72WPm3LcI/TXIxOa4SCQOpXfrPxehLObHWRtYs=';
            
                var headerHash = {
                    'request-Date-Time' : '"'+rfc3339DateTime+'"',
                    'api-Version': '0.12',
                    'trace-Id': trace_id,
                    'authorization': 'Bearer ' + data.accessToken,
                    'digest' : computedDigest,
                    '(request-target)' : method + ' ' + targetUrl
                };
                
                var config = {
                    algorithm : 'hmac-sha256',
                    keyId : "<?php echo env('PAYME_SINGING_KEY_ID'); ?>",
                    secretkey : CryptoJS.enc.Base64.parse("<?php echo env('PAYME_SINGING_KEY'); ?>"),
                    headers : ['(request-target)', 'Api-Version', 'Request-Date-Time', 'Trace-Id', 'Authorization', 'Digest']
                };
                var signature = computeHttpSignature(config, headerHash);
                console.log(signature, 'Check First')
            if(data){

                $(function() {
                        var params = {
                            // Request parameters
                        };
                    
                        $.ajax({
                            url: "https://sandbox.api.payme.hsbc.com.hk/payments/paymentrequests",
                            beforeSend: function(xhrObj){
                                // Request headers
                                xhrObj.setRequestHeader("Api-Version","0.12");
                                xhrObj.setRequestHeader("Accept","application/json");
                                xhrObj.setRequestHeader("Content-Type","application/json");
                                xhrObj.setRequestHeader("Authorization","Bearer "+data.accessToken);
                                xhrObj.setRequestHeader("Trace-Id",trace_id);
                                xhrObj.setRequestHeader("Digest", computedDigest);
                                xhrObj.setRequestHeader("Signature",signature);
                                xhrObj.setRequestHeader("Request-Date-Time", rfc3339DateTime);
                                xhrObj.setRequestHeader("X-HSBC-Merchant-Id","50e1de21-e0cd-41a4-b384-736e38b3c08d");
                                xhrObj.setRequestHeader("X-HSBC-Device-Id","");
                                xhrObj.setRequestHeader("Content-Type","application/json");
                                xhrObj.setRequestHeader("api-version","0.12");
                            },
                            type: "POST",
                            // Request body
                            data: JSON.stringify(body) ,
                        })
                        .done(function(data) {
                            console.log(data);
                            alert("success");
                        })
                        .fail(function() {
                            alert("error");
                        });
                    });
                    

                // const apiUrl = 'https://sandbox.api.payme.hsbc.com.hk/payments/paymentrequests';

                // // Headers for the request
                // const headers = {
                // '(request-target)' : method + ' ' + targetUrl,
                // 'api-version': '0.12',
                // 'request-date-time': rfc3339DateTime,
                // 'Content-Type': 'json',
                // 'digest': computedDigest,
                // 'accept': application/json,
                // 'trace-id': trace_id,
                // 'Authorization': 'Bearer '+accessToken, // Replace with your actual access token
                // };

                // // Axios request
                // axios({
                // method: 'post', // or 'get', 'put', 'delete', etc.
                // url: apiUrl,
                // headers: headers,
                // data: body,
                // })
                // .then(response => {
                //     // Handle successful response
                //     console.log('Data from the API:', response.data);
                // })
                // .catch(error => {
                //     // Handle errors
                //     console.error('Error fetching data:', error);
                // });


           
             
                // $.ajax({
                //     type: 'POST',
                //     url: '{{ url("/create_payment_form") }}',
                //     dataType: "json",
                //     // async:false,
                //     data: {
                //         access_token: data.accessToken,
                //         digest: computedDigest,
                //         signature: signature,
                //         _token: '{{csrf_token()}}',
                //         dateTime: rfc3339DateTime,
                //         trace: trace_id
                //         // body: data
                //     },
                    
                //     success: function(response) {
                //         // alert("var");
                //         console.log(response);
                //         document.getElementById('paymentRequest').innerHTML = response;
                //     }

                // });
            } else {
                
            }
             
            }
        });
    });

    function uuidv4() {
        return "10000000-1000-4000-8000-100000000000".replace(/[018]/g, c =>
            (c ^ crypto.getRandomValues(new Uint8Array(1))[0] & 15 >> c / 4).toString(16)
        );
    }

    
    function computeHttpSignature(config, headerHash) {
        var template = 'keyId="${keyId}",algorithm="${algorithm}",headers="${headers}",signature="${signature}"',
            sig = template;
            console.log("temp" +template)
            console.log("keyid  " +config.keyId)

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
