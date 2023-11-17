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
    
    var targetUrl = '/payments/paymentrequests'
    var method = request.method.toLowerCase();
    var sha256digest = CryptoJS.SHA256(request.data);
    var base64sha256 = CryptoJS.enc.Base64.stringify(sha256digest);
    var computedDigest = 'SHA-256=' + base64sha256;
    var content_length =  JSON.stringify(request.data).length;
    
    var headerHash = {
          'Request-Date-Time' : environment['utc_now'],
          'Api-Version': environment['version'],
          'Trace-Id': environment['trace_id'],
          'Authorization': 'Bearer ' + environment['access_token'],
          'Digest' : computedDigest,
          '(request-target)' : method + ' ' + targetUrl
        };
    
    var config = {
          algorithm : 'hmac-sha256',
          keyId : environment['signing_key_id'],
          secretkey : CryptoJS.enc.Base64.parse(environment['signing_key']),
          headers : ['(request-target)', 'Api-Version', 'Request-Date-Time', 'Trace-Id', 'Authorization', 'Digest']
        };
    
    pm.environment.set('signature', computeHttpSignature(config, headerHash));
    pm.environment.set('digest', computedDigest);