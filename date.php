<!DOCTYPE html>
<html>
<head>
	<title>Date</title>
	  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>jQuery UI Datepicker - Display month &amp; year menus</title>
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="/resources/demos/style.css">
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script type="text/javascript">
    $('#dob').datepicker({
    onSelect: function(value, ui) {
        var today = new Date(), 
            dob = new Date(value), 
            age = new Date(today - dob).getFullYear() - 1970;
        
        $('#age').text(age);
    },
    maxDate: '+0d',
    yearRange: '1920:2010',
    changeMonth: true,
   
  </script>
</head>
<body>
<form>
            DOB (mm/dd/yyyy):
            <input type="text" id="dob">
            Age:
            <input id="age" type="text" value="">
        </form>
</body>
</html>