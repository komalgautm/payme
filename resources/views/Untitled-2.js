    // var computedDigest = 'SHA-256=' + base64sha256;


                // var targetUrl = '/payments/paymentrequests';
                // var method = 'post';
                // var sha256digest = CryptoJS.SHA256(body);
                // var base64sha256 = CryptoJS.enc.Base64.stringify(sha256digest);
                // var computedDigest = 'SHA-256=' + base64sha256;
                // var content_length =  JSON.stringify(body).length;   
                
                
                // var headerHash = {
                //     'request-Date-Time' : '"'+rfc3339DateTime+'"',
                //     'api-Version': '0.12',
                //     'trace-Id': trace_id,
                //     'authorization': 'Bearer ' + data.accessToken,
                //     'digest' : base64Digest,
                //     '(request-target)' : method + ' ' + targetUrl
                // };
                
                // var config = {
                //     algorithm : 'hmac-sha256',
                //     keyId : "<?php echo env('PAYME_SINGING_KEY_ID'); ?>",
                //     secretkey : CryptoJS.enc.Base64.parse("<?php echo env('PAYME_SINGING_KEY'); ?>"),
                //     headers : ['(request-target)', 'Api-Version', 'Request-Date-Time', 'Trace-Id', 'Authorization', 'Digest']
                // };
                // var signature = computeHttpSignature(config, headerHash);
        
            //    console.log(signature, 'Check First')
            //    console.log(computedDigest, "Check Digesttttt")
            // if(data){



            //     $(function() {
            //             var params = {
            //                 // Request parameters
            //             };
                    
            //             $.ajax({
            //                 url: "https://sandbox.api.payme.hsbc.com.hk/payments/paymentrequests",
            //                 beforeSend: function(xhrObj){
            //                     // Request headers
            //                     xhrObj.setRequestHeader("Api-Version","0.12");
            //                     xhrObj.setRequestHeader("Accept","application/json");
            //                     xhrObj.setRequestHeader("Content-Type","application/json");
            //                     xhrObj.setRequestHeader("Authorization","Bearer "+data.accessToken);
            //                     xhrObj.setRequestHeader("Trace-Id",trace_id);
            //                     xhrObj.setRequestHeader("Digest", computedDigest);
            //                     xhrObj.setRequestHeader("Signature",signature);
            //                     xhrObj.setRequestHeader("Request-Date-Time", rfc3339DateTime);
            //                     xhrObj.setRequestHeader("X-HSBC-Merchant-Id","50e1de21-e0cd-41a4-b384-736e38b3c08d");
            //                     xhrObj.setRequestHeader("X-HSBC-Device-Id","");
            //                     xhrObj.setRequestHeader("Content-Type","application/json");
            //                     xhrObj.setRequestHeader("api-version","0.12");
            //                 },
            //                 type: "POST",
            //                 // Request body
            //                 data: JSON.stringify(body) ,
            //             })
            //             .done(function(data) {
            //                 console.log(data);
            //                 alert("success");
            //             })
            //             .fail(function() {
            //                 alert("error");
            //             });
            //         });
                    

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
            // } 