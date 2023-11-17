<!doctype html>
<html>

<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="Content-Type" content="text/html; charset=Utf-8">
  <script type="text/javascript" src="{{ url('js/qrcode.js') }}"></script>
  <script type="text/javascript" src="{{ url('js/paycode.js') }}"></script>
  <script type="text/javascript" src="{{ url('js/example.js') }}"></script>

  <!--STYLE-->
  <link rel="stylesheet" type="text/css" href="{{ url('style/style.css') }}">

  <title>PayCode Generator</title>
</head>

<body onload="update_qrcode()">
  <h1>PayMe PayCode Generator</h1>
  <form name="qrForm" onsubmit="update_qrcode();return false;">
    <span>TypeNumber:</span>
    <select name="t" onchange="update_qrcode()">
      <option value="0" selected="selected">Auto Detect</option>
      <option value="1">1</option>
      <option value="2">2</option>
      <option value="3">3</option>
      <option value="4">4</option>
      <option value="5">5</option>
      <option value="6">6</option>
      <option value="7">7</option>
      <option value="8">8</option>
      <option value="9">9</option>
      <option value="10">10</option>
      <option value="11">11</option>
      <option value="12">12</option>
      <option value="13">13</option>
      <option value="14">14</option>
      <option value="15">15</option>
      <option value="40">40</option>
    </select>
    <span>ErrorCorrectionLevel:</span>
    <select name="e" onchange="update_qrcode()">
      <option value="L" selected="selected">L(7%)</option>
      <option value="M">M(15%)</option>
      <option value="Q">Q(25%)</option>
      <option value="H">H(30%)</option>
    </select>
    <span>Size:</span>
    <input name="size" type="text" value="344" />
    <br />
    <span>Logo:</span>
    <input type="file" onchange="encodeImageFileAsURL(this)" />
    <span>
      <img id="logo" width="30" height="30" src="{{ $logo }}" onload="update_qrcode()">
    </span>
    <input type="checkbox" name="consumer" value="consumer" onchange="update_qrcode()"> PayMe Style
    <br>
    <p />
    <textarea name="msg" rows="2" cols="86">{{ $link }}</textarea>
    <p />
    <input class="update-btn" type="button" value="Update" onclick="update_qrcode()" />
    <div id="qr"></div>
    <canvas id="payCodeCanvas" width="344" height="344"></canvas>
  </form>

  <footer>
    &#169; PayMe from HSBC | <a href="https://payme.hsbc.com.hk/terms-and-conditions" rel="noopener noreferrer" target="blank">Terms &amp; Conditions</a>
    | <a href="https://payme.hsbc.com.hk/pws-terms" rel="noopener noreferrer" target="blank">Website Terms of Use</a>
    | <a href="https://payme.hsbc.com.hk/pws-privacy-security" rel="noopener noreferrer" target="blank">Privacy and Security</a>
    <br>
    SVF License Number: SVFB002
  </footer>
</body>

</html>
































<!-- <!DOCTYPE html>
<html>
<body>
<style>
    #hide{
    display: none;
}
</style>
<h2>HTML Forms</h2>

<form action="">
  <label for="fname">First name:</label><br>
  <input type="file" id="imageInput" name="fname" value=""><br>

  <input type="button" value="Submit" onclick="callingFunction()">
</form> 


<div class="hide" id="hide">
    hii
</div>
<label for="lname">Last name:</label><br>
  <input type="file" id="lname" name="lname" value="Doe"><br><br>
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script> 
<script>
    // $(document).ready(function(){
    //     document.getElementById("hide").onclick = function() { 
    //         document.getElementById("hide").style.display = "none"; 
    //     } 
    // });
    // function callingFunction() {

    //     console.log("hello");
    //     const imageInput = document.getElementById('imageInput');
    //     const imageUrl = imageInput.files;
    //     console.log(imageInput);
    //     console.log(imageUrl);
    //     document.getElementById("hide").innerHTML +=imageInput;

    //     // console.log(document.getElementById('imageInput').value);
    // }
 
</script>
</body>
</html> -->